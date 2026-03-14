<?php
namespace App\Jobs;

use App\Helpers\QuestionHelper;
use App\Models\CompetencyDescription;
use App\Models\TestResult;
use App\Models\TestSession;
use App\Models\TypologyDescription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public readonly string $sessionId)
    {}

    public function handle(): void
    {
        $session = TestSession::with(['user', 'Program'])->find($this->sessionId);

        if (!$session) {
            Log::warning("GenerateAssessmentReport: sesi {$this->sessionId} tidak ditemukan.");
            return;
        }

        if (!$session->selesai) {
            Log::warning("GenerateAssessmentReport: sesi {$this->sessionId} belum selesai.");
            return;
        }

        // ── Hitung hasil ──────────────────────────────────────────────────────
        $st30Scores = QuestionHelper::calculateST30Scores($session->id);
        $tkPayload  = QuestionHelper::buildTKResultPayload($session->id);

        $dominantTypology = array_key_first($st30Scores);

        // ── Ambil deskripsi ───────────────────────────────────────────────────
        $typologyDesc  = TypologyDescription::where('kode_tipologi', $dominantTypology)->first();
        $competencyDescs = CompetencyDescription::whereIn('kode_kompetensi', array_keys($tkPayload['all'] ? array_column($tkPayload['all'], null, 'code') : []))->get()->keyBy('kode_kompetensi');

        // ── Simpan / update TestResult ────────────────────────────────────────
        $testResult = TestResult::updateOrCreate(
            ['id_sesi' => $session->id],
            [
                'hasil_st30'         => $st30Scores,
                'hasil_tk'           => $tkPayload,
                'tipologi_dominan'   => $dominantTypology,
                'laporan_dibuat_pada'=> now(),
            ]
        );

        // ── Generate PDF ──────────────────────────────────────────────────────
        $pdfData = [
            'session'          => $session,
            'user'             => $session->user,
            'Program'            => $session->Program,
            'st30Scores'       => $st30Scores,
            'tkPayload'        => $tkPayload,
            'dominantTypology' => $dominantTypology,
            'typologyDesc'     => $typologyDesc,
            'competencyDescs'  => $competencyDescs,
        ];

        $pdf = Pdf::loadView('pdf.assessment-report', $pdfData)->setPaper('a4', 'portrait');

        // Simpan ke disk local (bukan public — tidak perlu symlink)
        $filename  = 'reports/'.date('Y/m').'/'.$session->id.'.pdf';
        Storage::disk('local')->put($filename, $pdf->output());

        // Update path_pdf
        $testResult->update(['path_pdf' => $filename]);

        // ── Kirim email ───────────────────────────────────────────────────────
        $user = $session->user;
        if ($user && $user->email && empty($testResult->email_terkirim_pada)) {
            try {
                $pdfPath = Storage::disk('local')->path($filename);
                Mail::raw(
                    "Halo {$user->nama},\n\nTerima kasih telah menyelesaikan Talent Assessment.\nBerikut kami lampirkan hasil assessment Anda.\n\nSalam,\nTim BCTI",
                    function ($m) use ($user, $pdfPath, $session) {
                        $m->to($user->email, $user->nama)
                          ->subject('Hasil Talent Assessment - '.($session->Program?->nama ?? 'BCTI'))
                          ->attach($pdfPath, ['as' => 'hasil-asesmen.pdf', 'mime' => 'application/pdf']);
                    }
                );
                $testResult->update(['email_terkirim_pada' => now()]);

                // Update pivot hasil_terkirim
                if ($session->Program) {
                    $session->Program->participants()->updateExistingPivot($session->id_pengguna, ['hasil_terkirim' => true]);
                }
            } catch (\Exception $e) {
                Log::error("GenerateAssessmentReport: gagal kirim email ke {$user->email} — ".$e->getMessage());
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("GenerateAssessmentReport GAGAL untuk sesi {$this->sessionId}: ".$exception->getMessage());
    }
}
