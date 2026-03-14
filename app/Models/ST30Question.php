<?php
// ============================================================
// ST30Question.php  →  tabel: soal_st30
// ============================================================
namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ST30Question extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'soal_st30';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_versi', 'nomor', 'pernyataan', 'kode_tipologi', 'aktif'];
    protected $casts = ['nomor' => 'integer', 'aktif' => 'boolean'];
    protected string $customIdPrefix = 'ST';

    // Alias untuk kode lama
    public function getVersionIdAttribute(): string  { return $this->id_versi; }
    public function getNumberAttribute(): int        { return $this->nomor; }
    public function getStatementAttribute(): string  { return $this->pernyataan; }
    public function getTypologyCodeAttribute(): string { return $this->kode_tipologi; }
    public function getIsActiveAttribute(): bool     { return $this->aktif; }

    public function generateCustomId(): string
    {
        $lastId = static::where('id', 'like', $this->customIdPrefix . '%')->orderBy('id', 'desc')->value('id');
        if (!$lastId) return $this->customIdPrefix . '001';
        $lastNumber = (int) substr($lastId, strlen($this->customIdPrefix));
        return $this->customIdPrefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function questionVersion(): BelongsTo
    {
        return $this->belongsTo(QuestionVersion::class, 'id_versi');
    }

    public function typologyDescription(): BelongsTo
    {
        return $this->belongsTo(TypologyDescription::class, 'kode_tipologi', 'kode_tipologi');
    }
}
