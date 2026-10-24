<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StasiunRadio;
use App\Models\RadioListenerStats;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\NewRadioNotification;
use App\Models\User;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playlists = StasiunRadio::where('is_archieved', false)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('admin.playlist.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.playlist.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_stasiun' => 'required|string|max:255|unique:stasiun_radio,nama_stasiun',
            'nama_daerah' => 'required|string|max:255',
            'url_stasiun' => 'required|url|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'required|string',
            'genre' => 'required|string|max:100',
        ],

            ['nama_stasiun.unique' => 'Stasiun radio sudah ditambahkan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan stasiun radio');
        }

        // Handle file upload
        $imageName = null;
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = Str::slug($request->nama_stasiun).'-'.time().'.'.$image->getClientOriginalExtension();
            
            // Simpan ke folder public_html
            $image->storeAs('thumbnails', $imageName, 'public_html'); 
        }

        $playlist = StasiunRadio::create([
            'nama_stasiun' => $request->nama_stasiun,
            'nama_daerah' => $request->nama_daerah,
            'url_stasiun' => $request->url_stasiun,
            'feed_url' => $request->feed_url,
            'jadwal_url' => $request->jadwal_url,
            'thumbnail' => $imageName,
            'deskripsi' => $request->deskripsi,
            'genre' => $request->genre,
            'tanggal_upload' => now()->format('Y-m-d'),
            'jam_upload' => now()->format('H:i:s'),
            'jumlah_pendengar' => 0,
            'is_archieved' => false
        ]);

        // 🔔 Kirim notifikasi ke semua pendengar
        $pendengarUsers = User::where('role', 'pendengar')->get();
        foreach ($pendengarUsers as $user) {
            $user->notify(new NewRadioNotification($playlist));
        }

        return redirect()->route('playlist.index')->with('success', 'Playlist berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $playlist = StasiunRadio::findOrFail($id);
        return view('admin.playlist.show', compact('playlist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $playlist = StasiunRadio::findOrFail($id);
        return view('admin.playlist.edit', compact('playlist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_stasiun' => 'required|string|max:255|unique:stasiun_radio,nama_stasiun,'.$id,
            'nama_daerah' => 'required|string|max:255',
            'url_stasiun' => 'required|url|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'required|string',
            'genre' => 'required|string|max:100',
        ],
        [
            'nama_stasiun.unique' => 'Stasiun radio sudah ditambahkan',
        ]);
        

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan stasiun radio');
        }

        $playlist = StasiunRadio::findOrFail($id);
        $data = $request->only(['nama_stasiun', 'nama_daerah', 'url_stasiun', 'feed_url', 'jadwal_url', 'deskripsi', 'genre']);

        // Handle file upload if new thumbnail is provided
        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama di public_html (jika ada)
            if ($playlist->thumbnail && Storage::disk('public_html')->exists('thumbnails/'.$playlist->thumbnail)) {
                Storage::disk('public_html')->delete('thumbnails/'.$playlist->thumbnail);
            }
            
            $image = $request->file('thumbnail');
            $imageName = Str::slug($request->nama_stasiun).'-'.time().'.'.$image->getClientOriginalExtension();
            
            // Simpan gambar baru ke public_html
            $image->storeAs('thumbnails', $imageName, 'public_html');
            
            $data['thumbnail'] = $imageName;
        }

        $playlist->update($data);

        return redirect()->route('playlist.index')->with('success', 'Playlist berhasil diperbarui');
    }

    /**
     * Archive the specified resource.
     */
    public function archive(string $id)
    {
        $playlist = StasiunRadio::findOrFail($id);
        $playlist->update(['is_archieved' => true]);

        return redirect()->route('playlist.index')->with('success', 'Playlist berhasil diarsipkan');
    }

    /**
     * Display a listing of archived resources.
     */
    public function archived()
    {
        $playlists = StasiunRadio::where('is_archieved', true)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('admin.playlist.archived', compact('playlists'));
    }

    /**
     * Restore the specified resource from archive.
     */
    public function restore(string $id)
    {
        $playlist = StasiunRadio::findOrFail($id);
        $playlist->update(['is_archieved' => false]);

        return redirect()->route('playlist.archived')->with('success', 'Playlist berhasil dikembalikan');
    }

    /**
     * Player for streaming
     */
    public function player($id)
    {
        $playlist = StasiunRadio::findOrFail($id);
        return view('admin.playlist.player', compact('playlist'));
    }

    /**
     * Increment listener count
     */
        public function incrementListener($radioId)
{
    try {
        DB::transaction(function () use ($radioId) {
            // 1. Update total listener di tabel radio
            $radio = StasiunRadio::findOrFail($radioId);
            $radio->increment('jumlah_pendengar');
            
            // 2. Catat statistik harian
            $today = now()->format('Y-m-d');
            $currentHour = now()->hour;
            
            $stat = RadioListenerStats::firstOrCreate(
                [
                    'radio_id' => $radioId, 
                    'stat_date' => $today
                ],
                [
                    'total_listeners' => 0, 
                    'hourly_listeners' => []
                ]
            );
            
            // 3. Increment total harian
            $stat->increment('total_listeners');
            
            // 4. Update data per jam
            $hourlyData = $stat->hourly_listeners ?: [];
            $hourlyData[$currentHour] = ($hourlyData[$currentHour] ?? 0) + 1;
            $stat->hourly_listeners = $hourlyData;
            
            $stat->save();
        });
        
        return true;
    } catch (\Exception $e) {
        Log::error("Gagal mencatat pendengar: ".$e->getMessage());
        return false;
    }
}

        public function periodStatistics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $radios = StasiunRadio::whereBetween('tanggal_upload', [$startDate, $endDate])
                    ->where('is_archieved', false)
                    ->orderBy('jumlah_pendengar', 'desc')
                    ->get();
        
        return view('admin.statistic.statistics', compact('radios', 'startDate', 'endDate'));
    }

    // Controller
