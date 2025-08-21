<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class ST30Response extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'st30_responses';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'session_id',
        'question_version_id',
        'stage_number',
        'selected_items',
        'excluded_items',
        'for_scoring',
        'response_time'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'selected_items' => 'array',
        'excluded_items' => 'array',
        'for_scoring' => 'boolean',
        'stage_number' => 'integer',
        'response_time' => 'integer',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'STR';

    /**
     * Generate custom ID
     */
    public function generateCustomId(): string
    {
        $lastId = static::where('id', 'like', $this->customIdPrefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastId) {
            return $this->customIdPrefix . '01';
        }

        $lastNumber = (int) substr($lastId->id, strlen($this->customIdPrefix));
        $newNumber = $lastNumber + 1;

        return $this->customIdPrefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Test session this response belongs to
     */
    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    /**
     * Question version used for this response
     */
    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'question_version_id');
    }

    /**
     * Get selected ST30 questions
     */
    public function selectedQuestions(): Collection
    {
        if (empty($this->selected_items)) {
            return collect();
        }

        return ST30Question::where('version_id', $this->question_version_id)
            ->whereIn('number', $this->selected_items)
            ->orderBy('number')
            ->get();
    }

    /**
     * Get excluded ST30 questions
     */
    public function excludedQuestions(): Collection
    {
        if (empty($this->excluded_items)) {
            return collect();
        }

        return ST30Question::where('version_id', $this->question_version_id)
            ->whereIn('number', $this->excluded_items)
            ->orderBy('number')
            ->get();
    }

    /**
     * Get count of selected items
     */
    public function getSelectedCountAttribute(): int
    {
        return count($this->selected_items ?? []);
    }

    /**
     * Get count of excluded items
     */
    public function getExcludedCountAttribute(): int
    {
        return count($this->excluded_items ?? []);
    }

    /**
     * Check if this response contains a specific question number
     */
    public function containsQuestion(int $questionNumber): bool
    {
        $selected = $this->selected_items ?? [];
        $excluded = $this->excluded_items ?? [];

        return in_array($questionNumber, $selected) || in_array($questionNumber, $excluded);
    }

    /**
     * Check if a question number is selected
     */
    public function hasSelectedQuestion(int $questionNumber): bool
    {
        $selected = $this->selected_items ?? [];
        return in_array($questionNumber, $selected);
    }

    /**
     * Check if a question number is excluded
     */
    public function hasExcludedQuestion(int $questionNumber): bool
    {
        $excluded = $this->excluded_items ?? [];
        return in_array($questionNumber, $excluded);
    }

    /**
     * Get typology distribution from selected items
     */
    public function getTypologyDistribution(): array
    {
        if (empty($this->selected_items)) {
            return [];
        }

        $questions = ST30Question::where('version_id', $this->question_version_id)
            ->whereIn('number', $this->selected_items)
            ->get();

        return $questions->groupBy('typology_code')
            ->map(function ($items) {
                return $items->count();
            })
            ->toArray();
    }

    /**
     * Get dominant typology from selected items
     */
    public function getDominantTypology(): ?string
    {
        $distribution = $this->getTypologyDistribution();

        if (empty($distribution)) {
            return null;
        }

        $maxCount = max($distribution);

        return array_key_first(
            array_filter($distribution, function($count) use ($maxCount) {
                return $count === $maxCount;
            })
        );
    }

    /**
     * Scope for responses by session
     */
    public function scopeBySession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for responses by stage
     */
    public function scopeByStage($query, int $stage)
    {
        return $query->where('stage_number', $stage);
    }

    /**
     * Scope for scoring responses only
     */
    public function scopeForScoring($query)
    {
        return $query->where('for_scoring', true);
    }

    /**
     * Scope for responses by version
     */
    public function scopeByVersion($query, string $versionId)
    {
        return $query->where('question_version_id', $versionId);
    }

    /**
     * Get all responses for a specific session
     */
    public static function getSessionResponses(string $sessionId): Collection
    {
        return static::where('session_id', $sessionId)
            ->orderBy('stage_number')
            ->get();
    }

    /**
     * Get scoring responses for a session
     */
    public static function getScoringResponses(string $sessionId): Collection
    {
        return static::where('session_id', $sessionId)
            ->where('for_scoring', true)
            ->orderBy('stage_number')
            ->get();
    }

    /**
     * Calculate total response time for session
     */
    public static function getTotalResponseTime(string $sessionId): int
    {
        return static::where('session_id', $sessionId)
            ->sum('response_time') ?? 0;
    }

    /**
     * Get average response time per stage
     */
    public static function getAverageResponseTimePerStage(string $sessionId): array
    {
        return static::where('session_id', $sessionId)
            ->selectRaw('stage_number, AVG(response_time) as avg_time')
            ->groupBy('stage_number')
            ->pluck('avg_time', 'stage_number')
            ->toArray();
    }

    /**
     * Validate response data
     */
    public function validateResponseData(): array
    {
        $errors = [];

        // Check if stage number is valid (1-4)
        if (!in_array($this->stage_number, [1, 2, 3, 4])) {
            $errors[] = 'Invalid stage number. Must be between 1 and 4.';
        }

        // Check if selected items count is appropriate for stage
        $selectedCount = $this->selected_count;
        $expectedCounts = [
            1 => [5, 6, 7], // Stage 1: 5-7 items
            2 => [5, 6, 7], // Stage 2: 5-7 items
            3 => [5, 6, 7], // Stage 3: 5-7 items
            4 => [5, 6, 7], // Stage 4: 5-7 items
        ];

        if (isset($expectedCounts[$this->stage_number])) {
            if (!in_array($selectedCount, $expectedCounts[$this->stage_number])) {
                $errors[] = "Stage {$this->stage_number} should have 5-7 selected items, got {$selectedCount}.";
            }
        }

        // Check if question numbers are valid (1-30)
        $allItems = array_merge($this->selected_items ?? [], $this->excluded_items ?? []);
        foreach ($allItems as $item) {
            if (!is_numeric($item) || $item < 1 || $item > 30) {
                $errors[] = "Invalid question number: {$item}. Must be between 1 and 30.";
            }
        }

        // Check for duplicates between selected and excluded
        $duplicates = array_intersect($this->selected_items ?? [], $this->excluded_items ?? []);
        if (!empty($duplicates)) {
            $errors[] = "Question numbers cannot be both selected and excluded: " . implode(', ', $duplicates);
        }

        return $errors;
    }

    /**
     * Check if response is complete
     */
    public function isComplete(): bool
    {
        return !empty($this->selected_items) &&
               count($this->selected_items) >= 5 &&
               count($this->selected_items) <= 7;
    }
}
