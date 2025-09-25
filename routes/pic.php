<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PIC\DashboardController as PICDashboardController;
use App\Http\Controllers\PIC\EventController as PICEventController;
use App\Http\Controllers\PIC\ParticipantController;
use App\Http\Controllers\PIC\ResultController as PICResultController;
use App\Http\Controllers\PIC\ReportController as PICReportController;

Route::middleware(['auth', 'role:pic'])
    ->prefix('pic')->name('pic.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [PICDashboardController::class, 'index'])->name('dashboard');

        // EVENTS
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [PICEventController::class, 'index'])->name('index');
            Route::get('/{event}', [PICEventController::class, 'show'])->name('show'); // {event} = Route Model Binding
        });

        // PARTICIPANTS
        Route::prefix('participants')->name('participants.')->group(function () {
            Route::get('/', [ParticipantController::class, 'index'])->name('index');
            Route::get('/{event}', [ParticipantController::class, 'show'])->name('show');
        });

        // RESULTS
        Route::prefix('results')->name('results.')->group(function () {
            Route::get('/', [PICResultController::class, 'index'])->name('index');
            Route::get('/top-performers', [PICResultController::class, 'topPerformers'])->name('top-performers');
            Route::get('/{testResult}', [PICResultController::class, 'show'])->name('show');
        });

        // REPORTS
        Route::prefix('reports')->name('reports.')->group(function () {
            // Participants + PDF
            Route::get('/participants',    [PICReportController::class, 'participants'])->name('participants');
            Route::get('/participants/pdf', [PICReportController::class, 'exportParticipantsPdf'])->name('participants.pdf');

            // Top 10 + PDF
            Route::get('/top',             [PICReportController::class, 'top'])->name('top');
            Route::get('/top/pdf',         [PICReportController::class, 'exportTopPdf'])->name('top.pdf');
        });
    });
