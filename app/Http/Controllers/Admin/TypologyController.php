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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_tipologi', 'like', "%{$search}%")
                  ->orWhere('kode_tipologi', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kekuatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kelemahan', 'like', "%{$search}%");
            });
        }

        $typologies  = $query->orderBy('kode_tipologi')->paginate(10);
        $totalTypologies = TypologyDescription::count();
        $latestUpdate    = TypologyDescription::latest('updated_at')->first()?->updated_at ?? now();

        return view('admin.questions.typologies.index', compact('typologies', 'totalTypologies', 'latestUpdate'));
    }

    public function create()
    {
        return view('admin.questions.typologies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_tipologi'      => 'required|string|max:10|unique:deskripsi_tipologi,kode_tipologi',
            'nama_tipologi'      => 'required|string|max:255',
            'deskripsi_kekuatan' => 'nullable|string',
            'deskripsi_kelemahan'=> 'nullable|string',
        ]);

        TypologyDescription::create($request->only([
            'kode_tipologi', 'nama_tipologi', 'deskripsi_kekuatan', 'deskripsi_kelemahan',
        ]));

        return redirect()->route('admin.questions.typologies.index')
            ->with('success', 'Tipologi berhasil ditambahkan.');
    }

    public function show($id)
    {
        return view('admin.questions.typologies.show', ['typology' => TypologyDescription::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('admin.questions.typologies.edit', ['typology' => TypologyDescription::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_tipologi'       => 'required|string|max:10|unique:deskripsi_tipologi,kode_tipologi,'.$id,
            'nama_tipologi'       => 'required|string|max:255',
            'deskripsi_kekuatan'  => 'nullable|string',
            'deskripsi_kelemahan' => 'nullable|string',
        ]);

        TypologyDescription::findOrFail($id)->update($request->only([
            'kode_tipologi', 'nama_tipologi', 'deskripsi_kekuatan', 'deskripsi_kelemahan',
        ]));

        return redirect()->route('admin.questions.typologies.index')
            ->with('success', 'Tipologi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        TypologyDescription::findOrFail($id)->delete();
        return redirect()->route('admin.questions.typologies.index')
            ->with('success', 'Tipologi berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = TypologyDescription::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_tipologi', 'like', "%{$search}%")
                  ->orWhere('kode_tipologi', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kekuatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kelemahan', 'like', "%{$search}%");
            });
        }

        $pdf = Pdf::loadView('admin.questions.typologies.pdf.typologyReport', [
            'reportTitle' => 'Laporan Data Tipologi',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $query->orderBy('kode_tipologi')->get(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Tipologi.pdf');
    }
}
