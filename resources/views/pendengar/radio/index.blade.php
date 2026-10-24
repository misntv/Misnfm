@extends('pendengar.layouts.app')

@section('title', 'Daftar Stasiun Radio')

@section('content')
<div class="container py-4">
    <div class="hero-section text-white rounded-4 p-5 mb-5 text-center shadow" style="background: linear-gradient(to bottom, #964834a0, #964734);">
        <h1 class="fw-bold display-5 mb-3">Dengarkan Radio Favoritmu</h1>
        <p class="lead">Nikmati siaran radio terbaik dari seluruh penjuru negeri</p>
        <form action="{{ route('pendengar.radio.index') }}" method="GET" class="d-flex justify-content-center mt-4">
            <input type="text" name="search" class="form-control w-50 rounded-start-pill shadow-sm" placeholder="Cari stasiun radio..." value="{{ $search ?? '' }}">
            <button class="btn btn-dark rounded-end-pill px-4 shadow-sm" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Untuk mencegah error canvas dari file JS eksternal -->
    <canvas id="audioSpectrum" style="display:none;"></canvas>

    <!-- ========================================== -->
    <!-- ✅ REKOMENDASI UNTUK ANDA -->
    <!-- ========================================== -->
    @auth
    @if(!request()->has('search'))
    @php
        $hasValidHistory = \App\Models\RiwayatPemutaran::where('user_id', Auth::id())
                            ->where('durasi', '>=', 60)
                            ->exists();
    @endphp

    @if($hasValidHistory)
    <div class="mb-5" id="rekomendasi-section">
        <div class="section-heading mb-4" style="border-left-color: #ff6b35; background: linear-gradient(to right, #fff5f0, #f8f9fa);">
            <span class="section-title">
                <i class="fas fa-star text-warning me-2"></i>
                Rekomendasi Untuk Anda
            </span>
            <span class="ms-3 text-muted small" id="rekomendasi-update-time">
                <i class="fas fa-sync-alt me-1"></i>
                Diperbarui otomatis
            </span>
        </div>
        <div class="scrolling-wrapper pb-3" id="rekomendasi-container">
            <div class="text-center text-muted py-4 w-100" id="rekomendasi-loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat rekomendasi...</p>
            </div>
        </div>
    </div>
    @else
    {{-- Tidak tampilkan rekomendasi untuk user baru --}}
    <div class="mb-5">
        <div class="section-heading mb-4" style="border-left-color: #ff6b35; background: linear-gradient(to right, #fff5f0, #f8f9fa);">
            <span class="section-title">
                <i class="fas fa-info-circle text-info me-2"></i>
                Rekomendasi Untuk Anda
            </span>
        </div>
        <div class="text-center py-4 bg-light rounded">
            <i class="fas fa-headphones fa-3x text-muted mb-3"></i>
            <p class="text-muted">Dengarkan radio minimal 1 menit untuk mendapatkan rekomendasi yang dipersonalisasi!</p>
            <small class="text-muted">Rekomendasi akan muncul setelah Anda memiliki riwayat pemutaran.</small>
        </div>
    </div>
    @endif
    @endif
    @endauth

    <!-- ========================================== -->
    <!-- RADIO POPULER -->
    <!-- ========================================== -->
    @if($featuredRadios->count() > 0)
    <div class="mb-5">
        <div class="section-heading mb-4">
            <span class="section-title">Radio Populer</span>
        </div>
        <div class="scrolling-wrapper pb-3">
            @foreach($featuredRadios->take(10) as $radio)
            <div class="scrolling-card">
                <a href="{{ route('pendengar.player.index', $radio->id) }}" class="text-decoration-none text-dark">
                    <div class="radio-outer-card">
                        <div class="radio-thumbnail-effect-container">
                            <div class="radio-thumbnail-background" style="background-image: url('{{ $radio->thumbnail ? asset('storage/thumbnails/'.$radio->thumbnail) : asset('img/default-radio.png') }}');"></div>
                            <img src="{{ $radio->thumbnail ? asset('storage/thumbnails/'.$radio->thumbnail) : asset('img/default-radio.png') }}"
                                 alt="{{ $radio->nama_stasiun }}"
                                 class="radio-thumbnail-main-image">
                        </div>
                        <div class="radio-info-box shadow-sm">
                            <h6 class="mb-1">{{ $radio->nama_stasiun }}</h6>
                            <p class="text-muted small text-truncate mb-0" title="{{ $radio->deskripsi }}">
                                {{ Str::limit($radio->deskripsi, 60) }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- STASIUN RADIO BERDASARKAN DAERAH -->
    <!-- ========================================== -->
    <div class="mb-5">
        <div class="section-heading mb-4">
            <span class="section-title">Stasiun Radio Berdasarkan Daerah</span>
        </div>
        @foreach($groupedRadios as $region => $radios)
        <div class="region-group mb-5">
            <div class="region-heading mb-3 d-flex justify-content-between align-items-center">
                <span class="region-title">{{ $region ?: 'Lainnya' }}</span>
                @if($region)
                <a href="{{ route('pendengar.radio.byRegion', ['daerah' => $region]) }}" 
                class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                </a>
            @endif
            </div>
            <div class="scrolling-wrapper pb-3">
                @foreach($radios as $radio)
                <div class="scrolling-card">
                    <a href="{{ route('pendengar.player.index', $radio->id) }}" class="text-decoration-none text-dark">
                        <div class="radio-outer-card">
                            <div class="radio-thumbnail-effect-container">
                                <div class="radio-thumbnail-background" style="background-image: url('{{ $radio->thumbnail ? asset('storage/thumbnails/'.$radio->thumbnail) : asset('img/default-radio.png') }}');"></div>
                                <img src="{{ $radio->thumbnail ? asset('storage/thumbnails/'.$radio->thumbnail) : asset('img/default-radio.png') }}"
                                     alt="{{ $radio->nama_stasiun }}"
                                     class="radio-thumbnail-main-image">
                            </div>
                            <div class="radio-info-box shadow-sm">
                                <h6 class="mb-1">{{ $radio->nama_stasiun }}</h6>
                                <p class="text-muted small text-truncate mb-0" title="{{ $radio->deskripsi }}">
                                    {{ Str::limit($radio->deskripsi, 60) }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        @if($groupedRadios->isEmpty())
        <div class="col-12">
            <div class="alert alert-info text-center py-4">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h5>Tidak ada stasiun radio yang ditemukan</h5>
                <p class="mb-0">Silakan coba dengan kata kunci lain</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .scrolling-wrapper {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 10px;
        margin-left: -8px;
        width: 100%;
    }

    .scrolling-card {
        flex: 0 0 auto;
        padding-left: 8px;
    }

    .scrolling-wrapper::-webkit-scrollbar {
        display: none;
    }

    .radio-outer-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 160px;
        transition: all 0.2s ease-in-out;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        background-color: #fff;
    }

    .radio-outer-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .radio-thumbnail-effect-container {
        position: relative;
        width: 100%;
        height: 120px;
        overflow: hidden;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .radio-thumbnail-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: blur(8px);
        transform: scale(1.1);
        z-index: 0;
    }

    .radio-thumbnail-main-image {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        position: relative;
        z-index: 1;
        border-radius: 6px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.15);
    }

    .radio-info-box {
        width: 100%;
        background-color: white;
        padding: 8px;
        border-radius: 0 0 12px 12px;
        margin-top: 0px;
        box-shadow: none;
        text-align: left;
    }
    .radio-info-box h6 {
        font-size: 0.85rem;
        margin-bottom: 0.25rem !important;
    }
    .radio-info-box p {
        font-size: 0.7rem;
    }

    .text-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .region-group {
        margin-bottom: 2rem;
    }

    .section-heading {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        border-left: 5px solid #964734;
        padding-left: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 10px 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .section-heading i {
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: bold;
    }

    .region-heading {
        display: flex;
        align-items: center;
        font-size: 1.2rem;
        font-weight: 600;
        background-color: #f1f1f1;
        padding: 8px 16px;
        border-left: 4px solid #dc3545;
        border-radius: 6px;
        color: #333;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    .region-title {
        font-size: 1.1rem;
        font-weight: bold;
    }

    #rekomendasi-section .section-heading {
        border-left-color: #ff6b35;
        background: linear-gradient(to right, #fff5f0, #f8f9fa);
    }

    @media (min-width: 992px) {
        .scrolling-wrapper {
            justify-content: flex-start;
            margin-left: -12px;
        }
        .scrolling-card {
            flex: 0 0 calc(16.66% - 12px);
            padding-left: 12px;
        }
        .radio-outer-card {
            width: 180px;
        }
        .radio-thumbnail-effect-container {
            height: 130px;
        }
        .radio-info-box {
            padding: 10px;
        }
        .radio-info-box h6 {
            font-size: 0.9rem;
        }
        .radio-info-box p {
            font-size: 0.75rem;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .scrolling-card {
            flex: 0 0 calc(25% - 12px);
            padding-left: 12px;
        }
        .radio-outer-card {
            width: 170px;
        }
        .radio-thumbnail-effect-container {
            height: 125px;
        }
        .radio-info-box {
            padding: 9px;
        }
        .radio-info-box h6 {
            font-size: 0.88rem;
        }
        .radio-info-box p {
            font-size: 0.72rem;
        }
    }

    @media (max-width: 767px) {
        .scrolling-card {
            padding-left: 12px;
        }
        .radio-outer-card {
            width: 140px;
        }
        .radio-thumbnail-effect-container {
            height: 100px;
        }
        .radio-info-box {
            padding: 7px;
        }
        .radio-info-box h6 {
            font-size: 0.8rem;
        }
        .radio-info-box p {
            font-size: 0.65rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadRecommendations();
        setInterval(loadRecommendations, 300000);

        // Event listener untuk form search
        const searchForm = document.querySelector('form[action*="radio"]');
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                setTimeout(loadRecommendations, 500);
            });
        }

        // Event listener untuk input search (Enter)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    setTimeout(loadRecommendations, 500);
                }
            });
        }
    });

    function loadRecommendations() {
        const container = document.getElementById('rekomendasi-container');
        if (!container) {
            console.error('❌ Container tidak ditemukan!');
            return;
        }

        const searchInput = document.querySelector('input[name="search"]');
        const searchValue = searchInput ? searchInput.value : '';

        let url = '{{ route("pendengar.rekomendasi.json") }}';
        const params = new URLSearchParams();
        params.append('_', Date.now());
        if (searchValue) {
            params.append('search', searchValue);
        }
        url += '?' + params.toString();

        console.log('📡 Fetching:', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('📊 Data received:', data);
            if (data.success && data.data && data.data.length > 0) {
                console.log('✅ Rendering ' + data.data.length + ' rekomendasi...');
                renderRecommendations(data.data);
                updateTime();
            } else {
                console.log('❌ Data kosong, tampilkan empty state');
                showEmptyState();
            }
        })
        .catch(error => {
            console.error('❌ Error loading recommendations:', error);
            showEmptyState();
        });
    }

    function renderRecommendations(radios) {
        const container = document.getElementById('rekomendasi-container');
        if (!container) {
            console.error('❌ Container tidak ditemukan untuk render!');
            return;
        }
        console.log('🎨 Rendering ' + radios.length + ' radio(s)');
        container.innerHTML = '';

        radios.forEach(radio => {
            const card = document.createElement('div');
            card.className = 'scrolling-card';
            card.innerHTML = `
                <a href="/pendengar/player/${radio.id}" class="text-decoration-none text-dark">
                    <div class="radio-outer-card">
                        <div class="radio-thumbnail-effect-container">
                            <div class="radio-thumbnail-background" style="background-image: url('${radio.thumbnail}');"></div>
                            <img src="${radio.thumbnail}" alt="${radio.nama_stasiun}" class="radio-thumbnail-main-image">
                        </div>
                        <div class="radio-info-box shadow-sm">
                            <h6 class="mb-1">${radio.nama_stasiun}</h6>
                            <p class="text-muted small text-truncate mb-0">
                                <i class="fas fa-tag me-1"></i>${radio.genre || 'Umum'}
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-headphones me-1"></i>
                                ${formatNumber(radio.jumlah_pendengar)} pendengar
                            </small>
                        </div>
                    </div>
                </a>
            `;
            container.appendChild(card);
        });
    }

    function showEmptyState() {
        const container = document.getElementById('rekomendasi-container');
        if (!container) return;
        container.innerHTML = `
            <div class="text-center text-muted py-4 w-100">
                <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                <p>Belum ada rekomendasi. Dengarkan beberapa radio untuk mendapatkan rekomendasi yang dipersonalisasi!</p>
                <small class="text-muted">Atau lihat radio populer di bawah</small>
            </div>
        `;
    }

    function updateTime() {
        const timeElement = document.getElementById('rekomendasi-update-time');
        if (timeElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            timeElement.innerHTML = `<i class="fas fa-sync-alt me-1"></i> Diperbarui: ${timeString}`;
        }
    }

    function formatNumber(num) {
        if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
        if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
        return num;
    }
</script>
@endpush