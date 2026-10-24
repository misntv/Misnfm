@extends('pendengar.layouts.app')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="hero-section text-white rounded-4 p-5 mb-5 text-center shadow position-relative overflow-hidden">
        <div class="hero-overlay position-absolute w-100 h-100"></div>
        <div class="position-relative">
            <h1 class="fw-bold display-5 mb-3 z-2">{{ $search ? 'Hasil Pencarian Berita' : 'Berita Pilihan untuk Anda' }}</h1>
            <p class="lead z-2">{{ $search ? 'Temukan berita yang Anda cari' : 'Temukan informasi terkini, terpercaya, dan menarik dari berbagai penjuru daerah' }}</p>
            <form action="{{ route('pendengar.berita.index') }}" method="GET" class="d-flex justify-content-center mt-4 z-2">
                <input type="text" name="search" class="form-control w-50 rounded-start-pill shadow-sm" placeholder="Cari berita..." value="{{ $search ?? '' }}">
                <button class="btn btn-dark rounded-end-pill px-4 shadow-sm" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Section Component -->
    @php
    function beritaSection($judul, $beritas, $warna) {
        if (!isset($beritas) || $beritas->count() === 0) return;
        echo '<div class="mb-5">';
        echo    '<div class="d-flex align-items-center mb-4">';
        echo        "<div class='section-title-decoration bg-$warna me-3'></div>";
        echo        "<h4 class='section-title mb-0 fw-bold text-$warna'>$judul</h4>";
        echo    '</div>';
        echo    '<div class="scrolling-wrapper pb-3">';
        foreach ($beritas as $berita) {
            echo '<div class="scrolling-card">';
            echo    '<a href="' . route('pendengar.baca.show', $berita->id) . '" class="text-decoration-none text-dark">';
            echo        '<div class="news-outer-card position-relative">';
            echo            $berita->url_gambar
                                ? '<img src="' . asset('storage/berita/' . $berita->url_gambar) . '" class="news-thumbnail">'
                                : '<div class="bg-secondary news-thumbnail"></div>';
            echo            '<div class="news-info-box shadow-sm">';
            echo                "<span class='badge bg-$warna mb-1'>" . ($berita->topik ?? ucfirst($warna)) . "</span>";
            echo                "<h6 class='mb-1'>" . Str::limit($berita->judul_berita, 50) . "</h6>";
            echo                "<p class='text-muted small text-truncate mb-0'>" . Str::limit(strip_tags($berita->isi_berita), 70) . "</p>";
            echo                "<p class='text-muted small mt-1'>" . $berita->tanggal_terbit->format('d M Y') . "</p>";
            echo            '</div>';
            echo        '</div>';
            echo    '</a>';
            echo '</div>';
        }
        echo    '</div>';
        echo '</div>';
    }
    @endphp
        
    @if(!$search)
        @php beritaSection('Berita Terbaru', $beritaTerbaru ?? null, 'primary'); @endphp
        @php beritaSection('Berita Terpopuler', $beritaTerpopuler ?? null, 'danger'); @endphp

        <!-- Section Baru Dibaca -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <div class='section-title-decoration bg-info me-3'></div>
                <h4 class='section-title mb-0 fw-bold text-info'>Baru Dibaca</h4>
            </div>
            
            @if(isset($baruDibaca) && $baruDibaca->count() > 0)
                <div class="scrolling-wrapper pb-3">
                    @foreach($baruDibaca as $berita)
                        <div class="scrolling-card">
                            <a href="{{ route('pendengar.baca.show', $berita->id) }}" class="text-decoration-none text-dark">
                                <div class="news-outer-card position-relative">
                                    @if($berita->url_gambar)
                                        <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" class="news-thumbnail">
                                    @else
                                        <div class="bg-secondary news-thumbnail"></div>
                                    @endif
                                    <div class="news-info-box shadow-sm">
                                        <span class='badge bg-info mb-1'>{{ $berita->topik ?? 'Berita' }}</span>
                                        <h6 class='mb-1'>{{ Str::limit($berita->judul_berita, 50) }}</h6>
                                        <p class='text-muted small text-truncate mb-0'>{{ Str::limit(strip_tags($berita->isi_berita), 70) }}</p>
                                        <p class='text-muted small mt-1'>{{ $berita->tanggal_terbit->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light text-center py-3">
                    <i class="fas fa-book-open me-2"></i>
                    <span>Belum ada berita yang baru dibaca</span>
                </div>
            @endif
        </div>

        @foreach($beritaPerTopik ?? [] as $topik => $beritas)
            @php 
                $colors = ['primary', 'success', 'warning', 'danger', 'info'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            @php beritaSection("Topik: " . $topik, $beritas, $color); @endphp
        @endforeach
    @endif

    <!-- Semua Berita / Hasil Pencarian -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-4">
            <div class='section-title-decoration bg-dark me-3'></div>
            <h4 class='section-title mb-0 fw-bold text-dark'>{{ $search ? 'Hasil Pencarian' : 'Semua Berita' }}</h4>
        </div>

        @if(isset($semuaBerita) && $semuaBerita->count() > 0)
        <div class="row g-4">
            @foreach($semuaBerita as $berita)
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('pendengar.baca.show', $berita->id) }}" class="text-decoration-none text-dark">
                    <div class="news-main-card h-100">
                        @if($berita->url_gambar)
                            <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" class="news-main-thumbnail">
                        @else
                            <div class="bg-secondary news-main-thumbnail"></div>
                        @endif
                        <div class="news-main-info p-3 position-relative">
                            <!-- Topik & Tanggal: Topik kiri atas, tanggal kanan atas -->
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-primary">{{ $berita->topik ?? 'Berita' }}</span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-3">
                                <small class="text-muted">{{ $berita->tanggal_terbit->format('d M Y') }}</small>
                            </div>

                            <div class="mt-5 pt-2">
                                <h5 class="mb-2">{{ $berita->judul_berita }}</h5>
                                <p class="text-muted mb-3">{{ Str::limit(strip_tags($berita->isi_berita), 120) }}</p>
                                <div class="d-flex align-items-center text-primary">
                                    <span class="me-2">Baca Selengkapnya</span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $semuaBerita->links() }}
        </div>
        @else
        <div class="alert alert-info text-center py-4">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <h5>{{ $search ? 'Tidak ditemukan berita' : 'Belum ada berita tersedia' }}</h5>
            <p class="mb-0">{{ $search ? 'Coba gunakan kata kunci lain.' : 'Silakan kembali lagi nanti.' }}</p>
        </div>
        @endif
    </div>
