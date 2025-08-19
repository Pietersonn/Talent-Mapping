<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionVersion extends Model
{
    use HasFactory, HasCustomId;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'version',
        'type',
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'QV';

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
     * ST-30 questions in this version
     */
    public function st30Questions(): HasMany
    {
        return $this->hasMany(ST30Question::class, 'version_id');
    }

    /**
     * SJT questions in this version
     */
    public function sjtQuestions(): HasMany
    {
        return $this->hasMany(SJTQuestion::class, 'version_id');
    }

    /**
     * ST-30 responses using this version
     */
    public function st30Responses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'question_version_id');
    }

    /**
     * SJT responses using this version
     */
    public function sjtResponses(): HasMany
    {
        return $this->hasMany(SJTResponse::class, 'question_version_id');
    }

    /**
     * Get version display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->type . ' Version ' . $this->version;
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'st30' => 'ST-30 (Strength Typology)',
            'sjt' => 'SJT (Situational Judgment Test)',
            default => $this->type
        };
    }

    /**
     * Get questions count
     */
    public function getQuestionsCountAttribute(): int
    {
        return match($this->type) {
            'st30' => $this->st30Questions()->count(),
            'sjt' => $this->sjtQuestions()->count(),
            default => 0
        };
    }

    /**
     * Get status display
     */
    public function getStatusDisplayAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Scope for active versions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ST-30 versions
     */
    public function scopeST30($query)
    {
        return $query->where('type', 'st30');
    }

    /**
     * Scope for SJT versions
     */
    public function scopeSJT($query)
    {
        return $query->where('type', 'sjt');
    }

    /**
     * Get active version for a specific type
     */
    public static function getActive(string $type): ?self
    {
        return static::where('type', $type)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Activate this version (deactivate others of same type)
     */
    public function activate(): bool
    {
        // Deactivate other versions of same type
        static::where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        // Activate this version
        return $this->update(['is_active' => true]);
    }

    /**
     * Check if version has any responses
     */
    public function hasResponses(): bool
    {
        return $this->st30Responses()->exists() || $this->sjtResponses()->exists();
    }

    /**
     * Get usage statistics
     */
    public function getUsageStatsAttribute(): array
    {
        $st30Count = $this->st30Responses()->distinct('session_id')->count();
        $sjtCount = $this->sjtResponses()->distinct('session_id')->count();

        return [
            'st30_responses' => $st30Count,
            'sjt_responses' => $sjtCount,
            'total_usage' => max($st30Count, $sjtCount)
        ];
    }
}
