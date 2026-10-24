<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('admin.notification.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        return redirect($notif->data['url'] ?? route('admin.dashboard'));
    }

    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back();
    }

    public function deleteAll(Request $request)
    {
        auth()->user()->notifications()->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'deleted']);
        }

        return back();
    }

}