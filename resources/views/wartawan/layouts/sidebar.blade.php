<div class="sidebar" id="sidebar">
    <div class="sidebar-header text-center">
        <h1 class="wartawan-text">Wartawan MISNFM</h1>
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('wartawan.dashboard.wartawan') }}" class="{{ request()->routeIs('wartawan.dashboard.wartawan') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('berita.create') }}" class="{{ request()->routeIs('berita.create') ? 'active' : '' }}">
            <i class="fas fa-paper-plane"></i> Kirim Berita
        </a>
        <a href="{{ route('berita.index') }}" class="{{ request()->routeIs('berita.index') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> Daftar Berita
        </a>
        <a href="{{ route('berita.archived') }}" class="{{ request()->routeIs('berita.archived') ? 'active' : '' }}">
            <i class="fas fa-archive"></i> Berita Diarsipkan
        </a>
    </div>
    <div class="sidebar-footer text-center mt-auto">
        <a href="{{ route('pendengar.radio.index') }}" title="Halaman Radio">
            <img src="{{ asset('assets/img/logo_misnfm_switch.png') }}" alt="Radio MisnFM" class="logo-footer">
        </a>
    </div>
</div>