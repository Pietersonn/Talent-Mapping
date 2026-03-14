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

        $Programs = $query->latest('tanggal_mulai')->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            $Programs->getCollection()->transform(function ($Program) {
                return [
                    'id'                 => $Program->id,
                    'name'               => $Program->nama,
                    'company'            => $Program->perusahaan ?? '-',
                    'Program_code'         => $Program->kode_program,
                    'participants_count' => $Program->participants_count,
                    'max_participants'   => $Program->maks_peserta,
                    'is_active'          => $Program->aktif,
                    'date_range'         => $Program->tanggal_mulai->format('d M').' - '.$Program->tanggal_selesai->format('d M Y'),
                    'show_url'           => route('mitra.Programs.show', $Program->id),
                ];
            });
            return response()->json(['Programs' => $Programs]);
        }

        return view('mitra.Program.index', compact('Programs'));
    }

    public function show(Program $Program)
    {
        abort_unless($Program->id_mitra === Auth::id(), 403);

        $Program->load(['participants.testSessions' => fn($q) => $q->where('id_program', $Program->id)]);

        $completedTests = TestSession::where('id_program', $Program->id)->where('selesai', true)->count();
        $resultsSent    = TestResult::whereHas('testSession', fn($q) => $q->where('id_program', $Program->id))
            ->whereNotNull('email_terkirim_pada')->count();

        $stats = [
            'total_participants' => $Program->participants->count(),
            'completed_tests'    => $completedTests,
            'pending_tests'      => $Program->participants->count() - $completedTests,
            'results_sent'       => $resultsSent,
        ];

        return view('mitra.Program.show', compact('Program', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $Programs = Program::where('id_mitra', Auth::id())->withCount('participants')->latest('tanggal_mulai')->get();

        $pdf = Pdf::loadView('mitra.Program.pdf.ProgramReport', [
            'reportTitle' => 'Laporan Program Saya',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $Programs,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Program_Mitra.pdf');
    }
}
