<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <img src="{{ asset('assets/public/images/logo-bcti.png') }}" alt="BCTI Logo" class="logo">
        </div>

        <div class="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    @auth
                        <a href="{{ route('test.form') }}"
                            class="nav-link {{ request()->routeIs('test.*') ? 'active' : '' }}">
                            Test
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">
                            Tes
                        </a>
                    @endauth
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        Contact
                    </a>
                </li>
            </ul>

            <div class="navbar-actions">
                @auth
                    <div class="user-menu">
                        <button class="btn-user" onclick="toggleUserDropdown()">
                            <i class="icon-user"></i>
                            {{ Auth::user()->name }}
                            <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <!-- Role-based dashboard access -->
                            @if (in_array(Auth::user()->role, ['admin', 'staff']))
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                    <i class="icon-dashboard"></i> Admin Dashboard
                                </a>
                            @elseif(Auth::user()->role === 'pic')
                                <a href="{{ route('pic.dashboard') }}" class="dropdown-item">
                                    <i class="icon-dashboard"></i> PIC Dashboard
                                </a>
                            @endif

                            <!-- Common menu items -->
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="icon-user"></i> Profile
                            </a>

                            @if (Auth::user()->role === 'user')
                                <a href="{{ route('user.profile') }}" class="dropdown-item">
                                    <i class="icon-test"></i> My Tests
                                </a>
                            @endif

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="icon-logout"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>
