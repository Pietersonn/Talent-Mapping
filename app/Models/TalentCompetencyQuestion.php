<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentCompetencyQuestion extends Model
{
    use HasFactory;

    protected $table = 'soal_tk';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_versi', 'nomor', 'teks_pertanyaan', 'kode_kompetensi', 'aktif'];
    protected $casts = ['aktif' => 'boolean', 'nomor' => 'integer'];

    // Alias untuk kode lama
    public function getVersionIdAttribute(): string    { return $this->id_versi; }
    public function getNumberAttribute(): int          { return $this->nomor; }
    public function getQuestionTextAttribute(): string { return $this->teks_pertanyaan; }
    public function getCompetencyAttribute(): string   { return $this->kode_kompetensi; }
    public function getIsActiveAttribute(): bool       { return $this->aktif; }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = $model->generateNextId();
            }
        });
    }

    public function generateNextId(): string
    {
        $lastId = static::orderBy('id', 'desc')->value('id');
        if (!$lastId) return 'SJ001';
        $num = (int) substr($lastId, 2);
        return 'SJ' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

    // ===== Relationships =====

    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'id_versi');
    }

    public function competencyDescription(): BelongsTo
    {
        return $this->belongsTo(CompetencyDescription::class, 'kode_kompetensi', 'kode_kompetensi');
    }

    public function options(): HasMany
    {
        return $this->hasMany(TalentCompetencyOption::class, 'id_soal')->orderBy('huruf_pilihan');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(TalentCompetencyOption::class, 'id_soal')
            ->where('aktif', true)->orderBy('huruf_pilihan');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TalentCompetencyResponse::class, 'id_soal');
    }

    // ===== Accessors =====

    public function getQuestionPreviewAttribute(): string
    {
        return strlen($this->teks_pertanyaan) > 100
            ? substr($this->teks_pertanyaan, 0, 100) . '...'
            : $this->teks_pertanyaan;
    }

    public function getCompetencyNameAttribute(): string
    {
        return $this->competencyDescription?->nama_kompetensi ?? $this->kode_kompetensi;
    }
}
