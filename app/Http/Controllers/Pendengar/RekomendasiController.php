<?php

namespace App\Http\Controllers\Pendengar;

use App\Http\Controllers\Controller;
use App\Models\StasiunRadio;
use App\Models\RekomendasiCache;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Tampilkan rekomendasi di halaman radio
     */
    public function index()
    {
        if (!Auth::check()) {
            return StasiunRadio::where('is_archieved', false)
                ->orderByDesc('jumlah_pendengar')
                ->take(4)
                ->get();
        }

        $userId = Auth::id();
        $recommendationIds = $this->recommendationService->getRecommendations($userId);

        if (empty($recommendationIds)) {
            return StasiunRadio::where('is_archieved', false)
                ->orderByDesc('jumlah_pendengar')
                ->take(4)
                ->get();
        }

        $recommendations = StasiunRadio::whereIn('id', $recommendationIds)
            ->where('is_archieved', false)
            ->get();

        $sorted = [];
        foreach ($recommendationIds as $id) {
            $radio = $recommendations->firstWhere('id', $id);
            if ($radio) {
                $sorted[] = $radio;
            }
        }

        if (count($sorted) < 4) {
            $existingIds = collect($sorted)->pluck('id')->toArray();
            $remaining = 4 - count($sorted);
            $fallback = StasiunRadio::where('is_archieved', false)
                ->whereNotIn('id', $existingIds)
                ->orderByDesc('jumlah_pendengar')
                ->take($remaining)
                ->get();
            
            $sorted = array_merge($sorted, $fallback->toArray());
        }

        return collect($sorted);
    }

    /**
     * Force refresh rekomendasi
     */
    public function refresh(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $userId = Auth::id();
        
        RekomendasiCache::where('user_id', $userId)->delete();
        $recommendationIds = $this->recommendationService->getRecommendations($userId, true);

        $recommendations = StasiunRadio::whereIn('id', $recommendationIds)
            ->where('is_archieved', false)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi diperbarui',
            'data' => $recommendations
        ]);
    }

    /**
     * Get rekomendasi via AJAX untuk partial refresh
     * ✅ DENGAN FITUR SEARCH - FILTER HANYA NAMA STASIUN
     */
    public function getRecommendationsJson(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
                'data' => []
            ], 401);
        }

        $userId = Auth::id();
        $search = $request->input('search');

        // ✅ JIKA ADA SEARCH, AMBIL DARI DATABASE LANGSUNG
        if ($search) {
            $recommendations = StasiunRadio::where('is_archieved', false)
                ->where('nama_stasiun', 'LIKE', '%' . $search . '%')
                ->orderBy('jumlah_pendengar', 'desc')
                ->take(4)
                ->get();

            $data = $recommendations->map(function ($radio) {
                return [
                    'id' => $radio->id,
                    'nama_stasiun' => $radio->nama_stasiun,
                    'thumbnail' => $radio->thumbnail ? asset('storage/thumbnails/' . $radio->thumbnail) : asset('img/default-radio.png'),
                    'genre' => $radio->genre,
                    'jumlah_pendengar' => $radio->jumlah_pendengar,
                    'url' => route('pendengar.player.index', $radio->id)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        // ✅ TANPA SEARCH, PAKAI CACHE
        $recommendationIds = $this->recommendationService->getRecommendations($userId);

        if (empty($recommendationIds)) {
            $recommendations = StasiunRadio::where('is_archieved', false)
                ->orderByDesc('jumlah_pendengar')
                ->take(4)
                ->get();
        } else {
            $recommendations = StasiunRadio::whereIn('id', $recommendationIds)
                ->where('is_archieved', false)
                ->get();
            
            $sorted = [];
            foreach ($recommendationIds as $id) {
                $radio = $recommendations->firstWhere('id', $id);
                if ($radio) {
                    $sorted[] = $radio;
                }
            }
            $recommendations = collect($sorted);
        }

        // Format response
        $data = $recommendations->map(function ($radio) {
            return [
                'id' => $radio->id,
                'nama_stasiun' => $radio->nama_stasiun,
                'thumbnail' => $radio->thumbnail ? asset('storage/thumbnails/' . $radio->thumbnail) : asset('img/default-radio.png'),
                'genre' => $radio->genre,
                'jumlah_pendengar' => $radio->jumlah_pendengar,
                'url' => route('pendengar.player.index', $radio->id)
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}