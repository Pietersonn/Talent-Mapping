<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/login-form-09/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/login-form-09/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-09/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-09/css/style.css') }}">

    <title>Login — TalentMapping</title>

    <style>
      .password-wrapper {
        position: relative;
      }
      #togglePassword {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d; /* Warna ikon */
      }

      .password-wrapper .form-control {
        padding-right: 40px;
      }
    </style>
    </head>

<body>

    <div class="content">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-6 contents">
                    <div class="form-block mx-auto">
                        <div class="mb-4 text-center">
                            <h3><strong>TalentMapping</strong></h3>
                            <p class="mb-4">Discover your potential and unlock your talent with us.</p>
                        </div>

                        {{-- session success --}}
                        @if (session('status'))
                            <div class="alert alert-success py-2 text-center mb-4">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group first">
                                <label for="email">Email</label>
                                <input id="email" type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group last mb-4">
                                <label for="password">Password</label>

                                <div class="password-wrapper">
                                  <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                  <span id="togglePassword">
                                    <span class="icon-eye" id="toggleIcon"></span>
                                  </span>
                                </div>

                                @error('password')
                                  <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex mb-4 align-items-center justify-content-between">
                                <label class="control control--checkbox mb-0">
                                    <span class="caption">Remember me</span>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <div class="control__indicator"></div>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-pass">Forgot Password</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-pill text-white btn-block btn-primary">
                                Log In
                            </button>

                            <span class="d-block text-center my-4 text-muted">or sign in with</span>

                            <div class="social-login text-center mb-4">
                                <a href="{{ route('login.google.redirect') }}" class="google">
                                    <span class="icon-google mr-3"></span>
                                </a>
                            </div>

                            @if (Route::has('register'))
                                <div class="text-center">
                                    <small>Don’t have an account?
                                        <a href="{{ route('register') }}">Create one</a>
                                    </small>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/login-form-09/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/login-form-09/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/login-form-09/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/login-form-09/js/main.js') }}"></script>

    <script>
      const toggleButton = document.getElementById("togglePassword");
      const passwordField = document.getElementById("password");
      const toggleIcon = document.getElementById("toggleIcon");

      if (toggleButton) {
        toggleButton.addEventListener("click", function() {
          // Toggle tipe atribut
          const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
          passwordField.setAttribute("type", type);

          // Toggle kelas ikon
          if (type === "text") {
            // Ganti ke ikon "mata-tercoret".
            // Pastikan kamu punya 'icon-eye-slash' di icomoon pack kamu.
            toggleIcon.classList.remove("icon-eye");
            toggleIcon.classList.add("icon-eye-slash");
          } else {
            toggleIcon.classList.remove("icon-eye-slash");
            toggleIcon.classList.add("icon-eye");
          }
        });
      }
    </script>
    </body>

</html>
