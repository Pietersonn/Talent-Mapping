<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TypologyDescription extends Model
{
    use HasFactory;

    protected $table = 'deskripsi_tipologi';

    protected $fillable = [
        'kode_tipologi',
        'nama_tipologi',
        'deskripsi_kekuatan',
        'deskripsi_kelemahan',
    ];

    // Alias untuk kode lama
    public function getTypologyCodeAttribute(): string      { return $this->kode_tipologi; }
    public function getTypologyNameAttribute(): string      { return $this->nama_tipologi; }
    public function getStrengthDescriptionAttribute(): ?string { return $this->deskripsi_kekuatan; }
    public function getWeaknessDescriptionAttribute(): ?string { return $this->deskripsi_kelemahan; }

    public function getDisplayNameAttribute(): string
    {
        return $this->nama_tipologi . ' (' . $this->kode_tipologi . ')';
    }

    public function getShortDescriptionAttribute(): string
    {
        return Str::limit($this->deskripsi_kekuatan ?? 'Tidak ada deskripsi', 100);
    }

    public function st30Questions(): HasMany
    {
        return $this->hasMany(ST30Question::class, 'kode_tipologi', 'kode_tipologi');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('nama_tipologi', 'like', "%{$term}%")
            ->orWhere('kode_tipologi', 'like', "%{$term}%");
    }

    public static function getAllCodes(): array
    {
        return static::pluck('kode_tipologi')->toArray();
    }

    public static function getSelectOptions(): array
    {
        return static::orderBy('nama_tipologi')
            ->pluck('nama_tipologi', 'kode_tipologi')
            ->toArray();
    }
}
