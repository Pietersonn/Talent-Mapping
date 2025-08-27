<?php

namespace App\Helpers;

use App\Models\ST30Response;
use App\Models\SJTResponse;
use App\Models\ST30Question;
use App\Models\SJTOption;
use App\Models\TestSession;
use App\Models\TypologyDescription;
use App\Models\CompetencyDescription;
use Illuminate\Support\Collection;

class ScoringHelper
{
    /**
     * Calculate complete test results for a session
     */
    public static function calculateTestResults(TestSession $session): array
    {
        return [
            'st30_results' => self::calculateST30Results($session),
            'sjt_results' => self::calculateSJTResults($session),
            'session_info' => [
                'participant_name' => $session->participant_name,
                'completed_at' => $session->completed_at,
                'total_response_time' => self::calculateTotalResponseTime($session)
            ]
        ];
    }

    /**
     * Calculate ST-30 results based on scoring responses (Stage 1 & 3)
     */
    public static function calculateST30Results(TestSession $session): array
    {
        // Get scoring responses (stage 1 & 3 - positive selections)
        $scoringResponses = ST30Response::where('session_id', $session->id)
            ->where('for_scoring', true)
            ->get();

        if ($scoringResponses->isEmpty()) {
            return [
                'strengths' => [],
                'development_areas' => [],
                'error' => 'No scoring responses found'
            ];
        }

        // Collect all selected question IDs from scoring responses
        $selectedQuestionIds = [];
        foreach ($scoringResponses as $response) {
            $questionIds = json_decode($response->selected_items, true) ?? [];
            $selectedQuestionIds = array_merge($selectedQuestionIds, $questionIds);
        }

        // Get questions and count typology frequency
        $questions = ST30Question::whereIn('id', $selectedQuestionIds)->get();
        $typologyFrequency = [];

        foreach ($questions as $question) {
            $typologyCode = $question->typology_code;
            $typologyFrequency[$typologyCode] = ($typologyFrequency[$typologyCode] ?? 0) + 1;
        }

        // Sort by frequency (descending)
        arsort($typologyFrequency);

        // Get top 7 as strengths, bottom 7 as development areas
        $allTypologies = array_keys($typologyFrequency);
        $strengthCodes = array_slice($allTypologies, 0, 7);
        $developmentCodes = array_slice($allTypologies, -7);

        // Get typology descriptions
        $strengths = self::getTypologyDetails($strengthCodes, $typologyFrequency);
        $developmentAreas = self::getTypologyDetails($developmentCodes, $typologyFrequency);

        return [
            'strengths' => $strengths,
            'development_areas' => $developmentAreas,
            'dominant_typology' => $strengthCodes[0] ?? null,
            'typology_frequency' => $typologyFrequency,
            'total_responses' => count($selectedQuestionIds)
        ];
    }

    /**
     * Calculate SJT results based on competency scores
     */
    public static function calculateSJTResults(TestSession $session): array
    {
        // Get all SJT responses for this session
        $sjtResponses = SJTResponse::where('session_id', $session->id)->get();

        if ($sjtResponses->isEmpty()) {
            return [
                'competency_scores' => [],
                'strengths' => [],
                'development_areas' => [],
                'error' => 'No SJT responses found'
            ];
        }

        // Get selected options with their scores and competency targets
        $optionIds = $sjtResponses->pluck('selected_option_id');
        $selectedOptions = SJTOption::whereIn('id', $optionIds)->get();

        // Calculate scores per competency
        $competencyScores = [];
        $competencyQuestionCounts = [];

        foreach ($selectedOptions as $option) {
            $competency = $option->competency_target;
            $competencyScores[$competency] = ($competencyScores[$competency] ?? 0) + $option->score;
            $competencyQuestionCounts[$competency] = ($competencyQuestionCounts[$competency] ?? 0) + 1;
        }

        // Calculate average scores per competency
        $competencyAverages = [];
        foreach ($competencyScores as $competency => $totalScore) {
            $questionCount = $competencyQuestionCounts[$competency];
            $competencyAverages[$competency] = round($totalScore / $questionCount, 2);
        }

        // Sort by average score (descending)
        arsort($competencyAverages);

        // Get top 5 as strengths, bottom 5 as development areas
        $allCompetencies = array_keys($competencyAverages);
        $strengthCompetencies = array_slice($allCompetencies, 0, 5);
        $developmentCompetencies = array_slice($allCompetencies, -5);

        // Get competency descriptions
        $strengths = self::getCompetencyDetails($strengthCompetencies, $competencyAverages, $competencyScores);
        $developmentAreas = self::getCompetencyDetails($developmentCompetencies, $competencyAverages, $competencyScores);

        return [
            'competency_scores' => $competencyScores,
            'competency_averages' => $competencyAverages,
            'strengths' => $strengths,
            'development_areas' => $developmentAreas,
            'total_questions' => $sjtResponses->count(),
            'max_possible_score' => $sjtResponses->count() * 4 // Max score per question is 4
        ];
    }

