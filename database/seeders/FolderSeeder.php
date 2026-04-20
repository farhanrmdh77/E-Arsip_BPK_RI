<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    for ($i = 0; $i <= 19; $i++) {
        \App\Models\Folder::create([
            'kode_folder' => 'KP.' . str_pad($i, 2, '0', STR_PAD_LEFT),
            'nama_folder' => 'Folder Klasifikasi ' . $i
        ]);
    }
}
}
