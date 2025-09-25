<?php

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

    public $tries   = 1;
    public $timeout = 120;

    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        // ---- 1) Session & user
        $session = DB::table('test_sessions')->where('id', $this->sessionId)->first();
        if (!$session) return;

        $user = DB::table('users')->where('id', $session->user_id)->first();
        $recipientName  = $session->participant_name ?: ($user->name ?? 'Peserta');
        $recipientEmail = $user->email ?? null;

        // ---- 2) Agregat SJT
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

        $comp = DB::table('competency_descriptions')->get()->keyBy('competency_code');

        // Bentuk ranking + lengkapi nama & narasi dari master
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

        // ---- 3) ST-30
        $st1Ids = json_decode((string) DB::table('st30_responses')
            ->where('session_id', $this->sessionId)
            ->where('stage_number', 1)->where('for_scoring', 1)
            ->value('selected_items'), true) ?: [];

        $st2Ids = json_decode((string) DB::table('st30_responses')
            ->where('session_id', $this->sessionId)
            ->where('stage_number', 2)->where('for_scoring', 1)
            ->value('selected_items'), true) ?: [];

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
                ->distinct()->get();
        }

        $st2Typos = collect();
        if (!empty($st2Ids)) {
            $st2Typos = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st2Ids)
                ->select('t.typology_code AS code', 't.typology_name AS name', 't.weakness_description AS desc')
                ->distinct()->get();
        }

        // ---- 3.1) Splitter & formatter bullet
        $toPointsSmart = function (?string $t, int $max = 5): array {
            $t = trim((string)$t);
            if ($t === '') return [];
            // newline | '|' | akhir kalimat (.!? + spasi + Huruf Kapital)
            $parts = preg_split('/\r\n|\r|\n|\s*\|\s*|(?<=[\.!?])\s+(?=\p{Lu})/u', $t);
            $parts = array_values(array_filter(array_map('trim', $parts), fn($s) => $s !== ''));
            $parts = array_map(fn($s) => rtrim($s, ". \t\n\r\0\x0B"), $parts);
            return array_slice($parts, 0, $max);
        };
        $fmtDash = fn(array $lines) => implode("\n", array_map(fn($s) => '- '.$s, $lines));

        // ---- 3.2) Siapkan isi 3 kotak (lemah #1..#3) berupa string multi-baris ber-dash
        $recoActivityBoxes = [];
        $recoTrainingBoxes = [];
        foreach (array_slice($bottom3, 0, 3) as $row) {
            $recoActivityBoxes[] = $fmtDash($toPointsSmart($row['activity'] ?? '', 5));
            $recoTrainingBoxes[] = $fmtDash($toPointsSmart($row['training'] ?? '', 5));
        }

        // ---- 4) Data ke view
        $data = [
            'user'            => ['name' => $recipientName, 'email' => $recipientEmail],
            'sjt_top3'        => $top3,                 // array
            'sjt_bottom3'     => $bottom3,              // array
            'reco_activity'   => $recoActivityBoxes,    // 3 string (tiap string multi-baris ber-dash)
            'reco_training'   => $recoTrainingBoxes,    // 3 string (tiap string multi-baris ber-dash)
            'st30_strengths'  => $st1Typos->values(),
            'st30_weakness'   => $st2Typos->values(),
            'pages'           => [
                'person','person 2','person 3','person 4','user',
                'person 5','person 6','person 7','person 8','person 9',
                'person 10','person 11','person 12','person 13','person 14','person 15',
            ],
        ];

        // ---- 5) Render & simpan ke storage/app/reports/<nama dengan spasi>.pdf
        Storage::disk('local')->makeDirectory('reports');

        $fileName     = Str::of($recipientName)->slug(' ') . '.pdf';
        $relativePath = "reports/{$fileName}";

        // PENTING: jangan setOptions agar metrik layout sama seperti versi “benar”
        $pdf = Pdf::loadView('public.pdf.report', $data)->setPaper('a4', 'landscape');

        Storage::disk('local')->put($relativePath, $pdf->output());
        $absolutePath = Storage::disk('local')->path($relativePath);

        // ---- 6) Persist ringkasan + path
        $payload = [
            'st30_results'        => json_encode([
                'strengths' => $st1Typos->values(),
                'weakness'  => $st2Typos->values(),
            ], JSON_UNESCAPED_UNICODE),
            'sjt_results'         => json_encode([
                'top3'     => $top3,
                'bottom3'  => $bottom3,
            ], JSON_UNESCAPED_UNICODE),
            'dominant_typology'   => $dominantTypologyCode,
            'report_generated_at' => now(),
            'pdf_path'            => $relativePath,
            'updated_at'          => now(),
        ];

        $existing = DB::table('test_results')->where('session_id', $this->sessionId)->first();
        if ($existing) {
            DB::table('test_results')->where('id', $existing->id)->update($payload);
        } else {
            do {
                $trId = 'TR' . str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
            } while (DB::table('test_results')->where('id', $trId)->exists());

            $payload['id']         = $trId;
            $payload['session_id'] = $this->sessionId;
            $payload['created_at'] = now();

            DB::table('test_results')->insert($payload);
        }

        // ---- 7) Kirim email (opsional: kalau ada email)
        if ($recipientEmail && is_file($absolutePath)) {
            Mail::raw(
                "Halo {$recipientName},\n\nBerikut hasil Talent Assessment Anda terlampir.\n\nTerima kasih.",
                function ($m) use ($recipientEmail, $recipientName, $absolutePath) {
                    $m->to($recipientEmail, $recipientName)
                      ->subject('Hasil Talent Assessment')
                      ->attach($absolutePath);
                }
            );

            DB::table('test_results')->where('session_id', $this->sessionId)->update([
                'email_sent_at' => now(),
                'updated_at'    => now(),
            ]);
        }

        // ---- 8) Tandai sesi selesai (opsional)
        DB::table('test_sessions')->where('id', $this->sessionId)->update([
            'is_completed' => 1,
            'updated_at'   => now(),
        ]);
    }
}
