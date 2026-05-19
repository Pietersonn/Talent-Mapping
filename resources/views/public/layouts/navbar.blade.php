<nav class="navbar">
  <div class="container">
    <div class="navbar-brand">
      <a href="{{ route('home') }}" aria-label="Go to Home">
        <img src="{{ asset('assets/public/images/logo-bcti1.png') }}" alt="BCTI Logo" class="logo">
      </a>
    </div>

    <button class="mobile-menu-toggle" id="tmMobileToggle"
            aria-controls="tmNavbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
            onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </button>

    <div class="navbar-menu" id="tmNavbarMenu">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        </li>

        <li class="nav-item">
          @auth
            <a href="{{ route('test.form') }}" class="nav-link {{ request()->routeIs('test.*') ? 'active' : '' }}">Tes</a>
          @else
            <a href="{{ route('login') }}" class="nav-link">Tes</a>
          @endauth
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">Kontak</a>
        </li>

        @guest
        <li class="nav-item mobile-only">
          <a href="{{ route('login') }}" class="nav-link">Daftar / Masuk</a>
        </li>
        @endguest
      </ul>
    </div>

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

            @if (in_array(Auth::user()->peran, ['admin','staff']))
              <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                <i class="icon-dashboard"></i> Admin Dashboard
              </a>
            @elseif (Auth::user()->peran === 'mitra')
              <a href="{{ route('mitra.dashboard') }}" class="dropdown-item">
                <i class="icon-dashboard"></i> Mitra Dashboard
              </a>
            @endif

            @if (Auth::user()->peran === 'peserta')
              <a href="{{ route('profile') }}" class="dropdown-item">
                <i class="icon-user"></i> Profil Saya
              </a>
              <a href="{{ route('profile') }}" class="dropdown-item">
                <i class="icon-test"></i> Tes Saya
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

<script>
  function toggleMobileMenu() {
    const menu = document.getElementById('tmNavbarMenu');
    const toggle = document.getElementById('tmMobileToggle');
    const isOpen = menu.classList.toggle('is-open');
    toggle.classList.toggle('active', isOpen);
  }

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
