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
            ['name' => 'Kawasan Perkebunan Gambut Blok Timur'],
            [
                'district' => 'Kec. Rasau Jaya, Kab. Kubu Raya',
                'risk_level' => 'tinggi',
                'contact_agency' => 'BPBD Kubu Raya & Manggala Agni Daops Pontianak',
                'description' => 'Lahan gambut dalam di sekitar perkebunan warga.',
            ]
        );

        Area::firstOrCreate(
            ['name' => 'Lahan Semak Belukar Sungai Ambawang'],
            [
                'district' => 'Kec. Sungai Ambawang, Kab. Kubu Raya',
                'risk_level' => 'sedang',
                'contact_agency' => 'Damkar Sungai Ambawang & Pemerintah Desa',
                'description' => 'Kawasan semak kering dekat jalan lintas provinsi.',
            ]
        );

        Area::firstOrCreate(
            ['name' => 'Hutan Sekunder Penyangga Bukit Sebelah'],
            [
                'district' => 'Kec. Mandor, Kab. Landak',
                'risk_level' => 'rendah',
                'contact_agency' => 'KPH Wilayah Landak & BPBD Landak',
                'description' => 'Area perbukitan dan kebun karet masyarakat.',
            ]
        );
    }
}