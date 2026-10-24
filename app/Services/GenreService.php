<?php

namespace App\Services;

use App\Models\StasiunRadio;
use Carbon\Carbon;

class GenreService
{
    protected RssFeedService $rssService;
    protected JadwalTayangService $jadwalService;

    public function __construct()
    {
        $this->rssService = new RssFeedService();
        $this->jadwalService = new JadwalTayangService();
    }

    /**
     * Dapatkan genre berdasarkan stasiun radio dan waktu
     * Otomatis ambil dari RSS atau URL Jadwal
     */
    public function getGenreByTime($stasiunRadioId, $waktu = null)
    {
        if ($waktu === null) {
            $waktu = Carbon::now();
        }

        $stasiun = StasiunRadio::find($stasiunRadioId);
        if (!$stasiun) return 'Lainnya';

        $jam = $waktu->format('H:i:s');
        $hari = $waktu->locale('id')->dayName;

        // PRIORITAS 1: Dari RSS Feed
        if (!empty($stasiun->feed_url)) {
            $genre = $this->rssService->getGenreByTime($stasiun->feed_url, $jam, $hari);
            if ($genre) return $genre;
        }

        // PRIORITAS 2: Dari URL Jadwal Tayang
        if (!empty($stasiun->jadwal_url)) {
            $genre = $this->jadwalService->getGenreByTime($stasiun->jadwal_url, $jam, $hari);
            if ($genre) return $genre;
        }

        // PRIORITAS 3: Fallback ke genre default
        return $stasiun->genre ?? 'Lainnya';
    }

    /**
     * Untuk riwayat pemutaran (pakai waktu played_at)
     */
    public function getGenreForHistory($stasiunRadioId, $playedAt)
    {
        return $this->getGenreByTime($stasiunRadioId, Carbon::parse($playedAt));
    }
}