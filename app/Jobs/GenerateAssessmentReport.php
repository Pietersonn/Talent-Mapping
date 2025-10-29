<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 120;

    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        // ---- 1) Ambil session & user
        $session = DB::table('test_sessions')->where('id', $this->sessionId)->first();
        if (!$session) return;

        $user = DB::table('users')->where('id', $session->user_id)->first();
        $recipientName  = $session->participant_name ?: ($user->name ?? 'Peserta');
        $recipientEmail = $user->email ?? null;

        // ---- 2) Ambil hasil dari test_results (sudah dihitung & disimpan di Controller)
        $result = DB::table('test_results')->where('session_id', $this->sessionId)->first();
        if (!$result) return;

        $sjtResults = json_decode((string)$result->sjt_results, true) ?: [];
        $st30Results = json_decode((string)$result->st30_results, true) ?: [];

        $top3    = $sjtResults['top3'] ?? [];
        $bottom3 = $sjtResults['bottom3'] ?? [];
        $dominantTypologyCode = $result->dominant_typology ?? null;
        $st1Ids = json_decode((string) DB::table('st30_responses')
            ->where('session_id', $this->sessionId)
            ->where('stage_number', 1)->where('for_scoring', 1)
            ->value('selected_items'), true) ?: [];

        $st2Ids = json_decode((string) DB::table('st30_responses')
            ->where('session_id', $this->sessionId)
            ->where('stage_number', 2)->where('for_scoring', 1)
            ->value('selected_items'), true) ?: [];

        $st30Strengths = collect();
        if (!empty($st1Ids)) {
            $st30Strengths = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st1Ids)
                ->select('t.typology_code AS code', 't.typology_name AS name', 't.strength_description AS desc')
                ->distinct()->get();
        }

        $st30Weakness = collect();
        if (!empty($st2Ids)) {
            $st30Weakness = DB::table('st30_questions as q')
                ->join('typology_descriptions as t', 't.typology_code', '=', 'q.typology_code')
                ->whereIn('q.id', $st2Ids)
                ->select('t.typology_code AS code', 't.typology_name AS name', 't.weakness_description AS desc')
                ->distinct()->get();
        }




        // ---- 3) Format rekomendasi dari bottom 3 kompetensi
        $toPointsSmart = function (?string $t, int $max = 5): array {
            $t = trim((string)$t);
            if ($t === '') return [];

            // Pisahkan per baris atau kalimat
            $parts = preg_split(
                '/\r\n|\r|\n|\s*\|\s*|(?<=[\.!?])\s+(?=\p{Lu})/u',
                $t
            );

            $parts = array_values(array_filter(array_map('trim', $parts), fn($s) => $s !== ''));
            $parts = array_map(fn($s) => rtrim($s, ". \t\n\r\0\x0B"), $parts);

            return array_slice($parts, 0, $max);
        };

        $fmtDash = fn(array $lines) => implode("\n", array_map(fn($s) => '- ' . $s, $lines));

        $recoActivityBoxes = [];
        $recoTrainingBoxes = [];

        foreach (array_slice($bottom3, 0, 3) as $row) {
            $recoActivityBoxes[] = $fmtDash($toPointsSmart($row['activity'] ?? '', 5));
            $recoTrainingBoxes[] = $fmtDash($toPointsSmart($row['training'] ?? '', 5));
        }

        // ---- 4) Siapkan data ke view PDF
        $data = [
            'user'           => ['name' => $recipientName, 'email' => $recipientEmail],
            'sjt_top3'       => $top3,
            'sjt_bottom3'    => $bottom3,
            'reco_activity'  => $recoActivityBoxes,
            'reco_training'  => $recoTrainingBoxes,
            'st30_strengths' => $st30Strengths,
            'st30_weakness'  => $st30Weakness,
            'pages'          => [
                'person',
                'person 2',
                'person 3',
                'person 4',
                'user',
                'person 5',
                'person 6',
                'person 7',
                'person 8',
                'person 9',
                'person 10',
                'person 11',
                'person 12',
                'person 13',
                'person 14',
                'person 15',
            ],
        ];

        // ---- 5) Generate & simpan PDF
        Storage::disk('local')->makeDirectory('reports');

        $fileName     = Str::of($recipientName)->slug(' ') . '.pdf';
        $relativePath = "reports/{$fileName}";

        $pdf = Pdf::loadView('public.pdf.report', $data)
            ->setPaper('a4', 'landscape');

        Storage::disk('local')->put($relativePath, $pdf->output());
        $absolutePath = Storage::disk('local')->path($relativePath);

        // ---- 6) Update test_results dengan path PDF
        DB::table('test_results')->where('session_id', $this->sessionId)->update([
            'pdf_path'            => $relativePath,
            'report_generated_at' => now(),
            'updated_at'          => now(),
        ]);

        // ---- 7) Kirim email hasil (jika ada email user)
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

        // ---- 8) Tandai sesi selesai (recap, safety)
        DB::table('test_sessions')->where('id', $this->sessionId)->update([
            'is_completed' => 1,
            'updated_at'   => now(),
        ]);
    }
}
