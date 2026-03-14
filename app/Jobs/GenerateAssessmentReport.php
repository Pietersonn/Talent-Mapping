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

    public $timeout = 0;

    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        try {
            Log::info("Job GenerateAssessmentReport DIMULAI untuk sesi: {$this->sessionId}");

            // ---- 1) Ambil sesi & pengguna ----
            $session = DB::table('sesi_tes')->where('id', $this->sessionId)->first();
            if (!$session) {
                Log::warning("GenerateAssessmentReport: Sesi {$this->sessionId} tidak ditemukan.");
                return;
            }

            $user           = DB::table('pengguna')->where('id', $session->id_pengguna)->first();
            $recipientName  = $session->nama_peserta ?: ($user->nama ?? 'Peserta');
            $recipientEmail = $user->email ?? null;

            $event     = DB::table('acara')->where('id', $session->id_acara)->first();
            $eventName = $event->nama ?? 'Talent Mapping';

            // ---- 2) Ambil hasil dari hasil_tes ----
            $result = DB::table('hasil_tes')->where('id_sesi', $this->sessionId)->first();
            if (!$result) {
                Log::warning("GenerateAssessmentReport: Hasil tes untuk sesi {$this->sessionId} tidak ditemukan.");
                return;
            }

            // ---- 3) Susun data PDF ----
            $tkResults   = json_decode((string) $result->hasil_tk,   true) ?: [];
            $st30Results = json_decode((string) $result->hasil_st30, true) ?: [];

            $top3    = $tkResults['top3']    ?? [];
            $bottom3 = $tkResults['bottom3'] ?? [];

            $st1Ids = json_decode((string) DB::table('jawaban_st30')
                ->where('id_sesi', $this->sessionId)
                ->where('nomor_tahap', 1)
                ->where('untuk_penilaian', 1)
                ->value('item_dipilih'), true) ?: [];

            $st2Ids = json_decode((string) DB::table('jawaban_st30')
                ->where('id_sesi', $this->sessionId)
                ->where('nomor_tahap', 2)
                ->where('untuk_penilaian', 1)
                ->value('item_dipilih'), true) ?: [];

            // ---- 4) Ambil tipologi ST-30 ----
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
                    ->distinct()->get();
            }

            $st2Typos = collect();
            if (!empty($st2Ids)) {
                $st2Typos = DB::table('soal_st30 as q')
                    ->join('deskripsi_tipologi as t', 't.kode_tipologi', '=', 'q.kode_tipologi')
                    ->whereIn('q.id', $st2Ids)
                    ->select('t.kode_tipologi AS code', 't.nama_tipologi AS name', 't.deskripsi_kelemahan AS desc')
                    ->distinct()->get();
            }

            $dominantTypology = $dominantTypologyCode
                ? DB::table('deskripsi_tipologi')->where('kode_tipologi', $dominantTypologyCode)->first()
                : null;

            // ---- 5) Generate PDF ----
            $pdfData = [
                'recipientName'    => $recipientName,
                'eventName'        => $eventName,
                'top3'             => $top3,
                'bottom3'          => $bottom3,
                'allCompetencies'  => $tkResults['all'] ?? [],
                'st1Typos'         => $st1Typos,
                'st2Typos'         => $st2Typos,
                'dominantTypology' => $dominantTypology,
                'generatedAt'      => now('Asia/Makassar')->format('d M Y H:i') . ' WITA',
            ];

            $pdf = Pdf::loadView('public.test.result-pdf', $pdfData)
                ->setPaper('a4', 'portrait')
                ->setOptions(['isRemoteEnabled' => true]);

            $pdfContent  = $pdf->output();
            $fileName    = 'hasil-talent-mapping-' . Str::slug($recipientName) . '-' . $this->sessionId . '.pdf';
            $relativePath = 'reports/' . $fileName;

            Storage::disk('local')->put($relativePath, $pdfContent);

            DB::table('hasil_tes')->where('id_sesi', $this->sessionId)->update([
                'tipologi_dominan'    => $dominantTypologyCode,
                'laporan_dibuat_pada' => now(),
                'path_pdf'            => $relativePath,
                'updated_at'          => now(),
            ]);

            // ---- 6) Kirim email ----
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

                    DB::table('hasil_tes')->where('id_sesi', $this->sessionId)->update([
                        'email_terkirim_pada' => now(),
                        'updated_at'          => now(),
                    ]);

                    Log::info("Email berhasil dikirim ke: {$recipientEmail}");
                } catch (\Throwable $e) {
                    Log::error("Email gagal dikirim: " . $e->getMessage());
                }
            }

            // ---- 7) Tandai sesi selesai ----
            DB::table('sesi_tes')->where('id', $this->sessionId)->update([
                'selesai'    => 1,
                'updated_at' => now(),
            ]);

            Log::info("Job GenerateAssessmentReport SELESAI untuk sesi {$this->sessionId}");
        } catch (\Throwable $e) {
            Log::error("GAGAL memproses GenerateAssessmentReport untuk sesi {$this->sessionId}: " . $e->getMessage());
            throw $e;
        }
    }
}
