<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Session;

class BacaController extends Controller
{
    public function show($id)
    {
        // 1. Ambil berita utama
        $berita = Berita::with('penulis')
                  ->where('status_unggah', 'diterima')
                  ->findOrFail($id);

        // 2. Increment view count
        $berita->increment('views');

        // 3. Track viewed news - dengan perbaikan
        $this->trackViewedNews($berita->id);

        // 4. Ambil related news (berita dengan topik sama)
        $relatedNews = Berita::where('topik', $berita->topik)
                       ->where('id', '!=', $id)
                       ->where('status_unggah', 'diterima')
                       ->orderBy('created_at', 'desc')
                       ->limit(4)
                       ->get();

        // 5. Ambil berita populer
        $beritaPopuler = Berita::where('status_unggah', 'diterima')
                          ->orderBy('views', 'desc')
                          ->limit(5)
                          ->get();

        return view('pendengar.baca.show', compact(
            'berita',
            'relatedNews',
            'beritaPopuler'
        ));
    }

    /**
     * Method untuk tracking berita yang dibaca
     * - Memastikan urutan terbaru selalu di depan
     * - Menghindari duplikasi
     * - Membatasi hanya 5 berita terakhir
     */
    protected function trackViewedNews($id)
    {
        $dibaca = Session::get('berita_dibaca', []);
        
        // Hapus ID jika sudah ada (untuk menghindari duplikat)
        $dibaca = array_values(array_diff($dibaca, [$id]));
        
        // Tambahkan ID baru di awal array
        array_unshift($dibaca, $id);
        
        // Simpan hanya 5 terakhir
        $dibaca = array_slice($dibaca, 0, 5);
        
        // Simpan ke session dan pastikan tersimpan
        Session::put('berita_dibaca', $dibaca);
        Session::save();
    }
}