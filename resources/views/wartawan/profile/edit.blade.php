@extends('wartawan.layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4 text-center fw-bold">EDIT PROFIL</h4>

        <form action="{{ route('wartawan.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- FOTO PROFIL --}}
                <div class="col-md-3 text-center mb-4">
                    <div class="mx-auto" style="width: 120px; height: 120px;">
                        <img src="{{ $user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/default-profile.png') }}"
                             onerror="this.src='{{ asset('assets/img/default-profile.png') }}'"
                             class="rounded-circle"
                             style="width: 100%; height: 100%; object-fit: cover; border: 2px solid #ccc;"
                             alt="Foto Profil">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Foto Profil (opsional)</label>
                        <input type="file" name="url_foto_profil" class="form-control form-control-sm">
                        <small class="text-muted d-block mt-1">Maks. ukuran 2MB</small>
                    </div>
                </div>

                {{-- FORM PROFIL --}}
                <div class="col-md-9">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor Identitas</label>
                            <input type="text" name="no_identitas" class="form-control" value="{{ old('no_identitas', $user->no_identitas) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $user->no_telp) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Asal Perusahaan</label>
                            <input type="text" name="asal_perusahaan" class="form-control" value="{{ old('asal_perusahaan', $user->asal_perusahaan) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection