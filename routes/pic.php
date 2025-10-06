<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PIC\DashboardController as PICDashboardController;
use App\Http\Controllers\PIC\EventController as PICEventController;
use App\Http\Controllers\PIC\ParticipantController as PICParticipantController;

Route::middleware(['auth', 'role:pic'])
    ->prefix('pic')->name('pic.')
    ->group(function () {
        Route::get('/dashboard', [PICDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [PICEventController::class, 'index'])->name('index');
            Route::get('/{event}', [PICEventController::class, 'show'])->name('show');
        });
        Route::prefix('participants')->name('participants.')->group(function () {
            Route::get('/', [PICParticipantController::class, 'index'])->name('index');
            Route::get('/{session}/result-pdf', [PICParticipantController::class, 'resultPdf'])
                ->name('result-pdf');
        });
        Route::get('/participants/export/pdf', [PICParticipantController::class, 'exportPdf'])
            ->name('participants.export-pdf');
    });
