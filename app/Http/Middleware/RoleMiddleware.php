<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->aktif) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        if (!in_array($user->peran, $roles)) {
            return match ($user->peran) {
                'admin'   => redirect()->route('admin.dashboard'),
                'mitra'   => redirect()->route('pic.dashboard'),
                'peserta' => redirect()->route('home'),
                default   => redirect()->route('home'),
            };
        }

        return $next($request);
    }
}
