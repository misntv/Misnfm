@extends('admin.layouts.app')

@section('title', 'Tambah Playlist')

@section('content')
<div class="container-fluid px-4 my-2">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success fade show" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="mb-4">Tambah Playlist Baru</h1>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('playlist.store') }}" enctype="multipart/form-data" id="playlist-form">
                @csrf

                <div class="mb-3 row align-items-center">
                    <label for="nama_stasiun" class="col-sm-2 col-form-label">Nama Stasiun</label>
                    <div class="col-sm-10 pe-5">
                        <input type="text" name="nama_stasiun" id="nama_stasiun" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="nama_daerah" class="col-sm-2 col-form-label">Daerah</label>
                    <div class="col-sm-10 pe-5">
                        <select name="nama_daerah" id="nama_daerah" class="form-select select2-daerah" required>
                            <option value="">Pilih atau ketik nama daerah</option>
                            <option value="Surabaya">Surabaya</option>
                            <option value="Malang">Malang</option>
                            <option value="Probolinggo">Probolinggo</option>
                            <option value="Jember">Jember</option>
                            <option value="Banyuwangi">Banyuwangi</option>
                            <option value="Sitobondo">Sitobondo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="url_stasiun" class="col-sm-2 col-form-label">URL Stasiun</label>
                    <div class="col-sm-10 pe-5">
                        <input type="url" name="url_stasiun" id="url_stasiun" class="form-control" required>
                        <small class="text-muted">Contoh: https://example.com/stream.mp3 atau https://example.com/stream.m3u8</small>
                    </div>
                </div>

                {{-- RSS Feed URL --}}
                <div class="mb-3 row align-items-center">
                    <label for="feed_url" class="col-sm-2 col-form-label">RSS Feed URL</label>
                    <div class="col-sm-8 pe-2">
                        <input type="url" name="feed_url" id="feed_url" class="form-control" 
                            placeholder="https://example.com/feed.xml">
                        <small class="text-muted">Kosongkan jika tidak ada RSS Feed</small>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary" id="btn-cek-rss" style="width:100%;">
                            <i class="fas fa-rss"></i> Cek RSS
                        </button>
                    </div>
                </div>
                
                <div class="mb-3 row align-items-center">
                    <label for="jadwal_url" class="col-sm-2 col-form-label">URL Jadwal Tayang</label>
                    <div class="col-sm-8 pe-2">
                        <input type="url" name="jadwal_url" id="jadwal_url" class="form-control" 
                            value="{{ old('jadwal_url', $playlist->jadwal_url ?? '') }}"
                            placeholder="https://www.girifm.com/jadwal">
                        <small class="text-muted">URL halaman jadwal tayang stasiun radio</small>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success" id="btn-cek-jadwal" style="width:100%;">
                            <i class="fas fa-calendar-alt"></i> Cek Jadwal
                        </button>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="genre" class="col-sm-2 col-form-label">Genre</label>
                    <div class="col-sm-10 pe-5">
                        <select name="genre" id="genre" class="form-select" required>
                            <option value="">Pilih Genre</option>
                            <option value="Pop">Pop</option>
                            <option value="Dangdut">Dangdut</option>
                            <option value="Religi">Religi</option>
                            <option value="Berita">Berita</option>
                            <option value="Lokal">Lokal</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="thumbnail" class="col-sm-2 col-form-label">Thumbnail</label>
                    <div class="col-sm-10 pe-5">
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4 row align-items-start">
                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10 pe-5">
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Tambahkan frekuensi stasiun atau informasi lainnya" required></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="offset-sm-2 col-sm-10 pe-5 text-end">
                        <button type="submit" class="btn btn-custom" style="width: 100px;">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Popup -->
<div class="popup-overlay d-none" id="custom-confirm">
    <div class="popup-box">
        <p><strong>Tambah Stasiun Radio</strong><br>
        Apakah Anda yakin ingin menambahkan stasiun radio baru?</p>
        <div class="d-flex justify-content-center gap-3 mt-3">
            <button type="button" class="btn-finish" id="confirm-submit">Simpan</button>
            <button type="button" class="btn-finish" style="background-color:#6c757d" id="cancel-submit">Batal</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Custom Styles for Popup -->
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
.popup-box .btn-finish {
    background-color: #964734;
    color: white;
    padding: 8px 30px;
    border: none;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
}
.popup-box .btn-finish:hover {
    background-color: #7c362a;
}
</style>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2-daerah').select2({
        tags: true,
        placeholder: "Pilih atau ketik nama daerah",
        allowClear: true,
        width: '100%',
        createTag: function(params) {
            if ($.trim(params.term) === '') return null;
            return {
                id: params.term,
                text: params.term,
                newTag: true
            };
        },
        templateResult: function(data) {
            var $result = $("<span>" + data.text + "</span>");
            if (data.newTag) {
                $result.append(" <span class='text-muted'>(custom)</span>");
            }
            return $result;
        }
    });

    // Auto-dismiss success alert
    setTimeout(function () {
        $('#success-alert').fadeOut('slow');
    }, 5000);

    // Custom popup confirm on submit
    $('#playlist-form').on('submit', function(e) {
        e.preventDefault();
        $('#custom-confirm').removeClass('d-none');
    });

    $('#confirm-submit').on('click', function () {
        $('#playlist-form')[0].submit();
    });

    $('#cancel-submit').on('click', function () {
        $('#custom-confirm').addClass('d-none');
    });

    // =============================
    // CEK RSS FEED - Auto-fill Genre
    // =============================
    $('#btn-cek-rss').on('click', function() {
        let feedUrl = $('#feed_url').val();
        
        if (!feedUrl) {
            alert('Masukkan URL RSS Feed terlebih dahulu!');
            return;
        }

        // Ubah tombol menjadi loading
        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: '{{ route("playlist.cekRss") }}',
            method: 'POST',
            data: {
                feed_url: feedUrl,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Pilih genre di dropdown
                    $('#genre').val(response.genre).trigger('change');
                    alert('✅ Genre berhasil diambil dari RSS: ' + response.genre);
                } else {
                    alert('❌ Gagal mengambil genre dari RSS:\n' + response.message);
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menghubungi server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert('❌ Error: ' + msg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-rss"></i> Cek RSS');
            }
        });
    });

    // =============================
    // CEK JADWAL TAYANG - Auto-fill Genre
    // =============================
    $('#btn-cek-jadwal').on('click', function() {
        let jadwalUrl = $('#jadwal_url').val();
        
        if (!jadwalUrl) {
            alert('Masukkan URL halaman jadwal tayang terlebih dahulu!');
            return;
        }

        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: '{{ route("playlist.cekJadwal") }}',
            method: 'POST',
            data: {
                jadwal_url: jadwalUrl,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#genre').val(response.genre).trigger('change');
                    alert('✅ Genre berhasil diambil dari jadwal tayang: ' + response.genre);
                } else {
                    alert('❌ Gagal mengambil genre dari jadwal tayang:\n' + response.message);
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menghubungi server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert('❌ Error: ' + msg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-calendar-alt"></i> Cek Jadwal');
            }
        });
    });
});
</script>
@endsection