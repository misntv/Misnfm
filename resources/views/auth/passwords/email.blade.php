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

    .alert-success {
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
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

    @media (max-width: 576px) {
        .card.shadow-lg {
            max-width: 95%;
        }
    }
</style>

<div class="container">
    <div class="card shadow-lg">
        <div class="card-header">
            <i class="fas fa-key"></i> Lupa Password
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert-success" style="padding: 10px 15px; border-radius: 6px; margin-bottom: 15px;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-danger" style="padding: 10px 15px; border-radius: 6px; margin-bottom: 15px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="text-muted text-center mb-4" style="font-size: 0.9rem;">
                Masukkan email Anda, kami akan mengirimkan link untuk mereset password.
            </p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Link Reset
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection