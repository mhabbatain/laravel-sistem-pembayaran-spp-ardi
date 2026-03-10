<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\TransaksiGateway;
use App\Models\MetodePembayaran;
use App\Models\Pengaturan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    /**
     * Tahap 1 & 2: Inisiasi Pembayaran + Pengiriman Data Transaksi
     * Membuat transaksi gateway baru dengan token unik
     */
    public function createTransaction(Pembayaran $pembayaran, MetodePembayaran $metode): TransaksiGateway
    {
        $kodeTransaksi = 'TRX-' . strtoupper(Str::random(8));
        $token = Str::uuid()->toString();

        $transaksi = TransaksiGateway::create([
            'pembayaran_id' => $pembayaran->id,
            'kode_transaksi' => $kodeTransaksi,
            'metode_pembayaran_id' => $metode->id,
            'jumlah' => $pembayaran->jumlah_bayar,
            'status' => 'pending',
            'token' => $token,
            'expired_at' => now()->addMinutes(15),
            'gateway_response' => [
                'stage' => 'initiated',
                'message' => 'Transaksi berhasil dibuat',
                'merchant' => Pengaturan::where('key', 'nama_sekolah')->first()?->value ?? 'Pondok Pesantren',
                'payment_method' => $metode->nama,
                'payment_category' => $metode->kategori,
                'initiated_at' => now()->toIso8601String(),
                'data_sent_at' => now()->toIso8601String(),
            ],
        ]);

        Log::info('Gateway: Transaction created', [
            'kode_transaksi' => $kodeTransaksi,
            'pembayaran_id' => $pembayaran->id,
            'jumlah' => $pembayaran->jumlah_bayar,
        ]);

        return $transaksi;
    }

    /**
     * Tahap 3: Proses Otorisasi Pembayaran
     * Simulasi verifikasi oleh bank/provider
     */
    public function processAuthorization(TransaksiGateway $transaksi, bool $approved = true): TransaksiGateway
    {
        if (!$transaksi->canProcess()) {
            if ($transaksi->isExpired()) {
                return $this->expireTransaction($transaksi);
            }
            return $transaksi;
        }

        // Update status ke processing
        $transaksi->update([
            'status' => 'processing',
            'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                'stage' => 'authorization',
                'authorization_started_at' => now()->toIso8601String(),
                'message' => 'Memverifikasi ketersediaan dana...',
            ]),
        ]);

        Log::info('Gateway: Authorization processing', [
            'kode_transaksi' => $transaksi->kode_transaksi,
            'approved' => $approved,
        ]);

        if ($approved) {
            // Simulasi otorisasi berhasil
            $transaksi->update([
                'status' => 'authorized',
                'authorized_at' => now(),
                'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                    'stage' => 'authorized',
                    'authorization_code' => 'AUTH-' . strtoupper(Str::random(6)),
                    'authorized_at' => now()->toIso8601String(),
                    'message' => 'Pembayaran telah diotorisasi oleh bank',
                    'bank_ref' => 'REF-' . rand(100000, 999999),
                ]),
            ]);

            Log::info('Gateway: Authorization approved', [
                'kode_transaksi' => $transaksi->kode_transaksi,
            ]);
        } else {
            // Simulasi otorisasi ditolak
            $transaksi->update([
                'status' => 'failed',
                'failure_reason' => 'Otorisasi ditolak oleh bank/provider',
                'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                    'stage' => 'failed',
                    'failed_at' => now()->toIso8601String(),
                    'message' => 'Otorisasi ditolak: Saldo tidak mencukupi atau transaksi ditolak',
                    'error_code' => 'DECLINED',
                ]),
            ]);

            // Update pembayaran status
            $transaksi->pembayaran->update([
                'status_konfirmasi' => 'ditolak',
                'tanggal_konfirmasi' => now(),
                'catatan' => 'Ditolak oleh Payment Gateway: Otorisasi ditolak oleh bank/provider',
            ]);

            Log::info('Gateway: Authorization declined', [
                'kode_transaksi' => $transaksi->kode_transaksi,
            ]);
        }

        return $transaksi->fresh();
    }

    /**
     * Tahap 4: Konfirmasi Status Pembayaran
     * Gateway mengirimkan konfirmasi ke merchant
     */
    public function confirmPayment(TransaksiGateway $transaksi): TransaksiGateway
    {
        if ($transaksi->status !== 'authorized') {
            return $transaksi;
        }

        $transaksi->update([
            'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                'stage' => 'confirmed',
                'confirmed_at' => now()->toIso8601String(),
                'message' => 'Pembayaran dikonfirmasi. Menunggu proses settlement.',
                'notification_sent' => true,
            ]),
        ]);

        Log::info('Gateway: Payment confirmed', [
            'kode_transaksi' => $transaksi->kode_transaksi,
        ]);

        return $transaksi->fresh();
    }

    /**
     * Tahap 5: Penyelesaian Transaksi (Settlement)
     * Dana dipindahkan ke rekening merchant, update pembayaran & tagihan
     */
    public function settleTransaction(TransaksiGateway $transaksi): TransaksiGateway
    {
        if ($transaksi->status !== 'authorized') {
            return $transaksi;
        }

        // Update transaksi gateway
        $transaksi->update([
            'status' => 'settled',
            'settled_at' => now(),
            'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                'stage' => 'settled',
                'settled_at' => now()->toIso8601String(),
                'message' => 'Dana telah dicairkan ke rekening merchant',
                'settlement_ref' => 'STL-' . strtoupper(Str::random(8)),
            ]),
        ]);

        // Generate Digital Receipt (SVG/Image)
        $buktiPath = $this->generateDigitalReceipt($transaksi);

        // Update pembayaran - otomatis dikonfirmasi
        $pembayaran = $transaksi->pembayaran;
        $pembayaran->update([
            'status_konfirmasi' => 'dikonfirmasi',
            'tanggal_konfirmasi' => now(),
            'dikonfirmasi_oleh' => 1, // System (Admin ID 1)
            'bukti_pembayaran' => $buktiPath, // Set digital receipt as proof
            'catatan' => 'Otomatis dikonfirmasi via Payment Gateway. Kode: ' . $transaksi->kode_transaksi,
        ]);

        // Update tagihan
        $tagihan = $pembayaran->tagihan;
        $tagihan->jumlah_bayar += (float) $pembayaran->jumlah_bayar;
        $tagihan->sisa_tagihan = (float) $tagihan->total_tagihan - (float) $tagihan->jumlah_bayar;
        if ($tagihan->sisa_tagihan <= 0) {
            $tagihan->status = 'lunas';
        }
        $tagihan->save();

        // Send WhatsApp notification
        try {
            $waService = app(WhatsAppNotificationService::class);
            $notifyPembayaran = Pengaturan::where('key', 'whatsapp_notify_pembayaran')->first()?->value ?? '1';
            if ($notifyPembayaran === '1') {
                $waService->sendPembayaranKonfirmasiNotification($pembayaran);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification for gateway payment', ['error' => $e->getMessage()]);
        }

        Log::info('Gateway: Transaction settled', [
            'kode_transaksi' => $transaksi->kode_transaksi,
            'pembayaran_id' => $pembayaran->id,
            'tagihan_status' => $tagihan->status,
        ]);

        return $transaksi->fresh();
    }

    /**
     * Expire a transaction that has timed out
     */
    public function expireTransaction(TransaksiGateway $transaksi): TransaksiGateway
    {
        $transaksi->update([
            'status' => 'expired',
            'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                'stage' => 'expired',
                'expired_at' => now()->toIso8601String(),
                'message' => 'Transaksi kadaluarsa karena melewati batas waktu pembayaran',
            ]),
        ]);

        // Update pembayaran
        $transaksi->pembayaran->update([
            'status_konfirmasi' => 'ditolak',
            'tanggal_konfirmasi' => now(),
            'catatan' => 'Transaksi kadaluarsa via Payment Gateway. Kode: ' . $transaksi->kode_transaksi,
        ]);

        Log::info('Gateway: Transaction expired', [
            'kode_transaksi' => $transaksi->kode_transaksi,
        ]);

        return $transaksi->fresh();
    }

    /**
     * Full flow: Process all steps from authorization to settlement
     * Used when user approves payment on bank simulator page
     */
    public function processFullFlow(TransaksiGateway $transaksi, bool $approved = true): TransaksiGateway
    {
        // Tahap 3: Otorisasi
        $transaksi = $this->processAuthorization($transaksi, $approved);

        if (!$approved || $transaksi->status === 'failed') {
            return $transaksi;
        }

        // Tahap 4: Konfirmasi
        $transaksi = $this->confirmPayment($transaksi);

        // Tahap 5: Settlement
        $transaksi = $this->settleTransaction($transaksi);

        return $transaksi;
    }

    /**
     * Generate Digital Receipt (SVG format)
     * Returns the storage path of the generated receipt
     */
    protected function generateDigitalReceipt(TransaksiGateway $transaksi): string
    {
        $fileName = 'receipt-' . $transaksi->kode_transaksi . '.svg';
        $directory = 'bukti-transfer';
        $fullPath = storage_path('app/public/' . $directory);
        
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $merchant = $transaksi->gateway_response['merchant'] ?? 'SPP Darul Jalal';
        $amount = 'Rp ' . number_format((float) $transaksi->jumlah, 0, ',', '.');
        $date = $transaksi->settled_at ? $transaksi->settled_at->format('d M Y, H:i:s') : now()->format('d M Y, H:i:s');
        $student = $transaksi->pembayaran?->tagihan?->siswa?->nama ?? '-';
        $nisn = $transaksi->pembayaran?->tagihan?->siswa?->nisn ?? '-';
        $method = $transaksi->metodePembayaran?->nama ?? '-';
        $status = 'PEMBAYARAN BERHASIL';
        $trxId = $transaksi->kode_transaksi;

        // Create a stylized SVG receipt
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="400" height="600" viewBox="0 0 400 600" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="600" rx="16" fill="white"/>
            <rect width="400" height="130" rx="16" fill="#10B981"/>
            <path d="M0 16C0 7.16344 7.16344 0 16 0H384C392.837 0 400 7.16344 400 16V110H0V16Z" fill="#10B981"/>
            <circle cx="200" cy="120" r="40" fill="white" stroke="#10B981" stroke-width="4"/>
            <path d="M185 120L195 130L215 110" stroke="#10B981" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
            <text x="200" y="45" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="white" text-anchor="middle">BUKTI PEMBAYARAN</text>
            <text x="200" y="65" font-family="Arial, sans-serif" font-size="12" font-weight="medium" fill="#D1FAE5" text-anchor="middle">'.$merchant.'</text>
            <text x="200" y="195" font-family="Arial, sans-serif" font-size="14" font-weight="bold" fill="#059669" text-anchor="middle">'.$status.'</text>
            <text x="200" y="235" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="#111827" text-anchor="middle">'.$amount.'</text>
            <line x1="40" y1="260" x2="360" y2="260" stroke="#E5E7EB" stroke-width="1" stroke-dasharray="4 4"/>
            <g font-family="Arial, sans-serif" font-size="12" fill="#6B7280">
                <text x="40" y="290">No. Transaksi</text>
                <text x="360" y="290" text-anchor="end" fill="#111827" font-weight="bold">'.$trxId.'</text>
                <text x="40" y="320">Waktu Pembayaran</text>
                <text x="360" y="320" text-anchor="end" fill="#111827">'.$date.'</text>
                <text x="40" y="350">Metode Pembayaran</text>
                <text x="360" y="350" text-anchor="end" fill="#111827">'.$method.'</text>
                <text x="40" y="380">Status</text>
                <text x="360" y="380" text-anchor="end" fill="#059669" font-weight="bold">LUNAS/SETTLED</text>
            </g>
            <line x1="40" y1="410" x2="360" y2="410" stroke="#E5E7EB" stroke-width="1"/>
            <text x="40" y="440" font-family="Arial, sans-serif" font-size="12" font-weight="bold" fill="#374151">DETAIL SISWA</text>
            <g font-family="Arial, sans-serif" font-size="12" fill="#6B7280">
                <text x="40" y="470">Nama Siswa</text>
                <text x="360" y="470" text-anchor="end" fill="#111827">'.$student.'</text>
                <text x="40" y="500">NISN</text>
                <text x="360" y="500" text-anchor="end" fill="#111827">'.$nisn.'</text>
            </g>
            <rect x="40" y="535" width="320" height="40" rx="8" fill="#F9FAFB"/>
            <text x="200" y="550" font-family="Arial, sans-serif" font-size="10" fill="#9CA3AF" text-anchor="middle">Dokumen ini adalah bukti pembayaran digital yang sah.</text>
            <text x="200" y="565" font-family="Arial, sans-serif" font-size="10" fill="#9CA3AF" text-anchor="middle">Generated by SPP Payment Gateway System.</text>
            <path d="M0 585H400V589C400 595.075 395.075 600 389 600H11C4.92487 600 0 595.075 0 589V585Z" fill="#10B981"/>
        </svg>';

        file_put_contents(storage_path('app/public/' . $directory . '/' . $fileName), $svg);

        return $directory . '/' . $fileName;
    }
}
