<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResendRequest extends Model {
    use HasFactory;
    protected $table = 'permintaan_kirim_ulang';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id','id_pengguna','id_hasil_tes','status','disetujui_oleh','disetujui_pada','alasan_penolakan','catatan_admin','tanggal_permintaan'];
    protected $casts = ['disetujui_pada' => 'datetime','tanggal_permintaan' => 'datetime'];

    // Alias
    public function getUserIdAttribute(): int           { return $this->id_pengguna; }
    public function getTestResultIdAttribute(): string  { return $this->id_hasil_tes; }
    public function getApprovedByAttribute(): ?int      { return $this->disetujui_oleh; }
    public function getApprovedAtAttribute()            { return $this->disetujui_pada; }
    public function getRejectionReasonAttribute(): ?string { return $this->alasan_penolakan; }
    public function getAdminNotesAttribute(): ?string   { return $this->catatan_admin; }
    public function getRequestDateAttribute()           { return $this->tanggal_permintaan; }
    public function getReasonAttribute(): ?string       { return $this->catatan_admin; }
    public function getDestinationEmailAttribute(): ?string { return optional($this->user)->email; }

    public function user()           { return $this->belongsTo(User::class, 'id_pengguna'); }
    public function testResult()     { return $this->belongsTo(TestResult::class, 'id_hasil_tes', 'id'); }
    public function result()         { return $this->belongsTo(TestResult::class, 'id_hasil_tes', 'id'); }
    public function approvedByUser() { return $this->belongsTo(User::class, 'disetujui_oleh'); }
    public function approver()       { return $this->belongsTo(User::class, 'disetujui_oleh'); }

    protected static function booted() {
        static::creating(function (self $model) {
            if (empty($model->id)) {
                $prefix = 'RR';
                for ($i = 1; $i <= 999; $i++) {
                    $id = $prefix . str_pad((string)$i, 3, '0', STR_PAD_LEFT);
                    if (!self::whereKey($id)->exists()) { $model->id = $id; break; }
                }
            }
        });
    }
}
