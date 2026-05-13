<?php

namespace App\Helpers;

use App\Models\ST30Response;
use App\Models\TalentCompetencyResponse;
use App\Models\TalentCompetencyQuestion;
use App\Models\ST30Question;
use App\Models\TypologyDescription;
use App\Models\CompetencyDescription;
use App\Models\QuestionVersion;
use Illuminate\Support\Collection;

class QuestionHelper
{
    // ─── ST-30: Hitung Tipologi Dominan ───────────────────────────────────────

    public static function calculateST30Scores(string $sessionId): array
    {
        $responses = ST30Response::where('id_sesi', $sessionId)
            ->join('soal_st30', 'soal_st30.id', '=', 'jawaban_st30.id_soal')
            ->select('soal_st30.kode_tipologi', 'jawaban_st30.skor_dipilih')
            ->get();

        $scores = [];
        foreach ($responses as $r) {
            $scores[$r->kode_tipologi] = ($scores[$r->kode_tipologi] ?? 0) + $r->skor_dipilih;
        }
        arsort($scores);
        return $scores;
    }

    public static function getDominantTypology(string $sessionId): ?string
    {
        $scores = self::calculateST30Scores($sessionId);
        return array_key_first($scores);
    }

    public static function getTopTypologies(string $sessionId, int $n = 3): array
    {
        $scores = self::calculateST30Scores($sessionId);
        return array_slice($scores, 0, $n, true);
    }

    // ─── TK: Hitung Skor Kompetensi ───────────────────────────────────────────

    public static function calculateTKScores(string $sessionId): array
    {
        $responses = TalentCompetencyResponse::where('id_sesi', $sessionId)
            ->join('soal_tk', 'soal_tk.id', '=', 'jawaban_tk.id_soal')
            ->join('pilihan_tk', function ($j) {
                $j->on('pilihan_tk.id_soal', '=', 'jawaban_tk.id_soal')
                    ->whereColumn('pilihan_tk.huruf_pilihan', 'jawaban_tk.pilihan_dipilih');
            })
            // UBAH BARIS INI: Gunakan target_kompetensi dari tabel pilihan_tk
            ->select('pilihan_tk.target_kompetensi', 'pilihan_tk.skor')
            ->get();

        $scores = [];
        foreach ($responses as $r) {
            // UBAH BARIS INI: Mengikuti alias yang di-select di atas
            $scores[$r->target_kompetensi] = ($scores[$r->target_kompetensi] ?? 0) + $r->skor;
        }
        arsort($scores);
        return $scores;
    }

    public static function buildTKResultPayload(string $sessionId): array
    {
        $rawScores = self::calculateTKScores($sessionId);

        $descs = CompetencyDescription::whereIn('kode_kompetensi', array_keys($rawScores))
            ->pluck('nama_kompetensi', 'kode_kompetensi');

        $all = [];
        foreach ($rawScores as $code => $score) {
            $all[] = [
                'code'  => $code,
                'name'  => $descs[$code] ?? $code,
                'score' => $score,
            ];
        }

        $top3 = array_slice($all, 0, 3);

        return ['all' => $all, 'top3' => $top3];
    }

    // ─── BANTUAN FUNGSI MAPPING / VALIDASI LAINNYA ────────────────────────────

    public static function getSelectedQuestionIds($session, array $stages): array
    {
        $selected = [];
        $responses = ST30Response::where('id_sesi', $session->id)
            ->whereIn('nomor_tahap', $stages)
            ->get();

        foreach ($responses as $r) {
            $selected = array_merge($selected, is_array($r->item_dipilih) ? $r->item_dipilih : json_decode($r->item_dipilih, true));
        }
        return array_unique($selected);
    }

    public static function calculateST30ByStage(string $sessionId, int $questionsPerStage = 5): array
    {
        $responses = ST30Response::where('id_sesi', $sessionId)
            ->join('soal_st30', 'soal_st30.id', '=', 'jawaban_st30.id_soal')
            ->select('soal_st30.nomor', 'soal_st30.kode_tipologi', 'jawaban_st30.skor_dipilih')
            ->orderBy('soal_st30.nomor')
            ->get();

        $stages = [];
        foreach ($responses as $r) {
            $stage = (int) ceil($r->nomor / $questionsPerStage);
            if (!isset($stages[$stage])) $stages[$stage] = ['tahap' => $stage, 'skor' => 0, 'item_dipilih' => []];
            $stages[$stage]['skor']          += $r->skor_dipilih;
            $stages[$stage]['item_dipilih'][] = $r->kode_tipologi;
        }

        return array_values($stages);
    }

    public static function isST30Complete(string $sessionId, string $versionId): bool
    {
        $total    = ST30Question::where('id_versi', $versionId)->where('aktif', true)->count();
        $answered = ST30Response::where('id_sesi', $sessionId)->count();
        return $answered >= $total;
    }

    public static function isTKComplete(string $sessionId): bool
    {
        $activeVersion = QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();
        if (!$activeVersion) return false;

        $total    = TalentCompetencyQuestion::where('id_versi', $activeVersion->id)->where('aktif', true)->count();
        $answered = TalentCompetencyResponse::where('id_sesi', $sessionId)->count();
        return $answered >= $total;
    }

    public static function getProgress(string $sessionId, string $st30VersionId): array
    {
        $tkVersion  = QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();

        $totalST30  = ST30Question::where('id_versi', $st30VersionId)->where('aktif', true)->count();
        $doneST30   = ST30Response::where('id_sesi', $sessionId)->count();

        $totalTK    = $tkVersion ? TalentCompetencyQuestion::where('id_versi', $tkVersion->id)->where('aktif', true)->count() : 0;
        $doneTK     = TalentCompetencyResponse::where('id_sesi', $sessionId)->count();

        return [
            'st30' => ['total' => $totalST30, 'done' => $doneST30, 'pct' => $totalST30 > 0 ? round(($doneST30 / $totalST30) * 100) : 0],
            'tk'   => ['total' => $totalTK,   'done' => $doneTK,   'pct' => $totalTK > 0   ? round(($doneTK   / $totalTK)   * 100) : 0],
        ];
    }
}
