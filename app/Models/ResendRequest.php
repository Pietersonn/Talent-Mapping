<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResendRequest extends Model
{
    use HasFactory;

    protected $table = 'resend_requests';
    protected $primaryKey = 'id';
    public $incrementing = false;      // ID string (mis. RR001)
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'test_result_id',   // string (contoh: TR701)
        'status',           // pending|approved|rejected
        'approved_by',
        'approved_at',
        'rejection_reason',
        'admin_notes',      // menyimpan alasan/notes dari user
        'request_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'approved_at'  => 'datetime',
        'request_date' => 'datetime',
    ];

    /* ===========================
       RELATIONS
       =========================== */

    // Pengaju
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hasil tes — alias dipakai di beberapa tempat
    public function result()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id', 'id');
    }

    // Alias yang sering dipakai di admin: $resendRequest->testResult
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id', 'id');
    }

    // Admin yang memproses: $resendRequest->approvedBy
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Alias tambahan jika ada kode lama: $resendRequest->approvedByUser / approver
    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ===========================
       ACCESSORS kompat lama
       =========================== */

    // Beberapa view lama menampilkan destination_email -> ambil dari user
    public function getDestinationEmailAttribute(): ?string
    {
        return optional($this->user)->email;
    }

    // Beberapa view lama pakai reason -> map ke admin_notes
    public function getReasonAttribute(): ?string
    {
        return $this->admin_notes;
    }

    /* ===========================
       BOOT: auto-generate ID "RR001"
       =========================== */
    protected static function booted()
    {
        static::creating(function (self $model) {
            if (empty($model->id)) {
                $prefix = 'RR';
                for ($i = 1; $i <= 999; $i++) {
                    $id = $prefix . str_pad((string)$i, 3, '0', STR_PAD_LEFT); // RR001
                    if (! self::whereKey($id)->exists()) {
                        $model->id = $id;
                        break;
                    }
                }
                if (empty($model->id)) {
                    $model->id = substr($prefix . bin2hex(random_bytes(3)), 0, 5);
                }
            }
        });
    }
}
