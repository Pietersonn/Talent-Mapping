<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetencyDescription extends Model
{
    use HasFactory;

    protected $table = 'deskripsi_kompetensi';

    protected $fillable = [
        'kode_kompetensi',
        'nama_kompetensi',
        'deskripsi_kekuatan',
        'deskripsi_kelemahan',
        'aktivitas_pengembangan',
        'rekomendasi_pelatihan',
    ];

    // Alias untuk kode lama
    public function getCompetencyCodeAttribute(): string       { return $this->kode_kompetensi; }
    public function getCompetencyNameAttribute(): string       { return $this->nama_kompetensi; }
    public function getStrengthDescriptionAttribute(): ?string { return $this->deskripsi_kekuatan; }
    public function getWeaknessDescriptionAttribute(): ?string { return $this->deskripsi_kelemahan; }
    public function getImprovementActivityAttribute(): ?string { return $this->aktivitas_pengembangan; }
    public function getTrainingRecommendationsAttribute(): ?string { return $this->rekomendasi_pelatihan; }

    public function questions(): HasMany
    {
        return $this->hasMany(TalentCompetencyQuestion::class, 'kode_kompetensi', 'kode_kompetensi');
    }
}
