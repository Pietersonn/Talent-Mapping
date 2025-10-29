<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoringHelper
{
    /**
     * Hitung SJT + ST30 lalu simpan ringkasan ke table test_results.
     * Dipanggil synchronously dari controller agar hasil segera tersimpan.
     *
     * @param string $sessionId
     * @return void
     */
    public static function calculateAndSaveResults(string $sessionId): void
    {
        $session = DB::table('test_sessions')->where('id', $sessionId)->first();
        if (!$session) {
            Log::warning("calculateAndSaveResults: session not found {$sessionId}");
            return;
        }

        // ---------------- SJT aggregation ----------------
        $agg = DB::table('sjt_responses as sr')
            ->join('sjt_options as so', function ($j) {
                $j->on('sr.question_id', '=', 'so.question_id')
                    ->on('sr.selected_option', '=', 'so.option_letter');
            })
            ->where('sr.session_id', $sessionId)
            ->select('so.competency_target', DB::raw('AVG(so.score) * 5 as scaled'))
            ->groupBy('so.competency_target')
            ->get()
            ->keyBy('competency_target');

        $comp = DB::table('competency_descriptions')->get()->keyBy('competency_code');

        $ranked = collect($agg)->map(function ($row, $code) use ($comp) {
            $m = $comp[$code] ?? null;
            return [
                'code'     => $code,
                'name'     => $m->competency_name ?? $code,
                'score'    => round((float)$row->scaled, 1),
                'strength' => (string)($m->strength_description ?? ''),
                'weakness' => (string)($m->weakness_description ?? ''),
                'activity' => (string)($m->improvement_activity ?? ''),
                'training' => (string)($m->training_recommendations ?? ''),
            ];
        })->sortByDesc('score')->values();

        $top3    = $ranked->take(3)->values()->all();
        $bottom3 = $ranked->sortBy('score')->take(3)->values()->all();

        // ---------------- ST-30 processing ----------------
        // Cast to string before json_decode to avoid "array given" error
        $st1Raw = (string) DB::table('st30_responses')
            ->where('session_id', $sessionId)
            ->where('stage_number', 1)
            ->where('for_scoring', 1)
            ->value('selected_items');

        $st2Raw = (string) DB::table('st30_responses')
            ->where('session_id', $sessionId)
            ->where('stage_number', 2)
            ->where('for_scoring', 1)
            ->value('selected_items');

        $st1Ids = json_decode($st1Raw, true) ?: [];
        $st2Ids = json_decode($st2Raw, true) ?: [];

        $dominantTypologyCode = null;
        if (!empty($st1Ids)) {
            $firstSt1 = DB::table('st30_questions')->where('id', $st1Ids[0])->first();
            $dominantTypologyCode = $firstSt1->typology_code ?? null;
        }

        $st1Typos = collect();
        if (!empty($st1Ids)) {
            $st1Typos = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st1Ids)
                ->select('t.typology_code AS code', 't.typology_name AS name', 't.strength_description AS desc')
                ->distinct()
                ->get();
        }

        $st2Typos = collect();
        if (!empty($st2Ids)) {
            $st2Typos = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st2Ids)
                ->select('t.typology_code AS code', 't.typology_name AS name', 't.weakness_description AS desc')
                ->distinct()
                ->get();
        }



        // ---------------- Persist to test_results ----------------
        $payload = [
            'sjt_results' => json_encode([
                'top3' => $top3,
                'bottom3' => $bottom3,
                'all' => $ranked->values(),
            ], JSON_UNESCAPED_UNICODE),
            'st30_results' => json_encode([
                'strengths' => $st1Typos->values(),
                'weakness'  => $st2Typos->values(),
            ], JSON_UNESCAPED_UNICODE),
            'dominant_typology' => $dominantTypologyCode,
            'report_generated_at' => null,
            'pdf_path' => null,
            'updated_at' => now(),
        ];


        $existing = DB::table('test_results')->where('session_id', $sessionId)->first();
        if ($existing) {
            DB::table('test_results')->where('id', $existing->id)->update($payload);
        } else {
            // create deterministic TR id like earlier logic
            $trId = self::generateTestResultId();

            $payload['id'] = $trId;
            $payload['session_id'] = $sessionId;
            $payload['created_at'] = now();

            DB::table('test_results')->insert($payload);
        }
    }

    private static int $resultCounter = 0;

    private static function generateTestResultId(): string
    {
        self::$resultCounter++;
        return 'TR' . str_pad(self::$resultCounter, 3, '0', STR_PAD_LEFT);
    }
}
