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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();

        // PERBAIKAN: Gunakan 'peserta'
        if ($user->peran === 'admin' || $user->peran === 'staff') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($user->peran === 'mitra') {
            return redirect()->intended(route('mitra.dashboard', absolute: false));
        } elseif ($user->peran === 'peserta') {
            return redirect()->intended(route('home', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
