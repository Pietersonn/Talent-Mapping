<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetencyDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CompetencyController extends Controller
{
    public function index(Request $request)
    {
        $query = CompetencyDescription::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kompetensi', 'like', "%{$search}%")
                  ->orWhere('kode_kompetensi', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kekuatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kelemahan', 'like', "%{$search}%");
            });
        }

        $totalCompetencies = CompetencyDescription::count();
        $latestUpdate = CompetencyDescription::latest('updated_at')->first()?->updated_at ?? now();
        $competencies = $query->orderBy('kode_kompetensi')->simplePaginate(10);

        return view('admin.questions.competencies.index', compact('competencies', 'totalCompetencies', 'latestUpdate'));
    }

    public function create()
    {
        return view('admin.questions.competencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kompetensi'          => 'required|string|max:30|unique:deskripsi_kompetensi,kode_kompetensi',
            'nama_kompetensi'          => 'required|string|max:255',
            'deskripsi_kekuatan'       => 'nullable|string',
            'deskripsi_kelemahan'      => 'nullable|string',
            'aktivitas_pengembangan'   => 'nullable|string',
            'rekomendasi_pelatihan'    => 'nullable|string',
        ]);

        CompetencyDescription::create($request->only([
            'kode_kompetensi', 'nama_kompetensi', 'deskripsi_kekuatan',
            'deskripsi_kelemahan', 'aktivitas_pengembangan', 'rekomendasi_pelatihan',
        ]));

        return redirect()->route('admin.questions.competencies.index')
            ->with('success', 'Kompetensi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $competency = CompetencyDescription::findOrFail($id);
        return view('admin.questions.competencies.show', compact('competency'));
    }

    public function edit($id)
    {
        $competency = CompetencyDescription::findOrFail($id);
        return view('admin.questions.competencies.edit', compact('competency'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kompetensi'        => 'required|string|max:30|unique:deskripsi_kompetensi,kode_kompetensi,'.$id,
            'nama_kompetensi'        => 'required|string|max:255',
            'deskripsi_kekuatan'     => 'nullable|string',
            'deskripsi_kelemahan'    => 'nullable|string',
            'aktivitas_pengembangan' => 'nullable|string',
            'rekomendasi_pelatihan'  => 'nullable|string',
        ]);

        CompetencyDescription::findOrFail($id)->update($request->only([
            'kode_kompetensi', 'nama_kompetensi', 'deskripsi_kekuatan',
            'deskripsi_kelemahan', 'aktivitas_pengembangan', 'rekomendasi_pelatihan',
        ]));

        return redirect()->route('admin.questions.competencies.index')
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        CompetencyDescription::findOrFail($id)->delete();
        return redirect()->route('admin.questions.competencies.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = CompetencyDescription::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kompetensi', 'like', "%{$search}%")
                  ->orWhere('kode_kompetensi', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kekuatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kelemahan', 'like', "%{$search}%");
            });
        }

        $rows = $query->orderBy('kode_kompetensi')->get();

        $pdf = Pdf::loadView('admin.questions.competencies.pdf.competencyReport', [
            'reportTitle' => 'Laporan Bank Data Kompetensi',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $rows,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Kompetensi.pdf');
    }
}
