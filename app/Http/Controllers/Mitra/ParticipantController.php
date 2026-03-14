<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    /** Helper: Ambil ID Acara milik Mitra */
    private function myEventIds(int $userId): array
    {
        return Event::where('id_pic', $userId)->pluck('id')->toArray();
    }

    /** LIST Participants */
    public function index(Request $request)
    {
        $mitraEventIds = $this->myEventIds(Auth::id());

        $search  = $request->input('search') ?? $request->input('q');
        $eventId = $request->input('event_id');
        $n       = $request->input('n', 10);

        $query = DB::table('sesi_tes as ts')
            ->join('pengguna as u', 'u.id', '=', 'ts.id_pengguna')
            ->leftJoin('acara as e', 'e.id', '=', 'ts.id_acara')
            ->leftJoin('hasil_tes as tr', 'tr.id_sesi', '=', 'ts.id')
            ->select(
                'ts.id as session_id',
                'u.nama as name',
                'u.email',
                'ts.latar_belakang as instansi',
                'e.nama as event_name',
                'e.kode_acara as event_code',
                'tr.path_pdf',
                'tr.hasil_tk'
            )
            ->whereIn('ts.id_acara', $mitraEventIds);

        if ($eventId) $query->where('ts.id_acara', $eventId);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.nama', 'like', "%{$search}%")
                    ->orWhere('u.email', 'like', "%{$search}%")
                    ->orWhere('ts.latar_belakang', 'like', "%{$search}%");
            });
        }

        $query->orderBy('ts.created_at', 'desc');
        $rows = $query->paginate($n)->withQueryString();

        $rows->getCollection()->transform(function ($row) {
            $row->total_score = null;
            if (!empty($row->hasil_tk)) {
                $data = json_decode($row->hasil_tk, true);
                if (isset($data['all'])) {
                    $row->total_score = round(collect($data['all'])->sum('score'));
                }
            }

            $row->download_url = (!empty($row->path_pdf))
                ? route('mitra.participants.result-pdf', $row->session_id)
                : null;

            $row->event_name_short = Str::limit($row->event_name, 25);
            return $row;
        });

        if ($request->ajax()) {
            return response()->json([
                'data'  => $rows->items(),
                'links' => (string) $rows->links(),
                'from'  => $rows->firstItem() ?? 0,
            ]);
        }

        $events = Event::whereIn('id', $mitraEventIds)
            ->orderByDesc('tanggal_mulai')
            ->get(['id', 'nama', 'kode_acara']);

        return view('mitra.participants.index', [
            'rows'    => $rows,
            'events'  => $events,
            'filters' => ['search' => $search, 'event_id' => $eventId],
        ]);
    }

    /** EXPORT PDF Laporan Peserta */
    public function exportPdf(Request $request)
    {
        $mitraEventIds = $this->myEventIds(Auth::id());
        $search  = $request->input('search') ?? $request->input('q');
        $eventId = $request->input('event_id');

        $query = DB::table('sesi_tes as ts')
            ->join('pengguna as u', 'u.id', '=', 'ts.id_pengguna')
            ->leftJoin('acara as e', 'e.id', '=', 'ts.id_acara')
            ->select(
                'ts.id as session_id',
                'u.nama as name',
                'u.email',
                'u.nomor_telepon as phone_number',
                'ts.latar_belakang as instansi',
                'ts.jabatan',
                'e.nama as event_name'
            )
            ->whereIn('ts.id_acara', $mitraEventIds);

        if ($eventId) $query->where('ts.id_acara', $eventId);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.nama', 'like', "%{$search}%")
                    ->orWhere('u.email', 'like', "%{$search}%");
            });
        }

        $results = $query->orderBy('ts.created_at', 'desc')->get();

        $pdf = Pdf::loadView('mitra.participants.pdf.participantReport', [
            'reportTitle' => 'Laporan Peserta',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $results,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Peserta.pdf');
    }

    /** DOWNLOAD RESULT PDF */
    public function resultPdf(string $session)
    {
        $allowed = $this->myEventIds(Auth::id());

        $sessionData = DB::table('sesi_tes as ts')
            ->join('hasil_tes as tr', 'tr.id_sesi', '=', 'ts.id')
            ->where('ts.id', $session)
            ->whereIn('ts.id_acara', $allowed)
            ->select('tr.path_pdf')
            ->first();

        if (!$sessionData || empty($sessionData->path_pdf)) {
            abort(404, 'PDF tidak ditemukan atau akses ditolak.');
        }

        $path = storage_path('app/' . $sessionData->path_pdf);
        if (!file_exists($path)) {
            abort(404, 'File PDF tidak tersedia.');
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="hasil-tes.pdf"',
        ]);
    }
}
