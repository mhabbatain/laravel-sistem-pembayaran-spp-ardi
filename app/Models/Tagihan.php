<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'tanggal_tagihan',
        'status',
        'total_tagihan',
        'jumlah_bayar',
        'sisa_tagihan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_tagihan' => 'date',
        'total_tagihan' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function detailTagihan()
    {
        return $this->hasMany(DetailTagihan::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    /**
     * Get total tagihan hanya untuk komponen yang dipilih (is_selected = true)
     */
    public function getTotalTagihanTerpilihAttribute()
    {
        return $this->detailTagihan->where('is_selected', true)->sum('jumlah');
    }

    /**
     * Get jumlah bayar hanya untuk komponen yang dipilih
     * Dihitung dari pembayaran yang sudah dikonfirmasi
     */
    public function getJumlahBayarTerpilihAttribute()
    {
        // Sum dari pembayaran yang sudah dikonfirmasi
        $totalDikonfirmasi = $this->pembayaran()
            ->where('status_konfirmasi', 'dikonfirmasi')
            ->sum('jumlah_bayar');
        
        return $totalDikonfirmasi;
    }

    /**
     * Get sisa tagihan hanya untuk komponen yang dipilih
     * Sisa tidak boleh negatif (minimum 0)
     */
    public function getSisaTagihanTerpilihAttribute()
    {
        $sisa = $this->total_tagihan_terpilih - $this->jumlah_bayar_terpilih;
        return max(0, $sisa);
    }
}
