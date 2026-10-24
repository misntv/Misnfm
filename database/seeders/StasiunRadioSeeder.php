<?php

namespace Database\Seeders;

use App\Models\StasiunRadio;
use Illuminate\Database\Seeder;

class StasiunRadioSeeder extends Seeder
{
    public function run()
    {
        $radios = [
            [
                'nama_stasiun' => 'Radio Pop Indonesia',
                'nama_daerah' => 'Jakarta',
                'url_stasiun' => 'https://stream-example.com/pop.mp3',
                'thumbnail' => 'default-radio.png',  // ✅ DITAMBAHKAN
                'tanggal_upload' => '2026-07-20',    // ✅ DITAMBAHKAN
                'jam_upload' => '2026-07-20',        // ✅ DITAMBAHKAN
                'deskripsi' => 'Radio dengan musik pop terbaik dari dalam dan luar negeri',
                'genre' => 'Pop',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Dangdut Nusantara',
                'nama_daerah' => 'Surabaya',
                'url_stasiun' => 'https://stream-example.com/dangdut.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio dangdut favorit dengan lagu-lagu dangdut terbaik',
                'genre' => 'Dangdut',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Religi Hati',
                'nama_daerah' => 'Bandung',
                'url_stasiun' => 'https://stream-example.com/religi.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio religi dengan siaran kajian dan lagu-lagu religi',
                'genre' => 'Religi',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Berita Terkini',
                'nama_daerah' => 'Medan',
                'url_stasiun' => 'https://stream-example.com/berita.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio berita dengan informasi terkini dan terpercaya',
                'genre' => 'Berita',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Lokal Jogja',
                'nama_daerah' => 'Yogyakarta',
                'url_stasiun' => 'https://stream-example.com/lokal.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio lokal dengan budaya dan musik khas Yogyakarta',
                'genre' => 'Lokal',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Harmoni',
                'nama_daerah' => 'Bali',
                'url_stasiun' => 'https://stream-example.com/lainnya.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio dengan berbagai genre musik dan hiburan',
                'genre' => 'Lainnya',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Pop Remaja',
                'nama_daerah' => 'Jakarta',
                'url_stasiun' => 'https://stream-example.com/pop2.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio pop untuk remaja dengan lagu-lagu terkini',
                'genre' => 'Pop',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
            [
                'nama_stasiun' => 'Radio Dangdut Koplo',
                'nama_daerah' => 'Surabaya',
                'url_stasiun' => 'https://stream-example.com/dangdut2.mp3',
                'thumbnail' => 'default-radio.png',
                'tanggal_upload' => '2026-07-20',
                'jam_upload' => '2026-07-20',
                'deskripsi' => 'Radio dangdut koplo dengan irama yang enak didengar',
                'genre' => 'Dangdut',
                'jumlah_pendengar' => 0,
                'is_archieved' => false,
            ],
        ];

        foreach ($radios as $radio) {
            StasiunRadio::create($radio);
        }
    }
}