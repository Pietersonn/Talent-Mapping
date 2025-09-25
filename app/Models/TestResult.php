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

    protected $table = 'test_results';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'session_id',
        'st30_results',
        'sjt_results',
        'dominant_typology',
        'report_generated_at',
        'email_sent_at',
        'pdf_path',
    ];

    protected $casts = [
        'st30_results'        => 'array',
        'sjt_results'         => 'array',
        'report_generated_at' => 'datetime',
        'email_sent_at'       => 'datetime',
    ];

    /** ---------- Custom ID (TR001) ---------- */
    protected $customIdPrefix = 'TR';

    public function generateCustomId(): string
    {
        $last = static::where('id', 'like', $this->customIdPrefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return $this->customIdPrefix.'001';
        }

        $num = (int) substr($last->id, strlen($this->customIdPrefix));
        return $this->customIdPrefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

    // =======================
    // RELATIONS
    // =======================

    /** Nama relasi utama yang dianjurkan */
    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'session_id', 'id');
    }

    /**
     * ALIAS untuk kompatibilitas kode lama:
     * Mengatasi error "undefined relationship [session]" tanpa ubah controller/blade.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'session_id', 'id');
    }

    /** Relasi ke permintaan kirim ulang (jika dipakai di User Management) */
    public function resendRequests(): HasMany
    {
        return $this->hasMany(ResendRequest::class, 'test_result_id', 'id');
    }

    /** (Opsional) Metadata tipologi dominan */
    public function dominantTypologyDescription(): BelongsTo
    {
        return $this->belongsTo(
            TypologyDescription::class,
            'dominant_typology',
            'typology_code'
        );
    }

    // =======================
    // HELPERS / ACCESSORS
    // =======================

    public function hasPdfReport(): bool
    {
        return !empty($this->pdf_path) && file_exists(storage_path('app/' . $this->pdf_path));
    }

    public function isEmailSent(): bool
    {
        return !is_null($this->email_sent_at);
    }

    public function getSt30StrengthsAttribute(): array
    {
        return $this->st30_results['strengths'] ?? [];
    }

    public function getSt30DevelopmentAreasAttribute(): array
    {
        return $this->st30_results['development_areas'] ?? [];
    }

    public function getSjtStrengthsAttribute(): array
    {
        return $this->sjt_results['strengths'] ?? [];
    }

    public function getSjtDevelopmentAreasAttribute(): array
    {
        return $this->sjt_results['development_areas'] ?? [];
    }

    public function getSummaryAttribute(): array
    {
        $st = $this->st30_strengths;
        $sj = $this->sjt_strengths;

        return [
            'dominant_typology'    => $st[0] ?? null,
            'top_competency'       => $sj[0] ?? null,
            'total_st30_responses' => $this->st30_results['total_responses'] ?? 0,
            'total_sjt_questions'  => $this->sjt_results['total_questions'] ?? 0,
        ];
    }

    // =======================
    // SCOPES
    // =======================

    public function scopeEmailSent($q)    { return $q->whereNotNull('email_sent_at'); }
    public function scopeEmailPending($q) { return $q->whereNull('email_sent_at'); }
    public function scopeWithPdf($q)      { return $q->whereNotNull('pdf_path'); }

    public function scopeByTypology($q, string $code)
    {
        return $q->where('dominant_typology', $code);
    }
}
