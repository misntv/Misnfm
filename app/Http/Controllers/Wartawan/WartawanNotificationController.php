<?php

namespace App\Http\Controllers\Wartawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WartawanNotificationController extends Controller
{
    public function index()
    {
        return view('wartawan.notifikasi.index', [
            'notifications' => auth()->user()->notifications()->latest()->get()
        ]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        if ($notification && $notification->unread()) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url'] ?? route('wartawan.notifikasi.index'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca.');
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}