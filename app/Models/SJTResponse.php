<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SJTResponse extends Model
{
    protected $table = 'sjt_responses';

    // PK integer auto-increment
    protected $primaryKey = 'id';
    public $incrementing  = true;
    protected $keyType    = 'int';

    // Tabel ini tanpa created_at / updated_at
    public $timestamps = false;

    // Jangan masukkan 'id' ke fillable
    protected $fillable = [
        'session_id',            // string: TS503
        'question_id',           // string: SJ101
        'question_version_id',   // string: SJV01
        'page_number',           // int: 1..5
        'selected_option',       // enum: a|b|c|d|e
        'response_time',         // int|null (detik)
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'session_id'           => 'string',
        'question_id'          => 'string',
        'question_version_id'  => 'string',
        'page_number'          => 'int',
        'response_time'        => 'int',
    ];

    /**
     * Relasi ke pertanyaan SJT.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SJTQuestion::class, 'question_id', 'id');
    }

    /**
     * Relasi ke session tes (jika ada model TestSession).
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'session_id', 'id');
    }

    /**
     * Relasi ke versi pertanyaan (jika ada model QuestionVersion).
     */
    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'question_version_id', 'id');
    }

    /* ----------------------- Helper Scopes ----------------------- */

    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForQuestion($query, string $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    public function scopeForSessionAndQuestion($query, string $sessionId, string $questionId)
    {
        return $query->where('session_id', $sessionId)
                     ->where('question_id', $questionId);
    }

    /* --------------------- Upsert Convenience -------------------- */

    /**
     * Upsert jawaban SJT satu soal untuk satu sesi.
     */
    public static function upsertAnswer(
        string $sessionId,
        string $questionId,
        string $versionId,
        int $pageNumber,
        string $selectedOption,
        ?int $responseTime = null
    ): self {
        return static::updateOrCreate(
            [
                'session_id'  => $sessionId,
                'question_id' => $questionId,
            ],
            [
                'question_version_id' => $versionId,
                'page_number'         => $pageNumber,
                'selected_option'     => $selectedOption,
                'response_time'       => $responseTime,
            ]
        );
    }
}
