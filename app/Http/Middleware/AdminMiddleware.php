<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        if (!$user->aktif) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }
        if ($user->peran !== 'admin') {
            return match ($user->peran) {
                'mitra'   => redirect()->route('mitra.dashboard')->with('error', 'Akses admin diperlukan.'),
                'peserta' => redirect()->route('home')->with('error', 'Akses admin diperlukan.'),
                default   => redirect()->route('home'),
            };
        }
        return $next($request);
    }
}
