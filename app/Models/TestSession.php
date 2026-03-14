<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TestSession extends Model
{
    use HasCustomId;

    protected $table = 'sesi_tes';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected string $customIdPrefix = 'TS';
    protected int $customIdLength = 3;

    protected $fillable = [
        'id_pengguna',
        'id_acara',
        'token_sesi',
        'langkah_saat_ini',
        'id_versi_st30',
        'nama_peserta',
        'latar_belakang',
        'jabatan',
        'selesai',
        'selesai_pada',
    ];

    protected $casts = [
        'selesai'      => 'boolean',
        'selesai_pada' => 'datetime',
    ];

    // ===== Alias untuk kompatibilitas kode lama =====
    public function getUserIdAttribute(): int              { return $this->id_pengguna; }
    public function getEventIdAttribute(): ?string         { return $this->id_acara; }
    public function getSessionTokenAttribute(): string     { return $this->token_sesi; }
    public function getCurrentStepAttribute(): string      { return $this->langkah_saat_ini; }
    public function getSt30VersionIdAttribute(): ?string   { return $this->id_versi_st30; }
    public function getParticipantNameAttribute(): ?string { return $this->nama_peserta; }
    public function getParticipantBackgroundAttribute(): ?string { return $this->latar_belakang; }
    public function getPositionAttribute(): ?string        { return $this->jabatan; }
    public function getIsCompletedAttribute(): bool        { return $this->selesai; }
    public function getCompletedAtAttribute()              { return $this->selesai_pada; }

    // ===== Relationships =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'id_acara');
    }

    public function st30Responses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'id_sesi', 'id');
    }

    public function talentCompetencyResponses(): HasMany
    {
        return $this->hasMany(TalentCompetencyResponse::class, 'id_sesi', 'id');
    }

    /** @deprecated Gunakan talentCompetencyResponses() */
    public function sjtResponses(): HasMany
    {
        return $this->talentCompetencyResponses();
    }

    public function testResult(): HasOne
    {
        return $this->hasOne(TestResult::class, 'id_sesi', 'id');
    }

    // ===== Accessors =====

    public function getProgressDisplayAttribute(): string
    {
        $step = $this->langkah_saat_ini;
        if (str_starts_with($step, 'st30')) return 'ST-30 Assessment';
        if (str_starts_with($step, 'sjt'))  return 'TK Assessment';
        if ($this->selesai)                 return 'Assessment Selesai';
        return 'Belum dimulai';
    }
}
