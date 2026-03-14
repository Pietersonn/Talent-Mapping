<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'acara';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'perusahaan',
        'deskripsi',
        'kode_acara',
        'tanggal_mulai',
        'tanggal_selesai',
        'id_pic',
        'aktif',
        'maks_peserta',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'aktif'           => 'boolean',
    ];

    // ===== Alias untuk kompatibilitas kode lama =====
    public function getNameAttribute(): string        { return $this->nama; }
    public function getCompanyAttribute(): ?string    { return $this->perusahaan; }
    public function getDescriptionAttribute(): ?string{ return $this->deskripsi; }
    public function getEventCodeAttribute(): ?string  { return $this->kode_acara; }
    public function getStartDateAttribute()           { return $this->tanggal_mulai; }
    public function getEndDateAttribute()             { return $this->tanggal_selesai; }
    public function getIsActiveAttribute(): bool      { return $this->aktif; }
    public function getMaxParticipantsAttribute(): ?int { return $this->maks_peserta; }

    // ===== Relationships =====
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pic');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'peserta_acara', 'id_acara', 'id_pengguna')
            ->withPivot('tes_selesai', 'hasil_terkirim')
            ->withTimestamps();
    }

    public function sesiTes(): HasMany
    {
        return $this->hasMany(TestSession::class, 'id_acara');
    }

    // ===== Scopes =====
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeOngoing($query)
    {
        return $query->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>', now());
    }

    // ===== Accessors =====
    public function getStatusDisplayAttribute(): string
    {
        if (!$this->aktif) return 'Nonaktif';
        $now = now();
        if ($this->tanggal_mulai > $now) return 'Akan Datang';
        if ($this->tanggal_selesai < $now) return 'Selesai';
        return 'Berlangsung';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_display) {
            'Nonaktif'     => 'secondary',
            'Akan Datang'  => 'info',
            'Berlangsung'  => 'success',
            'Selesai'      => 'warning',
            default        => 'secondary',
        };
    }
}
