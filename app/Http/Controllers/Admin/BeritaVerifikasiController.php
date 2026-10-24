<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Notifications\NewNewsNotification;
use App\Notifications\BeritaStatusNotification;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BeritaVerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar untuk berita yang belum diarsipkan
        $queryPengajuan = Berita::where('is_archieved', false)
                                ->where('status_unggah', 'pengajuan');
        
        $queryDiterima = Berita::where('is_archieved', false)
                                ->where('status_unggah', 'diterima');
        
        $queryDitolak = Berita::where('is_archieved', false)
                                ->where('status_unggah', 'ditolak');

        // Jika ada pencarian, tambahkan kondisi where
        if ($search) {
            $searchTerm = "%{$search}%";
            
            $queryPengajuan->where(function($q) use ($searchTerm) {
                $q->where('judul_berita', 'like', $searchTerm)
                  ->orWhere('topik', 'like', $searchTerm);
            });
            
            $queryDiterima->where(function($q) use ($searchTerm) {
                $q->where('judul_berita', 'like', $searchTerm)
                  ->orWhere('topik', 'like', $searchTerm);
            });
            
            $queryDitolak->where(function($q) use ($searchTerm) {
                $q->where('judul_berita', 'like', $searchTerm)
                  ->orWhere('topik', 'like', $searchTerm);
            });
        }

        // Ambil data
        $beritaPengajuan = $queryPengajuan->get();
        $beritaDiterima = $queryDiterima->get();
        $beritaDitolak = $queryDitolak->get();

        // Hitung jumlah total
        $totalPengajuan = $beritaPengajuan->count();
        $totalDiterima = $beritaDiterima->count();
        $totalDitolak = $beritaDitolak->count();

        return view('admin.berita.verifikasi', compact(
            'beritaPengajuan',
            'beritaDiterima',
            'beritaDitolak',
            'totalPengajuan',
            'totalDiterima',
            'totalDitolak',
            'search'
        ));
    }

    public function terima(Berita $berita)
    {
        $berita->update([
            'status_unggah' => 'diterima',
            'tanggal_terbit' => now()->format('Y-m-d'),
            'jam_terbit' => now()->format('H:i:s'),
        ]);

        // Kirim notifikasi ke wartawan
        if ($berita->penulis) {
            $berita->penulis->notify(new BeritaStatusNotification($berita, 'diterima'));
        }

        $pendengar = User::where('role', 'pendengar')->get();
        Log::info('Jumlah pendengar:', ['count' => $pendengar->count()]);

        $pendengar = User::where('role', 'pendengar')->get();
        foreach ($pendengar as $user) {
            $user->notify(new NewNewsNotification($berita));
        }

        return back()->with('success', 'Berita telah diterima');
    }

    public function tolak(Berita $berita)
    {
        $berita->update([
            'status_unggah' => 'ditolak',
        ]);

        // Kirim notifikasi ke wartawan
        if ($berita->penulis) {
            $berita->penulis->notify(new BeritaStatusNotification($berita, 'ditolak'));
        }

        return back()->with('success', 'Berita telah ditolak');
    }

    public function show(Berita $berita)
    {
        return view('admin.berita.detail', compact('berita'));
    }
}