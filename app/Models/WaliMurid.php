<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliMurid extends Model
{
    use HasFactory;

    protected $table = 'wali_murid';

    protected $fillable = [
        'user_id',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
