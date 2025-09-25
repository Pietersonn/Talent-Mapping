<?php

use Illuminate\Support\Facades\Route;

// PUBLIC
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\GuideController;

// PROFILE (semua user login)
use App\Http\Controllers\ProfileController;

// --------------------
// PUBLIC ROUTES
// --------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/panduan-hasil', [GuideController::class, 'index'])->name('guide');

// Auth scaffolding (login, register, dll)
require __DIR__.'/auth.php';


// --------------------
// SPLIT ROUTE FILES
// --------------------
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/pic.php';
