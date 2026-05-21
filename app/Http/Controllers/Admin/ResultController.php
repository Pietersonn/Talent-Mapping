<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        // FIX: Relasi menggunakan huruf kecil 'session.program'
        $query = TestResult::with(['session.user', 'session.program'])
            ->orderBy('laporan_dibuat_pada', 'desc');

        if ($request->filled('search')) {
            $search = (string)$request->search;
            $query->whereHas('session', function ($q) use ($search) {
                $q->where('nama_peserta', 'LIKE', "%{$search}%")
                  ->orWhere('latar_belakang', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('nomor_telepon', 'LIKE', "%{$search}%"))
                  // FIX: relasi ke program menggunakan huruf kecil 'program'
                  ->orWhereHas('program', fn($e) => $e->where('nama', 'LIKE', "%{$search}%"));
            });
        }

        // FIX: Parameter request diubah jadi huruf kecil program_id
        if ($request->filled('program_id')) {
            $query->whereHas('session', fn($q) => $q->where('id_program', $request->program_id));
        }

        if ($request->ajax()) {
            $results = $query->paginate(20)->withQueryString();
            return response()->json([
                'results'      => $results,
                'is_admin'     => Auth::user()->peran === 'admin',
                'download_url' => url('admin/results'),
            ]);
        }

        $results = $query->paginate(20)->withQueryString();
        // FIX: Variabel diubah jadi lowercase $programs
        $programs  = Program::where('aktif', true)->get(['id', 'nama']);

        return view('admin.results.index', compact('results', 'programs'));
    }

    public function exportPdf(Request $request)
    {
        // FIX: Relasi menggunakan huruf kecil 'session.program'
        $query = TestResult::with(['session.user', 'session.program'])
            ->orderBy('laporan_dibuat_pada', 'desc');

        if ($request->filled('search')) {
            $search = (string)$request->search;
            $query->whereHas('session', function ($q) use ($search) {
                $q->where('nama_peserta', 'LIKE', "%{$search}%")
                  ->orWhere('latar_belakang', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('nomor_telepon', 'LIKE', "%{$search}%"))
                  ->orWhereHas('program', fn($e) => $e->where('nama', 'LIKE', "%{$search}%"));
            });
        }

        // FIX: Parameter request diubah jadi huruf kecil program_id
        if ($request->filled('program_id')) {
            $query->whereHas('session', fn($q) => $q->where('id_program', $request->program_id));
        }

        $pdf = Pdf::loadView('admin.results.pdf.resultReport', [
            'reportTitle' => 'Laporan Peserta',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d M Y H:i'),
            'rows'        => $query->get(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Peserta.pdf');
    }

    public function downloadPdf(TestResult $testResult)
    {
        if (empty($testResult->path_pdf)) {
            return back()->with(
                'error',
                'File PDF belum digenerate atau path kosong.'
            );
        }

        if (Storage::disk('public')->exists($testResult->path_pdf)) {
            return Response::make(
                Storage::disk('public')->get($testResult->path_pdf),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' =>
                        'inline; filename="result-'.$testResult->id.'.pdf"',
                ]
            );
        }

        return back()->with(
            'error',
            'File PDF tidak ditemukan di server.'
        );
    }
}
