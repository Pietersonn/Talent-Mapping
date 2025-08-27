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
    public $timestamps = false; // DISABLE TIMESTAMPS - table tidak punya created_at/updated_at

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
        $prefix = 'STR';  // 3 karakter

        $lastId = static::where('id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastId) {
            return $prefix . '01';  // STR01 = 5 karakter (fit di varchar(5))
        }

        $lastNumber = (int) substr($lastId->id, strlen($prefix));
        $newNumber = $lastNumber + 1;

        return $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);  // 2 digit = STR99 max
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
            ->whereIn('id', $this->selected_items)
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
            ->whereIn('id', $this->excluded_items)
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
     * Check if response includes a specific question number
     */
    public function hasQuestion(int $questionNumber): bool
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
            ->whereIn('id', $this->selected_items)
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
}
