<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WaliMurid;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class WaliSeeder extends Seeder
{
    public function run(): void
    {
        // Wali 1 - Budi Santoso (2 anak)
        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
            'no_hp' => '081234567891',
            'created_by' => 1,
        ]);

        $wali1 = WaliMurid::create([
            'user_id' => $user1->id,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali1->id,
            'nisn' => '1234567890',
            'nama' => 'Ahmad Budi Santoso',
            'jenis_kelamin' => 'L',
            'jurusan' => 'IPA',
            'alamat' => 'Jl. Merdeka No. 10, Kelurahan Sukamaju, Kec. Ciputat, Tangerang Selatan',
            'kelas' => 'X',
            'is_active' => true,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali1->id,
            'nisn' => '1234567891',
            'nama' => 'Fatimah Budi Santoso',
            'jenis_kelamin' => 'P',
            'jurusan' => 'IPS',
            'alamat' => 'Jl. Merdeka No. 10, Kelurahan Sukamaju, Kec. Ciputat, Tangerang Selatan',
            'kelas' => 'VIII',
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Wali 2 - Siti Aminah (1 anak)
        $user2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
            'no_hp' => '081234567892',
            'created_by' => 1,
        ]);

        $wali2 = WaliMurid::create([
            'user_id' => $user2->id,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali2->id,
            'nisn' => '1234567892',
            'nama' => 'Muhammad Rizki',
            'jenis_kelamin' => 'L',
            'jurusan' => 'IPA',
            'alamat' => 'Jl. Pahlawan No. 25, Kelurahan Cempaka, Kec. Pondok Aren, Tangerang Selatan',
            'kelas' => 'IX',
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Wali 3 - Hendra Wijaya (3 anak)
        $user3 = User::create([
            'name' => 'Hendra Wijaya',
            'email' => 'hendra@example.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
            'no_hp' => '081234567893',
            'created_by' => 1,
        ]);

        $wali3 = WaliMurid::create([
            'user_id' => $user3->id,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali3->id,
            'nisn' => '1234567893',
            'nama' => 'Dina Wijaya',
            'jenis_kelamin' => 'P',
            'jurusan' => 'IPA',
            'alamat' => 'Jl. Sudirman No. 45, Kelurahan Bintaro, Kec. Pesanggrahan, Jakarta Selatan',
            'kelas' => 'XI',
            'is_active' => true,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali3->id,
            'nisn' => '1234567894',
            'nama' => 'Rina Wijaya',
            'jenis_kelamin' => 'P',
            'jurusan' => 'IPS',
            'alamat' => 'Jl. Sudirman No. 45, Kelurahan Bintaro, Kec. Pesanggrahan, Jakarta Selatan',
            'kelas' => 'IX',
            'is_active' => true,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali3->id,
            'nisn' => '1234567895',
            'nama' => 'Andi Wijaya',
            'jenis_kelamin' => 'L',
            'jurusan' => 'IPA',
            'alamat' => 'Jl. Sudirman No. 45, Kelurahan Bintaro, Kec. Pesanggrahan, Jakarta Selatan',
            'kelas' => 'VII',
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Wali 4 - Rina Kusuma (1 anak)
        $user4 = User::create([
            'name' => 'Rina Kusuma',
            'email' => 'rina@example.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
            'no_hp' => '081234567894',
            'created_by' => 1,
        ]);

        $wali4 = WaliMurid::create([
            'user_id' => $user4->id,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali4->id,
            'nisn' => '1234567896',
            'nama' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'jurusan' => 'IPS',
            'alamat' => 'Jl. Ahmad Yani No. 100, Kelurahan Cipadu, Kec. Larangan, Tangerang',
            'kelas' => 'X',
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Wali 5 - Agus Pratama (2 anak, 1 non-aktif)
        $user5 = User::create([
            'name' => 'Agus Pratama',
            'email' => 'agus@example.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
            'no_hp' => '081234567895',
            'created_by' => 1,
        ]);

        $wali5 = WaliMurid::create([
            'user_id' => $user5->id,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali5->id,
            'nisn' => '1234567897',
            'nama' => 'Rudi Pratama',
            'jenis_kelamin' => 'L',
            'jurusan' => 'IPA',
            'alamat' => 'Jl. Gatot Subroto No. 77, Kelurahan Kebayoran, Kec. Kebayoran Baru, Jakarta Selatan',
            'kelas' => 'VIII',
            'is_active' => true,
            'created_by' => 1,
        ]);

        Siswa::create([
            'wali_murid_id' => $wali5->id,
            'nisn' => '1234567898',
            'nama' => 'Dewi Pratama',
            'jenis_kelamin' => 'P',
            'jurusan' => 'IPS',
            'alamat' => 'Jl. Gatot Subroto No. 77, Kelurahan Kebayoran, Kec. Kebayoran Baru, Jakarta Selatan',
            'kelas' => 'XII',
            'is_active' => false,
            'created_by' => 1,
        ]);
    }
}
