@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Plantilla HTML</h4>
                  {{-- <p class="card-description"> </p> --}}

                  <div class="row">
                      <div class="col-xs-12 col-sm-3">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                          <p>{{ $plantilla_datos->nombre }}</p>
                        </div>
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="asunto">Asunto <span class="text-danger">*</span></label>
                          <p>{{ $plantilla_datos->asunto }}</p>
                        </div>
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="flujo_ejecucion">Flujo Ejecución <span class="text-danger">*</span></label>
                          <select disabled="" class="form-control text-uppercase" name="flujo_ejecucion" id="flujo_ejecucion" required="">
                              <option value="">SELECCIONAR...</option>
                              <option value="INVITACION"
                              @if('INVITACION'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >INVITACION</option>
                              <option value="CONFIRMACION"
                              @if('CONFIRMACION'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >CONFIRMACION</option>
                              <option value="RECORDATORIO" 
                              @if('RECORDATORIO'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >RECORDATORIO</option>
                              <option value="NOINVITADO" 
                              @if('NOINVITADO'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >NO INVITADO</option>
                              <option value="LEY-27419" 
                              @if('LEY-27419'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >LEY 27419</option>
                              <option value="MAILING" 
                              @if('MAILING'=== $plantilla_datos->flujo_ejecucion)
                                selected 
                              @endif
                              >MAILING</option>

                          </select>
                          {!! $errors->first('flujo_ejecucion', '<span class=error>:message</span>') !!}
                        </div>


                      </div> {{-- end column 1 --}}

                      <div class="col-xs-12 col-sm-9">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="plantillahtml">HTML</label>
                          <div id="plantilla_htmlview" style="background: #f3f3f3;padding: 10px;">
                            {!! $plantilla_datos->plantillahtml !!}
                            
                          </div>
                          <div id="plantilla_htmlview2"></div>
                        </div>

                      </div> {{-- end column 2 --}}
                    </div> {{-- end row --}}
                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <a href="{{ route('plantillaemail.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>
                </div>
              </div>
            </div>
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