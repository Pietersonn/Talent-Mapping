<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetencyDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CompetencyController extends Controller
{
    /**
     * Display a listing of competencies with search and pagination
     */
    public function index(Request $request)
    {
        $query = CompetencyDescription::query();

        // Fitur Pencarian (Debounce di View) mencakup Nama, Kode, Kekuatan, dan Kelemahan
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('competency_name', 'like', "%{$search}%")
                    ->orWhere('competency_code', 'like', "%{$search}%")
                    ->orWhere('strength_description', 'like', "%{$search}%")
                    ->orWhere('weakness_description', 'like', "%{$search}%");
            });
        }

        // Statistik untuk Cards
        $totalCompetencies = CompetencyDescription::count();
        $latestUpdate = CompetencyDescription::latest('updated_at')->first()->updated_at ?? now();

        // Gunakan simplePaginate(10) agar sesuai permintaan (hanya tombol geser Prev/Next)
        $competencies = $query->orderBy('competency_code', 'asc')->simplePaginate(10);

        return view('admin.questions.competencies.index', compact(
            'competencies',
            'totalCompetencies',
            'latestUpdate'
        ));
    }

    public function create()
    {
        return view('admin.questions.competencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'competency_code' => 'required|string|max:30|unique:competency_descriptions,competency_code',
            'competency_name' => 'required|string|max:255',
            'strength_description' => 'nullable|string',
            'weakness_description' => 'nullable|string',
            'improvement_activity' => 'nullable|string',
            'training_recommendations' => 'nullable|string',
        ]);

        CompetencyDescription::create($request->all());

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
            'competency_code' => 'required|string|max:30|unique:competency_descriptions,competency_code,' . $id,
            'competency_name' => 'required|string|max:255',
            'strength_description' => 'nullable|string',
            'weakness_description' => 'nullable|string',
            'improvement_activity' => 'nullable|string',
            'training_recommendations' => 'nullable|string',
        ]);

        $competency = CompetencyDescription::findOrFail($id);
        $competency->update($request->all());

        return redirect()->route('admin.questions.competencies.index')
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $competency = CompetencyDescription::findOrFail($id);
        $competency->delete();

        return redirect()->route('admin.questions.competencies.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }

    /**
     * Export Competencies to PDF
     */
    public function export(Request $request)
    {
        $query = CompetencyDescription::query();

        // TAMBAHKAN LOGIC SEARCH INI
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('competency_name', 'like', "%{$search}%")
                    ->orWhere('competency_code', 'like', "%{$search}%")
                    ->orWhere('strength_description', 'like', "%{$search}%")
                    ->orWhere('weakness_description', 'like', "%{$search}%");
            });
        }

        $rows = $query->orderBy('competency_code', 'asc')->get();

        $data = [
            'reportTitle' => 'Laporan Bank Data Kompetensi SJT',
            'generatedBy' => Auth::user()->name,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $rows
        ];

        $pdf = Pdf::loadView('admin.questions.competencies.pdf.competencyReport', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Kompetensi-SJT.pdf');
    }
}
