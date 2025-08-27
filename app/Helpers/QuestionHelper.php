<?php

namespace App\Helpers;

use App\Models\ST30Question;
use App\Models\SJTQuestion;
use App\Models\ST30Response;
use App\Models\TestSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuestionHelper
{
    /**
     * Get available ST-30 questions for a specific stage
     */
    public static function getAvailableQuestionsForStage(TestSession $session, int $stage): Collection
    {
        // Get all active ST-30 questions for the current version
        $allQuestions = ST30Question::where('is_active', true)
            ->orderBy('number')
            ->get();

        switch ($stage) {
            case 1:
                // Stage 1: All 30 questions available
                return $allQuestions;

            case 2:
                // Stage 2: Questions NOT selected in Stage 1
                $stage1Selected = self::getSelectedQuestionIds($session, 1);
                return $allQuestions->whereNotIn('id', $stage1Selected);

            case 3:
                // Stage 3: Questions NOT selected in Stage 1 AND Stage 2
                $stage1Selected = self::getSelectedQuestionIds($session, 1);
                $stage2Selected = self::getSelectedQuestionIds($session, 2);
                $excludedIds = array_merge($stage1Selected, $stage2Selected);
                return $allQuestions->whereNotIn('id', $excludedIds);

            case 4:
                // Stage 4: Questions NOT selected in Stage 1, 2, AND 3
                $stage1Selected = self::getSelectedQuestionIds($session, 1);
                $stage2Selected = self::getSelectedQuestionIds($session, 2);
                $stage3Selected = self::getSelectedQuestionIds($session, 3);
                $excludedIds = array_merge($stage1Selected, $stage2Selected, $stage3Selected);
                return $allQuestions->whereNotIn('id', $excludedIds);

            default:
                return collect();
        }
    }

    /**
     * Get selected question IDs from specific stages
     */
    public static function getSelectedQuestionIds(TestSession $session, int|array $stages): array
    {
        $stages = is_array($stages) ? $stages : [$stages];

        $responses = ST30Response::where('session_id', $session->id)
            ->whereIn('stage_number', $stages)
            ->get();

        $questionIds = [];
        foreach ($responses as $response) {
            $selected = json_decode($response->selected_items, true) ?? [];
            $questionIds = array_merge($questionIds, $selected);
        }

        return array_unique($questionIds);
    }

    /**
     * Get excluded (selected) question IDs from multiple stages
     */
    public static function getExcludedQuestionIds(TestSession $session, array $stages): array
    {
        return self::getSelectedQuestionIds($session, $stages);
    }

    /**
     * Get SJT questions for a specific page
     */
    public static function getSJTQuestionsForPage(int $page, ?string $versionId = null): Collection
    {
        $startNumber = (($page - 1) * 10) + 1;
        $endNumber = $page * 10;

        $query = SJTQuestion::whereBetween('number', [$startNumber, $endNumber])
            ->with(['options' => function($query) {
                $query->orderBy('option_letter');
            }])
            ->orderBy('number');

        if ($versionId) {
            $query->where('version_id', $versionId);
        }

        return $query->get();
    }

    /**
     * Validate ST-30 stage progression
     */
    public static function validateStageProgression(TestSession $session, int $requestedStage): array
    {
        $validation = [
            'is_valid' => true,
            'current_allowed_stage' => 1,
            'completed_stages' => [],
            'message' => ''
        ];

        // Get all completed ST-30 responses for this session
        $completedStages = ST30Response::where('session_id', $session->id)
            ->pluck('stage_number')
            ->sort()
            ->values()
            ->toArray();

        $validation['completed_stages'] = $completedStages;

        if (empty($completedStages)) {
            // No stages completed, only stage 1 is allowed
            $validation['current_allowed_stage'] = 1;
        } else {
            // Next allowed stage is the highest completed + 1
            $validation['current_allowed_stage'] = max($completedStages) + 1;
        }

        // Check if requested stage is valid
        if ($requestedStage > $validation['current_allowed_stage']) {
            $validation['is_valid'] = false;
            $validation['message'] = "You must complete stage {$validation['current_allowed_stage']} first.";
        } elseif ($requestedStage < $validation['current_allowed_stage'] && !in_array($requestedStage, $completedStages)) {
            $validation['is_valid'] = false;
            $validation['message'] = "Invalid stage progression.";
        }

        return $validation;
    }

    /**
     * Get question statistics for a session
     */
    public static function getSessionQuestionStats(TestSession $session): array
    {
        $st30Responses = ST30Response::where('session_id', $session->id)->get();
        $totalSt30Questions = 0;

        foreach ($st30Responses as $response) {
            $selected = json_decode($response->selected_items, true) ?? [];
            $totalSt30Questions += count($selected);
        }

        $sjtResponsesCount = DB::table('sjt_responses')
            ->where('session_id', $session->id)
            ->count();

        return [
            'st30_total_selections' => $totalSt30Questions,
            'st30_stages_completed' => $st30Responses->count(),
            'sjt_questions_answered' => $sjtResponsesCount,
            'sjt_pages_completed' => ceil($sjtResponsesCount / 10),
            'is_st30_complete' => $st30Responses->count() >= 4,
            'is_sjt_complete' => $sjtResponsesCount >= 50,
            'is_fully_complete' => $st30Responses->count() >= 4 && $sjtResponsesCount >= 50
        ];
    }

    /**
     * Get typology distribution for session responses
     */
    public static function getTypologyDistribution(TestSession $session): array
    {
        $scoringResponses = ST30Response::where('session_id', $session->id)
            ->where('for_scoring', true)
            ->get();

        $distribution = [];

        foreach ($scoringResponses as $response) {
            $questionIds = json_decode($response->selected_items, true) ?? [];
            $questions = ST30Question::whereIn('id', $questionIds)->get();

            foreach ($questions as $question) {
                $code = $question->typology_code;
                $distribution[$code] = ($distribution[$code] ?? 0) + 1;
            }
        }

        arsort($distribution);
        return $distribution;
    }

    /**
     * Check if all required questions are answered for SJT page
     */
    public static function validateSJTPageCompletion(array $responses, int $expectedCount = 10): array
    {
        $validation = [
            'is_complete' => false,
            'answered_count' => count($responses),
            'expected_count' => $expectedCount,
            'missing_questions' => []
        ];

        $validation['is_complete'] = ($validation['answered_count'] === $expectedCount);

        if (!$validation['is_complete']) {
            // Find which questions are missing (this would need question IDs to be more specific)
            $validation['missing_questions'] = range(1, $expectedCount - $validation['answered_count']);
        }

        return $validation;
    }

    /**
     * Get summary of test progress
     */
    public static function getTestProgress(TestSession $session): array
    {
        $stats = self::getSessionQuestionStats($session);

        $progress = [
            'overall_percentage' => 0,
            'st30_percentage' => 0,
            'sjt_percentage' => 0,
            'current_stage' => $session->current_step,
            'is_complete' => $session->is_completed
        ];

        // Calculate ST-30 progress (4 stages = 100%)
        $progress['st30_percentage'] = min(100, ($stats['st30_stages_completed'] / 4) * 100);

        // Calculate SJT progress (50 questions = 100%)
        $progress['sjt_percentage'] = min(100, ($stats['sjt_questions_answered'] / 50) * 100);

        // Overall progress (ST-30 is 60%, SJT is 40% of total test)
        $progress['overall_percentage'] =
            ($progress['st30_percentage'] * 0.6) +
            ($progress['sjt_percentage'] * 0.4);

        return $progress;
    }
}
