@extends('layouts.app')

@section('content')
<style>
    nav.navbar {
        display: none !important;
    }

    body, html {
        background-color: #AFDDE5;
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
        box-sizing: border-box;
    }

    .card-popup {
        width: 100%;
        max-width: 700px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        background-color: white;
        display: flex;
        flex-wrap: nowrap;
        border: none;
    }

    .left-section, .right-section {
        flex: 1;
        max-width: 50%;
        padding: 40px 30px;
        box-sizing: border-box;
    }

    .left-section {
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
    }

    .left-section img {
        max-height: 150px;
        width: auto;
        margin-bottom: 10px;
    }

    .left-section .tagline {
        font-size: 1rem;
        color: #444;
        font-weight: 500;
    }

    .right-section {
        background-color: #003135;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .right-section p {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .btn-finish {
        background-color: #964734;
        color: white;
        padding: 10px 30px;
        border: none;
        border-radius: 30px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-finish:hover {
        background-color: #7c362a;
    }

    @media (max-width: 767.98px) {
        .card-popup {
            flex-direction: column;
        }

        .left-section, .right-section {
            max-width: 100%;
            padding: 30px 20px;
        }

        .left-section {
            order: 1;
        }

        .right-section {
            order: 2;
        }

        .left-section img {
            max-height: 130px;
        }

        .right-section p {
            font-size: 0.95rem;
        }
    }
</style>

<div class="container">
    <div class="card-popup">
        <!-- Logo -->
        <div class="left-section">
            <img src="{{ asset('images/misnfm_logo.png') }}" alt="MISN FM Logo">
            <div class="tagline">Teman Setia di Setiap Frekuensi</div>
        </div>

        <!-- Pesan -->
        <div class="right-section">
            <p><strong>Verifikasi Akun Wartawan</strong><br>
            Proses verifikasi diselesaikan maksimal 2x24 jam.<br>
            Silakan periksa akun Anda secara berkala untuk pembaruan status.</p>

            <a href="{{ route('login') }}" class="btn-finish">Selesai</a>
        </div>
    </div>
</div>
@endsection