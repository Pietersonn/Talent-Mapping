<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{

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

        // Check if user is staff or admin (staff can access admin areas with read-only)
        if (!in_array($user->role, ['staff', 'admin'])) {
            // Redirect based on user's actual role
            switch ($user->role) {
                case 'pic':
                    return redirect()->route('pic.dashboard')->with('error', 'Staff access required.');
                case 'user':
                default:
                    return redirect()->route('home')->with('error', 'Staff access required.');
            }
        }

        return $next($request);
    }
}
