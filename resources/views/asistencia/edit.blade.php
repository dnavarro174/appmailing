@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Eventos</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('eventos.update', $eventos_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    

                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="descripcion">Descripción</label>
                        <textarea type="text" class="form-control border-primary text-uppercase" id="descripcion" name="descripcion" rows="4" >{{ $eventos_datos->descripcion }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_inicio">Fecha Inicio</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_inicio" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ $eventos_datos->fecha_inicio }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_inicio', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_fin">Fecha Fin</label>
                        <div id="datepicker-popup2" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_fin" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ $eventos_datos->fecha_fin }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_fin', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_evento_id">Tipo de Evento</label>
                        <select class="form-control border-primary text-uppercase" id="tipo_evento_id" name="tipo_evento_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tipo_evento as $datos)
                          <option value="{{ $datos->id }}"
                              @if ($datos->id === $eventos_datos->tipo_evento_id)
                                  selected
                              @endif
                            >{{ $datos->tipo }}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado">
                          <option value="0">SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $eventos_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $eventos_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('eventos.index')}}" class="btn btn-light">Volver al listado</a>
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