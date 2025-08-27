<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * ALL USERS REDIRECT TO HOME AFTER LOGIN
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Check email verification using database column
        if (is_null($user->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email to access all features.');
        }

        // ALL ROLES REDIRECT TO HOME (NOT DASHBOARD)
        return redirect()->intended(route('home'))
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Destroy an authenticated session.
     * LOGOUT ALWAYS REDIRECT TO HOME
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
