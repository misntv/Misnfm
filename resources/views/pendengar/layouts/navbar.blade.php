@php
    use Carbon\Carbon;
    
    $user = auth()->user();
    $unreadCount = 0;
    $notifs = collect();
    $grouped = collect();

    if ($user) {
        $unreadCount = $user->unreadNotifications->count();
        $notifs = $user->notifications->take(99);
        $grouped = $notifs->groupBy(function ($notif) {
            $created = Carbon::parse($notif->created_at);
            $now = Carbon::now();
    
            if ($created->greaterThanOrEqualTo($now->copy()->startOfDay())) {
                return 'Hari Ini';
            } elseif ($created->greaterThanOrEqualTo($now->copy()->subDay()->startOfDay())) {
                return 'Kemarin';
            } elseif ($created->greaterThanOrEqualTo($now->copy()->subDays(7)->startOfDay())) {
                return '7 Hari Terakhir';
            } elseif ($created->greaterThanOrEqualTo($now->copy()->subDays(30)->startOfDay())) {
                return '30 Hari Terakhir';
            } else {
                return 'Lebih dari 30 Hari';
            }
        });
    }
@endphp

<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <nav class="navbar navbar-header navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-auto align-items-center">
                @if(auth()->check())
                    {{-- Notifikasi --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" role="button" id="notifDropdown">
                            <i class="fas fa-bell fs-5 text-dark"></i>
                            @if($unreadCount)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                                    {{ $unreadCount }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-end p-0 shadow notif-dropdown-wrapper" aria-labelledby="notifDropdown" style="width: 320px;">
                            <div class="notif-scrollable" style="max-height: 400px; overflow-y: auto;">
                                <div class="mb-2 px-3 pt-3">
                                    <strong class="d-block">Notifikasi</strong>
                                </div>
                                <hr class="my-1">

                                @forelse($grouped as $group => $items)
                                    <div class="text-muted small px-3 py-1 fw-bold">{{ $group }}</div>
                                    @foreach($items as $notif)
                                        <a href="{{ route('pendengar.notifications.read', $notif->id) }}"
                                           class="dropdown-item {{ is_null($notif->read_at) ? 'bg-light border-start border-primary border-3' : '' }} notification-item mb-1 rounded"
                                           style="white-space: normal;">
                                            <i class="{{ $notif->data['icon'] ?? 'fas fa-bell' }} me-2 text-primary"></i>
                                            <strong>{{ $notif->data['title'] ?? 'Notifikasi' }}</strong><br>
                                            <span class="text-muted small">{{ \Illuminate\Support\Str::limit($notif->data['body'] ?? '-', 70) }}</span>
                                            <div class="small text-muted text-end">{{ $notif->created_at->diffForHumans() }}</div>
                                        </a>
                                    @endforeach
                                @empty
                                    <div class="text-center text-muted py-3">Tidak ada notifikasi</div>
                                @endforelse
                            </div>

                            <div class="notif-footer">
                                <a href="#" id="markAllNotifRead" class="text-success small">✓ Tandai telah dibaca</a>
                                <a href="#" id="clearNotifList" class="text-danger small">🗑️ Hapus semua</a>
                            </div>
                        </div>
                    </li>

                    {{-- PROFIL DROPDOWN --}}
                    <li class="nav-item topbar-user dropdown hidden-caret">
                        <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                            <img src="{{ $user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/default-profile.png') }}"
                                 alt="Foto Profil"
                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" />
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box d-flex align-items-center gap-3 px-3 py-2">
                                        <img src="{{ $user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/default-profile.png') }}"
                                             alt="Foto Profil"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" />
                                        <div class="u-text">
                                            <h4 class="mb-0">{{ $user->nama_lengkap }}</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.85rem;">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('pendengar.profile.show') }}">
                                        <i class="fas fa-user me-2"></i> My Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </div>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    {{-- SCRIPT NOTIFIKASI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const markAllBtn = document.getElementById('markAllNotifRead');
            const clearAllBtn = document.getElementById('clearNotifList');

            if (markAllBtn) {
                markAllBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    fetch("{{ route('pendengar.notifications.readAll') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) location.reload();
                        else alert("Gagal menandai notifikasi.");
                    });
                });
            }

            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!confirm("Yakin ingin menghapus semua notifikasi?")) return;

                    fetch("{{ route('pendengar.notifications.deleteAll') }}", {
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) location.reload();
                        else alert("Gagal menghapus notifikasi.");
                    });
                });
            }
        });
    </script>
</div>
