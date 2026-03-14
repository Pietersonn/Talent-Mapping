<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MitraMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        if (!$user->aktif) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }
        if ($user->peran !== 'mitra') {
            return match ($user->peran) {
                'admin'   => redirect()->route('admin.dashboard')->with('error', 'Akses mitra diperlukan.'),
                'peserta' => redirect()->route('home')->with('error', 'Akses mitra diperlukan.'),
                default   => redirect()->route('home'),
            };
        }
        return $next($request);
    }
}
