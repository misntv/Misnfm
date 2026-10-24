@extends('wartawan.layouts.app')

@section('content')
    <div class="container-fluid px-1 my-1 clearfix">
        <div class="row">
            <div class="container-fluid px-5 my-3 pt-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center py-3"
                        style="background-color: #AFDDE5; border-radius: 0.375rem 0.375rem 0 0 !important;">

                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center me-3">
                                <i class="fas fa-newspaper me-2" style="color: #000000; font-size: 1.5rem;"></i>
                                <h3 class="card-title mb-0" style="color: #000000; font-size: 1.5rem; font-weight: 600;">
                                    Daftar Berita Aktif
                                </h3>
                            </div>

                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <small style="font-size: 1.10rem; color: #6c757d;">
                                    Total Berita Aktif:
                                </small>
                                <span class="badge rounded-pill"
                                    style="background-color: #964734; color: #FFFFFF; font-size: 0.9rem; padding: 0.5em 0.8em; margin-left: 5px;">
                                    {{ $beritas->total() }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center ms-3">
                            <form action="{{ route('berita.index') }}" method="GET" class="h-100">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Cari berita..." value="{{ request('search') }}"
                                        style="border-radius: 0.25rem 0 0 0.25rem;">
                                    <button class="btn btn-sm" type="submit"
                                        style="background-color: #964734; color: white; border-radius: 0 0.25rem 0.25rem 0;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('berita.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show auto-dismiss text-center" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(request('search'))
                            <div class="alert alert-info alert-dismissible fade show auto-dismiss text-center" role="alert">
                                Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <th width="3" style="font-weight: bold;">No</th>
                                    <th style="font-weight: bold;">Gambar</th>
                                    <th style="font-weight: bold;">Judul Berita</th>
                                    <th style="font-weight: bold;">Topik</th>
                                    <th style="font-weight: bold;">Penulis</th>
                                    <th style="font-weight: bold;">Tanggal Kirim</th>
                                    <th style="font-weight: bold;">Status</th>
                                    <th style="font-weight: bold;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($beritas as $index => $berita)
                                        <tr class="align-middle">
                                            <td class="align-middle">
                                                {{ ($beritas->currentPage() - 1) * $beritas->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-center">
                                                    <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}"
                                                        alt="{{ $berita->judul_berita }}" width="60" class="img-thumbnail">
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ Str::limit($berita->judul_berita, 25, '...') }}</td>
                                            <td class="align-middle">{{ $berita->topik }}</td>
                                            <td class="align-middle">{{ $berita->penulis->nama_lengkap ?? 'Tidak diketahui' }}
                                            </td>
                                            <td class="align-middle">
                                                {{ \Carbon\Carbon::parse($berita->tanggal_kirim)->timezone('Asia/Jakarta')->translatedFormat('d/m/Y') }}
                                            </td>
                                            <td class="align-middle">
                                                @if($berita->status_unggah == 'pengajuan')
                                                    <span
                                                        class="badge bg-warning d-flex flex-column align-items-center justify-content-center"
                                                        style="height: 30px; width: 62px; line-height: 1.1; color: #000;">
                                                        <span>Menunggu</span>
                                                        <span>Verifikasi</span>
                                                    </span>
                                                @elseif($berita->status_unggah == 'ditolak')
                                                    <span class="badge bg-danger color: #000;">Ditolak</span>
                                                @elseif($berita->status_unggah == 'diterima')
                                                    <span class="badge"
                                                        style="background-color: #4EF037; color: #000;">Disetujui</span>
                                                @endif
                                            </td>

                                            <td class="align-middle">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('berita.show', $berita->id) }}" class="btn btn-sm"
                                                        style="background-color: #FF6B00; color: white;" title="Lihat"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('berita.archive', $berita->id) }}" method="POST"
                                                        class="d-inline" id="archive-form-{{ $berita->id }}">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm archive-btn"
                                                            style="background-color: #FF0000; color: white; border: none;"
                                                            data-id="{{ $berita->id }}" data-judul="{{ $berita->judul_berita }}"
                                                            title="Arsipkan" data-bs-toggle="tooltip" data-bs-placement="top">
                                                            <i class="fas fa-archive"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                @if(request('search'))
                                                    Tidak ditemukan berita dengan kata kunci "{{ request('search') }}"
                                                @else
                                                    Tidak ada berita aktif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            {{-- Add Bootstrap 5 pagination styling --}}
                            {{ $beritas->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.auto-dismiss');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            });

            const archiveButtons = document.querySelectorAll('.archive-btn');
            archiveButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const beritaId = this.getAttribute('data-id');
                    const judulBerita = this.getAttribute('data-judul');

                    Swal.fire({
                        html: `Apakah Anda yakin akan mengarsipkan berita dengan judul "<strong>${judulBerita}</strong>"?`,
                        showCancelButton: true,
                        confirmButtonColor: '#FF0000',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Arsipkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`archive-form-${beritaId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        /* Center all content in table cells */
        table th,
        table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Center badges */
        .badge {
            display: inline-block;
            margin: 0 auto;
        }

        /* Pagination adjustments */
        .pagination {
            justify-content: center;
            /* Ensures the pagination component itself is centered */
        }

        /* Target pagination links and icons for better sizing */
        .page-item .page-link {
            font-size: 0.875rem;
            /* Smaller font size for links */
            padding: 0.5rem 0.75rem;
            /* Adjust padding */
            line-height: 1.25;
            /* Maintain line height */
        }

        /* Ensure icons within pagination links are not oversized */
        .page-item .page-link i.fas {
            font-size: 0.875rem;
            /* Match the font size of the text */
            vertical-align: middle;
            /* Align icons vertically */
        }

        /* Search bar styling */
        .search-container {
            min-width: 250px;
            max-width: 350px;
        }

        .search-form .input-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-form input {
            border: 1px solid #ddd;
            padding: 0.375rem 0.75rem;
        }

        .search-form button {
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: #7a3928 !important;
        }

        .btn-clear {
            border: 1px solid #ddd;
            border-left: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush