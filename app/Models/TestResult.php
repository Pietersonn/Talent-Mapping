<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestResult extends Model
{
    use HasFactory, HasCustomId;

    // Menunjuk nama tabel bahasa Indonesia yang baru
    protected $table = 'hasil_tes';

    // Menentukan jenis primary key berupa String ID (TR001, dst.) agar tidak dibulatkan ke angka 0
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected string $customIdPrefix = 'TR';
    protected int $customIdLength = 3;

    protected $fillable = [
        'id_sesi',
        'hasil_st30',
        'hasil_tk',
        'tipologi_dominan',
        'laporan_dibuat_pada',
        'email_sent_at', // PERBAIKAN: Sesuai nama kolom index hasil_tes baru
        'pdf_path',      // PERBAIKAN: Kolom database baru menggunakan pdf_path, bukan path_pdf
    ];

    protected $casts = [
        'hasil_st30'          => 'array',
        'hasil_tk'            => 'array',
        'laporan_dibuat_pada' => 'datetime',
        'email_sent_at'       => 'datetime', // PERBAIKAN: Sinkron dengan database
    ];

    /**
     * Relasi ke Sesi Tes (Menggunakan Foreign Key id_sesi secara eksplisit)
     */
    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'id_sesi', 'id');
    }

    /**
     * Relasi Alias ke Sesi Tes agar rute atau fungsi lama tidak broken
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'id_sesi', 'id');
    }

    /**
     * Relasi ke Riwayat Permintaan Kirim Ulang Hasil (Resend Request)
     */
    public function resendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class, 'id_hasil_tes', 'id');
    }

    /**
     * Relasi ke Deskripsi Tipologi Dominan
     */
    public function dominantTypologyDescription(): BelongsTo
    {
        return $this->belongsTo(TypologyDescription::class, 'tipologi_dominan', 'kode_tipologi');
    }

    // ===== Helpers =====

    /**
     * Mengecek apakah file laporan PDF tersedia secara fisik di storage
     */
    public function hasPdfReport(): bool
    {
        return !empty($this->pdf_path) && file_exists(storage_path('app/' . $this->pdf_path));
    }

    /**
     * Mengecek apakah laporan hasil tes sudah pernah dikirimkan lewat email
     */
    public function isEmailSent(): bool
    {
        return !is_null($this->email_sent_at);
    }

    /**
     * Accessor untuk mendapatkan nama program asal secara dinamis
     * PERBAIKAN UTAMA: Mengubah relasi 'event->nama' menjadi 'program->nama' sesuai model baru
     */
    public function getEventTitleAttribute(): string
    {
        return $this->testSession?->program?->nama ?? 'Hasil Mandiri';
    }

    // ===== Scopes =====
    public function scopeEmailSent($q)   { return $q->whereNotNull('email_sent_at'); }
    public function scopeEmailPending($q){ return $q->whereNull('email_sent_at'); }
    public function scopeWithPdf($q)     { return $q->whereNotNull('pdf_path'); }
    public function scopeByTypology($q, string $code) { return $q->where('tipologi_dominan', $code); }
}
