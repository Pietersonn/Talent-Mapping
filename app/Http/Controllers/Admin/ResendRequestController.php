<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResendRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ResendRequestController extends Controller
{
    /**
     * Menampilkan daftar permintaan kirim ulang.
     */
    public function index(Request $request)
    {
        // 1. Data untuk KARTU (Hanya Pending)
        // Eager load event untuk performa
        $pendingRequests = ResendRequest::with(['user', 'testResult.testSession.event'])
            ->where('status', 'pending')
            ->orderBy('request_date', 'asc')
            ->get();

        // 2. Data untuk TABEL & PENCARIAN (History: Approved/Rejected)
        $historyQuery = ResendRequest::with(['user', 'approvedBy', 'testResult.testSession.event'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc');

        // Logic Filter / Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $historyQuery->where(function($q) use ($search) {
                // Cari berdasarkan User (Nama/Email)
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                })
                // ATAU Cari berdasarkan Nama Event
                ->orWhereHas('testResult.testSession.event', function ($e) use ($search) {
                    $e->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $historyQuery->whereDate('request_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $historyQuery->whereDate('request_date', '<=', $request->date_to);
        }

        $historyRequests = $historyQuery->paginate(10)->withQueryString();

        // --- AJAX RESPONSE (Untuk Pencarian Realtime JS) ---
        if ($request->ajax()) {
            $formattedData = $historyRequests->map(function ($item) {
                // Ambil judul event (pastikan accessor di model TestResult sudah ada, atau ambil manual)
                $eventTitle = $item->testResult->event_title ?? ($item->testResult->testSession->event->name ?? '-');

                return [
                    'id' => $item->id,
                    'user_name' => $item->user->name ?? '-',
                    'user_email' => $item->user->email ?? '-',
                    'event_name' => $eventTitle, // Data Event untuk JS
                    'date_dmy' => $item->request_date->format('d M Y'),
                    'date_hi' => $item->request_date->format('H:i'),
                    'status' => $item->status,
                    'processor' => $item->approvedBy->name ?? '-',
                    'processed_at' => $item->approved_at ? $item->approved_at->diffForHumans() : '',
                ];
            });

            return response()->json([
                'data' => $formattedData,
                'links' => (string) $historyRequests->links()
            ]);
        }

        // Statistik
        $stats = [
            'total'    => ResendRequest::count(),
            'pending'  => $pendingRequests->count(),
            'approved' => ResendRequest::where('status', 'approved')->count(),
            'rejected' => ResendRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.resend.index', compact('pendingRequests', 'historyRequests', 'stats'));
    }

    /**
     * Export History ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = ResendRequest::with(['user', 'approvedBy', 'testResult.testSession.event'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc');

        // Logic Filter yang SAMA PERSIS dengan index agar hasil print sesuai pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('testResult.testSession.event', function ($e) use ($search) {
                    $e->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('request_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('request_date', '<=', $request->date_to);
        }

        $rows = $query->get();

        $data = [
            'rows'        => $rows,
            'reportTitle' => 'Laporan Riwayat Permintaan Kirim Ulang',
            'generatedBy' => Auth::user()->name ?? 'Admin',
            'generatedAt' => now('Asia/Makassar')->format('d M Y H:i') . ' WITA',
            'dateFrom'    => $request->date_from,
            'dateTo'      => $request->date_to,
        ];

        $pdf = Pdf::loadView('admin.resend.pdf.resendReport', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->stream('laporan-resend-history.pdf');
    }

    public function show(ResendRequest $resendRequest): View
    {
        $resendRequest->load(['user','testResult.testSession.event','testResult.dominantTypologyDescription','approvedBy']);
        return view('admin.resend.show', compact('resendRequest'));
    }

    public function approve(Request $request, ResendRequest $resendRequest): RedirectResponse
    {
        if ($resendRequest->status !== 'pending') return redirect()->back()->with('error', 'Permintaan sudah diproses sebelumnya.');
        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        try {
            DB::beginTransaction();
            $resendRequest->update([
                'status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now(), 'admin_notes' => $request->admin_notes
            ]);

            $testResult = $resendRequest->testResult;
            $user = $resendRequest->user;

            // Generate PDF jika belum ada
            if (empty($testResult->pdf_path) || !Storage::disk('local')->exists($testResult->pdf_path)) {
                if ($testResult->session_id) {
                    \App\Jobs\GenerateAssessmentReport::dispatchSync($testResult->session_id);
                    $testResult->refresh();
                }
            }

            // Kirim Email
            if (!empty($testResult->pdf_path) && Storage::disk('local')->exists($testResult->pdf_path)) {
                $pdfPath = Storage::disk('local')->path($testResult->pdf_path);
                Mail::raw("Halo {$user->name},\n\nSesuai permintaan Anda, berikut kami lampirkan kembali hasil Talent Assessment Anda.\n\nTerima kasih.", function ($m) use ($user, $pdfPath) {
                    $m->to($user->email, $user->name)->subject('Hasil Talent Assessment - Kirim Ulang')->attach($pdfPath);
                });
                $testResult->update(['email_sent_at' => now()]);
            }
            DB::commit();
            return redirect()->back()->with('success', "Permintaan disetujui, email berhasil dikirim ke {$user->email}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, ResendRequest $resendRequest): RedirectResponse
    {
        if ($resendRequest->status !== 'pending') return redirect()->back()->with('error', 'Permintaan sudah diproses sebelumnya.');
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        try {
            $resendRequest->update([
                'status' => 'rejected', 'approved_by' => Auth::id(), 'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason, 'admin_notes' => $request->admin_notes
            ]);

            $user = $resendRequest->user;
            Mail::raw("Halo {$user->name},\n\nMohon maaf, permintaan kirim ulang hasil assessment Anda ditolak.\nAlasan: {$request->rejection_reason}", function ($m) use ($user) {
                $m->to($user->email)->subject('Permintaan Kirim Ulang Ditolak');
            });

            return redirect()->back()->with('success', 'Permintaan berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function cleanup(): RedirectResponse
    {
        ResendRequest::whereIn('status', ['approved','rejected'])->where('updated_at', '<', now()->subMonths(3))->delete();
        return redirect()->back()->with('success', 'Data lama berhasil dibersihkan.');
    }
}
