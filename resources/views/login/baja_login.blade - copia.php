@extends('layout.home')

@section('content')

  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper bg_fondo2">
          <div class="row justify-content-center">

            {{-- @if($error == "NO" && $datos->vacantes >= $datos->inscritos_invi ) --}}
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card bg-">
                <div class="card-body">
                  <div class="col-md-12 mb-4 text-center">
                    {{-- <img src="{{ asset('images/head_form.png') }}" width="540" alt="head" class="img-responsive"> --}}
                    <img src="{{URL::route('home')}}/images/logo_ticketing.png"  class="img-fluid" width="200" alt="logo tkt">
                  </div>

                  <div id="login_form" class="row justify-content-center">
                    <div class="col-xs-12 col-md-8">
                      <form class="forms-sample" id="login_baja" action="{{ route('caii.baja') }}" method="post">
                      {!! csrf_field() !!}
                      <div class="form-group">
                        {{-- <label class="label">Usuario</label> --}}
                        <div class="input-group">
                          <input id="text" type="text" placeholder="USUARIO" class="form-control h4 border-secondary {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

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
                        <input id="password" type="password" placeholder="*********" class="form-control h4 border-secondary {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        

                          @if ($errors->has('password'))
                              <span class="invalid-feedback">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                      </div>
                    </div>
                    <div class="form-group text-center">
                      <button class="btn btn-dark submit-btn " style="background:#c00000;color:#fff;">{{ __('Ingresar') }}</button>

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
                  </div>

                  
                                    

                  
                </div>
              </div>
            </div>
            {{-- @else

                <div class="row justify-content-center">
                    <div class="col-xs-12 col-md-12">
                      <img src="{{ asset('images/ev/vacante_ing_es.jpg')}}" alt="Vacante lleno" class="text-center">
                    </div>
                  </div>  

            @endif --}}
          </div>
          
          
        </div>
        

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layout.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
   
@endsection

