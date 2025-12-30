<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTagihan extends Model
{
    use HasFactory;

    protected $table = 'detail_tagihan';

    protected $fillable = [
        'tagihan_id',
        'biaya_id',
        'jumlah',
        'jumlah_dibayar',
        'is_selected',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'is_selected' => 'boolean',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }
}
