<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'sesi_tes';

    public $incrementing = false;
    protected $keyType = 'string';

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


    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program', 'id');
    }

    public function st30Responses()
    {
        return $this->hasMany(ST30Response::class, 'id_sesi', 'id');
    }

    public function tkResponses()
    {
        return $this->hasMany(TalentCompetencyResponse::class, 'id_sesi', 'id');
    }

    public function testResult()
    {
        return $this->hasOne(TestResult::class, 'id_sesi', 'id');
    }
}
