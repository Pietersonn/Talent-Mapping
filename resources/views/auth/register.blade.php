<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    {{-- Icomoon icons dari template --}}
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/fonts/icomoon/style.css') }}">

    {{-- CSS Template (harus ada di: public/assets/login-form-05/...) --}}
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login-form-05/css/style.css') }}">

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <title>Register — TalentMapping</title>

    <style>
      .progress{height:6px}.progress-bar{transition:width .25s ease}
      .help-text{font-size:12px;color:#6c757d}
      .control__indicator{top:2px}
    </style>
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
                <h3 class="text-uppercase">Create your <strong>TalentMapping</strong> account</h3>
                <p class="mb-0" style="opacity:.7;">Start your assessment journey</p>
              </div>

              {{-- fallback alert --}}
              @if (session('status'))
                <div class="alert alert-success py-2">{{ session('status') }}</div>
              @endif

              <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                {{-- Name --}}
                <div class="form-group first">
                  <label for="name">Full Name</label>
                  <input id="name" type="text" name="name"
                         class="form-control @error('name') is-invalid @enderror"
                         placeholder="Your full name" value="{{ old('name') }}" required autofocus>
                  @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                  <label for="email">Email</label>
                  <input id="email" type="email" name="email"
                         class="form-control @error('email') is-invalid @enderror"
                         placeholder="your-email@gmail.com" value="{{ old('email') }}" required>
                  @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Password (tanpa confirm) --}}
                <div class="form-group last mb-3">
                  <label for="password">Password</label>
                  <input id="password" type="password" name="password"
                         class="form-control @error('password') is-invalid @enderror"
                         placeholder="Minimum 8 characters" required>
                  @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Strength indicator --}}
                <div class="mb-3">
                  <div class="progress">
                    <div class="progress-bar" id="password-strength" role="progressbar" style="width:0%"></div>
                  </div>
                  <small id="password-help" class="help-text">Password must be at least 8 characters.</small>
                </div>

                {{-- Terms --}}
                <div class="d-sm-flex mb-4 align-items-center">
                  <label class="control control--checkbox mb-3 mb-sm-0">
                    <span class="caption">I agree to the Terms & Privacy</span>
                    <input type="checkbox" id="agreeTerms" required>
                    <div class="control__indicator"></div>
                  </label>

                  <span class="ml-auto">
                    <a href="{{ route('login') }}" class="forgot-pass">Already have an account? Login</a>
                  </span>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-block py-2 btn-primary" id="registerBtn">
                  Create Account
                </button>

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

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      // Password strength
      function scorePassword(pw){
        let s=0; if(!pw) return s;
        if(pw.length>=8) s+=25; if(pw.length>=12) s+=25;
        if(/[a-z]/.test(pw)) s+=12.5;
        if(/[A-Z]/.test(pw)) s+=12.5;
        if(/[0-9]/.test(pw)) s+=12.5;
        if(/[^A-Za-z0-9]/.test(pw)) s+=12.5;
        return Math.min(100,s);
      }
      function updateStrengthUI(v){
        const bar=$('#password-strength'), help=$('#password-help');
        let color='bg-danger', text='Very weak';
        if(v>=75){color='bg-success';text='Strong password';}
        else if(v>=50){color='bg-info';text='Good password';}
        else if(v>=25){color='bg-warning';text='Weak password';}
        bar.removeClass('bg-danger bg-warning bg-info bg-success').addClass(color).css('width',v+'%');
        help.text(text);
      }
      $('#password').on('keyup', function(){ updateStrengthUI(scorePassword($(this).val())); });

      // submit validate (terms only, confirm password tidak ada)
      $('#registerForm').on('submit', function(e){
        if(!$('#agreeTerms').is(':checked')){
          e.preventDefault();
          Swal.fire({icon:'error', title:'Please agree to the Terms & Privacy', toast:true, position:'top-end', timer:4000, showConfirmButton:false});
          return false;
        }
        $('#registerBtn').prop('disabled', true).text('Creating Account...');
      });

      // SweetAlert hooks dari server
      @if (session('success'))
        Swal.fire({ icon:'success', title:@json(session('success')), toast:true, position:'top-end', timer:4000, showConfirmButton:false });
      @endif
      @if (session('error'))
        Swal.fire({ icon:'error', title:@json(session('error')), toast:true, position:'top-end', timer:5000, showConfirmButton:false });
      @endif
      @if ($errors->any())
        Swal.fire({
          icon: 'error',
          title: 'Registration failed',
          html: `{!! '<ul class="text-left mb-0"><li>'.implode('</li><li>', $errors->all()).'</li></ul>' !!}`,
          confirmButtonText: 'OK'
        });
      @endif
    </script>
  </body>
</html>
