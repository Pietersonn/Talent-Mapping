@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-edit mr-1"></i>
                            Edit User Information
                        </h3>
                    </div>
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="required">Full Name</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $user->name) }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="required">Email Address</label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $user->email) }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Role & Status -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role" class="required">Role</label>
                                        <select class="form-control @error('role') is-invalid @enderror"
                                                id="role"
                                                name="role"
                                                required
                                                {{ (Auth::user()->role !== 'admin' && $user->role === 'admin') ? 'disabled' : '' }}>
                                            @if(Auth::user()->role === 'admin')
                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                            @endif
                                            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                            <option value="pic" {{ old('role', $user->role) == 'pic' ? 'selected' : '' }}>Person in Charge</option>
                                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Regular User</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active">Account Status</label>
                                        <div class="mt-2">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       id="is_active"
                                                       name="is_active"
                                                       value="1"
                                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                                       {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                                <label class="custom-control-label" for="is_active">
                                                    Active Account
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="change_password">
                                    <label class="custom-control-label" for="change_password">
                                        Change Password
                                    </label>
                                </div>
                            </div>

                            <div id="password_fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="password"
                                                   name="password"
                                                   minlength="8">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password"
                                                   class="form-control"
                                                   id="password_confirmation"
                                                   name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Leave empty to keep current password</small>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Update User
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Simple Sidebar -->
            <div class="col-lg-4">
                <!-- User Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-1"></i> Current User Info
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="user-avatar mb-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }} ml-1">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <table class="table table-sm">
                            <tr>
                                <td>Created:</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Last Updated:</td>
                                <td>{{ $user->updated_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Test Sessions:</td>
                                <td>{{ $user->testSessions()->count() }}</td>
                            </tr>
                            @if($user->role === 'pic')
                                <tr>
                                    <td>Events as PIC:</td>
                                    <td>{{ $user->picEvents()->count() }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tools mr-1"></i> Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <button type="button"
                                    class="btn btn-info btn-sm btn-block mb-2"
                                    onclick="resetUserPassword()">
                                <i class="fas fa-key mr-1"></i> Reset Password
                            </button>

                            <button type="button"
                                    class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} btn-sm btn-block mb-2"
                                    onclick="confirmToggleStatus('{{ $user->name }}', '{{ route('admin.users.toggle-status', $user) }}', {{ $user->is_active ? 'true' : 'false' }})">
                                <i class="fas fa-power-off mr-1"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>

                            @if($user->role !== 'admin')
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-block"
                                        onclick="confirmDelete('{{ $user->name }}', '{{ route('admin.users.destroy', $user) }}')">
                                    <i class="fas fa-trash mr-1"></i> Delete User
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password fields
        $('#change_password').change(function() {
            if ($(this).is(':checked')) {
                $('#password_fields').slideDown();
                $('#password').attr('required', true);
                $('#password_confirmation').attr('required', true);
            } else {
                $('#password_fields').slideUp();
                $('#password').removeAttr('required').val('');
                $('#password_confirmation').removeAttr('required').val('');
            }
        });

        // Form validation
        $('#editUserForm').on('submit', function(e) {
            if ($('#change_password').is(':checked')) {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();

                if (password !== confirmPassword) {
                    e.preventDefault();
                    showErrorToast('Passwords do not match!');
                    return false;
                }
            }
        });

        // Quick actions
        function confirmToggleStatus(userName, toggleUrl, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            confirmToggleStatus(
                `${action.charAt(0).toUpperCase() + action.slice(1)} User?`,
                `Are you sure you want to ${action} "${userName}"?`,
                toggleUrl,
                currentStatus
            );
        }

        function confirmDelete(userName, deleteUrl) {
            confirmDelete(
                'Delete User?',
                `Are you sure you want to delete "${userName}"? This cannot be undone.`,
                deleteUrl
            );
        }

        function resetUserPassword() {
            customConfirm({
                title: 'Reset Password?',
                text: 'Generate a temporary password for this user?',
                icon: 'question',
                confirmButtonText: 'Yes, reset!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('admin.users.reset-password', $user) }}';
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .required::after {
            content: " *";
            color: red;
        }

        .user-avatar {
            display: inline-block;
        }

        .table-sm td {
            padding: 0.3rem;
            border-top: 1px solid #dee2e6;
            font-size: 0.875rem;
        }

        .table-sm td:first-child {
            font-weight: 500;
            width: 40%;
        }
    </style>
@endpush
