<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodePembayaran = [
            // Bank Transfer
            [
                'nama' => 'Bank Mandiri',
                'kode' => 'mandiri',
                'kategori' => 'bank_transfer',
                'logo' => 'mandiri.png',
                'nomor_rekening' => '1370012345678',
                'nama_pemilik' => 'Pondok Pesantren Darul Jalal',
                'instruksi' => "1. Login ke aplikasi Livin' by Mandiri atau Internet Banking\n2. Pilih menu Transfer\n3. Masukkan nomor rekening tujuan\n4. Masukkan nominal sesuai tagihan\n5. Konfirmasi dan simpan bukti transfer",
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'Bank BCA',
                'kode' => 'bca',
                'kategori' => 'bank_transfer',
                'logo' => 'bca.png',
                'nomor_rekening' => '5431234567',
                'nama_pemilik' => 'Pondok Pesantren Darul Jalal',
                'instruksi' => "1. Login ke aplikasi BCA Mobile atau KlikBCA\n2. Pilih menu Transfer\n3. Masukkan nomor rekening tujuan\n4. Masukkan nominal sesuai tagihan\n5. Konfirmasi dan simpan bukti transfer",
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'Bank BSI',
                'kode' => 'bsi',
                'kategori' => 'bank_transfer',
                'logo' => 'bsi.png',
                'nomor_rekening' => '7123456789',
                'nama_pemilik' => 'Pondok Pesantren Darul Jalal',
                'instruksi' => "1. Login ke aplikasi BSI Mobile\n2. Pilih menu Transfer\n3. Masukkan nomor rekening tujuan\n4. Masukkan nominal sesuai tagihan\n5. Konfirmasi dan simpan bukti transfer",
                'is_active' => true,
                'urutan' => 3,
            ],
            [
                'nama' => 'Bank BRI',
                'kode' => 'bri',
                'kategori' => 'bank_transfer',
                'logo' => 'BRI.png',
                'nomor_rekening' => '0123456789012345',
                'nama_pemilik' => 'Pondok Pesantren Darul Jalal',
                'instruksi' => "1. Login ke aplikasi BRImo atau Internet Banking BRI\n2. Pilih menu Transfer\n3. Masukkan nomor rekening tujuan\n4. Masukkan nominal sesuai tagihan\n5. Konfirmasi dan simpan bukti transfer",
                'is_active' => true,
                'urutan' => 4,
            ],
            
            // E-Wallet
            [
                'nama' => 'GoPay',
                'kode' => 'gopay',
                'kategori' => 'e_wallet',
                'logo' => 'gopay.png',
                'nomor_rekening' => '081234567890',
                'nama_pemilik' => 'Pondok Pesantren Darul Jalal',
                'instruksi' => "1. Buka aplikasi Gojek\n2. Pilih menu GoPay\n3. Pilih Transfer ke rekening/nomor\n4. Masukkan nomor tujuan\n5. Masukkan nominal dan konfirmasi",
                'is_active' => true,
                'urutan' => 4,
            ],
            [
                'nama' => 'DANA',
                'kode' => 'dana',
                'kategori' => 'e_wallet',
                'logo' => 'dana.png',
                'nomor_rekening' => '081234567891',
                'nama_pemilik' => 'Pondok Pesantren Darul Jalal',
                'instruksi' => "1. Buka aplikasi DANA\n2. Pilih menu Kirim\n3. Masukkan nomor DANA tujuan\n4. Masukkan nominal dan konfirmasi\n5. Simpan bukti transfer",
                'is_active' => true,
                'urutan' => 5,
            ],
        ];

        foreach ($metodePembayaran as $metode) {
            MetodePembayaran::updateOrCreate(
                ['kode' => $metode['kode']],
                $metode
            );
        }
    }
}
