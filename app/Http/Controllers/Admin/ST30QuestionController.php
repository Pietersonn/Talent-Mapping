<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ST30Question;
use App\Models\QuestionVersion;
use App\Models\TypologyDescription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ST30QuestionController extends Controller
{
    public function index(Request $request)
    {
        $activeVersion   = QuestionVersion::getActive('st30');
        $selectedVersion = $request->filled('version') ? QuestionVersion::find($request->version) : $activeVersion;
        $versions        = QuestionVersion::where('jenis', 'st30')->orderBy('versi', 'desc')->get();

        $questions = collect(); $typologyStats = [];

        if ($selectedVersion) {
            $query = ST30Question::where('id_versi', $selectedVersion->id)
                ->with(['questionVersion', 'typologyDescription'])->orderBy('nomor');

            $typologyStats = $query->get()->groupBy('kode_tipologi')->map(fn($i) => $i->count())->toArray();
            $questions     = $query->paginate(10);
        }

        $typologies = TypologyDescription::orderBy('kode_tipologi')->get();
        return view('admin.questions.st30.index', compact('questions', 'selectedVersion', 'activeVersion', 'versions', 'typologyStats', 'typologies'));
    }

    public function create(Request $request)
    {
        $selectedVersion = $request->filled('version')
            ? QuestionVersion::find($request->version)
            : QuestionVersion::getActive('st30');

        if (!$selectedVersion) return redirect()->route('admin.questions.index')->with('error', 'Tidak ada versi ST-30. Buat versi terlebih dahulu.');

        $total = ST30Question::where('id_versi', $selectedVersion->id)->count();
        if ($total >= 30) return redirect()->route('admin.questions.st30.index', ['version' => $selectedVersion->id])->with('error', 'Versi ini sudah memiliki 30 pertanyaan (maksimum).');

        $existing  = ST30Question::where('id_versi', $selectedVersion->id)->pluck('nomor')->toArray();
        $nextNumber = 1;
        for ($i = 1; $i <= 30; $i++) { if (!in_array($i, $existing)) { $nextNumber = $i; break; } }

        $typologies = TypologyDescription::orderBy('nama_tipologi')->get();
        $versions   = QuestionVersion::where('jenis', 'st30')->orderBy('versi', 'desc')->get();
        return view('admin.questions.st30.create', compact('selectedVersion', 'nextNumber', 'typologies', 'versions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_versi'      => 'required|exists:versi_soal,id',
            'nomor'         => 'required|integer|min:1|max:30',
            'pernyataan'    => 'required|string|max:500',
            'kode_tipologi' => 'required|exists:deskripsi_tipologi,kode_tipologi',
        ]);

        if (ST30Question::where('id_versi', $request->id_versi)->where('nomor', $request->nomor)->exists()) {
            return back()->withErrors(['nomor' => 'Nomor pertanyaan sudah ada pada versi ini.'])->withInput();
        }

        ST30Question::create([
            'id_versi'     => $request->id_versi,
            'nomor'        => $request->nomor,
            'pernyataan'   => $request->pernyataan,
            'kode_tipologi'=> $request->kode_tipologi,
        ]);

        return redirect()->route('admin.questions.st30.index', ['version' => $request->id_versi])
            ->with('success', 'Pertanyaan ST-30 berhasil dibuat.');
    }

    public function show(ST30Question $st30Question)
    {
        $st30Question->load(['questionVersion', 'typologyDescription']);
        return view('admin.questions.st30.show', compact('st30Question'));
    }

    public function edit(ST30Question $st30Question)
    {
        $st30Question->load(['questionVersion']);
        $typologies = TypologyDescription::orderBy('nama_tipologi')->get();
        $versions   = QuestionVersion::where('jenis', 'st30')->orderBy('versi', 'desc')->get();
        return view('admin.questions.st30.edit', compact('st30Question', 'typologies', 'versions'));
    }

    public function update(Request $request, ST30Question $st30Question)
    {
        $request->validate([
            'nomor'         => 'required|integer|min:1|max:30',
            'pernyataan'    => 'required|string|max:500',
            'kode_tipologi' => 'required|exists:deskripsi_tipologi,kode_tipologi',
        ]);

        if (ST30Question::where('id_versi', $st30Question->id_versi)->where('nomor', $request->nomor)->where('id', '!=', $st30Question->id)->exists()) {
            return back()->withErrors(['nomor' => 'Nomor pertanyaan sudah ada pada versi ini.'])->withInput();
        }

        $st30Question->update(['nomor' => $request->nomor, 'pernyataan' => $request->pernyataan, 'kode_tipologi' => $request->kode_tipologi]);

        return redirect()->route('admin.questions.st30.index', ['version' => $st30Question->id_versi])
            ->with('success', 'Pertanyaan ST-30 berhasil diperbarui.');
    }

    public function destroy(ST30Question $st30Question)
    {
        $versionId = $st30Question->id_versi;
        $st30Question->delete();
        return redirect()->route('admin.questions.st30.index', ['version' => $versionId])
            ->with('success', 'Pertanyaan ST-30 berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['id_versi' => 'required|exists:versi_soal,id', 'import_file' => 'required|file|mimes:csv,xlsx']);
        return redirect()->route('admin.questions.st30.index', ['version' => $request->id_versi])->with('info', 'Fitur impor akan segera tersedia.');
    }

    public function export(Request $request)
    {
        $versionId = $request->get('version') ?? QuestionVersion::getActive('st30')?->id;
        if (!$versionId) return back()->with('error', 'Silakan pilih versi untuk diekspor.');

        $version = QuestionVersion::find($versionId);
        $search  = $request->get('search');
        $query   = ST30Question::where('id_versi', $versionId)->with(['typologyDescription']);

        if ($search) {
            $query->where(fn($q) => $q->where('pernyataan', 'like', "%{$search}%")
                ->orWhere('nomor', 'like', "%{$search}%")
                ->orWhere('kode_tipologi', 'like', "%{$search}%")
                ->orWhereHas('typologyDescription', fn($s) => $s->where('nama_tipologi', 'like', "%{$search}%")));
        }

        $pdf = Pdf::loadView('admin.questions.st30.pdf.st30Report', [
            'reportTitle' => 'Laporan Bank Soal ST-30',
            'versionName' => $version->versi.' - '.$version->nama.($search ? " (Filter: {$search})" : ''),
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $query->orderBy('nomor')->get(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Soal-ST30-v'.$version->versi.'.pdf');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'id_versi'           => 'required|exists:versi_soal,id',
            'questions'          => 'required|array',
            'questions.*.id'     => 'required|exists:soal_st30,id',
            'questions.*.nomor'  => 'required|integer|min:1|max:30',
        ]);

        foreach ($request->questions as $q) {
            ST30Question::where('id', $q['id'])->update(['nomor' => $q['nomor']]);
        }

        return response()->json(['success' => true]);
    }
}
