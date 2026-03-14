<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'nama', 'perusahaan', 'deskripsi', 'kode_program',
        'tanggal_mulai', 'tanggal_selesai', 'id_mitra', 'aktif', 'maks_peserta',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'aktif'           => 'boolean',
    ];

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mitra');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'peserta_program', 'id_program', 'id_pengguna')
            ->withPivot('tes_selesai', 'hasil_terkirim')
            ->withTimestamps();
    }

    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class, 'id_program', 'id');
    }

    public function scopeActive($query) { return $query->where('aktif', true); }
    public function scopeOngoing($query) {
        return $query->where('tanggal_mulai', '<=', now())->where('tanggal_selesai', '>=', now());
    }
    public function scopeUpcoming($query) { return $query->where('tanggal_mulai', '>', now()); }

    public function getStatusDisplayAttribute(): string
    {
        if (!$this->aktif) return 'Nonaktif';
        $now = now();
        if ($this->tanggal_mulai > $now)   return 'Akan Datang';
        if ($this->tanggal_selesai < $now) return 'Selesai';
        return 'Berlangsung';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_display) {
            'Nonaktif' => 'secondary', 'Akan Datang' => 'info',
            'Berlangsung' => 'success', 'Selesai' => 'warning', default => 'secondary',
        };
    }
}
