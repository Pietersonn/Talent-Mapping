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

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) $model->id = $model->generateNextId();
        });
    }

    public function generateNextId(): string
    {
        $lastId = static::orderBy('id', 'desc')->value('id');
        if (!$lastId) return 'SJ001';
        return 'SJ' . str_pad((int)substr($lastId, 2) + 1, 3, '0', STR_PAD_LEFT);
    }

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

    public function getQuestionPreviewAttribute(): string
    {
        return strlen($this->teks_pertanyaan) > 100
            ? substr($this->teks_pertanyaan, 0, 100).'...'
            : $this->teks_pertanyaan;
    }

    public function getCompetencyNameAttribute(): string
    {
        return $this->competencyDescription?->nama_kompetensi ?? $this->kode_kompetensi;
    }
}
