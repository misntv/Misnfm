<?php
    use Carbon\Carbon;
    $user = auth()->user();
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
            return '1 Bulan Terakhir';
        } else {
            return $created->translatedFormat('F Y');
        }
    });
?>

<nav class="navbar navbar-header navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
        <button class="btn d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav ms-auto align-items-center position-relative" style="gap: 16px;">
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" role="button" id="notifDropdown">
                    <i class="fas fa-bell fs-5 text-dark"></i>
                    <?php if($unreadCount): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                            <?php echo e($unreadCount); ?>

                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    <?php endif; ?>
                </a>

                <div class="dropdown-menu dropdown-menu-end p-0 shadow notif-dropdown-wrapper" aria-labelledby="notifDropdown" style="width: 320px;">
                    <div class="notif-header d-flex justify-content-between align-items-center px-3 pt-3 pb-2">
                        <strong class="d-block">Notifikasi</strong>
                        <?php if($unreadCount): ?>
                            <span class="badge bg-primary rounded-pill"><?php echo e($unreadCount); ?> Baru</span>
                        <?php endif; ?>
                    </div>
                    <hr class="my-0">

                    <div class="notif-scrollable" style="max-height: 400px; overflow-y: auto;">
                        <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="text-muted small px-3 py-2 fw-bold notif-group-heading"><?php echo e($group); ?></div>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('pendengar.notifications.read', $notif->id)); ?>"
                                   class="dropdown-item notif-item <?php echo e(is_null($notif->read_at) ? 'unread-notif' : ''); ?>"
                                   style="white-space: normal;">
                                    <div class="d-flex align-items-start">
                                        <i class="<?php echo e($notif->data['icon'] ?? 'fas fa-bell'); ?> me-3 mt-1 text-primary"></i>
                                        <div>
                                            <strong class="notif-title"><?php echo e($notif->data['title'] ?? 'Notifikasi'); ?></strong>
                                            <p class="text-muted mb-0 notif-body"><?php echo e(\Illuminate\Support\Str::limit($notif->data['body'] ?? '-', 70)); ?></p>
                                            <div class="small text-muted text-end notif-time"><?php echo e($notif->created_at->diffForHumans()); ?></div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-muted py-4">Tidak ada notifikasi</div>
                        <?php endif; ?>
                    </div>

                    <div class="notif-footer px-3 py-2 d-flex justify-content-between align-items-center">
                        <a href="#" id="markAllNotifRead" class="text-success small fw-bold">✓ Tandai telah dibaca</a>
                        <a href="#" id="clearNotifList" class="text-danger small fw-bold">🗑️ Hapus semua</a>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown profile-icon order-2">
                <a class="nav-link dropdown-toggle no-caret profile-pic" data-bs-toggle="dropdown" href="#">
                    <img src="<?php echo e($user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/profile.jpg')); ?>"
                         alt="Foto Admin"
                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" />
                </a>
                <ul class="dropdown-menu dropdown-user dropdown-menu-end animated fadeIn">
                    <li>
                        <div class="user-box d-flex align-items-center gap-3 px-3 py-2">
                            <img src="<?php echo e($user->url_foto_profil ? asset('storage/' . $user->url_foto_profil) : asset('assets/img/profile.jpg')); ?>"
                                 alt="Foto Profil"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" />
                            <div class="u-text">
                                <h4 class="mb-0"><?php echo e($user->nama_lengkap); ?></h4>
                                <p class="text-muted mb-0"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo e(route('admin.profile.show')); ?>">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?></form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const markAllBtn = document.getElementById('markAllNotifRead');
        const deleteAllBtn = document.getElementById('clearNotifList');

        if (markAllBtn) {
            markAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                fetch("<?php echo e(route('admin.notifications.read.all')); ?>", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    }
                }).then(res => location.reload());
            });
        }

        if (deleteAllBtn) {
            deleteAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus semua notifikasi?')) {
                    fetch("<?php echo e(route('admin.notifications.delete.all')); ?>", {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        }
                    }).then(res => location.reload());
                }
            });
        }
    });
</script>

<style>
    /* =============================== */
    /* NOTIFIKASI DROPDOWN */
    /* =============================== */
    .notif-dropdown-wrapper {
        position: absolute !important;
        top: 100%;
        right: 0;
        margin-top: 8px;
        width: 320px;
        background: #fff;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1); /* Softer shadow */
        border-radius: 12px; /* More rounded corners */
        z-index: 9999;
        overflow: hidden;
    }

    .notif-header {
        background-color: #f8f9fa; /* Light background for header */
        border-bottom: 1px solid #eee; /* Subtle bottom border */
    }

    .notif-scrollable {
        max-height: 360px;
        overflow-y: auto;
        padding: 0; /* Remove padding from here, add to items */
    }

    .notif-group-heading {
        background-color: #f0f2f5; /* Slightly darker background for group headings */
        padding: 8px 15px; /* Add padding */
        margin-top: 0; /* Remove top margin */
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
    }

    .notif-item {
        padding: 12px 15px; /* Comfortable padding */
        border-bottom: 1px solid #f2f2f2; /* Light separator */
        transition: background-color 0.2s ease;
        display: flex;
        align-items: flex-start;
        text-decoration: none; /* Remove underline */
        color: inherit; /* Inherit text color */
    }

    .notif-item:last-child {
        border-bottom: none; /* No border for the last item */
    }

    .notif-item:hover {
        background-color: #e9ecef; /* Subtle hover effect */
    }

    .unread-notif {
        background-color: #eef7ff; /* Light blue for unread */
        border-left: 4px solid #007bff; /* Primary color border for unread */
        font-weight: 500; /* Slightly bolder for unread */
    }

    .unread-notif .notif-title {
        color: #333;
    }

    .notif-title {
        font-size: 0.95rem;
        margin-bottom: 2px;
        color: #212529;
    }

    .notif-body {
        font-size: 0.85rem;
        line-height: 1.4;
        color: #6c757d;
    }

    .notif-time {
        font-size: 0.75rem;
        color: #999;
        margin-top: 5px;
    }

    .notif-footer {
        border-top: 1px solid #eee;
        padding: 10px 15px; /* Adjust padding */
        background-color: #f8f9fa;
        position: sticky;
        bottom: 0;
        display: flex;
        justify-content: space-between;
        z-index: 5;
    }

    .notif-footer a {
        text-decoration: none;
        padding: 5px 8px;
        border-radius: 5px;
        transition: background-color 0.2s ease;
    }

    .notif-footer a.text-success:hover {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .notif-footer a.text-danger:hover {
        background-color: rgba(220, 53, 69, 0.1);
    }

    /* Badge Notif (kept similar as it works well for indication) */
    .notif-count-badge {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(30%, -30%);
        background-color: red;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        min-width: 20px;
        height: 20px;
        line-height: 20px;
        text-align: center;
        font-weight: bold;
        padding: 0 5px;
        z-index: 10;
    }
</style><?php /**PATH /home/misntvbi/laravel_misnfm/resources/views/admin/layouts/navbar.blade.php ENDPATH**/ ?>