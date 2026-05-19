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
    public function index(Request $request)
    {
        $pendingRequests = ResendRequest::with(['user', 'testResult.testSession.program'])
            ->where('status', 'pending')
            ->orderBy('tanggal_permintaan', 'asc')
            ->get();

        $historyQuery = ResendRequest::with(['user', 'approvedBy', 'testResult.testSession.program'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $historyQuery->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"))
                  ->orWhereHas('testResult.testSession.program', fn($p) => $p->where('nama', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('date_from')) $historyQuery->whereDate('tanggal_permintaan', '>=', $request->date_from);
        if ($request->filled('date_to'))   $historyQuery->whereDate('tanggal_permintaan', '<=', $request->date_to);

        $historyRequests = $historyQuery->paginate(10)->withQueryString();

        if ($request->ajax()) {
            $formattedData = $historyRequests->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'user_name'    => $item->user->nama ?? $item->user->name ?? '-',
                    'user_email'   => $item->user->email ?? '-',
                    'event_name'   => $item->testResult?->testSession?->program?->nama ?? '-',
                    'date_dmy'     => $item->tanggal_permintaan->format('d M Y'),
                    'date_hi'      => $item->tanggal_permintaan->format('H:i'),
                    'status'       => $item->status,
                    // PERBAIKAN UTAMA: Fallback pengecekan nama / name agar AJAX tidak mengirim string kosong
                    'processor'    => $item->approvedBy?->nama ?? $item->approvedBy?->name ?? '-',
                    'processed_at' => $item->updated_at ? $item->updated_at->locale('id')->diffForHumans() : '',
                ];
            });

            return response()->json(['data' => $formattedData, 'links' => (string)$historyRequests->links()]);
        }

        $stats = [
            'total'    => ResendRequest::count(),
            'pending'  => $pendingRequests->count(),
            'approved' => ResendRequest::where('status', 'approved')->count(),
            'rejected' => ResendRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.resend.index', compact('pendingRequests', 'historyRequests', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $query = ResendRequest::with(['user', 'approvedBy', 'testResult.testSession.program'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"))
                  ->orWhereHas('testResult.testSession.program', fn($p) => $p->where('nama', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('date_from')) $query->whereDate('tanggal_permintaan', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('tanggal_permintaan', '<=', $request->date_to);

        $pdf = Pdf::loadView('admin.resend.pdf.resendReport', [
            'rows'        => $query->get(),
            'reportTitle' => 'Laporan Riwayat Permintaan Kirim Ulang',
            'generatedBy' => Auth::user()->nama ?? Auth::user()->name,
            'generatedAt' => now('Asia/Makassar')->format('d M Y H:i').' WITA',
            'dateFrom'    => $request->date_from,
            'dateTo'      => $request->date_to,
        ])->setPaper('a4', 'landscape')->setOptions(['isRemoteEnabled' => true]);

        return $pdf->stream('laporan-resend-history.pdf');
    }

    public function show(ResendRequest $resendRequest): View
    {
        $resendRequest->load(['user', 'testResult.testSession.program', 'testResult.dominantTypologyDescription', 'approvedBy']);
        return view('admin.resend.show', compact('resendRequest'));
    }

    public function approve(Request $request, ResendRequest $resendRequest): RedirectResponse
    {
        if ($resendRequest->status !== 'pending') return back()->with('error', 'Permintaan sudah diproses sebelumnya.');
        $request->validate(['catatan_admin' => 'nullable|string|max:500']);

        try {
            DB::beginTransaction();
            $resendRequest->update([
                'status'         => 'approved',
                'disetujui_oleh' => Auth::id(),
                'disetujui_pada' => now(),
                'catatan_admin'  => $request->catatan_admin,
            ]);

            $testResult = $resendRequest->testResult;
            $user = $resendRequest->user;

            if (empty($testResult->pdf_path) || !Storage::disk('local')->exists($testResult->pdf_path)) {
                if ($testResult->id_sesi) {
                    \App\Jobs\GenerateAssessmentReport::dispatchSync($testResult->id_sesi);
                    $testResult->refresh();
                }
            }

            if (!empty($testResult->pdf_path) && Storage::disk('local')->exists($testResult->pdf_path)) {
                $pdfPath = Storage::disk('local')->path($testResult->pdf_path);

                $recipientName = $user->nama ?? $user->name;
                Mail::raw("Halo {$recipientName},\n\nSesuai permintaan Anda, berikut kami lampirkan kembali hasil Talent Assessment Anda.\n\nTerima kasih.", function ($m) use ($user, $pdfPath, $recipientName) {
                    $m->to($user->email, $recipientName)->subject('Hasil Talent Assessment - Kirim Ulang')->attach($pdfPath);
                });

                $testResult->update(['email_sent_at' => now()]);
            }
            DB::commit();
            return back()->with('success', "Permintaan disetujui, email berhasil dikirim ke {$user->email}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: '.$e->getMessage());
        }
    }

    public function reject(Request $request, ResendRequest $resendRequest): RedirectResponse
    {
        if ($resendRequest->status !== 'pending') return back()->with('error', 'Permintaan sudah diproses sebelumnya.');
        $request->validate(['alasan_penolakan' => 'required|string|max:500']);

        try {
            $resendRequest->update([
                'status'           => 'rejected',
                'disetujui_oleh'   => Auth::id(),
                'disetujui_pada'   => now(),
                'alasan_penolakan' => $request->alasan_penolakan,
                'catatan_admin'    => $request->catatan_admin,
            ]);

            $user = $resendRequest->user;
            $recipientName = $user->nama ?? $user->name;

            Mail::raw("Halo {$recipientName},\n\nMohon maaf, permintaan kirim ulang hasil assessment Anda ditolak.\nAlasan: {$request->alasan_penolakan}", function ($m) use ($user) {
                $m->to($user->email)->subject('Permintaan Kirim Ulang Ditolak');
            });

            return back()->with('success', 'Permintaan berhasil ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses: '.$e->getMessage());
        }
    }

    public function cleanup(): RedirectResponse
    {
        ResendRequest::whereIn('status', ['approved', 'rejected'])->where('updated_at', '<', now()->subMonths(3))->delete();
        return back()->with('success', 'Data lama berhasil dibersihkan.');
    }
}
