<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentCompetencyResponse extends Model
{
    protected $table = 'jawaban_tk';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['id_sesi', 'id_soal', 'id_versi_soal', 'nomor_halaman', 'pilihan_dipilih', 'waktu_respons'];
    protected $guarded = ['id'];
    protected $casts = ['id_sesi' => 'string', 'id_soal' => 'string', 'id_versi_soal' => 'string', 'nomor_halaman' => 'int', 'waktu_respons' => 'int'];

    public function question(): BelongsTo { return $this->belongsTo(TalentCompetencyQuestion::class, 'id_soal', 'id'); }
    public function session(): BelongsTo  { return $this->belongsTo(TestSession::class, 'id_sesi', 'id'); }
    public function questionVersion(): BelongsTo { return $this->belongsTo(QuestionVersion::class, 'id_versi_soal', 'id'); }

    public function scopeForSession($query, string $sessionId) { return $query->where('id_sesi', $sessionId); }

    public static function upsertAnswer(string $sessionId, string $questionId, string $versionId, int $pageNumber, string $selectedOption, ?int $responseTime = null): self
    {
        return static::updateOrCreate(
            ['id_sesi' => $sessionId, 'id_soal' => $questionId],
            ['id_versi_soal' => $versionId, 'nomor_halaman' => $pageNumber, 'pilihan_dipilih' => $selectedOption, 'waktu_respons' => $responseTime]
        );
    }
}
