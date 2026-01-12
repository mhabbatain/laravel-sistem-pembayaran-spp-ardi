<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Biaya;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@daruljalal.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_hp' => '081234567890',
        ]);

        // Create Biaya (SPP, Katering, Laundry)
        Biaya::create([
            'nama_biaya' => 'SPP',
            'kode' => 'SPP',
            'jumlah' => 500000,
            'keterangan' => 'Sumbangan Pembinaan Pendidikan',
            'is_default' => true,
            'created_by' => 1,
        ]);

        Biaya::create([
            'nama_biaya' => 'Katering',
            'kode' => 'KATERING',
            'jumlah' => 300000,
            'keterangan' => 'Biaya Katering Bulanan',
            'is_default' => true,
            'created_by' => 1,
        ]);

        Biaya::create([
            'nama_biaya' => 'Laundry',
            'kode' => 'LAUNDRY',
            'jumlah' => 150000,
            'keterangan' => 'Biaya Laundry Bulanan',
            'is_default' => true,
            'created_by' => 1,
        ]);

        // Create Pengaturan
        Pengaturan::create(['key' => 'nama_instansi', 'value' => 'Pondok Pesantren Darul Jalal']);
        Pengaturan::create(['key' => 'email_instansi', 'value' => 'info@daruljalal.com']);
        Pengaturan::create(['key' => 'telepon_instansi', 'value' => '0821-7592-01022']);
        Pengaturan::create(['key' => 'alamat_instansi', 'value' => 'R3QG+6Q6, Tabun, Kec. VII Koto, Kabupaten Tebo, Jambi 37259']);
        Pengaturan::create(['key' => 'data_per_halaman', 'value' => '10']);
    }
}
