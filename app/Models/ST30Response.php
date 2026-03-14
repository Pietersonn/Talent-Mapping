<?php
// File: app/Models/ST30Response.php
namespace App\Models;
use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class ST30Response extends Model {
    use HasFactory, HasCustomId;
    protected $table = 'jawaban_st30';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $customIdPrefix = 'STR';

    protected $fillable = ['id_sesi','id_versi_soal','nomor_tahap','item_dipilih','item_dikecualikan','untuk_penilaian','waktu_respons'];
    protected $casts = ['item_dipilih' => 'array','item_dikecualikan' => 'array','untuk_penilaian' => 'boolean','nomor_tahap' => 'integer','waktu_respons' => 'integer'];

    // Alias
    public function getSessionIdAttribute(): string         { return $this->id_sesi; }
    public function getQuestionVersionIdAttribute(): string { return $this->id_versi_soal; }
    public function getStageNumberAttribute(): int          { return $this->nomor_tahap; }
    public function getSelectedItemsAttribute(): ?array     { return $this->getAttributes()['item_dipilih'] ? json_decode($this->getAttributes()['item_dipilih'], true) : null; }
    public function getExcludedItemsAttribute(): ?array     { return $this->getAttributes()['item_dikecualikan'] ? json_decode($this->getAttributes()['item_dikecualikan'], true) : null; }
    public function getForScoringAttribute(): bool          { return $this->untuk_penilaian; }
    public function getResponseTimeAttribute(): ?int        { return $this->waktu_respons; }

    public function generateCustomId(): string {
        $prefix = 'STR';
        $lastId = static::where('id', 'like', $prefix.'%')->orderBy('id', 'desc')->first();
        if (!$lastId) return $prefix . '01';
        $lastNumber = (int) substr($lastId->id, strlen($prefix));
        return $prefix . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
    }

    public function testSession(): BelongsTo { return $this->belongsTo(TestSession::class, 'id_sesi'); }
    public function questionVersion(): BelongsTo { return $this->belongsTo(QuestionVersion::class, 'id_versi_soal'); }
    public function scopeBySession($query, string $sessionId) { return $query->where('id_sesi', $sessionId); }
    public function scopeByStage($query, int $stage)           { return $query->where('nomor_tahap', $stage); }
    public function scopeForScoring($query)                    { return $query->where('untuk_penilaian', true); }

    public static function getSessionResponses(string $sessionId): Collection {
        return static::where('id_sesi', $sessionId)->orderBy('nomor_tahap')->get();
    }
}