public function showStatistics(Request $request)
{
    $radioId = $request->input('radio_id', 1);
    $year = $request->input('year', date('Y'));
    $month = $request->input('month', date('m'));
    
    return view('admin.statistic.statistics', [
        'monthlyData' => $this->getMonthlyStats($radioId, $year),
        'dailyData' => $this->getDailyStats($radioId, $year, $month),
        'selectedYear' => $year,
        'selectedMonth' => $month
    ]);
}

        /**
     * Display radio statistics.
     */


public function statistics(Request $request)
{
    // 1. Get all active radios for dropdown
    $allRadios = StasiunRadio::where('is_archieved', false)
                    ->orderBy('nama_stasiun')
                    ->get();

    // 2. Get filter parameters
    $selectedRadioId = $request->input('radio_id', $allRadios->first()->id ?? null);
    $timePeriod = $request->input('time_period', 'monthly');
    $selectedYear = $request->input('year', date('Y'));
    $selectedMonth = $request->input('month', date('m'));
    
    // 3. Prepare filter data
    $availableYears = range(date('Y'), date('Y') - 4);
    rsort($availableYears);
    
    $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    // 4. Get selected radio
    $selectedRadio = $selectedRadioId ? StasiunRadio::find($selectedRadioId) : null;
    
    // 5. Get top 10 radios
    $topRadios = StasiunRadio::where('is_archieved', false)
                    ->orderBy('jumlah_pendengar', 'desc')
                    ->take(10)
                    ->get();

    // 6. Generate statistics data
    $statisticsData = [];
    $labels = [];
    $hourlyData = [];
    $visibleRows = 5;
    $totalListeners = 0;
    
    if ($selectedRadio) {
        if ($timePeriod === 'monthly') {
            // Monthly statistics
            $rawData = $this->getMonthlyStats($selectedRadio->id, $selectedYear);
            
            // Fill all months with data (including zeros for missing months)
            foreach (range(1, 12) as $month) {
                $monthKey = sprintf('%02d', $month);
                $labels[] = $months[$monthKey];
                $statisticsData[] = $rawData[$monthKey] ?? 0;
            }
            $totalListeners = array_sum($rawData);

        } else {
            // Daily statistics
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
            $rawData = $this->getDailyStats($selectedRadio->id, $selectedYear, $selectedMonth);
            
            // Fill all days with data
            foreach (range(1, $daysInMonth) as $day) {
                $dayKey = sprintf('%02d', $day);
                $labels[] = $dayKey;
                $statisticsData[] = $rawData[$dayKey] ?? 0;
            }
            $totalListeners = array_sum($rawData);
            
            // Get hourly data for first day of the month as example
            if (count($rawData) > 0) {
                $firstDay = array_key_first($rawData);
                $hourlyData = RadioListenerStats::where('radio_id', $selectedRadio->id)
                    ->whereDate('stat_date', $selectedYear.'-'.$selectedMonth.'-'.$firstDay)
                    ->first()
                    ->hourly_listeners ?? [];
            }
        }
    }

    return view('admin.statistic.statistics', compact(
        'allRadios',
        'selectedRadio',
        'selectedRadioId',
        'timePeriod',
        'statisticsData',
        'labels',
        'topRadios',
        'availableYears',
        'selectedYear',
        'months',
        'selectedMonth',
        'hourlyData',
        'visibleRows',
        'totalListeners'
    ));
}

