@extends('admin.layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h4 class="fw-bold">PROFIL ADMIN</h4>
    </div>

    <div class="d-flex justify-content-center">
        <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-3">
                <img src="{{ $admin->url_foto_profil 
                            ? asset('storage/' . $admin->url_foto_profil) 
                            : asset('assets/img/default-profile.png') }}" 
                     class="rounded-circle mb-2" width="100" height="100" alt="Foto Profil">

                <div>
                    <span class="badge bg-danger">ADMIN</span>
                </div>
            </div>

            <div class="text-center">
                <h5 class="mb-1">{{ $admin->nama_lengkap }}</h5>
                <p class="text-muted">{{ '@' . $admin->username }}</p>
            </div>

            <div class="mt-4">
                <p><strong>NOMOR WHATSAPP</strong><br>{{ $admin->no_telp }}</p>
                <p><strong>EMAIL</strong><br>{{ $admin->email }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
