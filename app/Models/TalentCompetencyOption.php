<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentCompetencyOption extends Model
{
    use HasFactory;

    protected $table = 'pilihan_tk';

    protected $fillable = ['id_soal', 'huruf_pilihan', 'teks_pilihan', 'skor', 'target_kompetensi', 'aktif'];
    protected $casts = ['aktif' => 'boolean', 'skor' => 'integer'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(TalentCompetencyQuestion::class, 'id_soal');
    }
}
