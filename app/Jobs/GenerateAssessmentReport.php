<?php
// app/Jobs/GenerateAssessmentReport.php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class GenerateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID sesi tes (mis. TS503)
     */
    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        // === Session & user (nama & email)
        $session = DB::table('test_sessions')->where('id', $this->sessionId)->first();
        if (!$session) return;

        $user = DB::table('users')->where('id', $session->user_id)->first();
        $recipientName  = $session->participant_name ?: ($user->name ?? 'Peserta');
        $recipientEmail = $user->email ?? null;

        // === SJT aggregate 0..20 per competency
        $agg = DB::table('sjt_responses as sr')
            ->join('sjt_options as so', function ($j) {
                $j->on('sr.question_id', '=', 'so.question_id')
                  ->on('sr.selected_option', '=', 'so.option_letter');
            })
            ->where('sr.session_id', $this->sessionId)
            ->select('so.competency_target', DB::raw('AVG(so.score) * 5 as scaled'))
            ->groupBy('so.competency_target')
            ->get()
            ->keyBy('competency_target');

        // master kompetensi
        $comp = DB::table('competency_descriptions')->get()->keyBy('competency_code');

        // bentuk array utk blade person(5)/(6)/(7)/(12)
        $ranked = collect($agg)->map(function ($row, $code) use ($comp) {
            $m = $comp[$code] ?? null;
            return [
                'code'     => $code,
                'name'     => $m->competency_name ?? $code,
                'score'    => round((float)$row->scaled, 1),
                'strength' => $m->strength_description ?? '',
                'weakness' => $m->weakness_description ?? '',
                'activity' => $m->improvement_activity ?? '',
                'training' => $m->training_recommendations ?? '',
            ];
        })->sortByDesc('score')->values();

        $top3    = $ranked->take(3)->values();                 // person (5)
        $bottom3 = $ranked->sortBy('score')->take(3)->values(); // person (6),(7),(12)

        // === ST-30 dari selected_items (JSON) per stage
        $st1Json = DB::table('st30_responses')
            ->where('session_id', $this->sessionId)
            ->where('stage_number', 1)->where('for_scoring', 1)
            ->value('selected_items');

        $st2Json = DB::table('st30_responses')
            ->where('session_id', $this->sessionId)
            ->where('stage_number', 2)->where('for_scoring', 1)
            ->value('selected_items');

        $st1Ids = $st1Json ? json_decode($st1Json, true) : [];
        $st2Ids = $st2Json ? json_decode($st2Json, true) : [];

        // dominant typology = typology dari item pertama stage 1 (jika ada)
        $dominantTypologyCode = null;
        if (!empty($st1Ids)) {
            $firstSt1 = DB::table('st30_questions')->where('id', $st1Ids[0])->first();
            $dominantTypologyCode = $firstSt1->typology_code ?? null;
        }

        // Stage 1 → Potensi Kekuatan (OBJECTS: ->name, ->desc) untuk blade person(10)
        $st1Typos = collect();
        if (!empty($st1Ids)) {
            $st1Typos = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st1Ids)
                ->select('t.typology_name AS name', 't.strength_description AS desc')
                ->distinct()
                ->get();
        }

        // Stage 2 → Potensi Kelemahan (OBJECTS: ->name, ->desc) untuk blade person(11)
        $st2Typos = collect();
        if (!empty($st2Ids)) {
            $st2Typos = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st2Ids)
                ->select('t.typology_name AS name', 't.weakness_description AS desc')
                ->distinct()
                ->get();
        }

        // === Data ke view
        $data = [
            'user'            => ['name' => $recipientName, 'email' => $recipientEmail],
            'sjt_top3'        => $top3,
            'sjt_bottom3'     => $bottom3,
            'reco_activity'   => $bottom3->pluck('activity')->take(3)->values(),
            'reco_training'   => $bottom3->pluck('training')->take(3)->values(),
            'st30_strengths'  => $st1Typos->values(),  // objek: ->name, ->desc
            'st30_weakness'   => $st2Typos->values(),  // objek: ->name, ->desc
            'pages'           => [
                'person','person 2','person 3','person 4','user',
                'person 5','person 6','person 7','person 8','person 9',
                'person 10','person 11','person 12','person 13','person 14','person 15',
            ],
        ];

        // === Render PDF (VIEW: resources/views/public/pdf/report.blade.php)
        $pdf = Pdf::loadView('public.pdf.report', $data)->setPaper('a4', 'landscape');

        $fileName = Str::of($recipientName)->slug(' ') . '.pdf';
        $relative = "reports/{$fileName}";
        $absolute = storage_path("app/{$relative}");

        Storage::disk('local')->put($relative, $pdf->output());

        // === Upsert ringkasan & path ke test_results (BUKAN test_sessions)
        $st30Summary = ['strengths' => $st1Typos->values(), 'weakness' => $st2Typos->values()];
        $sjtSummary  = ['top3' => $top3, 'bottom3' => $bottom3];

        $existing = DB::table('test_results')->where('session_id', $this->sessionId)->first();
        if ($existing) {
            DB::table('test_results')->where('id', $existing->id)->update([
                'st30_results'        => json_encode($st30Summary, JSON_UNESCAPED_UNICODE),
                'sjt_results'         => json_encode($sjtSummary, JSON_UNESCAPED_UNICODE),
                'dominant_typology'   => $dominantTypologyCode,
                'report_generated_at' => now(),
                'pdf_path'            => $relative,
                'updated_at'          => now(),
            ]);
        } else {
            // generate TRxxx (panjang total 5: TR001..TR999) — cocok dgn kolom CHAR(5)
            do {
                $trId = 'TR' . str_pad((string)random_int(1, 999), 3, '0', STR_PAD_LEFT);
            } while (DB::table('test_results')->where('id', $trId)->exists());

            DB::table('test_results')->insert([
                'id'                  => $trId,
                'session_id'          => $this->sessionId,
                'st30_results'        => json_encode($st30Summary, JSON_UNESCAPED_UNICODE),
                'sjt_results'         => json_encode($sjtSummary, JSON_UNESCAPED_UNICODE),
                'dominant_typology'   => $dominantTypologyCode,
                'report_generated_at' => now(),
                'pdf_path'            => $relative,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        // === Kirim email (kalau ada email)
        if ($recipientEmail) {
            Mail::raw(
                "Halo {$recipientName},\n\nBerikut hasil Talent Assessment Anda terlampir.\n\nTerima kasih.",
                function ($m) use ($recipientEmail, $recipientName, $absolute) {
                    $m->to($recipientEmail, $recipientName)
                      ->subject('Hasil Talent Assessment')
                      ->attach($absolute);
                }
            );

            DB::table('test_results')->where('session_id', $this->sessionId)->update([
                'email_sent_at' => now(),
                'updated_at'    => now(),
            ]);
        }

        // (opsional) tandai sesi completed
        DB::table('test_sessions')->where('id', $this->sessionId)->update([
            'is_completed' => 1,
            'updated_at'   => now(),
        ]);
    }
}
