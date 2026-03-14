<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google gagal. Silakan coba lagi.');
        }

        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            // Jika belum punya google_id, simpan
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }
            // Cek akun aktif
            if (!$user->aktif) {
                return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
            }
        } else {
            // Buat akun baru via Google
            $user = User::create([
                'nama'              => $googleUser->name,
                'email'             => $googleUser->email,
                'google_id'         => $googleUser->id,
                'peran'             => 'peserta',
                'aktif'             => true,
                'email_verified_at' => now(),
                'password'          => null,
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
