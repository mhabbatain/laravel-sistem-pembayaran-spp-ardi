<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiGateway extends Model
{
    use HasFactory;

    protected $table = 'transaksi_gateway';

    protected $fillable = [
        'pembayaran_id',
        'kode_transaksi',
        'metode_pembayaran_id',
        'jumlah',
        'status',
        'gateway_response',
        'authorized_at',
        'settled_at',
        'expired_at',
        'token',
        'failure_reason',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'gateway_response' => 'array',
        'authorized_at' => 'datetime',
        'settled_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Relasi ke Pembayaran
     */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    /**
     * Relasi ke Metode Pembayaran
     */
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    /**
     * Scope untuk transaksi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk transaksi yang sudah settled
     */
    public function scopeSettled($query)
    {
        return $query->where('status', 'settled');
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at);
    }

    /**
     * Check if transaction can be processed
     */
    public function canProcess(): bool
    {
        return in_array($this->status, ['pending', 'processing']) && !$this->isExpired();
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Sedang Diproses',
            'authorized' => 'Terotorisasi',
            'settled' => 'Selesai',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            default => $this->status,
        };
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'authorized' => 'indigo',
            'settled' => 'green',
            'failed' => 'red',
            'expired' => 'gray',
            default => 'gray',
        };
    }
}
