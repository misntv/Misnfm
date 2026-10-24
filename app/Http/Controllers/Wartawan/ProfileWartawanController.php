<?php

namespace App\Http\Controllers\Wartawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileWartawanController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('wartawan.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('wartawan.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'no_identitas'     => 'required|string|max:100',
            'username'         => 'required|string|max:100|unique:user_misnfm,username,' . $user->id,
            'no_telp'          => 'nullable|string|max:20',
            'email'            => 'required|email|unique:user_misnfm,email,' . $user->id,
            'alamat'           => 'nullable|string|max:1000', // ← tambahkan validasi alamat
            'url_foto_profil'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload file baru jika ada
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
            'alamat', // ← tambahkan di sini juga
        ]));

        return redirect()->route('wartawan.profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

}
