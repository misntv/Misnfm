# SISTEM REKOMENDASI STASIUN RADIO BERBASIS CONTENT-BASED FILTERING MENGGUNAKAN COSINE SIMILARITY PADA WEBSITE MISNFM

Sistem rekomendasi stasiun radio berbasis web (LARAVEL) dan MySQL untuk membantu pengguna menemukan siaran radio sesuai preferensi berdasarkan riwayat pemutaran.

Live: misnfm.misntv.biz.id

## Tentang Proyek

Proyek ini merupakan **Tugas Akhir** pada Program Studi D-IV Teknologi Rekayasa Perangkat Lunak, Politeknik Negeri Banyuwangi (Poliwangi).

Sistem ini menerapkan metode **Content-Based Filtering** dengan perhitungan **Cosine Similarity** untuk memberikan rekomendasi stasiun radio secara personal berdasarkan riwayat pemutaran pengguna (durasi dan frekuensi).

## Teknologi yang Digunakan

- Laravel 11
- MySQL
- Bootstrap 5
- JavaScript (jQuery, AJAX)

## Fitur Utama

- Pencatatan riwayat pemutaran radio secara otomatis
- Pembentukan profil preferensi pengguna (14 hari terakhir)
- Perhitungan Cosine Similarity
- Rekomendasi 4 stasiun radio teratas
- Auto-deteksi genre dari RSS Feed dan Jadwal Tayang
- Update genre otomatis via scheduler/cron job

## Menjalankan Secara Lokal

```bash
# clone repository
git clone https://github.com/DikiHermawan08204/tugas-akhir-2026-Ywdhsinyus.git

# masuk ke folder proyek
cd tugas-akhir-2026-Ywdhsinyus

# install dependencies
composer install
npm install

# setup environment
cp .env.example .env
php artisan key:generate

# setup database di .env lalu jalankan migrasi
php artisan migrate

# jalankan server
php artisan serve
```

## Menjalankan Scheduler (Update Genre Otomatis)

```bash
# di lokal
php artisan schedule:work

# di server (cron job)
* * * * * cd /path-ke-proyek && php artisan schedule:run >> /dev/null 2>&1
```

## Author

**Diki Hermawan**
NIM 362258302129
D-IV Teknologi Rekayasa Perangkat Lunak
Politeknik Negeri Banyuwangi

Proyek ini dikembangkan untuk Radio MISNFM (Media Inspirasi Sahabat Nusantara Televisi).

## Lisensi

Hak cipta dilindungi.