<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Helpers\ScoringHelper;

class RecalculateAllResults extends Command
{
    protected $signature = 'scoring:recalculate-all';
    protected $description = 'Hitung ulang semua test session dan simpan ke test_results';

    public function handle()
    {
        $sessions = DB::table('test_sessions')->pluck('id');
        $this->info("Ditemukan {$sessions->count()} test sessions.");

        foreach ($sessions as $sessionId) {
            try {
                $this->info("🔄 Memproses session: {$sessionId}");
                ScoringHelper::calculateAndSaveResults($sessionId);
                $this->info("✅ Selesai untuk {$sessionId}");
            } catch (\Throwable $e) {
                $this->error("❌ Gagal {$sessionId}: {$e->getMessage()}");
            }
        }

        $this->info("🎉 Semua session selesai diproses!");
    }
}
