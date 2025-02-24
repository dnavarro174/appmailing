@extends('layout.home')

@section('content')

<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth  theme-one bg_fondo2">
      <div class="row w-100">
        <div class="col-lg-4 mx-auto">
          
          <div id="login_form" class="auto-form-wrapper">
            <div class="col-md-12 mb-4 text-center">
              {{-- <img src="{{ asset('images/head_form.png') }}" width="540" alt="head" class="img-responsive"> --}}
              <img src="{{URL::route('home')}}/images/logo_ticketing.png"  class="img-fluid pb-4" width="200" alt="logo tkt">
            </div>
            <form class="forms-sample" id="loginCAII" action="{{ route('caii.validacionlogin') }}" method="post">
              {!! csrf_field() !!}
              <div class="form-group">
                {{-- <label class="label">Usuario</label> --}}
                <div class="input-group">
                  <input id="" type="text" maxlength="12" placeholder="USUARIO" class="form-control h4  border border-secondary rounded {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                    @if ($errors->has('username'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                  
                </div>
              </div>

              <div class="form-group">
              {{-- <label class="label">Contraseña</label> --}}
              <div class="input-group">
                <input id="password" type="password" placeholder="*********" class="form-control  border border-secondary rounded h4{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                

                  @if ($errors->has('password'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
              </div>
            </div>
            <div class="form-group text-center">
              <button class="btn btn- submit-btn px-4" style="background:#c00000;color:#fff;">{{ __('Ingresar / Login') }}</button>

              <div class="row mt-3">
                
                @if(Session::has('login'))     {{-- dd(Session::get('test')); --}}
                  <div class="col-md-12 col-md-offset-2" >
                      <p class="alert alert-danger">
                        {{ Session::get('login') }}
                        <br>Telef: +511 200 8430 Anexo: 5515 / 5516
                      </p>
                    </div>
                @endif
                @if(Session::has('login_no'))     {{-- dd(Session::get('test')); --}}
                  <div class="col-md-12 col-md-offset-2" >
                      <p class="alert alert-danger">
                        {{ Session::get('login_no') }}
                      </p>
                    </div>
                @endif
                @if(Session::has('finalizo'))     {{-- dd(Session::get('test')); --}}
                  <div class="col-md-12 col-md-offset-2" >
                      <p class="alert alert-danger">
                        {{ Session::get('finalizo') }}
                      </p>
                    </div>
                @endif
                  
              </div>

            </div>
         

          </form>
          </div>
          
          <p class="footer-text text-center pt-4 text-black">copyright © {{ date('Y') }} Escuela Nacional de Control. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
   
@endsection

