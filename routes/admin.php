<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ST30QuestionController;
use App\Http\Controllers\Admin\SJTQuestionController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\TypologyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\Admin\ResendRequestController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'role:admin,staff'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStatistics'])->name('dashboard.stats');

        // ==========================
        // QUESTION BANK (VERSIONS + BANK)
        // ==========================
        Route::prefix('questions')->name('questions.')->group(function () {

            // 1. Routes Utama (Index, Create, Store)
            Route::get('/', [AdminQuestionController::class, 'index'])->name('index');
            Route::get('/create', [AdminQuestionController::class, 'create'])->name('create')->middleware('role:admin');
            Route::post('/', [AdminQuestionController::class, 'store'])->name('store')->middleware('role:admin');

            // 2. ROUTE EXPORT PDF GLOBAL (REKAP) - PERBAIKAN DISINI
            // Ditaruh SEBELUM route dinamis {questionVersion} dan TANPA parameter {questionVersion}
            Route::get('/export/pdf', [AdminQuestionController::class, 'exportPdf'])->name('export.pdf');

            // 3. Route Group Statis (st30, sjt, dll)
            Route::prefix('st30')->name('st30.')->group(function () {
                Route::get('/', [ST30QuestionController::class, 'index'])->name('index');
                Route::get('/create', [ST30QuestionController::class, 'create'])->name('create')->middleware('role:admin');
                Route::post('/', [ST30QuestionController::class, 'store'])->name('store')->middleware('role:admin');

                Route::get('/export',  [ST30QuestionController::class, 'export'])->name('export');
                Route::post('/import', [ST30QuestionController::class, 'import'])->name('import')->middleware('role:admin');
                Route::post('/reorder', [ST30QuestionController::class, 'reorder'])->name('reorder')->middleware('role:admin');

                Route::get('/{st30Question}', [ST30QuestionController::class, 'show'])->name('show');
                Route::get('/{st30Question}/edit', [ST30QuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{st30Question}', [ST30QuestionController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{st30Question}', [ST30QuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            });

            Route::prefix('sjt')->name('sjt.')->group(function () {
                Route::get('/', [SJTQuestionController::class, 'index'])->name('index');
                Route::get('/create', [SJTQuestionController::class, 'create'])->name('create')->middleware('role:admin');
                Route::post('/', [SJTQuestionController::class, 'store'])->name('store')->middleware('role:admin');

                Route::get('/export', [SJTQuestionController::class, 'export'])->name('export');
                Route::post('/import', [SJTQuestionController::class, 'import'])->name('import')->middleware('role:admin');

                Route::get('/{sjtQuestion}', [SJTQuestionController::class, 'show'])->name('show');
                Route::get('/{sjtQuestion}/edit', [SJTQuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{sjtQuestion}', [SJTQuestionController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{sjtQuestion}', [SJTQuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            });

            Route::prefix('competencies')->name('competencies.')->group(function () {
                Route::get('/', [CompetencyController::class, 'index'])->name('index');
                Route::get('/export', [CompetencyController::class, 'export'])->name('export');
                Route::get('/create', [CompetencyController::class, 'create'])->name('create')->middleware('role:admin');
                Route::post('/', [CompetencyController::class, 'store'])->name('store')->middleware('role:admin');
                Route::get('/{competency}', [CompetencyController::class, 'show'])->name('show');
                Route::get('/{competency}/edit', [CompetencyController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{competency}', [CompetencyController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{competency}', [CompetencyController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            });

            Route::prefix('typologies')->name('typologies.')->group(function () {
                Route::get('/', [TypologyController::class, 'index'])->name('index');
                Route::get('/create', [TypologyController::class, 'create'])->name('create')->middleware('role:admin');
                Route::post('/', [TypologyController::class, 'store'])->name('store')->middleware('role:admin');
                Route::get('/export', [TypologyController::class, 'export'])->name('export');

                Route::get('/{typology}', [TypologyController::class, 'show'])->name('show');
                Route::get('/{typology}/edit', [TypologyController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{typology}', [TypologyController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{typology}', [TypologyController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            });

            // 4. Routes Dinamis ({questionVersion}) - TARUH PALING BAWAH
            Route::get('/{questionVersion}/statistics', [AdminQuestionController::class, 'statistics'])->name('statistics');
            Route::post('/{questionVersion}/activate', [AdminQuestionController::class, 'activate'])->name('activate')->middleware('role:admin');
            Route::post('/{questionVersion}/clone', [AdminQuestionController::class, 'clone'])->name('clone')->middleware('role:admin');

            Route::get('/{questionVersion}', [AdminQuestionController::class, 'show'])->name('show');
            Route::get('/{questionVersion}/edit', [AdminQuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
            Route::put('/{questionVersion}', [AdminQuestionController::class, 'update'])->name('update')->middleware('role:admin');
            Route::delete('/{questionVersion}', [AdminQuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        });

        // Users, Events, dll tetap sama...
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [AdminUserController::class, 'create'])->name('create');
            Route::post('/', [AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset-password');
            Route::get('/export/pdf', [AdminUserController::class, 'exportPdf'])->name('export.pdf');
        });

        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [AdminEventController::class, 'index'])->name('index');
            Route::get('/create', [AdminEventController::class, 'create'])->name('create')->middleware('role:admin');
            Route::post('/', [AdminEventController::class, 'store'])->name('store')->middleware('role:admin');
            Route::get('/export/pdf', [AdminEventController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
            Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit')->middleware('role:admin');
            Route::put('/{event}', [AdminEventController::class, 'update'])->name('update')->middleware('role:admin');
            Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            Route::post('/{event}/toggle-status', [AdminEventController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
        });

        Route::prefix('results')->name('results.')->group(function () {
            Route::get('/', [ResultController::class, 'index'])->name('index');
            Route::get('/export-pdf', [ResultController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{testResult}/download-pdf', [ResultController::class, 'downloadPdf'])->name('download-pdf');
        });

        Route::prefix('resend')->name('resend.')->group(function () {
            Route::get('/', [ResendRequestController::class, 'index'])->name('index');
            Route::get('/export-pdf', [ResendRequestController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{resendRequest}', [ResendRequestController::class, 'show'])->name('show');
            Route::post('/{resendRequest}/approve', [ResendRequestController::class, 'approve'])->name('approve');
            Route::post('/{resendRequest}/reject', [ResendRequestController::class, 'reject'])->name('reject');
            Route::post('/bulk-approve', [ResendRequestController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [ResendRequestController::class, 'bulkReject'])->name('bulk-reject');
            Route::delete('/cleanup', [ResendRequestController::class, 'cleanup'])->name('cleanup')->middleware('role:admin');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        });

        Route::prefix('score')->name('score.')->group(function () {
            Route::get('/', [ScoreController::class, 'participants'])->name('index');
            Route::get('/pdf', [ScoreController::class, 'exportParticipantsPdf'])->name('export.pdf');
        });
    });
