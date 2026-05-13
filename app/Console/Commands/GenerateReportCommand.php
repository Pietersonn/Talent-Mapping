<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateAssessmentReport;
use App\Helpers\ScoringHelper;
use Illuminate\Support\Facades\DB;

class GenerateReportCommand extends Command
{
    /**
     * Nama perintah yang akan diketik di terminal
     * Contoh: php artisan report:generate 001
     */
    protected $signature = 'report:generate {session_id}';

    /**
     * Deskripsi perintah
     */
    protected $description = 'Generate ulang PDF dan kirim email untuk ID Sesi tertentu secara manual';

    /**
     * Eksekusi perintah
     */
    public function handle()
    {
        $sessionId = $this->argument('session_id');

        $this->info("==========================================");
        $this->info("Memulai proses untuk ID Sesi: {$sessionId}");
        $this->info("==========================================");

        // 1. Cek apakah sesi ada di database
        $session = DB::table('sesi_tes')->where('id', $sessionId)->first();

        if (!$session) {
            $this->error("❌ ERROR: Sesi dengan ID '{$sessionId}' tidak ditemukan di tabel sesi_tes!");
            return Command::FAILURE;
        }

        if (!$session->selesai) {
            $this->warn("⚠️ PERINGATAN: Sesi ini belum ditandai selesai (selesai = 0), tapi kita akan paksa proses...");
        }

        // 2. Kalkulasi Ulang Skor (Untuk memastikan data skor benar-benar ada)
        $this->line("⏳ Menghitung ulang skor dari jawaban peserta...");
        try {
            ScoringHelper::calculateAndSaveResults($sessionId);
            $this->info("✅ Skor berhasil dihitung & disimpan ke tabel hasil_tes.");
        } catch (\Throwable $e) {
            $this->error("❌ GAGAL menghitung skor: " . $e->getMessage());
            return Command::FAILURE;
        }

        // 3. Jalankan Job Generate PDF & Email secara Sinkron (Langsung di Terminal)
        $this->line("⏳ Membuat file PDF dan mencoba mengirim email (Mohon tunggu beberapa detik)...");
        try {
            // dispatchSync memaksa proses berjalan langsung di terminal saat ini juga tanpa masuk antrean (Queue)
            GenerateAssessmentReport::dispatchSync($sessionId);

            $this->info("✅ BERHASIL! PDF telah dibuat dan email telah diproses.");

            // Cek lokasi file
            $hasil = DB::table('hasil_tes')->where('id_sesi', $sessionId)->first();
            if ($hasil && $hasil->path_pdf) {
                $this->line("📁 Lokasi PDF: storage/app/public/" . $hasil->path_pdf);
            }

        } catch (\Throwable $e) {
            $this->error("❌ GAGAL memproses PDF/Email: " . $e->getMessage());
            $this->error("File error: " . $e->getFile() . " baris " . $e->getLine());
            return Command::FAILURE;
        }

        $this->info("==========================================");
        return Command::SUCCESS;
    }
}
