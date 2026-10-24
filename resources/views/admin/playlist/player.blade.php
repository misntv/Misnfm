@extends('admin.layouts.app')

@section('title', 'Putar Radio - ' . $playlist->nama_stasiun)

@section('content')
<div class="container-fluid px-4 my-2">
<div class="card shadow rounded-4 overflow-hidden">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Kolom Kiri -->
            <div class="col-md-4 d-flex flex-column justify-content-center align-items-center text-center">
                <img src="{{ asset('storage/thumbnails/'.$playlist->thumbnail) }}"
                    alt="{{ $playlist->nama_stasiun }}"
                    class="img-fluid rounded mb-3"
                    style="width: 180px; height: 180px; object-fit: cover;">
                    <h4 class="fw-bold">{{ $playlist->nama_stasiun }}</h4>
                    <span class="badge px-3 py-2 mt-2 mx-auto" style="background-color: #974634; color: white;">
                            <i class="fas fa-headphones me-1"></i>
                    <span id="listener-count">{{ $playlist->jumlah_pendengar }}</span> Pendengar
                </span>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-8">
                <div class="border rounded p-4 bg-light">
                    <!-- Player -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pemutar Radio</label>
                        <audio id="radioPlayer" controls autoplay class="w-100 admin-audio-player">
                            <source src="{{ $playlist->url_stasiun }}" type="audio/mpeg">
                            Browser Anda tidak mendukung pemutar audio.
                        </audio>
                    </div>

                    <!-- Kontrol -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button id="volumeDown" class="btn btn-dark btn-sm">
                            <i class="fas fa-volume-down me-1"></i> Volume -
                        </button>
                        <button id="volumeUp" class="btn btn-dark btn-sm">
                            <i class="fas fa-volume-up me-1"></i> Volume +
                        </button>
                        <button id="muteBtn" class="btn btn-secondary btn-sm">
                            <i class="fas fa-volume-mute me-1"></i> Mute
                        </button>
                        <button id="stopBtn" class="btn btn-danger btn-sm">
                            <i class="fas fa-stop me-1"></i> Stop
                        </button>
                        <button id="refresh-btn" class="btn btn-primary btn-sm ms-auto">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>


                    <!-- Informasi Stream -->
                    <div class="mb-3 small">
                        <div><strong>URL Stream:</strong> <span id="current-stream-url">{{ $playlist->url_stasiun }}</span></div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="form-label fw-semibold">Deskripsi Stasiun</label>
                        <p class="text-muted mb-0">{{ $playlist->deskripsi }}</p>
                    </div>
                </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('playlist.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const player = document.getElementById('radioPlayer');
        const volumeUp = document.getElementById('volumeUp');
        const volumeDown = document.getElementById('volumeDown');
        const stopBtn = document.getElementById('stopBtn');
        const muteBtn = document.getElementById('muteBtn');
        const refreshBtn = document.getElementById('refresh-btn');
        const streamStatusEl = document.getElementById('stream-status');

        if (player) {
            player.volume = 0.7;

            player.addEventListener('playing', function() {
                streamStatusEl.textContent = "Sedang memutar";
                streamStatusEl.className = "badge bg-success";
                updateListenerCount();
            });

            player.addEventListener('waiting', function() {
                streamStatusEl.textContent = "Mencoba menghubungkan...";
                streamStatusEl.className = "badge bg-warning text-dark";
            });

            player.addEventListener('error', function() {
                streamStatusEl.textContent = "Gagal memutar stream";
                streamStatusEl.className = "badge bg-danger";
            });

            volumeUp.addEventListener('click', () => {
                player.volume = Math.min(player.volume + 0.1, 1);
            });

            volumeDown.addEventListener('click', () => {
                player.volume = Math.max(player.volume - 0.1, 0);
            });

            stopBtn.addEventListener('click', () => {
                player.pause();
                player.currentTime = 0;
                streamStatusEl.textContent = "Dihentikan";
                streamStatusEl.className = "badge bg-secondary";
            });

            muteBtn.addEventListener('click', () => {
                player.muted = !player.muted;
                muteBtn.innerHTML = player.muted
                    ? '<i class="fas fa-volume-up me-1"></i> Unmute'
                    : '<i class="fas fa-volume-mute me-1"></i> Mute';
            });

            refreshBtn.addEventListener('click', () => {
                player.pause();
                player.currentTime = 0;
                player.load();
                player.play().catch(e => console.error('Playback failed:', e));
                streamStatusEl.textContent = "Mencoba menghubungkan...";
                streamStatusEl.className = "badge bg-warning text-dark";
            });
        }

        function updateListenerCount() {
            fetch(`{{ route('playlist.increment', $playlist->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      document.getElementById('listener-count').textContent =
                          parseInt(document.getElementById('listener-count').textContent) + 1;
                  }
              });
        }
    });
</script>
@endpush

@push('styles')
<style>
.admin-audio-player {
    height: 38px;
}

.admin-audio-player::-webkit-media-controls-panel {
    background-color: #f8f9fa;
    border-radius: 6px;
}

.admin-audio-player::-webkit-media-controls-play-button,
.admin-audio-player::-webkit-media-controls-mute-button {
    background-color: #0d6efd;
    border-radius: 50%;
    color: white;
}
</style>
@endpush
