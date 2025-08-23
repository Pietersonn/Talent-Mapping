<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TypologyDescription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'typology_code',
        'typology_name',
        'strength_description',
        'weakness_description',
    ];

    // Tambahkan accessor methods untuk kompatibilitas dengan view

    /**
     * Accessor untuk description (menggunakan strength_description)
     */
    public function getDescriptionAttribute(): string
    {
        return $this->strength_description ?? 'No description available';
    }

    /**
     * Accessor untuk strengths
     */
    public function getStrengthsAttribute(): ?string
    {
        return $this->strength_description;
    }

    /**
     * Accessor untuk weaknesses
     */
    public function getWeaknessesAttribute(): ?string
    {
        return $this->weakness_description;
    }

    /**
     * Accessor untuk characteristics (tidak ada di database, return null)
     */
    public function getCharacteristicsAttribute(): ?string
    {
        return null;
    }

    /**
     * Accessor untuk career_suggestions (tidak ada di database, return null)
     */
    public function getCareerSuggestionsAttribute(): ?string
    {
        return null;
    }

    /**
     * Accessor untuk is_active (default true karena tidak ada kolom ini)
     */
    public function getIsActiveAttribute(): bool
    {
        return true;
    }

    /**
     * ST-30 questions that use this typology
     */
    public function st30Questions(): HasMany
    {
        return $this->hasMany(ST30Question::class, 'typology_code', 'typology_code');
    }

    /**
     * Get typology display name with code
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->typology_name . ' (' . $this->typology_code . ')';
    }

    /**
     * Get short description for typology
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
        return $query->where('typology_name', 'like', "%{$term}%")
                    ->orWhere('typology_code', 'like', "%{$term}%");
    }

    /**
     * Get questions count for this typology
     */
    public function getQuestionsCountAttribute(): int
    {
        return $this->st30Questions()->count();
    }

    /**
     * Check if typology is used in any active questions
     */
    public function isUsedInActiveQuestions(): bool
    {
        return $this->st30Questions()
            ->whereHas('questionVersion', function($query) {
                $query->where('is_active', true);
            })
            ->exists();
    }

    /**
     * Get all typology codes
     */
    public static function getAllCodes(): array
    {
        return static::pluck('typology_code')->toArray();
    }

    /**
     * Get typology options for select dropdown
     */
    public static function getSelectOptions(): array
    {
        return static::orderBy('typology_name')
            ->pluck('typology_name', 'typology_code')
            ->toArray();
    }

    /**
     * Get color class for this typology (for UI)
     */
    public function getColorClassAttribute(): string
    {
        $colors = [
            'AMB' => 'primary', 'ADM' => 'success', 'ANA' => 'info', 'ARR' => 'warning',
            'CAR' => 'danger', 'CMD' => 'dark', 'COM' => 'secondary', 'CRE' => 'light',
            'DES' => 'primary', 'DIS' => 'success', 'EDU' => 'info', 'EVA' => 'warning',
            'EXP' => 'danger', 'INT' => 'dark'
        ];

        return $colors[$this->typology_code] ?? 'secondary';
    }
}
