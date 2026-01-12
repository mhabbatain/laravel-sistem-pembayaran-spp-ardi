<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'nama',
        'kode',
        'kategori',
        'logo',
        'nomor_rekening',
        'nama_pemilik',
        'instruksi',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk metode pembayaran aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kategori tertentu
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get metode pembayaran diurutkan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('pembayaran/' . $this->logo);
        }
        return null;
    }
}
