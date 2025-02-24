@extends('layout.home')

@section('content')

<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth  theme-one bg_fondo2">
      <div class="row w-100">
        <div class="col-lg-5 mx-auto">
          
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card bg-light">
              <div class="card-body">

                <div class="row justify-content-center pt-4">
                  <div class="col-xs-12 col-md-12">
                    <h2 class="h4 text-center mb-4">Baja de Evento</h2>
                    <p class="card-text">
                      Al darse de baja al evento sus datos, actividades elegidas y gafete serán eliminados del sistema.
                    </p>

                    <form class="form-baja" id="login_baja" action="{{ route('caii.baja_store') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                      <div class="form-check form-check-flat">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" required=""><!-- checked="" -->
                            No podré asistir al evento
                          
                        </div>

                      <div class="input-group">
                        <textarea class="form-control" placeholder="Agradeceremos explicar brevemente el motivo" autofocus="" name="dar_baja" maxlength="200" id="" cols="30" rows="3"></textarea>
                        
                      </div>
                    </div>

                    
                  <div class="form-group text-center">
                    <button class="btn btn-dark submit-btn ">{{ __('Enviar') }}</button>

                    <div class="row mt-3">
                      
                     
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
          
          <p class="footer-text text-center pt-4 text-black">copyright © {{ date('Y') }} Escuela Nacional de Control. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
   
@endsection

