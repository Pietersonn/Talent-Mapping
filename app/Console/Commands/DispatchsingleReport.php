<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateAssessmentReport;

class DispatchSingleReport extends Command
{
    protected $signature = 'reports:dispatch-one {session_id}';
    protected $description = 'Dispatch GenerateAssessmentReport job untuk satu session ID';

    public function handle()
    {
        $sessionId = $this->argument('session_id');

        $this->info("🚀 Mengirim job GenerateAssessmentReport untuk session {$sessionId}...");
        GenerateAssessmentReport::dispatch($sessionId);
        $this->info("✅ Job berhasil dikirim ke queue!");
    }
}
