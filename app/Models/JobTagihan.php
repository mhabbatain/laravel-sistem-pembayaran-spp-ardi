<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTagihan extends Model
{
    use HasFactory;

    protected $table = 'job_tagihan';

    protected $fillable = [
        'modul_job',
        'progres',
        'total',
        'status',
        'deskripsi',
        'bulan',
        'tahun',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total == 0) {
            return 0;
        }
        return round(($this->progres / $this->total) * 100, 2);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }
}
