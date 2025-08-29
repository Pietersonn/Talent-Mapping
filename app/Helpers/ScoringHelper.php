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
     * Calculate ST-30 results based on Stage 1 (strengths) and Stage 2 (development areas)
     * FIXED: Now uses Stage 1 & 2 instead of Stage 1 & 3
     */
    public static function calculateST30Results(TestSession $session): array
    {
        // Get Stage 1 responses (STRENGTHS)
        $strengthsResponse = ST30Response::where('session_id', $session->id)
            ->where('stage_number', 1)
            ->where('for_scoring', true)
            ->first();

        // Get Stage 2 responses (DEVELOPMENT AREAS)
        $developmentResponse = ST30Response::where('session_id', $session->id)
            ->where('stage_number', 2)
            ->where('for_scoring', true)
            ->first();

        if (!$strengthsResponse || !$developmentResponse) {
            return [
                'strengths' => [],
                'development_areas' => [],
                'error' => 'Missing scoring responses for Stage 1 or Stage 2'
            ];
        }

        // Get selected question IDs for strengths (Stage 1)
        $strengthQuestionIds = json_decode($strengthsResponse->selected_items, true) ?? [];

        // Get selected question IDs for development areas (Stage 2)
        $developmentQuestionIds = json_decode($developmentResponse->selected_items, true) ?? [];

        // Get questions and map to typologies
        $strengthQuestions = ST30Question::whereIn('id', $strengthQuestionIds)->get();
        $developmentQuestions = ST30Question::whereIn('id', $developmentQuestionIds)->get();

        // Get typology codes
        $strengthTypologyCodes = $strengthQuestions->pluck('typology_code')->unique()->toArray();
        $developmentTypologyCodes = $developmentQuestions->pluck('typology_code')->unique()->toArray();

        // Get typology descriptions
        $strengths = self::getTypologyDetails($strengthTypologyCodes, 'strength');
        $developmentAreas = self::getTypologyDetails($developmentTypologyCodes, 'weakness');

        return [
            'strengths' => $strengths,
            'development_areas' => $developmentAreas,
            'dominant_typology' => $strengthTypologyCodes[0] ?? null,
            'typology_frequency' => [
                'strengths' => array_count_values($strengthQuestions->pluck('typology_code')->toArray()),
                'development' => array_count_values($developmentQuestions->pluck('typology_code')->toArray())
            ]
        ];
    }

    /**
     * Calculate SJT results with competency scoring
     */
    public static function calculateSJTResults(TestSession $session): array
    {
        // Get all SJT responses for this session
        $responses = SJTResponse::where('session_id', $session->id)->get();

        if ($responses->isEmpty()) {
            return [
                'competency_scores' => [],
                'top_competencies' => [],
                'bottom_competencies' => [],
                'error' => 'No SJT responses found'
            ];
        }

        // Calculate competency scores
        $competencyScores = [];

        foreach ($responses as $response) {
            // Get the option and its score
            $option = SJTOption::where('question_id', $response->question_id)
                ->where('option_letter', $response->selected_option)
                ->first();

            if ($option) {
                $competency = $option->competency_target;
                if (!isset($competencyScores[$competency])) {
                    $competencyScores[$competency] = [];
                }
                $competencyScores[$competency][] = $option->score;
            }
        }

        // Calculate averages and scale to 0-20
        $competencyAverages = [];
        foreach ($competencyScores as $competency => $scores) {
            $average = array_sum($scores) / count($scores);
            $competencyAverages[$competency] = round($average * 5, 1); // Scale 0-4 to 0-20
        }

        // Sort competencies by score
        arsort($competencyAverages);

        // Get top 3 and bottom 3
        $sortedCompetencies = array_keys($competencyAverages);
        $topCompetencies = array_slice($sortedCompetencies, 0, 3);
        $bottomCompetencies = array_slice($sortedCompetencies, -3);

        // Get competency details
        $topDetails = self::getCompetencyDetails($topCompetencies, $competencyAverages, 'strength');
        $bottomDetails = self::getCompetencyDetails($bottomCompetencies, $competencyAverages, 'weakness');

        return [
            'competency_scores' => $competencyAverages,
            'top_competencies' => $topDetails,
            'bottom_competencies' => $bottomDetails,
            'competency_averages' => $competencyAverages
        ];
    }

    /**
     * Get typology details with descriptions
     * FIXED: Added description_type parameter to get correct descriptions
     */
    private static function getTypologyDetails(array $typologyCodes, string $descriptionType = 'strength'): array
    {
        $typologies = TypologyDescription::whereIn('typology_code', $typologyCodes)->get();
        $details = [];

        foreach ($typologies as $typology) {
            $details[] = [
                'code' => $typology->typology_code,
                'name' => $typology->typology_name,
                'description' => $descriptionType === 'strength'
                    ? $typology->strength_description
                    : $typology->weakness_description
            ];
        }

        return $details;
    }

    /**
     * Get competency details with scores and descriptions
     */
    private static function getCompetencyDetails(array $competencyCodes, array $scores, string $descriptionType = 'strength'): array
    {
        $competencies = CompetencyDescription::whereIn('competency_code', $competencyCodes)->get();
        $details = [];

        foreach ($competencies as $competency) {
            $details[] = [
                'code' => $competency->competency_code,
                'name' => $competency->competency_name,
                'score' => $scores[$competency->competency_code] ?? 0,
                'description' => $descriptionType === 'strength'
                    ? $competency->strength_description
                    : $competency->weakness_description,
                'improvement_activity' => $competency->improvement_activity,
                'training_recommendations' => $competency->training_recommendations
            ];
        }

        return $details;
    }

    /**
     * Calculate total response time
     */
    private static function calculateTotalResponseTime(TestSession $session): ?int
    {
        $st30Time = ST30Response::where('session_id', $session->id)
            ->whereNotNull('response_time')
            ->sum('response_time');

        $sjtTime = SJTResponse::where('session_id', $session->id)
            ->whereNotNull('response_time')
            ->sum('response_time');

        return ($st30Time + $sjtTime) ?: null;
    }

    /**
     * Get comprehensive result summary
     */
    public static function getResultSummary(TestSession $session): array
    {
        $results = self::calculateTestResults($session);
        $st30 = $results['st30_results'];
        $sjt = $results['sjt_results'];

        $summary = [
            'participant' => $results['session_info']['participant_name'],
            'completed_at' => $results['session_info']['completed_at'],
        ];

        // ST-30 Summary
        $summary['st30_summary'] = [
            'dominant_typology' => $st30['dominant_typology'],
            'total_strengths' => count($st30['strengths']),
            'total_development_areas' => count($st30['development_areas'])
        ];

        // SJT Summary
        $summary['sjt_summary'] = [
            'highest_competency' => $sjt['top_competencies'][0] ?? null,
            'lowest_competency' => end($sjt['bottom_competencies']) ?? null,
            'average_score' => !empty($sjt['competency_averages']) ?
                array_sum($sjt['competency_averages']) / count($sjt['competency_averages']) : 0
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

        // Check ST-30 responses (need 4 stages)
        $st30Responses = ST30Response::where('session_id', $session->id)->get();
        if ($st30Responses->count() < 4) {
            $validation['errors'][] = 'Incomplete ST-30 responses (expected 4 stages)';
            $validation['is_valid'] = false;
        }

        // Check specific scoring responses (Stage 1 & 2)
        $scoringResponses = ST30Response::where('session_id', $session->id)
            ->where('for_scoring', true)
            ->get();

        if ($scoringResponses->count() < 2) {
            $validation['errors'][] = 'Missing ST-30 scoring responses (need Stage 1 & 2)';
            $validation['is_valid'] = false;
        }

        // Check SJT responses (need 50 questions)
        $sjtResponses = SJTResponse::where('session_id', $session->id)->get();
        if ($sjtResponses->count() < 50) {
            $validation['errors'][] = 'Incomplete SJT responses (expected 50 questions)';
            $validation['is_valid'] = false;
        }

        return $validation;
    }
}
