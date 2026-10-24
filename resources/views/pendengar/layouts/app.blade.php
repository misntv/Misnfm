<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Radio-MisnFM - Pendengar Dashboard')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('public/assets/img/logo_misnfm.png') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/pendengar.css') }}">

    <!-- Di dalam <head>, sebelum @stack('styles') -->
    <style>
        /* Sticky Footer hanya untuk halaman dengan class .page-content */
        .page-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .page-content .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .page-content .content {
            flex: 1 0 auto;
        }
        .page-content .footer {
            flex-shrink: 0;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        @include('pendengar.layouts.sidebar')

        <div class="main-panel">
            @include('pendengar.layouts.navbar')

            <div class="content">
                @yield('content')
            </div>

            @include('pendengar.layouts.footer')
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function () {
            const sidebar = $('.sidebar');
            const mainPanel = $('.main-panel');
            const sidebarToggle = $('.toggle-sidebar');
            const sidenavToggler = $('.sidenav-toggler');

            // Toggle sidebar collapse/expand
            sidebarToggle.click(function () {
                sidebar.toggleClass('collapsed');
                mainPanel.toggleClass('expanded');

                // Store state in localStorage
                const isCollapsed = sidebar.hasClass('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Check for saved state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.addClass('collapsed');
                mainPanel.addClass('expanded');
            }

            // Mobile toggle (for smaller screens)
            sidenavToggler.click(function () {
                $('body').toggleClass('sidenav-toggled');
                sidebar.toggleClass('show');
            });

            // Auto-close on mobile when clicking a link
            $('.sidebar-menu a').click(function () {
                if ($(window).width() < 992) {
                    sidebar.removeClass('show');
                    $('body').removeClass('sidenav-toggled');
                }
            });
        });

        // Handle window resize
        $(window).resize(function () {
            if ($(window).width() >= 992) {
                $('.sidebar').removeClass('show');
                $('body').removeClass('sidenav-toggled');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>