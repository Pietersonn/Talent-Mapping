<?php
namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    private function myProgramIds(): array
    {
        return Program::where('id_mitra', Auth::id())->pluck('id')->toArray();
    }

    public function index(Request $request)
    {
        $mitraProgramIds = $this->myProgramIds();
        $search  = $request->input('search') ?? $request->input('q');
        $ProgramId = $request->input('Program_id');
        $n       = $request->input('n', 10);

        $query = DB::table('sesi_tes as ts')
            ->join('pengguna as u', 'u.id', '=', 'ts.id_pengguna')
            ->leftJoin('program as e', 'e.id', '=', 'ts.id_program')
            ->leftJoin('hasil_tes as tr', 'tr.id_sesi', '=', 'ts.id')
            ->select(
                'ts.id as session_id', 'u.nama as name', 'u.email',
                'ts.latar_belakang as instansi', 'e.nama as Program_name',
                'e.kode_program as Program_code', 'tr.path_pdf', 'tr.hasil_tk'
            )
            ->whereIn('ts.id_program', $mitraProgramIds);

        if ($ProgramId) $query->where('ts.id_program', $ProgramId);
        if ($search) {
            $query->where(fn($q) => $q->where('u.nama', 'like', "%{$search}%")
                ->orWhere('u.email', 'like', "%{$search}%")
                ->orWhere('ts.latar_belakang', 'like', "%{$search}%"));
        }

        $rows = $query->orderBy('ts.created_at', 'desc')->paginate($n)->withQueryString();

        $rows->getCollection()->transform(function ($row) {
            $row->total_score = null;
            if (!empty($row->hasil_tk)) {
                $data = json_decode($row->hasil_tk, true);
                if (isset($data['all'])) $row->total_score = round(collect($data['all'])->sum('score'));
            }
            $row->download_url      = !empty($row->path_pdf) ? route('mitra.participants.result-pdf', $row->session_id) : null;
            $row->Program_name_short  = Str::limit($row->Program_name, 25);
            return $row;
        });

        if ($request->ajax()) {
            return response()->json(['data' => $rows->items(), 'links' => (string)$rows->links(), 'from' => $rows->firstItem() ?? 0]);
        }

        $Programs = Program::whereIn('id', $mitraProgramIds)->orderByDesc('tanggal_mulai')->get(['id', 'nama', 'kode_program']);

        return view('mitra.participants.index', [
            'rows'    => $rows,
            'Programs'  => $Programs,
            'filters' => ['search' => $search, 'Program_id' => $ProgramId],
        ]);
    }

    public function exportPdf(Request $request)
    {
        $mitraProgramIds = $this->myProgramIds();
        $search  = $request->input('search') ?? $request->input('q');
        $ProgramId = $request->input('Program_id');

        $query = DB::table('sesi_tes as ts')
            ->join('pengguna as u', 'u.id', '=', 'ts.id_pengguna')
            ->leftJoin('program as e', 'e.id', '=', 'ts.id_program')
            ->select(
                'ts.id as session_id', 'u.nama as name', 'u.email',
                'u.nomor_telepon as phone_number', 'ts.latar_belakang as instansi',
                'ts.jabatan', 'e.nama as Program_name'
            )
            ->whereIn('ts.id_program', $mitraProgramIds);

        if ($ProgramId) $query->where('ts.id_program', $ProgramId);
        if ($search) {
            $query->where(fn($q) => $q->where('u.nama', 'like', "%{$search}%")->orWhere('u.email', 'like', "%{$search}%"));
        }

        $pdf = Pdf::loadView('mitra.participants.pdf.participantReport', [
            'reportTitle' => 'Laporan Peserta',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $query->orderBy('ts.created_at', 'desc')->get(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Peserta.pdf');
    }

    public function resultPdf(string $session)
    {
        $allowed = $this->myProgramIds();

        $sessionData = DB::table('sesi_tes as ts')
            ->join('hasil_tes as tr', 'tr.id_sesi', '=', 'ts.id')
            ->where('ts.id', $session)->whereIn('ts.id_program', $allowed)
            ->select('tr.path_pdf')->first();

        if (!$sessionData || empty($sessionData->path_pdf)) abort(404, 'PDF tidak ditemukan atau akses ditolak.');

        $path = storage_path('app/'.$sessionData->path_pdf);
        if (!file_exists($path)) abort(404, 'File PDF tidak tersedia.');

        return response()->file($path, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="hasil-tes.pdf"']);
    }
}
