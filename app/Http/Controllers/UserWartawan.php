<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Berita;

class UserWartawan extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->isWartawan()) {
            abort(403, 'Unauthorized access');
        }

        if (!$user->isApproved()) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'login' => 'Your account needs admin approval'
            ]);
        }

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

        $monthlyReaders = array_replace(array_fill(1, 12, 0), $monthlyReaders);

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
