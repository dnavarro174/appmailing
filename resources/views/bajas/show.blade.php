@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Pre-Inscritos</h4>
                  <p class="card-description">
                  </p>
                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label class=" col-form-label" for="cboTipDoc">Tipo de documento <span class="text-danger">*</span></label>
                        <select disabled class="form-control border-primary" autofocus name="cboTipDoc" id="cboTipDoc">
                            <option value="0">SELECCIONAR...</option>
                            @foreach($tipo_doc as $tipoDoc)
                              <option value="{{ $tipoDoc->id }}"
                                @if ($tipoDoc->id === $estudiantes_datos->tipo_documento_documento_id)
                                  selected
                                @endif
                                >
                                {{ $tipoDoc->tipo_doc }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-6 form-group">
                        <label class=" col-form-label" for="inputdni">Número de documento <span class="text-danger">*</span></label>
                        <input disabled type="number" class="form-control border-primary" id="inputdni" name="inputdni" placeholder="DNI" value="{{ $estudiantes_datos->dni_doc }}" >
                        {!! $errors->first('inputdni', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputNombres">Nombres</label>
                        <input disabled type="text" class="form-control border-primary" id="inputNombres" name="inputNombres" placeholder="Nombres" value="{{ $estudiantes_datos->nombres }}">
                        {{-- {{ $errors->first('inputNombres') }} --}}
                        {!! $errors->first('inputNombres', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputApe_pat">Apellido Paterno</label>
                        <input disabled type="text" class="form-control border-primary" id="inputApe_pat" name="inputApe_pat" placeholder="Apellido Paterno" value="{{ $estudiantes_datos->ap_paterno }}">
                        {!! $errors->first('inputApe_pat', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputApe_mat">Apellido Materno</label>
                        <input disabled type="text" class="form-control border-primary" id="inputApe_mat" name="inputApe_mat" placeholder="Apellido Materno" value="{{ $estudiantes_datos->ap_materno }}">
                        {!! $errors->first('inputApe_mat', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Grupo</label>
                        <input type="text" class="form-control border-primary" id="inputGrupo" name="inputGrupo" placeholder="Grupo" disabled value="{{ $estudiantes_datos->grupo }}">
                        {!! $errors->first('inputFechaNac', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Cargo</label>
                        <input disabled type="text" class="form-control border-primary" id="inputCargo" name="inputCargo" placeholder="Cargo" value="{{ $estudiantes_datos->cargo }}">
                        {!! $errors->first('inputFechaNac', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Profesión</label>
                        <input disabled type="text" class="form-control border-primary" id="inputProfesion" name="inputProfesion" placeholder="Profesión" value="{{ $estudiantes_datos->profesion }}">
                        {!! $errors->first('inputProfesion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Email</label>
                        <input disabled type="text" class="form-control border-primary" id="inputEmail" name="inputEmail" placeholder="Email" value="{{ $estudiantes_datos->email }}">
                        {!! $errors->first('inputEmail', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Teléfono</label>
                        <input disabled type="text" class="form-control border-primary" id="inputTelefono" name="inputTelefono" placeholder="Teléfono" value="{{ $estudiantes_datos->telefono }}">
                        {!! $errors->first('inputTelefono', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Celular</label>
                        <input disabled type="text" class="form-control border-primary" id="inputCelular" name="inputCelular" placeholder="Celular" value="{{ $estudiantes_datos->celular }}">
                        {!! $errors->first('inputCelular', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div id="cboPais" class="row cboPais">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboDepartamento">Departamento </label>
                        <select disabled class="form-control border-primary text-uppercase" id="cboDepartamento" name="cboDepartamento">
                          <option value="">SELECCIONE</option>
                          @foreach ($departamentos_datos as $ubigeo)
                            <option 
                              value="{{ $ubigeo->ubigeo_id }}"
                              @if ($ubigeo->ubigeo_id === $prov)
                                selected
                              @endif
                              >
                              {{ $ubigeo->nombre }}
                            </option>
                          @endforeach
                        </select>

                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboProvincia">Provincia</label>
                        <select disabled class="form-control border-primary text-uppercase" id="cboProvincia" name="cboProvincia">
                          <option value="">SELECCIONE</option>
                          @foreach ($provincias_datos as $ubigeo)
                            <option 
                              value="{{ $ubigeo->ubigeo_id }}"
                              @if ($ubigeo->ubigeo_id === $dis)
                                selected
                              @endif
                              >
                              {{ $ubigeo->nombre }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboDistrito">Distrito</label>
                        <select disabled class="form-control border-primary text-uppercase" id="cboDistrito" name="cboDistrito">
                          <option value="">SELECCIONE</option>
                          @foreach ($distritos_datos as $ubigeo)
                            <option 
                              value="{{ $ubigeo->ubigeo_id }}"
                              @if ($ubigeo->ubigeo_id === $estudiantes_datos->ubigeo_ubigeo_id)
                                selected
                              @endif
                              >
                              {{ $ubigeo->nombre }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Fecha Nac.</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="inputFechaNac" class="form-control border-primary" placeholder="01/01/2018" disabled value="{{ $estudiantes_datos->fecha_nac }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('inputFechaNac', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-8 form-group">
                        <label class="col-form-label">Dirección</label>
                        <input disabled type="text" class="form-control border-primary" id="inputDireccion" name="inputDireccion" placeholder="Dirección" value="{{ $estudiantes_datos->direccion }}">
                        {!! $errors->first('inputDireccion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboEntidad">Entidad <span class="text-danger">*</span></label>
                        <select disabled class="form-control border-primary" id="cboEntidad" name="cboEntidad">
                          <option value="">SELECCIONE</option>
                          @foreach ($entidades_datos as $datos)
                          <option value="{{ $datos->id }}"
                            @if ($datos->id === $estudiantes_datos->entidades_entidad_id)
                              selected
                            @endif
                            >
                            {{ $datos->entidad }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboSexo">Sexo</label>
                        <select disabled class="form-control border-primary" id="cboSexo" name="cboSexo">
                          <option value="">SELECCIONE</option>
                          <option value="M" 
                            @if ('M' === $estudiantes_datos->sexo)
                              selected
                            @endif 
                          >
                          Masculino</option>
                          <option value="F" 
                            @if ('F' === $estudiantes_datos->sexo)
                              selected
                            @endif
                          >Femenino</option>
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select disabled class="form-control border-primary" id="cboEstado" name="cboEstado">
                          <option>SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $estudiantes_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $estudiantes_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="track">Track</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="track" name="track" placeholder="Track" disabled value="{{ $estudiantes_datos->track }}">
                        {!! $errors->first('track', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="accedio">Accedio</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="accedio" name="accedio" placeholder="Accedio" disabled value="{{ $estudiantes_datos->accedio }}">
                        {!! $errors->first('accedio', '<span class=error>:message</span>') !!}
                      </div>

                    </div>
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2" disabled>Guardar</button>
                        
                        <a href="{{ route('form_confirmacion.index')}}" class="btn btn-light">Volver al listado</a>
                        
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