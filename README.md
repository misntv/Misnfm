# SISTEM REKOMENDASI STASIUN RADIO BERBASIS CONTENT-BASED FILTERING MENGGUNAKAN COSINE SIMILARITY PADA WEBSITE MISNFM

Sistem rekomendasi stasiun radio berbasis web menggunakan Laravel dan MySQL untuk membantu pengguna menemukan siaran radio sesuai preferensi berdasarkan riwayat pemutaran.

- **Live MISNFM:** https://misnfm.misntv.biz.id
- **Portal Prakerin MISNTV:** https://www.prakerin.misntv.biz.id

## Tentang Proyek

Proyek ini merupakan **Tugas Akhir** pada Program Studi D-IV Teknologi Rekayasa Perangkat Lunak, Politeknik Negeri Banyuwangi (Poliwangi).

Sistem ini menerapkan metode **Content-Based Filtering** dengan perhitungan **Cosine Similarity** untuk memberikan rekomendasi stasiun radio secara personal berdasarkan riwayat pemutaran pengguna, termasuk durasi dan frekuensi pemutaran.

## Teknologi yang Digunakan

- Laravel 11
- MySQL
- Bootstrap 5
- JavaScript (jQuery, AJAX)

## Fitur Utama

- Pencatatan riwayat pemutaran radio secara otomatis
- Pembentukan profil preferensi pengguna berdasarkan riwayat 14 hari terakhir
- Perhitungan Cosine Similarity
- Rekomendasi 4 stasiun radio teratas
- Deteksi genre otomatis dari RSS Feed dan jadwal tayang
- Pembaruan genre otomatis melalui scheduler/cron job

## Menjalankan Secara Lokal

```bash
# Clone repository
git clone https://github.com/DikiHermawan08204/tugas-akhir-2026-Ywdhsinyus.git

# Masuk ke folder proyek
cd tugas-akhir-2026-Ywdhsinyus

# Install dependencies
composer install
npm install

# Siapkan environment
cp .env.example .env
php artisan key:generate

# Atur koneksi database di file .env, lalu jalankan migrasi
php artisan migrate

# Jalankan server pengembangan
php artisan serve
```

## Menjalankan Scheduler (Pembaruan Genre Otomatis)

Untuk menjalankan scheduler selama pengembangan lokal:

```bash
php artisan schedule:work
```

Untuk server produksi, tambahkan cron job berikut dan sesuaikan lokasi folder proyek:

```cron
* * * * * cd /path-ke-proyek && php artisan schedule:run >> /dev/null 2>&1
```

## Author

**Diki Hermawan**  
NIM: 362258302129  
D-IV Teknologi Rekayasa Perangkat Lunak  
Politeknik Negeri Banyuwangi

Proyek ini dikembangkan untuk Radio MISNFM (Media Inspirasi Sahabat Nusantara Televisi).

## Tautan

- **Website MISNFM:** https://misnfm.misntv.biz.id
- **Portal Prakerin MISNTV:** https://www.prakerin.misntv.biz.id
- **Organisasi MISNTV:** https://www.misntv.biz.id

## Ketentuan Penggunaan dan Amal Jariyah

Penggunaan, pembelajaran, atau pengembangan lebih lanjut dari proyek ini diharapkan tetap menghormati karya pembuat dan mempertahankan atribusi kepada MISNTV apabila bagian proyek yang terkait digunakan kembali.

Apabila proyek ini digunakan, disalin, dimodifikasi, atau didistribusikan kembali, mohon:
1. Tetap sertakan pemberitahuan hak cipta dan atribusi yang tersedia.
2. Sertakan berkas `LICENSE`.
3. Jelaskan perubahan penting yang dilakukan.
4. Hormati lisensi dan ketentuan dari komponen pihak ketiga yang digunakan.

Semoga proyek ini memberi manfaat, menjadi amal jariyah, dan membantu perkembangan teknologi serta layanan radio digital.

## Hak Cipta

Hak cipta atas karya dan kontribusi masing-masing tetap dimiliki oleh pemegang haknya. Lihat berkas `LICENSE` untuk ketentuan penggunaan kembali.

Copyright © 2026 MISNTV / Media Inspirasi Sahabat Nusantara Televisi.
