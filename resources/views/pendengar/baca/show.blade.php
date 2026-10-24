@extends('pendengar.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pendengar.berita.index') }}">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $berita->judul_berita }}</li>
                </ol>
            </nav>

            <article>
                <h1 class="fw-bold mb-1">{{ $berita->judul_berita }}</h1>
                <small class="text-muted mb-3 d-block">
                    <i class="fas fa-user-edit me-1"></i>
                    {{ $berita->penulis->nama_lengkap }}
                    <i class="far fa-calendar-alt me-1"></i>
                    {{ $berita->tanggal_terbit->format('d M Y') }}
                </small>

                @if($berita->url_gambar)
                <div class="mb-3"> {{-- mb-3 for spacing below image --}}
                    <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}"
                             alt="{{ $berita->judul_berita }}"
                             class="img-fluid rounded w-100"
                             style="max-height: 450px; object-fit: cover;">
                    <p class="text-muted small mt-2">Gambar: {{ $berita->judul_berita }}</p>
                </div>
                @endif

                {{-- Topic badge now below the image --}}
               <div class="mb-4">
    <span class="badge news-topic-badge">{{ $berita->topik }}</span>
</div>
                <div class="news-content mb-5">
                    {!! $berita->isi_berita !!}
                </div>

                {{-- The following div block has been removed:
                <div class="d-flex justify-content-between border-top border-bottom py-3 mb-4">
                    <div>
                        @if($berita->previous())
                        <a href="{{ route('pendengar.baca.show', $berita->previous()->id) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Berita Sebelumnya
                        </a>
                        @endif
                    </div>
                    <div>
                        @if($berita->next())
                        <a href="{{ route('pendengar.baca.show', $berita->next()->id) }}"
                           class="btn btn-outline-primary">
                            Berita Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
                --}}
            </article>
        </div>

        <div class="col-lg-4">
            @if($relatedNews->count() > 0)
            <div class="card mb-4">
                {{-- Changed bg-primary to custom-related-header --}}
                <div class="card-header text-black custom-related-header">
                    <h5 class="mb-0">Berita Terkait</h5>
                </div>
                <div class="card-body">
                    @foreach($relatedNews as $news)
                    <div class="mb-3">
                        <a href="{{ route('pendengar.baca.show', $news->id) }}">
                            <h6>{{ $news->judul_berita }}</h6>
                        </a>
                        <small class="text-muted">
                            {{ $news->tanggal_terbit->format('d M Y') }}
                        </small>
                    </div>
                    @if(!$loop->last)<hr>@endif
                    @endforeach
                </div>
            </div>
            @endif

            <div class="card">
                {{-- Changed bg-danger to custom-popular-header --}}
                <div class="card-header text-black custom-popular-header">
                    <h5 class="mb-0">Berita Populer</h5>
                </div>
                <div class="card-body">
                    @foreach($beritaPopuler as $popular)
                    <div class="mb-3">
                        <a href="{{ route('pendengar.baca.show', $popular->id) }}"
                           class="text-decoration-none">
                            <h6 class="mb-1">{{ $popular->judul_berita }}</h6>
                        </a>
                        <small class="text-muted">
                            {{ $popular->tanggal_terbit->format('d M Y') }}
                        </small>
                    </div>
                    @if(!$loop->last)
                    <hr>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .news-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .news-content img {
        max-width: 100%;
        height: auto;
        margin: 1rem 0;
        border-radius: 8px;
    }

    /* Keep this style for addressing navbar overlap if needed */
    .main-content-wrapper {
        margin-top: 70px; /* Adjust this value if your navbar is fixed/sticky */
    }

    .custom-topic-badge {
        font-size: 1.2rem;
        padding: 0.7em 1.0em; /* Sesuaikan padding jika bentuk lencana terlihat tidak proporsional setelah mengubah ukuran teks */

    }

    .news-topic-badge {
        font-size: 1.25rem;
        padding: 0.75em 1.2em;
        background-color: #964734; /* Contoh: Menggunakan kode heksadesimal untuk warna oranye-merah */
        /* Atau untuk gradien: */
        /* background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%); */
        /* Hapus kelas bg-primary/bg-danger dari HTML jika menggunakan background-color atau background kustom */
    }

    /* Custom styles for news headers */
    .custom-related-header {
        background-color: #007bff; /* A standard blue */
    }

    .custom-popular-header {
        background-color: #dc3545; /* A standard red */
    }
</style>
@endsection