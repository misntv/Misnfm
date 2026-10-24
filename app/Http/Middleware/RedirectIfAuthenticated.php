<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Pastikan user memiliki property role
                if (!property_exists($user, 'role')) {
                    Auth::guard($guard)->logout();
                    return redirect('/login')->with('error', 'Invalid user role');
                }

                // Redirect berdasarkan role
                switch ($user->role) {
                    case 'admin_radio':
                        return redirect()->route('admin.dashboard.admin');
                    case 'wartawan':
                        return is_null($user->approved_at) 
                            ? redirect('/login')->with('error', 'Akun wartawan belum disetujui')
                            : redirect()->route('wartawan.dashboard.wartawan');
                    case 'pendengar':
                        return redirect()->route('pendengar.radio.index');
                    default:
                        Auth::guard($guard)->logout();
                        return redirect('/login')->with('error', 'Role tidak dikenali');
                }
            }
        }

        return $next($request);
    }
}