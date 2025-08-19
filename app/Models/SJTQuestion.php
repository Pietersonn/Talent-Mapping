<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class SJTQuestion extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'sjt_questions';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'version_id',
        'number',
        'question_text',
        'competency',
        'page_number',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'number' => 'integer',
        'page_number' => 'integer',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'SJ';

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
     * Answer options for this question
     */
    public function options(): HasMany
    {
        return $this->hasMany(SJTOption::class, 'question_id')->orderBy('option_letter');
    }

    /**
     * Competency description for this question
     */
    public function competencyDescription(): BelongsTo
    {
        return $this->belongsTo(CompetencyDescription::class, 'competency', 'competency_code');
    }

    /**
     * Responses to this question
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SJTResponse::class, 'question_id');
    }

    /**
     * Get competency name
     */
    public function getCompetencyNameAttribute(): string
    {
        return $this->competencyDescription?->competency_name ?? $this->competency;
    }

    /**
     * Get question preview (truncated)
     */
    public function getQuestionPreviewAttribute(): string
    {
        return Str::limit($this->question_text, 100);
    }

    /**
     * Get page display
     */
    public function getPageDisplayAttribute(): string
    {
        return "Page {$this->page_number} (Questions " . (($this->page_number - 1) * 10 + 1) . "-" . ($this->page_number * 10) . ")";
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
     * Scope for questions by competency
     */
    public function scopeByCompetency($query, string $competency)
    {
        return $query->where('competency', $competency);
    }

    /**
     * Scope for questions by page
     */
    public function scopeByPage($query, int $pageNumber)
    {
        return $query->where('page_number', $pageNumber);
    }

    /**
     * Get questions for specific page and version
     */
    public static function getPageQuestions(int $pageNumber, ?string $versionId = null): \Illuminate\Database\Eloquent\Collection
    {
        $versionId = $versionId ?: QuestionVersion::getActive('sjt')?->id;

        if (!$versionId) {
            return collect();
        }

        return static::where('version_id', $versionId)
            ->where('page_number', $pageNumber)
            ->where('is_active', true)
            ->with('options')
            ->orderBy('number')
            ->get();
    }

    /**
     * Get all questions for active version
     */
    public static function getActiveQuestions(): \Illuminate\Database\Eloquent\Collection
    {
        $activeVersion = QuestionVersion::getActive('sjt');

        if (!$activeVersion) {
            return collect();
        }

        return static::where('version_id', $activeVersion->id)
            ->where('is_active', true)
            ->with(['options', 'competencyDescription'])
            ->orderBy('number')
            ->get();
    }

    /**
     * Check if question has all required options (a, b, c, d, e)
     */
    public function hasCompleteOptions(): bool
    {
        $requiredOptions = ['a', 'b', 'c', 'd', 'e'];
        $existingOptions = $this->options->pluck('option_letter')->toArray();

        return count(array_intersect($requiredOptions, $existingOptions)) === 5;
    }

    /**
     * Get missing options
     */
    public function getMissingOptionsAttribute(): array
    {
        $requiredOptions = ['a', 'b', 'c', 'd', 'e'];
        $existingOptions = $this->options->pluck('option_letter')->toArray();

        return array_diff($requiredOptions, $existingOptions);
    }

    /**
     * Check if question is used in any responses
     */
    public function hasResponses(): bool
    {
        return $this->responses()->exists();
    }

    /**
     * Get usage count in responses
     */
    public function getUsageCountAttribute(): int
    {
        return $this->responses()->count();
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
     * Auto-assign page number based on question number
     */
    public function autoAssignPageNumber(): void
    {
        $this->page_number = ceil($this->number / 10);
    }
}
