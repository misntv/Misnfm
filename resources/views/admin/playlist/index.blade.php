@extends('admin.layouts.app')

@section('title', 'Kelola Playlist')

@section('content')
@php
    // Default values for variables that might be undefined
    $sortField = $sortField ?? 'created_at';
    $sortDirection = $sortDirection ?? 'desc';
    $playlists = $playlists ?? collect(); // Use collect() for empty to prevent errors
@endphp

<div class="container-fluid px-4 my-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-0">Daftar Playlist Aktif</h3>
                        <p class="mb-0 text-muted">
                            <small>Total Radio Aktif: <span class="badge badge-primary">{{ $playlists->total() }}</span></small>
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('playlist.search') }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label for="search" class="form-label text-muted small">Pencarian Nama Stasiun</label>
                                        <div class="input-group">
                                            <input type="text" id="search" name="search" class="form-control" placeholder="Cari radio..." value="{{ request('search') }}">
                                            <button class="btn" type="submit" style="background-color: #964734; color: white; border: none;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filter_listeners" class="form-label text-muted small">Filter Pendengar</label>
                                        <select id="filter_listeners" name="filter_listeners" class="form-select">
                                            <option value="">Semua Pendengar</option>
                                            <option value="high" {{ request('filter_listeners') == 'high' ? 'selected' : '' }}>Pendengar Tinggi (&gt;1000)</option>
                                            <option value="medium" {{ request('filter_listeners') == 'medium' ? 'selected' : '' }}>Pendengar Menengah (100–1000)</option>
                                            <option value="low" {{ request('filter_listeners') == 'low' ? 'selected' : '' }}>Pendengar Rendah (&lt;100)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn flex-grow-1"
                                                style="background-color: #964734; color: white; border: none;">
                                            <i class="fas fa-filter"></i> Terapkan Filter
                                        </button>
                                        <a href="{{ route('playlist.index') }}" class="btn flex-grow-1"
                                           style="background-color: #a7a6a6; color: #333; border: none;">
                                            <i class="fas fa-sync-alt"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="70" class="text-center align-middle">No</th>
                                    <th width="150" class="text-center align-middle">Thumbnail</th>
                                    <th class="text-center align-middle">Nama Stasiun</th>
                                    <th class="text-center align-middle">Genre</th>
                                    <th class="text-center align-middle">Daerah</th>
                                    <th class="text-center align-middle">URL</th>
                                    <th width="150" class="text-center align-middle">Tanggal Upload</th>
                                    <th width="70" class="text-center align-middle">Pendengar</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($playlists as $index => $playlist)
                                <tr class="table-row">
                                    <td class="align-middle text-center">
                                        {{ ($playlists->currentPage() - 1) * $playlists->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="align-middle text-center"> {{-- Added text-center here too --}}
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
                                    <td class="align-middle text-center">
                                        <strong>{{ $playlist->nama_stasiun }}</strong>
                                    </td>

                                    <td class="align-middle text-center">
                                        <span style="color: #333; font-weight: 600;">
                                            {{ $playlist->genre ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="align-middle text-center">
                                        <span class="badge badge-daerah-custom">
                                            {{ $playlist->nama_daerah ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ $playlist->url_stasiun }}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Kunjungi
                                        </a>
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ \Carbon\Carbon::parse($playlist->tanggal_upload)->format('d M Y') }}<br>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-pill badge-info">
                                            {{ number_format($playlist->jumlah_pendengar) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex flex-column gap-2 align-items-center">
                                            <a href="{{ route('playlist.show', $playlist->id) }}"
                                            class="btn btn-info"
                                            style="padding: 5px 10px; font-size: 1rem;"
                                            title="Detail"
                                            data-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('playlist.edit', $playlist->id) }}"
                                            class="btn btn-warning"
                                            style="padding: 5px 10px; font-size: 1rem;"
                                            title="Edit"
                                            data-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('playlist.jadwal', $playlist->id) }}"
                                            class="btn btn-secondary"
                                            style="padding: 5px 10px; font-size: 1rem;"
                                            title="Kelola Jadwal Tayang"
                                            data-toggle="tooltip">
                                                <i class="fas fa-calendar-alt"></i>
                                            </a>
                                            <form action="{{ route('playlist.archive', $playlist->id) }}" method="POST" class="d-inline archive-form">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-danger"
                                                        style="padding: 5px 10px; font-size: 1rem;"
                                                        title="Arsipkan"
                                                        data-toggle="tooltip">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-music fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada playlist aktif</p>
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
@endsection

@push('styles')
<style>
    .badge-daerah-custom {
        background-color: #6c757d;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
    }

    .table-row:hover {
        background-color: #f8f9fa;
    }

    th a {
        color: inherit;
        text-decoration: none;
    }

    th a:hover {
        color: #007bff;
    }

    .btn-xs {
        padding: 0.25rem 0.4rem;
        font-size: 0.75rem;
        line-height: 1;
        border-radius: 0.2rem;
    }

    .swal2-popup {
        font-size: 0.875rem;
    }
    .swal2-title {
        font-size: 1.25rem;
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

    .badge-genre-custom {
        background-color: #964734;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Auto dismiss bootstrap alert after 5 seconds
        const autoDismissAlert = document.getElementById('autoDismissAlert');
        if (autoDismissAlert) {
            setTimeout(function() {
                $(autoDismissAlert).alert('close');
            }, 5000);
        }

        // Archive confirmation
        document.querySelectorAll('.archive-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const currentForm = this;

                Swal.fire({
                    title: 'Konfirmasi Arsipkan',
                    text: "Apakah Anda yakin ingin mengarsipkan playlist ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Arsipkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        currentForm.submit();
                    }
                });
            });
        });
    });
</script>
@endpush