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
            'test_result_id' => ['required', 'string', 'exists:hasil_tes,id'], // Pastikan tabelnya hasil_tes
            'note'           => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return back()->with('error', 'Kamu harus login.');
        }

        // Pastikan result memang milik user ini
        $result = TestResult::with('testSession:id_pengguna,id')
            ->where('id', $request->input('test_result_id'))
            ->firstOrFail();

        if (!$result->testSession || $result->testSession->id_pengguna !== $user->id) {
            return back()->with('error', 'Kamu tidak berhak meminta resend untuk hasil ini.');
        }

        // Cegah duplikat pending untuk result yang sama
        $alreadyPending = ResendRequest::where('id_hasil_tes', $result->id)
            ->where('id_pengguna', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('warning', 'Kamu sudah memiliki request resend yang masih pending untuk hasil ini.');
        }

        // Buat request baru
        $rr = new ResendRequest();
        $rr->id                 = $rr->generateCustomId(); // Trait HasCustomId
        $rr->id_pengguna        = $user->id;
        $rr->id_hasil_tes       = $result->id;
        $rr->tanggal_permintaan = now();
        $rr->status             = 'pending';
        $rr->catatan_admin      = $request->input('note');
        $rr->save();

        return back()->with('success', 'Request resend berhasil dikirim.');
    }
}
