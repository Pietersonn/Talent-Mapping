<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSession extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'test_sessions';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',                    // CRITICAL: Add id to allow mass assignment
        'user_id',
        'event_id',
        'session_token',
        'current_step',
        'participant_name',
        'participant_background', // CORRECT: This field exists in database
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Custom ID prefix for generation
     */
    protected $customIdPrefix = 'TS';

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
     * User who owns this test session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Event this session belongs to (optional)
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * ST-30 question version used in this session
     */
    public function st30Version(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'st30_version_id');
    }

    /**
     * SJT question version used in this session
     */
    public function sjtVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'sjt_version_id');
    }

    /**
     * ST-30 responses for this session
     */
    public function st30Responses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'session_id');
    }

    /**
     * SJT responses for this session
     */
    public function sjtResponses(): HasMany
    {
        return $this->hasMany(SJTResponse::class, 'session_id');
    }

    /**
     * Test result for this session
     */
    public function testResult(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TestResult::class, 'session_id');
    }

    /**
     * Check if session is in ST-30 stage
     */
    public function isInST30Stage(): bool
    {
        return str_starts_with($this->current_step, 'st30_stage');
    }

    /**
     * Check if session is in SJT stage
     */
    public function isInSJTStage(): bool
    {
        return str_starts_with($this->current_step, 'sjt_page');
    }

    /**
     * Get current ST-30 stage number
     */
    public function getCurrentST30Stage(): ?int
    {
        if (!$this->isInST30Stage()) {
            return null;
        }

        return (int) str_replace('st30_stage_', '', $this->current_step);
    }

    /**
     * Get current SJT page number
     */
    public function getCurrentSJTPage(): ?int
    {
        if (!$this->isInSJTStage()) {
            return null;
        }

        return (int) str_replace('sjt_page_', '', $this->current_step);
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage(): int
    {
        if ($this->is_completed) {
            return 100;
        }

        $stepMapping = [
            'form_data' => 0,
            'st30_stage_1' => 10,
            'st30_stage_2' => 25,
            'st30_stage_3' => 40,
            'st30_stage_4' => 55,
            'sjt_page_1' => 65,
            'sjt_page_2' => 72,
            'sjt_page_3' => 79,
            'sjt_page_4' => 86,
            'sjt_page_5' => 93,
            'completed' => 100,
        ];

        return $stepMapping[$this->current_step] ?? 0;
    }

    /**
     * Get progress display text
     */
    public function getProgressDisplayAttribute(): string
    {
        switch ($this->current_step) {
            case 'form_data':
                return 'Registration Complete';
            case 'st30_stage_1':
                return 'ST-30 Stage 1 of 4';
            case 'st30_stage_2':
                return 'ST-30 Stage 2 of 4';
            case 'st30_stage_3':
                return 'ST-30 Stage 3 of 4';
            case 'st30_stage_4':
                return 'ST-30 Stage 4 of 4';
            case 'sjt_page_1':
                return 'SJT Page 1 of 5';
            case 'sjt_page_2':
                return 'SJT Page 2 of 5';
            case 'sjt_page_3':
                return 'SJT Page 3 of 5';
            case 'sjt_page_4':
                return 'SJT Page 4 of 5';
            case 'sjt_page_5':
                return 'SJT Page 5 of 5';
            case 'completed':
                return 'Test Completed';
            default:
                return 'Unknown Step';
        }
    }

    /**
     * Check if user can access specific stage/page
     */
    public function canAccessStage(string $targetStep): bool
    {
        $stepOrder = [
            'form_data', 'st30_stage_1', 'st30_stage_2', 'st30_stage_3', 'st30_stage_4',
            'sjt_page_1', 'sjt_page_2', 'sjt_page_3', 'sjt_page_4', 'sjt_page_5', 'completed'
        ];

        $currentIndex = array_search($this->current_step, $stepOrder);
        $targetIndex = array_search($targetStep, $stepOrder);

        // User can access current step or go back to previous steps
        return $targetIndex !== false && $targetIndex <= $currentIndex;
    }
}
