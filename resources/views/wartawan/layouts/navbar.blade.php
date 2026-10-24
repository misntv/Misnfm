@php
    use Carbon\Carbon;

    $user = auth()->user();
    $unreadCount = $user->unreadNotifications->count();
    $notifs = $user->notifications->take(99);

    $now = Carbon::now()->setTimezone('Asia/Jakarta');

    $grouped = $notifs->groupBy(function ($notif) use ($now) {
        $created = Carbon::parse($notif->created_at)->setTimezone('Asia/Jakarta');

        if ($created->greaterThanOrEqualTo($now->copy()->startOfDay())) {
            return 'Hari Ini';
        } elseif ($created->greaterThanOrEqualTo($now->copy()->subDay()->startOfDay())) {
            return 'Kemarin';
        } elseif ($created->greaterThanOrEqualTo($now->copy()->subDays(7)->startOfDay())) {
            return '7 Hari Terakhir';
        } elseif ($created->greaterThanOrEqualTo($now->copy()->subDays(30)->startOfDay())) {
            return '1 Bulan Terakhir';
        } else {
            return $created->translatedFormat('F Y');
        }
    });
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-2 px-3 sticky-top shadow-sm">
    <div class="container-fluid">
        {{-- Sidebar Toggle (Mobile Only) --}}
        <button id="toggleSidebar" class="btn btn-outline-secondary d-lg-none me-2">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Right Side --}}
        <div class="d-flex ms-auto align-items-center gap-3">
            {{-- Notifikasi --}}
            <div class="nav-item dropdown">
                <a class="nav-link position-relative p-0" data-bs-toggle="dropdown" href="#" role="button" id="notifDropdown">
                    <i class="fas fa-bell fs-5"></i>
                    @if($unreadCount)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-end p-0 shadow notif-dropdown-wrapper" aria-labelledby="notifDropdown" style="width: 340px;">
                    <div class="notif-header d-flex justify-content-between align-items-center px-3 pt-3 pb-2">
                        <strong class="d-block">Notifikasi</strong>
                        @if($unreadCount)
                            <span class="badge bg-primary rounded-pill">{{ $unreadCount }} Baru</span>
                        @endif
                    </div>
                    <hr class="my-0">

                    <div class="notif-scrollable">
                        @forelse($grouped as $group => $items)
                            <div class="text-muted small px-3 py-2 fw-bold notif-group-heading">{{ $group }}</div>
                            @foreach($items as $notif)
                                <a href="{{ route('wartawan.notifications.read', $notif->id) }}"
                                   class="dropdown-item notif-item {{ is_null($notif->read_at) ? 'unread-notif' : '' }}"
                                   style="white-space: normal;">
                                    <div class="d-flex align-items-start">
                                        <i class="{{ $notif->data['icon'] ?? 'fas fa-bell' }} me-3 mt-1 text-primary"></i>
                                        <div>
                                            <strong class="notif-title">{{ $notif->data['title'] ?? 'Notifikasi' }}</strong>
                                            <p class="text-muted mb-0 notif-body">{{ \Illuminate\Support\Str::limit($notif->data['body'] ?? '-', 70) }}</p>
                                            <div class="small text-muted text-end notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @empty
                            <div class="text-center text-muted py-4">Tidak ada notifikasi</div>
                        @endforelse
                    </div>

                    <div class="notif-footer px-3 py-2 d-flex justify-content-between align-items-center border-top">
                        <a href="#" id="markAllNotifRead" class="text-success small fw-bold">✓ Tandai telah dibaca</a>
                        <a href="#" id="clearNotifList" class="text-danger small fw-bold">🗑️ Hapus semua</a>
                    </div>
                </div>
            </div>

            {{-- Profile --}}
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle p-0 d-flex align-items-center" data-bs-toggle="dropdown" href="#">
                    <img src="{{ $user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/profile.jpg') }}"
                         alt="Foto Profil"
                         style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%;" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li class="px-3 py-2">
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/profile.jpg') }}"
                                 alt="Foto Profil"
                                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" />
                            <div class="ms-2">
                                <strong>{{ $user->nama_lengkap }}</strong><br>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('wartawan.profile.show') }}">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const markAllBtn = document.getElementById('markAllNotifRead');
        const deleteAllBtn = document.getElementById('clearNotifList');

        if (markAllBtn) {
            markAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                fetch("{{ route('wartawan.notifications.readAll') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            });
        }

        if (deleteAllBtn) {
            deleteAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus semua notifikasi?')) {
                    fetch("{{ route('wartawan.notifications.deleteAll') }}", {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => location.reload());
                }
            });
        }
    });
</script>
@endpush
