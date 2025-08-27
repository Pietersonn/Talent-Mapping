<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ST30QuestionController;
use App\Http\Controllers\Admin\SJTQuestionController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\TypologyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\ResendRequestController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\SettingController;

// PIC Controllers
use App\Http\Controllers\PIC\DashboardController as PICDashboardController;
use App\Http\Controllers\PIC\EventController as PICEventController;
use App\Http\Controllers\PIC\ParticipantController;
use App\Http\Controllers\PIC\ResultController as PICResultController;

// Public Controllers
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\TestController;
use App\Http\Controllers\Public\ProfileController as PublicProfileController;
use App\Http\Controllers\Public\GuideController;

// Auth Controllers
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES - ACCESSIBLE BY ANYONE
|--------------------------------------------------------------------------
*/

// Homepage - First page everyone sees
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/panduan-hasil', [GuideController::class, 'index'])->name('guide');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // General profile routes for all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES (role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    // User profile & test history
    Route::get('/my-profile', [PublicProfileController::class, 'index'])->name('user.profile');
    Route::post('/resend-request', [PublicProfileController::class, 'requestResend'])->name('user.resend-request');

    // Test Flow Routes
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/form', [TestController::class, 'form'])->name('form');
        Route::post('/form', [TestController::class, 'storeForm'])->name('form.store');

        // ST-30 Test Pages
        Route::prefix('st30')->name('st30.')->group(function () {
            Route::get('/stage/{stage}', [TestController::class, 'st30Stage'])->name('stage');
            Route::post('/stage/{stage}', [TestController::class, 'storeST30Stage'])->name('stage.store');
        });

        // SJT Test Pages
        Route::prefix('sjt')->name('sjt.')->group(function () {
            Route::get('/page/{page}', [TestController::class, 'sjtPage'])->name('page');
            Route::post('/page/{page}', [TestController::class, 'storeSJTPage'])->name('page.store');
        });

        Route::get('/thank-you', [TestController::class, 'thankYou'])->name('thank-you');
        Route::post('/complete', [TestController::class, 'complete'])->name('complete');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN & STAFF ROUTES (role: admin,staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStatistics'])->name('dashboard.stats');

    // Test alert for SweetAlert2
    Route::get('/test-alert', function () {
        return redirect()->route('admin.dashboard')->with('success', 'SweetAlert2 is working!');
    })->name('test-alert');

    // Question Bank Management
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [AdminQuestionController::class, 'index'])->name('index');
        Route::get('/create', [AdminQuestionController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [AdminQuestionController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{questionVersion}', [AdminQuestionController::class, 'show'])->name('show');
        Route::get('/{questionVersion}/edit', [AdminQuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{questionVersion}', [AdminQuestionController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{questionVersion}', [AdminQuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::post('/{questionVersion}/activate', [AdminQuestionController::class, 'activate'])->name('activate')->middleware('role:admin');
        Route::post('/{questionVersion}/clone', [AdminQuestionController::class, 'clone'])->name('clone')->middleware('role:admin');
        Route::get('/{questionVersion}/statistics', [AdminQuestionController::class, 'statistics'])->name('statistics');

        // ST-30 Questions
        Route::prefix('st30')->name('st30.')->group(function () {
            Route::get('/', [ST30QuestionController::class, 'index'])->name('index');
            Route::get('/create', [ST30QuestionController::class, 'create'])->name('create')->middleware('role:admin');
            Route::post('/', [ST30QuestionController::class, 'store'])->name('store')->middleware('role:admin');
            Route::get('/{st30Question}', [ST30QuestionController::class, 'show'])->name('show');
            Route::get('/{st30Question}/edit', [ST30QuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
            Route::put('/{st30Question}', [ST30QuestionController::class, 'update'])->name('update')->middleware('role:admin');
            Route::delete('/{st30Question}', [ST30QuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        });

        // SJT Questions
        Route::prefix('sjt')->name('sjt.')->group(function () {
            Route::get('/', [SJTQuestionController::class, 'index'])->name('index');
            Route::get('/create', [SJTQuestionController::class, 'create'])->name('create')->middleware('role:admin');
            Route::post('/', [SJTQuestionController::class, 'store'])->name('store')->middleware('role:admin');
            Route::get('/{sjtQuestion}', [SJTQuestionController::class, 'show'])->name('show');
            Route::get('/{sjtQuestion}/edit', [SJTQuestionController::class, 'edit'])->name('edit')->middleware('role:admin');
            Route::put('/{sjtQuestion}', [SJTQuestionController::class, 'update'])->name('update')->middleware('role:admin');
            Route::delete('/{sjtQuestion}', [SJTQuestionController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        });

        // Competency Descriptions
        Route::prefix('competencies')->name('competencies.')->group(function () {
            Route::get('/', [CompetencyController::class, 'index'])->name('index');
            Route::get('/{competency}', [CompetencyController::class, 'show'])->name('show');
            Route::get('/{competency}/edit', [CompetencyController::class, 'edit'])->name('edit')->middleware('role:admin');
            Route::put('/{competency}', [CompetencyController::class, 'update'])->name('update')->middleware('role:admin');
        });

        // Typology Descriptions
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

    // User Management
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

    // Event Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('index');
        Route::get('/create', [AdminEventController::class, 'create'])->name('create')->middleware('role:admin');
        Route::post('/', [AdminEventController::class, 'store'])->name('store')->middleware('role:admin');
        Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit')->middleware('role:admin');
        Route::put('/{event}', [AdminEventController::class, 'update'])->name('update')->middleware('role:admin');
        Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy')->middleware('role:admin');
        Route::post('/{event}/toggle-status', [AdminEventController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin');
    });

    // Results Management
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [AdminResultController::class, 'index'])->name('index');
        Route::get('/top-performers', [AdminResultController::class, 'topPerformers'])->name('top-performers');
        Route::get('/{testResult}', [AdminResultController::class, 'show'])->name('show');
        Route::post('/{testResult}/send-result', [AdminResultController::class, 'sendResult'])->name('send-result');
    });

    // Resend Requests Management
    Route::prefix('resend')->name('resend.')->group(function () {
        Route::get('/', [ResendRequestController::class, 'index'])->name('index');
        Route::post('/{resendRequest}/approve', [ResendRequestController::class, 'approve'])->name('approve');
        Route::post('/{resendRequest}/reject', [ResendRequestController::class, 'reject'])->name('reject');
    });

    // Monitoring
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/sessions', [MonitoringController::class, 'sessions'])->name('sessions');
        Route::get('/activities', [MonitoringController::class, 'activities'])->name('activities');
        Route::get('/system-logs', [MonitoringController::class, 'systemLogs'])->name('system-logs');
    });

    // Settings (Admin only)
    Route::prefix('settings')->name('settings.')->middleware('role:admin')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });

    // Profile Management (for admin/staff)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

/*
|--------------------------------------------------------------------------
| PIC ROUTES (role: pic)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pic'])->prefix('pic')->name('pic.')->group(function () {

    // PIC Dashboard
    Route::get('/dashboard', [PICDashboardController::class, 'index'])->name('dashboard');

    // My Events
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [PICEventController::class, 'index'])->name('index');
        Route::get('/{event}', [PICEventController::class, 'show'])->name('show');
    });

    // Participants
    Route::prefix('participants')->name('participants.')->group(function () {
        Route::get('/', [ParticipantController::class, 'index'])->name('index');
        Route::get('/{event}', [ParticipantController::class, 'show'])->name('show');
    });

    // Results
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [PICResultController::class, 'index'])->name('index');
        Route::get('/top-performers', [PICResultController::class, 'topPerformers'])->name('top-performers');
        Route::get('/{testResult}', [PICResultController::class, 'show'])->name('show');
    });
});
