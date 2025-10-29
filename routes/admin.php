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
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\ReportController;


use App\Http\Controllers\Admin\ResendRequestController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\SettingController;
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
            Route::get('/', [AdminQuestionController::class, 'index'])->name('index');
            Route::get('/create', [AdminQuestionController::class, 'create'])->name('create')->middleware('role:admin');
            Route::post('/', [AdminQuestionController::class, 'store'])->name('store')->middleware('role:admin');

            Route::post('/{questionVersion}/activate', [AdminQuestionController::class, 'activate'])
                ->name('activate')->middleware('role:admin')->whereNumber('questionVersion');

            Route::post('/{questionVersion}/clone', [AdminQuestionController::class, 'clone'])
                ->name('clone')->middleware('role:admin')->whereNumber('questionVersion');

            Route::get('/{questionVersion}', [AdminQuestionController::class, 'show'])
                ->name('show')->whereNumber('questionVersion');

            Route::get('/{questionVersion}/edit', [AdminQuestionController::class, 'edit'])
                ->name('edit')->middleware('role:admin')->whereNumber('questionVersion');

            Route::put('/{questionVersion}', [AdminQuestionController::class, 'update'])
                ->name('update')->middleware('role:admin')->whereNumber('questionVersion');

            Route::delete('/{questionVersion}', [AdminQuestionController::class, 'destroy'])
                ->name('destroy')->middleware('role:admin')->whereNumber('questionVersion');

            // ------- ST-30 -------
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

            // ------- SJT -------
            Route::prefix('sjt')->name('sjt.')->group(function () {
                Route::get('/', [SJTQuestionController::class, 'index'])->name('index');
                Route::get('/create', [SJTQuestionController::class, 'create'])->name('create')->middleware('role:admin');
                Route::post('/', [SJTQuestionController::class, 'store'])->name('store')->middleware('role:admin');
                Route::get('/{sjtQuestion}', [SJTQuestionController::class, 'show'])->name('show');
                Route::get('/{sjtQuestion}/edit', [SJTQuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{sjtQuestion}', [SJTQuestionController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{sjtQuestion}', [SJTQuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            });

            // ------- Competencies -------
            Route::prefix('competencies')->name('competencies.')->group(function () {
                Route::get('/', [CompetencyController::class, 'index'])->name('index');
                Route::get('/{competency}', [CompetencyController::class, 'show'])->name('show');
                Route::get('/{competency}/edit', [CompetencyController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{competency}', [CompetencyController::class, 'update'])->name('update')->middleware('role:admin');
            });

            // ------- Typologies -------
            Route::prefix('typologies')->name('typologies.')->group(function () {
                Route::get('/', [TypologyController::class, 'index'])->name('index');
                Route::get('/create', [TypologyController::class, 'create'])->name('create')->middleware('role:admin');
                Route::post('/', [TypologyController::class, 'store'])->name('store')->middleware('role:admin');

                Route::get('/{typology}', [TypologyController::class, 'show'])->name('show');
                Route::get('/{typology}/edit', [TypologyController::class, 'edit'])->name('edit')->middleware('role:admin');
                Route::put('/{typology}', [TypologyController::class, 'update'])->name('update')->middleware('role:admin');
                Route::delete('/{typology}', [TypologyController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            });
        });

        // Users
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
        });

        // Events
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [AdminEventController::class, 'index'])->name('index');
            Route::get('/create', [AdminEventController::class, 'create'])->name('create')->middleware('role:admin');
            Route::post('/', [AdminEventController::class, 'store'])->name('store')->middleware('role:admin');
            Route::get('/export/pdf', [AdminEventController::class, 'exportPdf'])->name('export.pdf'); // <— NEW
            Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
            Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit')->middleware('role:admin');
            Route::put('/{event}', [AdminEventController::class, 'update'])->name('update')->middleware('role:admin');
            Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy')->middleware('role:admin');
            Route::post('/{event}/toggle-status', [AdminEventController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
        });

        // Results
        Route::prefix('results')->name('results.')->group(function () {
            Route::get('/', [AdminResultController::class, 'index'])->name('index');
            Route::get('/statistics', [AdminResultController::class, 'getStatistics'])->name('statistics');
            Route::get('/export', [AdminResultController::class, 'export'])->name('export');
            Route::get('/{testResult}', [AdminResultController::class, 'show'])->name('show');
            Route::get('/{testResult}/download-pdf', [AdminResultController::class, 'downloadPdf'])->name('download-pdf');
            Route::post('/{testResult}/send-result', [AdminResultController::class, 'sendResult'])->name('send-result');
            Route::post('/{testResult}/regenerate-pdf', [AdminResultController::class, 'regeneratePdf'])->name('regenerate-pdf');
            Route::post('/bulk-action', [AdminResultController::class, 'bulkAction'])->name('bulk-action');
        });

        // Resend Requests
        Route::prefix('resend')->name('resend.')->group(function () {
            Route::get('/', [ResendRequestController::class, 'index'])->name('index');
            Route::get('/{resendRequest}', [ResendRequestController::class, 'show'])->name('show');
            Route::post('/{resendRequest}/approve', [ResendRequestController::class, 'approve'])->name('approve');
            Route::post('/{resendRequest}/reject', [ResendRequestController::class, 'reject'])->name('reject');
            Route::post('/bulk-approve', [ResendRequestController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [ResendRequestController::class, 'bulkReject'])->name('bulk-reject');
            Route::delete('/cleanup', [ResendRequestController::class, 'cleanup'])->name('cleanup')->middleware('role:admin');
        });

        // Monitoring
        Route::prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/sessions', [MonitoringController::class, 'sessions'])->name('sessions');
            Route::get('/activities', [MonitoringController::class, 'activities'])->name('activities');
            Route::get('/system-logs', [MonitoringController::class, 'systemLogs'])->name('system-logs');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->middleware('role:admin')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });

        // Profile (admin/staff)
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',               [ReportController::class, 'index'])->name('index');
            Route::get('/participants',   [ReportController::class, 'participants'])->name('participants');
            Route::get('/events',         [ReportController::class, 'events'])->name('events');
            Route::get('/delivery',       [ReportController::class, 'delivery'])->name('delivery');
            Route::get('/resend',         [ReportController::class, 'resend'])->name('resend');
            Route::get('/data-quality',   [ReportController::class, 'dataQuality'])->name('data_quality');
            Route::get('/anomaly',        [ReportController::class, 'anomaly'])->name('anomaly');
            Route::get('/insight',        [ReportController::class, 'insight'])->name('insight');

            // === PDF EXPORTS ===
            Route::get('/pdf/health',         [ReportController::class, 'exportHealthPdf'])->name('pdf.health');
            Route::get('/pdf/participants',   [ReportController::class, 'exportParticipantsPdf'])->name('pdf.participants');
            Route::get('/pdf/events',         [ReportController::class, 'exportEventsPdf'])->name('pdf.events');
            Route::get('/pdf/delivery',       [ReportController::class, 'exportDeliveryPdf'])->name('pdf.delivery');
            Route::get('/pdf/resend',         [ReportController::class, 'exportResendPdf'])->name('pdf.resend');
            Route::get('/pdf/data-quality',   [ReportController::class, 'exportDataQualityPdf'])->name('pdf.data_quality');
            Route::get('/pdf/anomaly',        [ReportController::class, 'exportAnomalyPdf'])->name('pdf.anomaly');
            Route::get('/pdf/insight',        [ReportController::class, 'exportInsightPdf'])->name('pdf.insight');
        });
    });
