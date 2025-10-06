<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'company',
        'description',
        'event_code',
        'start_date',
        'end_date',
        'pic_id',
        'is_active',
        'max_participants'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants')
            ->withPivot('test_completed', 'results_sent')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    // Accessors
    public function getStatusDisplayAttribute(): string
    {
        if (!$this->is_active) return 'Inactive';

        $now = now();
        if ($this->start_date > $now) return 'Upcoming';
        if ($this->end_date < $now) return 'Ended';
        return 'Ongoing';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_display) {
            'Inactive' => 'secondary',
            'Upcoming' => 'info',
            'Ongoing' => 'success',
            'Ended' => 'warning'
        };
    }
}
