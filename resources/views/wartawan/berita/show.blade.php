@extends('wartawan.layouts.app')

@section('title', 'Detail - ' . $berita->judul_berita)

@section('content')
    <div class="container-fluid px-5 my-4 pt-2">
        <div class="card shadow-lg">
            <div class="card-header"
                style="background-color: #964734; color: white; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="mb-0">Detail Berita</h4>
                <a href="{{ route('berita.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
    <!-- Header Info -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="image-container rounded overflow-hidden" style="height: 250px;">
                <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}"
                    alt="{{ $berita->judul_berita }}" class="img-fluid h-100 w-100 object-fit-cover">
            </div>
        </div>
        <!-- Kolom Konten -->
        <div class="col-md-8">
            <!-- Judul Berita -->
            <h4 class="mb-2 text-justify" style="font-size: 1.35rem; line-height: 1.4;">
                {{ $berita->judul_berita }}
            </h4>

            <div class="meta-info mb-3">
                <span class="badge bg-secondary me-2">{{ $berita->topik }}</span>
                <span class="text-muted me-3">
                    <i class="fas fa-user me-1"></i> {{ $berita->penulis->nama_lengkap ?? 'Tidak diketahui' }}
                </span>
                <span class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($berita->tanggal_kirim)->format('d/m/Y') }}
                </span>
            </div>

            @if($berita->status_unggah == 'diunggah' && $berita->tanggal_terbit)
                <div class="publish-info mb-3">
                    <span class="text-success">
                        <i class="fas fa-calendar-check me-1"></i>
                        Terbit: {{ \Carbon\Carbon::parse($berita->tanggal_terbit)->translatedFormat('d/m/Y') }}
                    </span>
                </div>
            @endif

            <div class="status-and-actions d-flex align-items-center mb-4">
                <div class="status-info me-3">
                    @if($berita->status_unggah == 'pengajuan')
                        <span class="badge bg-warning text-dark color: #000;">Menunggu Verifikasi</span>
                    @elseif($berita->status_unggah == 'ditolak')
                        <span class="badge bg-danger color: #000;">Ditolak</span>
                    @elseif($berita->status_unggah == 'diterima')
                       <span class="badge" style="background-color: #4EF037; color: #000;">Disetujui</span>
                    @endif
                </div>
                
                <div class="action-buttons">
                    @if($berita->status_unggah != 'ditolak')
                        <form id="archive-form-{{ $berita->id }}" action="{{ route('berita.archive', $berita->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-danger btn-sm archive-btn"
                                data-id="{{ $berita->id }}" data-judul="{{ $berita->judul_berita }}">
                                <i class="fas fa-archive me-1"></i> Arsipkan
                            </button>
                        </form>
                    @else
                        <form action="{{ route('berita.update', $berita->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Berita -->
    <div class="content-section">
        <h4 class="section-title mb-3">
            <i class="fas fa-align-left me-2"></i> Isi Berita
        </h4>
        <div class="news-content ql-editor p-4 rounded border">
            {!! $berita->isi_berita !!}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
  .status-and-actions {
    display: flex;
    align-items: center;
    gap: 0.6 rem; /* dikurangi agar tombol lebih dekat ke badge */
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
    flex-wrap: wrap;
}

.status-info .badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    display: inline-flex;
    align-items: center;
    height: 38px;
}

.action-buttons .btn {
    height: 27px; /* samakan tinggi dengan badge */
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
      margin-left: -8px; 
}

   
    .card-header {
        padding: 1rem 1.5rem;
    }

    .section-title {
        color: #964734;
        border-bottom: 2px solid #964734;
        padding-bottom: 0.5rem;
    }

    /* Image Styles */
    .image-container {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
    }

    /* Content Styles */
    .news-content {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 16px;
        line-height: 1.8;
        color: #333;
        background-color: #fff;
        max-height: 600px;
        overflow-y: auto;
        background-color: #f9f9f9;
    }

    /* Quill Content Styles */
    .news-content h1,
    .news-content h2,
    .news-content h3,
    .news-content h4 {
        margin-top: 1.5em;
        margin-bottom: 0.8em;
        color: #964734;
        font-weight: 600;
    }

    .news-content p {
        margin-bottom: 1.2em;
        text-align: justify;
    }

    .news-content img {
        max-width: 100%;
        height: auto;
        margin: 1.5em auto;
        display: block;
        border-radius: 6px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .news-content ul,
    .news-content ol {
        margin-bottom: 1.5em;
        padding-left: 2.5em;
    }

    .news-content ul {
        list-style-type: disc;
    }

    .news-content ol {
        list-style-type: decimal;
    }

    .news-content a {
        color: #964734;
        text-decoration: underline;
        font-weight: 500;
    }

    .news-content blockquote {
        border-left: 4px solid #964734;
        padding: 0.8em 1.2em;
        margin: 1.5em 0;
        background-color: #f5f5f5;
        font-style: italic;
        color: #555;
    }

    /* Scrollbar Styles */
    .news-content::-webkit-scrollbar {
        width: 8px;
    }

    .news-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .news-content::-webkit-scrollbar-thumb {
        background: #964734;
        border-radius: 4px;
    }

    .news-content::-webkit-scrollbar-thumb:hover {
        background: #7a392a;
    }

    .btn-arsipkan {
    background-color: #ff4d4d; /* merah cerah */
    border-color: #ff3333;
    color: read;
    transition: background-color 0.3s ease;
}

.btn-arsipkan:hover {
    background-color: #e60000;
    border-color: #cc0000;
}

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .news-content {
            max-height: 400px;
            font-size: 15px;
        }

        .image-container {
            height: 200px;
            margin-bottom: 1.5rem;
        }
    }
</style>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle archive button click
            const archiveButtons = document.querySelectorAll('.archive-btn');

            archiveButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const beritaId = this.getAttribute('data-id');
                    const judulBerita = this.getAttribute('data-judul');

                    Swal.fire({
                        html: Apakah Anda yakin akan mengarsipkan berita dengan judul "<strong>${judulBerita}</strong>"?,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#FF0000',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Arsipkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(archive-form-${beritaId}).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush