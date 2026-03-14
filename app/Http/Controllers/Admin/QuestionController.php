<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionVersion;
use App\Models\ST30Question;
use App\Models\TalentCompetencyQuestion;
use App\Models\TalentCompetencyOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $st30Versions = QuestionVersion::where('jenis', 'st30')
            ->withCount(['st30Questions'])
            ->orderBy('aktif', 'desc')->orderBy('versi', 'desc')->get();

        $tkVersions = QuestionVersion::where('jenis', 'tk')
            ->withCount(['talentCompetencyQuestions'])
            ->orderBy('aktif', 'desc')->orderBy('versi', 'desc')->get();

        $activeVersions = [
            'st30' => $st30Versions->where('aktif', true)->first(),
            'tk'   => $tkVersions->where('aktif', true)->first(),
        ];

        return view('admin.questions.versions.index', compact('st30Versions', 'tkVersions', 'activeVersions'));
    }

    public function create()
    {
        return view('admin.questions.versions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'     => 'required|in:st30,tk',
            'nama'      => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $latestVersion = QuestionVersion::where('jenis', $request->jenis)->orderBy('versi', 'desc')->first();

        $questionVersion = QuestionVersion::create([
            'versi'     => $latestVersion ? $latestVersion->versi + 1 : 1,
            'jenis'     => $request->jenis,
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'aktif'     => false,
        ]);

        return redirect()->route('admin.questions.show', ['questionVersion' => $questionVersion->id])
            ->with('success', 'Versi soal berhasil dibuat.');
    }

    public function show(QuestionVersion $questionVersion)
    {
        if ($questionVersion->jenis === 'st30') {
            $questionVersion->load('st30Questions');
            $typologyStats   = $questionVersion->st30Questions->groupBy('kode_tipologi')->map(fn($q) => $q->count())->toArray();
            $competencyStats = [];
        } else {
            $questionVersion->load('talentCompetencyQuestions');
            $competencyStats = $questionVersion->talentCompetencyQuestions->groupBy('kode_kompetensi')->map(fn($q) => $q->count())->toArray();
            $typologyStats   = [];
        }

        return view('admin.questions.versions.show', compact('questionVersion', 'typologyStats', 'competencyStats'));
    }

    public function edit(QuestionVersion $questionVersion)
    {
        return view('admin.questions.versions.edit', compact('questionVersion'));
    }

    public function update(Request $request, QuestionVersion $questionVersion)
    {
        $request->validate(['nama' => 'required|string|max:50', 'deskripsi' => 'nullable|string|max:500']);

        $questionVersion->update(['nama' => $request->nama, 'deskripsi' => $request->deskripsi]);

        return redirect()->route('admin.questions.show', ['questionVersion' => $questionVersion->id])
            ->with('success', 'Versi soal berhasil diperbarui.');
    }

    public function destroy(QuestionVersion $questionVersion)
    {
        $table = $questionVersion->jenis === 'st30' ? 'jawaban_st30' : 'jawaban_tk';
        if (DB::table($table)->where('id_versi_soal', $questionVersion->id)->exists()) {
            return redirect()->route('admin.questions.index')
                ->with('error', 'Tidak dapat menghapus versi yang sudah memiliki data respon peserta.');
        }
        if ($questionVersion->aktif) {
            return redirect()->route('admin.questions.index')
                ->with('error', 'Tidak dapat menghapus versi yang sedang aktif.');
        }
        $questionVersion->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Versi soal berhasil dihapus.');
    }

    public function activate(QuestionVersion $questionVersion)
    {
        $count    = $questionVersion->questions_count;
        $required = $questionVersion->jenis === 'st30' ? 30 : 50;

        if ($count < $required) {
            return redirect()->route('admin.questions.index')
                ->with('error', "Gagal aktivasi. Versi ini hanya memiliki {$count} soal (Minimal {$required}).");
        }

        if ($questionVersion->jenis === 'tk') {
            $incomplete = $questionVersion->talentCompetencyQuestions()->withCount('options')->get()
                ->filter(fn($q) => $q->options_count < 5)->count();
            if ($incomplete > 0) {
                return redirect()->route('admin.questions.index')
                    ->with('error', "Gagal aktivasi. Ada {$incomplete} soal TK yang opsinya belum lengkap.");
            }
        }

        DB::transaction(function () use ($questionVersion) {
            QuestionVersion::where('jenis', $questionVersion->jenis)->update(['aktif' => false]);
            $questionVersion->update(['aktif' => true]);
        });

        return redirect()->route('admin.questions.index')
            ->with('success', "Versi {$questionVersion->nama} berhasil diaktifkan.");
    }

    public function clone(QuestionVersion $questionVersion)
    {
        DB::transaction(function () use ($questionVersion) {
            $latest     = QuestionVersion::where('jenis', $questionVersion->jenis)->orderBy('versi', 'desc')->lockForUpdate()->first();
            $newVersion = QuestionVersion::create([
                'versi'     => ($latest ? $latest->versi : 0) + 1,
                'jenis'     => $questionVersion->jenis,
                'nama'      => $questionVersion->nama.' (Copy)',
                'deskripsi' => 'Disalin dari '.$questionVersion->nama,
                'aktif'     => false,
            ]);

            if ($questionVersion->jenis === 'st30') {
                foreach ($questionVersion->st30Questions as $q) {
                    ST30Question::create([
                        'id_versi'     => $newVersion->id,
                        'nomor'        => $q->nomor,
                        'pernyataan'   => $q->pernyataan,
                        'kode_tipologi'=> $q->kode_tipologi,
                        'aktif'        => $q->aktif,
                    ]);
                }
            } else {
                foreach ($questionVersion->talentCompetencyQuestions as $q) {
                    $newQ = TalentCompetencyQuestion::create([
                        'id_versi'        => $newVersion->id,
                        'nomor'           => $q->nomor,
                        'teks_pertanyaan' => $q->teks_pertanyaan,
                        'kode_kompetensi' => $q->kode_kompetensi,
                        'aktif'           => $q->aktif,
                    ]);
                    foreach ($q->options as $opt) {
                        TalentCompetencyOption::create([
                            'id_soal'          => $newQ->id,
                            'huruf_pilihan'    => $opt->huruf_pilihan,
                            'teks_pilihan'     => $opt->teks_pilihan,
                            'skor'             => $opt->skor,
                            'target_kompetensi'=> $opt->target_kompetensi,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.questions.index')->with('success', 'Versi berhasil diduplikasi.');
    }

    public function exportPdf(Request $request)
    {
        $search = $request->query('search');
        $query  = QuestionVersion::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('jenis', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
                if (stripos($search, 'tidak') !== false || stripos($search, 'non') !== false) {
                    $q->orWhere('aktif', 0);
                } elseif (stripos($search, 'aktif') !== false) {
                    $q->orWhere('aktif', 1);
                }
            });
        }

        $versions = $query->orderBy('jenis', 'desc')->orderBy('versi', 'desc')->get();

        $pdf = Pdf::loadView('admin.questions.versions.pdf.versionReport', [
            'reportTitle' => 'Laporan Versi Soal',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'versions'    => $versions,
            'search'      => $search,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Versi_Soal.pdf');
    }

    public function statistics(QuestionVersion $questionVersion)
    {
        return response()->json(['questions_count' => $questionVersion->questions_count, 'aktif' => $questionVersion->aktif]);
    }
}
