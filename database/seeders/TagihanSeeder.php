<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Biaya;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use Carbon\Carbon;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        $biaya = Biaya::all()->keyBy('nama_biaya');
        $siswaAktif = Siswa::where('is_active', true)->get();

        // Buat tagihan untuk 3 bulan terakhir
        $bulanList = [
            ['bulan' => 10, 'tahun' => 2024], // Oktober
            ['bulan' => 11, 'tahun' => 2024], // November
            ['bulan' => 12, 'tahun' => 2024], // Desember
        ];

        foreach ($siswaAktif as $siswa) {
            foreach ($bulanList as $index => $periode) {
                $bulan = $periode['bulan'];
                $tahun = $periode['tahun'];

                // Status tagihan berbeda-beda
                if ($index == 0) {
                    // Oktober - semua lunas
                    $status = 'lunas';
                    $totalDibayar = $biaya['SPP']->jumlah + $biaya['Katering']->jumlah + $biaya['Laundry']->jumlah;
                } elseif ($index == 1) {
                    // November - sebagian lunas, sebagian baru
                    $status = $siswa->id % 2 == 0 ? 'lunas' : 'baru';
                    $totalDibayar = $status == 'lunas'
                        ? $biaya['SPP']->jumlah + $biaya['Katering']->jumlah + $biaya['Laundry']->jumlah
                        : 0;
                } else {
                    // Desember - baru/belum bayar
                    $status = 'baru';
                    $totalDibayar = 0;
                }

                $totalTagihan = $biaya['SPP']->jumlah + $biaya['Katering']->jumlah + $biaya['Laundry']->jumlah;
                $jumlahBayar = $totalDibayar;
                $sisaTagihan = $totalTagihan - $jumlahBayar;

                $tagihan = Tagihan::create([
                    'siswa_id' => $siswa->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal_tagihan' => Carbon::create($tahun, $bulan, 1),
                    'status' => $status,
                    'total_tagihan' => $totalTagihan,
                    'jumlah_bayar' => $jumlahBayar,
                    'sisa_tagihan' => $sisaTagihan,
                    'created_by' => 1,
                ]);

                // Detail tagihan untuk semua biaya
                foreach (['SPP', 'Katering', 'Laundry'] as $namaBiaya) {
                    $biayaItem = $biaya[$namaBiaya];

                    DetailTagihan::create([
                        'tagihan_id' => $tagihan->id,
                        'biaya_id' => $biayaItem->id,
                        'jumlah' => $biayaItem->jumlah,
                    ]);
                }
            }
        }
    }
}
