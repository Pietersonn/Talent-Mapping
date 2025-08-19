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
     * Check if question is used in any responses
     */
    public function hasResponses(): bool
    {
        // Check if this question number appears in any ST30 responses for this version
        return ST30Response::where('question_version_id', $this->version_id)
            ->where(function($query) {
                $query->whereJsonContains('selected_items', $this->number)
                      ->orWhereJsonContains('excluded_items', $this->number);
            })
            ->exists();
    }

    /**
     * Get usage count in responses
     */
    public function getUsageCountAttribute(): int
    {
        return ST30Response::where('question_version_id', $this->version_id)
            ->where(function($query) {
                $query->whereJsonContains('selected_items', $this->number)
                      ->orWhereJsonContains('excluded_items', $this->number);
            })
            ->count();
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
}
