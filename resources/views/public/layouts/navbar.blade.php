<nav class="navbar">
  <div class="container">
    <!-- Brand -->
    <div class="navbar-brand">
      <a href="{{ route('home') }}" aria-label="Go to Home">
        <img src="{{ asset('assets/public/images/logo-bcti1.png') }}" alt="BCTI Logo" class="logo">
      </a>
    </div>

    <!-- Mobile menu toggle -->
    <button class="mobile-menu-toggle" id="tmMobileToggle"
            aria-controls="tmNavbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
            onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </button>

    <!-- Main menu -->
    <div class="navbar-menu" id="tmNavbarMenu">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        </li>

        <li class="nav-item">
          @auth
            <a href="{{ route('test.form') }}" class="nav-link {{ request()->routeIs('test.*') ? 'active' : '' }}">Test</a>
          @else
            <a href="{{ route('login') }}" class="nav-link">Tes</a>
          @endauth
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">Contact</a>
        </li>

        <!-- Tombol login hanya muncul di mobile -->
        @guest
        <li class="nav-item mobile-only">
          <a href="{{ route('login') }}" class="nav-link">Daftar / Masuk</a>
        </li>
        @endguest
      </ul>
    </div>

    <!-- Right actions (desktop only) -->
    <div class="navbar-actions">
      @auth
        <div class="user-menu" id="tmUserMenu">
          <button class="btn-user btn-user--avatar"
                  onclick="toggleUserDropdown()"
                  aria-haspopup="true"
                  aria-expanded="false"
                  aria-label="Open user menu">
            <img src="{{ asset('assets/public/images/user-circle.png') }}"
                 alt="User" class="user-avatar-img" loading="lazy" decoding="async">
          </button>

          <div class="user-dropdown" id="userDropdown" role="menu" aria-hidden="true">
            @if (in_array(Auth::user()->role, ['admin','staff']))
              <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                <i class="icon-dashboard"></i> Admin Dashboard
              </a>
            @elseif (Auth::user()->role === 'pic')
              <a href="{{ route('pic.dashboard') }}" class="dropdown-item">
                <i class="icon-dashboard"></i> PIC Dashboard
              </a>
            @endif

            <a href="{{ route('profile.index') }}" class="dropdown-item">
              <i class="icon-user"></i> Profile
            </a>

            @if (Auth::user()->role === 'user')
              <a href="{{ route('profile.index') }}" class="dropdown-item">
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
        <a href="{{ route('login') }}" class="btn btn-login desktop-only">Daftar/Masuk</a>
      @endauth
    </div>
  </div>
</nav>

<!-- JS inline -->
<script>
  // Toggle mobile menu
  function toggleMobileMenu() {
    const menu = document.getElementById('tmNavbarMenu');
    const toggle = document.getElementById('tmMobileToggle');
    const isOpen = menu.classList.toggle('is-open');
    toggle.classList.toggle('active', isOpen);
  }

  // Toggle user dropdown
  function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const menuWrap = document.getElementById('tmUserMenu');
    const btn = menuWrap ? menuWrap.querySelector('.btn-user') : null;
    if (!dropdown || !menuWrap || !btn) return;
    const shown = dropdown.classList.toggle('show');
    menuWrap.classList.toggle('open', shown);
    btn.setAttribute('aria-expanded', shown ? 'true' : 'false');
    dropdown.setAttribute('aria-hidden', shown ? 'false' : 'true');
  }

  // Close dropdown on outside click
  document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('userDropdown');
    const menuWrap = document.getElementById('tmUserMenu');
    if (!menuWrap || !dropdown) return;
    if (!menuWrap.contains(e.target)) {
      dropdown.classList.remove('show');
      menuWrap.classList.remove('open');
    }
  });
</script>