// Helper untuk statistik bulanan (berdasarkan jumlah_pendengar saat ini)
public function getMonthlyStats($radioId, $year)
{
    return RadioListenerStats::where('radio_id', $radioId)
        ->whereYear('stat_date', $year)
        ->selectRaw('DATE_FORMAT(stat_date, "%m") as month, SUM(total_listeners) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();
}

// Helper untuk statistik harian
public function getDailyStats($radioId, $year, $month)
{
    return RadioListenerStats::where('radio_id', $radioId)
        ->whereYear('stat_date', $year)
        ->whereMonth('stat_date', $month)
        ->selectRaw('DATE_FORMAT(stat_date, "%d") as day, SUM(total_listeners) as total')
        ->groupBy('day')
        ->orderBy('day')
        ->pluck('total', 'day')
        ->toArray();
}



/**
 * Search for radios
 */
// PlaylistController.php

public function search(Request $request)
{
    $query = StasiunRadio::where('is_archieved', false);
    
    // Hanya pertahankan search dan filter listeners
    if ($request->has('search') && !empty($request->search)) {
        $query->where(function($q) use ($request) {
            $q->where('nama_stasiun', 'LIKE', '%'.$request->search.'%')
              ->orWhere('deskripsi', 'LIKE', '%'.$request->search.'%');
        });
    }

    // Filter listeners (opsional, bisa dihapus juga jika tidak needed)
    if ($request->has('filter_listeners')) {
        switch ($request->filter_listeners) {
            case 'high': $query->where('jumlah_pendengar', '>', 1000); break;
            case 'medium': $query->whereBetween('jumlah_pendengar', [100, 1000]); break;
            case 'low': $query->where('jumlah_pendengar', '<', 100); break;
        }
    }
    
    $playlists = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.playlist.index', compact('playlists'));
}

/**
 * Cek RSS Feed dan ambil genre
 */
public function cekRss(Request $request)
{
    $request->validate([
        'feed_url' => 'required|url'
    ]);

    try {
        $rssService = new \App\Services\RssFeedService();
        $genre = $rssService->getLatestGenre($request->feed_url);

        if ($genre) {
            return response()->json([
                'success' => true,
                'genre' => $genre,
                'message' => 'Genre berhasil diambil dari RSS'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak dapat mengambil genre dari RSS. Pastikan URL RSS valid.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

public function cekJadwal(Request $request)
{
    $request->validate([
        'jadwal_url' => 'required|url'
    ]);

    try {
        $service = new \App\Services\JadwalTayangService();
        $genre = $service->getGenreFromJadwal($request->jadwal_url);

        if ($genre) {
            return response()->json([
                'success' => true,
                'genre' => $genre,
                'message' => 'Genre berhasil diambil dari jadwal tayang'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak dapat mengambil genre dari jadwal tayang.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}
}