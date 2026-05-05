<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
use App\Http\Controllers\Mitra\ProgramController as MitraProgramController;
use App\Http\Controllers\Mitra\ParticipantController as MitraParticipantController;
use App\Http\Controllers\Mitra\ScoreController as MitraScoreController;

// Perbaikan: middleware menggunakan 'peran:mitra'
Route::middleware(['auth', 'peran:mitra'])
    ->prefix('mitra')->name('mitra.')
    ->group(function () {

        Route::get('/dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');

        // Events -> Programs
        Route::prefix('programs')->name('programs.')->group(function () {
            Route::get('/', [MitraProgramController::class, 'index'])->name('index');
            Route::get('/export/pdf', [MitraProgramController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{program}', [MitraProgramController::class, 'show'])->name('show');
        });

        // Participants
        Route::prefix('participants')->name('participants.')->group(function () {
            Route::get('/', [MitraParticipantController::class, 'index'])->name('index');
            Route::get('/{session}/result-pdf', [MitraParticipantController::class, 'resultPdf'])->name('result-pdf');
        });

        Route::get('/participants/export/pdf', [MitraParticipantController::class, 'exportPdf'])
            ->name('participants.export-pdf');

        // Score
        Route::prefix('score')->name('score.')->group(function () {
            Route::get('/', [MitraScoreController::class, 'index'])->name('index');
            Route::get('/export/pdf', [MitraScoreController::class, 'exportPdf'])->name('export.pdf');
        });
    });
