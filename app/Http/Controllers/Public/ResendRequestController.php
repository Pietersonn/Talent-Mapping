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
            'test_result_id' => ['required', 'string', 'exists:hasil_tes,id'], // test_results -> hasil_tes
            'note'           => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return back()->with('error', 'Kamu harus login.');
        }

        // Pastikan result memang milik user ini (via TestSession.id_pengguna)
        $result = TestResult::with('testSession:id_pengguna,id')
            ->where('id', $request->input('test_result_id'))
            ->firstOrFail();

        if (!$result->testSession || $result->testSession->id_pengguna !== $user->id) { // user_id -> id_pengguna
            return back()->with('error', 'Kamu tidak berhak meminta resend untuk hasil ini.');
        }

        // Cegah duplikat pending untuk result yang sama
        $alreadyPending = ResendRequest::where('id_hasil_tes', $result->id) // test_result_id -> id_hasil_tes
            ->where('id_pengguna', $user->id) // user_id -> id_pengguna
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('warning', 'Kamu sudah memiliki request resend yang masih pending untuk hasil ini.');
        }

        // Buat request
        $rr = new ResendRequest();
        $rr->id                 = $rr->generateCustomId();     // TRait HasCustomId
        $rr->id_pengguna        = $user->id; // user_id -> id_pengguna
        $rr->id_hasil_tes       = $result->id; // test_result_id -> id_hasil_tes
        $rr->tanggal_permintaan = now(); // request_date -> tanggal_permintaan
        $rr->status             = 'pending';
        $rr->catatan            = $request->input('note'); // admin_notes -> catatan
        $rr->save();

        // UX: kembali ke halaman sebelumnya (modal tetap muncul saat reload karena dipanggil dari popup)
        return back()->with('success', 'Request resend berhasil dikirim.');
    }
}
