<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\TestResult;
use App\Models\ResendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $results = TestResult::with('testSession.program')
            ->whereHas('testSession', function ($query) use ($user) {
                $query->where('id_pengguna', $user->id)
                      ->where('selesai', true);
            })
            ->latest('created_at')
            ->get();

        // Ambil riwayat permintaan kirim ulang sesuai struktur database baru
        $resendRequests = ResendRequest::where('id_pengguna', $user->id)
            ->latest('tanggal_permintaan')
            ->get();

        // Kirim $results dan $resendRequests ke view
        return view('public.profile.index', compact('user', 'results', 'resendRequests'));
    }

    public function edit(): View
    {
        return view('public.profile.edit', ['user' => Auth::user()]);
    }
}
