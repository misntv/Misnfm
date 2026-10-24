<?php

namespace App\Http\Controllers;


use App\Models\StasiunRadio;
use Illuminate\Http\Request;

class RadioPlayerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);
        
        $query = StasiunRadio::query();

        // Kalau mau, bisa filter yang belum diarsipkan
        // $query->where('is_archieved', false);

        $total = $query->count();
        $stations = $query->select([
                'id',
                'nama_stasiun',
                'url_stasiun',
                'thumbnail',
                'tanggal_upload',
                'jam_upload',
                'deskripsi',
                'jumlah_pendengar',
                'is_archieved'
            ])
            ->orderBy('nama_stasiun')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        
        $total_pages = ceil($total / $perPage);
        $startItem = ($page - 1) * $perPage + 1;
        $endItem = min($page * $perPage, $total);
        
        return view('playradio', [
            'radioStations' => $stations,
            'page' => $page,
            'total_pages' => $total_pages,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'total' => $total
        ]);
    }
}
