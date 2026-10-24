@extends('pendengar.layouts.app')

@section('content')
<div class="container py-4">
    {{-- Tombol Kembali --}}
    <a href="{{ route('pendengar.dashboard') }}" class="btn btn-outline-secondary mb-3">
        &larr; Kembali
    </a>

    <div class="row">
        {{-- FOTO & NAMA --}}
        <div class="col-md-4 text-center">
            <div class="mx-auto mb-3" style="width: 150px; height: 150px;">
                <img src="{{ $user->url_foto_profil 
                            ? asset('storage/' . $user->url_foto_profil) 
                            : asset('assets/img/default-profile.png') }}"
                     onerror="this.src='{{ asset('assets/img/default-profile.png') }}'"
                     class="rounded-circle"
                     style="width: 100%; height: 100%; object-fit: cover; border: 2px solid #ccc;"
                     alt="Foto Profil">
            </div>

            <h4 class="mb-0">{{ $user->nama_lengkap }}</h4>

            <a href="{{ route('pendengar.profile.edit') }}" class="btn btn-primary mt-3">
                Edit Profil
            </a>
        </div>

        {{-- DETAIL PROFIL --}}
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-muted" style="width: 180px;">Nomor Identitas</th>
                        <td>{{ $user->no_identitas }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. Telepon</th>
                        <td>{{ $user->no_telp }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
