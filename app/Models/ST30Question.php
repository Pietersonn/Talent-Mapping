<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ST30Question extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'st30_questions';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'version_id',
        'number',
        'statement',
        'typology_code',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'number' => 'integer',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'ST';

    /**
     * Generate custom ID
     */
    public function generateCustomId(): string
    {
        $lastId = static::where('id', 'like', $this->customIdPrefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastId) {
            return $this->customIdPrefix . '001';
        }

        $lastNumber = (int) substr($lastId->id, strlen($this->customIdPrefix));
        $newNumber = $lastNumber + 1;

        return $this->customIdPrefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Question version this question belongs to
     */
    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'version_id');
    }

    /**
     * Typology description for this question
     */
    public function typologyDescription(): BelongsTo
    {
        return $this->belongsTo(TypologyDescription::class, 'typology_code', 'typology_code');
    }

    /**
     * Get responses where this question was selected
     */
    public function selectedInResponses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'question_version_id', 'version_id')
            ->whereJsonContains('selected_items', $this->number);
    }

    /**
     * Get responses where this question was excluded
     */
    public function excludedInResponses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'question_version_id', 'version_id')
            ->whereJsonContains('excluded_items', $this->number);
    }

    /**
     * Get all responses that include this question (selected or excluded)
     */
    public function allResponses()
    {
        return ST30Response::where('question_version_id', $this->version_id)
            ->where(function($query) {
                $query->whereJsonContains('selected_items', $this->number)
                      ->orWhereJsonContains('excluded_items', $this->number);
            });
    }

    /**
     * Get typology name
     */
    public function getTypologyNameAttribute(): string
    {
        return $this->typologyDescription?->typology_name ?? $this->typology_code;
    }

    /**
     * Get statement preview (truncated)
     */
    public function getStatementPreviewAttribute(): string
    {
        return Str::limit($this->statement, 100);
    }

    /**
     * Get usage count in responses
     */
    public function getUsageCountAttribute(): int
    {
        return $this->allResponses()->count();
    }

    /**
     * Get count of times this question was selected
     */
    public function getSelectedCountAttribute(): int
    {
        return ST30Response::where('question_version_id', $this->version_id)
            ->whereJsonContains('selected_items', $this->number)
            ->count();
    }

    /**
     * Get count of times this question was excluded
     */
    public function getExcludedCountAttribute(): int
    {
        return ST30Response::where('question_version_id', $this->version_id)
            ->whereJsonContains('excluded_items', $this->number)
            ->count();
    }

    /**
     * Get selection ratio (selected vs excluded)
     */
    public function getSelectionRatioAttribute(): float
    {
        $selected = $this->selected_count;
        $excluded = $this->excluded_count;
        $total = $selected + $excluded;

        return $total > 0 ? $selected / $total : 0;
    }

    /**
     * Check if question is used in any responses
     */
    public function hasResponses(): bool
    {
        return $this->allResponses()->exists();
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for questions by version
     */
    public function scopeByVersion($query, string $versionId)
    {
        return $query->where('version_id', $versionId);
    }

    /**
     * Scope for questions by typology
     */
    public function scopeByTypology($query, string $typologyCode)
    {
        return $query->where('typology_code', $typologyCode);
    }

    /**
     * Scope for questions by number range
     */
    public function scopeByNumberRange($query, int $start, int $end)
    {
        return $query->whereBetween('number', [$start, $end]);
    }

    /**
     * Get questions for active version
     */
    public static function getActiveQuestions(): \Illuminate\Database\Eloquent\Collection
    {
        $activeVersion = QuestionVersion::getActive('st30');

        if (!$activeVersion) {
            return collect();
        }

        return static::where('version_id', $activeVersion->id)
            ->where('is_active', true)
            ->orderBy('number')
            ->get();
    }

    /**
     * Validate question number uniqueness within version
     */
    public function validateUniqueNumber(): bool
    {
        $exists = static::where('version_id', $this->version_id)
            ->where('number', $this->number)
            ->where('id', '!=', $this->id)
            ->exists();

        return !$exists;
    }

    /**
     * Get popularity score based on selection frequency
     */
    public function getPopularityScoreAttribute(): float
    {
        $totalResponses = ST30Response::where('question_version_id', $this->version_id)->count();

        if ($totalResponses === 0) {
            return 0;
        }

        return $this->selected_count / $totalResponses;
    }

    /**
     * Check if this question is frequently selected
     */
    public function isPopular(float $threshold = 0.5): bool
    {
        return $this->popularity_score >= $threshold;
    }

    /**
     * Check if this question is rarely selected
     */
    public function isUnpopular(float $threshold = 0.2): bool
    {
        return $this->popularity_score <= $threshold;
    }

    /**
     * Get questions with similar selection patterns
     */
    public function getSimilarQuestions(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        $currentRatio = $this->selection_ratio;

        return static::where('version_id', $this->version_id)
            ->where('id', '!=', $this->id)
            ->get()
            ->filter(function($question) use ($currentRatio) {
                return abs($question->selection_ratio - $currentRatio) <= 0.1;
            })
            ->take($limit);
    }

    /**
     * Get usage statistics for this question
     */
    public function getUsageStatistics(): array
    {
        return [
            'total_usage' => $this->usage_count,
            'selected_count' => $this->selected_count,
            'excluded_count' => $this->excluded_count,
            'selection_ratio' => $this->selection_ratio,
            'popularity_score' => $this->popularity_score,
        ];
    }
}
