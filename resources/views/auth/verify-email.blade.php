<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Email | TalentMapping</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="mb-3">
                    <i class="fas fa-envelope-open-text fa-3x text-primary"></i>
                </div>
                <h1 class="h4 mb-1">
                    <b>Talent</b>Mapping
                </h1>
                <p class="text-muted mb-0">Email Verification Required</p>
            </div>

            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Check Your Email</h5>
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                </div>

                <div class="text-center mb-4">
                    <p class="text-muted">
                        We sent a verification email to:<br>
                        <strong>{{ auth()->user()->email }}</strong>
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <div class="row">
                    <div class="col-12 mb-3">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Resend Verification Email
                            </button>
                        </form>
                    </div>

                    <div class="col-12">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <div class="alert alert-light">
                        <h6><i class="fas fa-lightbulb mr-2"></i>Didn't receive the email?</h6>
                        <small class="text-muted">
                            • Check your spam/junk folder<br>
                            • Make sure {{ auth()->user()->email }} is correct<br>
                            • Try clicking "Resend Verification Email"
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-footer text-center text-muted">
                <small>
                    <i class="fas fa-shield-alt mr-1"></i>
                    Email verification helps keep your account secure
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
            // Check for session messages
            @if(session('success'))
                showSuccessToast('{{ session('success') }}');
            @endif

            @if(session('status') == 'verification-link-sent')
                showSuccessToast('Verification email has been sent!');
            @endif
        });

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
    </script>
</body>
</html>
