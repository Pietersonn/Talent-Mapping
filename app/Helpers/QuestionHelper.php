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

    /**
     * Hitung skor tipologi dari jawaban ST-30 sesi tertentu.
     * Return: ['TIPE_CODE' => total_skor, ...]
     */
    public static function calculateST30Scores(string $sessionId): array
    {
        $responses = ST30Response::where('id_sesi', $sessionId)
            ->join('soal_st30 as q', 'q.id', '=', 'jawaban_st30.id_soal')
            ->select('q.kode_tipologi', 'jawaban_st30.skor_dipilih')
            ->get();

        $scores = [];
        foreach ($responses as $r) {
            $scores[$r->kode_tipologi] = ($scores[$r->kode_tipologi] ?? 0) + $r->skor_dipilih;
        }
        arsort($scores);
        return $scores;
    }

    /**
     * Dapatkan tipologi dominan (kode dengan skor tertinggi).
     */
    public static function getDominantTypology(string $sessionId): ?string
    {
        $scores = self::calculateST30Scores($sessionId);
        return array_key_first($scores);
    }

    /**
     * Dapatkan top-N tipologi.
     */
    public static function getTopTypologies(string $sessionId, int $n = 3): array
    {
        $scores = self::calculateST30Scores($sessionId);
        return array_slice($scores, 0, $n, true);
    }

    // ─── TK: Hitung Skor Kompetensi ───────────────────────────────────────────

    /**
     * Hitung skor kompetensi dari jawaban TK sesi tertentu.
     * Return: ['KODE_KOMPETENSI' => total_skor, ...]
     */
    public static function calculateTKScores(string $sessionId): array
    {
        $responses = TalentCompetencyResponse::where('id_sesi', $sessionId)
            ->join('soal_tk as q', 'q.id', '=', 'jawaban_tk.id_soal')
            ->join('pilihan_tk as o', function ($j) {
                $j->on('o.id_soal', '=', 'jawaban_tk.id_soal')
                  ->whereColumn('o.huruf_pilihan', 'jawaban_tk.pilihan_dipilih');
            })
            ->select('q.kode_kompetensi', 'o.skor')
            ->get();

        $scores = [];
        foreach ($responses as $r) {
            $scores[$r->kode_kompetensi] = ($scores[$r->kode_kompetensi] ?? 0) + $r->skor;
        }
        arsort($scores);
        return $scores;
    }

    /**
     * Hitung skor TK & format untuk disimpan ke kolom hasil_tk (JSON).
     * Struktur: { "all": [...], "top3": [...] }
     */
    public static function buildTKResultPayload(string $sessionId): array
    {
        $rawScores = self::calculateTKScores($sessionId);

        // Ambil semua deskripsi kompetensi sekali query
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

        // Sudah urut desc karena arsort
        $top3 = array_slice($all, 0, 3);

        return ['all' => $all, 'top3' => $top3];
    }

    // ─── ST-30: Hitung skor per tahap (untuk tampilan stage summary) ──────────

    /**
     * Hitung skor ST-30 dikelompokkan per nomor tahap (1-6, setiap 5 soal).
     */
    public static function calculateST30ByStage(string $sessionId, int $questionsPerStage = 5): array
    {
        $responses = ST30Response::where('id_sesi', $sessionId)
            ->join('soal_st30 as q', 'q.id', '=', 'jawaban_st30.id_soal')
            ->select('q.nomor', 'q.kode_tipologi', 'jawaban_st30.skor_dipilih')
            ->orderBy('q.nomor')
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

    // ─── Validasi Kelengkapan Jawaban ─────────────────────────────────────────

    public static function isST30Complete(string $sessionId, string $versionId): bool
    {
        $total    = ST30Question::where('id_versi', $versionId)->count();
        $answered = ST30Response::where('id_sesi', $sessionId)->count();
        return $answered >= $total;
    }

    public static function isTKComplete(string $sessionId): bool
    {
        $activeVersion = QuestionVersion::getActive('tk');
        if (!$activeVersion) return false;

        $total    = TalentCompetencyQuestion::where('id_versi', $activeVersion->id)->count();
        $answered = TalentCompetencyResponse::where('id_sesi', $sessionId)->count();
        return $answered >= $total;
    }

    // ─── Ambil Progress Peserta ───────────────────────────────────────────────

    public static function getProgress(string $sessionId, string $st30VersionId): array
    {
        $tkVersion  = QuestionVersion::getActive('tk');

        $totalST30  = ST30Question::where('id_versi', $st30VersionId)->count();
        $doneST30   = ST30Response::where('id_sesi', $sessionId)->count();

        $totalTK    = $tkVersion ? TalentCompetencyQuestion::where('id_versi', $tkVersion->id)->count() : 0;
        $doneTK     = TalentCompetencyResponse::where('id_sesi', $sessionId)->count();

        return [
            'st30' => ['total' => $totalST30, 'done' => $doneST30, 'pct' => $totalST30 > 0 ? round(($doneST30 / $totalST30) * 100) : 0],
            'tk'   => ['total' => $totalTK,   'done' => $doneTK,   'pct' => $totalTK > 0   ? round(($doneTK   / $totalTK)   * 100) : 0],
        ];
    }
}
