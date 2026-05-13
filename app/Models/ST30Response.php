<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ST30Response extends Model
{
    // Arahkan ke nama tabel baru
    protected $table = 'jawaban_st30';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_sesi',
        'id_versi_soal',
        'nomor_tahap', // PASTIKAN nomor_tahap ADA DI SINI
        'item_dipilih',
        'untuk_penilaian',
    ];

    protected $casts = [
        'item_dipilih' => 'array',
        'untuk_penilaian' => 'boolean',
    ];
}
