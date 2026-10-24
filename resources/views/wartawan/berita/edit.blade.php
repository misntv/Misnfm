@extends('wartawan.layouts.app')

@section('title', 'Edit Berita: ' . $berita->judul_berita)

@section('content')
    <div class="main-content mx-auto" style="max-width: 1350px; padding: 2rem 2rem;">

        <h1 style="color: #964734; text-align: center; margin-top: -3px; padding-top: -1;">Formulir Edit Berita Radio MisnFM
        </h1>
        <div class="card shadow px-4" style="padding-top: 0.5rem;">
            <div class="card-body">
                <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3 row align-items-center">
                        <label for="judul_berita" class="col-sm-2 col-form-label fw-bold">Judul Berita</label>
                        <div class="col-sm-10 pe-5">
                            <input type="text" name="judul_berita" id="judul_berita"
                                class="form-control @error('judul_berita') is-invalid @enderror"
                                value="{{ old('judul_berita', $berita->judul_berita) }}" required>
                            @error('judul_berita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label for="url_gambar" class="col-sm-2 col-form-label fw-bold">Gambar Utama</label>
                        <div class="col-sm-10 pe-5">
                            <input type="file" name="url_gambar" id="url_gambar"
                                class="form-control @error('url_gambar') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg,image">
                            @error('url_gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: jpeg, png, jpg, gif (Maksimal 2MB)</small>
                            <div class="mt-2">
                                @if($berita->url_gambar)
                                    <img src="{{ asset('storage/berita/' . $berita->url_gambar) }}" alt="Current Image"
                                        width="100" class="img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label for="topik" class="col-sm-2 col-form-label fw-bold">Topik</label>
                        <div class="col-sm-10 pe-5">
                            @php
                                $topics = ['Politik', 'Hukum', 'Ekonomi', 'Sepak bola', 'Olahraga', 'Lifestyle', 'Hiburan', 'Kesenian', 'Nusantara', 'Teknologi', 'Otomotif', 'MISNTV', 'Kejahatan', 'Kriminal'];
                                $currentTopic = old('topik', $berita->topik);
                                $isCustomTopic = !in_array($currentTopic, $topics) && $currentTopic !== '';
                            @endphp

                            <select name="topik_select" id="topik_select"
                                class="form-select @error('topik') is-invalid @enderror">
                                <option value="" disabled>Pilih Topik</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic }}" {{ $currentTopic == $topic ? 'selected' : '' }}>
                                        {{ $topic }}
                                    </option>
                                @endforeach
                                <option value="Lainnya" {{ $isCustomTopic ? 'selected' : '' }}>Lainnya</option>
                            </select>

                            <input type="hidden" name="topik" id="topik" value="{{ $currentTopic }}">

                            <div id="customTopicContainer" class="mt-2"
                                style="display: {{ $isCustomTopic ? 'block' : 'none' }};">
                                <input type="text" name="custom_topik" id="custom_topik"
                                    class="form-control @error('topik') is-invalid @enderror"
                                    placeholder="Masukkan topik lainnya" value="{{ $isCustomTopic ? $currentTopic : '' }}"
                                    {{ $isCustomTopic ? 'required' : '' }}>
                                @error('topik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <script>
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

                        // Client-side validation for image size
                        document.getElementById('url_gambar').addEventListener('change', function (e) {
                            const file = e.target.files[0];
                            if (file) {
                                // Check file type
                                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                if (!validTypes.includes(file.type)) {
                                    alert('Format file tidak valid. Harap unggah gambar dengan format JPEG, PNG, atau JPG.');
                                    e.target.value = '';
                                    return;
                                }

                                // Check file size (2MB max)
                                const maxSize = 2 * 1024 * 1024; //25MB
                                if (file.size > maxSize) {
                                    alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
                                    e.target.value = '';
                                    return;
                                }
                            }
                        });
                    </script>

                    <div class="mb-4 row align-items-start">
                        <label for="isi_berita" class="col-sm-2 col-form-label fw-bold">Isi Berita</label>
                        <div class="col-sm-10 pe-5">
                            <div id="editorError" class="error-message"
                                style="display: none; color: red; margin-bottom: 10px;"></div>
                            <div id="quillEditor" style="height: 300px; width: 100%;">
                                {!! old('isi_berita', $berita->isi_berita) !!}
                            </div>
                            <textarea name="isi_berita" id="isi_berita" style="display:none;"
                                class="form-control @error('isi_berita') is-invalid @enderror"
                                required>{{ old('isi_berita', $berita->isi_berita) }}</textarea>
                            @error('isi_berita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-sm-2 col-sm-10 pe-5 text-end">
                            <a href="{{ route('berita.index') }}" class="btn btn-secondary me-2 fw-bold">Kembali</a>
                            <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Quill stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Include Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        // Quill editor initialization
        let quill;
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Quill === 'undefined') {
                document.getElementById('editorError').style.display = 'block';
                document.getElementById('editorError').innerText = 'Failed to load Quill.js. Please check your internet connection or try again later.';
            } else {
                try {
                    quill = new Quill('#quillEditor', {
                        theme: 'snow',
                        placeholder: 'Tulis isi berita di sini...',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
                                [{ 'align': [] }],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                [{ 'indent': '-1' }, { 'indent': '+1' }],
                                ['link'],
                                ['clean']
                            ]
                        }
                    });

                    // Update hidden textarea on text change
                    quill.on('text-change', function () {
                        var content = quill.root.innerHTML;
                        document.getElementById('isi_berita').value = content === '<p><br></p>' ? '' : content;
                    });

                    // Initialize with existing content
                    if (quill.root.innerHTML === '<p><br></p>') {
                        document.getElementById('isi_berita').value = '';
                    }
                } catch (e) {
                    document.getElementById('editorError').style.display = 'block';
                    document.getElementById('editorError').innerText = 'Error initializing Quill.js: ' + e.message;
                }
            }

            // Form submission handler
            document.querySelector("form").addEventListener("submit", function (event) {
                if (typeof Quill !== 'undefined') {
                    var content = quill.root.innerHTML;
                    document.getElementById('isi_berita').value = content === '<p><br></p>' ? '' : content;
                }
            });
        });
    </script>
@endsection