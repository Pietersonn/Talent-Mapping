<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentCompetencyOption extends Model
{
    use HasFactory;

    protected $table = 'pilihan_tk';

    protected $fillable = [
        'id_soal',
        'huruf_pilihan',
        'teks_pilihan',
        'skor',
        'target_kompetensi',
        'aktif',
    ];

    protected $casts = ['aktif' => 'boolean', 'skor' => 'integer'];

    // Alias untuk kode lama
    public function getQuestionIdAttribute(): string    { return $this->id_soal; }
    public function getOptionLetterAttribute(): string  { return $this->huruf_pilihan; }
    public function getOptionTextAttribute(): string    { return $this->teks_pilihan; }
    public function getScoreAttribute(): int            { return $this->skor; }
    public function getCompetencyTargetAttribute(): string { return $this->target_kompetensi; }
    public function getIsActiveAttribute(): bool        { return $this->aktif; }

    public function question(): BelongsTo
    {
        return $this->belongsTo(TalentCompetencyQuestion::class, 'id_soal');
    }
}
