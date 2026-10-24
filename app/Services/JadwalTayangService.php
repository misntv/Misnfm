<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JadwalTayangService
{
    /**
     * Ambil genre dari URL jadwal tayang (untuk tombol "Cek Jadwal")
     */
    public function getGenreFromJadwal($url)
    {
        try {
            $response = Http::timeout(10)->get($url);
            if (!$response->successful()) return null;

            $html = $response->body();
            return $this->extractGenre($html);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Ambil genre berdasarkan jam sekarang (untuk genre dinamis)
     */
    public function getGenreByTime($jadwalUrl, $jamSekarang, $hari)
    {
        try {
            $response = Http::timeout(10)->get($jadwalUrl);
            if (!$response->successful()) return null;

            $html = $response->body();

            // Cari pola jam di HTML
            $pattern = '/(\d{1,2}[:.]\d{2})\s*[-–]\s*(\d{1,2}[:.]\d{2})/';
            preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $start = $this->normalizeTime($match[1]);
                $end = $this->normalizeTime($match[2]);

                if ($this->isTimeInRange($jamSekarang, $start, $end)) {
                    $context = $this->getContextAround($html, $match[0], 300);
                    $genre = $this->extractGenre($context);
                    if ($genre) return $genre;
                }
            }

            return $this->extractGenre($html);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeTime($time)
    {
        $time = str_replace('.', ':', $time);
        if (strlen($time) < 5) $time = '0' . $time;
        return $time . ':00';
    }

    private function isTimeInRange($now, $start, $end)
    {
        return $now >= $start && $now <= $end;
    }

    private function getContextAround($html, $keyword, $length)
    {
        $pos = strpos($html, $keyword);
        if ($pos === false) return '';
        $start = max(0, $pos - $length);
        return substr($html, $start, $length * 2);
    }

    private function extractGenre($text)
    {
        $keywords = [
            'dangdut' => 'Dangdut', 'koplo' => 'Dangdut', 'campursari' => 'Dangdut',
            'pop' => 'Pop', 'hits' => 'Pop', 'musik' => 'Pop',
            'religi' => 'Religi', 'islami' => 'Religi', 'rohani' => 'Religi', 'sholawat' => 'Religi',
            'berita' => 'Berita', 'news' => 'Berita', 'info' => 'Berita', 'talkshow' => 'Berita',
            'lokal' => 'Lokal', 'daerah' => 'Lokal', 'budaya' => 'Lokal',
        ];

        $text = strtolower($text);
        foreach ($keywords as $keyword => $genre) {
            if (strpos($text, $keyword) !== false) {
                return $genre;
            }
        }
        return null;
    }

    // === METHOD LAMA UNTUK "CEK JADWAL" (tetap dipertahankan) ===
    public function getGenreFromUrl($url)
    {
        try {
            $response = Http::timeout(10)->get($url);
            if (!$response->successful()) return null;

            $html = $response->body();
            return $this->extractGenre($html);
        } catch (\Exception $e) {
            return null;
        }
    }
}