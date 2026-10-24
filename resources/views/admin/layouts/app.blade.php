<!-- File: resources/views/admin/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Radio-MisnFM - @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="{{ asset('public/assets/img/logo_misnfm.png') }}" type="image/x-icon">

    <!-- Fonts and icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        .wrapper {
            display: flex;
            flex-direction: row;
        }

        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        @media (max-width: 991.98px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="wrapper">
        @include('admin.layouts.sidebar')

        <div class="main-panel">
            @include('admin.layouts.navbar')

            <div class="main-content container mt-4">
                @yield('content')
            </div>

            @include('admin.layouts.footer')
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleButton = document.getElementById('sidebarToggle');

            if (toggleButton && sidebar && backdrop) {
                toggleButton.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>
    @yield('scripts')
</body>

</html>