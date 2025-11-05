<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;


class ReportController extends Controller
{
    private function filters(Request $r): array
    {
        return [
            'event_id' => $r->query('event_id'),
            'instansi' => trim((string) $r->query('instansi', '')),
            'q'        => trim((string) $r->query('q', '')),
        ];
    }

    private function commonData()
    {
        $events = Event::query()->orderBy('start_date', 'desc')->get(['id', 'name', 'event_code']);
        return compact('events');
    }

    private function baseParticipantsQuery(array $filters, bool $onlyWithResults = true)
    {
        $topCompExpr = "JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results, '$.top3[0].name'))";

        $q = DB::table('test_sessions as ts')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->leftJoin('events as e', 'e.id', '=', 'ts.event_id')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->select(
                'ts.id as session_id',
                'u.name',
                'u.email',
                'u.phone_number',
                'ts.participant_background as instansi',
                'ts.position',
                'e.name as event_name',
                'e.event_code',
                'tr.sjt_results', // Ambil JSON
                DB::raw("{$topCompExpr} as top_competency")
            );

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
            $q->whereNotNull('tr.sjt_results');
        }

        return $q;
    }


    public function participants(Request $req)
    {
        $validated = $req->validate([
            'mode'     => 'nullable|in:all,top,bottom',
            'n'        => 'nullable|integer|min:1|max:5000',
            'event_id' => 'nullable|string|exists:events,id',
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

        $common = $this->commonData();
        $events = $common['events'];

        $q = $this->baseParticipantsQuery($filters, true);
        $results = $q->orderBy('u.name')->orderBy('ts.id')->get();

        $processedRows = $results->map(function ($row) {
            $totalScore = 0;
            if (!empty($row->sjt_results)) {
                $sjtData = json_decode($row->sjt_results, true);
                // Pastikan struktur JSON sesuai: { ..., "all": [ {"code": "...", "score": N}, ... ] }
                if (isset($sjtData['all']) && is_array($sjtData['all'])) {
                    foreach ($sjtData['all'] as $competency) {
                        if (isset($competency['score']) && is_numeric($competency['score'])) {
                            $totalScore += (float) $competency['score'];
                        }
                    }
                }
            }
            $row->total_score = $totalScore;
            return $row;
        });

        if ($mode === 'top') {
            $sortedRows = $processedRows->sortByDesc('total_score')->take($n);
        } elseif ($mode === 'bottom') {
            $sortedRows = $processedRows->sortBy('total_score')->take($n);
        } else { // mode 'all'
            $sortedRows = $processedRows->sortByDesc('total_score');
        }

        $rows = null;
        $pagination = null;
        if ($mode === 'all') {
            $perPage = 25;
            $currentPage = $req->integer('page', 1);
            $paginatedItems = $sortedRows->slice(($currentPage - 1) * $perPage, $perPage);

            $pagination = new LengthAwarePaginator(
                $paginatedItems->values(), // Gunakan values() agar collection direset indexnya
                $sortedRows->count(),
                $perPage,
                $currentPage,
                ['path' => $req->url(), 'query' => $req->query()]
            );
            $rows = $paginatedItems->values(); // Ambil item untuk halaman saat ini
        } else {
            $rows = $sortedRows->values();
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


    public function exportParticipantsPdf(Request $req)
    {
        $mode     = $req->query('mode', 'all');
        $n        = (int) $req->query('n', 10);
        $eventId  = $req->query('event_id');
        $instansi = trim((string) $req->query('instansi', ''));
        $search   = trim((string) $req->query('q', ''));

        $filters = [
            'event_id' => $eventId,
            'instansi' => $instansi,
            'q'        => $search,
        ];

        $q = $this->baseParticipantsQuery($filters, true);
        $results = $q->orderBy('u.name')->orderBy('ts.id')->get();

        $processedRows = $results->map(function ($row) {
            $totalScore = 0;
            $sjtData = null; // Inisialisasi sjtData
            if (!empty($row->sjt_results)) {
                $sjtData = json_decode($row->sjt_results, true);
                if (isset($sjtData['all']) && is_array($sjtData['all'])) {
                    foreach ($sjtData['all'] as $competency) {
                        if (isset($competency['score']) && is_numeric($competency['score'])) {
                            $totalScore += (float) $competency['score'];
                        }
                    }
                }
            }
            $row->total_score = $totalScore;

            // Parsing detail kompetensi untuk PDF
            $codes = ['SM', 'CIA', 'TS', 'WWO', 'CA', 'L', 'SE', 'PS', 'PE', 'GH'];
            $competencies = collect([]);
            // Gunakan $sjtData yang sudah di-decode
            if (isset($sjtData['all']) && is_array($sjtData['all'])) {
                foreach ($sjtData['all'] as $c) {
                    if (isset($c['code'], $c['score'])) {
                        $competencies->put($c['code'], $c['score']);
                    }
                }
            }
            foreach ($codes as $code) {
                // Pastikan kolom ini memang dibutuhkan oleh view PDF ('admin.reports.pdf.participants')
                $row->{$code} = round($competencies->get($code, 0), 1);
            }

            return $row;
        });

        if ($mode === 'top') {
            $rows = $processedRows->sortByDesc('total_score')->take($n);
        } elseif ($mode === 'bottom') {
            $rows = $processedRows->sortBy('total_score')->take($n);
        } else {
            $rows = $processedRows->sortByDesc('total_score');
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
            'generatedBy' => Auth::user()->name ?? 'Admin',
            'generatedAt' => now('Asia/Makassar')->format('d M Y H:i') . ' WITA',
        ];

        // Pastikan view PDF 'admin.reports.pdf.participants' ada dan sesuai
        $pdf = Pdf::loadView('pic.participants.pdf.report-participant', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]); // WAJIB untuk membaca URL (asset())

        return $pdf->stream('participants-report.pdf');
    }
}
