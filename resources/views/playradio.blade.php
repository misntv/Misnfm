<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Radio Osing</title>
    <link rel="stylesheet" href="{{ asset('css/playradio.css') }}">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Radio-MisnFM</h2>
        <nav>
            <a href="#">Radio</a>
            <a href="#">Berita</a>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        <header class="top-bar">
            <div class="navbar">
                <div class="search-container">
                    <input type="text" placeholder="Search.." />
                </div>
                <div class="nav-icons">
                    <div class="icon notif-icon">🔔</div>
                    <div class="icon user-icon">👤</div>
                </div>
            </div>
        </header>


        @foreach ($radioStations as $station)
            <section class="player-card">
                <h1>{{ $station->nama_stasiun }}</h1>
                <img src="{{ $station->thumbnail_url }}" alt="Thumbnail">
                <p>{{ $station->deskripsi }}</p>
                <audio controls>
                    <source src="{{ $station->url_stasiun }}" type="audio/mpeg">
                </audio>
            </section>
        @endforeach

        <div class="pagination">
            <p>Halaman {{ $page }} dari {{ $total_pages }} (Total: {{ $total }} stasiun)</p>
            <div>
                @if ($page > 1)
                    <a href="{{ url()->current() }}?page={{ $page - 1 }}">← Sebelumnya</a>
                @endif
                @if ($page < $total_pages)
                    <a href="{{ url()->current() }}?page={{ $page + 1 }}">Berikutnya →</a>
                @endif
            </div>
        </div>
    </main>
</div>
</body>
</html>
