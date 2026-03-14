<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    /**
     * Halaman Utama (Tabel List).
     */
    public function index(Request $request)
    {
        $query = TestResult::with(['session.user', 'session.event'])
            ->orderBy('report_generated_at', 'desc');

        // 1. Filter Search (Global Search: Nama, Telp, Event, Instansi, Jabatan)
        if ($request->filled('search')) {
            $search = (string) $request->search;
            $query->whereHas('session', function ($q) use ($search) {
                $q->where('participant_name', 'LIKE', "%{$search}%")
                    ->orWhere('participant_background', 'LIKE', "%{$search}%") // Instansi
                    ->orWhere('position', 'LIKE', "%{$search}%") // Jabatan
                    // Cari di tabel users (Email, No Telp, Nama User Asli)
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone_number', 'LIKE', "%{$search}%");
                    })
                    // Cari di tabel events (Nama Event)
                    ->orWhereHas('event', function ($e) use ($search) {
                        $e->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // 2. Filter Event Dropdown (Spesifik)
        if ($request->filled('event_id')) {
            $query->whereHas('session', fn($q) => $q->where('event_id', $request->event_id));
        }

        // AJAX Response untuk Realtime Search
        if ($request->ajax()) {
            $results = $query->paginate(20)->withQueryString();
            return response()->json([
                'results' => $results,
                'is_admin' => Auth::user()->role === 'admin',
                'download_url' => url('admin/results')
            ]);
        }

        $results = $query->paginate(20)->withQueryString();
        $events = Event::where('is_active', true)->get(['id', 'name']);

        return view('admin.results.index', compact('results', 'events'));
    }

    /**
     * Tombol PRINT (Header): Export Data Tabel ke PDF Laporan.
     * View: admin.results.pdf.resultReport
     */
    public function exportPdf(Request $request)
    {
        $query = TestResult::with(['session.user', 'session.event'])
            ->orderBy('report_generated_at', 'desc');

        // 1. Filter Search (Logika SAMA PERSIS dengan index)
        if ($request->filled('search')) {
            $search = (string) $request->search;
            $query->whereHas('session', function ($q) use ($search) {
                $q->where('participant_name', 'LIKE', "%{$search}%")
                    ->orWhere('participant_background', 'LIKE', "%{$search}%") // Instansi
                    ->orWhere('position', 'LIKE', "%{$search}%") // Jabatan
                    // Cari di tabel users
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone_number', 'LIKE', "%{$search}%");
                    })
                    // Cari di tabel events
                    ->orWhereHas('event', function ($e) use ($search) {
                        $e->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // 2. Filter Event
        if ($request->filled('event_id')) {
            $query->whereHas('session', fn($q) => $q->where('event_id', $request->event_id));
        }

        // Ambil semua data (tanpa pagination)
        $results = $query->get();

        // Load View PDF
        $pdf = Pdf::loadView('admin.results.pdf.resultReport', [
            'reportTitle' => 'Laporan Peserta',
            'generatedBy' => Auth::user()->name,
            'generatedAt' => now()->format('d M Y H:i'),
            'rows'        => $results,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Peserta.pdf');
    }

    /**
     * Tombol RESULT (Aksi): Download/Lihat PDF Hasil Individu.
     */
    public function downloadPdf(TestResult $testResult)
    {
        if (empty($testResult->pdf_path)) {
            return back()->with('error', 'File PDF belum digenerate atau path kosong.');
        }

        if (Storage::disk('public')->exists($testResult->pdf_path)) {
            $fileContent = Storage::disk('public')->get($testResult->pdf_path);

            return Response::make($fileContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="result-' . $testResult->id . '.pdf"',
            ]);
        }

        return back()->with('error', 'File PDF tidak ditemukan di server.');
    }
}
