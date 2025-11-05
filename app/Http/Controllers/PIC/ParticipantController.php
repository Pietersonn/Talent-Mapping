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

    private function baseQuery(array $filters, array $allowedEventIds)
    {
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
            tr.sjt_results, /* <-- BARIS BARU DITAMBAHKAN */
            {$sumExpr} as sum_top3
        ");

        // ... sisa baseQuery sama
        return [$q, $sumExpr];
    }

    /** LIST Participants */
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

        // Dropdown event
        $events = Event::query()
            ->whereIn('id', $allowed ?: [-1])
            ->orderByDesc('start_date')
            ->get(['id', 'name', 'event_code']);

        [$q, $sumExpr] = $this->baseQuery($filters, $allowed);

        if ($mode === 'all') {
            $q->orderByRaw("{$sumExpr} DESC")->orderBy('u.name')->orderBy('ts.id');
            $pagination = $q->paginate(25)->withQueryString();
            $rows = collect($pagination->items());
        } else {
            $q->whereNotNull('tr.sjt_results');
            if ($mode === 'top') {
                $q->orderByRaw("{$sumExpr} DESC")->orderBy('u.name')->orderBy('ts.id');
            } else {
                $q->orderByRaw("{$sumExpr} ASC")->orderBy('u.name')->orderBy('ts.id');
            }
            $rows = $q->limit($n)->get();
            $pagination = null;
        }

        // === Hitung total_score & berhasil global ===
       if ($pagination) {
        $rows = collect($pagination->items());
    }

    $rows = $rows->map(function ($r) {
        $r->total_score = null;
        if (isset($r->sjt_results) && !empty($r->sjt_results)) {
            $data = json_decode($r->sjt_results, true);
            if (isset($data['all'])) {
                // Hitung total skor dari semua kompetensi
                $r->total_score = round(collect($data['all'])->sum('score'), 0);
            }
        }
        // Hapus data mentah JSON agar lebih ringan di view
        unset($r->sjt_results);
        return $r;
    });

    // === Perbarui return view (Hapus $total_score global) ===
    return view('pic.participants.index', [
        'events'   => $events,
        'mode'    => $mode,
        'n'     => $n,
        'rows'    => $rows,
        'pagination' => $pagination,
        'filters'  => $filters,
    ]);
    }

    /** STREAM PDF hasil peserta */
    public function resultPdf(string $session)
    {
        $allowed = $this->myEventIds(Auth::id());

        $row = DB::table('test_sessions as ts')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->when($allowed, fn($q) => $q->whereIn('ts.event_id', $allowed))
            ->where(function ($q) use ($session) {
                if (ctype_digit($session)) {
                    $q->orWhere('ts.id', (int)$session);
                }
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

        if (preg_match('~^https?://~i', $path)) {
            return redirect()->away($path);
        }

        foreach (['local', 'public', config('filesystems.default')] as $disk) {
            if (!$disk) continue;
            try {
                if (Storage::disk($disk)->exists($path)) {
                    return Response::make(Storage::disk($disk)->get($path), 200, [
                        'Content-Type'        => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="result-' . $row->session_id . '.pdf"',
                    ]);
                }
            } catch (\Throwable $e) {
            }
        }

        if (is_file($path)) {
            return Response::make(file_get_contents($path), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="result-' . $row->session_id . '.pdf"',
            ]);
        }

        abort(404, 'File PDF tidak ditemukan.');
    }

    /** EXPORT PDF semua peserta */
    public function exportPdf(Request $req)
    {
        $mode     = $req->query('mode', 'all');
        $n        = (int) $req->query('n', 10);
        $eventId  = $req->query('event_id');
        $instansi = trim((string) $req->query('instansi', ''));
        $search   = trim((string) $req->query('q', ''));


        $allowedEventIds = $this->myEventIds(Auth::id());

        $query = DB::table('test_sessions as ts')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->leftJoin('events as e', 'e.id', '=', 'ts.event_id')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->select([
                'ts.id as session_id',
                'u.name',
                'u.email',
                'u.phone_number',
                'ts.participant_background as instansi',
                'e.name as event_name',
                'tr.sjt_results',
            ])
            ->when($allowedEventIds, fn($q) => $q->whereIn('ts.event_id', $allowedEventIds))
            ->when($eventId, fn($q) => $q->where('ts.event_id', $eventId))
            ->when($instansi !== '', fn($q) => $q->where('ts.participant_background', 'like', "%{$instansi}%"))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('u.name', 'like', "%{$search}%")
                        ->orWhere('u.email', 'like', "%{$search}%");
                });
            });

        $rows = $query->get()->map(function ($r) {
            $data = json_decode($r->sjt_results, true);
            $competencies = collect([]);

            /// Ambil nilai dari JSON 'all' saja
            if (!empty($data['all'])) {
                foreach ($data['all'] as $c) {
                    if (isset($c['code'], $c['score'])) {
                        $competencies->put($c['code'], $c['score']);
                    }
                }
            }

            // Pastikan 10 kode utama tetap muncul
            $codes = ['SM', 'CIA', 'TS', 'WWO', 'CA', 'L', 'SE', 'PS', 'PE', 'GH'];
            foreach ($codes as $code) {
                $r->{$code} = round($competencies->get($code, 0), 1);
            }

            $r->total_score = $competencies->sum();
            return $r;
        });

        // Urutan data sesuai mode
        if ($mode === 'top') {
            $rows = $rows->sortByDesc('total_score')->take($n);
        } elseif ($mode === 'bottom') {
            $rows = $rows->sortBy('total_score')->take($n);
        } else {
            $rows = $rows->sortByDesc('total_score');
        }

        $reportTitle = 'Participants Competency Report';
        $modeText = match ($mode) {
            'top' => "Top {$n} Participants by Total Score",
            'bottom' => "Bottom {$n} Participants by Total Score",
            default => "All Participants — Ordered by Highest Score",
        };

        $data = [
            'rows'        => $rows,
            'reportTitle' => $reportTitle,
            'modeText'    => $modeText,
            'generatedBy' => Auth::user()->name ?? 'PIC',
            'generatedAt' => now('Asia/Makassar')->format('d M Y H:i') . ' WITA',

        ];

        $pdf = Pdf::loadView('pic.participants.pdf.report-participant', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('participants-report.pdf');
    }
}
