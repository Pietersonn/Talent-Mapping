<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
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
        $totalUsers        = User::count();
        $totalAdmins       = User::where('peran', 'admin')->count();
        $totalMitras       = User::where('peran', 'mitra')->count();
        $totalParticipants = User::where('peran', 'peserta')->count();
        $activeUsers       = User::where('aktif', true)->count();

        $totalPrograms    = Program::count();
        $activePrograms   = Program::where('aktif', true)->where('tanggal_mulai', '<=', now())->where('tanggal_selesai', '>=', now())->count();
        $upcomingPrograms = Program::where('tanggal_mulai', '>', now())->count();
        $expiredPrograms  = Program::where('tanggal_selesai', '<', now())->count();

        $totalTestSessions = TestSession::count();
        $completedTests    = TestSession::where('selesai', true)->count();
        $ongoingTests      = TestSession::where('selesai', false)->count();
        $testsToday        = TestSession::whereDate('created_at', today())->count();
        $testsThisWeek     = TestSession::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $testsThisMonth    = TestSession::whereMonth('created_at', now()->month)->count();

        $totalQuestionVersions          = QuestionVersion::count();
        $activeVersions                 = QuestionVersion::where('aktif', true)->count();
        $totalST30Questions             = ST30Question::count();
        $totalTalentCompetencyQuestions = TalentCompetencyQuestion::count();

        $totalResults   = TestResult::count();
        $resultsWithPDF = TestResult::whereNotNull('path_pdf')->count();
        $emailsSent     = TestResult::whereNotNull('email_terkirim_pada')->count();
        $pendingResults = TestResult::whereNull('email_terkirim_pada')->count();

        $totalResendRequests    = ResendRequest::count();
        $pendingResendRequests  = ResendRequest::where('status', 'pending')->count();
        $approvedResendRequests = ResendRequest::where('status', 'approved')->count();
        $rejectedResendRequests = ResendRequest::where('status', 'rejected')->count();

        $completionRate = $totalTestSessions > 0
            ? round(($completedTests / $totalTestSessions) * 100, 1) : 0;

        $testsPerDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $testsPerDay[] = ['date' => $date->format('d M'), 'count' => TestSession::whereDate('created_at', $date->toDateString())->count()];
        }

        $recentTestSessions   = TestSession::with(['user', 'Program'])->latest()->take(5)->get();
        $recentResults        = TestResult::with(['testSession.user', 'testSession.Program'])->latest()->take(5)->get();
        $recentResendRequests = ResendRequest::with(['user'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalUsers', 'totalAdmins', 'totalMitras', 'totalParticipants', 'activeUsers',
            'totalPrograms', 'activePrograms', 'upcomingPrograms', 'expiredPrograms',
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
        $labels = []; $data = [];

        if ($period === 'today') {
            $start = Carbon::today();
            for ($i = 0; $i <= 23; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $data[]   = TestSession::whereBetween('created_at', [$start->copy()->addHours($i), $start->copy()->addHours($i)->endOfHour()])->count();
            }
        } elseif ($period === 'month') {
            $start = Carbon::now()->startOfMonth();
            for ($i = 1; $i <= $start->daysInMonth; $i++) {
                $date = $start->copy()->day($i);
                $labels[] = $date->format('d M');
                $data[]   = TestSession::whereDate('created_at', $date)->count();
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[]   = TestSession::whereDate('created_at', $date)->count();
            }
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}
