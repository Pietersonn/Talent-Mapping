<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIC Dashboard - TalentMapping')</title>
    
    <link rel="icon" href="{{ asset('assets/public/images/logo-tm.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/pic/css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">


    @stack('styles')
</head>

<body>
    @php
        $isDashActive = request()->routeIs('pic.dashboard');
        $isEventsActive = request()->routeIs('pic.events.*');
        $isPartsActive = request()->routeIs('pic.participants.*');
    @endphp

    <!-- SIDEBAR -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-2">
            <div class="sidebar-brand d-flex align-items-center gap-2">
                <i class="bi bi-person-badge fs-5"></i>
                <span class="fw-semibold">PIC Panel</span>
            </div>
            <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <ul class="sidebar-nav list-unstyled px-2 mb-0">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 {{ $isDashActive ? 'active' : '' }}"
                    href="{{ route('pic.dashboard') }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 {{ $isEventsActive ? 'active' : '' }}"
                    href="{{ route('pic.events.index') }}">
                    <i class="bi bi-calendar-event"></i><span>My Events</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 {{ $isPartsActive ? 'active' : '' }}"
                    href="{{ route('pic.participants.index') }}">
                    <i class="bi bi-people"></i><span>Participants</span>
                </a>
            </li>
            {{-- Tidak ada menu Results / Reports --}}
        </ul>

        <div class="sidebar-footer mt-auto p-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-person-circle fs-4"></i>
                <div>
                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-muted small">PIC</div>
                </div>
            </div>

            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm w-100"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" class="d-none" action="{{ route('logout') }}" method="POST">@csrf</form>
        </div>
    </nav>

    <!-- MAIN -->
    <div class="main-content">
        <!-- TOPBAR -->
        <nav class="topbar navbar navbar-expand navbar-light bg-white border-bottom px-3">
            <button class="btn btn-link d-lg-none" id="sidebarToggleTop" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="ms-auto navbar-nav">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> <span>{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('home') }}">
                                <i class="bi bi-house"></i><span>Home</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENT -->
        <div class="content-wrapper p-3 p-lg-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/pic/js/dashboard.js') }}"></script>
    @stack('scripts')
</body>

</html>
