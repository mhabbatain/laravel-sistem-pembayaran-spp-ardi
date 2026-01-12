<?php

namespace App\Services;

use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected WhatsAppService $whatsApp;

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    /**
     * Check if WhatsApp notification is enabled
     */
    public function isEnabled(): bool
    {
        $pengaturan = Pengaturan::where('key', 'whatsapp_enabled')->first();
        return $pengaturan && $pengaturan->value === '1';
    }

    /**
     * Get nama sekolah from settings
     */
    protected function getNamaSekolah(): string
    {
        return Pengaturan::where('key', 'nama_sekolah')->first()?->value ?? 'Pondok Pesantren Darul Jalal';
    }

    /**
     * Format currency
     */
    protected function formatRupiah(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Send notification for new tagihan (billing)
     */
    public function sendTagihanNotification(Tagihan $tagihan): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Notifikasi WhatsApp tidak aktif'];
        }

        $siswa = $tagihan->siswa;
        $waliMurid = $siswa->waliMurid;

        if (!$waliMurid || !$waliMurid->user?->no_hp) {
            Log::warning('WhatsApp notification skipped: No phone number', [
                'tagihan_id' => $tagihan->id,
                'siswa_id' => $siswa->id,
            ]);
            return ['success' => false, 'message' => 'Nomor HP wali murid tidak tersedia'];
        }

        $namaSekolah = $this->getNamaSekolah();
        $message = $this->buildTagihanMessage($tagihan, $siswa, $namaSekolah);

        $result = $this->whatsApp->sendMessage($waliMurid->user->no_hp, $message);

        if ($result['success']) {
            Log::info('Tagihan notification sent', [
                'tagihan_id' => $tagihan->id,
                'phone' => $waliMurid->user->no_hp,
            ]);
        }

        return $result;
    }

    /**
     * Build tagihan message template
     */
    protected function buildTagihanMessage(Tagihan $tagihan, Siswa $siswa, string $namaSekolah): string
    {
        $detailBiaya = '';
        foreach ($tagihan->detailTagihan as $detail) {
            $detailBiaya .= "• {$detail->biaya->nama}: {$this->formatRupiah($detail->jumlah)}\n";
        }

        return "🔔 *TAGIHAN BARU*\n\n" .
            "Assalamu'alaikum Wr. Wb.\n\n" .
            "Bapak/Ibu Wali dari *{$siswa->nama}*,\n\n" .
            "Berikut informasi tagihan SPP:\n\n" .
            "📋 *Detail Tagihan*\n" .
            "━━━━━━━━━━━━━━━━\n" .
            "Periode: *{$tagihan->bulan} {$tagihan->tahun}*\n" .
            "Nama: *{$siswa->nama}*\n" .
            "Kelas: *{$siswa->kelas}*\n\n" .
            "*Rincian Biaya:*\n" .
            $detailBiaya . "\n" .
            "━━━━━━━━━━━━━━━━\n" .
            "*Total: {$this->formatRupiah($tagihan->total_tagihan)}*\n" .
            "━━━━━━━━━━━━━━━━\n\n" .
            "Mohon segera melakukan pembayaran sebelum tanggal jatuh tempo.\n\n" .
            "Terima kasih atas perhatiannya.\n\n" .
            "Wassalamu'alaikum Wr. Wb.\n\n" .
            "_*{$namaSekolah}*_";
    }

    /**
     * Send notification for payment confirmation (success)
     */
    public function sendPembayaranKonfirmasiNotification(Pembayaran $pembayaran): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Notifikasi WhatsApp tidak aktif'];
        }

        $tagihan = $pembayaran->tagihan;
        $siswa = $tagihan->siswa;
        $waliMurid = $siswa->waliMurid;

        if (!$waliMurid || !$waliMurid->user?->no_hp) {
            return ['success' => false, 'message' => 'Nomor HP wali murid tidak tersedia'];
        }

        $namaSekolah = $this->getNamaSekolah();
        $message = $this->buildPembayaranKonfirmasiMessage($pembayaran, $tagihan, $siswa, $namaSekolah);

        $result = $this->whatsApp->sendMessage($waliMurid->user->no_hp, $message);

        if ($result['success']) {
            Log::info('Payment confirmation notification sent', [
                'pembayaran_id' => $pembayaran->id,
                'phone' => $waliMurid->user->no_hp,
            ]);
        }

        return $result;
    }

    /**
     * Build payment confirmation message template
     */
    protected function buildPembayaranKonfirmasiMessage(Pembayaran $pembayaran, Tagihan $tagihan, Siswa $siswa, string $namaSekolah): string
    {
        $statusTagihan = $tagihan->status === 'lunas' ? '✅ LUNAS' : "⏳ Sisa: {$this->formatRupiah($tagihan->sisa_tagihan)}";

        return "✅ *PEMBAYARAN DIKONFIRMASI*\n\n" .
            "Assalamu'alaikum Wr. Wb.\n\n" .
            "Bapak/Ibu Wali dari *{$siswa->nama}*,\n\n" .
            "Pembayaran Anda telah dikonfirmasi.\n\n" .
            "📋 *Detail Pembayaran*\n" .
            "━━━━━━━━━━━━━━━━\n" .
            "No. Transaksi: *#{$pembayaran->id}*\n" .
            "Tanggal: *{$pembayaran->tanggal_bayar->format('d/m/Y')}*\n" .
            "Periode: *{$tagihan->bulan} {$tagihan->tahun}*\n" .
            "Nama: *{$siswa->nama}*\n\n" .
            "*Jumlah Dibayar: {$this->formatRupiah($pembayaran->jumlah)}*\n\n" .
            "Status Tagihan: {$statusTagihan}\n" .
            "━━━━━━━━━━━━━━━━\n\n" .
            "Terima kasih atas pembayarannya.\n\n" .
            "Wassalamu'alaikum Wr. Wb.\n\n" .
            "_*{$namaSekolah}*_";
    }

    /**
     * Send payment reminder notification
     */
    public function sendReminderNotification(Tagihan $tagihan): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Notifikasi WhatsApp tidak aktif'];
        }

        $siswa = $tagihan->siswa;
        $waliMurid = $siswa->waliMurid;

        if (!$waliMurid || !$waliMurid->user?->no_hp) {
            return ['success' => false, 'message' => 'Nomor HP wali murid tidak tersedia'];
        }

        $namaSekolah = $this->getNamaSekolah();
        $message = $this->buildReminderMessage($tagihan, $siswa, $namaSekolah);

        $result = $this->whatsApp->sendMessage($waliMurid->user->no_hp, $message);

        if ($result['success']) {
            Log::info('Reminder notification sent', [
                'tagihan_id' => $tagihan->id,
                'phone' => $waliMurid->user->no_hp,
            ]);
        }

        return $result;
    }

    /**
     * Build reminder message template
     */
    protected function buildReminderMessage(Tagihan $tagihan, Siswa $siswa, string $namaSekolah): string
    {
        return "⏰ *PENGINGAT PEMBAYARAN*\n\n" .
            "Assalamu'alaikum Wr. Wb.\n\n" .
            "Bapak/Ibu Wali dari *{$siswa->nama}*,\n\n" .
            "Ini adalah pengingat bahwa tagihan SPP Anda belum dibayar.\n\n" .
            "📋 *Detail Tagihan*\n" .
            "━━━━━━━━━━━━━━━━\n" .
            "Periode: *{$tagihan->bulan} {$tagihan->tahun}*\n" .
            "Nama: *{$siswa->nama}*\n" .
            "Kelas: *{$siswa->kelas}*\n\n" .
            "*Total Tagihan: {$this->formatRupiah($tagihan->total_tagihan)}*\n" .
            "*Sudah Dibayar: {$this->formatRupiah($tagihan->jumlah_bayar)}*\n" .
            "*Sisa: {$this->formatRupiah($tagihan->sisa_tagihan)}*\n" .
            "━━━━━━━━━━━━━━━━\n\n" .
            "Mohon segera melakukan pembayaran untuk menghindari denda keterlambatan.\n\n" .
            "Terima kasih atas perhatiannya.\n\n" .
            "Wassalamu'alaikum Wr. Wb.\n\n" .
            "_*{$namaSekolah}*_";
    }

    /**
     * Send custom message to a phone number
     */
    public function sendCustomMessage(string $phone, string $message): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Notifikasi WhatsApp tidak aktif'];
        }

        return $this->whatsApp->sendMessage($phone, $message);
    }

    /**
     * Send bulk messages to multiple recipients
     */
    public function sendBulkMessage(array $phones, string $message): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Notifikasi WhatsApp tidak aktif'];
        }

        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($phones as $phone) {
            $result = $this->whatsApp->sendMessage($phone, $message);
            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
            $results['details'][$phone] = $result;

            // Add small delay to avoid rate limiting
            usleep(500000); // 0.5 second
        }

        return $results;
    }
}
