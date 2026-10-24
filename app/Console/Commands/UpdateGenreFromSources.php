<?php

namespace App\Console\Commands;

use App\Models\StasiunRadio;
use App\Services\GenreService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateGenreFromSources extends Command
{
    protected $signature = 'update:genre';
    protected $description = 'Update genre stasiun radio dari RSS/URL jadwal';

    public function handle()
    {
        $genreService = new GenreService();
        $radios = StasiunRadio::where('is_archieved', false)->get();

        foreach ($radios as $radio) {
            // Ambil genre berdasarkan jam sekarang
            $genre = $genreService->getGenreByTime($radio->id);
            
            if ($genre && $genre !== $radio->genre) {
                $radio->update(['genre' => $genre]);
                Log::info("✅ Genre updated: {$radio->nama_stasiun} → {$genre}");
                $this->info("Update genre {$radio->nama_stasiun}: {$genre}");
            }
        }

        $this->info('Genre update selesai.');
        return 0;
    }
}