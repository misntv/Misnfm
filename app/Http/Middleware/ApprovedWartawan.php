<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedWartawan
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->role === 'wartawan' && is_null($user->approved_at)) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'login' => 'Akun wartawan Anda belum disetujui oleh admin.'
            ]);
        }

        return $next($request);
    }
}