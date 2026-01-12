<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'wali_murid_id',
        'nisn',
        'nama',
        'jenis_kelamin',
        'jurusan',
        'alamat',
        'kelas',
        'biaya_spp',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'biaya_spp' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function waliMurid()
    {
        return $this->belongsTo(WaliMurid::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
