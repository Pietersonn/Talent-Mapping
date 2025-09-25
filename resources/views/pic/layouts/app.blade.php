<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIC Dashboard - TalentMapping')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/pic/css/dashboard.css') }}">
    @stack('styles')
</head>
<body>
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="bi bi-person-badge"></i>
                <span>PIC Panel</span>
            </div>
            <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        @php $reportsActive = request()->routeIs('pic.reports.*'); @endphp

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('pic.dashboard') }}" class="nav-link {{ request()->routeIs('pic.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pic.events.index') }}" class="nav-link {{ request()->routeIs('pic.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i> <span>My Events</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pic.participants.index') }}" class="nav-link {{ request()->routeIs('pic.participants.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>Participants</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pic.results.index') }}" class="nav-link {{ request()->routeIs('pic.results.*') ? 'active' : '' }}">
                    <i class="bi bi-file-text"></i> <span>Results</span>
                </a>
            </li>

            <!-- Reports dropdown -->
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $reportsActive ? 'active' : '' }}"
                   data-bs-toggle="collapse" href="#menuReports" role="button"
                   aria-expanded="{{ $reportsActive ? 'true' : 'false' }}" aria-controls="menuReports">
                    <span><i class="bi bi-graph-up"></i> <span class="ms-1">Reports</span></span>
                    <i class="bi {{ $reportsActive ? 'bi-chevron-up' : 'bi-chevron-down' }}"></i>
                </a>
                <div class="collapse {{ $reportsActive ? 'show' : '' }}" id="menuReports">
                    <ul class="list-unstyled ms-4 my-1">
                        <li class="mb-1">
                            <a href="{{ route('pic.reports.participants') }}"
                               class="nav-link {{ request()->routeIs('pic.reports.participants') ? 'active' : '' }}">
                                <i class="bi bi-people me-1"></i> Participants
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('pic.reports.top') }}"
                               class="nav-link {{ request()->routeIs('pic.reports.top') ? 'active' : '' }}">
                                <i class="bi bi-trophy me-1"></i> Top (Top 10)
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar"><i class="bi bi-person-circle"></i></div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">PIC</div>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </nav>

    <div class="main-content">
        <nav class="topbar navbar navbar-expand navbar-light">
            <button class="btn btn-link sidebar-toggle d-lg-none" id="sidebarToggleTop">
                <i class="bi bi-list"></i>
            </button>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
