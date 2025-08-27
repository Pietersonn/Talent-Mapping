<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResult extends Model
{
    use HasFactory, HasCustomId;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'session_id',
        'st30_results',
        'sjt_results',
        'dominant_typology',
        'report_generated_at',
        'email_sent_at',
        'pdf_path',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'st30_results' => 'array',
        'sjt_results' => 'array',
        'report_generated_at' => 'datetime',
        'email_sent_at' => 'datetime',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'TR';

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
     * Test session this result belongs to
     */
    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    /**
     * User who took the test
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get dominant typology description
     */
    public function dominantTypologyDescription()
    {
        return $this->belongsTo(TypologyDescription::class, 'dominant_typology', 'typology_code');
    }

    public $timestamps = false;

    /**
     * Check if PDF report is generated
     */
    public function hasPdfReport(): bool
    {
        return !empty($this->pdf_path) && file_exists(storage_path('app/public/' . $this->pdf_path));
    }

    /**
     * Check if email has been sent
     */
    public function isEmailSent(): bool
    {
        return !is_null($this->email_sent_at);
    }

    /**
     * Get ST-30 strengths
     */
    public function getSt30StrengthsAttribute(): array
    {
        return $this->st30_results['strengths'] ?? [];
    }

    /**
     * Get ST-30 development areas
     */
    public function getSt30DevelopmentAreasAttribute(): array
    {
        return $this->st30_results['development_areas'] ?? [];
    }

    /**
     * Get SJT strengths
     */
    public function getSjtStrengthsAttribute(): array
    {
        return $this->sjt_results['strengths'] ?? [];
    }

    /**
     * Get SJT development areas
     */
    public function getSjtDevelopmentAreasAttribute(): array
    {
        return $this->sjt_results['development_areas'] ?? [];
    }

    /**
     * Get overall summary
     */
    public function getSummaryAttribute(): array
    {
        $st30Strengths = $this->st30_strengths;
        $sjtStrengths = $this->sjt_strengths;

        return [
            'dominant_typology' => $st30Strengths[0] ?? null,
            'top_competency' => $sjtStrengths[0] ?? null,
            'total_st30_responses' => $this->st30_results['total_responses'] ?? 0,
            'total_sjt_questions' => $this->sjt_results['total_questions'] ?? 0,
        ];
    }

    /**
     * Scope for results with email sent
     */
    public function scopeEmailSent($query)
    {
        return $query->whereNotNull('email_sent_at');
    }

    /**
     * Scope for results without email sent
     */
    public function scopeEmailPending($query)
    {
        return $query->whereNull('email_sent_at');
    }

    /**
     * Scope for results with PDF generated
     */
    public function scopeWithPdf($query)
    {
        return $query->whereNotNull('pdf_path');
    }

    /**
     * Scope by dominant typology
     */
    public function scopeByTypology($query, string $typologyCode)
    {
        return $query->where('dominant_typology', $typologyCode);
    }
}
