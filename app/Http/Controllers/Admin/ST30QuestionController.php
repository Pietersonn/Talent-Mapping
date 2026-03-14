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
    /**
     * Display a listing of ST-30 questions
     */
    public function index(Request $request)
    {
        $activeVersion = QuestionVersion::getActive('st30');

        $selectedVersion = $request->filled('version')
            ? QuestionVersion::find($request->version)
            : $activeVersion;

        $versions = QuestionVersion::where('type', 'st30')
            ->orderBy('version', 'desc')
            ->get();

        $questions = collect();
        $typologyStats = [];

        if ($selectedVersion) {
            // 1. Buat Query Dasar
            $query = ST30Question::where('version_id', $selectedVersion->id)
                ->with(['questionVersion', 'typologyDescription'])
                ->orderBy('number');

            // 2. Ambil SEMUA data khusus untuk menghitung Statistik (agar chart/total akurat)
            $allDataForStats = $query->get();

            $typologyStats = $allDataForStats->groupBy('typology_code')
                ->map(fn($items) => $items->count())
                ->toArray();

            // 3. Ambil data PAGINATION (10 per halaman) untuk Tabel
            $questions = $query->paginate(10);
        }

        $typologies = TypologyDescription::orderBy('typology_code')->get();

        return view('admin.questions.st30.index', compact(
            'questions',
            'selectedVersion',
            'activeVersion',
            'versions',
            'typologyStats',
            'typologies'
        ));
    }

    /**
     * Show the form for creating a new ST-30 question
     */
    public function create(Request $request)
    {
        $selectedVersion = $request->filled('version')
            ? QuestionVersion::find($request->version)
            : QuestionVersion::getActive('st30');

        if (!$selectedVersion) {
            return redirect()->route('admin.questions.index')
                ->with('error', 'Tidak ada versi ST-30 yang tersedia. Silakan buat versi terlebih dahulu.');
        }

        // Cek jumlah total soal (count)
        $totalQuestions = ST30Question::where('version_id', $selectedVersion->id)->count();

        if ($totalQuestions >= 30) {
            return redirect()->route('admin.questions.st30.index', ['version' => $selectedVersion->id])
                ->with('error', 'Versi ini sudah memiliki 30 pertanyaan (maksimum).');
        }

        // Cari nomor kosong terkecil (1-30)
        $existingNumbers = ST30Question::where('version_id', $selectedVersion->id)
            ->pluck('number')
            ->toArray();

        $nextNumber = 1;
        for ($i = 1; $i <= 30; $i++) {
            if (!in_array($i, $existingNumbers)) {
                $nextNumber = $i;
                break;
            }
        }

        $typologies = TypologyDescription::orderBy('typology_name')->get();
        $versions   = QuestionVersion::where('type', 'st30')->orderBy('version', 'desc')->get();

        return view('admin.questions.st30.create', compact(
            'selectedVersion',
            'nextNumber',
            'typologies',
            'versions'
        ));
    }

    /**
     * Store a newly created ST-30 question
     */
    public function store(Request $request)
    {
        $request->validate([
            'version_id'    => 'required|exists:question_versions,id',
            'number'        => 'required|integer|min:1|max:30',
            'statement'     => 'required|string|max:500',
            'typology_code' => 'required|exists:typology_descriptions,typology_code',
        ]);

        $exists = ST30Question::where('version_id', $request->version_id)
            ->where('number', $request->number)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'number' => 'Nomor pertanyaan sudah ada pada versi ini.'
            ])->withInput();
        }

        ST30Question::create([
            'version_id'    => $request->version_id,
            'number'        => $request->number,
            'statement'     => $request->statement,
            'typology_code' => $request->typology_code,
        ]);

        return redirect()->route('admin.questions.st30.index', ['version' => $request->version_id])
            ->with('success', 'Pertanyaan ST-30 berhasil dibuat.');
    }

    /**
     * Display the specified ST-30 question
     */
    public function show(ST30Question $st30Question)
    {
        $st30Question->load(['questionVersion', 'typologyDescription']);
        return view('admin.questions.st30.show', compact('st30Question'));
    }

    /**
     * Show the form for editing the specified ST-30 question
     */
    public function edit(ST30Question $st30Question)
    {
        $st30Question->load(['questionVersion']);
        $typologies = TypologyDescription::orderBy('typology_name')->get();
        $versions   = QuestionVersion::where('type', 'st30')->orderBy('version', 'desc')->get();

        return view('admin.questions.st30.edit', compact('st30Question', 'typologies', 'versions'));
    }

    /**
     * Update the specified ST-30 question
     */
    public function update(Request $request, ST30Question $st30Question)
    {
        $request->validate([
            'number'        => 'required|integer|min:1|max:30',
            'statement'     => 'required|string|max:500',
            'typology_code' => 'required|exists:typology_descriptions,typology_code',
        ]);

        $exists = ST30Question::where('version_id', $st30Question->version_id)
            ->where('number', $request->number)
            ->where('id', '!=', $st30Question->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'number' => 'Nomor pertanyaan sudah ada pada versi ini.'
            ])->withInput();
        }

        $st30Question->update([
            'number'        => $request->number,
            'statement'     => $request->statement,
            'typology_code' => $request->typology_code,
        ]);

        return redirect()->route('admin.questions.st30.index', ['version' => $st30Question->version_id])
            ->with('success', 'Pertanyaan ST-30 berhasil diperbarui.');
    }

    /**
     * Remove the specified ST-30 question
     */
    public function destroy(ST30Question $st30Question)
    {
        if (method_exists($st30Question, 'hasResponses') && $st30Question->hasResponses()) {
            return redirect()->route('admin.questions.st30.index', ['version' => $st30Question->version_id])
                ->with('error', 'Tidak dapat menghapus pertanyaan yang sudah digunakan dalam tes.');
        }

        $versionId = $st30Question->version_id;
        $st30Question->delete();

        return redirect()->route('admin.questions.st30.index', ['version' => $versionId])
            ->with('success', 'Pertanyaan ST-30 berhasil dihapus.');
    }

    /**
     * Bulk import ST-30 questions
     */
    public function import(Request $request)
    {
        $request->validate([
            'version_id'  => 'required|exists:question_versions,id',
            'import_file' => 'required|file|mimes:csv,xlsx'
        ]);

        return redirect()->route('admin.questions.st30.index', ['version' => $request->version_id])
            ->with('info', 'Fitur impor akan segera tersedia.');
    }

    /**
     * Bulk export ST-30 questions
     */
    public function export(Request $request)
    {
        $versionId = $request->get('version');
        $search = $request->get('search');

        if (!$versionId) {
            $activeVersion = QuestionVersion::getActive('st30');
            if ($activeVersion) {
                $versionId = $activeVersion->id;
            } else {
                return redirect()->back()->with('error', 'Silakan pilih versi untuk diekspor.');
            }
        }

        $version = QuestionVersion::find($versionId);

        $query = ST30Question::where('version_id', $versionId)
            ->with(['typologyDescription']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('statement', 'like', '%' . $search . '%')
                  ->orWhere('number', 'like', '%' . $search . '%')
                  ->orWhere('typology_code', 'like', '%' . $search . '%')
                  ->orWhereHas('typologyDescription', function($subQ) use ($search) {
                      $subQ->where('typology_name', 'like', '%' . $search . '%');
                  });
            });
        }

        $questions = $query->orderBy('number')->get();

        $data = [
            'reportTitle' => 'Laporan Bank Soal ST-30',
            'versionName' => $version->version . ' - ' . $version->name . ($search ? ' (Filter: '.$search.')' : ''),
            'generatedBy' => Auth::user()->name,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $questions
        ];

        $pdf = Pdf::loadView('admin.questions.st30.pdf.st30Report', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Soal-ST30-v' . $version->version . '.pdf');
    }

    /**
     * Reorder questions
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'version_id'         => 'required|exists:question_versions,id',
            'questions'          => 'required|array',
            'questions.*.id'     => 'required|exists:st30_questions,id',
            'questions.*.number' => 'required|integer|min:1|max:30',
        ]);

        foreach ($request->questions as $q) {
            ST30Question::where('id', $q['id'])->update(['number' => $q['number']]);
        }

        return response()->json(['success' => true]);
    }
}
