<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PIC\DashboardController as PICDashboardController;
use App\Http\Controllers\PIC\EventController as PICEventController;
use App\Http\Controllers\PIC\ParticipantController as PICParticipantController; 
use App\Http\Controllers\PIC\ScoreController as PICScoreController;

Route::middleware(['auth', 'role:pic'])
    ->prefix('pic')->name('pic.')
    ->group(function () {

        Route::get('/dashboard', [PICDashboardController::class, 'index'])->name('dashboard');

        // Events
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [PICEventController::class, 'index'])->name('index');
            Route::get('/export/pdf', [PICEventController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{event}', [PICEventController::class, 'show'])->name('show');
        });

        // Participants
        Route::prefix('participants')->name('participants.')->group(function () {
            // Gunakan Alias Controller di sini
            Route::get('/', [PICParticipantController::class, 'index'])->name('index');
            Route::get('/{session}/result-pdf', [PICParticipantController::class, 'resultPdf'])->name('result-pdf');
        });

        Route::get('/participants/export/pdf', [PICParticipantController::class, 'exportPdf'])
            ->name('participants.export-pdf');

        // Score
        Route::prefix('score')->name('score.')->group(function () {
            Route::get('/', [PICScoreController::class, 'index'])->name('index');
            Route::get('/export/pdf', [PICScoreController::class, 'exportPdf'])->name('export.pdf');
        });
    });
