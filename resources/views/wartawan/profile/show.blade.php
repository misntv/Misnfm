@extends('wartawan.layouts.app')

@section('content')
<div class="container py-4">
    {{-- Tombol kembali --}}
    <a href="{{ route('wartawan.dashboard.wartawan') }}" class="btn btn-outline-secondary mb-3">
        &larr; Kembali
    </a>

    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            {{-- Foto Profil --}}
            <div class="mb-3 mx-auto" style="width: 150px; height: 150px;">
                <img src="{{ $user->url_foto_profil 
                            ? asset('storage/' . $user->url_foto_profil) 
                            : asset('assets/img/default-profile.png') }}"
                    alt="Foto Profil"
                    class="rounded-circle"
                    style="width: 100%; height: 100%; object-fit: cover; border: 2px solid #ccc;">
            </div>

            <h4>{{ $user->nama_lengkap }}</h4>

            <a href="{{ route('wartawan.profile.edit') }}" class="btn btn-primary mt-3">
                Edit Profil
            </a>
        </div>

        <div class="col-md-8">
            <table class="table table-striped">
                <tr>
                    <th>Nomor Identitas</th>
                    <td>{{ $user->no_identitas }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $user->no_telp }}</td>
                </tr>
                <tr>
                    <th>Asal Perusahaan</th>
                    <td>{{ $user->asal_perusahaan }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $user->alamat }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
