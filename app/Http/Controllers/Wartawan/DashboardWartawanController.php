<?php

namespace App\Http\Controllers\Wartawan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Berita;

class DashboardWartawanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;
        $currentYear = Carbon::now()->year;

        // Statistik views bulanan
        $monthlyReaders = DB::table('berita')
            ->selectRaw('MONTH(tanggal_terbit) as bulan, SUM(views) as total')
            ->where('penulis_id', $userId)
            ->whereNotNull('tanggal_terbit')
            ->where('status_unggah', 'diterima')
            ->whereYear('tanggal_terbit', $currentYear)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Mengisi 12 bulan dari 1 sampai 12
        $filledReaders = array_replace(array_fill(1, 12, 0), $monthlyReaders);

        // Ubah jadi array numerik dengan urutan bulan dari Jan–Des (index mulai dari 0)
        $monthlyReaders = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyReaders[] = $filledReaders[$i];
        }

        // Statistik berita
        $uploadedCount = Berita::where('penulis_id', $userId)
            ->where('status_unggah', 'diterima')
            ->where('is_archieved', false)
            ->count();

        $submittedCount = Berita::where('penulis_id', $userId)
            ->where('status_unggah', 'pengajuan')
            ->where('is_archieved', false)
            ->count();

        $rejectedCount = Berita::where('penulis_id', $userId)
            ->where('status_unggah', 'ditolak')
            ->where('is_archieved', false)
            ->count();

        return view('wartawan.dashboard.wartawan', compact(
            'monthlyReaders',
            'currentYear',
            'uploadedCount',
            'submittedCount',
            'rejectedCount'
        ));
    }
}
