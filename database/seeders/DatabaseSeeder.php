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
    }
}