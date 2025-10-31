@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">User Management</li>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Filter & Search Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            User Management
                        </h3>
                        <div class="card-tools">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add New User
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="search">Search Users:</label>
                                    <input type="text" id="search" name="search" class="form-control"
                                           placeholder="Search by name or email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="role">Filter by Role:</label>
                                    <select id="role" name="role" class="form-control">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="pic" {{ request('role') == 'pic' ? 'selected' : '' }}>Person in Charge</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status">Filter by Status:</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm ml-1">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $statistics['total_users'] }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $statistics['active_users'] }}</h3>
                        <p>Active Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $statistics['admins'] + $statistics['staff'] }}</h3>
                        <p>Admin & Staff</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $statistics['pics'] + $statistics['regular_users'] }}</h3>
                        <p>PIC & Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users List</h3>
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $users->total() }} total users</span>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        @if($users->count() > 0)
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th width="200">Name</th>
                                        <th width="200">Email</th>
                                        <th width="100">Role</th>
                                        <th width="80">Status</th>
                                        <th width="100">Activity</th>
                                        <th width="120">Created</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p>{{ $user->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    @if($user->test_sessions_count > 0)
                                                        <div><i class="fas fa-clipboard-check text-info"></i> {{ $user->test_sessions_count }} tests</div>
                                                    @endif
                                                    @if($user->pic_events_count > 0)
                                                        <div><i class="fas fa-calendar text-warning"></i> {{ $user->pic_events_count }} events</div>
                                                    @endif
                                                    @if($user->test_sessions_count == 0 && $user->pic_events_count == 0)
                                                        <span class="text-muted">No activity</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.users.show', $user) }}"
                                                       class="btn btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if(Auth::user()->role === 'admin' || Auth::id() === $user->id)
                                                        <a href="{{ route('admin.users.edit', $user) }}"
                                                           class="btn btn-warning" title="Edit User">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                                                        <button type="button"
                                                                class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }}"
                                                                onclick="confirmToggleStatus('{{ $user->name }}', '{{ route('admin.users.toggle-status', $user) }}', {{ $user->is_active ? 'true' : 'false' }})"
                                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>

                                                        @if($user->role !== 'admin' || Auth::user()->role === 'admin')
                                                            <button type="button"
                                                                    class="btn btn-danger"
                                                                    onclick="confirmDelete('{{ $user->name }}', '{{ route('admin.users.destroy', $user) }}')"
                                                                    title="Delete User">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Users Found</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'role', 'status']))
                                        No users match your current filters.
                                        <br><a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-times"></i> Clear Filters
                                        </a>
                                    @else
                                        Start by creating your first user.
                                        @if(Auth::user()->role === 'admin')
                                            <br><a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus"></i> Add First User
                                            </a>
                                        @endif
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                    @if($users->hasPages())
                        <div class="card-footer clearfix">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Role Distribution Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Role Distribution
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger">
                                        <i class="fas fa-user-shield"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Administrators</span>
                                        <span class="info-box-number">{{ $statistics['admins'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-user-cog"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Staff</span>
                                        <span class="info-box-number">{{ $statistics['staff'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Person in Charge</span>
                                        <span class="info-box-number">{{ $statistics['pics'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Regular Users</span>
                                        <span class="info-box-number">{{ $statistics['regular_users'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Toggle status confirmation
        function confirmToggleStatus(userName, toggleUrl, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            const actionText = currentStatus ? 'Deactivate' : 'Activate';

            confirmToggleStatus(
                `${actionText} User?`,
                `Are you sure you want to ${action} user "${userName}"?`,
                toggleUrl,
                currentStatus
            );
        }

        // Delete confirmation
        function confirmDelete(userName, deleteUrl) {
            confirmDelete(
                'Delete User?',
                `Are you sure you want to delete user "${userName}"? This action cannot be undone. Consider deactivating the user instead.`,
                deleteUrl
            );
        }

        $(document).ready(function() {
            // Auto-submit filter form on change
            $('#role, #status').on('change', function() {
                $(this).closest('form').submit();
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
