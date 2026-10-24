<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ✅ Halaman verifikasi akun wartawan + daftar anggota
    public function verifikasiWartawan()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Wartawan yang belum disetujui
        $unapprovedWartawan = User::where('role', 'wartawan')
            ->whereNull('approved_at')
            ->get();

        // Wartawan yang sudah disetujui
        $approvedWartawan = User::where('role', 'wartawan')
            ->whereNotNull('approved_at')
            ->get();

        return view('admin.wartawan.verifikasi', compact('unapprovedWartawan', 'approvedWartawan'));
    }

    // ✅ Menerima wartawan
    public function approveWartawan($id)
    {
        $wartawan = User::findOrFail($id);

        if ($wartawan->role !== 'wartawan') {
            return back()->with('error', 'Hanya akun wartawan yang dapat disetujui.');
        }

        $wartawan->update(['approved_at' => now()]);
        return back()->with('success', 'Akun wartawan telah disetujui.');
    }

    // ✅ Menolak wartawan
    public function rejectWartawan($id)
    {
        $wartawan = User::findOrFail($id);

        if ($wartawan->role !== 'wartawan') {
            return back()->with('error', 'Hanya akun wartawan yang dapat ditolak.');
        }

        // Bisa dihapus langsung atau update status jadi "ditolak"
        $wartawan->delete();

        return back()->with('success', 'Akun wartawan telah ditolak.');
    }
}