<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResendRequest extends Model
{
    use HasFactory, HasCustomId;

    // Menunjuk nama tabel bahasa Indonesia yang baru
    protected $table = 'permintaan_kirim_ulang';

    // Mengunci pengaturan primary key berbasis teks/string agar id tidak diubah menjadi angka 0
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    // Menentukan aturan struktur ID kustom untuk permintaan kirim ulang hasil
    protected string $customIdPrefix = 'RR';
    protected int $customIdLength = 3;

    protected $fillable = [
        'id',
        'id_pengguna',
        'id_hasil_tes',
        'tanggal_permintaan',
        'status',
        'admin_notes',      // Menyimpan catatan dari peserta
        'catatan_admin',    // Menyimpan catatan internal dari admin pembawa persetujuan
        'alasan_penolakan',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected $casts = [
        'tanggal_permintaan' => 'datetime',
        'disetujui_pada'     => 'datetime',
    ];

    /**
     * Relasi ke data Peserta yang meminta kirim ulang hasil
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }

    /**
     * Relasi ke data Hasil Tes target yang diminta kirim ulang
     */
    public function testResult(): BelongsTo
    {
        return $this->belongsTo(TestResult::class, 'id_hasil_tes', 'id');
    }

    /**
     * PERBAIKAN UTAMA: Relasi untuk Admin yang memproses data persetujuan/penolakan
     * Menghubungkan kolom 'disetujui_oleh' ke primary key id milik model User (tabel pengguna)
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id');
    }
}
