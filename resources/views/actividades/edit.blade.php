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
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Actividades</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('actividades.update', $actividades_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    <div class="row">
                      <div class="col-sm-3 form-group">
                        <label class=" col-form-label" for="programaciones_id">Código Programación <span class="text-danger">*</span></label>
                        <select class="form-control border-primary" autofocus name="programaciones_id" id="programaciones_id">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cod_prog as $prog)
                              <option value="{{ $prog->codigo }}"
                                @if ($prog->codigo == $actividades_datos->programaciones_id)
                                  selected
                                @endif
                                >
                                {{ $prog->codigo }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="ponente_id">Ponente <span class="text-danger">*</span></label>
                        <select class="form-control border-primary text-uppercase required" autofocus name="ponente_id" id="ponente_id">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cod_pon as $ponente)
                              <option value="{{ $ponente->id }}"
                                @if ($ponente->id === $actividades_datos->ponente_id)
                                  selected
                                @endif
                                >
                                {{ $ponente->id .' - '. $ponente->nombre }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="nomactividad">Nombre</label>
                        <input type="text" class="form-control border-primary text-uppercase required" id="nomactividad" name="nomactividad" value="{{ $actividades_datos->nomactividad }}" >
                        {!! $errors->first('nomactividad', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="descripcion">Descripción</label>
                        <textarea type="text" class="form-control border-primary text-uppercase" id="descripcion" name="descripcion" rows="4" >{{ $actividades_datos->descripcion }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_desde">Fecha Desde</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_desde" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ $actividades_datos->fecha_desde }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_desde', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_hasta">Fecha Hasta</label>
                        <div id="datepicker-popup2" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_hasta" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ $actividades_datos->fecha_hasta }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_hasta', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="aforo">Aforo</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="aforo" name="aforo" placeholder="Aforo" value="{{ $actividades_datos->aforo }}">
                        {!! $errors->first('aforo', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Hora Inicio</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="hora_inicio" class="form-control border-primary text-uppercase" placeholder="00:00" value="{{ $actividades_datos->hora_inicio }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('hora_inicio', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="hora_final">Hora Fin</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="hora_final" class="form-control border-primary text-uppercase" placeholder="00:00" value="{{ $actividades_datos->hora_final }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('hora_final', '<span class=error>:message</span>') !!}
                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Ubicación</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="ubicacion" name="ubicacion" placeholder="Ubicación" value="{{ $actividades_datos->ubicacion }}">
                        {!! $errors->first('ubicacion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Inscritos</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="inscritos" name="inscritos" placeholder="Inscritos" value="{{ $actividades_datos->inscritos }}">
                        {!! $errors->first('inscritos', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado">
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
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('actividades.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>

                  </form>
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