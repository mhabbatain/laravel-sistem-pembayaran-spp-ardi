<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin (Ketua Yayasan) User
        User::create([
            'name' => 'Ketua Yayasan',
            'email' => 'ketuayayasan@daruljalal.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'no_hp' => '081234567899',
        ]);
    }
}
