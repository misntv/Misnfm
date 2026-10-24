<div class="sidebar">
    @auth
        @php
            $role = Auth::user()->role ?? 'guest';
            $roleText = match ($role) {
                'admin_radio' => 'Admin MISNFM',
                'wartawan' => 'Wartawan MISNFM',
                default => 'Pendengar MISNFM',
            };
        @endphp
    @endauth

    <div class="sidebar-header text-center">
        <h1 class="pendengar-text">{{ $roleText ?? 'Pendengar MISNFM' }}</h1>
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('pendengar.radio.index') }}" class="{{ request()->routeIs('pendengar.radio.index') ? 'active' : '' }}">
            <i class="fas fa-broadcast-tower"></i> Radio
        </a>
        <a href="{{ route('pendengar.berita.index') }}" class="{{ request()->routeIs('pendengar.berita.index') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> Berita
        </a>
    </div>

    @auth
        @if ($role === 'admin_radio')
            <div class="sidebar-footer text-center mt-auto">
                <a href="{{ route('admin.dashboard.admin') }}" title="Kembali ke Admin">
                    <img src="{{ asset('assets/img/logo_misnfm_switch.png') }}" alt="Kembali ke Admin" class="logo-footer">
                </a>
            </div>
        @elseif ($role === 'wartawan')
            <div class="sidebar-footer text-center mt-auto">
                <a href="{{ route('wartawan.dashboard.wartawan') }}" title="Kembali ke Wartawan">
                    <img src="{{ asset('assets/img/logo_misnfm_switch.png') }}" alt="Kembali ke Wartawan" class="logo-footer">
                </a>
            </div>
        @endif
    @endauth
</div>