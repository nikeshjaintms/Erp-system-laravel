<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | APEX ERP System</title>

    <!-- Local Vendor Stylesheets (Offline & Local) -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">

    <!-- Custom Bootstrap ERP Admin Theme CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/admin-theme.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Navbar -->
            @include('layouts.partials.navbar')

            <!-- Optional Page Header with Breadcrumbs & Action Toolbar -->
            @hasSection('header')
                @yield('header')
            @else
                <div class="page-header-wrapper d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h1 class="page-title">@yield('page_title', 'Dashboard Overview')</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-nav">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a></li>
                                @yield('breadcrumbs')
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @yield('actions')
                    </div>
                </div>
            @endif

            <!-- Page Content Body -->
            <main class="page-content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Global Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Local Vendor Scripts (Offline & Local) -->
    <script src="{{ asset('admin-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/chartjs/chart.umd.min.js') }}"></script>

    <!-- Custom Bootstrap Admin Theme JS -->
    <script src="{{ asset('admin-assets/js/admin-theme.js') }}"></script>

    @stack('scripts')
</body>
</html>
