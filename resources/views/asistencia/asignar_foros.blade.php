@extends('layout.home')

@section('content')


      <!-- partial -->
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-10 mx-auto">
            
            <h2 class="text-center mb-4" style="font-weight: 500;">Asignar Foros</h2>
            <div class="auto-form-wrapper">
              <form class="forms-sample" id="estudiantesForm" action="{{ route('asistencia.store_foros') }}" method="post">
                {!! csrf_field() !!}
                {{-- <p class="text-center">
                  <img class="text-center" src="{{URL::route('home')}}/images/logo_ticketing_1.png" width="100" alt="Logo Ticketing" />
                </p>
                <h2 class="text-center mb-4 text-black">Registro de Asistencia</h2> --}}
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control" autofocus="" placeholder="DNI" value="{{ old('asistencia') }}" name="dni" id="dni" required="" style="font-size: 16px;">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi mdi-check-circle-outline"></i></span>
                    </div>

                  </div>
                    @if(Session::has('dni'))
                      <p class="alert 
                      @if(Session::has('reg_no'))
                      alert-danger
                      @endif
                      @if(Session::has('reg_si'))
                      alert-success
                      @endif
                      @if(Session::has('reg_ya'))
                      alert-primary
                      @endif

                       mt-2 text-center">El DNI: <strong>{{ Session::get('dni') }}
                        @if(Session::has('reg_no'))
                         NO ESTA REGISTRADO.
                        @endif
                        @if(Session::has('reg_si'))
                        SE HA REGISTRADO SATISFACTORIAMENTE.
                        @endif
                        @if(Session::has('reg_ya'))
                        YA ESTA REGISTRADO.
                        @endif

                       </strong>

                        @if(Session::has('reg_si') or Session::has('reg_ya'))
                        <br>
                        <strong>{{ Session::get('foro_1') }} <br>
                        <strong>{{ Session::get('foro_2') }}</strong> 

                        <br>
                        <a href="storage/confirmacion/2-{{ Session::get('dni') }}.pdf" target="_blank" class="btn btn-danger">Descargar Gafete</a>
                        @endif

                      </p>
                    @endif
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-6">

                    <div class="col-sm-12 col-md-12">
                      <div class="form-grou texto_foros">
                
                                          <div class="form-control2">
                                            <label class="form-radio">
                                              <input type="radio" class="form-check-" name="foro_1" value="6" required="">
                                              <em>Plenaria 1</em>
                                              </label>
                                            
                                          </div>

                                      </div>
                      </div>

                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">

                    <div class="col-sm-12 col-md-12">
                      <div class="form-grou texto_foros">
                
                                          <div class="form-control2">
                                            <label class="form-radio">
                                              <input type="radio" class="form-check-" name="foro_2" value="7" required="">
                                              <em>Plenaria 2</em>
                                              </label>
                                            
                                          </div>

                                      </div>
                      </div>
                      <div class="col-sm-12 col-md-12">
                      <div class="form-grou texto_foros">
                
                                          <div class="form-control2">
                                            <label class="form-radio">
                                              <input type="radio" class="form-check-" name="foro_2" value="8" required="">
                                              <em>Plenaria 3</em>
                                              </label>
                                            
                                          </div>

                                      </div>
                      </div>
                      
                  </div>
                </div>
                

                @if(session()->has('info'))
                      <div class="alert alert-success" role="alert">
                        <h2>{{ session('info') }}</h2>
                        @foreach(session('verAsistencia') as  $key => $value )
                          <p>
                            <strong>Nombre:</strong> {{ $value->nombres .' '. $value->ap_paterno }}  <br>
                            <strong>Programación:</strong> {{ $value->nombre }} <br>
                            <strong>Curso:</strong> {{ $value->nombre_curso }} <br>
                            <strong>Docente:</strong> {{ $value->docente }} <br>
                            <strong>Aula:</strong> {{ $value->aula }} <br>
                            <strong>Piso:</strong> {{ $value->piso }} 
                          </p>
                        @endforeach
                      </div>
                    
                @endif
                <div class="form-group">
                  {{-- <button class="btn btn-primary submit-btn btn-center">Guardar</button> --}}
                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-danger ">Guardar</button>
                  <a href="{{ route('asistencia.asignar_foros')}}" class="btn btn-primary">Limpiar</a>
                  <a href="{{ route('asistencia.index')}}" class="btn btn-light">Volver al listado</a>
                </div>
<style>
.form-control2 label.form-radio{font-weight: bold;font-size: 14px;}
.form-control2 label.form-radio em, .color_verde{color:#21AFAF;font-style: normal;}
.form-control2 label.form-radio span,.color_azul{color:#556685;}
.texto_foros p{padding-left: 25px;}
</style>
               
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
      <!-- main-panel ends -->
   
@endsection