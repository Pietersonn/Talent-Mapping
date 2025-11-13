<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/login-form-09/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/login-form-09/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-09/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-09/css/style.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <title>Register — TalentMapping</title>

    <style>
      .help-text {
        font-size: 12px;
        color: #6c757d;
      }
      .progress {
        height: 6px;
      }
      .progress-bar {
        transition: width .25s ease;
      }

      /* --- Style untuk Ikon Mata (Show/Hide Password) --- */
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
      /* Menambahkan padding kanan pada input password agar teks tidak tertimpa ikon */
      .password-wrapper .form-control {
        padding-right: 40px;
      }
      /* --- Akhir Style Ikon Mata --- */

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
                <p class="mb-4">Create your account and start your assessment journey.</p>
              </div>

              {{-- SweetAlert session success --}}
              @if (session('status'))
                <script>
                  document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                      icon: 'success',
                      title: 'Success',
                      text: '{{ session('status') }}',
                      confirmButtonColor: '#4CAF50'
                    });
                  });
                </script>
              @endif

              <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                {{-- Full Name --}}
                <div class="form-group first">
                  <label for="name">Full Name</label>
                  <input id="name" type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required autofocus>
                  @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                  <label for="email">Email</label>
                  <input id="email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                  @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Phone --}}
                <div class="form-group">
                  <label for="phone_number">Phone Number</label>
                  <input id="phone_number" type="text" name="phone_number"
                    class="form-control @error('phone_number') is-invalid @enderror"
                    value="{{ old('phone_number') }}" required>
                  @error('phone_number')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Password --}}
                <div class="form-group last mb-3">
                  <label for="password">Password</label>

                  <div class="password-wrapper">
                    <input id="password" type="password" name="password"
                      class="form-control @error('password') is-invalid @enderror"
                      required>
                    <span id="togglePassword">
                      <span class="icon-eye" id="toggleIcon"></span>
                    </span>
                  </div>

                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Strength Indicator --}}
                <div class="mb-3">
                  <small id="password-help" class="help-text">Use at least 8 characters including uppercase, numbers, and symbols.</small>
                </div>

                {{-- Terms --}}
                <div class="d-flex mb-4 align-items-center justify-content-between">
                  <label class="control control--checkbox mb-0">
                    <span class="caption">I agree to the Terms & Privacy</span>
                    <input type="checkbox" id="agreeTerms" required>
                    <div class="control__indicator"></div>
                  </label>

                  <a href="{{ route('login') }}" class="forgot-pass">Already have an account?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-pill text-white btn-block btn-primary">
                  Create Account
                </button>

                <span class="d-block text-center my-4 text-muted">or register with</span>

                {{-- Social --}}
                <div class="social-login text-center mb-4">
                  <a href="{{ route('login.google.redirect') }}" class="google">
                    <span class="icon-google mr-3"></span>
                  </a>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      // Password Strength Indicator
      const passwordInput = document.getElementById("password");
      const progressBar = document.getElementById("password-strength");
      const helpText = document.getElementById("password-help");

      passwordInput.addEventListener("input", function() {
        const val = passwordInput.value;
        let strength = 0;

        if (val.length >= 8) strength += 25;
        if (/[A-Z]/.test(val)) strength += 25;
        if (/[0-9]/.test(val)) strength += 25;
        if (/[^A-Za-z0-9]/.test(val)) strength += 25;

        progressBar.style.width = strength + "%";

        if (strength < 50) {
          progressBar.classList.add("bg-danger");
          progressBar.classList.remove("bg-warning", "bg-success");
        } else if (strength < 75) {
          progressBar.classList.add("bg-warning");
          progressBar.classList.remove("bg-danger", "bg-success");
        } else {
          progressBar.classList.add("bg-success");
          progressBar.classList.remove("bg-danger", "bg-warning");
        }
      });

      // --- SKRIP BARU UNTUK SHOW/HIDE PASSWORD ---
      const toggleButton = document.getElementById("togglePassword");
      const passwordField = document.getElementById("password"); // Sudah didefinisikan di atas, tapi kita ambil lagi untuk skop ini
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
      // --- AKHIR SKRIP BARU ---

    </script>
  </body>
</html>
