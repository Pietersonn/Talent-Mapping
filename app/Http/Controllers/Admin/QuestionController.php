<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionVersion;
use App\Models\ST30Question;
use App\Models\SJTQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionController extends Controller
{
    /**
     * Display a listing of question versions
     */
    public function index(Request $request)
    {
        $st30Versions = QuestionVersion::where('type', 'st30')
            ->withCount(['st30Questions'])
            ->orderBy('is_active', 'desc')
            ->orderBy('version', 'desc')
            ->get();

        $sjtVersions = QuestionVersion::where('type', 'sjt')
            ->withCount(['sjtQuestions'])
            ->orderBy('is_active', 'desc')
            ->orderBy('version', 'desc')
            ->get();

        $activeVersions = [
            'st30' => $st30Versions->where('is_active', true)->first(),
            'sjt' => $sjtVersions->where('is_active', true)->first(),
        ];

        return view('admin.questions.versions.index', compact('st30Versions', 'sjtVersions', 'activeVersions'));
    }

    /**
     * Show the form for creating a new version
     */
    public function create()
    {
        return view('admin.questions.versions.create');
    }

    /**
     * Store a newly created version
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:st30,sjt',
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $latestVersion = QuestionVersion::where('type', $request->type)
            ->orderBy('version', 'desc')
            ->first();

        $nextVersion = $latestVersion ? $latestVersion->version + 1 : 1;

        $questionVersion = QuestionVersion::create([
            'version' => $nextVersion,
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.questions.show', ['questionVersion' => $questionVersion->id])
            ->with('success', 'Versi soal berhasil dibuat.');
    }

    /**
     * Display the specified version
     */
    public function show(QuestionVersion $questionVersion)
    {
        if ($questionVersion->type === 'st30') {
            $questionVersion->load('st30Questions');
            $typologyStats = $questionVersion->st30Questions
                ->groupBy('typology_code')
                ->map(fn($questions) => $questions->count())
                ->toArray();
            $competencyStats = [];
        } else {
            $questionVersion->load('sjtQuestions');
            $competencyStats = $questionVersion->sjtQuestions
                ->groupBy('competency')
                ->map(fn($questions) => $questions->count())
                ->toArray();
            $typologyStats = [];
        }

        $statistics = [
            'total_questions' => $questionVersion->questions_count,
        ];

        return view('admin.questions.versions.show', compact(
            'questionVersion',
            'statistics',
            'typologyStats',
            'competencyStats'
        ));
    }

    /**
     * Show the form for editing the specified version
     */
    public function edit(QuestionVersion $questionVersion)
    {
        return view('admin.questions.versions.edit', compact('questionVersion'));
    }

    /**
     * Update the specified version
     */
    public function update(Request $request, QuestionVersion $questionVersion)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $questionVersion->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.questions.show', ['questionVersion' => $questionVersion->id])
            ->with('success', 'Versi soal berhasil diperbarui.');
    }

    /**
     * Remove the specified version
     */
    public function destroy(QuestionVersion $questionVersion)
    {
        $hasResponses = false;

        // Cek langsung ke tabel response menggunakan question_version_id
        // Tidak perlu join ke tabel questions karena di tabel response sudah ada question_version_id
        if ($questionVersion->type === 'st30') {
            $hasResponses = DB::table('st30_responses')
                ->where('question_version_id', $questionVersion->id)
                ->exists();
        } else {
            $hasResponses = DB::table('sjt_responses')
                ->where('question_version_id', $questionVersion->id)
                ->exists();
        }

        if ($hasResponses) {
            return redirect()
                ->route('admin.questions.index')
                ->with('error', 'Tidak dapat menghapus versi yang sudah memiliki data respon peserta.');
        }

        if ($questionVersion->is_active) {
            return redirect()
                ->route('admin.questions.index')
                ->with('error', 'Tidak dapat menghapus versi yang sedang aktif.');
        }

        $questionVersion->delete();

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Versi soal berhasil dihapus.');
    }

    /**
     * Activate the specified version
     */
    public function activate(QuestionVersion $questionVersion)
    {
        $count = $questionVersion->questions_count;
        $required = $questionVersion->type === 'st30' ? 30 : 50;

        if ($count < $required) {
            return redirect()
                ->route('admin.questions.index')
                ->with('error', "Gagal aktivasi. Versi ini hanya memiliki {$count} soal (Minimal {$required}).");
        }

        if ($questionVersion->type === 'sjt') {
            $questions = $questionVersion->sjtQuestions()->with('options')->get();
            $incompleteQuestions = 0;
            foreach ($questions as $q) {
                if ($q->options->count() < 5) $incompleteQuestions++;
            }

            if ($incompleteQuestions > 0) {
                return redirect()
                    ->route('admin.questions.index')
                    ->with('error', "Gagal aktivasi. Ada {$incompleteQuestions} soal SJT yang opsinya belum lengkap.");
            }
        }

        DB::transaction(function () use ($questionVersion) {
            QuestionVersion::where('type', $questionVersion->type)->update(['is_active' => false]);
            $questionVersion->update(['is_active' => true]);
        });

        return redirect()
            ->route('admin.questions.index')
            ->with('success', "Versi {$questionVersion->name} berhasil diaktifkan.");
    }

    /**
     * Clone version
     */
    public function clone(QuestionVersion $questionVersion)
    {
        DB::transaction(function () use ($questionVersion) {
            $latestVersion = QuestionVersion::where('type', $questionVersion->type)
                ->orderBy('version', 'desc')
                ->lockForUpdate()
                ->first();

            $newVersion = QuestionVersion::create([
                'version' => ($latestVersion ? $latestVersion->version : 0) + 1,
                'type' => $questionVersion->type,
                'name' => $questionVersion->name . ' (Copy)',
                'description' => 'Cloned from ' . $questionVersion->name,
                'is_active' => false,
            ]);

            if ($questionVersion->type === 'st30') {
                foreach ($questionVersion->st30Questions as $q) {
                    ST30Question::create([
                        'version_id' => $newVersion->id,
                        'number' => $q->number,
                        'statement' => $q->statement,
                        'typology_code' => $q->typology_code,
                        'is_active' => $q->is_active,
                    ]);
                }
            } else {
                foreach ($questionVersion->sjtQuestions as $q) {
                    $newQ = SJTQuestion::create([
                        'version_id' => $newVersion->id,
                        'number' => $q->number,
                        'question_text' => $q->question_text,
                        'competency' => $q->competency,
                        'page_number' => $q->page_number,
                        'is_active' => $q->is_active,
                    ]);
                    foreach ($q->options as $opt) {
                        $newQ->options()->create([
                            'option_letter' => $opt->option_letter,
                            'option_text' => $opt->option_text,
                            'score' => $opt->score,
                            'competency_target' => $opt->competency_target
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.questions.index')->with('success', 'Versi berhasil diduplikasi.');
    }

    /**
     * Export PDF Report (REKAP SEMUA VERSI)
     * Mengganti logika sebelumnya yang per-id menjadi global list.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->query('search'); // Ambil kata kunci dari URL

        $query = QuestionVersion::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan Nama
                $q->where('name', 'LIKE', "%{$search}%")
                    // Cari berdasarkan Tipe (SJT / ST30)
                    ->orWhere('type', 'LIKE', "%{$search}%")
                    // Cari berdasarkan Deskripsi
                    ->orWhere('description', 'LIKE', "%{$search}%");

                // Cari berdasarkan Status (Aktif / Tidak Aktif)
                if (stripos($search, 'tidak') !== false || stripos($search, 'non') !== false) {
                    $q->orWhere('is_active', 0);
                } elseif (stripos($search, 'aktif') !== false) {
                    $q->orWhere('is_active', 1);
                }
            });
        }

        // Urutkan: Tipe dulu, lalu Versi terbaru
        $versions = $query->orderBy('type', 'desc')
            ->orderBy('version', 'desc')
            ->get();

        $data = [
            'reportTitle' => 'Laporan Versi Soal',
            'generatedBy' => Auth::user()->name,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'versions'    => $versions,
            'search'      => $search
        ];

        $pdf = Pdf::loadView('admin.questions.versions.pdf.versionReport', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Versi_Soal.pdf');
    }
    /**
     * AJAX Statistics
     */
    public function statistics(QuestionVersion $questionVersion)
    {
        return response()->json([
            'questions_count' => $questionVersion->questions_count,
            'is_active' => $questionVersion->is_active,
        ]);
    }
}
