<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    {{-- Icomoon icons (from template) --}}
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/fonts/icomoon/style.css') }}">

    {{-- Template CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/css/style.css') }}">

    <title>Login — TalentMapping</title>
  </head>
  <body>

  <div class="d-md-flex half">
    <div class="bg" style="background-image: url('{{ asset('assets/login-form-05/images/bg_1.jpg') }}');"></div>
    <div class="contents">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-12">
            <div class="form-block mx-auto">

              <div class="text-center mb-5">
                <h3 class="text-uppercase"><strong>Talent Mapping</strong></h3>
              </div>

              {{-- optional plain bootstrap alerts (fallback) --}}
              @if (session('status'))
                <div class="alert alert-success py-2">{{ session('status') }}</div>
              @endif

              <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group first">
                  <label for="email">Email</label>
                  <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="your-email@gmail.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                  >
                  @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group last mb-3">
                  <label for="password">Password</label>
                  <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Your Password"
                    required
                  >
                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-sm-flex mb-4 align-items-center">
                  <label class="control control--checkbox mb-3 mb-sm-0">
                    <span class="caption">Remember me</span>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <div class="control__indicator"></div>
                  </label>

                  <span class="ml-auto">
                    @if (Route::has('password.request'))
                      <a href="{{ route('password.request') }}" class="forgot-pass">Forgot Password</a>
                    @endif
                  </span>
                </div>

                <button type="submit" class="btn btn-block py-2 btn-primary">Log In</button>

                <span class="text-center my-3 d-block">or</span>

                <div>
                  <a href="{{ route('login.google.redirect') }}" class="btn btn-block py-2 btn-google">
                    <span class="icon-google mr-3"></span> Login with Google
                  </a>
                </div>

                @if (Route::has('register'))
                  <div class="text-center mt-4">
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
  </div>

    {{-- Template JS --}}
    <script src="{{ asset('assets/login-form-05/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/login-form-05/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/login-form-05/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/login-form-05/js/main.js') }}"></script>

    {{-- SweetAlert2 (gunakan CDN agar halaman login ini mandiri dari layout admin) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert hooks untuk flash & validation errors --}}
    <script>
      // session success
      @if (session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: @json(session('success')),
          confirmButtonText: 'OK'
        });
      @endif

      // session error
      @if (session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Login failed',
          text: @json(session('error')),
          confirmButtonText: 'OK'
        });
      @endif

      // laravel status (mis. link reset password sent)
      @if (session('status'))
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: @json(session('status')),
          confirmButtonText: 'OK'
        });
      @endif

      // validation errors (email/password salah, dsb)
      @if ($errors->any())
        Swal.fire({
          icon: 'error',
          title: 'Login failed',
          html: {!! json_encode('<ul class="text-left mb-0"><li>'.implode('</li><li>', $errors->all()).'</li></ul>') !!},
          confirmButtonText: 'OK'
        });
      @endif
    </script>
  </body>
</html>
