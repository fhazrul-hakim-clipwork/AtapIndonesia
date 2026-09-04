<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin AtapIndonesia',
                'email' => 'atap.idn@gmail.com',
                'password' => Hash::make('atap_ind0n3sia'),
                'role' => 'admin',
                'kota' => 'Banyumas',
                'telepon' => '081234567890',
                'alamat' => 'Jalan Karang Duren RT.2/RW.3 Sokaraja Lor, Sokaraja, kab.Banyumas Jawa Tengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('beli123'),
                'role' => 'pembeli',
                'kota' => 'Jakarta',
                'telepon' => '089876543210',
                'alamat' => 'Jakarta Selatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
