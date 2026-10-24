<?php

namespace App\Services;

use App\Models\StasiunRadio;
use App\Models\RiwayatPemutaran;
use App\Models\RekomendasiCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RecommendationService
{
    /**
     * Daftar genre yang tersedia (6 genre)
     */
    protected array $genres = [
        'Pop', 'Dangdut', 'Religi', 'Berita', 'Lokal', 'Lainnya'
    ];

    /**
     * Ambang batas minimal durasi (60 detik = 1 menit)
     */
    protected int $minDuration = 60;

    /**
     * Periode data aktif (14 hari)
     */
    protected int $periodDays = 14;

    /**
     * Bobot durasi (70%)
     */
    protected float $weightDuration = 0.7;

    /**
     * Bobot frekuensi (30%)
     */
    protected float $weightFrequency = 0.3;

    /**
     * Jumlah rekomendasi yang ditampilkan
     */
    protected int $topN = 4;

    /**
     * Ambil rekomendasi untuk user tertentu
     */
    public function getRecommendations(int $userId, bool $forceRefresh = false): array
    {
        if (!$forceRefresh) {
            $cache = RekomendasiCache::where('user_id', $userId)
                ->where('expired_at', '>', now())
                ->first();

            if ($cache && $cache->isValid()) {
                return $cache->rekomendasi_ids;
            }
        }

        $recommendationIds = $this->calculateRecommendations($userId);
        $this->saveCache($userId, $recommendationIds);

        return $recommendationIds;
    }

    /**
     * Hitung rekomendasi menggunakan Cosine Similarity
     */
    protected function calculateRecommendations(int $userId): array
    {
        $allRadios = StasiunRadio::where('is_archieved', false)->get();

        if ($allRadios->isEmpty()) {
            return [];
        }

        $histories = RiwayatPemutaran::where('user_id', $userId)
            ->where('played_at', '>=', now()->subDays($this->periodDays))
            ->where('durasi', '>=', $this->minDuration)
            ->get();

        if ($histories->isEmpty()) {
            return $allRadios->sortByDesc('jumlah_pendengar')
                ->take($this->topN)
                ->pluck('id')
                ->toArray();
        }

        $userVector = $this->calculateUserVector($histories);

        $scores = [];
        foreach ($allRadios as $radio) {
            // 🔥 PERUBAHAN: kirim object $radio, bukan string genre
            $radioVector = $this->getRadioVector($radio);
            $similarity = $this->cosineSimilarity($userVector, $radioVector);
            $scores[$radio->id] = $similarity;
        }

        arsort($scores);
        $topIds = array_keys(array_slice($scores, 0, $this->topN, true));

        if (count($topIds) < $this->topN) {
            $remaining = $this->topN - count($topIds);
            $popularRadios = StasiunRadio::where('is_archieved', false)
                ->whereNotIn('id', $topIds)
                ->orderByDesc('jumlah_pendengar')
                ->take($remaining)
                ->pluck('id')
                ->toArray();
            $topIds = array_merge($topIds, $popularRadios);
        }

        return $topIds;
    }

    /**
     * Hitung vektor preferensi user dari riwayat
     */
    protected function calculateUserVector($histories): array
    {
        // 🔥 PERUBAHAN: pakai GenreService
        $genreService = new GenreService();
        $frequencyVector = array_fill(0, count($this->genres), 0);
        $durationVector = array_fill(0, count($this->genres), 0);

        foreach ($histories as $history) {
            // Ambil genre berdasarkan waktu pemutaran (played_at)
            $genre = $genreService->getGenreByTime(
                $history->stasiun_radio_id,
                $history->played_at
            );
            
            $genreIndex = $this->getGenreIndex($genre);
            if ($genreIndex === -1) continue;

            $frequencyVector[$genreIndex] += 1;
            $durationInMinutes = $history->durasi / 60;
            $durationVector[$genreIndex] += $durationInMinutes;
        }

        return $this->normalizeVector($frequencyVector, $durationVector);
    }

    /**
     * Normalisasi vektor dengan bobot durasi dan frekuensi
     */
    protected function normalizeVector(array $frequency, array $duration): array
    {
        $result = [];

        $maxFrequency = max($frequency) ?: 1;
        $maxDuration = max($duration) ?: 1;

        for ($i = 0; $i < count($this->genres); $i++) {
            $normFreq = $frequency[$i] / $maxFrequency;
            $normDur = $duration[$i] / $maxDuration;
            $result[$i] = ($normFreq * $this->weightFrequency) + ($normDur * $this->weightDuration);
        }

        return $result;
    }

    /**
     * Ambil vektor one-hot encoding untuk radio (BERDASARKAN JAM SEKARANG)
     */
    protected function getRadioVector($radio): array
    {
        // 🔥 PERUBAHAN: pakai GenreService untuk ambil genre berdasarkan jam sekarang
        $genreService = new GenreService();
        $genre = $genreService->getGenreByTime($radio->id);
        
        $vector = array_fill(0, count($this->genres), 0);
        $genreIndex = $this->getGenreIndex($genre);
        if ($genreIndex !== -1) {
            $vector[$genreIndex] = 1;
        }
        return $vector;
    }

    /**
     * Cari index genre berdasarkan nama
     */
    protected function getGenreIndex(string $genre): int
    {
        $genre = trim($genre);
        $index = array_search($genre, $this->genres);
        
        if ($index === false) {
            $index = array_search('Lainnya', $this->genres);
        }

        return $index !== false ? $index : -1;
    }

    /**
     * Hitung Cosine Similarity antara dua vektor
     */
    protected function cosineSimilarity(array $vectorA, array $vectorB): float
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($vectorA); $i++) {
            $dotProduct += $vectorA[$i] * $vectorB[$i];
            $normA += $vectorA[$i] * $vectorA[$i];
            $normB += $vectorB[$i] * $vectorB[$i];
        }

        $normA = sqrt($normA);
        $normB = sqrt($normB);

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dotProduct / ($normA * $normB);
    }

    /**
     * Simpan cache rekomendasi ke database
     */
    protected function saveCache(int $userId, array $recommendationIds): void
    {
        RekomendasiCache::where('user_id', $userId)->delete();

        RekomendasiCache::create([
            'user_id' => $userId,
            'rekomendasi_ids' => $recommendationIds,
            'expired_at' => now()->addHour(),
        ]);
    }

    /**
     * Bersihkan cache expired
     */
    public function cleanExpiredCache(): void
    {
        RekomendasiCache::where('expired_at', '<=', now())->delete();
    }
}