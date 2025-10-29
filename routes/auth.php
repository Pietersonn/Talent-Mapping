<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\GoogleController;

Route::middleware('guest')->group(function () {
    // Register
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    // Google OAuth
    Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('login.google.redirect');
    Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('login.google.callback');
});

Route::middleware('auth')->group(function () {
    // Confirm password (optional)
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update password
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Email Verification STUBS (NONAKTIF)
    |--------------------------------------------------------------------------
    | Kamu tidak pakai verifikasi email, tapi beberapa view masih memanggil
    | route('verification.send') / notice / verify. Tiga route di bawah ini
    | dibuat NO-OP supaya tidak error.
    */

    Route::get('/email/verify', function () {
        return redirect()->route('home'); // atau: return redirect('/');
    })->name('verification.notice');

    // Verify link → juga langsung lempar ke home
    Route::get('/email/verify/{id}/{hash}', function () {
        return redirect()->route('home'); // atau: return redirect('/');
    })->name('verification.verify');

    // Resend verification email → no-op + flash message
    Route::post('/email/verification-notification', function () {
        return back()->with('success', 'Email verification is disabled.');
    })->name('verification.send');
});
