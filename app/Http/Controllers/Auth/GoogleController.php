<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Kalau ketemu "state mismatch", pakai ->stateless() di kedua method.
        return Socialite::driver('google')->redirect();
        // return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        if (!$googleUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Your Google account has no email.');
        }

        // Temukan atau buat user baru
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'              => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User',
                'email'             => $googleUser->getEmail(),
                'password'          => Hash::make(Str::random(40)), // random, tidak dipakai
                'role'              => 'user',
                'is_active'         => true,
                'email_verified_at' => now(), // kamu minta verifikasi email dimatikan
            ]);
        } else {
            if (!$user->is_active) {
                return redirect()->route('login')->with('error', 'Your account is inactive.');
            }
            // optional: isi nama kalau kosong
            if (!$user->name && $googleUser->getName()) {
                $user->name = $googleUser->getName();
                $user->save();
            }
        }

        Auth::login($user, true);

        // Sesuai permintaanmu: semua login redirect ke home
        return redirect()->route('home');
    }
}
