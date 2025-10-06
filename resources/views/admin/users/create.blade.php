@extends('admin.layouts.app')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">

        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-1"></i>
                        Create User
                    </h3>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                    @csrf

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
                                       value="{{ old('name') }}"
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
                                       value="{{ old('email') }}"
                                       required
                                       maxlength="255"
                                       placeholder="user@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Must be unique across the system</small>
                            </div>
                        </div>

                        <!-- Password Section (required on create) -->
                        <div class="form-section mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-lock mr-1"></i> Password
                            </h5>

                            <div class="form-group">
                                <label for="password" class="required">Password</label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           minlength="8"
                                           required
                                           placeholder="Enter password (min 8 chars)">
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
                                <label for="password_confirmation" class="required">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           placeholder="Confirm password">
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
                                        required>
                                    <option value="">-- Select Role --</option>
                                    @if(Auth::user()->role === 'admin')
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Administrator - Full system access
                                        </option>
                                    @endif
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>
                                        Staff - Limited administrative access
                                    </option>
                                    <option value="pic" {{ old('role') == 'pic' ? 'selected' : '' }}>
                                        Person in Charge - Event management access
                                    </option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                                        Regular User - Assessment taking access
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                           {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Account Active
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Inactive users cannot log in to the system
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Create User
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- (Tidak ada sidebar untuk halaman create) -->

    </div>
</div>
@endsection

@push('scripts')
<script>
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

    // Password strength checker
    function checkPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score += 25;
        if (/[A-Z]/.test(password)) score += 25;
        if (/[a-z]/.test(password)) score += 25;
        if (/[0-9]/.test(password)) score += 15;
        if (/[^A-Za-z0-9]/.test(password)) score += 10;
        return { score };
    }

    $(document).ready(function() {
        // Role description visibility
        function showRoleDescription(role) {
            const allDescs = document.querySelectorAll('.role-desc');
            allDescs.forEach(d => d.style.display = 'none');
            if (role) {
                const target = document.getElementById(role + '-desc');
                if (target) target.style.display = 'block';
            }
        }
        showRoleDescription($('#role').val());
        $('#role').on('change', function(){ showRoleDescription(this.value); });

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

        // Form validation (password match)
        $('#createUserForm').on('submit', function(e) {
            const password = $('#password').val();
            const confirm = $('#password_confirmation').val();
            let ok = true;
            if (password !== confirm) {
                $('#password_confirmation').addClass('is-invalid');
                if (!$('#password_confirmation').next('.invalid-feedback').length) {
                    $('#password_confirmation').after('<div class="invalid-feedback">Passwords do not match.</div>');
                }
                ok = false;
            } else {
                $('#password_confirmation').removeClass('is-invalid');
                $('#password_confirmation').next('.invalid-feedback').remove();
            }
            if (!ok) {
                e.preventDefault();
                if (typeof showErrorToast === 'function') {
                    showErrorToast('Please fix the form errors before submitting.');
                } else {
                    alert('Please fix the form errors before submitting.');
                }
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .required::after { content: " *"; color: red; }
    .form-section { border-radius: 0.5rem; transition: all 0.3s ease; }
    .password-strength .progress { border-radius: 10px; }
    .role-desc { display: none; }
    .form-text.text-danger { color: #dc3545 !important; }
    .form-text.text-warning { color: #ffc107 !important; }
    .form-text.text-success { color: #28a745 !important; }
</style>
@endpush