    /**
     * Get typology details with descriptions
     */
    private static function getTypologyDetails(array $typologyCodes, array $frequency): array
    {
        $typologies = TypologyDescription::whereIn('typology_code', $typologyCodes)->get();

        $result = [];
        foreach ($typologyCodes as $code) {
            $typology = $typologies->firstWhere('typology_code', $code);

            $result[] = [
                'code' => $code,
                'name' => $typology->typology_name ?? 'Unknown',
                'frequency' => $frequency[$code] ?? 0,
                'strength_description' => $typology->strength_description ?? '',
                'weakness_description' => $typology->weakness_description ?? ''
            ];
        }

        return $result;
    }

    /**
     * Get competency details with descriptions
     */
    private static function getCompetencyDetails(array $competencyCodes, array $averages, array $totalScores): array
    {
        $competencies = CompetencyDescription::whereIn('competency_code', $competencyCodes)->get();

        $result = [];
        foreach ($competencyCodes as $code) {
            $competency = $competencies->firstWhere('competency_code', $code);

            $result[] = [
                'code' => $code,
                'name' => $competency->competency_name ?? 'Unknown',
                'average_score' => $averages[$code] ?? 0,
                'total_score' => $totalScores[$code] ?? 0,
                'strength_description' => $competency->strength_description ?? '',
                'weakness_description' => $competency->weakness_description ?? '',
                'improvement_activity' => $competency->improvement_activity ?? ''
            ];
        }

        return $result;
    }

    /**
     * Calculate total response time for session
     */
    private static function calculateTotalResponseTime(TestSession $session): int
    {
        $st30Time = ST30Response::where('session_id', $session->id)
            ->sum('response_time') ?? 0;

        // SJT doesn't have response_time in current structure, but we can add it later
        $sjtTime = 0;

        return $st30Time + $sjtTime;
    }

    /**
     * Generate summary interpretation
     */
    public static function generateSummary(array $results): array
    {
        $st30 = $results['st30_results'];
        $sjt = $results['sjt_results'];

        $summary = [];

        // ST-30 Summary
        if (!empty($st30['strengths'])) {
            $topStrength = $st30['strengths'][0];
            $summary['dominant_typology'] = [
                'code' => $topStrength['code'],
                'name' => $topStrength['name'],
                'description' => $topStrength['strength_description']
            ];
        }

        // SJT Summary
        if (!empty($sjt['strengths'])) {
            $topCompetency = $sjt['strengths'][0];
            $summary['top_competency'] = [
                'code' => $topCompetency['code'],
                'name' => $topCompetency['name'],
                'score' => $topCompetency['average_score']
            ];
        }

        // Overall performance
        $avgSjtScore = !empty($sjt['competency_averages']) ?
            array_sum($sjt['competency_averages']) / count($sjt['competency_averages']) : 0;

        $summary['overall_performance'] = [
            'st30_diversity' => count($st30['typology_frequency'] ?? []),
            'sjt_average_score' => round($avgSjtScore, 2),
            'completion_date' => $results['session_info']['completed_at']
        ];

        return $summary;
    }

    /**
     * Validate scoring data completeness
     */
    public static function validateScoringData(TestSession $session): array
    {
        $validation = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => []
        ];

        // Check ST-30 responses
        $st30Responses = ST30Response::where('session_id', $session->id)->get();
        if ($st30Responses->count() < 4) {
            $validation['errors'][] = 'Incomplete ST-30 responses (expected 4 stages)';
            $validation['is_valid'] = false;
        }

        // Check SJT responses
        $sjtResponses = SJTResponse::where('session_id', $session->id)->get();
        if ($sjtResponses->count() < 50) {
            $validation['errors'][] = 'Incomplete SJT responses (expected 50 questions)';
            $validation['is_valid'] = false;
        }

        // Check for scoring responses
        $scoringResponses = ST30Response::where('session_id', $session->id)
            ->where('for_scoring', true)
            ->get();

        if ($scoringResponses->isEmpty()) {
            $validation['errors'][] = 'No ST-30 scoring responses found';
            $validation['is_valid'] = false;
        }

        return $validation;
    }
}
