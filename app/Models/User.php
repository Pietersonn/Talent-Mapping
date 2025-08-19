<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is PIC
     */
    public function isPIC(): bool
    {
        return $this->role === 'pic';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Events where this user is assigned as PIC
     */
    public function picEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'pic_id');
    }

    /**
     * Events this user has participated in
     */
    public function participatedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participants')
            ->withPivot('test_completed', 'results_sent')
            ->withTimestamps();
    }

    /**
     * Test sessions created by this user
     */
    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class);
    }

    /**
     * Resend requests made by this user
     */
    public function resendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class);
    }

    /**
     * Resend requests approved by this user (for admins)
     */
    public function approvedResendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class, 'approved_by');
    }

    /**
     * Activity logs for this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Question versions created by this user (for admins)
     */
    public function createdQuestionVersions(): HasMany
    {
        return $this->hasMany(QuestionVersion::class, 'created_by');
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'pic' => 'Person in Charge',
            'user' => 'User',
            default => 'Unknown'
        };
    }

    /**
     * Get user's status display
     */
    public function getStatusDisplayAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for staff
     */
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    /**
     * Scope for PICs
     */
    public function scopePICs($query)
    {
        return $query->where('role', 'pic');
    }

    /**
     * Scope for regular users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }
}
