<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex justify-content-left align-items-center">
        <img src="{{ asset('dist/img/logo-bcti.png') }}" alt="TalentMapping Logo" class="brand-image"
            style="opacity:.9; max-height:50px;">
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="{{ route('admin.profile.edit') }}" class="d-block">
                    {{ Auth::user()->name }}
                    <small class="d-block text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Question Bank -->
                <li
                    class="nav-item {{ request()->routeIs('admin.questions.*') || request()->routeIs('admin.st30.*') || request()->routeIs('admin.sjt.*') || request()->routeIs('admin.competencies.*') || request()->routeIs('admin.typologies.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.questions.*') || request()->routeIs('admin.st30.*') || request()->routeIs('admin.sjt.*') || request()->routeIs('admin.competencies.*') || request()->routeIs('admin.typologies.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Question Bank
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.questions.index') }}"
                                class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Question Versions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.st30.index') }}"
                                class="nav-link {{ request()->routeIs('admin.st30.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ST-30 Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sjt.index') }}"
                                class="nav-link {{ request()->routeIs('admin.sjt.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>SJT Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.competencies.index') }}"
                                class="nav-link {{ request()->routeIs('admin.competencies.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Competencies</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.typologies.index') }}"
                                class="nav-link {{ request()->routeIs('admin.typologies.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Typologies</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Management (Admin Only) -->
                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                @endif

                <!-- Event Management -->
                <li class="nav-item">
                    <a href="{{ route('admin.events.index') }}"
                        class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Event Management
                            @if (Auth::user()->role === 'staff')
                                <small class="badge badge-info right">View Only</small>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- Results & Reports -->
                <li
                    class="nav-item {{ request()->routeIs('admin.results.*') || request()->routeIs('admin.resend.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.results.*') || request()->routeIs('admin.resend.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Results & Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.results.index') }}"
                                class="nav-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Results</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.resend.index') }}"
                                class="nav-link {{ request()->routeIs('admin.resend.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Resend Requests
                                    @if (Auth::user()->role === 'staff')
                                        <small class="badge badge-info right">View Only</small>
                                    @endif
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- System Monitoring -->
                <li class="nav-item">
                    <a href="{{ route('admin.monitoring.index') }}"
                        class="nav-link {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>System Monitoring</p>
                    </a>
                </li>

                <!-- Settings (Admin Only) -->
                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}"
                            class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                @endif

                <!-- Divider -->
                <li class="nav-header">QUICK ACTIONS</li>

                <!-- Back to Public Site -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-external-link-alt"></i>
                        <p>
                            View Public Site
                            <small class="badge badge-success right">Public</small>
                        </p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-left w-100 border-0 bg-transparent">
                            <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                            <p class="text-danger">Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
