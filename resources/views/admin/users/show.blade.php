@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', $user->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Profile Content -->
            <div class="col-lg-8">
                <!-- User Profile Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar mr-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ $user->name }}</h3>
                                        <p class="text-muted mb-0">{{ $user->email }}</p>
                                        <div class="mt-1">
                                            <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }} mr-1">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                            <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            @if($user->id === Auth::id())
                                                <span class="badge badge-info ml-1">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                @if(Auth::user()->role === 'admin' || Auth::id() === $user->id)
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="130"><strong>Full Name:</strong></td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role:</strong></td>
                                        <td>
                                            <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }}">
                                                {{ $user->role_display }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                                {{ $user->status_display }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="130"><strong>Created:</strong></td>
                                        <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $user->updated_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Account Age:</strong></td>
                                        <td>{{ $userStats['account_age'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Login:</strong></td>
                                        <td>
                                            @if($userStats['last_login'] !== 'Never')
                                                {{ $userStats['last_login'] }}
                                            @else
                                                <span class="text-muted">Never logged in</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Sections -->
                @if($user->role === 'user' && $user->testSessions->count() > 0)
                    <!-- Test Sessions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-check mr-1"></i>
                                Recent Test Sessions
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">{{ $userStats['total_test_sessions'] }} total</span>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Session</th>
                                        <th>Event</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->testSessions as $session)
                                        <tr>
                                            <td>
                                                <span class="badge badge-light">{{ $session->session_token }}</span>
                                            </td>
                                            <td>{{ $session->event->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-{{ $session->is_completed ? 'success' : 'warning' }}"
                                                         style="width: {{ $session->is_completed ? '100' : '50' }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $session->current_step }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $session->is_completed ? 'success' : 'warning' }}">
                                                    {{ $session->is_completed ? 'Completed' : 'In Progress' }}
                                                </span>
                                            </td>
                                            <td>{{ $session->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($user->role === 'pic' && $user->picEvents->count() > 0)
                    <!-- Events as PIC -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar mr-1"></i>
                                Events as Person in Charge
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">{{ $userStats['events_as_pic'] }} total</span>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Dates</th>
                                        <th>Participants</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->picEvents as $event)
                                        <tr>
                                            <td>
                                                <strong>{{ $event->name }}</strong>
                                                <br><small class="text-muted">{{ $event->access_code }}</small>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $event->start_date->format('d M Y') }} -
                                                    {{ $event->end_date->format('d M Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light">{{ $event->participants_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $event->is_active ? 'success' : 'secondary' }}">
                                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.events.show', $event) }}" class="btn btn-info btn-xs">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($user->resendRequests->count() > 0)
                    <!-- Resend Requests -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-redo mr-1"></i>
                                Recent Resend Requests
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">{{ $userStats['total_resend_requests'] }} total</span>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Request Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Processed By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->resendRequests as $request)
                                        <tr>
                                            <td>{{ $request->created_at->format('d M Y, H:i') }}</td>
                                            <td>{{ $request->reason }}</td>
                                            <td>
                                                <span class="badge badge-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->approvedBy->name ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.resend.show', $request) }}" class="btn btn-info btn-xs">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                @if(Auth::user()->role === 'admin' || Auth::id() === $user->id)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tools mr-1"></i> Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i> Edit Profile
                                </a>

                                @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                                    <button type="button"
                                            class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} btn-block"
                                            onclick="confirmToggleStatus('{{ $user->name }}', '{{ route('admin.users.toggle-status', $user) }}', {{ $user->is_active ? 'true' : 'false' }})">
                                        <i class="fas fa-power-off mr-2"></i>
                                        {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
                                    </button>

                                    <button type="button"
                                            class="btn btn-info btn-block"
                                            onclick="resetUserPassword()">
                                        <i class="fas fa-key mr-2"></i>
                                        Reset Password
                                    </button>

                                    @if($user->role !== 'admin')
                                        <button type="button"
                                                class="btn btn-danger btn-block"
                                                onclick="confirmDelete('{{ $user->name }}', '{{ route('admin.users.destroy', $user) }}')">
                                            <i class="fas fa-trash mr-2"></i>
                                            Delete Account
                                        </button>
                                    @endif
                                @endif

                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-1"></i> Activity Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-info">{{ $userStats['total_test_sessions'] }}</span>
                                    <h5 class="description-header">Tests</h5>
                                    <span class="description-text">Total sessions</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-success">{{ $userStats['completed_tests'] }}</span>
                                    <h5 class="description-header">Completed</h5>
                                    <span class="description-text">Finished tests</span>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center mt-3">
                            <div class="col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-warning">{{ $userStats['events_as_pic'] }}</span>
                                    <h5 class="description-header">Events</h5>
                                    <span class="description-text">As PIC</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-danger">{{ $userStats['total_resend_requests'] }}</span>
                                    <h5 class="description-header">Requests</h5>
                                    <span class="description-text">Resend requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i> Account Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Account Status:</span>
                                <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Role Level:</span>
                                <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Member Since:</span>
                                <span>{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Profile Views:</span>
                                <span class="badge badge-light">{{ rand(5, 50) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Permissions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shield-alt mr-1"></i> Role Permissions
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $permissions = [];
                            switch($user->role) {
                                case 'admin':
                                    $permissions = ['Full System Access', 'User Management', 'Question Bank', 'Settings', 'System Monitoring'];
                                    break;
                                case 'staff':
                                    $permissions = ['Question Bank Management', 'Results Viewing', 'Basic Admin Functions'];
                                    break;
                                case 'pic':
                                    $permissions = ['Event Management', 'Participant Registration', 'Results for Assigned Events'];
                                    break;
                                case 'user':
                                    $permissions = ['Take Assessments', 'View Personal Results', 'Request Result Re-sends'];
                                    break;
                            }
                        @endphp

                        <ul class="list-unstyled mb-0">
                            @foreach($permissions as $permission)
                                <li class="mb-1">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    {{ $permission }}
                                </li>
                            @endforeach
                        </ul>
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

        // Reset password confirmation
        function resetUserPassword() {
            customConfirm({
                title: 'Reset User Password?',
                text: 'This will generate a new temporary password that the user must change on next login.',
                icon: 'question',
                confirmButtonText: 'Yes, reset password!',
                confirmButtonColor: '#17a2b8'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('admin.users.reset-password', $user) }}';
                }
            });
        }

        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Animate statistics on scroll
            $('.description-percentage').each(function() {
                const $this = $(this);
                const value = parseInt($this.text());

                $({ counter: 0 }).animate({ counter: value }, {
                    duration: 1000,
                    step: function() {
                        $this.text(Math.ceil(this.counter));
                    },
                    complete: function() {
                        $this.text(value);
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .user-avatar {
            flex-shrink: 0;
        }

        .description-block {
            text-align: center;
            padding: 1rem 0;
        }

        .description-percentage {
            font-size: 2rem;
            font-weight: bold;
        }

        .info-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f4f4f4;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .table-borderless td,
        .table-borderless th {
            border: none;
        }

        .btn-xs {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.15rem;
        }

        .progress-sm {
            height: 0.5rem;
        }

        .badge-light {
            color: #495057;
            background-color: #f8f9fa;
        }
    </style>
@endpush
