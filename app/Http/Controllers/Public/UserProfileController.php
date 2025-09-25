<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\TestResult;
use App\Models\ResendRequest;

class ProfileController extends Controller
{
    /**
     * (Opsional) Halaman penuh /my-profile.
     * Tidak wajib dipakai karena kita pakai popup, tapi biar route existing tidak error.
     */
    public function index(): View
    {
        $authId = Auth::id();
        $user = User::with([
            'testSessions'  => fn($q) => $q->latest(),
            'testResults'   => fn($q) => $q->latest('report_generated_at'),
            'events',
            'picEvents',
            'resendRequests' => fn($q) => $q->latest('request_date'),
        ])->findOrFail($authId);

        // Kalau kamu ingin, ini bisa render page penuh; untuk sekarang pakai popup.
        return view('public.profile.index', compact('user'));
    }

    /**
     * Submit permintaan kirim ulang (pakai route existing: POST /resend-request).
     */
    public function requestResend(Request $request): RedirectResponse
    {
        $request->validate([
            'test_result_id' => ['required', 'string', 'exists:test_results,id'],
            'note'           => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return back()->with('error', 'Kamu harus login.');
        }

        // Pastikan hasil memang milik user ini (via testSession.user_id)
        $result = TestResult::with('testSession:user_id,id')->findOrFail($request->input('test_result_id'));
        if (!$result->testSession || $result->testSession->user_id !== $user->id) {
            return back()->with('error', 'Kamu tidak berhak meminta resend untuk hasil ini.');
        }

        // Cegah duplikat pending
        $alreadyPending = ResendRequest::where('test_result_id', $result->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('warning', 'Kamu sudah memiliki request resend yang masih pending untuk hasil ini.');
        }

        // Buat RR (pakai trait HasCustomId)
        $rr = new ResendRequest();
        $rr->id             = $rr->generateCustomId();
        $rr->user_id        = $user->id;
        $rr->test_result_id = $result->id;
        $rr->request_date   = now();
        $rr->status         = 'pending';
        $rr->admin_notes    = $request->input('note'); // catatan opsional user
        $rr->save();

        return back()->with('success', 'Request resend berhasil dikirim. Mohon tunggu persetujuan.');
    }
}
