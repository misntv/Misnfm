<?php

namespace App\Http\Controllers\Pendengar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePendengarController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // Tetap pakai default guard
        if (!$user || $user->role !== 'pendengar') {
            abort(403); // Akses ditolak jika bukan pendengar
        }

        return view('pendengar.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendengar') {
            abort(403);
        }

        return view('pendengar.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendengar') {
            abort(403);
        }

        $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'no_identitas'     => 'required|string|max:100',
            'username'         => 'required|string|max:100|unique:user_misnfm,username,' . $user->id,
            'no_telp'          => 'nullable|string|max:20',
            'email'            => 'required|email|unique:user_misnfm,email,' . $user->id,
            'url_foto_profil'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('url_foto_profil')) {
            if ($user->url_foto_profil && Storage::disk('public')->exists($user->url_foto_profil)) {
                Storage::disk('public')->delete($user->url_foto_profil);
            }

            $path = $request->file('url_foto_profil')->store('foto_profile', 'public');
            $user->url_foto_profil = $path;
        }

        $user->update($request->only([
            'nama_lengkap',
            'no_identitas',
            'username',
            'no_telp',
            'email',
        ]));

        return redirect()->route('pendengar.profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
