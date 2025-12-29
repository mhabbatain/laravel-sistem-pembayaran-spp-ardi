<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'wali_murid_id',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'jumlah_bayar',
        'bukti_pembayaran',
        'nama_bank_pengirim',
        'pemilik_rekening_pengirim',
        'nomor_rekening_pengirim',
        'rekening_tujuan_id',
        'status_konfirmasi',
        'tanggal_konfirmasi',
        'dikonfirmasi_oleh',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'tanggal_konfirmasi' => 'date',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function waliMurid()
    {
        return $this->belongsTo(WaliMurid::class);
    }

    public function rekeningTujuan()
    {
        return $this->belongsTo(RekeningSekolah::class, 'rekening_tujuan_id');
    }

    // Alias untuk kompatibilitas
    public function rekeningSekolah()
    {
        return $this->belongsTo(RekeningSekolah::class, 'rekening_tujuan_id');
    }

    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

    public function scopePending($query)
    {
        return $query->where('status_konfirmasi', 'pending');
    }

    public function scopeDikonfirmasi($query)
    {
        return $query->where('status_konfirmasi', 'dikonfirmasi');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_konfirmasi', 'ditolak');
    }
}
