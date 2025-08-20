<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CompetencyDescription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'competency_code',
        'competency_name',
        'strength_description',
        'weakness_description',
        'improvement_activity',
    ];

    /**
     * SJT questions that use this competency
     */
    public function sjtQuestions(): HasMany
    {
        return $this->hasMany(SJTQuestion::class, 'competency', 'competency_code');
    }

    /**
     * Get competency display name with code
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->competency_name . ' (' . $this->competency_code . ')';
    }

    /**
     * Get short description for competency
     */
    public function getShortDescriptionAttribute(): string
    {
        return Str::limit($this->strength_description ?? 'No description available', 100);
    }

    /**
     * Scope for searching by name or code
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where('competency_name', 'like', "%{$term}%")
                    ->orWhere('competency_code', 'like', "%{$term}%");
    }

    /**
     * Get questions count for this competency
     */
    public function getQuestionsCountAttribute(): int
    {
        return $this->sjtQuestions()->count();
    }

    /**
     * Check if competency is used in any active questions
     */
    public function isUsedInActiveQuestions(): bool
    {
        return $this->sjtQuestions()
            ->whereHas('questionVersion', function($query) {
                $query->where('is_active', true);
            })
            ->exists();
    }

    /**
     * Get all competency codes
     */
    public static function getAllCodes(): array
    {
        return static::pluck('competency_code')->toArray();
    }

    /**
     * Get competency options for select dropdown
     */
    public static function getSelectOptions(): array
    {
        return static::orderBy('competency_name')
            ->pluck('competency_name', 'competency_code')
            ->toArray();
    }
}
