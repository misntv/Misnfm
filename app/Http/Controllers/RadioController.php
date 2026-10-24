<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StasiunRadio;
use App\Models\RadioListenerStats;
use Carbon\Carbon;
use App\Models\RiwayatPemutaran;
use Illuminate\Support\Facades\Auth;
use App\Services\RssFeedService;

class RadioController extends Controller
{
    /**
     * Display the radio homepage
     */
    public function index(Request $request)
{
    // Query dasar
    $query = StasiunRadio::where('is_archieved', false);
    
    // Filter pencarian
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('nama_stasiun', 'like', '%'.$search.'%')
              ->orWhere('nama_daerah', 'like', '%'.$search.'%')
              ->orWhere('deskripsi', 'like', '%'.$search.'%');
        });
    }

    // Get featured radios (top 6 by listeners)
    $featuredRadios = (clone $query)
                    ->orderBy('jumlah_pendengar', 'desc')
                    ->take(6)
                    ->get();
    
    // Get all radios grouped by region
    $allRadios = (clone $query)->get();
    $groupedRadios = $allRadios->groupBy('nama_daerah');
    
    // For paginated view (fallback)
    $paginatedRadios = $query->orderBy('created_at', 'desc')
                        ->paginate(12);
    
    return view('pendengar.radio.index', [
        'featuredRadios' => $featuredRadios,
        'groupedRadios' => $groupedRadios,
        'paginatedRadios' => $paginatedRadios,
        'totalRadios' => $allRadios->count()
    ]);
}

    /**
 * Display radios by region (Selengkapnya)
 */
public function byRegion($daerah)
{
    $radios = StasiunRadio::where('is_archieved', false)
                ->where('nama_daerah', $daerah)
                ->orderBy('nama_stasiun', 'asc')
                ->paginate(12);
    
    $daerahName = $daerah;
    
    return view('pendengar.radio.by-region', compact('radios', 'daerahName'));
}

    /**
     * Display radio player page
     */
    public function player($id)
{
    $radio = StasiunRadio::findOrFail($id);
    
    // Update listener stats
    $this->updateListenerStats($radio);
    
    // Get popular radios (excluding current one)
    $popularRadios = StasiunRadio::where('is_archieved', false)
                    ->where('id', '!=', $id)
                    ->orderBy('jumlah_pendengar', 'desc')
                    ->take(5)
                    ->get();

    // Get recently played radios (from session)
    $recentlyPlayed = session('recently_played', collect());
    
    return view('pendengar.player.index', compact('radio', 'popularRadios', 'recentlyPlayed'));
}

    public function trackPlay(Request $request, $id)
{
    $radio = StasiunRadio::findOrFail($id);
    
    if (!session()->has('listened_'.$id)) {
        $radio->increment('jumlah_pendengar');
        session()->put('listened_'.$id, true);
        
        $this->updateListenerStats($radio);
        $this->savePlayHistory($radio);

        // ✅ TAMBAHKAN: Ambil genre dari RSS
        $genre = null;
        if ($radio->feed_url) {
            $genre = app(RssFeedService::class)->getLatestGenre($radio->feed_url);
        }
        
        // Fallback ke genre default jika RSS gagal
        if (empty($genre)) {
            $genre = $radio->genre;
        }

        if (Auth::check()) {
            RiwayatPemutaran::create([
                'user_id' => Auth::id(),
                'stasiun_radio_id' => $radio->id,
                'played_at' => now(),
                'durasi' => null,
                'genre_saat_diputar' => $genre, // ✅ SIMPAN GENRE DARI RSS
            ]);
        }
    }
    
    return response()->json([
        'success' => true,
        'listeners' => $radio->jumlah_pendengar
    ]);
}

    protected function savePlayHistory(StasiunRadio $radio)
{
    $recentlyPlayed = session('recently_played', collect());
    
    // Tambahkan radio ke recently played jika belum ada
    if (!$recentlyPlayed->contains('id', $radio->id)) {
        $recentlyPlayed->prepend($radio->only(['id', 'nama_stasiun', 'logo']));
    }
    
    // Batasi hanya 5 item terakhir
    $recentlyPlayed = $recentlyPlayed->take(5);
    
    session(['recently_played' => $recentlyPlayed]);
}
    
    /**
     * Update listener statistics
     */
    protected function updateListenerStats(StasiunRadio $radio)
    {
        $today = Carbon::today()->format('Y-m-d');
        $currentHour = date('H');

        // Update jumlah_pendengar di tabel utama
        $radio->increment('jumlah_pendengar');
        
        // Cari atau buat statistik hari ini
        $stats = RadioListenerStats::firstOrCreate(
            ['radio_id' => $radio->id, 'stat_date' => $today],
            ['total_listeners' => 0, 'hourly_listeners' => []]
        );
        
        // Update total listeners
        $stats->increment('total_listeners');
        
        // Update hourly listeners
        $hourlyListeners = $stats->hourly_listeners ?? [];
        $hourlyListeners[$currentHour] = ($hourlyListeners[$currentHour] ?? 0) + 1;
        
        $stats->hourly_listeners = $hourlyListeners;
        $stats->save();
    }

    public function search(Request $request)
{
    $query = $request->input('search');
    
    $radios = StasiunRadio::when($query, function($q) use ($query) {
                return $q->where('nama_stasiun', 'like', '%'.$query.'%')
                        ->orWhere('deskripsi', 'like', '%'.$query.'%');
            })
            ->orderBy('jumlah_pendengar', 'desc')
            ->paginate(10);
    
    return view('pendengar.radio.index', compact('radios'));
}

/**
 * Store play history when user starts listening
 */
public function storePlayHistory(Request $request)
{
    $request->validate([
        'stasiun_radio_id' => 'required|exists:stasiun_radio,id'
    ]);

    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    // Cek apakah sudah ada riwayat dalam 5 menit terakhir untuk radio yang sama
    $existing = RiwayatPemutaran::where('user_id', Auth::id())
        ->where('stasiun_radio_id', $request->stasiun_radio_id)
        ->where('played_at', '>=', now()->subMinutes(5))
        ->first();

    if ($existing) {
        $existing->update([
            'played_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Riwayat diperbarui',
            'data' => $existing,
            'is_new' => false
        ]);
    }

    // Buat riwayat baru
    $riwayat = RiwayatPemutaran::create([
        'user_id' => Auth::id(),
        'stasiun_radio_id' => $request->stasiun_radio_id,
        'played_at' => now(),
        'durasi' => 0
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Riwayat dicatat',
        'data' => $riwayat,
        'is_new' => true
    ]);
}

/**
 * Update play duration when user stops listening
 */
public function updatePlayDuration(Request $request, $id)
{
    $request->validate([
        'durasi' => 'required|integer|min:0'
    ]);

    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    $riwayat = RiwayatPemutaran::where('user_id', Auth::id())
        ->where('id', $id)
        ->first();

    if (!$riwayat) {
        return response()->json([
            'success' => false,
            'message' => 'Riwayat tidak ditemukan'
        ], 404);
    }

    $riwayat->update([
        'durasi' => $request->durasi
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Durasi diperbarui',
        'data' => $riwayat
    ]);
}
}