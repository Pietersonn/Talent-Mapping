<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // PERBAIKAN: Gunakan field 'peran', BUKAN 'role'
        if ($user->peran !== $role) {

            // Jika peran tidak sesuai, kembalikan ke dashboard masing-masing
            if ($user->peran === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->peran === 'mitra') {
                return redirect('/mitra/dashboard');
            }

            return redirect('/');
        }

        return $next($request);
    }
}
