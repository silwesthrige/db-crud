<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    @yield('header_content')
</head>
<body>
    <div class="admin-wrapper">
        @include('templates.includes.topbar')
        <div class="admin-container">
            @include('templates.includes.sidebar')
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <main class="admin-main">
                <div class="content-wrapper">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @yield('content')
                </div>
                
                <!-- Simple Footer -->
                <footer class="simple-footer">
                    <div class="simple-footer-content">
                        <span>&copy; {{ date('Y') }} Event Manager. All rights reserved.</span>
                    </div>
                </footer>
            </main>
        </div>
    </div>
    <script src="{{ asset('assets/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin.js') }}"></script>
    <script src="{{ asset('assets/theme.js') }}"></script>
    @stack('scripts')
    @yield('optional_scripts')
</body>
</html>
