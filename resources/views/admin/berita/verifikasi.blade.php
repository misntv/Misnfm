@extends('admin.layouts.app')

@section('title', 'Verifikasi Berita')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center fw-bold" style="color: #964734;">Verifikasi Berita</h1>

    <div class="status-info text-end mb-4">
        <span class="hijau">{{ $totalDiterima }} Diterima</span>
        <span class="merah ms-3">{{ $totalDitolak }} Ditolak</span>
        <span class="biru ms-3">{{ $totalPengajuan }} Pengajuan</span>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Berita Verifikasi</h6>

                <form action="{{ route('admin.berita.verifikasi') }}" method="GET" class="d-flex align-items-center" style="max-width: 400px; width: 100%;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-start-pill" placeholder="Cari judul atau topik..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary rounded-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.berita.verifikasi') }}" class="btn btn-outline-secondary rounded-end-pill">
                                <i class="fas fa-times"></i>
                            </a>
                        @else
                            <span class="input-group-text bg-white border-end rounded-end-pill"></span>
                        @endif
                    </div>
                </form>
            </div>

            @if(request('search'))
                <small class="text-muted mt-2 d-block">Hasil pencarian: "{{ request('search') }}"</small>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Judul Berita</th>
                            <th>Topik</th>
                            <th>Gambar</th>
                            <th>Tgl.Kirim</th>
                            <th>Pukul</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($beritaPengajuan->isEmpty() && $beritaDiterima->isEmpty() && $beritaDitolak->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data berita</td>
                            </tr>
                        @else
                            @if(!$beritaPengajuan->isEmpty())
                                <tr class="bg-light">
                                    <td colspan="8" class="fw-bold">Berita Pengajuan ({{ $totalPengajuan }})</td>
                                </tr>
                                @foreach($beritaPengajuan as $index => $berita)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::limit($berita->judul_berita, 50) }}</td>
                                    <td>{{ $berita->topik }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" class="thumb" alt="Thumbnail">
                                    </td>
                                    <td>{{ $berita->tanggal_kirim->format('d/m/Y') }}</td>
                                    <td>{{ $berita->jam_kirim }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Pengajuan</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                            <button class="btn btn-sm btn-success btn-terima" data-id="{{ $berita->id }}">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-tolak" data-id="{{ $berita->id }}">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                            <a href="{{ route('admin.berita.show', $berita->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif

                            @if(!$beritaDiterima->isEmpty())
                                <tr class="bg-light">
                                    <td colspan="8" class="fw-bold">Berita Diterima ({{ $totalDiterima }})</td>
                                </tr>
                                @foreach($beritaDiterima as $index => $berita)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::limit($berita->judul_berita, 50) }}</td>
                                    <td>{{ $berita->topik }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" class="thumb" alt="Thumbnail">
                                    </td>
                                    <td>{{ $berita->tanggal_kirim->format('d/m/Y') }}</td>
                                    <td>{{ $berita->jam_kirim }}</td>
                                    <td>
                                        <span class="badge bg-success">Diterima</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.berita.show', $berita->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif

                            @if(!$beritaDitolak->isEmpty())
                                <tr class="bg-light">
                                    <td colspan="8" class="fw-bold">Berita Ditolak ({{ $totalDitolak }})</td>
                                </tr>
                                @foreach($beritaDitolak as $index => $berita)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::limit($berita->judul_berita, 50) }}</td>
                                    <td>{{ $berita->topik }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" class="thumb" alt="Thumbnail">
                                    </td>
                                    <td>{{ $berita->tanggal_kirim->format('d/m/Y') }}</td>
                                    <td>{{ $berita->jam_kirim }}</td>
                                    <td>
                                        <span class="badge bg-danger">Ditolak</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.berita.show', $berita->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Terima -->
    <div class="popup-overlay" id="terimaModal" style="display:none;">
        <div class="popup-box">
            <p><strong>Konfirmasi Penerimaan Berita</strong><br>
            Apakah Anda yakin ingin menerima berita ini?<br>Berita akan ditampilkan di halaman beranda.</p>
            <form id="formTerima" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-finish">Ya, Terima</button>
            </form>
            <button onclick="closeModal('terimaModal')" class="btn-finish" style="background-color: #555; margin-top: 10px;">Batal</button>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="popup-overlay" id="tolakModal" style="display:none;">
        <div class="popup-box">
            <p><strong>Konfirmasi Penolakan Berita</strong><br>Apakah Anda yakin ingin menolak berita ini?</p>
            <form id="formTolak" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-finish mt-3">Tolak Berita</button>
            </form>
            <button onclick="closeModal('tolakModal')" class="btn-finish" style="background-color: #555; margin-top: 10px;">Batal</button>
        </div>
    </div>
</div>

<style>
    .status-info {
        font-size: 16px;
        margin-bottom: 20px;
    }
    .status-info span {
        margin-left: 15px;
        font-weight: bold;
    }
    .hijau { color: #28a745; }
    .merah { color: #dc3545; }
    .biru { color: #17a2b8; }
    .thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
    }
    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.85em;
    }
    .popup-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .popup-box {
        width: 333px;
        background-color: #003135;
        border-top: 13px solid #964734;
        border-radius: 12px;
        padding: 25px 20px;
        color: white;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .popup-box p {
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .btn-finish {
        background-color: #964734;
        color: white;
        padding: 8px 30px;
        border: none;
        border-radius: 20px;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-terima').forEach(button => {
        button.addEventListener('click', function() {
            const beritaId = this.getAttribute('data-id');
            document.getElementById('formTerima').action = `/admin/berita/${beritaId}/terima`;
            document.getElementById('terimaModal').style.display = 'flex';
        });
    });

    document.querySelectorAll('.btn-tolak').forEach(button => {
        button.addEventListener('click', function() {
            const beritaId = this.getAttribute('data-id');
            document.getElementById('formTolak').action = `/admin/berita/${beritaId}/tolak`;
            document.getElementById('tolakModal').style.display = 'flex';
        });
    });
});

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
@endsection