<?php

use Illuminate\Support\Facades\Route;

// PUBLIC
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\GuideController;
use Illuminate\Support\Facades\Mail;

// PROFILE (semua user login)
use App\Http\Controllers\ProfileController;

// --------------------
// PUBLIC ROUTES
// --------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/panduan-hasil', [GuideController::class, 'index'])->name('guide');

Route::get('/test-email', function () {
    Mail::raw('Tes kirim lewat SendGrid API', function ($m) {
        $m->to('alamatmu@gmail.com')->subject('Test Kirim SendGrid API');
    });
    return '✅ Email test sudah dikirim (cek inbox atau spam folder)';
});

// Auth scaffolding (login, register, dll)
require __DIR__.'/auth.php';


// --------------------
// SPLIT ROUTE FILES
// --------------------
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/pic.php';
