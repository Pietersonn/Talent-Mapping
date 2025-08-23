<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'competency', // BENAR - ini nama kolom yang ada di database
        'page_number',
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
            return $this->customIdPrefix . '101';
        }

        $lastNumber = (int) substr($lastId->id, strlen($this->customIdPrefix));
        $newNumber = $lastNumber + 1;

        return $this->customIdPrefix . $newNumber;
    }

    /**
     * Question version this question belongs to
     */
    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'version_id');
    }

    /**
     * Competency description for this question - PERBAIKAN UTAMA
     */
    public function competencyDescription(): BelongsTo
    {
        // Kolom 'competency' di sjt_questions join dengan 'competency_code' di competency_descriptions
        return $this->belongsTo(CompetencyDescription::class, 'competency', 'competency_code');
    }

    /**
     * Get all options for this question
     */
    public function options(): HasMany
    {
        return $this->hasMany(SJTOption::class, 'question_id')->orderBy('option_letter');
    }

    /**
     * Get active options only
     */
    public function activeOptions(): HasMany
    {
        return $this->hasMany(SJTOption::class, 'question_id')
            ->where('is_active', true)
            ->orderBy('option_letter');
    }

    /**
     * Get responses for this question
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SJTResponse::class, 'question_id');
    }

    /**
     * Get question preview (truncated)
     */
    public function getQuestionPreviewAttribute(): string
    {
        return strlen($this->question_text) > 100
            ? substr($this->question_text, 0, 100) . '...'
            : $this->question_text;
    }

    /**
     * Get competency code - ALIAS untuk konsistensi
     */
    public function getCompetencyCodeAttribute(): string
    {
        return $this->competency;
    }

    /**
     * Get competency name through relationship - PERBAIKAN
     */
    public function getCompetencyNameAttribute(): string
    {
        return $this->competencyDescription?->competency_name ?? 'Unknown Competency';
    }

    /**
     * Get usage count in responses
     */
    public function getUsageCountAttribute(): int
    {
        return $this->responses()->count();
    }

    /**
     * Check if question has responses
     */
    public function hasResponses(): bool
    {
        return $this->responses()->exists();
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
    public function scopeByCompetency($query, string $competencyCode)
    {
        return $query->where('competency', $competencyCode);
    }

    /**
     * Scope for questions by page
     */
    public function scopeByPage($query, int $pageNumber)
    {
        return $query->where('page_number', $pageNumber);
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
        $activeVersion = QuestionVersion::getActive('sjt');

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
     * Get options count for this question
     */
    public function getOptionsCountAttribute(): int
    {
        return $this->options()->count();
    }

    /**
     * Check if question has complete options (5 options)
     */
    public function hasCompleteOptions(): bool
    {
        return $this->options()->count() === 5;
    }

    /**
     * Get highest scoring option
     */
    public function getHighestScoringOption()
    {
        return $this->options()->orderBy('score', 'desc')->first();
    }

    /**
     * Get lowest scoring option
     */
    public function getLowestScoringOption()
    {
        return $this->options()->orderBy('score', 'asc')->first();
    }

    /**
     * Get score distribution for this question
     */
    public function getScoreDistribution(): array
    {
        return $this->options()
            ->selectRaw('score, COUNT(*) as count')
            ->groupBy('score')
            ->pluck('count', 'score')
            ->toArray();
    }
}
