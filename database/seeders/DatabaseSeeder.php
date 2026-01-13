<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed data dalam urutan yang benar
        $this->call([
            InitialDataSeeder::class,      // Admin, Biaya, Pengaturan
            SuperadminSeeder::class,        // Superadmin (Ketua Yayasan)
            RekeningSekolahSeeder::class,   // Rekening Bank
            MetodePembayaranSeeder::class,  // Metode Pembayaran
            WaliSeeder::class,         // Wali Murid, User Wali, Siswa
            TagihanSeeder::class,           // Tagihan untuk semua siswa
            PembayaranSeeder::class,        // Pembayaran (lunas, cicilan, pending)
        ]);
    }
}
