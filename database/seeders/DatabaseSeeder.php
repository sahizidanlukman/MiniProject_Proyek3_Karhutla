<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\FireReport;
use App\Models\ReportStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
                Storage::disk('public')->put(
            'evidences/sample-evidence.jpg',
            file_get_contents(database_path('seeders/files/sample-evidence.jpg'))
        );

        $andi = User::create([
            'name' => 'Andi',
            'email' => 'andi@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
        ]);

        $rina = User::create([
            'name' => 'Rina',
            'email' => 'rina@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
        ]);

        $sari = User::create([
            'name' => 'Sari',
            'email' => 'sari@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'relawan',
        ]);

        $budi = User::create([
            'name' => 'Budi',
            'email' => 'budi@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'koordinator',
        ]);

        $area1 = Area::create([
            'name' => 'Kalimantan Tengah',
            'district' => 'Kota Palangka Raya',
            'risk_level' => 'tinggi',
            'contact_agency' => 'BPBD Kalimantan Tengah',
            'description' => 'Wilayah dengan lahan gambut luas.',
        ]);

        $area2 = Area::create([
            'name' => 'Kalimantan Barat',
            'district' => 'Kab. Kubu Raya',
            'risk_level' => 'sedang',
            'contact_agency' => 'BPBD Kalimantan Barat & Manggala Agni',
            'description' => 'Wilayah perkebunan dan lahan semak.',
        ]);

        $area3 = Area::create([
            'name' => 'Kalimantan Selatan',
            'district' => 'Kab. Banjar',
            'risk_level' => 'rendah',
            'contact_agency' => 'BPBD Kalimantan Selatan',
            'description' => 'Wilayah lahan kering di sekitar permukiman.',
        ]);

        // Laporan 1: Andi, menunggu_tinjauan 
        FireReport::create([
            'user_id' => $andi->id,
            'area_id' => $area1->id,
            'title' => 'Asap tebal muncul dari tepi kanal perkebunan',
            'description' => 'Terlihat kepulan asap putih pekat membumbung dari balik kebun sawit sekitar pukul 14.00 WIB.',
            'incident_time' => now()->subHours(5),
            'latitude' => -2.2145000,
            'longitude' => 113.9210000,
            'severity' => 'tinggi',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'menunggu_tinjauan',
        ]);

        // Laporan 2: Andi, ditinjau 
        $rep2 = FireReport::create([
            'user_id' => $andi->id,
            'area_id' => $area1->id,
            'title' => 'Titik api merambat di semak kering dekat parit batas desa',
            'description' => 'Api mulai membakar semak pakis kering sepanjang 20 meter, angin bertiup ke arah utara.',
            'incident_time' => now()->subDays(1),
            'latitude' => -2.2189000,
            'longitude' => 113.9290000,
            'severity' => 'tinggi',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'ditinjau',
            'reviewed_by' => $sari->id,
            'review_notes' => 'Informasi dan koordinat wajar, berada satu hamparan dengan laporan asap kanal. Perlu segera diteruskan.',
            'needs_forwarding' => true,
        ]);

        ReportStatusLog::create([
            'fire_report_id' => $rep2->id,
            'changed_by' => $sari->id,
            'old_status' => 'menunggu_tinjauan',
            'new_status' => 'ditinjau',
        ]);

        // Laporan 3: Andi, diteruskan
        $rep3 = FireReport::create([
            'user_id' => $andi->id,
            'area_id' => $area2->id,
            'title' => 'Kepulan asap di lahan kosong pinggir jalan lintas',
            'description' => 'Asap mengganggu jarak pandang pengendara di sekitar KM 18 Sungai Ambawang.',
            'incident_time' => now()->subDays(2),
            'latitude' => -0.0891000,
            'longitude' => 109.4912000,
            'severity' => 'sedang',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'diteruskan',
            'reviewed_by' => $sari->id,
            'review_notes' => 'Bukti jelas, lokasi dekat jalan raya utama.',
            'needs_forwarding' => true,
            'forwarded_to' => 'BPBD Kalimantan Barat & Manggala Agni',
            'forwarded_at' => now()->subDay(),
        ]);

        ReportStatusLog::create([
            'fire_report_id' => $rep3->id,
            'changed_by' => $budi->id,
            'old_status' => 'ditinjau',
            'new_status' => 'diteruskan',
        ]);

        // Laporan 4: Rina, selesai 
        FireReport::create([
            'user_id' => $rina->id,
            'area_id' => $area3->id,
            'title' => 'Sisa pembakaran ranting di pinggir kebun karet',
            'description' => 'Terdapat asap tipis dari tumpukan ranting kering namun sudah mulai mengecil.',
            'incident_time' => now()->subDays(4),
            'latitude' => -3.3124000,
            'longitude' => 114.5900000,
            'severity' => 'rendah',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'selesai',
            'reviewed_by' => $sari->id,
            'review_notes' => 'Telah ditinjau dan dikonfirmasi warga sekitar bahwa asap sudah berhenti.',
            'needs_forwarding' => false,
            'forwarded_to' => 'Pemerintah Desa Setempat',
            'forwarded_at' => now()->subDays(3),
        ]);

        FireReport::create([
            'user_id' => $rina->id,
            'area_id' => $area2->id,
            'title' => 'Asap putih di lahan gambut belakang gardu induk',
            'description' => 'Terlihat asap dari kejauhan sekitar 300 meter di belakang gardu listrik.',
            'incident_time' => now()->subHours(12),
            'latitude' => -0.0923000,
            'longitude' => 109.5011000,
            'severity' => 'sedang',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'menunggu_tinjauan',
        ]);

        FireReport::create([
            'user_id' => $andi->id,
            'area_id' => $area3->id,
            'title' => 'Indikasi pembakaran lahan semak di lereng bukit',
            'description' => 'Terlihat cahaya kemerahan dan asap pada sore menjelang malam di lereng bukit.',
            'incident_time' => now()->subHours(20),
            'latitude' => -3.3201000,
            'longitude' => 114.6009000,
            'severity' => 'sedang',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'ditinjau',
            'reviewed_by' => $sari->id,
            'review_notes' => 'Sedang dipantau bersama relawan lokal.',
            'needs_forwarding' => true,
        ]);

        FireReport::create([
            'user_id' => $rina->id,
            'area_id' => $area1->id,
            'title' => 'Laporan duplikat kabut pagi di sekitar perkebunan',
            'description' => 'Terlihat kabut putih tipis pada pukul 06.00 pagi di sekitar jalan kebun.',
            'incident_time' => now()->subDays(5),
            'latitude' => -2.2100000,
            'longitude' => 113.9200000,
            'severity' => 'rendah',
            'evidence_path' => 'evidences/sample-evidence.jpg',
            'status' => 'ditolak',
            'reviewed_by' => $sari->id,
            'review_notes' => 'Bukan indikasi kebakaran, hanya embun atau kabut pagi biasa.',
            'needs_forwarding' => false,
        ]);
    }
}