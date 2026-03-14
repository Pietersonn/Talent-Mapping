<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = ['nama', 'email', 'password', 'nomor_telepon', 'peran', 'aktif', 'google_id'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'aktif' => 'boolean'];
    }

    public function hasRole($role): bool           { return $this->peran === $role; }
    public function hasAnyRole(array $roles): bool { return in_array($this->peran, $roles, true); }
    public function isActive(): bool               { return (bool) $this->aktif; }
    public function isAdmin(): bool                { return $this->peran === 'admin'; }
    public function isMitra(): bool                { return $this->peran === 'mitra'; }
    public function isPeserta(): bool              { return $this->peran === 'peserta'; }

    public function scopeActive($q)              { return $q->where('aktif', true); }
    public function scopeByRole($q, string $r)   { return $q->where('peran', $r); }
    public function scopeAdmins($q)              { return $q->where('peran', 'admin'); }
    public function scopeMitras($q)              { return $q->where('peran', 'mitra'); }
    public function scopePesertas($q)            { return $q->where('peran', 'peserta'); }

    public function getRoleDisplayAttribute(): string
    {
        return match ($this->peran) {
            'admin' => 'Administrator', 'mitra' => 'Mitra',
            'peserta' => 'Peserta', default => 'Tidak Diketahui',
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return $this->aktif ? 'Aktif' : 'Nonaktif';
    }

    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class, 'id_pengguna');
    }

    public function programSebagaiMitra(): HasMany
    {
        return $this->hasMany(Program::class, 'id_mitra');
    }

    /** @deprecated gunakan programSebagaiMitra() */
    public function acaraSebagaiMitra(): HasMany
    {
        return $this->programSebagaiMitra();
    }

    public function program(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'peserta_program', 'id_pengguna', 'id_program')
            ->withPivot('tes_selesai', 'hasil_terkirim')
            ->withTimestamps();
    }
}
