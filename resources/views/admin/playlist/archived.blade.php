@extends('admin.layouts.app')

@section('title', 'Playlist Diarsipkan')

@section('content')
@php
    $playlists->appends(request()->query());
@endphp

<div class="container-fluid px-4 my-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="mb-0 fw-semibold">Playlist Diarsipkan</h3>
                </div>

                <div class="card-body bg-light rounded-bottom-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded" role="alert" id="autoCloseAlert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 bg-white rounded-3 table-arsip">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Thumbnail</th>
                                    <th class="text-center">Stasiun Radio</th>
                                    <th class="text-center">Daerah</th>
                                    <th class="text-center">URL</th>
                                    <th class="text-center">Upload</th>
                                    <th class="text-center">Pendengar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($playlists as $playlist)
                                    <tr>
                                        <td class="align-middle text-center">
                                            {{-- Adjusted thumbnail container for consistent sizing --}}
                                            <div class="thumbnail-container">
                                                @if($playlist->thumbnail)
                                                    <img src="{{ asset('storage/thumbnails/'.$playlist->thumbnail) }}"
                                                         alt="{{ $playlist->nama_stasiun }}"
                                                         class="img-thumbnail rounded thumbnail-image"
                                                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                                                @else
                                                    <div class="bg-secondary d-flex align-items-center justify-content-center thumbnail-placeholder">
                                                        <i class="fas fa-music text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">{{ $playlist->nama_stasiun }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-info-subtle text-dark fw-medium shadow-sm">
                                                {{ $playlist->nama_daerah }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ $playlist->url_stasiun }}" target="_blank" class="text-decoration-none">
                                                <span class="badge bg-light text-dark border border-1">
                                                    <i class="fas fa-link me-1"></i> Lihat
                                                </span>
                                            </a>
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ \Carbon\Carbon::parse($playlist->tanggal_upload)->format('d M Y') }}<br>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary fw-medium shadow-sm">
                                                {{ number_format($playlist->jumlah_pendengar) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('playlist.restore', $playlist->id) }}" method="POST" class="d-inline restore-form">
                                                @csrf
                                                <button type="button" class="btn btn-custom rounded-pill shadow-sm px-3 btn-restore">
                                                    <i class="fas fa-undo me-1"></i> Pulihkan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                            <strong class="d-block">Tidak ada playlist yang diarsipkan</strong>
                                            <small class="text-muted">Semua playlist aktif saat ini.</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($playlists->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $playlists->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="popupConfirm" class="popup-overlay" style="display: none;">
    <div class="popup-box">
        <p><strong>Kembalikan Playlist</strong><br>
        Apakah Anda yakin ingin memulihkan playlist ini?</p>
        <div class="d-flex justify-content-center gap-3">
            <button id="popupCancel" class="btn-finish" style="background-color: gray;">Batal</button>
            <button id="popupYes" class="btn-finish">Ya, Pulihkan</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
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
    }

    .btn-finish:hover {
        background-color: #7c362a;
    }

    /* New CSS for consistent thumbnail sizing */
    .thumbnail-container {
        width: 100px; /* Set a fixed width */
        height: 60px; /* Set a fixed height */
        overflow: hidden; /* Hide overflowing parts of the image */
        margin: 0 auto; /* Center the container */
        display: flex; /* Use flexbox for centering content */
        align-items: center; /* Center vertically */
        justify-content: center; /* Center horizontally */
        border: 1px solid #ddd; /* Optional: Add a subtle border */
        border-radius: 5px; /* Optional: Rounded corners */
    }

    .thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* This is the key: it covers the area while maintaining aspect ratio */
    }

    .thumbnail-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close alert
        const alert = document.getElementById('autoCloseAlert');
        if (alert) {
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        // Popup logic
        const popup = document.getElementById('popupConfirm');
        const popupYes = document.getElementById('popupYes');
        const popupCancel = document.getElementById('popupCancel');
        let currentForm = null;

        document.querySelectorAll('.btn-restore').forEach(btn => {
            btn.addEventListener('click', function () {
                currentForm = this.closest('form');
                popup.style.display = 'flex';
            });
        });

        popupCancel.addEventListener('click', () => {
            popup.style.display = 'none';
            currentForm = null;
        });

        popupYes.addEventListener('click', () => {
            if (currentForm) {
                currentForm.submit();
            }
        });
    });
</script>
@endsection