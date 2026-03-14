<?php
namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ST30Question extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'soal_st30';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected string $customIdPrefix = 'ST';

    protected $fillable = ['id_versi', 'nomor', 'pernyataan', 'kode_tipologi', 'aktif'];
    protected $casts = ['nomor' => 'integer', 'aktif' => 'boolean'];

    public function generateCustomId(): string
    {
        $lastId = static::where('id', 'like', $this->customIdPrefix.'%')->orderBy('id', 'desc')->value('id');
        if (!$lastId) return $this->customIdPrefix.'001';
        return $this->customIdPrefix . str_pad((int)substr($lastId, strlen($this->customIdPrefix)) + 1, 3, '0', STR_PAD_LEFT);
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
