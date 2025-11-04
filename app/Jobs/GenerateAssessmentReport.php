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
use Illuminate\Support\Facades\Log;

class GenerateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Maks waktu job (detik) */
    public $timeout = 0;

    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        try {
            Log::info("Job GenerateAssessmentReport DIMULAI untuk session: {$this->sessionId}");

            // ---- 1) Ambil session & user
            $session = DB::table('test_sessions')->where('id', $this->sessionId)->first();
            if (!$session) {
                Log::warning("GenerateAssessmentReport: Test session {$this->sessionId} tidak ditemukan.");
                return;
            }
            $user = DB::table('users')->where('id', $session->user_id)->first();
            $recipientName  = $session->participant_name ?: ($user->name ?? 'Peserta');
            $recipientEmail = $user->email ?? null;

            $event = DB::table('events')->where('id', $session->event_id)->first();
            $eventName = $event->name ?? 'Talent Mapping'; // fallback kalau null

            // ---- 2) Ambil hasil dari test_results
            // Job ini MENGASUMSIKAN ScoringHelper sudah selesai dan TestResult sudah ADA.
            $result = DB::table('test_results')->where('session_id', $this->sessionId)->first();
            if (!$result) {
                Log::warning("GenerateAssessmentReport: Test result untuk session {$this->sessionId} tidak ditemukan (ScoringHelper mungkin gagal).");
                return;
            }

            // ---- 3) Susun data untuk view PDF
            $sjtResults  = json_decode((string) $result->sjt_results, true) ?: [];
            $st30Results = json_decode((string) $result->st30_results, true) ?: [];

            $top3    = $sjtResults['top3'] ?? [];
            $bottom3 = $sjtResults['bottom3'] ?? [];

            $st1Ids = json_decode((string) DB::table('st30_responses')
                ->where('session_id', $this->sessionId)
                ->where('stage_number', 1)
                ->where('for_scoring', 1)
                ->value('selected_items'), true) ?: [];

            $st2Ids = json_decode((string) DB::table('st30_responses')
                ->where('session_id', $this->sessionId)
                ->where('stage_number', 2)
                ->where('for_scoring', 1)
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

            $toPointsSmart = function (?string $t, int $max = 5): array {
                $t = trim((string) $t);
                if ($t === '') return [];
                $parts = preg_split('/\r\n|\r|\n|\s*\|\s*|(?<=[\.!?])\s+(?=\p{Lu})/u', $t);
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
                    'person 15'
                ],
            ];

            // ---- 4) Generate PDF
            $cleanName = preg_replace('/[^\w\s\-]/u', '', $recipientName);
            $fileName  = "{$cleanName} ({$eventName}).pdf";
            $relativePath = "reports/{$fileName}";
            Log::info("Membuat PDF: {$fileName} untuk session {$this->sessionId}");

            $pdf = Pdf::loadView('public.pdf.report', $data)->setPaper('a4', 'landscape');
            $pdfContent = $pdf->output();

            // ---- 5) Upload ke Supabase/S3 (visibility public)
            $ok = Storage::disk('s3')->put($relativePath, $pdfContent, ['visibility' => 'public']);
            if (!$ok) {
                throw new \Exception("Gagal meng-upload PDF ke Supabase (put() return false).");
            }

            // Optional: retry ringan untuk exists() (headObject kadang delay)
            $confirmed = false;
            for ($i = 0; $i < 3 && !$confirmed; $i++) {
                $confirmed = Storage::disk('s3')->exists($relativePath);
                if (!$confirmed) usleep(200000); // 200ms
            }
            if (!$confirmed) {
                Log::warning("Upload selesai tapi exists() belum konfirm untuk {$relativePath}");
            }

            Log::info("PDF berhasil DI-UPLOAD (verifikasi sebagian) di Supabase: {$relativePath}");

            // ---- 6) Update test_results
            DB::table('test_results')->where('session_id', $this->sessionId)->update([
                'pdf_path'          => $relativePath,
                'report_generated_at' => now(),
                'updated_at'          => now(),
            ]);

            // ---- 7) Bangun public URL (jika bucket public)
            $publicUrl = $this->buildSupabasePublicUrl($relativePath);

            // ---- 8) Kirim email via mailer default (SendGrid API)
            if ($recipientEmail) {
                try {
                    $body = "Halo {$recipientName},\n\n"
                        . "Terima kasih telah mengikuti program Talent Mapping.\n"
                        . "Berikut hasil asesmen Anda terlampir dalam format PDF.\n\n"
                        . "Semoga hasil ini dapat membantu Anda memahami potensi diri dengan lebih baik.\n\n"
                        . "Salam hangat,\nTim Talent Mapping";

                    Mail::raw($body, function ($m) use ($recipientEmail, $recipientName, $pdfContent, $fileName) {
                        $m->to($recipientEmail, $recipientName)
                            ->subject('Hasil Talent Mapping Anda')
                            ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']);
                    });

                    DB::table('test_results')->where('session_id', $this->sessionId)->update([
                        'email_sent_at' => now(),
                        'updated_at'    => now(),
                    ]);

                    Log::info("Email berhasil dikirim ke: {$recipientEmail}");
                } catch (\Throwable $e) {
                    Log::error("Email gagal dikirim: " . $e->getMessage(), ['session' => $this->sessionId]);
                }
            } else {
                Log::warning("Email penerima kosong untuk session {$this->sessionId}, lewati pengiriman email.");
            }

            // ---- 9) Tandai sesi selesai
            DB::table('test_sessions')->where('id', $this->sessionId)->update([
                'is_completed' => 1,
                'updated_at'   => now(),
            ]);

            Log::info("Job GenerateAssessmentReport SELESAI untuk session {$this->sessionId}");
        } catch (\Throwable $e) {
            Log::error("GAGAL memproses job GenerateAssessmentReport untuk session {$this->sessionId}: " . $e->getMessage());
            throw $e;
        }
    }

    private function buildSupabasePublicUrl(string $relativePath): ?string
    {
        $base   = rtrim((string) env('AWS_URL'), '/');    // ex: https://xxxx.supabase.co
        $bucket = trim((string) env('AWS_BUCKET', ''), '/'); // ex: report
        if ($base === '' || $bucket === '') return null;

        return "{$base}/storage/v1/object/public/{$bucket}/{$relativePath}";
    }
}
