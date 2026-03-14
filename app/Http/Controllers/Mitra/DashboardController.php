<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TestSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $mitra = Auth::user();
        abort_unless($mitra !== null, 401);

        // 1. Acara milik Mitra
        $myEvents = Event::where('id_pic', $mitra->id)
            ->where('aktif', true)
            ->orderByDesc('tanggal_mulai')
            ->get(['id', 'nama', 'kode_acara', 'tanggal_mulai', 'tanggal_selesai', 'aktif', 'maks_peserta']);

        $eventIds = $myEvents->pluck('id');

        // 2. Statistik Global
        $totalParticipants = 0;
        if ($eventIds->isNotEmpty()) {
            $totalParticipants = DB::table('peserta_acara')
                ->whereIn('id_acara', $eventIds)
                ->count();
        }

        $completedTests = 0;
        if ($eventIds->isNotEmpty()) {
            $completedTests = TestSession::whereIn('id_acara', $eventIds)
                ->where('selesai', true)
                ->count();
        }

        $pendingTests = max(0, $totalParticipants - $completedTests);
        $totalEvents  = $myEvents->count();

        // 3. Sesi terbaru
        $recentSessions = $eventIds->isNotEmpty()
            ? TestSession::whereIn('id_acara', $eventIds)
                ->with(['user:id,nama,email', 'event:id,nama,kode_acara'])
                ->latest('updated_at')
                ->limit(5)
                ->get()
            : collect();

        // 4. Statistik per acara
        $eventStats = $myEvents->map(function ($event) {
            $registered = DB::table('peserta_acara')->where('id_acara', $event->id)->count();
            $completed  = TestSession::where('id_acara', $event->id)->where('selesai', true)->count();

            return [
                'id'           => $event->id,
                'nama'         => $event->nama,
                'kode_acara'   => $event->kode_acara,
                'registered'   => $registered,
                'completed'    => $completed,
                'pending'      => max(0, $registered - $completed),
                'completion'   => $registered > 0 ? round(($completed / $registered) * 100) : 0,
            ];
        });

        return view('mitra.dashboard', compact(
            'myEvents', 'totalParticipants', 'completedTests',
            'pendingTests', 'totalEvents', 'recentSessions', 'eventStats'
        ));
    }
}
