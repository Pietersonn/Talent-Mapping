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

        $programs = $query->latest()->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            $programs->getCollection()->transform(function ($program) {
                return [
                    'id'                 => $program->id,
                    'nama'               => $program->nama,
                    'perusahaan'         => $program->perusahaan ?? '-',
                    'kode_program'       => $program->kode_program,
                    'mitra_name'         => $program->mitra->nama ?? 'Belum ada Mitra',
                    'mitra_email'        => $program->mitra->email ?? '',
                    'participants_count' => $program->participants_count,
                    'maks_peserta'       => $program->maks_peserta,
                    'aktif'              => $program->aktif,
                    'tanggal_mulai_formatted'   => \Carbon\Carbon::parse($program->tanggal_mulai)->format('d M'),
                    'tanggal_selesai_formatted' => \Carbon\Carbon::parse($program->tanggal_selesai)->format('d M Y'),
                    'show_url'           => route('admin.programs.show', $program->id),
                    'edit_url'           => route('admin.programs.edit', $program->id),
                    'delete_url'         => route('admin.programs.destroy', $program->id),
                ];
            });

            return response()->json(['programs' => $programs, 'is_admin' => Auth::user()->peran === 'admin']);
        }

        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        $mitras = User::where('peran', 'mitra')->where('aktif', true)->orderBy('nama')->get();
        return view('admin.programs.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'kode_program'    => ['required', 'string', 'max:15', 'unique:program,kode_program'],
            'perusahaan'      => ['nullable', 'string', 'max:100'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_mitra'        => ['nullable', 'exists:pengguna,id'],
            'maks_peserta'    => ['nullable', 'integer', 'min:1'],
            'deskripsi'       => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request) {
            $last = DB::table('program')->lockForUpdate()
                ->select(DB::raw("CAST(SUBSTRING(id, 4) AS UNSIGNED) as num"))
                ->orderByDesc('num')->first();

            Program::create([
                'id'              => 'PRG' . (($last ? $last->num : 0) + 1),
                'nama'            => $request->nama,
                'kode_program'    => strtoupper(str_replace(' ', '', $request->kode_program)),
                'perusahaan'      => $request->perusahaan,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'id_mitra'        => $request->id_mitra,
                'maks_peserta'    => $request->maks_peserta,
                'deskripsi'       => $request->deskripsi,
                'aktif'           => $request->has('aktif') ? true : false,
            ]);
        });

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dibuat.');
    }

    public function show(string $id)
    {
        $program = Program::with(['mitra', 'participants.testSessions'])->findOrFail($id);

        // --- PERBAIKAN: is_completed diubah menjadi selesai ---
        $completedTests = TestSession::where('id_program', $program->id)->where('selesai', true)->count();

        $resultsSent = 0;
        if (class_exists(TestResult::class)) {
            $resultsSent = TestResult::whereHas('testSession', fn($q) => $q->where('id_program', $program->id))
                ->whereNotNull('email_terkirim_pada')->count();
        }

        $stats = [
            'total_participants' => $program->participants->count(),
            'completed_tests'    => $completedTests,
            'pending_tests'      => $program->participants->count() - $completedTests,
            'results_sent'       => $resultsSent,
        ];

        return view('admin.programs.show', compact('program', 'stats'));
    }

    public function edit(string $id)
    {
        $program = Program::findOrFail($id);
        $mitras = User::where('peran', 'mitra')->where('aktif', true)->orderBy('nama')->get();
        return view('admin.programs.edit', compact('program', 'mitras'));
    }

    public function update(Request $request, string $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'kode_program'    => ['required', 'string', 'max:15', Rule::unique('program', 'kode_program')->ignore($program->id)],
            'perusahaan'      => ['nullable', 'string', 'max:100'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_mitra'        => ['nullable', 'exists:pengguna,id'],
            'maks_peserta'    => ['nullable', 'integer', 'min:1'],
            'deskripsi'       => ['nullable', 'string', 'max:1000'],
        ]);

        $program->update([
            'nama'            => $request->nama,
            'kode_program'    => strtoupper(str_replace(' ', '', $request->kode_program)),
            'perusahaan'      => $request->perusahaan,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'id_mitra'        => $request->id_mitra,
            'maks_peserta'    => $request->maks_peserta,
            'deskripsi'       => $request->deskripsi,
            'aktif'           => $request->has('aktif') ? true : false,
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $program = Program::findOrFail($id);
        if ($program->participants()->count() > 0) {
            return back()->with('error', 'Gagal menghapus: Program memiliki peserta terdaftar.');
        }
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }

    public function toggleStatus(string $id)
    {
        $program = Program::findOrFail($id);
        $program->update(['aktif' => !$program->aktif]);
        return back()->with('success', 'Status program berhasil diubah.');
    }

    public function exportPdf(Request $request)
    {
        $query = Program::with('mitra')->withCount('participants');
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$term}%")->orWhere('kode_program', 'like', "%{$term}%"));
        }

        $pdf = Pdf::loadView('admin.programs.pdf.programReport', [
            'reportTitle' => 'Laporan Daftar Program',
            'generatedBy' => Auth::user()->nama ?? 'Admin',
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $query->latest()->get(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Program.pdf');
    }
}
