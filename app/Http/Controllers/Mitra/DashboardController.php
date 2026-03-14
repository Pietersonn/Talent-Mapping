<?php
namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\TestSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $mitra = Auth::user();
        abort_unless($mitra !== null, 401);

        $myPrograms = Program::where('id_mitra', $mitra->id)
            ->where('aktif', true)
            ->orderByDesc('tanggal_mulai')
            ->get(['id', 'nama', 'kode_program', 'tanggal_mulai', 'tanggal_selesai', 'aktif', 'maks_peserta']);

        $ProgramIds = $myPrograms->pluck('id');

        $totalParticipants = $ProgramIds->isNotEmpty()
            ? DB::table('peserta_program')->whereIn('id_program', $ProgramIds)->count() : 0;

        $completedTests = $ProgramIds->isNotEmpty()
            ? TestSession::whereIn('id_program', $ProgramIds)->where('selesai', true)->count() : 0;

        $pendingTests = max(0, $totalParticipants - $completedTests);
        $totalPrograms  = $myPrograms->count();

        $recentSessions = $ProgramIds->isNotEmpty()
            ? TestSession::whereIn('id_program', $ProgramIds)
                ->with(['user:id,nama,email', 'Program:id,nama,kode_program'])
                ->latest('updated_at')->limit(5)->get()
            : collect();

        $ProgramStats = $myPrograms->map(function ($Program) {
            $registered = DB::table('peserta_program')->where('id_program', $Program->id)->count();
            $completed  = TestSession::where('id_program', $Program->id)->where('selesai', true)->count();
            return [
                'id'         => $Program->id,
                'nama'       => $Program->nama,
                'kode'       => $Program->kode_program,
                'registered' => $registered,
                'completed'  => $completed,
                'pending'    => max(0, $registered - $completed),
                'completion' => $registered > 0 ? round(($completed / $registered) * 100) : 0,
            ];
        });

        return view('mitra.dashboard', compact(
            'myPrograms', 'totalParticipants', 'completedTests',
            'pendingTests', 'totalPrograms', 'recentSessions', 'ProgramStats'
        ));
    }
}
