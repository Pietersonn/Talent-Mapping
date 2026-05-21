<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\TestSession;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        // KITA KEMBALI PAKAI: withCount('participants')
        $query = Program::where('id_mitra', Auth::id())->withCount('participants');

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                    ->orWhere('kode_program', 'like', "%{$term}%")
                    ->orWhere('perusahaan', 'like', "%{$term}%")
                    ->orWhere('deskripsi', 'like', "%{$term}%");
            });
        }

        $programs = $query->latest('tanggal_mulai')->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            $programs->getCollection()->transform(function ($program) {
                return [
                    'id'                 => $program->id,
                    'nama'               => $program->nama,
                    'perusahaan'         => $program->perusahaan ?? '-',
                    'kode_program'       => $program->kode_program,
                    'participants_count' => $program->participants_count, // Sesuai relasi
                    'maks_peserta'       => $program->maks_peserta,
                    'aktif'              => $program->aktif,
                    'show_url'           => route('mitra.programs.show', $program->id),
                ];
            });
            return response()->json(['programs' => $programs]);
        }

        return view('mitra.programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        abort_unless($program->id_mitra === Auth::id(), 403);
        $program->load(['participants.testSessions' => function ($q) use ($program) {
            $q->where('id_program', $program->id);
        }]);

        $completedTests = TestSession::where('id_program', $program->id)
            ->where('selesai', true)->count();

        // PERBAIKAN: Gunakan testSessions, bukan sesiTes (pastikan di TestResult.php nama relasinya testSession)
        $resultsSent = TestResult::whereHas('testSession', function ($q) use ($program) {
            $q->where('id_program', $program->id);
        })->whereNotNull('email_terkirim_pada')->count();

        $stats = [
            'total_peserta'  => $program->participants->count(),
            'tes_selesai'    => $completedTests,
            'tes_pending'    => max(0, $program->participants->count() - $completedTests),
            'hasil_dikirim'  => $resultsSent,
        ];

        return view('mitra.programs.show', compact('program', 'stats'));
    }
    public function exportPdf(Request $request)
    {
        $programs = Program::where('id_mitra', Auth::id())
            ->with(['mitra'])
            ->withCount('participants')
            ->latest('tanggal_mulai')
            ->get();

        $pdf = Pdf::loadView('mitra.programs.pdf.programReport', [
            'reportTitle' => 'Laporan Program Mitra',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $programs,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Program_Mitra.pdf');
    }
}
