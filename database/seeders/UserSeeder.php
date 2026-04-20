<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun admin default
        User::create([
            'name' => 'Admin Arsip',
            'email' => 'admin@arsip.com',
            'password' => Hash::make('rahasia123'), // Passwordnya: rahasia123
        ]);
    }
}