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

        // Ambil program yang dikelola oleh mitra ini
        $myPrograms = Program::where('id_mitra', $mitra->id)
            ->where('aktif', true)
            ->orderByDesc('tanggal_mulai')
            ->get(['id', 'nama', 'kode_program', 'tanggal_mulai', 'tanggal_selesai', 'aktif', 'maks_peserta']);

        $programIds = $myPrograms->pluck('id');

        // Menghitung total peserta menggunakan tabel pivot (peserta_program)
        $totalParticipants = $programIds->isNotEmpty()
            ? DB::table('peserta_program')->whereIn('id_program', $programIds)->count() : 0;

        // Menghitung jumlah tes yang statusnya 'selesai' = true
        $completedTests = $programIds->isNotEmpty()
            ? TestSession::whereIn('id_program', $programIds)->where('selesai', true)->count() : 0;

        $pendingTests = max(0, $totalParticipants - $completedTests);
        $totalPrograms  = $myPrograms->count();

        // Mengambil sesi tes terakhir
        // PENTING: Pastikan Model TestSession memiliki relasi:
        // public function pengguna() { return $this->belongsTo(User::class, 'id_pengguna'); }
        // public function program() { return $this->belongsTo(Program::class, 'id_program'); }
        $recentSessions = $programIds->isNotEmpty()
            ? TestSession::whereIn('id_program', $programIds)
                ->with(['pengguna:id,nama,email', 'program:id,nama,kode_program'])
                ->latest('updated_at')->limit(5)->get()
            : collect();

        // Melakukan map statistik untuk dilempar ke chart dan tabel di Blade
        $programStats = $myPrograms->map(function ($program) {
            $registered = DB::table('peserta_program')->where('id_program', $program->id)->count();
            $completed  = TestSession::where('id_program', $program->id)->where('selesai', true)->count();
            return [
                'id'         => $program->id,
                'nama'       => $program->nama,
                'kode'       => $program->kode_program,
                'registered' => $registered,
                'completed'  => $completed,
                'pending'    => max(0, $registered - $completed),
                'completion' => $registered > 0 ? round(($completed / $registered) * 100) : 0,
                'quota'      => $program->maks_peserta // Ditambahkan agar data max peserta muncul di chart & view
            ];
        });

        return view('mitra.dashboard', compact(
            'myPrograms', 'totalParticipants', 'completedTests',
            'pendingTests', 'totalPrograms', 'recentSessions', 'programStats'
        ));
    }
}
