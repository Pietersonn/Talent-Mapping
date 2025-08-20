<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SJTOption extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'sjt_options';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question_id',
        'option_letter',
        'option_text',
        'score',
        'competency_target',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'score' => 'integer',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'SO';

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
     * SJT question this option belongs to
     */
    public function sjtQuestion(): BelongsTo
    {
        return $this->belongsTo(SJTQuestion::class, 'question_id');
    }

    /**
     * Get option display text (truncated)
     */
    public function getOptionPreviewAttribute(): string
    {
        return Str::limit($this->option_text, 80);
    }

    /**
     * Get score display with color
     */
    public function getScoreDisplayAttribute(): string
    {
        $colors = [
            0 => 'danger',
            1 => 'warning',
            2 => 'info',
            3 => 'primary',
            4 => 'success'
        ];

        $color = $colors[$this->score] ?? 'secondary';
        return "<span class='badge badge-{$color}'>{$this->score}</span>";
    }

    /**
     * Get option letter display
     */
    public function getLetterDisplayAttribute(): string
    {
        return strtoupper($this->option_letter);
    }

    /**
     * Scope for specific option letter
     */
    public function scopeByLetter($query, string $letter)
    {
        return $query->where('option_letter', strtolower($letter));
    }

    /**
     * Scope for specific score
     */
    public function scopeByScore($query, int $score)
    {
        return $query->where('score', $score);
    }

    /**
     * Scope for high scoring options (3-4)
     */
    public function scopeHighScore($query)
    {
        return $query->whereIn('score', [3, 4]);
    }

    /**
     * Scope for low scoring options (0-1)
     */
    public function scopeLowScore($query)
    {
        return $query->whereIn('score', [0, 1]);
    }

    /**
     * Validate option letter (a-e only)
     */
    public function validateOptionLetter(): bool
    {
        return in_array(strtolower($this->option_letter), ['a', 'b', 'c', 'd', 'e']);
    }

    /**
     * Validate score range (0-4)
     */
    public function validateScore(): bool
    {
        return $this->score >= 0 && $this->score <= 4;
    }

    /**
     * Check if option is used in any responses
     */
    public function hasResponses(): bool
    {
        return SJTResponse::where('question_id', $this->question_id)
            ->where('selected_option', $this->option_letter)
            ->exists();
    }

    /**
     * Get usage count for this option
     */
    public function getUsageCountAttribute(): int
    {
        return SJTResponse::where('question_id', $this->question_id)
            ->where('selected_option', $this->option_letter)
            ->count();
    }
}
