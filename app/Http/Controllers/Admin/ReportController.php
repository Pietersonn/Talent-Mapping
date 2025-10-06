<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Event;
use App\Models\TestSession;
use App\Models\TestResult;
use App\Models\ResendRequest;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /* ========================== Helpers ========================== */
    private function filters(Request $r): array
    {
        return [
            'event_id'  => $r->query('event_id'),
            'instansi'  => trim((string) $r->query('instansi', '')),
            'date_from' => $r->query('date_from'),
            'date_to'   => $r->query('date_to'),
            'search'    => trim((string) $r->query('search', '')),
        ];
    }

    private function applyDateRange($q, ?string $from, ?string $to, string $col = 'created_at')
    {
        if ($from) $q->whereDate($col, '>=', $from);
        if ($to)   $q->whereDate($col, '<=', $to);
        return $q;
    }

    private function commonData()
    {
        $events = Event::query()->orderBy('start_date', 'desc')->get(['id', 'name', 'start_date', 'end_date']);
        return compact('events');
    }

    /**
     * Base query untuk laporan peserta (WEB/PDF).
     * - Hanya select kolom yang dipakai.
     * - Siapkan expression sum_top3 sekali.
     */
    private function baseParticipantsQuery(array $filters, bool $onlyWithResults = true)
    {
        // Hitung sum_top3 dari JSON (string -> cast numerik)
        $sumExpr = "
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[0].score')) AS DECIMAL(10,2)),0) +
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[1].score')) AS DECIMAL(10,2)),0) +
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[2].score')) AS DECIMAL(10,2)),0)
        ";

        $q = DB::table('test_sessions as ts')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->leftJoin('events as e', 'e.id', '=', 'ts.event_id')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->selectRaw("
                ts.id as session_id,
                u.name, u.email,
                ts.participant_background as instansi,
                e.name as event_name, e.event_code,
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
        if ($onlyWithResults) {
            // All/Top/Bottom perlu skor → wajib ada sjt_results
            $q->whereNotNull('tr.sjt_results');
        }

        return [$q, $sumExpr];
    }

    /* ========================== 1) Health Dashboard ========================== */
    public function index(Request $request)
    {
        $f = $this->filters($request);

        $base = TestSession::query();
        if ($f['event_id']) $base->where('event_id', $f['event_id']);
        if ($f['instansi'] !== '') $base->where('participant_background', 'LIKE', '%' . $f['instansi'] . '%');
        $this->applyDateRange($base, $f['date_from'], $f['date_to'], 'created_at');

        $totalRegistered = (clone $base)->count();

        $st30Started = (clone $base)->whereExists(function ($q) {
            $q->select(DB::raw(1))->from('st30_responses')
                ->whereColumn('st30_responses.session_id', 'test_sessions.id');
        })->count();

        $sjtStarted  = (clone $base)->whereExists(function ($q) {
            $q->select(DB::raw(1))->from('sjt_responses')
                ->whereColumn('sjt_responses.session_id', 'test_sessions.id');
        })->count();

        $completed = (clone $base)->whereExists(function ($q) {
            $q->select(DB::raw(1))->from('test_results')
                ->whereColumn('test_results.session_id', 'test_sessions.id');
        })->count();

        // SLA proxy
        $resQ = TestResult::query()->with('session');
        if ($f['event_id']) $resQ->whereHas('session', fn($q) => $q->where('event_id', $f['event_id']));
        if ($f['instansi'] !== '') $resQ->whereHas('session', fn($q) => $q->where('participant_background', 'LIKE', '%' . $f['instansi'] . '%'));
        $this->applyDateRange($resQ, $f['date_from'], $f['date_to'], 'created_at');

        $results = $resQ->get(['id', 'session_id', 'created_at', 'report_generated_at', 'email_sent_at']);

        $slaGen = [];
        $slaSend = [];
        foreach ($results as $r) {
            $completedAt = $r->created_at ?? optional($r->session)->updated_at ?? optional($r->session)->created_at;
            if ($completedAt && $r->report_generated_at) {
                $slaGen[] = Carbon::parse($completedAt)->diffInMinutes(Carbon::parse($r->report_generated_at));
            }
            if ($completedAt && $r->email_sent_at) {
                $slaSend[] = Carbon::parse($completedAt)->diffInMinutes(Carbon::parse($r->email_sent_at));
            }
        }
        $median = function (array $arr) {
            if (!$arr) return null;
            sort($arr);
            $n = count($arr);
            $m = intdiv($n, 2);
            return $n % 2 ? $arr[$m] : (($arr[$m - 1] + $arr[$m]) / 2);
        };

        $metrics = [
            'total_registered'    => $totalRegistered,
            'st30_started'        => $st30Started,
            'sjt_started'         => $sjtStarted,
            'completed'           => $completed,
            'conversion_rate'     => $totalRegistered ? round($completed / max(1, $totalRegistered) * 100, 1) : 0,
            'median_sla_generate' => $median($slaGen),
            'median_sla_send'     => $median($slaSend),
        ];

        return view('admin.reports.index', array_merge($this->commonData(), compact('metrics', 'f')));
    }

    /* ========================== 2) Peserta (WEB) ========================== */
    public function participants(Request $req)
    {
        $validated = $req->validate([
            'mode'     => 'nullable|in:all,top,bottom',
            'n'        => 'nullable|integer|min:1|max:5000',
            'event_id' => 'nullable|string',
            'instansi' => 'nullable|string|max:255',
            'q'        => 'nullable|string|max:255',
        ]);

        $mode = $validated['mode'] ?? 'all';
        $n    = (int)($validated['n'] ?? 10);

        $filters = [
            'event_id' => $validated['event_id'] ?? null,
            'instansi' => $validated['instansi'] ?? null,
            'q'        => $validated['q'] ?? null,
        ];

        $events = Event::query()->orderBy('start_date', 'desc')->get(['id', 'name', 'event_code']);

        // sesuai definisi: ALL = semua peserta yang punya skor, urut dari tertinggi
        [$q, $sumExpr] = $this->baseParticipantsQuery($filters, true);

        $rows = null;
        $pagination = null;

        if ($mode === 'all') {
            $q->orderByRaw($sumExpr . ' DESC')->orderBy('u.name')->orderBy('ts.id');
            $pagination = $q->paginate(25)->withQueryString();
            $rows = collect($pagination->items());
        } else {
            if ($mode === 'top') {
                $q->orderByRaw($sumExpr . ' DESC')->orderBy('u.name')->orderBy('ts.id');
            } else {
                $q->orderByRaw($sumExpr . ' ASC')->orderBy('u.name')->orderBy('ts.id');
            }
            $rows = $q->limit($n)->get();
        }

        return view('admin.reports.participants', [
            'events'     => $events,
            'mode'       => $mode,
            'n'          => $n,
            'rows'       => $rows,
            'pagination' => $pagination,
            'filters'    => $filters,
        ]);
    }

    /* ========================== 3) Rekap Event ========================== */
    public function events(Request $request)
    {
        $f = $this->filters($request);
        $events = Event::query()->orderBy('start_date', 'desc')->get();

        $summary = $events->map(function ($ev) {
            $base = TestSession::query()->where('event_id', $ev->id);
            $registered = (clone $base)->count();

            $st30Started = (clone $base)->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('st30_responses')
                    ->whereColumn('st30_responses.session_id', 'test_sessions.id');
            })->count();
            $sjtStarted  = (clone $base)->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('sjt_responses')
                    ->whereColumn('sjt_responses.session_id', 'test_sessions.id');
            })->count();
            $completed   = (clone $base)->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('test_results')
                    ->whereColumn('test_results.session_id', 'test_sessions.id');
            })->count();
            $resultsSent = TestResult::query()
                ->whereHas('session', fn($q) => $q->where('event_id', $ev->id))
                ->whereNotNull('email_sent_at')->count();

            return [
                'event_id'       => $ev->id,
                'event_name'     => $ev->name,
                'period'         => $ev->start_date ? ($ev->start_date . ' → ' . $ev->end_date) : '-',
                'registered'     => $registered,
                'st30_started'   => $st30Started,
                'sjt_started'    => $sjtStarted,
                'completed'      => $completed,
                'completion_rate' => $registered ? round($completed / max(1, $registered) * 100, 1) : 0,
                'results_sent'   => $resultsSent,
            ];
        });

        if ($f['search'] !== '') {
            $needle = mb_strtolower($f['search']);
            $summary = $summary->filter(fn($r) => str_contains(mb_strtolower($r['event_name']), $needle))->values();
        }

        return view('admin.reports.events', array_merge($this->commonData(), compact('summary', 'f')));
    }

    /* ========================== 4) Delivery ========================== */
    public function delivery(Request $request)
    {
        $f = $this->filters($request);

        $q = TestResult::query()->with(['session' => function ($s) {
            $s->select('id', 'event_id', 'participant_name', 'created_at', 'updated_at');
        }]);

        if ($f['event_id']) $q->whereHas('session', fn($s) => $s->where('event_id', $f['event_id']));
        if ($f['instansi'] !== '') $q->whereHas('session', fn($s) => $s->where('participant_background', 'LIKE', '%' . $f['instansi'] . '%'));
        $this->applyDateRange($q, $f['date_from'], $f['date_to'], 'created_at');

        $rows = $q->orderByDesc('created_at')->get([
            'id',
            'session_id',
            'pdf_path',
            'report_generated_at',
            'email_sent_at',
            'created_at'
        ]);

        $mapped = $rows->map(function ($r) {
            $completedAt = $r->created_at ?? optional($r->session)->updated_at ?? optional($r->session)->created_at;
            $slaGen = ($completedAt && $r->report_generated_at)
                ? Carbon::parse($completedAt)->diffInMinutes(Carbon::parse($r->report_generated_at)) : null;
            $slaSend = ($completedAt && $r->email_sent_at)
                ? Carbon::parse($completedAt)->diffInMinutes(Carbon::parse($r->email_sent_at)) : null;

            return [
                'result_id'    => $r->id,
                'name'         => data_get($r, 'session.participant_name'),
                'email'        => null,
                'event_id'     => data_get($r, 'session.event_id'),
                'pdf_path'     => $r->pdf_path,
                'generated_at' => optional($r->report_generated_at)?->toDateTimeString(),
                'sent_at'      => optional($r->email_sent_at)?->toDateTimeString(),
                'sla_generate' => $slaGen,
                'sla_send'     => $slaSend,
            ];
        });

        $eventMap = Event::query()->pluck('name', 'id')->toArray();
        return view('admin.reports.delivery', array_merge($this->commonData(), compact('mapped', 'eventMap', 'f')));
    }

    /* ========================== 5) Resend Requests ========================== */
    public function resend(Request $request)
    {
        $f = $this->filters($request);

        $q = ResendRequest::query()
            ->with(['result.session' => function ($s) {
                $s->select('id', 'event_id', 'participant_name', 'participant_background');
            }])
            ->orderByDesc('request_date');

        if ($f['event_id']) $q->whereHas('result.session', fn($s) => $s->where('event_id', $f['event_id']));
        if ($f['instansi'] !== '') $q->whereHas('result.session', fn($s) => $s->where('participant_background', 'LIKE', '%' . $f['instansi'] . '%'));
        $this->applyDateRange($q, $f['date_from'], $f['date_to'], 'request_date');

        $rows = $q->get();

        $eventMap = Event::query()->pluck('name', 'id')->toArray();
        return view('admin.reports.resend', array_merge($this->commonData(), compact('rows', 'eventMap', 'f')));
    }

    /* ========================== 6) Data Quality ========================== */
    public function dataQuality(Request $request)
    {
        $f = $this->filters($request);

        $q = TestSession::query();
        if ($f['event_id']) $q->where('event_id', $f['event_id']);
        $this->applyDateRange($q, $f['date_from'], $f['date_to'], 'created_at');

        $sessions = $q->get();

        $missingInstansi = $sessions->filter(fn($s) => !trim((string)$s->participant_background))->values();
        $noResult = $sessions->filter(fn($s) => !TestResult::where('session_id', $s->id)->exists())->values();

        $invalidPdf = TestResult::query()->where(function ($qq) {
            $qq->whereNull('pdf_path')->orWhere('pdf_path', '');
        });
        if ($f['event_id']) $invalidPdf->whereHas('session', fn($s) => $s->where('event_id', $f['event_id']));
        $invalidPdf = $invalidPdf->get();

        return view('admin.reports.data_quality', array_merge($this->commonData(), [
            'missingInstansi' => $missingInstansi,
            'noResult'        => $noResult,
            'invalidPdf'      => $invalidPdf,
            'f'               => $f,
        ]));
    }

    /* ========================== 7) Anomaly ========================== */
    public function anomaly(Request $request)
    {
        $f = $this->filters($request);
        $thresholdMin = (int) $request->query('min_minutes', 8);

        $q = TestSession::query()
            ->select('test_sessions.*')
            ->join('test_results', 'test_results.session_id', '=', 'test_sessions.id');

        if ($f['event_id']) $q->where('test_sessions.event_id', $f['event_id']);
        $this->applyDateRange($q, $f['date_from'], $f['date_to'], 'test_results.created_at');

        $rows = $q->get([
            'test_sessions.id',
            'test_sessions.event_id',
            'test_sessions.participant_name',
            'test_sessions.participant_background',
            'test_sessions.created_at',
            'test_results.created_at as completed_proxy'
        ]);

        $flagged = $rows->map(function ($s) use ($thresholdMin) {
            $start = $s->created_at;
            $done  = $s->completed_proxy;
            $dur   = ($start && $done) ? Carbon::parse($start)->diffInMinutes(Carbon::parse($done)) : null;
            return [
                'session_id' => $s->id,
                'name'       => $s->participant_name,
                'email'      => null,
                'instansi'   => $s->participant_background,
                'event_id'   => $s->event_id,
                'duration_m' => $dur,
                'flag'       => $dur !== null && $dur < $thresholdMin ? 'Too Fast' : null,
            ];
        })->filter(fn($r) => $r['flag'] !== null)->values();

        $eventMap = Event::query()->pluck('name', 'id')->toArray();
        return view('admin.reports.anomaly', array_merge($this->commonData(), compact('flagged', 'eventMap', 'f', 'thresholdMin')));
    }

    /* ========================== PDF EXPORTS ========================== */

    public function exportHealthPdf(Request $request)
    {
        $request->merge($this->filters($request));
        $view = $this->index($request);
        $data = $view->getData();
        $pdf = Pdf::loadView('admin.reports.pdf.health', $data)->setPaper('a4', 'portrait');
        return $pdf->download('health-dashboard.pdf');
    }

    public function exportParticipantsPdf(Request $request)
    {
        // Ambil filter & mode yang sama dengan halaman web
        $validated = $request->validate([
            'mode'     => 'nullable|in:all,top,bottom',
            'n'        => 'nullable|integer|min:1|max:5000',
            'event_id' => 'nullable|string',
            'instansi' => 'nullable|string|max:255',
            'q'        => 'nullable|string|max:255',
        ]);

        $mode = $validated['mode'] ?? 'all';
        $n    = (int)($validated['n'] ?? 10);

        $filters = [
            'event_id' => $validated['event_id'] ?? null,
            'instansi' => $validated['instansi'] ?? null,
            'q'        => $validated['q'] ?? null,
        ];

        // Query khusus PDF: no paginate, hanya kolom perlu, urut sesuai mode
        [$q, $sumExpr] = $this->baseParticipantsQuery($filters, true);

        if ($mode === 'top') {
            $q->orderByRaw($sumExpr . ' DESC')->orderBy('u.name')->orderBy('ts.id')->limit($n);
        } elseif ($mode === 'bottom') {
            $q->orderByRaw($sumExpr . ' ASC')->orderBy('u.name')->orderBy('ts.id')->limit($n);
        } else { // all = semua yang punya skor, urut tertinggi
            $q->orderByRaw($sumExpr . ' DESC')->orderBy('u.name')->orderBy('ts.id');
        }

        $rows = $q->get();

        // Opsi ringan DomPDF
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $data = [
            'rows'        => $rows,
            'mode'        => $mode,
            'n'           => $mode !== 'all' ? $n : null,
            'reportTitle' => 'Laporan Peserta',
            'generatedBy' => optional(Auth::user())->name ?? 'System',
            'generatedAt' => now()->format('d M Y H:i') . ' WIB',
        ];

        // Gunakan portrait; ganti ke 'landscape' jika tabel sering melebar
        $pdf = Pdf::loadView('admin.reports.pdf.participants', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('participants.pdf');
    }

    public function exportEventsPdf(Request $request)
    {
        $request->merge($this->filters($request));
        $view = $this->events($request);
        $data = $view->getData();
        $pdf = Pdf::loadView('admin.reports.pdf.events', $data)->setPaper('a4', 'landscape');
        return $pdf->download('events-summary.pdf');
    }

    public function exportDeliveryPdf(Request $request)
    {
        $request->merge($this->filters($request));
        $view = $this->delivery($request);
        $data = $view->getData();
        $pdf = Pdf::loadView('admin.reports.pdf.delivery', $data)->setPaper('a4', 'landscape');
        return $pdf->download('delivery-sla.pdf');
    }

    public function exportResendPdf(Request $request)
    {
        $request->merge($this->filters($request));
        $view = $this->resend($request);
        $data = $view->getData();
        $pdf = Pdf::loadView('admin.reports.pdf.resend', $data)->setPaper('a4', 'portrait');
        return $pdf->download('resend-requests.pdf');
    }

    public function exportDataQualityPdf(Request $request)
    {
        $request->merge($this->filters($request));
        $view = $this->dataQuality($request);
        $data = $view->getData();
        $pdf = Pdf::loadView('admin.reports.pdf.data_quality', $data)->setPaper('a4', 'portrait');
        return $pdf->download('data-quality.pdf');
    }

    public function exportAnomalyPdf(Request $request)
    {
        $request->merge($this->filters($request));
        $view = $this->anomaly($request);
        $data = $view->getData();
        $pdf = Pdf::loadView('admin.reports.pdf.anomaly', $data)->setPaper('a4', 'landscape');
        return $pdf->download('anomaly-fast-duration.pdf');
    }

    private function buildInsightData(array $f): array
    {
        $q = TestResult::query()->with('session');
        if (!empty($f['event_id'])) $q->whereHas('session', fn($s) => $s->where('event_id', $f['event_id']));
        if (($f['instansi'] ?? '') !== '') $q->whereHas('session', fn($s) => $s->where('participant_background', 'LIKE', '%' . $f['instansi'] . '%'));
        $this->applyDateRange($q, $f['date_from'] ?? null, $f['date_to'] ?? null, 'created_at');

        $results = $q->get(['id', 'sjt_results', 'st30_results', 'session_id']);

        $typoCount = [];
        $compScores = [];
        $top3Freq = [];

        foreach ($results as $r) {
            $dom = data_get($r, 'st30_results.dominant_typology');
            if ($dom) $typoCount[$dom] = ($typoCount[$dom] ?? 0) + 1;

            $scores = data_get($r, 'sjt_results.scores', []);
            if (is_array($scores)) {
                foreach ($scores as $name => $v) {
                    $score = is_array($v) ? ($v['score'] ?? null) : $v;
                    if (is_numeric($score)) {
                        if (!isset($compScores[$name])) $compScores[$name] = ['sum' => 0.0, 'n' => 0];
                        $compScores[$name]['sum'] += (float)$score;
                        $compScores[$name]['n']   += 1;
                    }
                }
            }

            $top3 = data_get($r, 'sjt_results.top3', []);
            if (is_array($top3)) {
                foreach ($top3 as $t) {
                    $nm = data_get($t, 'name');
                    if ($nm) $top3Freq[$nm] = ($top3Freq[$nm] ?? 0) + 1;
                }
            }
        }

        $compAvg = [];
        foreach ($compScores as $name => $agg) {
            $compAvg[$name] = $agg['n'] ? round($agg['sum'] / $agg['n'], 2) : null;
        }
        arsort($typoCount);
        arsort($compAvg);
        arsort($top3Freq);

        // data untuk view
        $events = $this->commonData()['events'];

        return compact('typoCount', 'compAvg', 'top3Freq', 'events') + ['f' => $f];
    }
}
