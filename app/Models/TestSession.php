<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TestSession extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'event_id',
        'session_token',
        'current_step',
        'st30_version_id',
        'participant_name',
        'participant_background',
        'position',
        'is_completed',
        'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function st30Responses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'session_id');
    }

    public function sjtResponses(): HasMany
    {
        return $this->hasMany(SJTResponse::class, 'session_id');
    }

    public function testResult(): HasOne
    {
        return $this->hasOne(TestResult::class, 'session_id');
    }


    /**
     * Get simple progress display text
     */
    public function getProgressDisplayAttribute(): string
    {
        $percentage = $this->progress_percentage;

        if ($percentage == 0) {
            return 'Belum dimulai';
        } elseif ($percentage < 60) {
            return 'ST-30 Assessment (' . number_format($percentage) . '%)';
        } elseif ($percentage < 100) {
            return 'SJT Assessment (' . number_format($percentage) . '%)';
        } else {
            return 'Assessment Selesai';
        }
    }

    /**
     * Get detailed progress info
     */
    public function getDetailedProgressAttribute(): array
    {
        return [
            'percentage' => $this->progress_percentage,
            'display' => $this->progress_display,
            'current_step' => $this->current_step,
            'is_st30_phase' => in_array($this->current_step, [
                'form_data',
                'st30_stage1',
                'st30_stage2',
                'st30_stage3',
                'st30_stage4'
            ]),
            'is_sjt_phase' => in_array($this->current_step, [
                'sjt_page1',
                'sjt_page2',
                'sjt_page3',
                'sjt_page4',
                'sjt_page5'
            ]),
            'is_completed' => $this->is_completed
        ];
    }

    /**
     * Check if user can access specific stage/page
     */
    public function canAccessStage(string $targetStep): bool
    {
        $stepOrder = [
            'form_data',
            'st30_stage1',
            'st30_stage2',
            'st30_stage3',
            'st30_stage4',
            'sjt_page1',
            'sjt_page2',
            'sjt_page3',
            'sjt_page4',
            'sjt_page5',
            'completed'
        ];

        $currentIndex = array_search($this->current_step, $stepOrder);
        $targetIndex = array_search($targetStep, $stepOrder);

        // User can access current step or go back to previous completed steps
        return $targetIndex !== false && $targetIndex <= $currentIndex;
    }

    /**
     * Get completion statistics
     */
    public function getCompletionStatsAttribute(): array
    {
        return [
            'st30_completed_stages' => $this->st30Responses()->where('for_scoring', true)->count(),
            'st30_total_stages' => 4,
            'sjt_completed_questions' => $this->sjtResponses()->count(),
            'sjt_total_questions' => 50,
            'overall_completion' => $this->progress_percentage
        ];
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope for completed sessions
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for sessions in specific phase
     */
    public function scopeInPhase($query, $phase)
    {
        switch ($phase) {
            case 'st30':
                return $query->whereIn('current_step', [
                    'form_data',
                    'st30_stage1',
                    'st30_stage2',
                    'st30_stage3',
                    'st30_stage4'
                ]);
            case 'sjt':
                return $query->whereIn('current_step', [
                    'sjt_page1',
                    'sjt_page2',
                    'sjt_page3',
                    'sjt_page4',
                    'sjt_page5'
                ]);
            default:
                return $query;
        }
    }

    /**
     * Check if session is ready for results
     */
    public function isReadyForResults(): bool
    {
        return $this->is_completed &&
            $this->st30Responses()->where('for_scoring', true)->count() >= 2 && // Stage 1 & 2
            $this->sjtResponses()->count() >= 50;
    }

    /**
     * Get next step in the flow
     */
    public function getNextStep(): ?string
    {
        $stepOrder = [
            'form_data' => 'st30_stage1',
            'st30_stage1' => 'st30_stage2',
            'st30_stage2' => 'st30_stage3',
            'st30_stage3' => 'st30_stage4',
            'st30_stage4' => 'sjt_page1',
            'sjt_page1' => 'sjt_page2',
            'sjt_page2' => 'sjt_page3',
            'sjt_page3' => 'sjt_page4',
            'sjt_page4' => 'sjt_page5',
            'sjt_page5' => 'completed',
        ];

        return $stepOrder[$this->current_step] ?? null;
    }
}
