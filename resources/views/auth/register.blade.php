@extends('layouts.app')

@section('content')
<style>
    nav.navbar { display: none !important; }

    body, html {
        background-color: #AFDDE5;
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
        box-sizing: border-box;
    }

    .card.shadow-lg {
        width: 100%;
        max-width: 800px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        border: none;
        display: flex;
        flex-wrap: nowrap;
        background-color: white;
    }

    .row.no-gutters {
        margin: 0;
        width: 100%;
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
    }

    .col-md-6.left-column,
    .col-md-6.right-column {
        flex: 1;
        max-width: 50%;
        padding: 40px 30px;
        box-sizing: border-box;
    }

    .col-md-6.left-column {
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        flex-direction: column;
    }

    .col-md-6.left-column img {
        max-height: 150px;
        width: auto;
        margin-bottom: 15px;
    }

    .col-md-6.left-column .tagline {
        font-size: 1rem;
        color: #444;
        font-weight: 500;
    }

    .col-md-6.right-column {
        background-color: #0FA4AF;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .col-md-6.right-column h2 {
        margin-bottom: 8px;
        font-weight: 700;
        font-size: 2rem;
        text-align: center;
        color: white;
    }

    .welcome-text {
        text-align: center;
        font-size: 1rem;
        font-weight: 400;
        color: #e2e2e2;
        margin-bottom: 30px;
    }

    form .form-group {
        margin-bottom: 20px;
    }

    form .form-group label {
        font-weight: 600;
        font-size: 16px;
        color: white;
        margin-bottom: 5px;
        display: block;
    }

    form .form-control {
        border-radius: 8px;
        border: none;
        padding: 10px 14px;
        font-size: 15px;
        height: 38px;
    }

    textarea.form-control {
        height: auto;
        resize: vertical;
    }

    .invalid-feedback {
        font-size: 0.9rem;
        color: #ffdddd;
        margin-top: 5px;
    }

    button.btn-primary {
        background-color: #024950;
        border: none;
        height: 42px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 99px;
        width: 100%;
        margin-top: 25px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        color: white;
    }

    button.btn-primary:hover {
        background-color: #022f32;
    }

    .text-center.mt-3 a {
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .text-center.mt-3 a:hover {
        text-decoration: underline;
        color: #e5e5e5;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-col {
        flex: 1;
        min-width: 0;
    }

    /* RESPONSIVE */
    @media(max-width: 767.98px) {
        .card.shadow-lg {
            flex-direction: column;
        }

        .row.no-gutters {
            flex-direction: column;
        }

        .col-md-6.left-column,
        .col-md-6.right-column {
            max-width: 100%;
            flex: 1 1 100%;
            padding: 30px 20px;
        }

        .col-md-6.left-column {
            order: 1;
        }

        .col-md-6.right-column {
            order: 2;
        }

        .col-md-6.left-column img {
            max-height: 130px;
        }

        .col-md-6.right-column h2 {
            font-size: 1.7rem;
        }

        .welcome-text {
            font-size: 0.95rem;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>

<div class="container">
    <div class="card shadow-lg">
        <div class="row no-gutters">
            <div class="col-md-6 left-column">
                <img src="{{ asset('images/misnfm_logo.png') }}" alt="MISN FM Logo">
                <div class="tagline">Teman Setia di Setiap Frekuensi</div>
            </div>

            <div class="col-md-6 right-column">
                <h2>Daftar Akun {{ ucfirst($userType) }}</h2>
                <div class="welcome-text">
                    @if($userType === 'wartawan')
                        Lengkapi data diri Anda sebagai wartawan
                    @else
                        Daftar sekarang dan nikmati hiburan tanpa henti!
                    @endif
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit', $userType) }}" enctype="multipart/form-data">
                    @csrf

                    @if($userType === 'wartawan')
                        <div class="form-group">
                            <label for="no_identitas">Nomor Identitas (NIK/ID PERS)</label>
                            <input id="no_identitas" type="text" class="form-control" name="no_identitas"
                                   value="{{ old('no_identitas') }}" required maxlength="16"
                                   placeholder="Masukkan Nomor Identitas">
                        </div>

                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input id="nama_lengkap" type="text" class="form-control" name="nama_lengkap"
                                   value="{{ old('nama_lengkap') }}" required placeholder="Masukkan Nama Lengkap">
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="no_telp">Nomor WhatsApp</label>
                                    <input id="no_telp" type="tel" class="form-control" name="no_telp"
                                           value="{{ old('no_telp') }}" required placeholder="Masukkan Nomor WhatsApp">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" required placeholder="Masukkan Email">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="asal_perusahaan">Asal Perusahaan</label>
                            <input id="asal_perusahaan" type="text" class="form-control" name="asal_perusahaan"
                                   value="{{ old('asal_perusahaan') }}" required placeholder="Masukkan Nama Perusahaan">
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" type="text" class="form-control" name="username"
                                   value="{{ old('username') }}" required placeholder="Masukkan Username">
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" class="form-control" name="alamat" required
                                      placeholder="Masukkan Alamat Lengkap">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="url_foto_kartu_identitas">Kartu Identitas (MAX 2MB)</label>
                            <input id="url_foto_kartu_identitas" type="file" class="form-control"
                                   name="url_foto_kartu_identitas" required
                                   accept="image/jpeg,image/png,image/jpg">
                            <small style="color: #e2e2e2;">Format: PNG, JPG, JPEG</small>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="password">Kata Sandi</label>
                                    <input id="password" type="password" class="form-control" name="password" required
                                           placeholder="Masukkan Kata Sandi">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                    <input id="password_confirmation" type="password" class="form-control"
                                           name="password_confirmation" required placeholder="Masukkan Ulang Kata Sandi">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input id="nama_lengkap" type="text" class="form-control" name="nama_lengkap"
                                   value="{{ old('nama_lengkap') }}" required placeholder="Masukkan Nama Lengkap">
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" type="text" class="form-control" name="username"
                                   value="{{ old('username') }}" required placeholder="Masukkan Username">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" name="email"
                                   value="{{ old('email') }}" required placeholder="Masukkan Email">
                        </div>

                        <div class="form-group">
                            <label for="no_telp">Nomor WhatsApp</label>
                            <input id="no_telp" type="tel" class="form-control" name="no_telp"
                                   value="{{ old('no_telp') }}" required placeholder="Masukkan Nomor WhatsApp">
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="password">Kata Sandi</label>
                                    <input id="password" type="password" class="form-control" name="password" required
                                           placeholder="Masukkan Kata Sandi">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                    <input id="password_confirmation" type="password" class="form-control"
                                           name="password_confirmation" required placeholder="Masukkan Ulang Kata Sandi">
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Daftar</button>

                    <div class="text-center mt-3">
                        <p>Sudah Punya Akun? <a href="{{ route('login') }}">Masuk</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('no_identitas')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 16);
    });

    document.getElementById('no_telp')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 15);
    });

    document.getElementById('url_foto_kartu_identitas')?.addEventListener('change', function(e) {
        if (this.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            this.value = '';
        }
    });
</script>
@endsection