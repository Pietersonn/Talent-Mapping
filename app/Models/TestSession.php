<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PHPDoc ini ditambahkan agar ekstensi PHP/Intelephense di VS Code
 * mengenali properti database tanpa memunculkan error PHP0416
 * * @property string $id
 * @property int $id_pengguna
 * @property int|null $id_program
 * @property string $token_sesi
 * @property string $nama_peserta
 * @property string|null $tempat_kerja
 * @property string|null $jabatan
 * @property string $langkah_saat_ini
 * @property bool $selesai
 * @property \Illuminate\Support\Carbon|null $diselesaikan_pada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * * @property-read \App\Models\User $user
 * @property-read \App\Models\Program|null $program
 */
class TestSession extends Model
{
    use HasFactory, HasCustomId;

    // Arahkan ke tabel Bahasa Indonesia
    protected $table = 'sesi_tes';

    public $incrementing = false;
    protected $keyType = 'string';

    // Sesuaikan fillable dengan nama kolom baru
    protected $fillable = [
        'id_pengguna',
        'id_program',
        'token_sesi',
        'nama_peserta',
        'latar_belakang',
        'jabatan',
        'langkah_saat_ini',
        'selesai',
        'diselesaikan_pada',
    ];

    protected $casts = [
        'selesai' => 'boolean',
        'diselesaikan_pada' => 'datetime',
    ];

    // --- Relasi (Tentukan Foreign Key Secara Eksplisit) ---

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function program()
    {
        // Menggantikan relasi Event lama
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function st30Responses()
    {
        return $this->hasMany(ST30Response::class, 'id_sesi');
    }

    public function tkResponses()
    {
        // Menggantikan SJT Responses lama
        return $this->hasMany(TalentCompetencyResponse::class, 'id_sesi');
    }

    public function testResult()
    {
        return $this->hasOne(TestResult::class, 'id_sesi');
    }
}
