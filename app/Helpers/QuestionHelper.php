<?php

namespace App\Helpers;

use App\Models\TestSession;
use App\Models\ST30Response;

class QuestionHelper
{
    /**
     * Get selected question IDs from specific stages
     *
     * @param TestSession $session
     * @param array $stages - Array of stage numbers to get selections from
     * @return array - Array of question IDs
     */
    public static function getSelectedQuestionIds(TestSession $session, array $stages): array
    {
        $responses = ST30Response::where('session_id', $session->id)
            ->whereIn('stage_number', $stages)
            ->get();

        $selectedIds = [];

        foreach ($responses as $response) {
            $questionIds = $response->selected_items ?? []; // Tidak perlu json_decode
            $selectedIds = array_merge($selectedIds, $questionIds);
        }

        return array_unique($selectedIds);
    }

    /**
     * Get available questions count for a stage
     *
     * @param TestSession $session
     * @param int $stage
     * @return int
     */
    public static function getAvailableQuestionsCount(TestSession $session, int $stage): int
    {
        $excludeStages = match ($stage) {
            1 => [],
            2 => [1],
            3 => [1, 2],
            4 => [1, 2, 3],
            default => []
        };

        $excludedIds = self::getSelectedQuestionIds($session, $excludeStages);

        return 30 - count($excludedIds); // Total ST-30 questions is 30
    }

    /**
     * Validate stage selection constraints
     *
     * @param TestSession $session
     * @param int $stage
     * @param array $selectedQuestions
     * @return array - Validation result
     */
    public static function validateStageSelection(TestSession $session, int $stage, array $selectedQuestions): array
    {
        $validation = [
            'is_valid' => true,
            'errors' => []
        ];

        // Check selection count (5-7 required)
        $count = count($selectedQuestions);
        if ($count < 5 || $count > 7) {
            $validation['is_valid'] = false;
            $validation['errors'][] = 'Must select between 5-7 questions (selected: ' . $count . ')';
        }

        // Check for duplicates with previous stages
        if ($stage > 1) {
            $excludeStages = match ($stage) {
                2 => [1],
                3 => [1, 2],
                4 => [1, 2, 3],
                default => []
            };

            $alreadySelected = self::getSelectedQuestionIds($session, $excludeStages);
            $conflicts = array_intersect($selectedQuestions, $alreadySelected);

            if (!empty($conflicts)) {
                $validation['is_valid'] = false;
                $validation['errors'][] = 'Cannot select questions already chosen in previous stages: ' . implode(', ', $conflicts);
            }
        }

        return $validation;
    }

    /**
     * Get stage completion status
     *
     * @param TestSession $session
     * @return array
     */
    public static function getStageCompletionStatus(TestSession $session): array
    {
        $responses = ST30Response::where('session_id', $session->id)->get();

        $status = [
            'stage1' => false,
            'stage2' => false,
            'stage3' => false,
            'stage4' => false,
            'completed_stages' => 0
        ];

        foreach ($responses as $response) {
            $stageKey = 'stage' . $response->stage_number;
            if (isset($status[$stageKey])) {
                $status[$stageKey] = true;
                $status['completed_stages']++;
            }
        }

        return $status;
    }

    /**
     * Get detailed selection summary for a session
     *
     * @param TestSession $session
     * @return array
     */
    public static function getSelectionSummary(TestSession $session): array
    {
        $responses = ST30Response::where('session_id', $session->id)
            ->orderBy('stage_number')
            ->get();

        $summary = [
            'total_stages_completed' => $responses->count(),
            'scoring_stages_completed' => $responses->where('for_scoring', true)->count(),
            'stages' => []
        ];

        foreach ($responses as $response) {
            $questionIds = json_decode($response->selected_items, true) ?? [];

            $summary['stages'][$response->stage_number] = [
                'question_count' => count($questionIds),
                'for_scoring' => $response->for_scoring,
                'question_ids' => $questionIds,
                'completed_at' => $response->created_at
            ];
        }

        return $summary;
    }

    /**
     * Check if session is ready for next phase
     *
     * @param TestSession $session
     * @return bool
     */
    public static function isReadyForSJT(TestSession $session): bool
    {
        $completionStatus = self::getStageCompletionStatus($session);

        return $completionStatus['completed_stages'] >= 4;
    }

    /**
     * Get progress statistics
     *
     * @param TestSession $session
     * @return array
     */
    public static function getProgressStats(TestSession $session): array
    {
        $st30Status = self::getStageCompletionStatus($session);
        $sjtCount = \App\Models\SJTResponse::where('session_id', $session->id)->count();

        return [
            'st30' => [
                'completed_stages' => $st30Status['completed_stages'],
                'total_stages' => 4,
                'percentage' => ($st30Status['completed_stages'] / 4) * 100
            ],
            'sjt' => [
                'completed_questions' => $sjtCount,
                'total_questions' => 50,
                'percentage' => ($sjtCount / 50) * 100
            ],
            'overall' => [
                'percentage' => (($st30Status['completed_stages'] * 15) + ($sjtCount * 0.8))
            ]
        ];
    }
}
