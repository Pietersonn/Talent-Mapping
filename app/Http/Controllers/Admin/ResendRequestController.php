<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResendRequest;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ResendRequestController extends Controller
{
    /**
     * Display a listing of resend requests
     */
    public function index(Request $request): View
    {
        $query = ResendRequest::with(['user', 'testResult.testSession', 'approvedBy'])
            ->orderBy('request_date', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('request_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('request_date', '<=', $request->date_to);
        }

        $requests = $query->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_requests' => ResendRequest::count(),
            'pending' => ResendRequest::where('status', 'pending')->count(),
            'approved' => ResendRequest::where('status', 'approved')->count(),
            'rejected' => ResendRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.resend.index', compact('requests', 'stats'));
    }

    /**
     * Display the specified resend request
     */
    public function show(ResendRequest $resendRequest): View
    {
        $resendRequest->load([
            'user',
            'testResult.testSession.event',
            'testResult.dominantTypologyDescription',
            'approvedBy'
        ]);

        return view('admin.resend.show', compact('resendRequest'));
    }

    /**
     * Approve resend request
     */
    public function approve(Request $request, ResendRequest $resendRequest): RedirectResponse
    {
        // Check if already processed
        if ($resendRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Request sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Update request status
            $resendRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes
            ]);

            // Send email with PDF attachment
            $testResult = $resendRequest->testResult;
            $user = $resendRequest->user;

            // Ensure PDF exists; if missing, regenerate synchronously
            if (empty($testResult->pdf_path) || !Storage::disk('local')->exists($testResult->pdf_path)) {
                if ($testResult->session_id) {
                    \App\Jobs\GenerateAssessmentReport::dispatchSync($testResult->session_id);
                    // refresh path after regeneration
                    $testResult->refresh();
                }
            }

            if (!empty($testResult->pdf_path) && Storage::disk('local')->exists($testResult->pdf_path)) {
                $pdfPath = Storage::disk('local')->path($testResult->pdf_path);

                Mail::raw(
                    "Halo {$user->name},\n\nSesuai dengan permintaan Anda, berikut hasil Talent Assessment Anda terlampir.\n\nTerima kasih.\n\n-- Tim Talent Mapping",
                    function ($message) use ($user, $pdfPath) {
                        $message->to($user->email, $user->name)
                            ->subject('Hasil Talent Assessment - Resend')
                            ->attach($pdfPath);
                    }
                );

                // Update email sent timestamp
                $testResult->update(['email_sent_at' => now()]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', "Request disetujui dan email berhasil dikirim ke {$user->email}");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal memproses request: ' . $e->getMessage());
        }
    }

    /**
     * Reject resend request
     */
    public function reject(Request $request, ResendRequest $resendRequest): RedirectResponse
    {
        // Check if already processed
        if ($resendRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Request sudah diproses sebelumnya.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            $resendRequest->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
                'admin_notes' => $request->admin_notes
            ]);

            // Send rejection email to user
            $user = $resendRequest->user;
            Mail::raw(
                "Halo {$user->name},\n\nMohon maaf, permintaan resend hasil assessment Anda tidak dapat diproses.\n\nAlasan: {$request->rejection_reason}\n\nJika ada pertanyaan, silakan hubungi admin.\n\nTerima kasih.\n\n-- Tim Talent Mapping",
                function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('Permintaan Resend Hasil Assessment');
                }
            );

            return redirect()->back()
                ->with('success', 'Request berhasil ditolak.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses request: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve requests
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_requests' => 'required|array|min:1',
            'selected_requests.*' => 'exists:resend_requests,id',
            'bulk_admin_notes' => 'nullable|string|max:500'
        ]);

        $requests = ResendRequest::with(['user', 'testResult'])
            ->whereIn('id', $request->selected_requests)
            ->where('status', 'pending')
            ->get();

        if ($requests->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada request pending yang dipilih.');
        }

        $approved = 0;
        $failed = 0;

        foreach ($requests as $resendRequest) {
            try {
                DB::beginTransaction();

                // Update request status
                $resendRequest->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'admin_notes' => $request->bulk_admin_notes
                ]);

                $testResult = $resendRequest->testResult;
                $user = $resendRequest->user;

                // Ensure PDF exists; if missing, regenerate synchronously
                if (empty($testResult->pdf_path) || !Storage::disk('local')->exists($testResult->pdf_path)) {
                    if ($testResult->session_id) {
                        \App\Jobs\GenerateAssessmentReport::dispatchSync($testResult->session_id);
                        $testResult->refresh();
                    }
                }

                if (!empty($testResult->pdf_path) && Storage::disk('local')->exists($testResult->pdf_path)) {
                    $pdfPath = Storage::disk('local')->path($testResult->pdf_path);

                    Mail::raw(
                        "Halo {$user->name},\n\nSesuai dengan permintaan Anda, berikut hasil Talent Assessment Anda terlampir.\n\nTerima kasih.\n\n-- Tim Talent Mapping",
                        function ($message) use ($user, $pdfPath) {
                            $message->to($user->email, $user->name)
                                ->subject('Hasil Talent Assessment - Resend')
                                ->attach($pdfPath);
                        }
                    );

                    // Update email sent timestamp
                    $testResult->update(['email_sent_at' => now()]);

                    DB::commit();
                    $approved++;
                } else {
                    DB::rollBack();
                    $failed++;
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                continue;
            }
        }

        $message = "Berhasil approve {$approved} request";
        if ($failed > 0) {
            $message .= ", {$failed} gagal diproses";
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk reject requests
     */
    public function bulkReject(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_requests' => 'required|array|min:1',
            'selected_requests.*' => 'exists:resend_requests,id',
            'bulk_rejection_reason' => 'required|string|max:500',
            'bulk_admin_notes' => 'nullable|string|max:500'
        ]);

        $requests = ResendRequest::whereIn('id', $request->selected_requests)
            ->where('status', 'pending')
            ->get();

        if ($requests->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada request pending yang dipilih.');
        }

        foreach ($requests as $resendRequest) {
            try {
                $resendRequest->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'rejection_reason' => $request->bulk_rejection_reason,
                    'admin_notes' => $request->bulk_admin_notes
                ]);

                // Send rejection email
                $user = $resendRequest->user;
                Mail::raw(
                    "Halo {$user->name},\n\nMohon maaf, permintaan resend hasil assessment Anda tidak dapat diproses.\n\nAlasan: {$request->bulk_rejection_reason}\n\nJika ada pertanyaan, silakan hubungi admin.\n\nTerima kasih.\n\n-- Tim Talent Mapping",
                    function ($message) use ($user) {
                        $message->to($user->email, $user->name)
                            ->subject('Permintaan Resend Hasil Assessment');
                    }
                );

            } catch (\Exception $e) {
                // continue
            }
        }

        return redirect()->back()->with('success', 'Berhasil reject request terpilih.');
    }

    /**
     * Cleanup processed requests older than 3 months
     */
    public function cleanup(): RedirectResponse
    {
        $cut = now()->subMonths(3);

        $deleted = ResendRequest::whereIn('status', ['approved', 'rejected'])
            ->where(function ($q) use ($cut) {
                $q->where('updated_at', '<', $cut)
                  ->orWhere('request_date', '<', $cut);
            })
            ->delete();

        return redirect()->back()->with('success', "Deleted {$deleted} old requests.");
    }
}
