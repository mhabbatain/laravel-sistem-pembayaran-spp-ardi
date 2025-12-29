<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        // Operator 1
        User::create([
            'name' => 'Operator 1',
            'email' => 'operator@daruljalal.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'no_hp' => '081234567899',
            'created_by' => 1,
        ]);

        // Operator 2
        User::create([
            'name' => 'Operator 2',
            'email' => 'operator2@daruljalal.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'no_hp' => '081234567898',
            'created_by' => 1,
        ]);
    }
}
