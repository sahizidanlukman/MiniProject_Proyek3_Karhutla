# Karhutla.app — Aplikasi Pelaporan dan Pemantauan Kebakaran Hutan dan Lahan (Kasus B)

## 1. Identitas Mahasiswa
- **Nama**: Sahizidan Lukman Hakim
- **NIM**: 251511060
- **Kelas**: 2B - D3

## 2. Deskripsi Singkat Aplikasi
**Karhutla.app** adalah aplikasi web menggunakan framework Laravel untuk membantu masyarakat melaporkan indikasi kebakaran hutan dan lahan (titik asap/api, lokasi koordinat, tingkat kondisi, dan bukti foto/dokumen), membantu **Relawan Pemantau** memeriksa kelengkapan dan kewajaran laporan serta keterkaitan laporan pada area yang sama, dan membantu **Koordinator Relawan** mencatat penerusan informasi ke instansi terkait (BPBD/Damkar) serta memantau rekapitulasi laporan per wilayah.

## 3. Kebutuhan Sistem 
- PHP >= 8.2
- Composer
- MySQL / MariaDB

## 4. Langkah Instalasi dari Awal hingga Berjalan
1. Clone repository ini:
   ```bash
   git clone https://github.com/sahizidanlukman/MiniProject_Proyek3_Karhutla.git
   cd Karhutla
2. Install PHP  
    composer install
3. Salin file .env.example menjadi .env dan sesuaikan nama database (DB_DATABASE=db_karhutla):
- cp .env.example .env
- php artisan key:generate
4. Buat symbolic link storage untuk mengakses file bukti laporan:
- php artisan storage:link
5. Jalankan migrasi dan seeder data awal:
- php artisan migrate:fresh --seed
6. Jalankan server
- php artisan serve