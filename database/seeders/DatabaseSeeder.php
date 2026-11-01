<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat akun demo untuk masing-masing role
        User::firstOrCreate(
            ['email' => 'andi@karhutla.test'],
            [
                'name' => 'Pak Andi',
                'password' => Hash::make('password123'),
                'role' => 'pelapor',
            ]
        );

        // 2. Data Master Area / Wilayah untuk Dropdown
        Area::firstOrCreate(
            ['name' => 'Kawasan Perkebunan Gambut Blok Timur'],
            [
                'district' => 'Kec. Rasau Jaya, Kab. Kubu Raya',
                'risk_level' => 'tinggi',
                'contact_agency' => 'BPBD Kubu Raya & Manggala Agni Daops Pontianak',
                'description' => 'Lahan gambut dalam di sekitar perkebunan warga.',
            ]
        );
    }
}