<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;



class ParticipantController extends Controller
{
    /** Ambil daftar event yang boleh diakses PIC saat ini (event.pic_id + pivot event_user bila ada). */
    private function myEventIds(int $userId): array
    {
        $ids = Event::where('pic_id', $userId)->pluck('id')->all();

        if (DB::getSchemaBuilder()->hasTable('event_user')) {
            $more = DB::table('event_user')
                ->where('user_id', $userId)
                ->when(Schema::hasColumn('event_user', 'role'), fn($q) => $q->where('role', 'pic'))
                ->pluck('event_id')->all();
            $ids = array_merge($ids, $more);
        }
        return array_values(array_unique($ids));
    }

    /** Base query untuk list participants (ambil score & pdf). */
    private function baseQuery(array $filters, array $allowedEventIds)
    {
        // Kalau kamu punya kolom numeric siap pakai di test_results (mis. sum_top3), lebih cepat:
        // $sumExpr = "COALESCE(tr.sum_top3,0)";
        // Fallback hitung dari JSON sjt_results:
        $sumExpr = "
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[0].score')) AS DECIMAL(10,2)),0) +
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[1].score')) AS DECIMAL(10,2)),0) +
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[2].score')) AS DECIMAL(10,2)),0)
        ";

        $q = DB::table('test_sessions as ts')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->leftJoin('events as e', 'e.id', '=', 'ts.event_id')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->when($allowedEventIds, fn($qq) => $qq->whereIn('ts.event_id', $allowedEventIds))
            ->selectRaw("
                ts.id as session_id,
                u.name, u.email,
                ts.participant_background as instansi,
                e.name as event_name, e.event_code,
                tr.id as test_result_id,
                tr.pdf_path,
                {$sumExpr} as sum_top3
            ");

        if (!empty($filters['event_id'])) {
            $q->where('ts.event_id', $filters['event_id']);
        }
        if (($filters['instansi'] ?? '') !== '') {
            $q->where('ts.participant_background', 'like', '%' . $filters['instansi'] . '%');
        }
        if (($filters['q'] ?? '') !== '') {
            $term = $filters['q'];
            $q->where(function ($w) use ($term) {
                $w->where('u.name', 'like', "%{$term}%")
                    ->orWhere('u.email', 'like', "%{$term}%");
            });
        }

        return [$q, $sumExpr];
    }

    /** LIST Participants (tanpa filter tanggal) */
    public function index(Request $req)
    {
        $validated = $req->validate([
            'mode'     => 'nullable|in:all,top,bottom',
            'n'        => 'nullable|integer|min:1|max:5000',
            'event_id' => 'nullable|string',
            'instansi' => 'nullable|string|max:255',
            'q'        => 'nullable|string|max:255',
        ]);

        $mode = $validated['mode'] ?? 'all';
        $n    = (int) ($validated['n'] ?? 10);

        $filters = [
            'event_id' => $validated['event_id'] ?? null,
            'instansi' => $validated['instansi'] ?? null,
            'q'        => $validated['q'] ?? null,
        ];

        $allowed = $this->myEventIds(Auth::id());

        // Dropdown event hanya event milik PIC
        $events = Event::query()
            ->whereIn('id', $allowed ?: [-1])
            ->orderByDesc('start_date')
            ->get(['id', 'name', 'event_code']);

        [$q, $sumExpr] = $this->baseQuery($filters, $allowed);

        if ($mode === 'all') {
            // semua peserta, urut skor desc → nama → id
            $q->orderByRaw("{$sumExpr} DESC")->orderBy('u.name')->orderBy('ts.id');
            $pagination = $q->paginate(25)->withQueryString();
            $rows = collect($pagination->items());
        } else {
            // top/bottom hanya yang sudah punya hasil
            $q->whereNotNull('tr.sjt_results');
            if ($mode === 'top') {
                $q->orderByRaw("{$sumExpr} DESC")->orderBy('u.name')->orderBy('ts.id');
            } else {
                $q->orderByRaw("{$sumExpr} ASC")->orderBy('u.name')->orderBy('ts.id');
            }
            $rows = $q->limit($n)->get();
            $pagination = null;
        }

        return view('pic.participants.index', [
            'events'     => $events,
            'mode'       => $mode,
            'n'          => $n,
            'rows'       => $rows,
            'pagination' => $pagination,
            'filters'    => $filters,
        ]);
    }

    /** STREAM/REDIRECT PDF hasil peserta.
     *  Param {session} bisa id angka; kalau kamu nanti pakai kode, controller ini akan coba kolom 'code' HANYA jika ada di schema.
     */
    public function resultPdf(string $session)
    {
        $allowed = $this->myEventIds(Auth::id());

        $row = DB::table('test_sessions as ts')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->when($allowed, fn($q) => $q->whereIn('ts.event_id', $allowed))
            ->where(function ($q) use ($session) {
                // Kalau angka → cocokkan id
                if (ctype_digit($session)) {
                    $q->orWhere('ts.id', (int)$session);
                }
                // Kalau proyek punya kolom 'code', baru dicoba
                if (Schema::hasColumn('test_sessions', 'code')) {
                    $q->orWhere('ts.code', $session);
                }
            })
            ->select(['ts.id as session_id', 'ts.event_id', 'tr.pdf_path'])
            ->first();

        if (!$row || empty($row->pdf_path)) {
            abort(404, 'Result PDF tidak ditemukan atau akses ditolak.');
        }

        $path = $row->pdf_path;

        // 1) URL eksternal → redirect
        if (preg_match('~^https?://~i', $path)) {
            return redirect()->away($path);
        }

        // 2) Storage (coba beberapa disk umum)
        foreach (['local', 'public', config('filesystems.default')] as $disk) {
            if (!$disk) continue;
            try {
                if (Storage::disk($disk)->exists($path)) {
                    return Response::make(Storage::disk($disk)->get($path), 200, [
                        'Content-Type'        => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="result-' . $row->session_id . '.pdf"',
                    ]);
                }
            } catch (\Throwable $e) { /* lanjut */
            }
        }

        // 3) Path absolut / relatif langsung
        if (is_file($path)) {
            return Response::make(file_get_contents($path), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="result-' . $row->session_id . '.pdf"',
            ]);
        }

        abort(404, 'File PDF tidak ditemukan di storage maupun sebagai URL.');
    }

