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
        <div class="row justify-content-center">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-edit mr-1"></i>
                            Edit User: {{ $user->name }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }} ml-1">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Personal Information -->
                            <div class="form-section mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user mr-1"></i> Personal Information
                                </h5>

                                <div class="form-group">
                                    <label for="name" class="required">Full Name</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           required
                                           maxlength="255"
                                           placeholder="Enter user's full name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="required">Email Address</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           required
                                           maxlength="255"
                                           placeholder="user@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Must be unique across the system</small>
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="form-section mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-lock mr-1"></i> Password Settings
                                    <button type="button" class="btn btn-link btn-sm" onclick="togglePasswordSection()">
                                        <span id="password-section-text">Change Password</span>
                                        <i id="password-section-icon" class="fas fa-chevron-down ml-1"></i>
                                    </button>
                                </h5>

                                <div id="password-section" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Leave password fields empty to keep the current password unchanged.
                                    </div>

                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <div class="input-group">
                                            <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="password"
                                                   name="password"
                                                   minlength="8"
                                                   placeholder="Enter new password (optional)">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                    <i class="fas fa-eye" id="password-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password Strength Indicator -->
                                        <div class="password-strength mt-2" style="display: none;">
                                            <div class="progress" style="height: 5px;">
                                                <div id="password-strength-bar" class="progress-bar" style="width: 0%"></div>
                                            </div>
                                            <small id="password-strength-text" class="form-text text-muted">Password strength: Not set</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password"
                                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   id="password_confirmation"
                                                   name="password_confirmation"
                                                   placeholder="Confirm new password">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                                </button>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                                        <div class="form-group">
                                            <button type="button" class="btn btn-warning btn-sm" onclick="generateTempPassword()">
                                                <i class="fas fa-random mr-1"></i> Generate Temporary Password
                                            </button>
                                            <small class="form-text text-muted">
                                                This will generate a random password that the user must change on next login
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Role & Permissions -->
                            <div class="form-section mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-tag mr-1"></i> Role & Permissions
                                </h5>

                                <div class="form-group">
                                    <label for="role" class="required">User Role</label>
                                    <select class="form-control @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            required
                                            {{ (Auth::user()->role !== 'admin' && $user->role === 'admin') ? 'disabled' : '' }}>
                                        <option value="">-- Select Role --</option>
                                        @if(Auth::user()->role === 'admin')
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                                Administrator - Full system access
                                            </option>
                                        @endif
                                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
                                            Staff - Limited administrative access
                                        </option>
                                        <option value="pic" {{ old('role', $user->role) == 'pic' ? 'selected' : '' }}>
                                            Person in Charge - Event management access
                                        </option>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                            Regular User - Assessment taking access
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if(old('role', $user->role) !== $user->role)
                                        <div class="alert alert-warning mt-2">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Role change detected. This will affect user's access permissions.
                                        </div>
                                    @endif
                                </div>

                                <!-- Role Description -->
                                <div id="role-description" class="alert alert-info">
                                    <div id="admin-desc" class="role-desc">
                                        <strong>Administrator:</strong> Full access to all system features including user management, question bank, settings, and system monitoring.
                                    </div>
                                    <div id="staff-desc" class="role-desc">
                                        <strong>Staff:</strong> Access to question bank management, results viewing, and basic administrative functions. Cannot manage users or system settings.
                                    </div>
                                    <div id="pic-desc" class="role-desc">
                                        <strong>Person in Charge:</strong> Can create and manage events, register participants, and view results for their assigned events.
                                    </div>
                                    <div id="user-desc" class="role-desc">
                                        <strong>Regular User:</strong> Can take assessments, view personal results, and request result re-sends. No administrative access.
                                    </div>
                                </div>
                            </div>

                            <!-- Account Status -->
                            <div class="form-section">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-toggle-on mr-1"></i> Account Status
                                </h5>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                               {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Account Active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        @if(Auth::id() === $user->id)
                                            You cannot deactivate your own account
                                        @else
                                            Inactive users cannot log in to the system
                                        @endif
                                    </small>

                                    @if(!old('is_active', $user->is_active) && $user->is_active)
                                        <div class="alert alert-warning mt-2">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Warning: Deactivating this account will prevent the user from logging in.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Current User Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i> Current Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $user->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Current Role:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : ($user->role === 'pic' ? 'info' : 'success')) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if(Auth::user()->role === 'admin')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tools mr-1"></i> Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(Auth::id() !== $user->id)
                                <button type="button"
                                        class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} btn-block btn-sm mb-2"
                                        onclick="confirmToggleStatus('{{ $user->name }}', '{{ route('admin.users.toggle-status', $user) }}', {{ $user->is_active ? 'true' : 'false' }})">
                                    <i class="fas fa-power-off mr-1"></i>
                                    {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
                                </button>

                                <button type="button"
                                        class="btn btn-info btn-block btn-sm mb-2"
                                        onclick="resetUserPassword()">
                                    <i class="fas fa-key mr-1"></i>
                                    Reset Password
                                </button>

                                @if($user->role !== 'admin')
                                    <button type="button"
                                            class="btn btn-danger btn-block btn-sm"
                                            onclick="confirmDelete('{{ $user->name }}', '{{ route('admin.users.destroy', $user) }}')">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete Account
                                    </button>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Limited actions available for your own account.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Activity Summary -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i> Activity Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-info">{{ $user->testSessions()->count() }}</span>
                                    <h5 class="description-header">Tests</h5>
                                    <span class="description-text">Total sessions</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-warning">{{ $user->picEvents()->count() }}</span>
                                    <h5 class="description-header">Events</h5>
                                    <span class="description-text">As PIC</span>
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
        // Toggle password section visibility
        function togglePasswordSection() {
            const section = document.getElementById('password-section');
            const text = document.getElementById('password-section-text');
            const icon = document.getElementById('password-section-icon');

            if (section.style.display === 'none') {
                section.style.display = 'block';
                text.textContent = 'Hide Password Section';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                section.style.display = 'none';
                text.textContent = 'Change Password';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Generate temporary password
        function generateTempPassword() {
            const tempPass = 'TalentMap' + Math.floor(Math.random() * 10000);
            document.getElementById('password').value = tempPass;
            document.getElementById('password_confirmation').value = tempPass;

            // Show password section if hidden
            const section = document.getElementById('password-section');
            if (section.style.display === 'none') {
                togglePasswordSection();
            }

            showSuccessToast(`Temporary password generated: ${tempPass}`);
        }

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

        // Reset password
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

        // Password strength checker (same as create view)
        function checkPasswordStrength(password) {
            let score = 0;
            if (password.length >= 8) score += 25;
            if (/[A-Z]/.test(password)) score += 25;
            if (/[a-z]/.test(password)) score += 25;
            if (/[0-9]/.test(password)) score += 15;
            if (/[^A-Za-z0-9]/.test(password)) score += 10;
            return { score };
        }

        // Role description display
        function showRoleDescription(role) {
            const allDescs = document.querySelectorAll('.role-desc');
            allDescs.forEach(desc => desc.style.display = 'none');

            if (role) {
                const targetDesc = document.getElementById(role + '-desc');
                if (targetDesc) targetDesc.style.display = 'block';
            }
        }

        $(document).ready(function() {
            // Show current role description
            showRoleDescription('{{ $user->role }}');

            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthDiv = $('.password-strength');

                if (password) {
                    strengthDiv.show();
                    const result = checkPasswordStrength(password);
                    const progressBar = $('#password-strength-bar');
                    const strengthText = $('#password-strength-text');

                    progressBar.css('width', result.score + '%');

                    if (result.score < 50) {
                        progressBar.removeClass().addClass('progress-bar bg-danger');
                        strengthText.text('Password strength: Weak').removeClass().addClass('form-text text-danger');
                    } else if (result.score < 75) {
                        progressBar.removeClass().addClass('progress-bar bg-warning');
                        strengthText.text('Password strength: Fair').removeClass().addClass('form-text text-warning');
                    } else {
                        progressBar.removeClass().addClass('progress-bar bg-success');
                        strengthText.text('Password strength: Strong').removeClass().addClass('form-text text-success');
                    }
                } else {
                    strengthDiv.hide();
                }
            });

            // Role change handler
            $('#role').on('change', function() {
                showRoleDescription($(this).val());
            });

            // Form validation before submit
            $('#editUserForm').on('submit', function(e) {
                let isValid = true;
                const password = $('#password').val();
                const passwordConfirm = $('#password_confirmation').val();

                // Check password match only if password is provided
                if (password || passwordConfirm) {
                    if (password !== passwordConfirm) {
                        $('#password_confirmation').addClass('is-invalid');
                        if (!$('#password_confirmation').next('.invalid-feedback').length) {
                            $('#password_confirmation').after('<div class="invalid-feedback">Passwords do not match.</div>');
                        }
                        isValid = false;
                    } else {
                        $('#password_confirmation').removeClass('is-invalid');
                        $('#password_confirmation').next('.invalid-feedback').remove();
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    showErrorToast('Please fix the form errors before submitting.');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .required::after {
            content: " *";
            color: red;
        }

        .form-section {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .password-strength .progress {
            border-radius: 10px;
        }

        .role-desc {
            display: none;
        }

        .description-block {
            text-align: center;
        }

        .form-text.text-danger {
            color: #dc3545 !important;
        }

        .form-text.text-warning {
            color: #ffc107 !important;
        }

        .form-text.text-success {
            color: #28a745 !important;
        }

        .table-borderless td,
        .table-borderless th {
            border: none;
        }
    </style>
@endpush

