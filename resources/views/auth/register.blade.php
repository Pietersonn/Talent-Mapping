<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register | TalentMapping</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <!-- Logo & Header -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="mb-3">
                    <i class="fas fa-users-cog fa-3x text-primary"></i>
                </div>
                <h1 class="h4 mb-1">
                    <b>Talent</b>Mapping
                </h1>
                <p class="text-muted mb-0">Create your account to get started</p>
            </div>

            <div class="card-body">
                <p class="register-box-msg">
                    <i class="fas fa-user-plus mr-2"></i>Register a new account
                </p>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Full Name -->
                    <div class="input-group mb-3">
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Full Name"
                               required
                               autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="input-group mb-3">
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Email Address"
                               required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               id="password"
                               placeholder="Password (min. 8 characters)"
                               required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="input-group mb-3">
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation"
                               id="password_confirmation"
                               placeholder="Confirm Password"
                               required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="mb-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" id="password-strength" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small id="password-help" class="form-text text-muted">
                            Password must be at least 8 characters long
                        </small>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="agree_terms" required>
                                <label for="agreeTerms">
                                    I agree to the <a href="#" class="text-primary">Terms of Service</a>
                                    and <a href="#" class="text-primary">Privacy Policy</a>
                                </label>
                                @error('agree_terms')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" id="registerBtn">
                                <i class="fas fa-user-plus mr-2"></i>
                                Create Account
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Social Login (Optional) -->
                <div class="social-auth-links text-center mb-3">
                    <p class="mb-2">- OR -</p>
                    <a href="#" class="btn btn-block btn-outline-danger">
                        <i class="fab fa-google mr-2"></i>
                        Register using Google
                    </a>
                </div>

                <!-- Login Link -->
                <p class="text-center">
                    <a href="{{ route('login') }}" class="text-primary">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        Already have an account? Sign in
                    </a>
                </p>
            </div>

            <!-- Footer Info -->
            <div class="card-footer text-center text-muted">
                <small>
                    <i class="fas fa-shield-alt mr-1"></i>
                    Your information is secure and will never be shared
                </small>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Password strength checker
            $('#password').on('keyup', function() {
                const password = $(this).val();
                const strength = checkPasswordStrength(password);

                updatePasswordStrengthUI(strength);
            });

            // Password confirmation match checker
            $('#password_confirmation').on('keyup', function() {
                const password = $('#password').val();
                const confirmation = $(this).val();

                if (password && confirmation) {
                    if (password === confirmation) {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    } else {
                        $(this).removeClass('is-valid').addClass('is-invalid');
                    }
                }
            });

            // Form submission with validation
            $('#registerForm').on('submit', function(e) {
                const password = $('#password').val();
                const confirmation = $('#password_confirmation').val();
                const agreeTerms = $('#agreeTerms').is(':checked');

                // Basic validation
                if (password !== confirmation) {
                    e.preventDefault();
                    showErrorToast('Passwords do not match!');
                    return false;
                }

                if (!agreeTerms) {
                    e.preventDefault();
                    showErrorToast('Please agree to the Terms of Service and Privacy Policy.');
                    return false;
                }

                // Show loading
                $('#registerBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...');
            });

            // Check for session messages
            @if(session('success'))
                showSuccessToast('{{ session('success') }}');
            @endif

            @if(session('error'))
                showErrorToast('{{ session('error') }}');
            @endif
        });

        function checkPasswordStrength(password) {
            let score = 0;
            if (!password) return score;

            // Length check
            if (password.length >= 8) score += 25;
            if (password.length >= 12) score += 25;

            // Character variety checks
            if (/[a-z]/.test(password)) score += 12.5;
            if (/[A-Z]/.test(password)) score += 12.5;
            if (/[0-9]/.test(password)) score += 12.5;
            if (/[^A-Za-z0-9]/.test(password)) score += 12.5;

            return score;
        }

        function updatePasswordStrengthUI(strength) {
            const progressBar = $('#password-strength');
            const helpText = $('#password-help');

            let color, text;

            if (strength < 25) {
                color = 'bg-danger';
                text = 'Very weak password';
            } else if (strength < 50) {
                color = 'bg-warning';
                text = 'Weak password';
            } else if (strength < 75) {
                color = 'bg-info';
                text = 'Good password';
            } else {
                color = 'bg-success';
                text = 'Strong password';
            }

            progressBar.removeClass('bg-danger bg-warning bg-info bg-success').addClass(color);
            progressBar.css('width', strength + '%');
            helpText.text(text);
        }

        // SweetAlert2 helper functions
        function showSuccessToast(message) {
            Swal.fire({
                title: message,
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }

        function showErrorToast(message) {
            Swal.fire({
                title: message,
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }
    </script>
</body>
</html>
