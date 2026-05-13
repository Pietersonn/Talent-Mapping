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
use Illuminate\Support\Facades\Log;

class GenerateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        try {
            Log::info("Job GenerateAssessmentReport DIMULAI untuk session: {$this->sessionId}");

            // ---- 1) Ambil Session & User (Gunakan tabel bahasa Indonesia)
            $session = DB::table('sesi_tes')->where('id', $this->sessionId)->first();
            if (!$session) {
                Log::warning("GenerateAssessmentReport: Sesi {$this->sessionId} tidak ditemukan.");
                return;
            }

            // PERBAIKAN: Gunakan tabel 'pengguna' bukan 'users'
            $user = DB::table('pengguna')->where('id', $session->id_pengguna)->first();
            $recipientName  = $session->nama_peserta ?: ($user->nama ?? 'Peserta');
            $recipientEmail = $user->email ?? null;

            $program = DB::table('program')->where('id', $session->id_program)->first();
            $programName = $program->nama_program ?? 'Talent Mapping';

            // ---- 2) Ambil Hasil dari tabel hasil_tes
            $result = DB::table('hasil_tes')->where('id_sesi', $this->sessionId)->first();
            if (!$result) {
                Log::warning("GenerateAssessmentReport: Hasil untuk sesi {$this->sessionId} tidak ditemukan.");
                return;
            }

            // ---- 3) Parse TK Data (Langsung ambil top3 & bottom3 dari JSON database)
            $tkData = json_decode((string) $result->hasil_tk, true) ?: [];

            // Mengambil struktur yang sudah dibuat oleh ScoringHelper
            $top3 = $tkData['top3'] ?? [];
            $bottom3 = $tkData['bottom3'] ?? [];

            // ---- 4) Parse Data ST-30
            $st1Raw = DB::table('jawaban_st30')->where('id_sesi', $this->sessionId)->where('nomor_tahap', 1)->value('item_dipilih');
            $st2Raw = DB::table('jawaban_st30')->where('id_sesi', $this->sessionId)->where('nomor_tahap', 2)->value('item_dipilih');

            $st1Ids = json_decode((string) $st1Raw, true) ?: [];
            $st2Ids = json_decode((string) $st2Raw, true) ?: [];

            $st30Strengths = collect();
            if (!empty($st1Ids)) {
                $st30Strengths = DB::table('soal_st30 as q')
                    ->join('deskripsi_tipologi as t', 't.kode_tipologi', '=', 'q.kode_tipologi')
                    ->whereIn('q.id', $st1Ids)
                    ->select('t.kode_tipologi AS code', 't.nama_tipologi AS name', 't.deskripsi_kekuatan AS desc')
                    ->distinct()->get();
            }

            $st30Weakness = collect();
            if (!empty($st2Ids)) {
                $st30Weakness = DB::table('soal_st30 as q')
                    ->join('deskripsi_tipologi as t', 't.kode_tipologi', '=', 'q.kode_tipologi')
                    ->whereIn('q.id', $st2Ids)
                    ->select('t.kode_tipologi AS code', 't.nama_tipologi AS name', 't.deskripsi_kelemahan AS desc')
                    ->distinct()->get();
            }

            // ---- 5) Helper Pemecah Paragraf Rekomendasi (Sesuai Logic Repo Lama)
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
            foreach ($bottom3 as $row) {
                $recoActivityBoxes[] = $fmtDash($toPointsSmart($row['activity'] ?? '', 5));
                $recoTrainingBoxes[] = $fmtDash($toPointsSmart($row['training'] ?? '', 5));
            }

            // ---- 6) Gabungkan Data untuk View PDF
            $data = [
                'user'           => ['name' => $recipientName, 'email' => $recipientEmail],
                'tk_top3'        => $top3,
                'tk_bottom3'     => $bottom3,
                'reco_activity'  => $recoActivityBoxes,
                'reco_training'  => $recoTrainingBoxes,
                'st30_strengths' => $st30Strengths,
                'st30_weakness'  => $st30Weakness,
                'pages'          => [
                    'person', 'person 2', 'person 3', 'person 4', 'user',
                    'person 5', 'person 6', 'person 7', 'person 8', 'person 9',
                    'person 10', 'person 11', 'person 12', 'person 13', 'person 14', 'person 15'
                ],
            ];

            // ---- 7) Generate PDF & Simpan
            $cleanName = preg_replace('/[^\w\s\-]/u', '', $recipientName);
            $fileName  = "{$cleanName} ({$programName}).pdf";
            $relativePath = "reports/{$fileName}";

            $pdf = Pdf::loadView('public.pdf.report', $data)->setPaper('a4', 'landscape');
            $pdfContent = $pdf->output();

            Storage::disk('public')->put($relativePath, $pdfContent);

            // ---- 8) Update path_pdf di database
            DB::table('hasil_tes')->where('id_sesi', $this->sessionId)->update([
                'path_pdf' => $relativePath,
                'updated_at' => now(),
            ]);

            // ---- 9) Kirim Email
            if ($recipientEmail) {
                $body = "Halo {$recipientName},\n\nTerima kasih telah mengikuti program {$programName}.\nBerikut lampiran hasil asesmen Anda.\n\nSalam,\nTim BCTI";

                Mail::raw($body, function ($m) use ($recipientEmail, $recipientName, $pdfContent, $fileName) {
                    $m->to($recipientEmail, $recipientName)
                      ->subject('Hasil Talent Mapping Anda')
                      ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']);
                });

                DB::table('hasil_tes')->where('id_sesi', $this->sessionId)->update(['email_terkirim_pada' => now()]);
            }

            Log::info("Job GenerateAssessmentReport SELESAI!");

        } catch (\Throwable $e) {
            Log::error("Job GAGAL: " . $e->getMessage());
            throw $e;
        }
    }
}
