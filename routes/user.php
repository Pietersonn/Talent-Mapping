<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\TestController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\ResendRequestController;

// PERBAIKAN MUTLAK: 'role:peserta' (Karena di DB perannya adalah peserta)
Route::middleware(['auth', 'role:peserta'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::post('/profile/resend-request', [ResendRequestController::class, 'store'])
        ->name('profile.resend.request');

    Route::get('/my-profile', function () {
        return redirect()->route('profile');
    })->name('profile.index');
});

// TEST FLOW (Akses untuk semua peran yang login)
Route::middleware(['auth'])->prefix('test')->name('test.')->group(function () {
    Route::get('/form', [TestController::class, 'form'])->name('form');
    Route::post('/form', [TestController::class, 'storeForm'])->name('form.store');

    Route::prefix('st30')->name('st30.')->group(function () {
        Route::get('/stage/{stage}', [TestController::class, 'st30Stage'])->name('stage');
        Route::post('/stage/{stage}', [TestController::class, 'storeST30Stage'])->name('stage.store');
    });

    Route::prefix('tk')->name('tk.')->group(function () {
        Route::get('/page/{page}', [TestController::class, 'tkPage'])->name('page');
        Route::post('/page/{page}', [TestController::class, 'storeTKPage'])->name('page.store');
    });

    Route::get('/thank-you', [TestController::class, 'thankYou'])->name('thank-you');
    Route::post('/complete', [TestController::class, 'complete'])->name('complete');
});
