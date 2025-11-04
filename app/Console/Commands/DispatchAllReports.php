<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\GenerateAssessmentReport;

class DispatchAllReports extends Command
{
    protected $signature = 'reports:dispatch-all';
    protected $description = 'Kirim job GenerateAssessmentReport untuk semua session di test_results';

    public function handle()
    {
        // Ambil semua session_id unik dari tabel test_results
        $sessions = DB::table('test_results')->distinct()->pluck('session_id');

        $this->info("📊 Ditemukan {$sessions->count()} test results untuk diproses.");

        foreach ($sessions as $sessionId) {
            GenerateAssessmentReport::dispatch($sessionId);
            $this->info("🚀 Job dikirim untuk session: {$sessionId}");
        }

        $this->info("✅ Semua job GenerateAssessmentReport berhasil dikirim ke queue!");
    }
}
