<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TestSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $pic = Auth::user();
        abort_unless($pic, 401);

        // Ambil semua event milik PIC (aktif saja, sesuai kode kamu)
        $myEvents = Event::query()
            ->where('pic_id', $pic->id)
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->get(['id','name','event_code','start_date','end_date','is_active','max_participants']);

        $eventIds = $myEvents->pluck('id');

        // ====== Aggregate peserta untuk seluruh event PIC ======
        if ($eventIds->isNotEmpty()) {
            $agg = DB::table('event_participants as ep')
                ->selectRaw('
                    COUNT(*) AS total_participants,
                    SUM(CASE WHEN ep.test_completed = 1 THEN 1 ELSE 0 END) AS completed_tests,
                    SUM(CASE WHEN ep.results_sent   = 1 THEN 1 ELSE 0 END) AS results_sent
                ')
                ->whereIn('ep.event_id', $eventIds)
                ->first();
        } else {
            // fallback jika belum ada event
            $agg = (object)[
                'total_participants' => 0,
                'completed_tests'    => 0,
                'results_sent'       => 0,
            ];
        }

        $totalEvents       = $myEvents->count();
        $totalParticipants = (int) ($agg->total_participants ?? 0);
        $completedTests    = (int) ($agg->completed_tests ?? 0);
        $pendingTests      = max(0, $totalParticipants - $completedTests);

        // ====== Recent activity (sesi terbaru di event PIC) ======
        $recentSessions = $eventIds->isNotEmpty()
            ? TestSession::query()
                ->whereIn('event_id', $eventIds)
                ->with(['user:id,name,email', 'event:id,name,event_code'])
                ->latest('updated_at')
                ->limit(10)
                ->get()
            : collect();

        // ====== Progress per event (pakai agregasi sekali + mapping) ======
        $progressMap = [];
        if ($eventIds->isNotEmpty()) {
            $perEventAgg = DB::table('event_participants as ep')
                ->selectRaw('
                    ep.event_id,
                    COUNT(*) AS total_participants,
                    SUM(CASE WHEN ep.test_completed = 1 THEN 1 ELSE 0 END) AS completed
                ')
                ->whereIn('ep.event_id', $eventIds)
                ->groupBy('ep.event_id')
                ->get();

            foreach ($perEventAgg as $row) {
                $progressMap[(int)$row->event_id] = [
                    'total_participants' => (int)$row->total_participants,
                    'completed'          => (int)$row->completed,
                ];
            }
        }

        $eventProgress = [];
        foreach ($myEvents as $ev) {
            $p = $progressMap[$ev->id] ?? ['total_participants' => 0, 'completed' => 0];
            $participants = (int) $p['total_participants'];
            $completed    = (int) $p['completed'];
            $rate         = $participants > 0 ? round($completed / $participants * 100, 1) : 0.0;

            // Sediakan DUA nama key untuk kompatibilitas Blade lama/baru
            $eventProgress[] = [
                'event'              => $ev,
                'total_participants' => $participants,
                'participants'       => $participants,
                'completed'          => $completed,
                'completion_rate'    => $rate,
            ];
        }

        // ====== Stats array (biar Blade yang pakai $stats nggak error) ======
        $stats = [
            'total_events'       => (int) $totalEvents,
            'total_participants' => (int) $totalParticipants,
            'completed_tests'    => (int) $completedTests,
            'pending_tests'      => (int) $pendingTests,
        ];

        return view('pic.dashboard', [
            // variabel terpisah (kompatibel dengan Blade lama)
            'totalEvents'       => $totalEvents,
            'totalParticipants' => $totalParticipants,
            'completedTests'    => $completedTests,
            'pendingTests'      => $pendingTests,

            // paket stats (kompatibel dengan Blade baru)
            'stats'         => $stats,
            'recentSessions'=> $recentSessions,
            'eventProgress' => $eventProgress,
        ]);
    }
}
