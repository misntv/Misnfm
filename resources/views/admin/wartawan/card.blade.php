<style>
    .profile-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .profile-top {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .profile-card img.avatar {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        background-color: #ccc;
        flex-shrink: 0;
    }

    .lihat-id-link {
        display: inline-block;
        margin-top: 5px;
        font-size: 0.9rem;
        text-decoration: underline;
        color: #007bff;
        cursor: pointer;
    }

    .lihat-id-link:hover { color: #0056b3; }

    .profile-details-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .profile-details-table td {
        padding: 6px 8px;
        vertical-align: top;
    }

    .profile-details-table td:first-child {
        font-weight: bold;
        width: 160px;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .btn-approve {
        background-color: #04FF00;
        color: black;
        border: none;
        padding: 7px 18px;
        border-radius: 20px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-approve:hover { background-color: #00e600; }

    .btn-reject {
        background-color: #964734;
        color: white;
        border: none;
        padding: 7px 18px;
        border-radius: 20px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-reject:hover { background-color: #7c362a; }

    .modal-content {
        background-color: #003135 !important;
        color: white !important;
        border-radius: 12px !important;
        border-top: 13px solid #964734 !important;
        padding: 25px 20px !important;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .modal-footer {
        border-top: none !important;
        justify-content: center;
        margin-top: 25px;
        gap: 15px;
    }

    @media (max-width: 576px) {
        .profile-top {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
</style>

<div class="col-md-6 col-sm-12">
    <div class="profile-card">
        <div class="profile-top">
            <img src="{{ asset('default-avatar.png') }}" alt="avatar" class="avatar">
            <div>
                <h5>{{ $wartawan->nama_lengkap }}</h5>
                <small>Wartawan</small><br>
                @if($mode === 'verifikasi')
                    <span class="lihat-id-link" data-bs-toggle="modal" data-bs-target="#idModal{{ $wartawan->id }}">Lihat ID</span>
                @endif
            </div>
        </div>

        <table class="profile-details-table">
            <tr><td>Nomor Identitas</td><td>:</td><td>{{ $wartawan->no_identitas }}</td></tr>
            <tr><td>Nomor WhatsApp</td><td>:</td><td>{{ $wartawan->no_telp }}</td></tr>
            <tr><td>Email</td><td>:</td><td>{{ $wartawan->email }}</td></tr>
            <tr><td>Asal Perusahaan</td><td>:</td><td>{{ $wartawan->asal_perusahaan }}</td></tr>
            <tr><td>Username</td><td>:</td><td>{{ $wartawan->username }}</td></tr>
            <tr><td>Alamat</td><td>:</td><td>{{ $wartawan->alamat }}</td></tr>
        </table>

        @if($mode === 'verifikasi')
        <div class="action-buttons">
            <button class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $wartawan->id }}">Tolak</button>
            <button class="btn-approve" data-bs-toggle="modal" data-bs-target="#approveModal{{ $wartawan->id }}">Terima</button>
        </div>
        @endif
    </div>
</div>

@if($mode === 'verifikasi')
<!-- Modal Tolak -->
<div class="modal fade" id="rejectModal{{ $wartawan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <h5>Apakah anda yakin menolak <br><strong>"{{ $wartawan->nama_lengkap }}"</strong>?</h5>
            <div class="modal-footer">
                <button type="button" class="btn-reject" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.reject.wartawan', $wartawan->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-approve">Ya, Tolak</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terima -->
<div class="modal fade" id="approveModal{{ $wartawan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <h5>Apakah anda yakin menerima <br><strong>"{{ $wartawan->nama_lengkap }}"</strong> sebagai anggota?</h5>
            <div class="modal-footer">
                <button type="button" class="btn-reject" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.approve.wartawan', $wartawan->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-approve">Ya, Terima</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat ID -->
<div class="modal fade" id="idModal{{ $wartawan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <h5>Kartu Identitas<br><strong>{{ $wartawan->nama_lengkap }}</strong></h5>
            <div class="my-3">
                @if($wartawan->url_foto_kartu_identitas)
                    <img src="{{ Storage::url($wartawan->url_foto_kartu_identitas) }}" alt="Foto Kartu Identitas" class="img-fluid rounded shadow">
                @else
                    <p>Tidak ada foto kartu identitas tersedia.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif