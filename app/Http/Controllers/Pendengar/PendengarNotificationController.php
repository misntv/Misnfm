<?php

namespace App\Http\Controllers\Pendengar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendengarNotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('pendengar.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        return redirect($notif->data['url'] ?? route('pendengar.dashboard'));
    }

    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca');
    }

    public function deleteAll(Request $request)
    {
        auth()->user()->notifications()->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'deleted']);
        }

        return back()->with('success', 'Semua notifikasi berhasil dihapus');
    }

}
