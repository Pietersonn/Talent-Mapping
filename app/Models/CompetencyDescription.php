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
        'kode_kompetensi', 'nama_kompetensi', 'deskripsi_kekuatan',
        'deskripsi_kelemahan', 'aktivitas_pengembangan', 'rekomendasi_pelatihan',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(TalentCompetencyQuestion::class, 'kode_kompetensi', 'kode_kompetensi');
    }
}
