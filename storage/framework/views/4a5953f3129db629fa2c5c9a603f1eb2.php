<div class="sidebar">
    <div class="sidebar-header text-center">
        <h1 class="admin-text">Admin MISNFM</h1>
    </div>
    <div class="sidebar-menu">
        <a href="<?php echo e(route('admin.dashboard.admin')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard.admin') ? 'active' : ''); ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="<?php echo e(route('admin.verifikasi.wartawan')); ?>" class="<?php echo e(request()->routeIs('admin.verifikasi.wartawan') ? 'active' : ''); ?>">
            <i class="fas fa-users"></i> Data Wartawan
        </a>
        <a href="<?php echo e(route('admin.berita.verifikasi')); ?>" class="<?php echo e(request()->routeIs('admin.berita.verifikasi') ? 'active' : ''); ?>">
            <i class="fas fa-users"></i> Verifikasi Berita

            <?php if(!empty($countBeritaPengajuan)): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small-badge">
                    <?php echo e($countBeritaPengajuan); ?>

                </span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('playlist.create')); ?>" class="<?php echo e(request()->routeIs('playlist.create') ? 'active' : ''); ?>">
            <i class="fas fa-music"></i> Kelola Playlist
        </a>
        <a href="<?php echo e(route('playlist.index')); ?>" class="<?php echo e(request()->routeIs('playlist.index') ? 'active' : ''); ?>">
            <i class="fas fa-list"></i> Daftar Playlist
        </a>
        <a href="<?php echo e(route('playlist.archived')); ?>" class="<?php echo e(request()->routeIs('playlist.archived') ? 'active' : ''); ?>">
            <i class="fas fa-archive"></i> Playlist Diarsipkan
        </a>
        <a href="<?php echo e(route('playlist.statistics')); ?>" class="<?php echo e(request()->routeIs('playlist.statistics') ? 'active' : ''); ?>">
            <i class="fas fa-chart-bar"></i> Statistik Pemutaran
        </a>
    </div>
    <div class="sidebar-footer text-center mt-auto">
        <a href="<?php echo e(route('pendengar.radio.index')); ?>" title="Halaman Radio">
            <img src="<?php echo e(asset('assets/img/logo_misnfm_switch.png')); ?>" alt="Radio MisnFM" class="logo-footer">
        </a>
    </div>
</div><?php /**PATH /home/misntvbi/laravel_misnfm/resources/views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>