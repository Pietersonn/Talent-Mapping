<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\TestController;
use App\Http\Controllers\Public\ProfileController;

// PROFILE khusus role:user
Route::middleware(['auth', 'role:user'])->group(function () {
    // PROFILE (path: /profile)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/resend-request', [ProfileController::class, 'requestResend'])
        ->name('profile.resend.request');

    // (Opsional) alias lama /my-profile -> redirect ke /profile
    Route::get('/my-profile', function () {
        return redirect()->route('profile');
    })->name('profile.index');
});

// TEST FLOW bisa diakses semua role yang login
Route::middleware(['auth'])->prefix('test')->name('test.')->group(function () {
    Route::get('/form', [TestController::class, 'form'])->name('form');
    Route::post('/form', [TestController::class, 'storeForm'])->name('form.store');

    // ST-30
    Route::prefix('st30')->name('st30.')->group(function () {
        Route::get('/stage/{stage}', [TestController::class, 'st30Stage'])->name('stage');
        Route::post('/stage/{stage}', [TestController::class, 'storeST30Stage'])->name('stage.store');
    });

    // SJT
    Route::prefix('sjt')->name('sjt.')->group(function () {
        Route::get('/page/{page}', [TestController::class, 'sjtPage'])->name('page');
        Route::post('/page/{page}', [TestController::class, 'storeSJTPage'])->name('page.store');
    });

    Route::get('/thank-you', [TestController::class, 'thankYou'])->name('thank-you');
    Route::post('/complete', [TestController::class, 'complete'])->name('complete');
});
