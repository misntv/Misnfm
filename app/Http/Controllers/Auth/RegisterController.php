<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Notifications\WartawanRegisteredNotification;

class RegisterController extends Controller
{
    // Tampilkan pilihan pendaftaran (Wartawan/Pendengar)
    public function showRoleSelection()
    {
        return view('auth.select-role');
    }

    // Tampilkan form pendaftaran
    public function showRegistrationForm($userType)
    {
        if (!in_array($userType, ['wartawan', 'pendengar'])) {
            abort(404);
        }

        return view('auth.register', ['userType' => $userType]);
    }

    // Proses pendaftaran
    public function register(Request $request, $userType)
    {
        if ($userType === 'wartawan') {
            $validator = $this->validateWartawan($request->all());
        } else {
            $validator = $this->validatePendengar($request->all());
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = $this->createUserData($request, $userType);
        $user = User::create($userData);

        if ($userType === 'wartawan') {
            // Kirim notifikasi ke semua admin_radio
            $admins = User::where('role', 'admin_radio')->get();
            foreach ($admins as $admin) {
                $admin->notify(new WartawanRegisteredNotification($user));
            }

            return redirect()->route('register.success')->with([
                'title' => 'Verifikasi Akun Wartawan',
                'message' => 'Proses verifikasi diselesaikan maksimal 2x24 jam. Silakan periksa akun Anda secara berkala untuk pembaruan status.'
            ]);
        }

        // Jika pendengar, langsung login
        auth()->login($user);
        return redirect()->route('pendengar.radio.index');
    }

    protected function validateWartawan(array $data)
    {
        return Validator::make($data, [
            'nama_lengkap' => 'required|string|min:3|max:255',
            'no_identitas' => 'required|digits_between:8,16|unique:user_misnfm,no_identitas',
            'email' => 'required|email:rfc,dns|max:255|unique:user_misnfm,email',
            'no_telp' => 'required|regex:/^[0-9]{10,15}$/',
            'asal_perusahaan' => 'required|string|min:3|max:255',
            'username' => 'required|alpha_num|min:3|max:20|unique:user_misnfm,username',
            'alamat' => 'required|string|min:5|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'url_foto_kartu_identitas' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'username.unique' => 'Username sudah digunakan.',
            'no_identitas.unique' => 'Nomor identitas sudah terdaftar.',
            'no_telp.regex' => 'Nomor telepon harus terdiri dari 10-15 digit angka.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'url_foto_kartu_identitas.max' => 'Ukuran file maksimal 2MB.',
            'url_foto_kartu_identitas.mimes' => 'Format file harus jpeg, png, atau jpg.',
        ]);
    }

    protected function validatePendengar(array $data)
    {
        return Validator::make($data, [
            'nama_lengkap' => 'required|string|min:3|max:255',
            'username' => 'required|alpha_num|min:3|max:20|unique:user_misnfm,username',
            'email' => 'required|email:rfc,dns|max:255|unique:user_misnfm,email',
            'no_telp' => 'required|regex:/^[0-9]{10,15}$/',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'username.unique' => 'Username sudah digunakan.',
            'no_telp.regex' => 'Nomor telepon harus terdiri dari 10-15 digit angka.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    }

    protected function createUserData(Request $request, $role)
    {
        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'role' => $role,
            'approved_at' => $role === 'pendengar' ? now() : null,
        ];

        if ($role === 'wartawan') {
            $userData['no_identitas'] = $request->no_identitas;
            $userData['asal_perusahaan'] = $request->asal_perusahaan;
            $userData['alamat'] = $request->alamat;

            if ($request->hasFile('url_foto_kartu_identitas')) {
                $path = $request->file('url_foto_kartu_identitas')->store('public/identitas');
                $userData['url_foto_kartu_identitas'] = Storage::url($path);
            }
        }

        return $userData;
    }

    // Tampilkan halaman sukses pendaftaran
    public function showSuccess()
    {
        if (!session()->has('title')) {
            return redirect()->route('register.role');
        }

        return view('auth.register-success');
    }
}