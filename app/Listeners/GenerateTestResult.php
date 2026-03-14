<?php

namespace App\Listeners;

use App\Events\TestCompleted;
use App\Helpers\ScoringHelper;
use App\Models\TestResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateTestResult implements ShouldQueue

{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TestCompleted $event): void
    {
        $session = $event->session;
        $result = $event->result;

        try {
            Log::info("Generating test results for session: {$session->id}");

            // Validate scoring data
            $validation = ScoringHelper::validateScoringData($session);

            if (!$validation['is_valid']) {
                Log::error("Invalid scoring data for session {$session->id}", $validation['errors']);
                return;
            }

            // Calculate results using ScoringHelper
            $calculatedResults = ScoringHelper::calculateTestResults($session);

            // Update the TestResult with calculated data
            $result->update([
                'st30_results' => $calculatedResults['st30_results'],
                'sjt_results' => $calculatedResults['sjt_results'],
                'dominant_typology' => $calculatedResults['st30_results']['dominant_typology'] ?? null,
                'report_generated_at' => now(),
            ]);

            Log::info("Test results generated successfully for session: {$session->id}");

            // TODO: Trigger PDF generation and email sending
            // This can be separate jobs/events for better performance

        } catch (\Exception $e) {
            Log::error("Failed to generate test results for session {$session->id}: " . $e->getMessage());

            // Mark the job as failed, but don't throw to prevent infinite retries
            $this->fail($e);
        }
    }
}
