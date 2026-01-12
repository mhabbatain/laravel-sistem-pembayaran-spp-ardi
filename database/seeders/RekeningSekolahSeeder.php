<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekeningSekolah;

class RekeningSekolahSeeder extends Seeder
{
    public function run(): void
    {
        $rekening = [
            [
                'kode_transfer' => 'MANDIRI001',
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '1370012345678',
                'pemilik_rekening' => 'Pondok Pesantren Darul Jalal',
                'is_active' => true,
            ],
            [
                'kode_transfer' => 'BRI001',
                'nama_bank' => 'Bank BRI',
                'nomor_rekening' => '0123-01-012345-50-9',
                'pemilik_rekening' => 'Yayasan Darul Jalal',
                'is_active' => true,
            ],
            [
                'kode_transfer' => 'BCA001',
                'nama_bank' => 'Bank BCA',
                'nomor_rekening' => '5431234567',
                'pemilik_rekening' => 'Pondok Pesantren Darul Jalal',
                'is_active' => false,
            ],
        ];

        foreach ($rekening as $item) {
            RekeningSekolah::create($item);
        }
    }
}
