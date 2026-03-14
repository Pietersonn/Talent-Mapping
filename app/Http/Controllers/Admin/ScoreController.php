<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ScoreController extends Controller
{
    private function commonData(): array
    {
        $Programs = Program::query()->orderBy('tanggal_mulai', 'desc')->get(['id', 'nama', 'kode_program']);
        return compact('Programs');
    }

    private function baseParticipantsQuery(array $filters, bool $onlyWithResults = true)
    {
        $topCompExpr = "JSON_UNQUOTE(JSON_EXTRACT(tr.hasil_tk, '$.top3[0].name'))";

        $q = DB::table('sesi_tes as ts')
            ->join('pengguna as u', 'u.id', '=', 'ts.id_pengguna')
            ->leftJoin('program as e', 'e.id', '=', 'ts.id_program')
            ->leftJoin('hasil_tes as tr', 'tr.id_sesi', '=', 'ts.id')
            ->select(
                'ts.id as session_id', 'u.nama as name', 'u.email',
                'u.nomor_telepon as phone_number', 'ts.latar_belakang as instansi',
                'ts.jabatan as position', 'e.nama as Program_name',
                'e.kode_program as Program_code', 'tr.hasil_tk',
                DB::raw("{$topCompExpr} as top_competency")
            );

        if (!empty($filters['Program_id'])) {
            $q->where('ts.id_program', $filters['Program_id']);
        }

        if (($filters['q'] ?? '') !== '') {
            $term = $filters['q'];
            $q->where(function ($w) use ($term) {
                $w->where('u.nama', 'like', "%{$term}%")
                  ->orWhere('u.email', 'like', "%{$term}%")
                  ->orWhere('ts.latar_belakang', 'like', "%{$term}%");
            });
        }

        if ($onlyWithResults) $q->whereNotNull('tr.hasil_tk');

        return $q;
    }

    private function processScores($results)
    {
        return $results->map(function ($row) {
            $totalScore = 0; $tkData = null;
            if (!empty($row->hasil_tk)) {
                $tkData = json_decode($row->hasil_tk, true);
                foreach ($tkData['all'] ?? [] as $c) {
                    if (isset($c['score']) && is_numeric($c['score'])) $totalScore += (float)$c['score'];
                }
            }
            $row->total_score = $totalScore;
            $competencies = collect([]);
            if (isset($tkData['all'])) {
                foreach ($tkData['all'] as $c) {
                    if (isset($c['code'], $c['score'])) $competencies->put($c['code'], $c['score']);
                }
            }
            foreach (['SM','CIA','TS','WWO','CA','L','SE','PS','PE','GH'] as $code) {
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
            'Program_id' => 'nullable|string|exists:program,id',
            'q'        => 'nullable|string|max:255',
        ]);

        $mode    = $validated['mode'] ?? 'all';
        $n       = (int)($validated['n'] ?? 10);
        $filters = ['Program_id' => $validated['Program_id'] ?? null, 'q' => $validated['q'] ?? null];
        $Programs  = $this->commonData()['Programs'];

        $processedRows = $this->processScores(
            $this->baseParticipantsQuery($filters, true)->orderBy('u.nama')->orderBy('ts.id')->get()
        );

        $sortedRows = match ($mode) {
            'top'    => $processedRows->sortByDesc('total_score')->take($n),
            'bottom' => $processedRows->sortBy('total_score')->take($n),
            default  => $processedRows->sortByDesc('total_score'),
        };

        $rows = $pagination = null;
        if ($mode === 'all') {
            $perPage = 25; $currentPage = $req->integer('page', 1);
            $paginatedItems = $sortedRows->slice(($currentPage - 1) * $perPage, $perPage);
            $pagination = new LengthAwarePaginator($paginatedItems->values(), $sortedRows->count(), $perPage, $currentPage, ['path' => $req->url(), 'query' => $req->query()]);
            $rows = $paginatedItems->values();
        } else {
            $rows = $sortedRows->values();
        }

        return view('admin.score.index', compact('Programs', 'mode', 'n', 'rows', 'pagination', 'filters'));
    }

    public function exportParticipantsPdf(Request $req)
    {
        $mode = $req->query('mode', 'all'); $n = (int)$req->query('n', 10);
        $filters = ['Program_id' => $req->query('Program_id'), 'q' => trim((string)$req->query('q', ''))];

        $processedRows = $this->processScores(
            $this->baseParticipantsQuery($filters, true)->orderBy('u.nama')->orderBy('ts.id')->get()
        );

        $rows = match ($mode) {
            'top'    => $processedRows->sortByDesc('total_score')->take($n),
            'bottom' => $processedRows->sortBy('total_score')->take($n),
            default  => $processedRows->sortByDesc('total_score'),
        };

        $modeText = match ($mode) {
            'top'    => "Top {$n} Peserta (Skor Tertinggi)",
            'bottom' => "Bottom {$n} Peserta (Skor Terendah)",
            default  => "Semua Peserta",
        };

        $filterParts = [];
        if (!empty($filters['Program_id'])) $filterParts[] = "Program: ".(Program::find($filters['Program_id'])?->nama ?? '-');
        if (!empty($filters['q']))         $filterParts[] = "Pencarian: '{$filters['q']}'";

        $pdf = Pdf::loadView('admin.score.pdf.scoreReport', [
            'rows'        => $rows,
            'reportTitle' => 'Laporan Skor Kompetensi Peserta',
            'modeText'    => $modeText.(count($filterParts) ? ' — '.implode(', ', $filterParts) : ''),
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Skor_Kompetensi.pdf');
    }
}
