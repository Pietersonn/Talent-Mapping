<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use App\Models\TestSession;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with(['mitra'])->withCount('participants');

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                  ->orWhere('kode_program', 'like', "%{$term}%")
                  ->orWhere('perusahaan', 'like', "%{$term}%")
                  ->orWhere('deskripsi', 'like', "%{$term}%")
                  ->orWhereHas('mitra', fn($p) => $p->where('nama', 'like', "%{$term}%"));
            });
        }

        $Programs = $query->latest()->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            $Programs->getCollection()->transform(function ($Program) {
                return [
                    'id'                 => $Program->id,
                    'name'               => $Program->nama,
                    'company'            => $Program->perusahaan ?? '-',
                    'Program_code'         => $Program->kode_program,
                    'mitra_name'         => $Program->mitra->nama ?? 'Belum ada Mitra',
                    'participants_count' => $Program->participants_count,
                    'max_participants'   => $Program->maks_peserta,
                    'is_active'          => $Program->aktif,
                    'date_range'         => $Program->tanggal_mulai->format('d M').' - '.$Program->tanggal_selesai->format('d M Y'),
                    'show_url'           => route('admin.Programs.show', $Program->id),
                    'edit_url'           => route('admin.Programs.edit', $Program->id),
                    'delete_url'         => route('admin.Programs.destroy', $Program->id),
                    'toggle_url'         => route('admin.Programs.toggle-status', $Program->id),
                ];
            });

            return response()->json(['Programs' => $Programs, 'is_admin' => Auth::user()->peran === 'admin']);
        }

        return view('admin.Programs.index', compact('Programs'));
    }

    public function create()
    {
        $mitras = User::where('peran', 'mitra')->where('aktif', true)->orderBy('nama')->get();
        return view('admin.Programs.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'kode_program'    => ['required', 'string', 'max:15', 'unique:program,kode_program'],
            'perusahaan'      => ['nullable', 'string', 'max:100'],
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_mitra'        => ['nullable', 'exists:pengguna,id'],
            'maks_peserta'    => ['nullable', 'integer', 'min:1'],
            'deskripsi'       => ['nullable', 'string', 'max:1000'],
            'aktif'           => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request) {
            $last = DB::table('program')->lockForUpdate()
                ->select(DB::raw("CAST(SUBSTRING(id, 4) AS UNSIGNED) as num"))
                ->orderByDesc('num')->first();

            Program::create([
                'id'              => 'EVT' . (($last ? $last->num : 0) + 1),
                'nama'            => $request->nama,
                'kode_program'    => strtoupper($request->kode_program),
                'perusahaan'      => $request->perusahaan,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'id_mitra'        => $request->id_mitra,
                'maks_peserta'    => $request->maks_peserta,
                'deskripsi'       => $request->deskripsi,
                'aktif'           => $request->has('aktif'),
            ]);
        });

        return redirect()->route('admin.Programs.index')->with('success', 'Program berhasil dibuat.');
    }

    public function show(Program $Program)
    {
        $Program->load(['mitra', 'participants.testSessions' => fn($q) => $q->where('id_program', $Program->id)]);

        $completedTests = TestSession::where('id_program', $Program->id)->where('selesai', true)->count();
        $resultsSent    = TestResult::whereHas('testSession', fn($q) => $q->where('id_program', $Program->id))
            ->whereNotNull('email_terkirim_pada')->count();

        $stats = [
            'total_participants' => $Program->participants->count(),
            'completed_tests'    => $completedTests,
            'pending_tests'      => $Program->participants->count() - $completedTests,
            'results_sent'       => $resultsSent,
        ];

        return view('admin.Programs.show', compact('Program', 'stats'));
    }

    public function edit(Program $Program)
    {
        $mitras = User::where('peran', 'mitra')->where('aktif', true)->orderBy('nama')->get();
        return view('admin.Programs.edit', compact('Program', 'mitras'));
    }

    public function update(Request $request, Program $Program)
    {
        $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'kode_program'    => ['required', 'string', 'max:15', Rule::unique('program', 'kode_program')->ignore($Program->id)],
            'perusahaan'      => ['nullable', 'string', 'max:100'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_mitra'        => ['nullable', 'exists:pengguna,id'],
            'maks_peserta'    => ['nullable', 'integer', 'min:1'],
            'deskripsi'       => ['nullable', 'string', 'max:1000'],
        ]);

        $Program->update([
            'nama'            => $request->nama,
            'kode_program'    => strtoupper($request->kode_program),
            'perusahaan'      => $request->perusahaan,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'id_mitra'        => $request->id_mitra,
            'maks_peserta'    => $request->maks_peserta,
            'deskripsi'       => $request->deskripsi,
            'aktif'           => $request->has('aktif'),
        ]);

        return redirect()->route('admin.Programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $Program)
    {
        if ($Program->participants()->count() > 0) {
            return back()->with('error', 'Gagal menghapus: Program memiliki peserta terdaftar.');
        }
        $Program->delete();
        return redirect()->route('admin.Programs.index')->with('success', 'Program berhasil dihapus.');
    }

    public function toggleStatus(Program $Program)
    {
        $Program->update(['aktif' => !$Program->aktif]);
        return back()->with('success', 'Program berhasil '.($Program->aktif ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    public function exportPdf(Request $request)
    {
        $query = Program::with('mitra')->withCount('participants');
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$term}%")->orWhere('kode_program', 'like', "%{$term}%"));
        }

        $pdf = Pdf::loadView('admin.Programs.pdf.ProgramReport', [
            'reportTitle' => 'Laporan Daftar Program',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $query->latest()->get(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Program.pdf');
    }
}
