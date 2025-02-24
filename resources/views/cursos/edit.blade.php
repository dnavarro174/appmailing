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
                  
                  <h4 class="card-title">Editar Cursos</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                  <form class="forms-sample" id="cursosForm"  action="{{ route('cursos.update', $cursos_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="nom_curso">Curso <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary text-uppercase" id="nom_curso" name="nom_curso" placeholder="Curso" value="{{ $cursos_datos->nom_curso or old('nom_curso') }}" >
                        {!! $errors->first('nom_curso', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="descripcion">Descripción</label>
                        <textarea class="form-control border-primary" name="descripcion" id="descripcion" rows="4">{{ $cursos_datos->descripcion or old('descripcion') }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cat_curso_id">Categoría</label>
                        <select class="form-control border-primary text-uppercase" id="cat_curso_id" name="cat_curso_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($cat_cursos_datos as $datos)
                          <option value="{{ $datos->id }}"
                              @if ($datos->id === $cursos_datos->cat_curso_id)
                                  selected
                              @endif
                            >{{ $datos->categoria }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_id">Tipo de Curso</label>
                        <select class="form-control border-primary text-uppercase" id="tipo_id" name="tipo_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_tipos_datos as $datos)
                          <option value="{{ $datos->tipo_id }}"
                              @if ($datos->tipo_id === $cursos_datos->tipo_id)
                                  selected
                              @endif
                              >{{ $datos->tipo }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="modalidad_id">Modalidades</label>
                        <select class="form-control border-primary text-uppercase" id="modalidad_id" name="modalidad_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_modalidades_datos as $datos)
                          <option value="{{ $datos->modalidad_id }}"
                            @if ($datos->modalidad_id === $cursos_datos->modalidad_id)
                                selected
                            @endif
  
                            >{{ $datos->modalidad }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="sede_id">Sedes</label>
                        <select class="form-control border-primary text-uppercase" id="sede_id" name="sede_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_sedes_datos as $datos)
                          <option value="{{ $datos->sede_id }}"
                            @if ($datos->sede_id === $cursos_datos->sede_id)
                                selected
                            @endif
                            >{{ $datos->sede }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Sesiones</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="sesiones" name="sesiones" placeholder="Sesiones" value="{{ $cursos_datos->sesiones or old('sesiones') }}">
                        {!! $errors->first('sesiones', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Horas Académicas</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="horas_aca" name="horas_aca" placeholder="Horas Académicas" value="{{ $cursos_datos->horas_aca or old('horas_aca') }}">
                        {!! $errors->first('horas_aca', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado">
                          <option value="0">SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $cursos_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $cursos_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('cursos.index')}}" class="btn btn-light">Volver al listado</a>
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