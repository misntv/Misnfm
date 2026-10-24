@extends('pendengar.layouts.app')

@section('title', 'Radio di ' . $daerahName)

{{-- Tambahkan class page-content --}}
@section('content')
<div class="page-content">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                Stasiun Radio di {{ $daerahName }}
            </h2>
            <a href="{{ route('pendengar.radio.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @if(isset($radios) && $radios->count() > 0)
            <div class="row">
                @foreach($radios as $radio)
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <a href="{{ route('pendengar.player.index', $radio->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm" style="border-radius: 12px; overflow: hidden; transition: all 0.2s;">
                            <div class="position-relative" style="height: 140px; background: #f0f0f0;">
                                <img src="{{ $radio->thumbnail ? asset('storage/thumbnails/'.$radio->thumbnail) : asset('img/default-radio.png') }}"
                                     alt="{{ $radio->nama_stasiun }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-2 text-center">
                                <h6 class="mb-1 fw-bold">{{ $radio->nama_stasiun }}</h6>
                                @if($radio->genre)
                                    <span class="badge" style="background: #964734; color: white; font-size: 0.7rem;">
                                        {{ $radio->genre }}
                                    </span>
                                @endif
                                <p class="text-muted small mb-0 mt-1" style="font-size: 0.7rem;">
                                    {{ $radio->deskripsi ? Str::limit($radio->deskripsi, 40) : 'Radio' }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $radio->nama_daerah ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $radios->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada stasiun radio di daerah ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
    .pagination .page-link {
        color: #964734;
        border-color: #ddd;
    }
    .pagination .page-item.active .page-link {
        background-color: #964734;
        border-color: #964734;
        color: white;
    }
</style>
@endpush