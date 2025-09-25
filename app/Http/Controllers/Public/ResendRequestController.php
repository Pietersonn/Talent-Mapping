<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TestResult;
use App\Models\ResendRequest;

class ResendRequestController extends Controller
{
    /**
     * Simpan permintaan kirim ulang hasil (Resend Request) dari popup.
     */
    public function store(Request $request)
    {
        $request->validate([
            'test_result_id' => ['required', 'string', 'exists:test_results,id'],
            'note'           => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return back()->with('error', 'Kamu harus login.');
        }

        // Pastikan result memang milik user ini (via TestSession.user_id)
        $result = TestResult::with('testSession:user_id,id')
            ->where('id', $request->input('test_result_id'))
            ->firstOrFail();

        if (!$result->testSession || $result->testSession->user_id !== $user->id) {
            return back()->with('error', 'Kamu tidak berhak meminta resend untuk hasil ini.');
        }

        // Cegah duplikat pending untuk result yang sama
        $alreadyPending = ResendRequest::where('test_result_id', $result->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('warning', 'Kamu sudah memiliki request resend yang masih pending untuk hasil ini.');
        }

        // Buat request
        $rr = new ResendRequest();
        $rr->id             = $rr->generateCustomId();     // TRait HasCustomId
        $rr->user_id        = $user->id;
        $rr->test_result_id = $result->id;
        $rr->request_date   = now();
        $rr->status         = 'pending';
        $rr->admin_notes    = $request->input('note');     // catatan opsional dari user
        $rr->save();

        // UX: kembali ke halaman sebelumnya (modal tetap muncul saat reload karena dipanggil dari popup)
        return back()->with('success', 'Request resend berhasil dikirim. Mohon tunggu persetujuan.');
    }
}
