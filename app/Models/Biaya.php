<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;

    protected $table = 'biaya';

    protected $fillable = [
        'nama_biaya',
        'kode',
        'jumlah',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function detailTagihan()
    {
        return $this->hasMany(DetailTagihan::class);
    }
}
