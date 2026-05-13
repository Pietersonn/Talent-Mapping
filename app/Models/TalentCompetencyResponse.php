<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentCompetencyResponse extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel Bahasa Indonesia
    protected $table = 'jawaban_tk';

    // Set false karena ID dibuat secara custom (string), bukan Auto Increment
    public $incrementing = false;
    protected $keyType = 'string';

    // Daftarkan semua kolom yang diizinkan untuk diisi melalui form/controller
    protected $fillable = [
        'id',
        'id_sesi',
        'id_soal',
        'id_versi_soal', // PASTIKAN INI ADA
        'nomor_halaman',
        'pilihan_dipilih',
        'created_at',
        'updated_at',
    ];

    // --- Relasi Tabel ---

    /**
     * Relasi kembali ke sesi tes
     */
    public function session()
    {
        return $this->belongsTo(TestSession::class, 'id_sesi');
    }

    /**
     * Relasi ke master soal TK
     */
    public function question()
    {
        return $this->belongsTo(TalentCompetencyQuestion::class, 'id_soal');
    }

    /**
     * Relasi ke master versi soal
     */
    public function version()
    {
        return $this->belongsTo(QuestionVersion::class, 'id_versi_soal');
    }
}
