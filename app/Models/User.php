<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',  
        'role',
        'is_active',
        'google_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ===== Helpers (role/status) =====
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }
    public function isPIC(): bool
    {
        return $this->role === 'pic';
    }
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function scopeByRole($q, string $role)
    {
        return $q->where('role', $role);
    }
    public function scopeAdmins($q)
    {
        return $q->where('role', 'admin');
    }
    public function scopeStaff($q)
    {
        return $q->where('role', 'staff');
    }
    public function scopePICs($q)
    {
        return $q->where('role', 'pic');
    }
    public function scopeUsers($q)
    {
        return $q->where('role', 'user');
    }

    public function getRoleDisplayAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'pic'   => 'Person in Charge',
            'user'  => 'User',
            default => 'Unknown',
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    // ===== RELATIONS =====

    /** User -> TestSessions (FK: test_sessions.user_id) */
    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class, 'user_id', 'id');
    }

    /** User -> TestResults (via TestSessions) */
    public function testResults(): HasManyThrough
    {
        return $this->hasManyThrough(
            TestResult::class,   // target
            TestSession::class,  // through
            'user_id',           // FK di test_sessions -> users.id
            'session_id',        // FK di test_results -> test_sessions.id
            'id',                // PK users
            'id'                 // PK test_sessions
        );
    }

    /** User <-> Events sebagai peserta (pivot: event_participants) */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participants', 'user_id', 'event_id')
            ->withPivot(['test_completed', 'results_sent'])
            ->withTimestamps(); // pivot punya created_at/updated_at
    }

    /** User -> Events yang dia kelola sebagai PIC (events.pic_id) */
    public function picEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'pic_id', 'id');
    }

    /** User -> ResendRequests yang dibuat user (resend_requests.user_id) */
    public function resendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class, 'user_id', 'id');
    }

    /** User -> ResendRequests yang disetujui user ini sebagai admin/pic (resend_requests.approved_by) */
    public function approvedResendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class, 'approved_by', 'id');
    }

    // ===== Scopes tambahan =====
    public function scopeWithResults($q)
    {
        return $q->whereHas('testResults');
    }
}
