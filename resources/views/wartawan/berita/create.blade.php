@extends('wartawan.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
    <div class="container-fluid px-5 mt-2 pt-1" style="max-width: 1350px;">
        <h2 class="mb-3 mt-2 pt-1 text-center fw-bold" style="color: #964734;">Formulir Kirim Berita Radio MisnFM</h2>

        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('berita.store') }}" enctype="multipart/form-data" id="beritaForm">
                    @csrf

                    <!-- Judul Berita -->
                    <div class="mb-3 row align-items-center">
                        <label for="judul_berita" class="col-sm-2 col-form-label fw-bold">Judul Berita</label>
                        <div class="col-sm-10 pe-5">
                            <input type="text" name="judul_berita" id="judul_berita"
                                class="form-control @error('judul_berita') is-invalid @enderror"
                                value="{{ old('judul_berita') }}" required>
                            @error('judul_berita')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Gambar Utama -->
                    <div class="mb-3 row align-items-center">
                        <label for="url_gambar" class="col-sm-2 col-form-label fw-bold">Gambar Utama</label>
                        <div class="col-sm-10 pe-5">
                            <input type="file" name="url_gambar" id="url_gambar"
                                class="form-control @error('url_gambar') is-invalid @enderror" required>
                            @error('url_gambar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Format: JPEG, PNG, JPG (Maksimal 2MB)</small>
                        </div>
                    </div>

                    <!-- Topik -->
                    <div class="mb-3 row align-items-center">
                        <label for="topik" class="col-sm-2 col-form-label fw-bold">Topik</label>
                        <div class="col-sm-10 pe-5">
                            <select name="topik_select" id="topik_select"
                                class="form-select @error('topik') is-invalid @enderror" required>
                                <option value="" selected disabled>Pilih Topik</option>
                                @foreach(['Politik', 'Hukum', 'Ekonomi', 'Sepak Bola', 'Olahraga', 'Lifestyle', 'Hiburan', 'Kesenian', 'Nusantara', 'Teknologi', 'Otomotif', 'MISNTV', 'Kejahatan', 'Kriminal'] as $topic)
                                    <option value="{{ $topic }}" {{ old('topik') == $topic || (session('selected_topic') == $topic) ? 'selected' : '' }}>
                                        {{ $topic }}
                                    </option>
                                @endforeach
                                <option value="Lainnya" {{ old('topik_select') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            <input type="hidden" name="topik" id="topik"
                                value="{{ old('topik', session('selected_topic')) }}">
                            <div id="customTopicContainer" class="mt-2"
                                style="display: {{ old('topik_select') == 'Lainnya' ? 'block' : 'none' }};">
                                <input type="text" name="custom_topik" id="custom_topik" class="form-control"
                                    placeholder="Masukkan topik lainnya" value="{{ old('custom_topik') }}">
                            </div>
                            @error('topik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Isi Berita -->
                    <div class="mb-5 row align-items-start">
                        <label for="isi_berita" class="col-sm-2 col-form-label fw-bold">Isi Berita</label>
                        <div class="col-sm-10 pe-5">
                            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                            <div id="editorError" class="error-message" style="display: none;"></div>
                            <div id="quillEditor"
                                style="height: 300px; background-color: white; border-radius: 0.375rem; border: 1px solid #ced4da;">
                                {!! old('isi_berita', session('quill_content')) !!}
                            </div>
                            <textarea name="isi_berita" id="isi_berita" class="form-control d-none" rows="10"
                                required>{{ old('isi_berita', session('quill_content')) }}</textarea>
                            @error('isi_berita')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-lg w-100 py-2 fw-bold"
                                style="background-color: #964734; color: white;">
                                <i class="fas fa-paper-plane me-2 "></i> Ajukan Berita
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Inisialisasi Quill dengan konten sebelumnya jika ada
        let quill;
        window.onload = function () {
            if (typeof Quill === 'undefined') {
                document.getElementById('editorError').style.display = 'block';
                document.getElementById('editorError').innerText = 'Failed to load Quill.js. Please check your internet connection or try again later.';
            } else {
                try {
                    quill = new Quill('#quillEditor', {
                        theme: 'snow',
                        placeholder: 'Tulis isi berita disini...',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
                                [{ 'align': [] }],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                [{ 'indent': '-1' }, { 'indent': '+1' }],
                                ['link', 'image'],
                                ['clean']
                            ]
                        }
                    });

                    // Set konten sebelumnya jika ada
                    @if(old('isi_berita') || session('quill_content'))
                        quill.root.innerHTML = {!! json_encode(old('isi_berita', session('quill_content'))) !!};
                    @endif

                    quill.on('text-change', function () {
                        var content = quill.root.innerHTML;
                        document.getElementById('isi_berita').value = content === '<p><br></p>' ? '' : content;
                    });

                    if (quill.root.innerHTML === '<p><br></p>') {
                        document.getElementById('isi_berita').value = '';
                    }
                } catch (e) {
                    document.getElementById('editorError').style.display = 'block';
                    document.getElementById('editorError').innerText = 'Error initializing Quill.js: ' + e.message;
                }
            }
        };

        // Form submission handler
       document.getElementById('beritaForm').addEventListener('submit', function (e) {
    e.preventDefault();
    
    // Update semua field sebelum submit
    if (typeof Quill !== 'undefined' && quill) {
        var content = quill.root.innerHTML;
        document.getElementById('isi_berita').value = content === '<p><br></p>' ? '' : content;
    }

            // Validate content
            if (document.getElementById('isi_berita').value === '') {
                alert('Isi berita tidak boleh kosong');
                return;
            }

            // Show confirmation
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin akan mengajukan berita baru?',
                showCancelButton: true,
                confirmButtonColor: '#964734',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ajukan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Topik selection script
        document.getElementById('topik_select').addEventListener('change', function () {
            const customTopicContainer = document.getElementById('customTopicContainer');
            if (this.value === 'Lainnya') {
                customTopicContainer.style.display = 'block';
                document.getElementById('custom_topik').required = true;
                document.getElementById('topik').value = '';
            } else {
                customTopicContainer.style.display = 'none';
                document.getElementById('custom_topik').required = false;
                document.getElementById('topik').value = this.value;
            }
        });

        document.getElementById('custom_topik').addEventListener('input', function () {
            if (document.getElementById('topik_select').value === 'Lainnya') {
                document.getElementById('topik').value = this.value.trim();
            }
        });

        // Inisialisasi topik jika kembali ke halaman dengan error
        @if(old('topik_select') == 'Lainnya' || (session('selected_topic') && !in_array(session('selected_topic'), ['Politik', 'Hukum', 'Ekonomi', 'Sepak Bola', 'Olahraga', 'Lifestyle', 'Hiburan', 'Kesenian', 'Nusantara', 'Teknologi', 'Otomotif', 'MISNTV', 'Kejahatan', 'Kriminal'])))
            document.getElementById('topik_select').value = 'Lainnya';
            document.getElementById('customTopicContainer').style.display = 'block';
            document.getElementById('custom_topik').value = '{{ old('custom_topik', session('selected_topic')) }}';
            document.getElementById('topik').value = '{{ old('custom_topik', session('selected_topic')) }}';
        @endif
    </script>
@endsection