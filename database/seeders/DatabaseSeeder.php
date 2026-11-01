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
        // 1. Akun Demo untuk masing-masing Role
        User::firstOrCreate(
            ['email' => 'andi@karhutla.test'],
            [
                'name' => 'Pak Andi',
                'password' => Hash::make('password123'),
                'role' => 'pelapor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'rina@karhutla.test'],
            [
                'name' => 'Ibu Rina',
                'password' => Hash::make('password123'),
                'role' => 'pelapor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'sari@karhutla.test'],
            [
                'name' => 'Bu Sari',
                'password' => Hash::make('password123'),
                'role' => 'relawan',
            ]
        );

        User::firstOrCreate(
            ['email' => 'budi@karhutla.test'],
            [
                'name' => 'Pak Budi',
                'password' => Hash::make('password123'),
                'role' => 'koordinator',
            ]
        );

        // 2. Data Master Area / Wilayah untuk Dropdown
        Area::firstOrCreate(
            ['name' => 'Hutan Kalimantan Tengah'],
            [
                'district' => 'Kota Palangka Raya',
                'risk_level' => 'tinggi',
                'contact_agency' => 'BPBD Kalimantan Tengah',
                'description' => 'Wilayah dengan lahan gambut luas.',
            ]
        );

        Area::firstOrCreate(
            ['name' => 'HutanKalimantan Barat'],
            [
                'district' => 'Kab. Kubu Raya',
                'risk_level' => 'sedang',
                'contact_agency' => 'BPBD Kalimantan Barat & Manggala Agni',
                'description' => 'Wilayah perkebunan dan lahan semak.',
            ]
        );

        Area::firstOrCreate(
            ['name' => 'Hutan Kalimantan Selatan'],
            [
                'district' => 'Kab. Banjar',
                'risk_level' => 'rendah',
                'contact_agency' => 'BPBD Kalimantan Selatan',
                'description' => 'Wilayah lahan kering di sekitar permukiman.',
            ]
        );
    }
}