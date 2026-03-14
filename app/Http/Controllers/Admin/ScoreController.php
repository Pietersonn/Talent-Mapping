<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ScoreController extends Controller
{
    private function filters(Request $r): array
    {
        return [
            'event_id' => $r->query('event_id'),
            'q'        => trim((string) $r->query('q', '')),
        ];
    }

    private function commonData(): array
    {
        $events = Event::query()->orderBy('tanggal_mulai', 'desc')->get(['id', 'nama', 'kode_acara']);
        return compact('events');
    }

    private function baseParticipantsQuery(array $filters, bool $onlyWithResults = true)
    {
        // JSON path tetap mengacu pada KEY di JSON (bukan nama kolom DB)
        $topCompExpr = "JSON_UNQUOTE(JSON_EXTRACT(tr.hasil_tk, '$.top3[0].name'))";

        $q = DB::table('sesi_tes as ts')
            ->join('pengguna as u', 'u.id', '=', 'ts.id_pengguna')
            ->leftJoin('acara as e', 'e.id', '=', 'ts.id_acara')
            ->leftJoin('hasil_tes as tr', 'tr.id_sesi', '=', 'ts.id')
            ->select(
                'ts.id as session_id',
                'u.nama as name',
                'u.email',
                'u.nomor_telepon as phone_number',
                'ts.latar_belakang as instansi',
                'ts.jabatan as position',
                'e.nama as event_name',
                'e.kode_acara as event_code',
                'tr.hasil_tk',
                DB::raw("{$topCompExpr} as top_competency")
            );

        if (!empty($filters['event_id'])) {
            $q->where('ts.id_acara', $filters['event_id']);
        }

        if (($filters['q'] ?? '') !== '') {
            $term = $filters['q'];
            $q->where(function ($w) use ($term) {
                $w->where('u.nama', 'like', "%{$term}%")
                    ->orWhere('u.email', 'like', "%{$term}%")
                    ->orWhere('ts.latar_belakang', 'like', "%{$term}%");
            });
        }

        if ($onlyWithResults) {
            $q->whereNotNull('tr.hasil_tk');
        }

        return $q;
    }

    private function processScores($results)
    {
        return $results->map(function ($row) {
            $totalScore = 0;
            $tkData     = null;

            if (!empty($row->hasil_tk)) {
                $tkData = json_decode($row->hasil_tk, true);
                $scores = $tkData['all'] ?? $tkData ?? [];
                if (is_array($scores)) {
                    foreach ($scores as $c) {
                        if (isset($c['score']) && is_numeric($c['score'])) {
                            $totalScore += (float) $c['score'];
                        }
                    }
                }
            }
            $row->total_score = $totalScore;

            $codes = ['SM', 'CIA', 'TS', 'WWO', 'CA', 'L', 'SE', 'PS', 'PE', 'GH'];
            $competencies = collect([]);
            if (isset($tkData['all']) && is_array($tkData['all'])) {
                foreach ($tkData['all'] as $c) {
                    if (isset($c['code'], $c['score'])) {
                        $competencies->put($c['code'], $c['score']);
                    }
                }
            }
            foreach ($codes as $code) {
                $row->{$code} = round($competencies->get($code, 0), 1);
            }

            return $row;
        });
    }

    public function participants(Request $req)
    {
        $validated = $req->validate([
            'mode'     => 'nullable|in:all,top,bottom',
            'n'        => 'nullable|integer|min:1|max:5000',
            'event_id' => 'nullable|string|exists:acara,id',
            'q'        => 'nullable|string|max:255',
        ]);

        $mode    = $validated['mode'] ?? 'all';
        $n       = (int) ($validated['n'] ?? 10);
        $filters = [
            'event_id' => $validated['event_id'] ?? null,
            'q'        => $validated['q'] ?? null,
        ];

        $common = $this->commonData();
        $events = $common['events'];

        $results      = $this->baseParticipantsQuery($filters, true)->orderBy('u.nama')->orderBy('ts.id')->get();
        $processedRows = $this->processScores($results);

        if ($mode === 'top') {
            $sortedRows = $processedRows->sortByDesc('total_score')->take($n);
        } elseif ($mode === 'bottom') {
            $sortedRows = $processedRows->sortBy('total_score')->take($n);
        } else {
            $sortedRows = $processedRows->sortByDesc('total_score');
        }

        $rows = null;
        $pagination = null;
        if ($mode === 'all') {
            $perPage     = 25;
            $currentPage = $req->integer('page', 1);
            $paginatedItems = $sortedRows->slice(($currentPage - 1) * $perPage, $perPage);
            $pagination = new LengthAwarePaginator(
                $paginatedItems->values(),
                $sortedRows->count(),
                $perPage,
                $currentPage,
                ['path' => $req->url(), 'query' => $req->query()]
            );
            $rows = $paginatedItems->values();
        } else {
            $rows = $sortedRows->values();
        }

        return view('admin.score.index', compact('events', 'mode', 'n', 'rows', 'pagination', 'filters'));
    }

    public function exportParticipantsPdf(Request $req)
    {
        $mode    = $req->query('mode', 'all');
        $n       = (int) $req->query('n', 10);
        $filters = [
            'event_id' => $req->query('event_id'),
            'q'        => trim((string) $req->query('q', '')),
        ];

        $results       = $this->baseParticipantsQuery($filters, true)->orderBy('u.nama')->orderBy('ts.id')->get();
        $processedRows = $this->processScores($results);

        if ($mode === 'top') {
            $rows = $processedRows->sortByDesc('total_score')->take($n);
        } elseif ($mode === 'bottom') {
            $rows = $processedRows->sortBy('total_score')->take($n);
        } else {
            $rows = $processedRows->sortByDesc('total_score');
        }

        $modeText = match ($mode) {
            'top'    => "Top {$n} Peserta (Skor Tertinggi)",
            'bottom' => "Bottom {$n} Peserta (Skor Terendah)",
            default  => "Semua Peserta",
        };

        $filterTextParts = [];
        if (!empty($filters['event_id'])) {
            $evtNama = Event::find($filters['event_id'])?->nama ?? '-';
            $filterTextParts[] = "Acara: {$evtNama}";
        }
        if (!empty($filters['q'])) {
            $filterTextParts[] = "Pencarian: '{$filters['q']}'";
        }
        $filterInfo = implode(', ', $filterTextParts);

        $pdf = Pdf::loadView('admin.score.pdf.scoreReport', [
            'rows'        => $rows,
            'reportTitle' => 'Laporan Skor Kompetensi Peserta',
            'modeText'    => $modeText . ($filterInfo ? " — {$filterInfo}" : ''),
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Skor_Kompetensi.pdf');
    }
}
