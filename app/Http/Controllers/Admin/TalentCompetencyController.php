<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TalentCompetencyQuestion;
use App\Models\TalentCompetencyOption;
use App\Models\QuestionVersion;
use App\Models\CompetencyDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TalentCompetencyController extends Controller
{
    public function index(Request $request)
    {
        $activeVersion   = QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();
        $selectedVersion = $request->has('version') ? QuestionVersion::find($request->version) : $activeVersion;
        $versions        = QuestionVersion::where('jenis', 'tk')->orderBy('versi', 'desc')->get();

        $questions = collect();
        $competencyStats = [];

        if ($selectedVersion) {
            $query = TalentCompetencyQuestion::where('id_versi', $selectedVersion->id)
                ->with(['questionVersion', 'competencyDescription', 'options'])->orderBy('nomor');

            $competencyStats = $query->get()->groupBy('kode_kompetensi')->map(fn($i) => $i->count())->toArray();
            $questions       = $query->paginate(10);
        }

        $competencies = CompetencyDescription::orderBy('nama_kompetensi')->get();

        return view('admin.questions.talentcompetency.index', compact('questions', 'selectedVersion', 'activeVersion', 'versions', 'competencyStats', 'competencies'));
    }

    public function create(Request $request)
    {
        $selectedVersion = $request->has('version')
            ? QuestionVersion::find($request->version)
            : QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();

        if (!$selectedVersion) {
            return redirect()->route('admin.questions.index')->with('error', 'Tidak ada versi TK. Buat versi terlebih dahulu.');
        }

        // PERBAIKAN 1: Hitung jumlah soal secara riil, bukan dari nomor max.
        $existingNumbers = TalentCompetencyQuestion::where('id_versi', $selectedVersion->id)->pluck('nomor')->toArray();

        if (count($existingNumbers) >= 50) {
            return redirect()->route('admin.questions.tk.index', ['version' => $selectedVersion->id])
                ->with('error', 'Versi ini sudah mencapai batas maksimum 50 pertanyaan.');
        }

        // PERBAIKAN 2: Cari otomatis nomor yang "bolong" / kosong dari 1 sampai 50
        $nextNumber = 1;
        while (in_array($nextNumber, $existingNumbers)) {
            $nextNumber++;
        }

        $competencies = CompetencyDescription::orderBy('nama_kompetensi')->get();
        $versions     = QuestionVersion::where('jenis', 'tk')->orderBy('versi', 'desc')->get();

        return view('admin.questions.talentcompetency.create', compact('selectedVersion', 'nextNumber', 'competencies', 'versions'));
    }

    public function store(Request $request)
    {
        // Validasi menyesuaikan input di create.blade.php (options.*.teks)
        $request->validate([
            'id_versi'        => 'required|exists:versi_soal,id',
            'nomor'           => 'required|integer|min:1|max:50',
            'teks_pertanyaan' => 'required|string|max:1000',
            'kode_kompetensi' => 'required|exists:deskripsi_kompetensi,kode_kompetensi',
            'options'         => 'required|array|size:5',
            'options.*.teks'  => 'required|string|max:500',
            'options.*.skor'  => 'required|integer|min:0|max:5',
        ]);

        if (TalentCompetencyQuestion::where('id_versi', $request->id_versi)->where('nomor', $request->nomor)->exists()) {
            return back()->withErrors(['nomor' => 'Nomor pertanyaan sudah ada di versi ini.'])->withInput();
        }

        DB::transaction(function () use ($request) {
            $question = TalentCompetencyQuestion::create([
                'id_versi'        => $request->id_versi,
                'nomor'           => $request->nomor,
                'teks_pertanyaan' => $request->teks_pertanyaan,
                'kode_kompetensi' => $request->kode_kompetensi,
                'aktif'           => $request->has('aktif') ? true : false,
            ]);

            foreach ($request->options as $index => $optionData) {
                TalentCompetencyOption::create([
                    'id_soal'           => $question->id,
                    'huruf_pilihan'     => ['a','b','c','d','e'][$index],
                    'teks_pilihan'      => $optionData['teks'], // Menangkap name="...[teks]"
                    'skor'              => $optionData['skor'],
                    'target_kompetensi' => $request->kode_kompetensi,
                ]);
            }
        });

        return redirect()->route('admin.questions.tk.index', ['version' => $request->id_versi])
            ->with('success', 'Pertanyaan TK beserta opsi berhasil dibuat.');
    }

    // PERBAIKAN 3: Tambahkan `string` pada $id untuk menghilangkan Error P1132
    public function show(string $id)
    {
        $talentCompetencyQuestion = TalentCompetencyQuestion::with(['questionVersion', 'competencyDescription', 'options'])->findOrFail($id);
        return view('admin.questions.talentcompetency.show', compact('talentCompetencyQuestion'));
    }

    // PERBAIKAN 3: Tambahkan `string` pada $id
    public function edit(string $id)
    {
        $talentCompetencyQuestion = TalentCompetencyQuestion::with(['questionVersion', 'options'])->findOrFail($id);
        $competencies = CompetencyDescription::orderBy('nama_kompetensi')->get();
        $versions     = QuestionVersion::where('jenis', 'tk')->orderBy('versi', 'desc')->get();

        return view('admin.questions.talentcompetency.edit', compact('talentCompetencyQuestion', 'competencies', 'versions'));
    }

    // PERBAIKAN 3: Tambahkan `string` pada $id
    public function update(Request $request, string $id)
    {
        $talentCompetencyQuestion = TalentCompetencyQuestion::findOrFail($id);

        // Validasi menyesuaikan input di edit.blade.php (options.*.teks_pilihan)
        $request->validate([
            'nomor'           => 'required|integer|min:1|max:50',
            'teks_pertanyaan' => 'required|string|max:1000',
            'kode_kompetensi' => 'required|exists:deskripsi_kompetensi,kode_kompetensi',
            'options'         => 'required|array|size:5',
            'options.*.teks_pilihan' => 'required|string|max:500',
            'options.*.skor'         => 'required|integer|min:0|max:5',
        ]);

        if (TalentCompetencyQuestion::where('id_versi', $talentCompetencyQuestion->id_versi)
                ->where('nomor', $request->nomor)
                ->where('id', '!=', $talentCompetencyQuestion->id)->exists()) {
            return back()->withErrors(['nomor' => 'Nomor pertanyaan sudah ada di versi ini.'])->withInput();
        }

        DB::transaction(function () use ($request, $talentCompetencyQuestion) {
            $talentCompetencyQuestion->update([
                'nomor'           => $request->nomor,
                'teks_pertanyaan' => $request->teks_pertanyaan,
                'kode_kompetensi' => $request->kode_kompetensi,
                'aktif'           => $request->has('aktif') ? true : false,
            ]);

            foreach ($request->options as $index => $optionData) {
                $option = $talentCompetencyQuestion->options()->where('huruf_pilihan', ['a','b','c','d','e'][$index])->first();
                if ($option) {
                    $option->update([
                        'teks_pilihan'      => $optionData['teks_pilihan'], // Menangkap name="...[teks_pilihan]"
                        'skor'              => $optionData['skor'],
                        'target_kompetensi' => $request->kode_kompetensi,
                    ]);
                }
            }
        });

        return redirect()->route('admin.questions.tk.index', ['version' => $talentCompetencyQuestion->id_versi])
            ->with('success', 'Pertanyaan TK berhasil diperbarui.');
    }

    // PERBAIKAN 3: Tambahkan `string` pada $id
    public function destroy(string $id)
    {
        $talentCompetencyQuestion = TalentCompetencyQuestion::findOrFail($id);
        $versionId = $talentCompetencyQuestion->id_versi;

        DB::transaction(function () use ($talentCompetencyQuestion) {
            $talentCompetencyQuestion->options()->delete();
            $talentCompetencyQuestion->delete();
        });

        return redirect()->route('admin.questions.tk.index', ['version' => $versionId])
            ->with('success', 'Pertanyaan TK berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $activeVersion = QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();
        $versionId = $request->get('version') ?? ($activeVersion ? $activeVersion->id : null);

        if (!$versionId) return back()->with('error', 'Silakan pilih versi untuk diekspor.');

        $version = QuestionVersion::find($versionId);
        $search  = $request->get('search');
        $query   = TalentCompetencyQuestion::where('id_versi', $versionId)->with(['options', 'competencyDescription']);

        if ($search) {
            $query->where(fn($q) => $q->where('teks_pertanyaan', 'like', "%{$search}%")
                ->orWhere('nomor', 'like', "%{$search}%")
                ->orWhere('kode_kompetensi', 'like', "%{$search}%")
                ->orWhereHas('competencyDescription', fn($s) => $s->where('nama_kompetensi', 'like', "%{$search}%")));
        }

        $pdf = Pdf::loadView('admin.questions.talentcompetency.pdf.tkReport', [
            'reportTitle' => 'Laporan Bank Soal Talenta Kompetensi',
            'versionName' => $version->versi.' - '.$version->nama.($search ? " (Filter: {$search})" : ''),
            'generatedBy' => Auth::user()->nama ?? Auth::user()->name,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $query->orderBy('nomor')->get(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Soal-TK-v'.$version->versi.'.pdf');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'id_versi'            => 'required|exists:versi_soal,id',
            'questions'           => 'required|array',
            'questions.*.id'      => 'required|exists:soal_tk,id',
            'questions.*.nomor'   => 'required|integer|min:1|max:50',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->questions as $q) {
                TalentCompetencyQuestion::where('id', $q['id'])->update(['nomor' => $q['nomor']]);
            }
        });

        return response()->json(['success' => true]);
    }
}
