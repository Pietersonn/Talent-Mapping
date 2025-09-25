<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $properties
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserId($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $competency_code
 * @property string $competency_name
 * @property string|null $strength_description
 * @property string|null $weakness_description
 * @property string|null $improvement_activity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $training_recommendations
 * @property-read string $display_name
 * @property-read int $questions_count
 * @property-read string $short_description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTQuestion> $sjtQuestions
 * @property-read int|null $sjt_questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription search(string $term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereCompetencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereCompetencyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereImprovementActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereStrengthDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereTrainingRecommendations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetencyDescription whereWeaknessDescription($value)
 */
	class CompetencyDescription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property string $event_code
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int|null $pic_id
 * @property bool $is_active
 * @property int|null $max_participants
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status_badge
 * @property-read string $status_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $participants
 * @property-read int|null $participants_count
 * @property-read \App\Models\User|null $pic
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event ongoing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereMaxParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $event_id
 * @property int $user_id
 * @property int $test_completed
 * @property int $results_sent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereResultsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereTestCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventParticipant whereUserId($value)
 */
	class EventParticipant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property int $version
 * @property string $type
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $display_name
 * @property-read int $questions_count
 * @property-read string $status_display
 * @property-read string $type_display
 * @property-read array $usage_stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTQuestion> $sjtQuestions
 * @property-read int|null $sjt_questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTResponse> $sjtResponses
 * @property-read int|null $sjt_responses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ST30Question> $st30Questions
 * @property-read int|null $st30_questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ST30Response> $st30Responses
 * @property-read int|null $st30_responses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion sJT()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion sT30()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionVersion whereVersion($value)
 */
	class QuestionVersion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property int $user_id
 * @property string $test_result_id
 * @property \Illuminate\Support\Carbon $request_date
 * @property string $status
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read string $status_badge_class
 * @property-read \App\Models\TestResult $testResult
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest recent($days = 7)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest rejected()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereTestResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResendRequest whereUserId($value)
 */
	class ResendRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $question_id
 * @property string $option_letter
 * @property string $option_text
 * @property int $score
 * @property string $competency_target
 * @property int $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read string $letter_display
 * @property-read string $option_preview
 * @property-read string $score_display
 * @property-read int $usage_count
 * @property-read \App\Models\SJTQuestion $sjtQuestion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption byLetter(string $letter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption byScore(int $score)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption highScore()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption lowScore()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereCompetencyTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereOptionLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereOptionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTOption whereUpdatedAt($value)
 */
	class SJTOption extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $version_id
 * @property int $number
 * @property string $question_text
 * @property string $competency
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTOption> $activeOptions
 * @property-read int|null $active_options_count
 * @property-read \App\Models\CompetencyDescription|null $competencyDescription
 * @property-read string $competency_code
 * @property-read string $competency_name
 * @property-read int|null $options_count
 * @property-read string $question_preview
 * @property-read int $usage_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTOption> $options
 * @property-read \App\Models\QuestionVersion $questionVersion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTResponse> $responses
 * @property-read int|null $responses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion byCompetency(string $competencyCode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion byNumberRange(int $start, int $end)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion byPage(int $pageNumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion byVersion(string $versionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereCompetency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereQuestionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTQuestion whereVersionId($value)
 */
	class SJTQuestion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $session_id
 * @property string $question_id
 * @property string $question_version_id
 * @property int $page_number
 * @property string $selected_option
 * @property int|null $response_time
 * @property-read \App\Models\SJTQuestion|null $question
 * @property-read \App\Models\QuestionVersion $questionVersion
 * @property-read \App\Models\TestSession $session
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse forQuestion(string $questionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse forSession(string $sessionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse forSessionAndQuestion(string $sessionId, string $questionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse wherePageNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse whereQuestionVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse whereResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse whereSelectedOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SJTResponse whereSessionId($value)
 */
	class SJTResponse extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $version_id
 * @property int $number
 * @property string $statement
 * @property string $typology_code
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ST30Response> $excludedInResponses
 * @property-read int|null $excluded_in_responses_count
 * @property-read int $excluded_count
 * @property-read float $popularity_score
 * @property-read int $selected_count
 * @property-read float $selection_ratio
 * @property-read string $statement_preview
 * @property-read string $typology_name
 * @property-read int $usage_count
 * @property-read \App\Models\QuestionVersion $questionVersion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ST30Response> $selectedInResponses
 * @property-read int|null $selected_in_responses_count
 * @property-read \App\Models\TypologyDescription|null $typologyDescription
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question byNumberRange(int $a, int $b)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question byTypology(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question byVersion(string $versionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereStatement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereTypologyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Question whereVersionId($value)
 */
	class ST30Question extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $session_id
 * @property string $question_version_id
 * @property int $stage_number
 * @property array<array-key, mixed> $selected_items
 * @property array<array-key, mixed>|null $excluded_items
 * @property bool $for_scoring
 * @property int|null $response_time
 * @property-read int $excluded_count
 * @property-read int $selected_count
 * @property-read \App\Models\QuestionVersion $questionVersion
 * @property-read \App\Models\TestSession $testSession
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response bySession(string $sessionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response byStage(int $stage)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response byVersion(string $versionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response forScoring()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereExcludedItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereForScoring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereQuestionVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereSelectedItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ST30Response whereStageNumber($value)
 */
	class ST30Response extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $session_id
 * @property array<array-key, mixed>|null $st30_results
 * @property array<array-key, mixed>|null $sjt_results
 * @property string|null $dominant_typology
 * @property \Illuminate\Support\Carbon|null $report_generated_at
 * @property \Illuminate\Support\Carbon|null $email_sent_at
 * @property string|null $pdf_path
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\TypologyDescription|null $dominantTypologyDescription
 * @property-read array $sjt_development_areas
 * @property-read array $sjt_strengths
 * @property-read array $st30_development_areas
 * @property-read array $st30_strengths
 * @property-read array $summary
 * @property-read \App\Models\TestSession $testSession
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult byTypology(string $typologyCode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult emailPending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult emailSent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereDominantTypology($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereEmailSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult wherePdfPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereReportGeneratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereSjtResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereSt30Results($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestResult withPdf()
 */
	class TestResult extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property int $user_id
 * @property string|null $event_id
 * @property string $session_token
 * @property string $current_step
 * @property string|null $st30_version_id
 * @property string|null $participant_name
 * @property string|null $participant_background
 * @property bool $is_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read array $completion_stats
 * @property-read array $detailed_progress
 * @property-read string $progress_display
 * @property-read float $progress_percentage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SJTResponse> $sjtResponses
 * @property-read int|null $sjt_responses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ST30Response> $st30Responses
 * @property-read int|null $st30_responses_count
 * @property-read \App\Models\TestResult|null $testResult
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession inPhase($phase)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereCurrentStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereParticipantBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereParticipantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereSessionToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereSt30VersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestSession whereUserId($value)
 */
	class TestSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $typology_code
 * @property string $typology_name
 * @property string|null $strength_description
 * @property string|null $weakness_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $career_suggestions
 * @property-read string|null $characteristics
 * @property-read string $color_class
 * @property-read string $description
 * @property-read string $display_name
 * @property-read bool $is_active
 * @property-read int $questions_count
 * @property-read string $short_description
 * @property-read string|null $strengths
 * @property-read string|null $weaknesses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ST30Question> $st30Questions
 * @property-read int|null $st30_questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription search(string $term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereStrengthDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereTypologyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereTypologyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypologyDescription whereWeaknessDescription($value)
 */
	class TypologyDescription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $google_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property bool $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $role_display
 * @property-read string $status_display
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User admins()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole(string $role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User pICs()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User staff()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User users()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

