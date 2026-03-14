<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentCompetencyResponse extends Model
{
    protected $table = 'jawaban_tk';

    protected $primaryKey = 'id';
    public $incrementing  = true;
    protected $keyType    = 'int';
    public $timestamps    = false;

    protected $fillable = [
        'id_sesi',           // string: TS503
        'id_soal',           // string: SJ101
        'id_versi_soal',     // string: TKV01
        'nomor_halaman',     // int: 1..5
        'pilihan_dipilih',   // enum: a|b|c|d|e
        'waktu_respons',     // int|null (detik)
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id_sesi'        => 'string',
        'id_soal'        => 'string',
        'id_versi_soal'  => 'string',
        'nomor_halaman'  => 'int',
        'waktu_respons'  => 'int',
    ];

    // ===== Relationships =====

    public function question(): BelongsTo
    {
        return $this->belongsTo(TalentCompetencyQuestion::class, 'id_soal', 'id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'id_sesi', 'id');
    }

    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'id_versi_soal', 'id');
    }

    // ===== Scopes =====

    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('id_sesi', $sessionId);
    }

    public function scopeForQuestion($query, string $questionId)
    {
        return $query->where('id_soal', $questionId);
    }

    public function scopeForSessionAndQuestion($query, string $sessionId, string $questionId)
    {
        return $query->where('id_sesi', $sessionId)
                     ->where('id_soal', $questionId);
    }

    // ===== Upsert Helper =====

    /**
     * Upsert jawaban TK untuk satu soal dalam satu sesi.
     */
    public static function upsertAnswer(
        string $sessionId,
        string $questionId,
        string $versionId,
        int    $pageNumber,
        string $selectedOption,
        ?int   $responseTime = null
    ): self {
        return static::updateOrCreate(
            [
                'id_sesi'  => $sessionId,
                'id_soal'  => $questionId,
            ],
            [
                'id_versi_soal'  => $versionId,
                'nomor_halaman'  => $pageNumber,
                'pilihan_dipilih'=> $selectedOption,
                'waktu_respons'  => $responseTime,
            ]
        );
    }

    // ===== Alias kolom lama → baru (kompatibilitas) =====
    public function getSessionIdAttribute(): string         { return $this->id_sesi; }
    public function getQuestionIdAttribute(): string        { return $this->id_soal; }
    public function getQuestionVersionIdAttribute(): string { return $this->id_versi_soal; }
    public function getPageNumberAttribute(): int           { return $this->nomor_halaman; }
    public function getSelectedOptionAttribute(): string    { return $this->pilihan_dipilih; }
    public function getResponseTimeAttribute(): ?int        { return $this->waktu_respons; }
}
