<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StasiunRadio;

class UserPendengar extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        if (!auth()->user()->isPendengar()) {
            abort(403, 'Unauthorized access');
        }

        $search = request()->query('search');

        $query = StasiunRadio::query();

        if ($search) {
            $query->where('nama_stasiun', 'like', "%{$search}%");
        }

        $radios = $query->get();

        $groupedRadios = $radios->groupBy('wilayah'); // Ganti 'wilayah' jika nama kolom beda
        $featuredRadios = StasiunRadio::orderByDesc('jumlah_pendengar')->take(10)->get();

        return view('pendengar.radio.index', [
            'featuredRadios' => $featuredRadios,
            'groupedRadios' => $groupedRadios,
            'search' => $search,
        ]);
    }
}
