<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionVersion extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'versi_soal';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'versi',
        'jenis',     // st30 | tk
        'nama',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'versi' => 'integer',
    ];

    // ===== Alias untuk kompatibilitas kode lama =====
    public function getVersionAttribute(): int         { return $this->versi; }
    public function getTypeAttribute(): string         { return $this->jenis; }
    public function getNameAttribute(): string         { return $this->nama; }
    public function getDescriptionAttribute(): ?string { return $this->deskripsi; }
    public function getIsActiveAttribute(): bool       { return $this->aktif; }

    /* =====================================================
    |  CUSTOM ID GENERATION
    ===================================================== */
    public function generateCustomId(): string
    {
        $prefix = match ($this->jenis) {
            'tk'   => 'TKV',
            'st30' => 'STV',
            default => 'QV',
        };

        $lastId = static::where('id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('id');

        if (!$lastId) {
            return $prefix . '01';
        }

        $num = (int) substr($lastId, strlen($prefix));
        return $prefix . str_pad($num + 1, 2, '0', STR_PAD_LEFT);
    }

    /* ===== Static helper ===== */
    public static function getActive(string $jenis): ?self
    {
        return static::where('jenis', $jenis)->where('aktif', true)->first();
    }

    /* ===== Relations ===== */
    public function st30Questions(): HasMany
    {
        return $this->hasMany(ST30Question::class, 'id_versi');
    }

    public function talentCompetencyQuestions(): HasMany
    {
        return $this->hasMany(TalentCompetencyQuestion::class, 'id_versi');
    }

    /** @deprecated Gunakan talentCompetencyQuestions() */
    public function sjtQuestions(): HasMany
    {
        return $this->talentCompetencyQuestions();
    }

    public function st30Responses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'id_versi_soal');
    }

    public function talentCompetencyResponses(): HasMany
    {
        return $this->hasMany(TalentCompetencyResponse::class, 'id_versi_soal');
    }

    /** @deprecated Gunakan talentCompetencyResponses() */
    public function sjtResponses(): HasMany
    {
        return $this->talentCompetencyResponses();
    }

    /* ===== Accessors ===== */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama ?: strtoupper($this->jenis) . ' Versi ' . $this->versi;
    }

    public function getTypeDisplayAttribute(): string
    {
        return match ($this->jenis) {
            'st30' => 'ST-30 (Strength Typology)',
            'tk'   => 'TK (Talent Kompetensi)',
            default => strtoupper($this->jenis),
        };
    }

    public function getQuestionsCountAttribute(): int
    {
        return match ($this->jenis) {
            'st30' => $this->st30Questions()->count(),
            'tk'   => $this->talentCompetencyQuestions()->count(),
            default => 0,
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return $this->aktif ? 'Aktif' : 'Nonaktif';
    }
}
