@extends('layouts.app')

@section('content')
<style>
    nav.navbar { display: none !important; }

    html, body {
        background-color: #AFDDE5;
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        box-sizing: border-box;
    }

    .card.shadow-lg.login-content {
        max-width: 600px;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        display: flex;
        background: white;
        flex-direction: row;
    }

    .left-column,
    .right-column {
        flex: 1;
        max-width: 50%;
        padding: 20px 16px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .left-column {
        background-color: #fff;
        align-items: center;
        text-align: center;
    }

    .left-column img {
        max-height: 90px;
        margin-bottom: 10px;
    }

    .left-column .tagline {
        font-size: 0.9rem;
        color: #444;
        font-weight: bold;
    }

    .right-column {
        background-color: #0FA4AF;
        color: white;
    }

    .right-column h2 {
        font-size: 1.3rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .welcome-text {
        text-align: center;
        font-size: 0.8rem;
        font-weight: 400;
        color: #e2e2e2;
        margin-bottom: 14px;
    }

    form .form-group {
        margin-bottom: 12px;
    }

    form .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    form .form-control {
        border-radius: 6px;
        border: none;
        padding: 7px 10px;
        font-size: 0.85rem;
        height: 34px;
    }

    .password-input-container {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        cursor: pointer;
        background: none;
        border: none;
        color: #0FA4AF;
    }

    .invalid-feedback {
        font-size: 0.75rem;
        color: #ffdede;
    }

    button.btn-primary {
        background-color: #024950;
        border: none;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 999px;
        height: 34px;
        width: 100%;
        color: white;
        margin-top: 12px;
        cursor: pointer;
    }

    button.btn-primary:hover {
        background-color: #023236;
    }

    .login-with-text {
        font-size: 0.75rem;
        margin: 16px 0 10px;
        text-align: center;
        color: #ffffff;
    }

    .btn-google-small {
        width: 34px;
        height: 34px;
        background-color: white;
        border-radius: 6px;
        border: 1px solid #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .btn-google-small img {
        width: 20px;
        height: 20px;
    }

    .text-center.mt-3 a {
        font-size: 0.75rem;
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
    }

    .text-center.mt-3 a:hover {
        text-decoration: underline;
    }

    @media (max-width: 767.98px) {
        html, body {
            overflow: auto;
        }

        .card.shadow-lg.login-content {
            flex-direction: column;
            max-width: 95%;
        }

        .left-column, .right-column {
            max-width: 100%;
            padding: 16px;
        }

        .left-column {
            border-bottom: 1px solid #ccc;
            margin-bottom: 12px;
        }

        .left-column img {
            max-height: 70px;
        }

        .right-column h2 {
            font-size: 1.2rem;
        }

        .btn-google-small {
            width: 30px;
            height: 30px;
        }

        .btn-google-small img {
            width: 18px;
            height: 18px;
        }
    }

    .popup-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .popup-card {
        background: rgba(15, 164, 175, 0.35);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        padding: 18px;
        width: 90%;
        max-width: 360px;
        color: white;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .popup-title {
        text-align: center;
        font-size: 1.05rem;
        font-weight: bold;
        margin-bottom: 14px;
    }

    .role-options {
        display: flex;
        justify-content: space-around;
        gap: 10px;
        flex-wrap: wrap;
    }

    .role-box {
        background-color: white;
        color: black;
        border-radius: 10px;
        padding: 10px;
        width: 45%;
        text-align: center;
        transition: transform 0.3s;
    }

    .role-box:hover {
        transform: translateY(-4px);
    }

    .role-box img {
        width: 50px;
        height: 50px;
        margin-bottom: 5px;
    }

    .btn-role {
        margin-top: 4px;
        background-color: #964734;
        color: white;
        border: none;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: bold;
        cursor: pointer;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 12px;
        font-size: 18px;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    .close-btn:hover {
        color: #ffcaca;
    }

    @media (max-width: 576px) {
        .popup-card {
            max-width: 300px;
            padding: 14px;
        }

        .popup-title {
            font-size: 1rem;
        }

        .role-box {
            width: 100%;
        }

        .btn-role {
            padding: 4px 10px;
        }
    }
</style>

<div class="container">
    <div class="card shadow-lg login-content">
        <div class="left-column">
            <img src="{{ asset('images/misnfm_logo.png') }}" alt="MISN FM Logo" />
            <div class="tagline">Teman Setia di Setiap Frekuensi</div>
        </div>

        <div class="right-column">
            <h2>Masuk</h2>
            <div class="welcome-text">Selamat Datang, Silahkan Masuk ke Akun Anda</div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="login">Username atau Email</label>
                    <input id="login" type="text" class="form-control @error('login') is-invalid @enderror"
                        name="login" value="{{ old('login') }}" required autofocus
                        placeholder="Masukkan username atau email" />
                    @error('login')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="password-input-container">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required placeholder="Masukkan password" />
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Masuk</button>

                <div class="login-with-text">Atau masuk dengan:</div>

                <a href="{{ route('google.login') }}" class="btn-google-small" title="Masuk dengan Google">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google" />
                </a>

                <div class="text-center mt-3">
                    Belum Punya Akun? <a href="javascript:void(0)" onclick="openRolePopup()"><strong>Daftar</strong></a>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ route('password.request') }}" style="color: #ffffff; font-size: 0.75rem; text-decoration: none;">
                        <i class="fas fa-key me-1"></i> Lupa Password?
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Popup Modal Role -->
<div class="popup-overlay" id="rolePopup">
    <div class="popup-card">
        <a href="javascript:void(0)" onclick="closeRolePopup()" class="close-btn">&times;</a>
        <div class="popup-title">Anda Daftar sebagai?</div>
        <div class="role-options">
            <a href="{{ route('register.form', 'pendengar') }}" class="role-box">
                <img src="https://cdn-icons-png.flaticon.com/512/3271/3271098.png" alt="Pendengar" />
                <div><button class="btn-role" type="button">PENDENGAR</button></div>
            </a>
            <a href="{{ route('register.form', 'wartawan') }}" class="role-box">
                <img src="https://cdn-icons-png.flaticon.com/512/3988/3988040.png" alt="Wartawan" />
                <div><button class="btn-role" type="button">WARTAWAN</button></div>
            </a>
        </div>
    </div>
</div>

<script>
    function openRolePopup() {
        document.getElementById('rolePopup').style.display = 'flex';
    }

    function closeRolePopup() {
        document.getElementById('rolePopup').style.display = 'none';
    }

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.querySelector('.toggle-password i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.classList.remove('fa-eye-slash');
            toggleButton.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleButton.classList.remove('fa-eye');
            toggleButton.classList.add('fa-eye-slash');
        }
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection