@extends('pendengar.layouts.app')

@section('title', 'Player Radio - ' . $radio->nama_stasiun)

@section('content')
    <div class="container py-4">
        <div class="row">
            <!-- Main Player Section -->
            <div class="col-lg-8">
                <!-- Player Card -->
                <div class="card shadow-sm mb-4 border-0"
                    style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Radio Thumbnail -->
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <div class="position-relative" style="width: 200px; height: 200px; margin: 0 auto;">
                                    <img src="{{ $radio->thumbnail ? asset('storage/thumbnails/' . $radio->thumbnail) : asset('img/default-radio.png') }}"
                                        class="img-fluid rounded-circle shadow" alt="{{ $radio->nama_stasiun }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border: 4px solid white;">
                                </div>
                            </div>

                            <!-- Radio Info and Controls -->
                            <div class="col-md-8">
                                <h2 class="mb-1 fw-bold text-dark">{{ $radio->nama_stasiun }}</h2>
                                
                                @if($radio->genre)
                                    <p class="mb-1">
                                        <span class="badge" style="background-color: #964734; color: white; font-size: 0.9rem; padding: 4px 12px; border-radius: 20px;">
                                            <i class="fas fa-tag me-1"></i> {{ $radio->genre }}
                                        </span>
                                    </p>
                                @endif

                                @if($radio->nama_daerah)
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $radio->nama_daerah }}
                                    </p>
                                @endif

                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-primary-soft text-primary rounded-pill px-3 py-1 ms-1">
                                        <i class="fas fa-headphones me-1"></i>
                                        {{ number_format($radio->jumlah_pendengar) }} Pendengar
                                    </span>
                                    <span class="badge bg-secondary-soft text-secondary rounded-pill px-3 py-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $radio->tanggal_upload->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Audio Player -->
                                <div class="mb-3">
                                    <audio id="radioPlayer" controls autoplay class="w-100 custom-audio-player"
                                        crossorigin="anonymous">
                                        <source src="{{ $radio->url_stasiun }}" type="audio/mpeg">
                                        Browser Anda tidak mendukung elemen audio.
                                    </audio>
                                </div>

                                <!-- Player Controls -->
                                <canvas id="audioSpectrum" class="w-100" height="60"
                                    style="background:#f1f1f1; border-radius:8px;"></canvas>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button id="volumeDown" class="btn btn-icon btn-outline-primary rounded-circle"
                                        title="Volume Down">
                                        <i class="fas fa-volume-down"></i>
                                    </button>
                                    <button id="stopBtn" class="btn btn-icon btn-outline-danger rounded-circle"
                                        title="Stop">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                    <button id="muteBtn" class="btn btn-icon btn-outline-secondary rounded-circle"
                                        title="Mute">
                                        <i class="fas fa-volume-mute"></i>
                                    </button>
                                    <button id="volumeUp" class="btn btn-icon btn-outline-primary rounded-circle"
                                        title="Volume Up">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                    <button id="playPauseBtn" class="btn btn-icon btn-outline-success rounded-circle"
                                        title="Play">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Radio Description -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-align-left me-2 text-primary"></i>
                            Deskripsi
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">{{ $radio->deskripsi }}</p>
                    </div>
                </div>

                <!-- Comment Ayuk (Backend untuk komentar) -->
                <!-- Comments Section -->
                <div class="card shadow-sm mb-4 border-0" id="comments-section">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center"
                        style="background-color: #964734;">
                        <!-- Di bagian header komentar -->
                        <h5 class="mb-0 fw-semibold text-white">
                            <i class="fas fa-comments me-2 text-light"></i>
                            Komentar ({{ \App\Models\Comment::countForRadio($radio->id) }})
                        </h5>
                        <button class="btn btn-sm btn-light rounded-pill px-3" id="add-comment-btn">
                            <!-- Ubah btn-success menjadi btn-light -->
                            <i class="fas fa-plus me-1"></i> Tambah Komentar
                        </button>
                    </div>
                    <!-- Comment Form (Initially hidden) -->
                    <div class="card-body bg-light rounded-bottom" id="comment-form-container" style="display: none;">
                        <form action="{{ route('comments.store') }}" method="POST" id="commentForm">
                            @csrf
                            <input type="hidden" name="radio_id" value="{{ $radio->id }}">
                            <input type="hidden" name="parent_id" id="parent_id" value="">
                            <div class="mb-3">
                                <label for="commentText" class="form-label fw-medium">Komentar Anda</label>
                                <textarea class="form-control border-2" id="commentText" name="content" rows="3" required
                                    style="border-radius: 8px; border-color: #e0e0e0;"></textarea>
                                <small class="text-muted">Gunakan @ untuk menandai pengguna lain</small>
                            </div>
                            <div class="d-flex justify-content-end gap-2" style="margin-top: -20px;">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-3"
                                    id="cancel-comment-btn">Batal</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4">Kirim Komentar</button>
                            </div>
                        </form>
                    </div>

                    <!-- Edit Comment Form (Initially hidden) -->
                    <div class="card-body bg-light rounded-bottom" id="edit-comment-container" style="display: none;">
                        <form id="editCommentForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editCommentText" class="form-label fw-medium">Edit Komentar</label>
                                <textarea class="form-control border-2" id="editCommentText" name="content" rows="3"
                                    required style="border-radius: 8px; border-color: #e0e0e0;"></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button"
                                    class="btn btn-outline-secondary rounded-pill px-3 cancel-edit">Batal</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="card-body p-0">
                        @if($radio->comments()->count() > 0)
                            <div class="comments-list p-3">
                                @foreach($radio->comments()->whereNull('parent_id')->latest()->get() as $comment)
                                    @include('pendengar.player.partials.comments', ['comment' => $comment])
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-comment-slash me-2"></i>
                                Belum ada komentar
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Popular Radios -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-fire me-2 text-danger"></i>
                            Radio Populer Lainnya
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($popularRadios as $popular)
                            <div class="d-flex align-items-center mb-3 radio-item p-2 rounded"
                                style="transition: all 0.2s ease;">
                                <div class="position-relative me-3">
                                    <img src="{{ $popular->thumbnail ? asset('storage/thumbnails/' . $popular->thumbnail) : asset('img/default-radio.png') }}"
                                        class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-1"
                                        style="width: 16px; height: 16px;">
                                        <i class="fas fa-play text-white" style="font-size: 0.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-medium">{{ $popular->nama_stasiun }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-headphones me-1"></i>
                                        {{ number_format($popular->jumlah_pendengar) }}
                                    </small>
                                </div>
                                <a href="{{ route('pendengar.player.index', $popular->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle" title="Putar">
                                    <i class="fas fa-play" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recently Played -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-history me-2 text-info"></i>
                            Baru Saja Diputar
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($recentlyPlayed as $recent)
                            <div class="d-flex align-items-center mb-3 radio-item p-2 rounded"
                                style="transition: all 0.2s ease;">
                                <div class="position-relative me-3">
                                    <img src="{{ $recent->thumbnail ? asset('storage/thumbnails/' . $recent->thumbnail) : asset('img/default-radio.png') }}"
                                        class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-1"
                                        style="width: 16px; height: 16px;">
                                        <i class="fas fa-play text-white" style="font-size: 0.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-medium">{{ $recent->nama_stasiun }}</h6>
                                    @if($recent->created_at)
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $recent->created_at->diffForHumans() }}
                                        </small>
                                    @endif
                                </div>
                                <a href="{{ route('pendengar.player.index', $recent->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle" title="Putar">
                                    <i class="fas fa-play" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Belum ada riwayat pemutaran
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const player = document.getElementById('radioPlayer');
    const volUp = document.getElementById('volumeUp');
    const volDown = document.getElementById('volumeDown');
    const stopBtn = document.getElementById('stopBtn');
    const muteBtn = document.getElementById('muteBtn');
    const playPauseBtn = document.getElementById('playPauseBtn');
    
    // ==========================================
    // AUDIO SPECTRUM VISUALIZER
    // ==========================================
    const canvas = document.getElementById('audioSpectrum');
    const ctx = canvas.getContext('2d');
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const analyser = audioCtx.createAnalyser();
    const source = audioCtx.createMediaElementSource(player);
    source.connect(analyser);
    analyser.connect(audioCtx.destination);
    analyser.fftSize = 128;

    const bufferLen = analyser.frequencyBinCount;
    const dataArr = new Uint8Array(bufferLen);

    function draw() {
        requestAnimationFrame(draw);
        analyser.getByteFrequencyData(dataArr);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const barW = (canvas.width / bufferLen) * 1.5;
        let x = 0;
        for (let i = 0; i < bufferLen; i++) {
            const value = dataArr[i];
            const scaledHeight = (value / 255) * canvas.height * 0.6;
            ctx.fillStyle = 'rgb(' + (value + 100) + ',50,100)';
            ctx.fillRect(x, canvas.height - scaledHeight, barW, scaledHeight);
            x += barW + 1;
        }
    }

    // ==========================================
    // VARIABEL RIWAYAT
    // ==========================================
    let riwayatId = null;
    let playStartTime = null;
    let durationInterval = null;
    let isRecording = false;

    // ==========================================
    // FUNGSI RIWAYAT
    // ==========================================
    function catatRiwayat() {
        if (isRecording) return;
        
        fetch('{{ route("pendengar.riwayat.play") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                stasiun_radio_id: {{ $radio->id }}
            })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                riwayatId = data.data.id;
                playStartTime = Date.now();
                isRecording = true;
                console.log('✅ Riwayat dicatat:', data);
            } else {
                console.warn('⚠️', data.message);
            }
        })
        .catch(function(error) {
            console.error('❌ Error:', error);
        });
    }

    function updateDurasi() {
        if (!riwayatId || !playStartTime) return;

        var durasi = Math.floor((Date.now() - playStartTime) / 1000);
        
        fetch('{{ url("/pendengar/riwayat/stop") }}/' + riwayatId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                durasi: durasi
            })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                console.log('⏱️ Durasi: ' + durasi + ' detik');
            }
        })
        .catch(function(error) {
            console.error('❌ Error update durasi:', error);
        });
    }

    function stopRecording() {
        if (isRecording) {
            updateDurasi();
            isRecording = false;
            if (durationInterval) {
                clearInterval(durationInterval);
                durationInterval = null;
            }
        }
    }

    // ==========================================
    // KONTROL
    // ==========================================
    player.volume = 0.7;
    volUp.onclick = function() {
        player.volume = Math.min(player.volume + 0.1, 1);
    };
    volDown.onclick = function() {
        player.volume = Math.max(player.volume - 0.1, 0);
    };

    stopBtn.onclick = function() {
        player.pause();
        stopRecording();
        player.currentTime = 0;
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    };

    muteBtn.onclick = function() {
        player.muted = !player.muted;
        muteBtn.innerHTML = player.muted
            ? '<i class="fas fa-volume-up"></i>'
            : '<i class="fas fa-volume-mute"></i>';
    };

    playPauseBtn.onclick = function() {
        if (player.paused) {
            player.play();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            player.pause();
            playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    };

    // ==========================================
    // EVENT PLAYER
    // ==========================================
    player.addEventListener('play', function() {
        setTimeout(function() {
            catatRiwayat();
            if (durationInterval) {
                clearInterval(durationInterval);
            }
            durationInterval = setInterval(updateDurasi, 30000);
        }, 1000);
        
        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        
        draw();
    });

    player.addEventListener('pause', function() {
        stopRecording();
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    });

    player.addEventListener('ended', function() {
        stopRecording();
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    });

    player.addEventListener('error', function() {
        stopRecording();
    });

    window.addEventListener('beforeunload', function() {
        stopRecording();
    });

    // ==========================================
    // COMMENT SECTION
    // ==========================================
    const addCommentBtn = document.getElementById('add-comment-btn');
    const commentFormContainer = document.getElementById('comment-form-container');
    const cancelCommentBtn = document.getElementById('cancel-comment-btn');

    if (addCommentBtn && commentFormContainer) {
        addCommentBtn.addEventListener('click', function () {
            commentFormContainer.style.display = 'block';
            addCommentBtn.style.display = 'none';
            document.getElementById('commentText').focus();
        });

        cancelCommentBtn.addEventListener('click', function () {
            commentFormContainer.style.display = 'none';
            addCommentBtn.style.display = 'block';
            document.getElementById('parent_id').value = '';
            document.getElementById('commentText').value = '';
        });
    }

    document.querySelectorAll('.toggle-replies').forEach(function(toggle) {
        toggle.addEventListener('click', function () {
            var commentId = this.dataset.commentId;
            var repliesContainer = document.getElementById('replies-' + commentId);
            var icon = this.querySelector('.icon');

            if (repliesContainer.style.display === 'none') {
                repliesContainer.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                repliesContainer.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    document.querySelectorAll('.reply-btn').forEach(function(btn) {
        btn.addEventListener('click', function () {
            document.getElementById('parent_id').value = this.dataset.commentId;
            document.getElementById('commentText').value = '@' + this.dataset.username + ' ';
            commentFormContainer.style.display = 'block';
            addCommentBtn.style.display = 'none';
            document.getElementById('commentText').focus();

            document.getElementById('comment-form-container').scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    document.querySelectorAll('.edit-comment').forEach(function(btn) {
        btn.addEventListener('click', function () {
            var commentId = this.dataset.commentId;
            var form = document.getElementById('editCommentForm');
            var editContainer = document.getElementById('edit-comment-container');

            form.action = '/comments/' + commentId;
            document.getElementById('editCommentText').value = this.dataset.content;

            editContainer.style.display = 'block';
            commentFormContainer.style.display = 'none';
            addCommentBtn.style.display = 'none';

            editContainer.scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    document.querySelector('.cancel-edit').addEventListener('click', function () {
        document.getElementById('edit-comment-container').style.display = 'none';
        document.getElementById('comment-form-container').style.display = 'none';
        document.getElementById('add-comment-btn').style.display = 'block';
    });

    var textareas = ['commentText', 'editCommentText'];
    textareas.forEach(function(id) {
        var textarea = document.getElementById(id);
        if (textarea) {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    });

    @if(session('success') && session('scroll_to'))
        var scrollTo = document.getElementById('{{ session('scroll_to') }}');
        if (scrollTo) {
            scrollTo.scrollIntoView({
                behavior: 'smooth'
            });
        }
    @endif

    // Hapus atau timpa error dari kaiadmin.min.js
// Cegah akses ke audioSpectrum
if (typeof document.getElementById('audioSpectrum') === 'undefined') {
    // Buat canvas dummy
    var dummyCanvas = document.createElement('canvas');
    dummyCanvas.id = 'audioSpectrum';
    dummyCanvas.style.display = 'none';
    document.body.appendChild(dummyCanvas);
}
});
</script>
@endpush

@push('styles')
    <style>
        :root {
            --primary: #024950;
            --primary-soft: rgba(2, 73, 80, 0.1);
            --secondary: #6c757d;
            --secondary-soft: rgba(108, 117, 125, 0.1);
            --success: #024950;
        }

        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #01363b;
            border-color: #01363b;
        }

        .btn-success {
            background-color: var(--success) !important;
            border-color: var(--success) !important;
        }

        .btn-success:hover {
            background-color: #964734 !important;
            border-color: #964734 !important;
        }

        .bg-primary-soft {
            background-color: var(--primary-soft);
        }

        .bg-secondary-soft {
            background-color: var(--secondary-soft);
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .text-secondary {
            color: var(--secondary) !important;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .custom-audio-player {
            height: 40px;
        }

        .custom-audio-player::-webkit-media-controls-panel {
            background-color: white;
            border-radius: 8px;
        }

        .custom-audio-player::-webkit-media-controls-play-button,
        .custom-audio-player::-webkit-media-controls-mute-button {
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
        }

        .radio-item {
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 8px;
        }

        .radio-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            cursor: pointer;
        }

        #comments-section {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        #comment-form-container,
        #edit-comment-container {
            background-color: rgba(248, 249, 250, 0.8);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .comments-list {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .comments-list::-webkit-scrollbar {
            width: 6px;
        }

        .comments-list::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .comments-list::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .comments-list::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.2);
        }

        .rounded-pill {
            border-radius: 50px !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 0.75rem;
        }

        .comment-card {
            margin-left: 0 !important;
            margin-right: 0 !important;
            border-radius: 12px;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 15px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 12px 15px;
        }

        .comment-card:hover {
            background-color: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .comment-content {
            text-align: left;
            margin: 4px 0 8px 0;
            white-space: pre-wrap;
            word-break: break-word;
            line-height: 1.6;
            padding: 0;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            margin-bottom: 4px;
        }

        .comment-meta {
            text-align: left;
            margin-bottom: 8px;
            gap: 2px;
        }

        .comment-time {
            display: block;
            margin-top: 4px;
            font-size: 0.8rem;
            margin-top: 0;
        }

        .comment-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
        }
        
        .bg-success-soft {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            .radio-item {
                padding: 0.5rem;
            }
        }
    </style>
@endpush