    public function exportPdf(Request $req)
    {
        $mode     = $req->query('mode', 'all');          // all | top | bottom
        $n        = (int) $req->query('n', 10);
        $eventId  = $req->query('event_id');
        $instansi = trim((string) $req->query('instansi', ''));
        $q        = trim((string) $req->query('q', ''));

        // Batasi event milik PIC (jika ada tabel pivot event_pic)
        $allowedEventIds = null;
        try {
            if (DB::getSchemaBuilder()->hasTable('event_pic')) {
                $allowedEventIds = DB::table('event_pic')
                    ->where('user_id', Auth::id())
                    ->pluck('event_id')
                    ->all();
            }
        } catch (\Throwable $e) {
            $allowedEventIds = null;
        }

        // Rumus sum_top3
        $sumExpr = "
        COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[0].score')) AS DECIMAL(10,2)),0) +
        COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[1].score')) AS DECIMAL(10,2)),0) +
        COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[2].score')) AS DECIMAL(10,2)),0)
    ";

        $qBuilder = DB::table('test_sessions as ts')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->leftJoin('events as e', 'e.id', '=', 'ts.event_id')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->selectRaw("
            ts.id as session_id,
            u.name, u.email,
            ts.participant_background as instansi,
            e.name as event_name, e.event_code,
            tr.id as test_result_id,
            tr.pdf_path,
            {$sumExpr} as sum_top3
        ");

        if (is_array($allowedEventIds) && count($allowedEventIds) > 0) {
            $qBuilder->whereIn('ts.event_id', $allowedEventIds);
        }
        if (!empty($eventId)) {
            $qBuilder->where('ts.event_id', $eventId);
        }
        if ($instansi !== '') {
            $qBuilder->where('ts.participant_background', 'like', '%' . $instansi . '%');
        }
        if ($q !== '') {
            $term = $q;
            $qBuilder->where(function ($w) use ($term) {
                $w->where('u.name', 'like', "%{$term}%")
                    ->orWhere('u.email', 'like', "%{$term}%");
            });
        }

        if ($mode === 'all') {
            // Semua peserta, urut skor tertinggi → terendah
            $qBuilder->orderByDesc('sum_top3')->orderBy('u.name')->orderBy('ts.id');
            $rows = $qBuilder->get();
        } else {
            // Top/Bottom — hanya yang punya SJT results
            $qBuilder->whereNotNull('tr.sjt_results');
            if ($mode === 'top') {
                $qBuilder->orderByDesc('sum_top3')->orderBy('u.name')->orderBy('ts.id');
            } else {
                $qBuilder->orderBy('sum_top3')->orderBy('u.name')->orderBy('ts.id');
            }
            $rows = $qBuilder->limit($n)->get();
        }

        $reportTitle = 'Participants Report';
        $modeText = $mode === 'top'    ? "Top ({$n}) participants"
            : ($mode === 'bottom' ? "Bottom ({$n}) participants"
                : "All participants — ordered by highest score");

        $data = [
            'rows'        => $rows,
            'reportTitle' => $reportTitle,
            'mode'        => $mode,
            'n'           => $n,
            'modeText'    => $modeText,
            'generatedBy' => Auth::check() ? (Auth::user()->name ?? 'PIC') : 'PIC',
            'generatedAt' => now()->format('d M Y H:i') . ' WIB',
        ];

        // VIEW PATH: resources/views/pic/participants/pdf/report-participant.blade.php
        return Pdf::loadView('pic.participants.pdf.report-participant', $data)
            ->setPaper('a4', 'landscape')
            ->download('participants-report.pdf');
    }
}
