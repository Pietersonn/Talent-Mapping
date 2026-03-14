<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\TestSession;
use App\Models\TestResult;
use App\Models\QuestionVersion;
use App\Models\ST30Question;
use App\Models\TalentCompetencyQuestion;
use App\Models\ResendRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik Pengguna ──────────────────────────────
        $totalUsers        = User::count();
        $totalAdmins       = User::where('peran', 'admin')->count();
        $totalMitras       = User::where('peran', 'mitra')->count();
        $totalParticipants = User::where('peran', 'peserta')->count();
        $activeUsers       = User::where('aktif', true)->count();

        // ── Statistik Acara ────────────────────────────────
        $totalEvents    = Event::count();
        $activeEvents   = Event::where('aktif', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->count();
        $upcomingEvents = Event::where('tanggal_mulai', '>', now())->count();
        $expiredEvents  = Event::where('tanggal_selesai', '<', now())->count();

        // ── Statistik Tes ──────────────────────────────────
        $totalTestSessions = TestSession::count();
        $completedTests    = TestSession::where('selesai', true)->count();
        $ongoingTests      = TestSession::where('selesai', false)->count();
        $testsToday        = TestSession::whereDate('created_at', today())->count();
        $testsThisWeek     = TestSession::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->count();
        $testsThisMonth = TestSession::whereMonth('created_at', now()->month)->count();

        // ── Statistik Bank Soal ────────────────────────────
        $totalQuestionVersions          = QuestionVersion::count();
        $activeVersions                 = QuestionVersion::where('aktif', true)->count();
        $totalST30Questions             = ST30Question::count();
        $totalTalentCompetencyQuestions = TalentCompetencyQuestion::count();

        // ── Statistik Hasil ────────────────────────────────
        $totalResults   = TestResult::count();
        $resultsWithPDF = TestResult::whereNotNull('path_pdf')->count();
        $emailsSent     = TestResult::whereNotNull('email_terkirim_pada')->count();
        $pendingResults = TestResult::whereNull('email_terkirim_pada')->count();

        // ── Permintaan Kirim Ulang ─────────────────────────
        $totalResendRequests    = ResendRequest::count();
        $pendingResendRequests  = ResendRequest::where('status', 'pending')->count();
        $approvedResendRequests = ResendRequest::where('status', 'approved')->count();
        $rejectedResendRequests = ResendRequest::where('status', 'rejected')->count();

        // ── Completion Rate ────────────────────────────────
        $completionRate = $totalTestSessions > 0
            ? round(($completedTests / $totalTestSessions) * 100, 1)
            : 0;

        // ── Tren Tes 7 Hari (testsPerDay untuk chart) ─────
        $testsPerDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $testsPerDay[] = [
                'date'  => $date->format('d M'),
                'count' => TestSession::whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        // ── Data Tabel Dashboard ───────────────────────────
        $recentTestSessions = TestSession::with(['user', 'event'])
            ->latest()
            ->take(5)
            ->get();

        $recentResults = TestResult::with(['testSession.user', 'testSession.event'])
            ->latest()
            ->take(5)
            ->get();

        $recentResendRequests = ResendRequest::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalUsers', 'totalAdmins', 'totalMitras', 'totalParticipants', 'activeUsers',
            'totalEvents', 'activeEvents', 'upcomingEvents', 'expiredEvents',
            'totalTestSessions', 'completedTests', 'ongoingTests', 'testsToday', 'testsThisWeek', 'testsThisMonth',
            'totalQuestionVersions', 'activeVersions', 'totalST30Questions', 'totalTalentCompetencyQuestions',
            'totalResults', 'resultsWithPDF', 'emailsSent', 'pendingResults',
            'totalResendRequests', 'pendingResendRequests', 'approvedResendRequests', 'rejectedResendRequests',
            'recentTestSessions', 'recentResults', 'recentResendRequests',
            'testsPerDay', 'completionRate'
        ));
    }

    public function getStatistics(Request $request)
    {
        $period = $request->get('period', 'week');
        $labels = [];
        $data   = [];

        if ($period === 'today') {
            $start = Carbon::today();
            for ($i = 0; $i <= 23; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $data[]   = TestSession::whereBetween('created_at', [
                    $start->copy()->addHours($i),
                    $start->copy()->addHours($i)->endOfHour(),
                ])->count();
            }
        } elseif ($period === 'month') {
            $start       = Carbon::now()->startOfMonth();
            $daysInMonth = $start->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date     = $start->copy()->day($i);
                $labels[] = $date->format('d M');
                $data[]   = TestSession::whereDate('created_at', $date)->count();
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date     = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[]   = TestSession::whereDate('created_at', $date)->count();
            }
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}
