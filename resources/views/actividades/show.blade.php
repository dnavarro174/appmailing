@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Actividades</h4>
                  <p class="card-description">
                  </p>
                    <div class="row">
                      <div class="col-sm-3 form-group">
                        <label class=" col-form-label" for="programaciones_id">Código Programación <span class="text-danger">*</span></label>
                        <select class="form-control border-primary" autofocus name="programaciones_id" id="programaciones_id" disabled="">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cod_prog as $prog)
                              <option value="{{ $prog->codigo }}"
                                @if ($prog->codigo == $actividades_datos->programaciones_id)
                                  selected
                                @endif
                                >
                                {{ $prog->nombre }}</option>
                            @endforeach
                        </select>
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="ponente_id">Ponente <span class="text-danger">*</span></label>
                        <select class="form-control border-primary text-uppercase required" autofocus name="ponente_id" id="ponente_id" disabled="">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cod_prog as $prog))
                              <option value="{{ $prog->id }}"
                                @if ($prog->id === $actividades_datos->programaciones_id)
                                  selected
                                @endif
                                >
                                {{ $prog->nombre }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="descripcion">Descripción</label>
                        <textarea type="text" class="form-control border-primary text-uppercase" id="descripcion" name="descripcion" rows="4" disabled >{{ $actividades_datos->descripcion }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_desde">Fecha Desde</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_desde" class="form-control border-primary text-uppercase" placeholder="01/01/2018" disabled value="{{ $actividades_datos->fecha_desde }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_desde', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_hasta">Fecha Hasta</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_hasta" class="form-control border-primary text-uppercase" placeholder="01/01/2018" disabled value="{{ $actividades_datos->fecha_hasta }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_hasta', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="aforo">Aforo</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="aforo" name="aforo" placeholder="Aforo" disabled value="{{ $actividades_datos->aforo }}">
                        {!! $errors->first('aforo', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Hora Inicio</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="hora_inicio" class="form-control border-primary text-uppercase" placeholder="00:00" disabled value="{{ $actividades_datos->hora_inicio }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('hora_inicio', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Hora Fin</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="hora_final" class="form-control border-primary text-uppercase" placeholder="00:00" disabled value="{{ $actividades_datos->hora_final }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('hora_final', '<span class=error>:message</span>') !!}
                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Ubicación</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="ubicacion" name="ubicacion" placeholder="Ubicación" disabled value="{{ $actividades_datos->ubicacion }}">
                        {!! $errors->first('ubicacion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Inscritos</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="inscritos" name="inscritos" placeholder="Inscritos" disabled value="{{ $actividades_datos->inscritos }}">
                        {!! $errors->first('inscritos', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado" disabled="">
                          <option value="0">SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $actividades_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $actividades_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2" disabled>Guardar</button>
                        <a href="{{ route('actividades.index')}}" class="btn btn-light">Volver al listado</a>
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