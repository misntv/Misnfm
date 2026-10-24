<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\StasiunRadio;
use App\Models\User;
use App\Models\RadioListenerStats;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tahun saat ini
        $currentYear = Carbon::now()->year;

        // Query untuk mendapatkan 5 stasiun radio dengan pendengar terbanyak (hanya yang aktif)
        $topStations = StasiunRadio::query()
            ->where('is_archieved', false) // Hanya stasiun aktif
            ->select([
                'stasiun_radio.id',
                'stasiun_radio.nama_stasiun',
                DB::raw('CAST(COALESCE(SUM(radio_listener_stats.total_listeners), 0) AS UNSIGNED) as total_listeners')
            ])
            ->leftJoin('radio_listener_stats', function ($join) use ($currentYear) {
                $join->on('stasiun_radio.id', '=', 'radio_listener_stats.radio_id')
                    ->whereYear('radio_listener_stats.stat_date', $currentYear);
            })
            ->groupBy('stasiun_radio.id', 'stasiun_radio.nama_stasiun')
            ->orderByDesc('total_listeners')
            ->take(5)
            ->get();

        // Ambil data bulanan untuk masing-masing stasiun radio
        $monthlyData = [];
        foreach ($topStations as $station) {
            $stats = RadioListenerStats::where('radio_id', $station->id)
                ->whereYear('stat_date', $currentYear)
                ->selectRaw('MONTH(stat_date) as month, CAST(SUM(total_listeners) AS UNSIGNED) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total', 'month')
                ->toArray();

            $monthlyData[$station->id] = array_replace(
                array_fill(1, 12, 0),
                $stats
            );
        }

        // Hitung jumlah berita berdasarkan status (hanya berita aktif/non-arsip)
        $stationCount = StasiunRadio::where('is_archieved', false)->count();
        $publishedNewsCount = Berita::where('status_unggah', 'diterima')
                                    ->where('is_archieved', false) // Hanya berita aktif
                                    ->count();
        $submittedNewsCount = Berita::where('status_unggah', 'pengajuan')
                                    ->where('is_archieved', false) // Hanya berita aktif
                                    ->count();
        $rejectedNewsCount = Berita::where('status_unggah', 'ditolak')
                                  ->where('is_archieved', false) // Hanya berita aktif
                                  ->count();
        $journalistCount = User::where('role', 'wartawan')->count();

        return view('admin.dashboard.admin', compact(
            'topStations',
            'monthlyData',
            'currentYear',
            'stationCount',
            'publishedNewsCount',
            'submittedNewsCount',
            'rejectedNewsCount',
            'journalistCount'
        ));
    }
}