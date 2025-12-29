<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\RekeningSekolah;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $rekening = RekeningSekolah::where('is_active', true)->first();

        // Ambil semua tagihan yang sudah dibayar (lunas)
        $tagihanLunas = Tagihan::where('status', 'lunas')->get();

        // Pembayaran untuk tagihan lunas
        foreach ($tagihanLunas as $tagihan) {
            $siswa = $tagihan->siswa;

            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'wali_murid_id' => $siswa->wali_murid_id,
                'metode_pembayaran' => 'transfer',
                'tanggal_pembayaran' => Carbon::create($tagihan->tahun, $tagihan->bulan, rand(1, 9)),
                'jumlah_bayar' => $tagihan->total_tagihan,
                'bukti_pembayaran' => 'bukti_transfer_' . $tagihan->id . '.jpg',
                'rekening_tujuan_id' => $rekening->id,
                'status_konfirmasi' => 'dikonfirmasi',
                'tanggal_konfirmasi' => Carbon::create($tagihan->tahun, $tagihan->bulan, rand(1, 10)),
                'dikonfirmasi_oleh' => 1,
                'catatan' => 'Pembayaran lunas untuk bulan ' . Carbon::create($tagihan->tahun, $tagihan->bulan, 1)->format('F Y'),
            ]);
        }

        // Tambahkan beberapa pembayaran pending untuk testing
        $tagihanBaru = Tagihan::where('status', 'baru')->take(3)->get();

        foreach ($tagihanBaru as $tagihan) {
            $siswa = $tagihan->siswa;

            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'wali_murid_id' => $siswa->wali_murid_id,
                'metode_pembayaran' => 'transfer',
                'tanggal_pembayaran' => Carbon::now()->subDays(rand(1, 5)),
                'jumlah_bayar' => $tagihan->total_tagihan,
                'bukti_pembayaran' => 'bukti_transfer_pending_' . $tagihan->id . '.jpg',
                'rekening_tujuan_id' => $rekening->id,
                'status_konfirmasi' => 'pending',
                'catatan' => 'Menunggu konfirmasi admin',
            ]);
        }
    }
}
