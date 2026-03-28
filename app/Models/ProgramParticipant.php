<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot; // Gunakan Pivot jika ini tabel perantara (M to M)

class ProgramParticipant extends Pivot
{
    // Sesuaikan dengan nama tabel di database Anda (terlihat di model Program & User)
    protected $table = 'peserta_program';

    // Jika Anda butuh melakukan update/insert tanpa created_at & updated_at, set ke false
    // public $timestamps = true;
}
