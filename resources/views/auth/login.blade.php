@extends('layout.home')

@section('content')

  <div class="container-scroller bg_fondo2">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper bg-light">
              <form action="{{ route('login') }}" method="POST">
                {!! csrf_field() !!}
                <div class="row justify-content-center">
                  <img src="https://enc-ticketing.org/images/logo_ticketing.png" alt="logo tkt" width="150" class="d-none d-sm-block img-fluid m-3 text-center">
                  <img src="https://enc-ticketing.org/images/logo_ticketing.png" alt="logo tkt" width="150" height="65" class="d-block d-sm-none m-3 text-center">
                </div>
                <div class="form-group">
                  {{-- <label class="label">Usuario</label> --}}
                  <div class="input-group">
                    <input id="email" type="email" placeholder="Usuario" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                      @if ($errors->has('email'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                    
                  </div>
                </div>
                <div class="form-group">
                  {{-- <label class="label">Contraseña</label> --}}
                  <div class="input-group">
                    {{-- <input type="password" name="password" class="form-control" placeholder="*********">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi mdi-check-circle-outline"></i></span>
                    </div> --}}
                    <input id="password" type="password" placeholder="*********" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                      @if ($errors->has('password'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-dark submit-btn btn-block">{{ __('Login') }}</button>

                </div>
                <div class="form-group d-flex justify-content-between">
                  <div class="form-check form-check-flat mt-0">
                    <label class="form-check-label">
                      {{-- <input type="checkbox" class="form-check-input" checked>
                      Mantenerme conectado --}}

                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Mantenerme conectado') }}
                    </label>
                  </div>
                  {{-- <a href="#" class="text-small forgot-password text-black">Olvide la contraseña</a> --}}
                  {{-- <a href="{{ route('password.request') }}" class="text-small forgot-password text-black">{{ __('Olvido la contraseña?') }} --}}
                  </a>
                </div>
                
                {{-- <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">No es un miembro?</span>
                  <a href="register.html" class="text-black text-small">Crear nueva cuenta</a>
                </div> --}}
              </form>
            </div>
            <ul class="auth-footer">
              <li><a class="text-dark" href="#">Conditions</a></li>
              <li><a class="text-dark" href="#">Help</a></li>
              <li><a class="text-dark" href="#">Terms</a></li>
            </ul>
            <p class="footer-text text-center text-black">Copyright © 2018 - {{date('Y')}} Escuela Nacional de Control. All rights reserved.</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
 


@endsection