<div class="sidebar">
    <div class="sidebar-header text-center">
        <h1 class="admin-text">Admin MISNFM</h1>
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('admin.dashboard.admin') }}" class="{{ request()->routeIs('admin.dashboard.admin') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('admin.verifikasi.wartawan') }}" class="{{ request()->routeIs('admin.verifikasi.wartawan') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Data Wartawan
        </a>
        <a href="{{ route('admin.berita.verifikasi') }}" class="{{ request()->routeIs('admin.berita.verifikasi') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Verifikasi Berita

            @if(!empty($countBeritaPengajuan))
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small-badge">
                    {{ $countBeritaPengajuan }}
                </span>
            @endif
        </a>

        <a href="{{ route('playlist.create') }}" class="{{ request()->routeIs('playlist.create') ? 'active' : '' }}">
            <i class="fas fa-music"></i> Kelola Playlist
        </a>
        <a href="{{ route('playlist.index') }}" class="{{ request()->routeIs('playlist.index') ? 'active' : '' }}">
            <i class="fas fa-list"></i> Daftar Playlist
        </a>
        <a href="{{ route('playlist.archived') }}" class="{{ request()->routeIs('playlist.archived') ? 'active' : '' }}">
            <i class="fas fa-archive"></i> Playlist Diarsipkan
        </a>
        <a href="{{ route('playlist.statistics') }}" class="{{ request()->routeIs('playlist.statistics') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Statistik Pemutaran
        </a>
    </div>
    <div class="sidebar-footer text-center mt-auto">
        <a href="{{ route('pendengar.radio.index') }}" title="Halaman Radio">
            <img src="{{ asset('assets/img/logo_misnfm_switch.png') }}" alt="Radio MisnFM" class="logo-footer">
        </a>
    </div>
</div>