</div>

<style>
    .hero-section {
        background: linear-gradient(to bottom, #AFDDE5, #024950);
        background-size: cover;
        position: relative;
        overflow: hidden;
    }
    .hero-overlay {
        background-image: url('/img/ornamen-news.svg');
        background-repeat: no-repeat;
        background-size: cover;
        opacity: 0.08;
        z-index: 1;
        top: 0;
        left: 0;
    }
    .scrolling-wrapper {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        margin-left: -8px;
        padding-bottom: 12px;
    }
    .scrolling-card {
        flex: 0 0 auto;
        padding-left: 8px;
    }
    .scrolling-wrapper::-webkit-scrollbar {
        display: none;
    }
    .news-outer-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 220px;
        transition: all 0.3s ease-in-out;
        border-radius: 16px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border: 1px solid #e5e5e5;
    }
    .news-outer-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }
    .news-thumbnail {
        width: 100%;
        height: auto;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        border-radius: 16px 16px 0 0;
    }
    .news-info-box {
        width: 100%;
        padding: 14px 16px;
        border-radius: 0 0 16px 16px;
        background: #ffffff;
    }

    .section-title-decoration {
        width: 6px;
        height: 30px;
        border-radius: 3px;
    }
    .section-title {
        font-size: 1.5rem;
        letter-spacing: -0.5px;
        position: relative;
    }

    .news-main-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .news-main-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .news-main-thumbnail {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .news-main-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .news-main-info h5 {
        font-size: 1.1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-main-info p {
        font-size: 0.9rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    @media (max-width: 768px) {
        .news-outer-card {
            width: 90% !important;
            max-width: 300px;
            margin: auto;
        }
        .section-title {
            font-size: 1.3rem;
        }
        .news-main-thumbnail {
            height: 150px;
        }

        /* Tambahan untuk membatasi lebar "Semua Berita" card */
        .news-main-card {
            max-width: 95%;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endsection