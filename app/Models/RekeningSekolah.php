<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningSekolah extends Model
{
    use HasFactory;

    protected $table = 'rekening_sekolah';

    protected $fillable = [
        'kode_transfer',
        'nama_bank',
        'pemilik_rekening',
        'nomor_rekening',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'rekening_tujuan_id');
    }
}
