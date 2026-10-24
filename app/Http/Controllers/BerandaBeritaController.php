<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BerandaBeritaController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Search functionality
            $search = $request->input('search');
            
            // Base query for accepted news only
            $query = Berita::where('status_unggah', 'diterima')
                          ->with('penulis');
            
            // Apply search if exists
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('judul_berita', 'like', '%'.$search.'%')
                      ->orWhere('isi_berita', 'like', '%'.$search.'%')
                      ->orWhere('topik', 'like', '%'.$search.'%');
                });
            }

            // Get data sections with status filter
            $beritaTerbaru = Berita::where('status_unggah', 'diterima')
                                  ->orderBy('tanggal_terbit', 'desc')
                                  ->take(10)
                                  ->get();

            $beritaTerpopuler = Berita::where('status_unggah', 'diterima')
                                    ->orderBy('views', 'desc')
                                    ->take(10)
                                    ->get();

            // Improved recently viewed functionality
            $baruDibaca = $this->getRecentlyViewed();

            // Group by topic only for accepted news
            $beritaPerTopik = Berita::where('status_unggah', 'diterima')
                                  ->select('topik')
                                  ->distinct()
                                  ->get()
                                  ->mapWithKeys(function ($item) {
                                      return [
                                          $item->topik => Berita::where('status_unggah', 'diterima')
                                                              ->where('topik', $item->topik)
                                                              ->take(10)
                                                              ->get()
                                      ];
                                  });

            // Main content with pagination
            $semuaBerita = $query->orderBy('tanggal_terbit', 'desc')
                                ->paginate(10);

            return view('pendengar.berita.index', compact(
                'semuaBerita',
                'beritaTerbaru',
                'beritaTerpopuler',
                'baruDibaca',
                'beritaPerTopik',
                'search'
            ));

        } catch (\Exception $e) {
            Log::error('Error in BerandaBeritaController: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat berita');
        }
    }

    public function show($id)
    {
        try {
            // Get the news article with status check
            $berita = Berita::with('penulis')
                           ->where('status_unggah', 'diterima')
                           ->findOrFail($id);

            // Track viewed news
            $this->trackViewedNews($id);

            // Increment view count
            $berita->increment('views');

            return view('pendengar.baca.show', compact('berita'));

        } catch (\Exception $e) {
            Log::error('Error showing berita: '.$e->getMessage());
            return back()->with('error', 'Berita tidak ditemukan atau belum disetujui');
        }
    }

    protected function getRecentlyViewed()
    {
        $dibaca = Session::get('berita_dibaca', []);
        
        if (empty($dibaca)) {
            return collect();
        }

        // Get news in the exact order they were viewed
        return Berita::whereIn('id', $dibaca)
                   ->where('status_unggah', 'diterima')
                   ->orderByRaw('FIELD(id, '.implode(',', $dibaca).')')
                   ->take(5)
                   ->get();
    }

    protected function trackViewedNews($id)
    {
        $dibaca = Session::get('berita_dibaca', []);
        
        // Remove if already exists
        if (($key = array_search($id, $dibaca)) !== false) {
            unset($dibaca[$key]);
        }
        
        // Add to beginning of array
        array_unshift($dibaca, $id);
        
        // Keep only last 5 items
        $dibaca = array_slice($dibaca, 0, 5);
        
        // Update session
        Session::put('berita_dibaca', array_values($dibaca));
        Session::save();
    }
}