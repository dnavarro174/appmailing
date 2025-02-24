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
      <!-- end menu_user -->
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      
      @include('layout.menu_iz')
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Auditoría Programaciones</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboTipDoc">Código <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="codigo" name="codigo" placeholder="Código" disabled value="{{ $ap_datos->codigo }}" >
                        {!! $errors->first('codigo', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="nombre">Nombre de programación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="nombre" name="nombre" placeholder="Nombre" disabled value="{{ $ap_datos->nombre }}" >
                        {!! $errors->first('nombre', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="tipo">Tipo</label>
                        <input type="text" class="form-control text-uppercase" id="tipo" name="tipo" placeholder="Tipo" disabled value="{{ $ap_datos->tipo }}">
                        {{-- {{ $errors->first('inputNombres') }} --}}
                        {!! $errors->first('tipo', '<span class=error>:message</span>') !!}
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="modalidad">Modalidad</label>
                        <input type="text" class="form-control text-uppercase" id="modalidad" name="modalidad" placeholder="Modalidad" disabled value="{{ $ap_datos->modalidad }}">
                        {!! $errors->first('modalidad', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="nombre_curso">Curso</label>
                        <input type="text" class="form-control text-uppercase" id="nombre_curso" name="nombre_curso" placeholder="Curso" disabled value="{{ $ap_datos->nombre_curso }}">
                        {!! $errors->first('nombre_curso', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="area_tematica">Área Temática</label>
                        <input type="text" class="form-control text-uppercase" id="area_tematica" name="area_tematica" placeholder="Área Temática" disabled value="{{ $ap_datos->area_tematica }}">
                        {!! $errors->first('area_tematica', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="docente">Docente</label>
                        <input type="text" class="form-control text-uppercase" id="docente" name="docente" placeholder="Docente" disabled value="{{ $ap_datos->docente }}">
                        {!! $errors->first('docente', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="aula">Aula</label>
                        <input type="text" class="form-control text-uppercase" id="aula" name="aula" placeholder="Aula" disabled value="{{ $ap_datos->aula }}">
                        {!! $errors->first('aula', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="piso">Piso</label>
                        <input type="text" class="form-control text-uppercase" id="piso" name="piso" placeholder="Piso" disabled value="{{ $ap_datos->piso }}">
                        {!! $errors->first('piso', '<span class=error>:message</span>') !!}
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="nsesiones">N° Sesiones</label>
                        <input type="text" class="form-control text-uppercase" id="nsesiones" name="nsesiones" placeholder="N° Sesiones" disabled value="{{ $ap_datos->nsesiones }}">
                        {!! $errors->first('nsesiones', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_desde">Fecha Desde</label>
                        <div id="datepicker-popup" class="input-group date datepicker">
                          <input type="text" name="fecha_desde" class="form-control text-uppercase" placeholder="01/01/2018" disabled value="{{ $ap_datos->fecha_desde }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_desde', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_hasta">Fecha Hasta</label>
                        <div id="datepicker-popup" class="input-group date datepicker">
                          <input type="text" name="fecha_hasta" class="form-control text-uppercase" placeholder="01/01/2018" disabled value="{{ $ap_datos->fecha_hasta }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_hasta', '<span class=error>:message</span>') !!}
                      </div>
                      
                    </div>


                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="frecuencia">Frecuencia</label>
                        <input fype="text" class="form-control text-uppercase" id="Frecuencia" name="Frecuencia" placeholder="Frecuencia" disabled value="{{ $ap_datos->frecuencia }}">
                        {!! $errors->first('frecuencia', '<span class=error>:message</span>') !!}
                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control" id="cboEstado" name="cboEstado" disabled>
                          <option>SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $ap_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $ap_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2" disabled>Guardar</button>
                        <a href="{{ route('auditoriap.index')}}" class="btn btn-light">Volver al listado</a>
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