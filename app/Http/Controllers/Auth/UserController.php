<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $request->login, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek jika wartawan belum disetujui
            if ($user->isWartawan() && !$user->isApproved()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Akun wartawan Anda belum disetujui admin.');
            }

            return $this->redirectToDashboard($user);
        }

        return back()->with('error', 'Kredensial tidak valid.');
    }

    protected function redirectToDashboard(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard.admin');
        } elseif ($user->isWartawan()) {
            return redirect()->route('wartawan.dashboard.wartawan');
        } elseif ($user->isPendengar()) {
            return redirect()->route('pendengar.radio.index');
        }

        return redirect('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            Log::info('Google login attempt', [
                'email' => $googleUser->email,
                'id' => $googleUser->id,
            ]);

            $user = User::where('email', $googleUser->email)
                        ->orWhere('provider_id', $googleUser->id)
                        ->first();

            if (!$user) {
                $user = User::create([
                    'nama_lengkap'     => $googleUser->name,
                    'email'            => $googleUser->email,
                    'username'         => $this->generateUniqueUsername($googleUser),
                    'password'         => Hash::make(Str::random(16)),
                    'role'             => 'pendengar',
                    'provider_id'      => $googleUser->id,
                    'provider'         => 'google',
                    'url_foto_profil'  => $googleUser->avatar,
                    'approved_at'      => now()
                ]);
            } elseif (empty($user->provider_id)) {
                $user->update([
                    'provider_id' => $googleUser->id,
                    'provider'    => 'google'
                ]);
            }

            Auth::login($user, true);
            return $this->redirectToDashboard($user);

        } catch (\Exception $e) {
            Log::error('Login Google error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    protected function generateUniqueUsername($googleUser)
    {
        $username = Str::slug(Str::before($googleUser->email, '@'));
        $original = $username;
        $count = 1;

        while (User::where('username', $username)->exists()) {
            $username = $original . $count++;
        }

        return $username;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Cegah kembali ke halaman setelah logout
        return redirect('/login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}