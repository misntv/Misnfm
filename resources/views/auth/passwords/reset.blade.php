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

    .card.shadow-lg {
        max-width: 450px;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        background: white;
    }

    .card-header {
        background-color: #0FA4AF;
        color: white;
        text-align: center;
        padding: 20px;
        font-size: 1.3rem;
        font-weight: bold;
        border-bottom: none;
    }

    .card-header i {
        margin-right: 10px;
    }

    .card-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
        color: #333;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ccc;
        padding: 8px 12px;
        font-size: 0.9rem;
        height: 38px;
        width: 100%;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #0FA4AF;
        outline: none;
        box-shadow: 0 0 0 3px rgba(15, 164, 175, 0.2);
    }

    .form-control[readonly] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    .password-input-container {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #0FA4AF;
        font-size: 1rem;
        padding: 0;
    }

    .toggle-password:hover {
        color: #0d8a94;
    }

    .btn-primary {
        background-color: #0FA4AF;
        border: none;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 999px;
        height: 38px;
        width: 100%;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0d8a94;
    }

    .btn-link {
        color: #0FA4AF;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-block;
        margin-top: 10px;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .alert-danger {
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .invalid-feedback {
        font-size: 0.8rem;
        color: #dc3545;
        display: block;
        margin-top: 4px;
    }

    .password-requirement {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 5px;
    }

    .password-requirement i {
        margin-right: 5px;
    }

    @media (max-width: 576px) {
        .card.shadow-lg {
            max-width: 95%;
        }
    }
</style>

<div class="container">
    <div class="card shadow-lg">
        <div class="card-header">
            <i class="fas fa-lock"></i> Reset Password
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="text-muted text-center mb-4" style="font-size: 0.9rem;">
                Masukkan password baru Anda.
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ $email ?? old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="password-input-container">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required placeholder="Password">
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                    <div class="password-requirement">
                        <i class="fas fa-info-circle"></i> Minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Konfirmasi Password</label>
                    <div class="password-input-container">
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required placeholder="Ulangi Password Baru">
                        <button type="button" class="toggle-password" onclick="togglePassword('password-confirm')">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-save me-2"></i> Simpan Password Baru
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="btn-link">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentElement.querySelector('.toggle-password i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection