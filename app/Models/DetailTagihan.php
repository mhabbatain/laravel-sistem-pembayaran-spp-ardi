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
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
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
