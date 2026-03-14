<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypologyDescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TypologyController extends Controller
{
    public function index(Request $request)
    {
        $query = TypologyDescription::query();

        // Fix: Mencari di kolom yang benar (strength & weakness)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('typology_name', 'like', "%{$search}%")
                    ->orWhere('typology_code', 'like', "%{$search}%")
                    ->orWhere('strength_description', 'like', "%{$search}%")
                    ->orWhere('weakness_description', 'like', "%{$search}%");
            });
        }

        $typologies = $query->orderBy('typology_code', 'asc')->paginate(10);

        // Statistik
        $totalTypologies = TypologyDescription::count();
        $latestUpdate = TypologyDescription::latest('updated_at')->first()->updated_at ?? now();

        return view('admin.questions.typologies.index', compact(
            'typologies',
            'totalTypologies',
            'latestUpdate'
        ));
    }

    public function create()
    {
        return view('admin.questions.typologies.create');
    }

    public function store(Request $request)
    {
        // Validasi kolom strength dan weakness
        $request->validate([
            'typology_code' => 'required|string|max:10|unique:typology_descriptions,typology_code',
            'typology_name' => 'required|string|max:255',
            'strength_description' => 'nullable|string',
            'weakness_description' => 'nullable|string',
        ]);

        TypologyDescription::create($request->all());

        return redirect()->route('admin.questions.typologies.index')
            ->with('success', 'Tipologi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $typology = TypologyDescription::findOrFail($id);
        return view('admin.questions.typologies.show', compact('typology'));
    }

    public function edit($id)
    {
        $typology = TypologyDescription::findOrFail($id);
        return view('admin.questions.typologies.edit', compact('typology'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'typology_code' => 'required|string|max:10|unique:typology_descriptions,typology_code,' . $id,
            'typology_name' => 'required|string|max:255',
            'strength_description' => 'nullable|string',
            'weakness_description' => 'nullable|string',
        ]);

        $typology = TypologyDescription::findOrFail($id);
        $typology->update($request->all());

        return redirect()->route('admin.questions.typologies.index')
            ->with('success', 'Tipologi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $typology = TypologyDescription::findOrFail($id);
        $typology->delete();

        return redirect()->route('admin.questions.typologies.index')
            ->with('success', 'Tipologi berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = TypologyDescription::query();

        // TAMBAHKAN LOGIC SEARCH INI
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('typology_name', 'like', "%{$search}%")
                    ->orWhere('typology_code', 'like', "%{$search}%")
                    ->orWhere('strength_description', 'like', "%{$search}%")
                    ->orWhere('weakness_description', 'like', "%{$search}%");
            });
        }

        $typologies = $query->orderBy('typology_code', 'asc')->get();

        $data = [
            'reportTitle' => 'Laporan Data Tipologi (Typologies)',
            'generatedBy' => Auth::user()->name,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $typologies
        ];

        $pdf = Pdf::loadView('admin.questions.typologies.pdf.typologyReport', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Tipologi.pdf');
    }
}
