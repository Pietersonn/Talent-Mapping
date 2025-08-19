<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact administrator.');
        }

        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            // Redirect based on user's actual role
            switch ($user->role) {
                case 'admin':
                case 'staff':
                    return redirect()->route('admin.dashboard');
                case 'pic':
                    return redirect()->route('pic.dashboard');
                case 'user':
                default:
                    return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
