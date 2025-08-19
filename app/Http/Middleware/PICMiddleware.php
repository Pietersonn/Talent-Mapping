<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PICMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        // Check if user is PIC
        if ($user->role !== 'pic') {
            // Redirect based on user's actual role
            switch ($user->role) {
                case 'admin':
                case 'staff':
                    return redirect()->route('admin.dashboard')->with('error', 'PIC access required.');
                case 'user':
                default:
                    return redirect()->route('home')->with('error', 'PIC access required.');
            }
        }

        return $next($request);
    }
}
