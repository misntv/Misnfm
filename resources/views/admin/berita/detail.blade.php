@extends('admin.layouts.app')

@section('title', 'Detail Berita')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Berita</h6>
            <a href="{{ route('admin.berita.verifikasi') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2 class="fw-bold">{{ $berita->judul_berita }}</h2>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2">{{ $berita->topik }}</span>
                        <span class="text-muted">
                            Dikirim pada: {{ $berita->tanggal_kirim->format('d/m/Y') }}
                        </span>
                    </div>
                    @if($berita->status_verifikasi === 'ditolak')
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">Berita Ditolak</h5>
                    </div>
                    @endif
                </div>
                <div class="col-md-4 text-end">
                    @if($berita->status_verifikasi === 'Pengajuan')
                    <div class="d-flex justify-content-end gap-2">
                        <form action="{{ route('admin.berita.terima', $berita->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Terima
                            </button>
                        </form>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" 
                         class="img-fluid rounded" 
                         alt="{{ $berita->judul_berita }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="ql-snow">
                        <div class="ql-editor">
                            {!! $berita->isi_berita !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="tolakModalLabel">Penolakan Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.berita.tolak', $berita->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_ditolak" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="catatan_ditolak" name="catatan_ditolak" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ql-editor {
        font-family: inherit;
        font-size: 1rem;
        line-height: 1.6;
        color: #212529;
        padding: 0;
    }
    
    .ql-editor img {
        max-width: 100%;
        height: auto;
    }
</style>
@endsection