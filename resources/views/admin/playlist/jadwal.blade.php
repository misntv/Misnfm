@extends('admin.layouts.app')

@section('title', 'Kelola Jadwal Tayang')

@section('content')
<div class="container-fluid px-4 my-4">
    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">Kelola Jadwal Tayang: {{ $stasiun->nama_stasiun }}</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('playlist.jadwal.store', $stasiun->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="nama_program" class="form-control" placeholder="Nama Program" required>
                    </div>
                    <div class="col-md-2">
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <select name="genre" class="form-select" required>
                            <option value="">Genre</option>
                            <option value="Pop">Pop</option>
                            <option value="Dangdut">Dangdut</option>
                            <option value="Religi">Religi</option>
                            <option value="Berita">Berita</option>
                            <option value="Lokal">Lokal</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="hari" class="form-select">
                            <option value="">Setiap Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Genre</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Hari</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->nama_program }}</td>
                        <td><span class="badge bg-primary">{{ $jadwal->genre }}</span></td>
                        <td>{{ $jadwal->jam_mulai }}</td>
                        <td>{{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->hari ?? 'Setiap Hari' }}</td>
                        <td>
                            <form action="{{ route('playlist.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada jadwal tayang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('playlist.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection