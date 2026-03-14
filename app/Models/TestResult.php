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

    protected $table = 'hasil_tes';
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
        'email_terkirim_pada',
        'path_pdf',
    ];

    protected $casts = [
        'hasil_st30'          => 'array',
        'hasil_tk'            => 'array',
        'laporan_dibuat_pada' => 'datetime',
        'email_terkirim_pada' => 'datetime',
    ];

    // Alias untuk kode lama
    public function getSessionIdAttribute(): string         { return $this->id_sesi; }
    public function getSt30ResultsAttribute(): ?array       { return $this->hasil_st30; }
    public function getSjtResultsAttribute(): ?array        { return $this->hasil_tk; }
    public function getDominantTypologyAttribute(): ?string { return $this->tipologi_dominan; }
    public function getReportGeneratedAtAttribute()         { return $this->laporan_dibuat_pada; }
    public function getEmailSentAtAttribute()               { return $this->email_terkirim_pada; }
    public function getPdfPathAttribute(): ?string          { return $this->path_pdf; }

    // ===== Relations =====
    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'id_sesi', 'id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'id_sesi', 'id');
    }

    public function resendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class, 'id_hasil_tes', 'id');
    }

    public function dominantTypologyDescription(): BelongsTo
    {
        return $this->belongsTo(TypologyDescription::class, 'tipologi_dominan', 'kode_tipologi');
    }

    // ===== Helpers =====
    public function hasPdfReport(): bool
    {
        return !empty($this->path_pdf) && file_exists(storage_path('app/' . $this->path_pdf));
    }

    public function isEmailSent(): bool
    {
        return !is_null($this->email_terkirim_pada);
    }

    public function getEventTitleAttribute(): string
    {
        return $this->testSession?->event?->nama ?? '-';
    }

    // ===== Scopes =====
    public function scopeEmailSent($q)   { return $q->whereNotNull('email_terkirim_pada'); }
    public function scopeEmailPending($q){ return $q->whereNull('email_terkirim_pada'); }
    public function scopeWithPdf($q)     { return $q->whereNotNull('path_pdf'); }
    public function scopeByTypology($q, string $code) { return $q->where('tipologi_dominan', $code); }
}
