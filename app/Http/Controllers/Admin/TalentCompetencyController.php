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
        $activeVersion   = QuestionVersion::getActive('tk');
        $selectedVersion = $request->has('version') ? QuestionVersion::find($request->version) : $activeVersion;
        $versions        = QuestionVersion::where('jenis', 'tk')->orderBy('versi', 'desc')->get();

        $questions = collect(); $competencyStats = [];

        if ($selectedVersion) {
            $query = TalentCompetencyQuestion::where('id_versi', $selectedVersion->id)
                ->with(['questionVersion', 'competencyDescription', 'options'])->orderBy('nomor');

            $competencyStats = $query->get()->groupBy('kode_kompetensi')->map(fn($i) => $i->count())->toArray();
            $questions       = $query->paginate(10);
        }

        $competencies = CompetencyDescription::orderBy('kode_kompetensi')->get();
        return view('admin.questions.talent-competency.index', compact('questions', 'selectedVersion', 'activeVersion', 'versions', 'competencyStats', 'competencies'));
    }

    public function create(Request $request)
    {
        $selectedVersion = $request->has('version')
            ? QuestionVersion::find($request->version)
            : QuestionVersion::getActive('tk');

        if (!$selectedVersion) return redirect()->route('admin.questions.index')->with('error', 'Tidak ada versi TK. Buat versi terlebih dahulu.');

        $nextNumber = TalentCompetencyQuestion::where('id_versi', $selectedVersion->id)->max('nomor') + 1;
        if ($nextNumber > 50) return redirect()->route('admin.questions.talent-competency.index', ['version' => $selectedVersion->id])->with('error', 'Versi ini sudah mencapai batas maksimum 50 pertanyaan.');

        $competencies = CompetencyDescription::orderBy('nama_kompetensi')->get();
        $versions     = QuestionVersion::where('jenis', 'tk')->orderBy('versi', 'desc')->get();
        return view('admin.questions.talent-competency.create', compact('selectedVersion', 'nextNumber', 'competencies', 'versions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_versi'        => 'required|exists:versi_soal,id',
            'nomor'           => 'required|integer|min:1|max:50',
            'teks_pertanyaan' => 'required|string|max:1000',
            'kode_kompetensi' => 'required|exists:deskripsi_kompetensi,kode_kompetensi',
            'options'         => 'required|array|size:5',
            'options.*.teks_pilihan' => 'required|string|max:500',
            'options.*.skor'         => 'required|integer|min:0|max:4',
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
            ]);

            foreach ($request->options as $index => $optionData) {
                TalentCompetencyOption::create([
                    'id_soal'           => $question->id,
                    'huruf_pilihan'     => ['a','b','c','d','e'][$index],
                    'teks_pilihan'      => $optionData['teks_pilihan'],
                    'skor'              => $optionData['skor'],
                    'target_kompetensi' => $request->kode_kompetensi,
                ]);
            }
        });

        return redirect()->route('admin.questions.talent-competency.index', ['version' => $request->id_versi])
            ->with('success', 'Pertanyaan TK beserta opsi berhasil dibuat.');
    }

    public function show(TalentCompetencyQuestion $talentCompetencyQuestion)
    {
        $talentCompetencyQuestion->load(['questionVersion', 'competencyDescription', 'options']);
        return view('admin.questions.talent-competency.show', compact('talentCompetencyQuestion'));
    }

    public function edit(TalentCompetencyQuestion $talentCompetencyQuestion)
    {
        $talentCompetencyQuestion->load(['questionVersion', 'options']);
        $competencies = CompetencyDescription::orderBy('nama_kompetensi')->get();
        $versions     = QuestionVersion::where('jenis', 'tk')->orderBy('versi', 'desc')->get();
        return view('admin.questions.talent-competency.edit', compact('talentCompetencyQuestion', 'competencies', 'versions'));
    }

    public function update(Request $request, TalentCompetencyQuestion $talentCompetencyQuestion)
    {
        $request->validate([
            'nomor'           => 'required|integer|min:1|max:50',
            'teks_pertanyaan' => 'required|string|max:1000',
            'kode_kompetensi' => 'required|exists:deskripsi_kompetensi,kode_kompetensi',
            'options'         => 'required|array|size:5',
            'options.*.teks_pilihan' => 'required|string|max:500',
            'options.*.skor'         => 'required|integer|min:0|max:4',
        ]);

        if (TalentCompetencyQuestion::where('id_versi', $talentCompetencyQuestion->id_versi)->where('nomor', $request->nomor)->where('id', '!=', $talentCompetencyQuestion->id)->exists()) {
            return back()->withErrors(['nomor' => 'Nomor pertanyaan sudah ada di versi ini.'])->withInput();
        }

        DB::transaction(function () use ($request, $talentCompetencyQuestion) {
            $talentCompetencyQuestion->update([
                'nomor'           => $request->nomor,
                'teks_pertanyaan' => $request->teks_pertanyaan,
                'kode_kompetensi' => $request->kode_kompetensi,
            ]);

            foreach ($request->options as $index => $optionData) {
                $option = $talentCompetencyQuestion->options()->where('huruf_pilihan', ['a','b','c','d','e'][$index])->first();
                if ($option) {
                    $option->update([
                        'teks_pilihan'      => $optionData['teks_pilihan'],
                        'skor'              => $optionData['skor'],
                        'target_kompetensi' => $request->kode_kompetensi,
                    ]);
                }
            }
        });

        return redirect()->route('admin.questions.talent-competency.index', ['version' => $talentCompetencyQuestion->id_versi])
            ->with('success', 'Pertanyaan TK berhasil diperbarui.');
    }

    public function destroy(TalentCompetencyQuestion $talentCompetencyQuestion)
    {
        $versionId = $talentCompetencyQuestion->id_versi;
        DB::transaction(function () use ($talentCompetencyQuestion) {
            $talentCompetencyQuestion->options()->delete();
            $talentCompetencyQuestion->delete();
        });
        return redirect()->route('admin.questions.talent-competency.index', ['version' => $versionId])
            ->with('success', 'Pertanyaan TK berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $versionId = $request->get('version') ?? QuestionVersion::getActive('tk')?->id;
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

        $pdf = Pdf::loadView('admin.questions.talent-competency.pdf.tkReport', [
            'reportTitle' => 'Laporan Bank Soal Talenta Kompetensi',
            'versionName' => $version->versi.' - '.$version->nama.($search ? " (Filter: {$search})" : ''),
            'generatedBy' => Auth::user()->nama,
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
