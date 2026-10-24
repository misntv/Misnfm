@extends('admin.layouts.app')
@section('title', 'Verifikasi Wartawan')
@section('content')

<style>
    .container.py-4 { max-width: 960px; }

    .nav-switch {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .nav-switch button {
        padding: 8px 20px;
        border: none;
        border-radius: 30px;
        font-weight: bold;
        cursor: pointer;
        background-color: #ccc;
        color: #333;
    }

    .nav-switch button.active {
        background-color: #024950;
        color: white;
    }

    .section-content { display: none; }
    .section-content.active { display: block; }
</style>

<div class="container py-4">
    <div class="nav-switch">
        <button id="btnVerifikasi" class="active" onclick="showSection('verifikasi')">Verifikasi Akun</button>
        <button id="btnAnggota" onclick="showSection('anggota')">Daftar Anggota</button>
    </div>

    <!-- Verifikasi Akun -->
    <div id="sectionVerifikasi" class="section-content active">
        <h2 class="fw-bold mb-4">{{ $unapprovedWartawan->count() }} Pengajuan Akun</h2>
        <div class="row">
            @forelse($unapprovedWartawan as $wartawan)
                @include('admin.wartawan.card', ['wartawan' => $wartawan, 'mode' => 'verifikasi'])
            @empty
                <div class="alert alert-info mt-4">Tidak ada wartawan yang menunggu verifikasi.</div>
            @endforelse
        </div>
    </div>

    <!-- Daftar Anggota -->
    <div id="sectionAnggota" class="section-content">
        <h2 class="fw-bold mb-4">Daftar Wartawan Aktif</h2>
        <div class="row">
            @forelse($approvedWartawan as $wartawan)
                @include('admin.wartawan.card', ['wartawan' => $wartawan, 'mode' => 'anggota'])
            @empty
                <div class="alert alert-info mt-4">Belum ada wartawan yang disetujui.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        document.getElementById('sectionVerifikasi').classList.remove('active');
        document.getElementById('sectionAnggota').classList.remove('active');
        document.getElementById('btnVerifikasi').classList.remove('active');
        document.getElementById('btnAnggota').classList.remove('active');

        document.getElementById('section' + section.charAt(0).toUpperCase() + section.slice(1)).classList.add('active');
        document.getElementById('btn' + section.charAt(0).toUpperCase() + section.slice(1)).classList.add('active');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection