<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoringHelper
{
    public static function calculateAndSaveResults(string $sessionId): void
    {
        $session = DB::table('sesi_tes')->where('id', $sessionId)->first();
        if (!$session) {
            Log::warning("calculateAndSaveResults: sesi tidak ditemukan {$sessionId}");
            return;
        }

        // -------- Agregasi TK (SJT) --------
        $agg = DB::table('jawaban_tk as sr')
            ->join('pilihan_tk as so', function ($j) {
                $j->on('sr.id_soal', '=', 'so.id_soal')
                    ->on('sr.pilihan_dipilih', '=', 'so.huruf_pilihan');
            })
            ->where('sr.id_sesi', $sessionId)
            ->select('so.target_kompetensi', DB::raw('SUM(so.skor) as skor'))
            ->groupBy('so.target_kompetensi')
            ->get()
            ->keyBy('target_kompetensi');

        $comp = DB::table('deskripsi_kompetensi')->get()->keyBy('kode_kompetensi');

        $ranked = collect($agg)->map(function ($row, $code) use ($comp) {
            $m = $comp[$code] ?? null;
            $totalScore = (int) $row->skor;

            return [
                'code'     => $code,
                'name'     => $m->nama_kompetensi ?? $code,
                'score'    => $totalScore,
                'strength' => (string)($m->deskripsi_kekuatan ?? ''),
                'weakness' => (string)($m->deskripsi_kelemahan ?? ''),
                'activity' => (string)($m->aktivitas_pengembangan ?? ''),
                'training' => (string)($m->rekomendasi_pelatihan ?? ''),
            ];
        })->sortByDesc('score')->values();

        $top3    = $ranked->take(3)->values()->all();
        $bottom3 = $ranked->sortBy('score')->take(3)->values()->all();

        // -------- ST-30 processing --------
        $st1Raw = (string) DB::table('jawaban_st30')
            ->where('id_sesi', $sessionId)
            ->where('nomor_tahap', 1)
            ->where('untuk_penilaian', 1)
            ->value('item_dipilih');

        $st2Raw = (string) DB::table('jawaban_st30')
            ->where('id_sesi', $sessionId)
            ->where('nomor_tahap', 2)
            ->where('untuk_penilaian', 1)
            ->value('item_dipilih');

        $st1Ids = json_decode($st1Raw, true) ?: [];
        $st2Ids = json_decode($st2Raw, true) ?: [];

        $dominantTypologyCode = null;
        if (!empty($st1Ids)) {
            $firstSt1 = DB::table('soal_st30')->where('id', $st1Ids[0])->first();
            $dominantTypologyCode = $firstSt1->kode_tipologi ?? null;
        }

        $st1Typos = collect();
        if (!empty($st1Ids)) {
            $st1Typos = DB::table('soal_st30 as q')
                ->join('deskripsi_tipologi as t', 't.kode_tipologi', '=', 'q.kode_tipologi')
                ->whereIn('q.id', $st1Ids)
                ->select('t.kode_tipologi AS code', 't.nama_tipologi AS name', 't.deskripsi_kekuatan AS desc')
                ->distinct()
                ->get();
        }

        $st2Typos = collect();
        if (!empty($st2Ids)) {
            $st2Typos = DB::table('soal_st30 as q')
                ->join('deskripsi_tipologi as t', 't.kode_tipologi', '=', 'q.kode_tipologi')
                ->whereIn('q.id', $st2Ids)
                ->select('t.kode_tipologi AS code', 't.nama_tipologi AS name', 't.deskripsi_kelemahan AS desc')
                ->distinct()
                ->get();
        }

        // -------- Simpan ke hasil_tes --------
        $payload = [
            'hasil_tk' => json_encode([
                'top3'    => $top3,
                'bottom3' => $bottom3,
                'all'     => $ranked->values(),
            ], JSON_UNESCAPED_UNICODE),
            'hasil_st30' => json_encode([
                'strengths' => $st1Typos->values(),
                'weakness'  => $st2Typos->values(),
            ], JSON_UNESCAPED_UNICODE),
            'tipologi_dominan'    => $dominantTypologyCode,
            'laporan_dibuat_pada' => null,
            'path_pdf'            => null,
            'updated_at'          => now(),
        ];

        $existing = DB::table('hasil_tes')->where('id_sesi', $sessionId)->first();
        if ($existing) {
            DB::table('hasil_tes')->where('id', $existing->id)->update($payload);
        } else {
            $trId = self::generateTestResultId();
            $payload['id']         = $trId;
            $payload['id_sesi']    = $sessionId;
            $payload['created_at'] = now();
            DB::table('hasil_tes')->insert($payload);
        }
    }

    public static function generateTestResultId(): string
    {
        return DB::transaction(function () {
            $lastId = DB::table('hasil_tes')
                ->select('id')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->limit(1)
                ->value('id');

            $nextNumber = $lastId
                ? (int) preg_replace('/\D/', '', $lastId) + 1
                : 1;

            return 'TR' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    public static function validateScoringData($session): array
    {
        $errors = [];

        $st30Count = DB::table('jawaban_st30')
            ->where('id_sesi', $session->id)
            ->count();

        if ($st30Count === 0) {
            $errors[] = 'Tidak ada jawaban ST-30 ditemukan.';
        }

        $tkCount = DB::table('jawaban_tk')
            ->where('id_sesi', $session->id)
            ->count();

        if ($tkCount === 0) {
            $errors[] = 'Tidak ada jawaban TK ditemukan.';
        }

        return ['is_valid' => empty($errors), 'errors' => $errors];
    }
}
