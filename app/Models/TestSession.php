<?php
namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TestSession extends Model
{
    use HasCustomId;

    protected $table = 'sesi_tes';
    protected $keyType = 'string';
    public $incrementing = false;
    protected string $customIdPrefix = 'TS';
    protected int $customIdLength = 3;

    protected $fillable = [
        'id_pengguna', 'id_program', 'token_sesi', 'langkah_saat_ini',
        'id_versi_st30', 'nama_peserta', 'latar_belakang', 'jabatan',
        'selesai', 'selesai_pada',
    ];

    protected $casts = [
        'selesai'      => 'boolean',
        'selesai_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function Program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function st30Responses(): HasMany
    {
        return $this->hasMany(ST30Response::class, 'id_sesi', 'id');
    }

    public function talentCompetencyResponses(): HasMany
    {
        return $this->hasMany(TalentCompetencyResponse::class, 'id_sesi', 'id');
    }

    public function testResult(): HasOne
    {
        return $this->hasOne(TestResult::class, 'id_sesi', 'id');
    }

    public function getProgressDisplayAttribute(): string
    {
        $step = $this->langkah_saat_ini;
        if (str_starts_with($step, 'st30')) return 'ST-30 Assessment';
        if (str_starts_with($step, 'sjt'))  return 'TK Assessment';
        if ($this->selesai)                 return 'Assessment Selesai';
        return 'Belum dimulai';
    }
}
