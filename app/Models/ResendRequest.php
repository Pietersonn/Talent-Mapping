<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResendRequest extends Model
{
    use HasFactory;

    protected $table = 'permintaan_kirim_ulang';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_pengguna', 'id_hasil_tes', 'status', 'disetujui_oleh',
        'disetujui_pada', 'alasan_penolakan', 'catatan_admin', 'tanggal_permintaan',
    ];
    protected $casts = ['disetujui_pada' => 'datetime', 'tanggal_permintaan' => 'datetime'];

    public function user()           { return $this->belongsTo(User::class, 'id_pengguna'); }
    public function testResult()     { return $this->belongsTo(TestResult::class, 'id_hasil_tes', 'id'); }
    public function approvedBy()     { return $this->belongsTo(User::class, 'disetujui_oleh'); }

    protected static function booted()
    {
        static::creating(function (self $model) {
            if (empty($model->id)) {
                for ($i = 1; $i <= 999; $i++) {
                    $id = 'RR'.str_pad((string)$i, 3, '0', STR_PAD_LEFT);
                    if (!self::whereKey($id)->exists()) { $model->id = $id; break; }
                }
            }
        });
    }
}
