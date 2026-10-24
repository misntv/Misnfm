@extends('wartawan.layouts.app')

@section('title', 'Berita Diarsipkan')

@section('content')
    <div class="container-fluid px-1 my-1 clearfix"></div>
    <div class="row">
        <div class="container-fluid px-5 my-3 pt-2">
            <div class="card border-0 shadow-sm"> <!-- Ubah border dan tambahkan shadow -->
                <div class="card-header d-flex justify-content-between align-items-center py-3"
                    style="background-color: #964734; border-radius: 0.375rem 0.375rem 0 0 !important;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-archive fa-lg me-3" style="color: #FFFFFF;"></i> <!-- Perbesar ikon -->
                        <h3 class="card-title mb-0" style="color: #FFFFFF; font-size: 1.50rem; font-weight: 600;">
                            Daftar Berita Arsip
                        </h3>
                    </div>
                    <div class="d-flex align-items-center"> <!-- Pisahkan bagian counter -->
                        <span style="color: white; font-size: 1rem; margin-right: 10px;"> {{-- teks juga sedikit diperbesar
                            --}}
                            Total Berita Arsip:
                        </span>
                        <span class="badge rounded-pill"
                            style="background-color: #0FA4AF; color: #FFFFFF; padding: 0.45em 0.75em; font-size: 0.95rem;">
                            {{ $beritas->total() }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4"> <!-- Tambahkan padding lebih rapi -->


                    {{-- Flash Message --}}
                    @foreach (['update_success', 'success', 'error'] as $msg)
                        @if(session($msg))
                            <div class="alert alert-{{ $msg === 'error' ? 'danger' : 'success' }} alert-dismissible fade show auto-dismiss text-center"
                                role="alert">
                                {{ session($msg) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    @endforeach

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light"
                                style="background-color: #FFF8E1 !important; border-bottom: 2px solid #964734; !important;">
                                <tr class="text-center">
                                    <th style="font-weight: bold;">Gambar</th>
                                    <th style="font-weight: bold;">Judul</th>
                                    <th style="font-weight: bold;">Topik</th>
                                    <th style="font-weight: bold;">Penulis</th>
                                    <th style="font-weight: bold;">Tanggal Kirim</th>
                                    <th style="font-weight: bold;">Status</th>
                                    <th style="font-weight: bold;">Status Edit</th>
                                    <th style="font-weight: bold;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($beritas as $berita)
                                    <tr class="text-muted align-middle">
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-center">
                                                <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}"
                                                    alt="{{ $berita->judul_berita }}" width="60" class="img-thumbnail"
                                                    style="opacity: 0.8;">
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ Str::limit($berita->judul_berita, 30, '...') }}</td>
                                        <td class="align-middle">{{ $berita->topik }}</td>
                                        <td class="align-middle">{{ $berita->penulis->nama_lengkap ?? 'Tidak diketahui' }}</td>
                                        <td class="align-middle">
                                            {{ \Carbon\Carbon::parse($berita->tanggal_kirim)->format('d/m/Y') }}
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
                                                <span class="badge" style="background-color: #4EF037; color: #000;">Disetujui</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                           @if($berita->edited_since_archieved)
    <span class="badge" style="background-color: #4EF037; color: #000;">Sudah Diedit</span>
@else
    <span class="badge bg-warning text-black">Belum Diedit</span>
@endif

                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-2 justify-content-center">
                                                {{-- Tombol Restore --}}
                                                <form action="{{ route('berita.restore', $berita->id) }}" method="POST"
                                                    id="restore-form-{{ $berita->id }}">
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-success restore-btn"
                                                        data-id="{{ $berita->id }}" data-judul="{{ $berita->judul_berita }}"
                                                        data-last-edited="{{ $berita->updated_at->format('d/m/Y H:i') }}"
                                                        data-can-restore="{{ $berita->edited_since_archieved ? '1' : '0' }}"
                                                        title="Kembalikan ke berita aktif" data-bs-toggle="tooltip">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>

                                                <a href="{{ route('berita.showarsip', $berita->id) }}" class="btn btn-sm"
                                                    style="background-color: #FF6B00; color: white;" title="Lihat"
                                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('berita.edit', $berita->id) }}" class="btn btn-sm"
                                                    style="background-color: #0066FF; color: white;" title="Edit Berita"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Tidak ada berita yang diarsipkan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $beritas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .alert.auto-dismiss {
            transition: opacity 0.5s ease-out;
        }

        .img-thumbnail {
            object-fit: cover;
            height: 60px;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.35em 0.65em;
            display: inline-block;
        }

        /* Center align text in table cells */
        table th,
        table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Center pagination */
        .pagination {
            justify-content: center;
        }

        /* Di bagian styles */
        .swal2-confirm {
            background-color: #964734 !important;
        }

        .swal2-title {
            font-size: 1.2rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto dismiss alerts
            document.querySelectorAll('.auto-dismiss').forEach(el => {
                setTimeout(() => {
                    let alert = bootstrap.Alert.getOrCreateInstance(el);
                    alert.close();
                }, 5000);
            });

            // Tooltip
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el);
            });

            // Restore confirmation
            document.querySelectorAll('.restore-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const beritaId = this.dataset.id;
                    const judul = this.dataset.judul;
                    const lastEdited = this.dataset.lastEdited;
                    const canRestore = this.dataset.canRestore === '1';
                    const status = this.closest('tr').querySelector('[class*="badge bg-"]').textContent.trim();

                    // Jika status berita "Disetujui"
                    if (status === 'Disetujui') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Dapat Dikembalikan',
                            html: `
                                    <div class="text-center">
                                        <p>Berita: <strong>${judul}</strong></p>
                                        <hr>
                                        <p class="text-danger">Berita yang telah disetujui tidak dapat dipulihkan atau diubah.</p>
                                        <p class="text-danger">Jika ingin membuat perubahan, harap ajukan berita baru.</p>
                                    </div>
                                `,
                            confirmButtonColor: '#964734',
                            confirmButtonText: 'Mengerti'
                        });
                        return;
                    }

                    if (!canRestore) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Bisa Dikembalikan',
                            html: `
                                    <div class="text-center">
                                        <p>Berita: <strong>${judul}</strong></p>
                                        <hr>
                                        <p class="text-danger">Silakan <strong>edit berita ini terlebih dahulu</strong> sebelum dapat dikembalikan ke daftar berita aktif.</p>
                                    </div>
                                `,
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Tutup'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Kembalikan Berita?',
                        html: `
                                <div class="text-center">
                                    <p>Berita: <strong>${judul}</strong></p>
                                    <hr>
                                    <p>Anda akan mengembalikan berita ini ke daftar aktif.</p>
                                </div>`,
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Kembalikan',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`restore-form-${beritaId}`).submit();
                        }
                    });
                });
            });

            // Tampilkan pesan error jika ada
            @if(session('error_edit'))
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Dapat Mengedit',
                    text: '{{ session('error_edit') }}',
                    confirmButtonColor: '#964734',
                    confirmButtonText: 'Mengerti'
                });
            @endif
            });
    </script>
@endpush