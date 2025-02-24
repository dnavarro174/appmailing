@extends('layout.home')

@section('content')

<div class="horizontal-menu bg_fondo" >
    <!-- partial:partials/_navbar.html -->

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- end menu_right -->
      <!-- partial -->

      <div class="main-panel">
        <div class="content-wrapper pt-0" style="background: none;">
          <div class="container">
            <div class="row justify-content-center">{{-- $datos->activo == 2 --}}
              <div class="col-xs-12 col-md-10 col-lg-10 mt-3">
                <form class="forms-sample border-top shadow " id="maestriaForm" action="{{ route('ficha_link.store') }}" method="post" enctype="multipart/form-data" autocomplete="on">

                  {!! csrf_field() !!}

                  <div class="row ">
                    

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card mb-0">

                      <div class="card rounded border" >
                        <div class="card-body">
                          @if($datos->imagen != 1)
                          <h1 class="card-title text-center mb-3 display-4" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
                          <p>
                            {!! $datos->descripcion_form !!}
                          </p>
                          @endif

                          <h4 class="card-title">I. Datos Personales</h4>
                          <p class="card-text">
                             <strong class="text-danger">* Campos obligatorios </strong>
                          </p>

                          <div class="form-group row">
                            <div class="col-sm-12">

                              @if(count($errors)>0)
                                <div class="alert alert-danger">
                                  Error:<br>
                                  <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                  </ul>
                                </div>
                              @endif
                            </div>
                          </div>

                          <div class="row">
                            @if($datos->grupo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="tipo_participante">Tipo de Participante: <span class="text-danger">*</span></label>
                                <select class="form-control" required name="tipo_participante" id="tipo_participante" >
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="CGR">CGR</option>
                                      <option value="OCI-CGR">OCI-CGR</option>
                                      <option value="OCI">OCI</option>
                                      <option value="PÚBLICO EN GENERAL">PÚBLICO EN GENERAL</option>
                                </select>
                              </div>
                            </div>
                            @endif

                            {{-- cod_curso  nom_curso --}}

                            @if($datos->tipo_doc == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="tipo_doc">Tipo de Documento <span class="text-danger">*</span></label>
                                <select class="form-control" required name="tipo_doc" id="cboTipDoc" >
                                  @foreach($tipos as $tipo)
                                  <option {{ old('tipo_doc')==$tipo->id? 'selected' : ''}} value="{{$tipo->id}}">{{$tipo->tipo_doc}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->dni == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">{{--  --}}
                                <label for="dni_doc">DNI / ID <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "8" id="dni_doc" name="dni_doc" required  placeholder="DNI/ID" value="{{ old('dni_doc') }}" />
                              </div>
                            </div>
                            @endif

                            @if($datos->grupo == 1)
                            {{-- <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="grupo">Grupo / Group <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend">
                                    <select class="form-control" required name="grupo" id="grupo" >
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($grupos as $tipo)
                                      <option {{ old('grupo')==$tipo->codigo? 'selected' : ''}} value="{{$tipo->codigo}}">{{$tipo->nombre}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div> --}}
                            @endif

                            {{-- correo posicion 1 --}}
                            

                            @if($datos->nombres == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="nombres">Nombres / Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nombres" name="nombres"  placeholder="Nombres / Name" required value="{{ old('nombres') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_paterno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_paterno">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno"  placeholder="Apellido Paterno/Last Name" required value="{{ old('ap_paterno') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4 ap_materno">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" required  placeholder="Apellido Materno" value="{{ old('ap_materno') }}">
                              </div>
                            </div>
                            @endif
                          </div>

                          

                          <div class="row">
                            <div class="col-sm-12">
                              <p>(1) Los grados y títulos en el extranjero deben estar inscritos o reconocidos por SUNEDU </p>
                              <p>
                                {{-- <a href="#" class="btn-link">+ Añadir Formación Académica</a> --}}
                                <a href="#" class="btn btn-link" id="add_row" ><span>+ Añadir Formación Académica</span></a>
                              </p>
                            </div>
                          </div>

                          <h4 class="card-title mt-4">III. Capacitaciones</h4>

                          <div class="row" id="filas_contenedor3">

                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="nom_capacitaciones">Nombre <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="nom_capacitaciones[]">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="ESPECIALIZACIONGESTION">ESPECIALIZACIÓN EN GESTIÓN EDUCATIVA</option>
                                  <option value="DOCENCIAUNIVERSITARIA">DOCENCIA UNIVERSITARIA</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="tipo_capa">Tipo de Capacitación  <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="tipo_capa"[]>
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="CURSO">CURSO</option>
                                  <option value="DIPLOMADO">DIPLOMADO</option>
                                  <option value="POSGRADO">POSGRADO</option>
                                  <option value="PROGRAMA">PROGRAMA</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="centro_estudios_capa">Centro de Estudios   <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="centro_estudios_capa[]">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="CURSO">CURSO</option>
                                  <option value="DIPLOMADO">DIPLOMADO</option>
                                  <option value="POSGRADO">POSGRADO</option>
                                  <option value="PROGRAMA">PROGRAMA</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="fecha_ini">Fecha Inicio <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                  <input type="date" class="form-control" name="fecha_ini[]" placeholder="{{date('d/m/Y')}}" required value="{{ old('fecha_ini[]') }}">
                                </div>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="fecha_termino">Fecha de Término <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                  <input type="date" class="form-control" name="fecha_termino[]" placeholder="{{date('d/m/Y')}}" required value="{{ old('fecha_termino[]') }}">
                                </div>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="condicion_actual">Condición Actual <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="condicion_actual[]">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="ESTUDIANTE">ESTUDIANTE</option>
                                  <option value="ESTUDIOS CONCLUIDOS">ESTUDIOS CONCLUIDOS</option>
                                  <option value="EGRESADO">EGRESADO</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="horas_cro">Horas Cronologicas <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="horas_cro[]">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="MENORES DE 40 HORAS">MENORES DE 40 HORAS</option>
                                  <option value="MAYORES DE 40 HORAS">MAYORES DE 40 HORAS</option>
                                  <option value="DE TRES MESES A MENOS DE UN AÑO">DE TRES MESES A MENOS DE UN AÑO</option>
                                  <option value="DE MÁS DE UN AÑO">DE MÁS DE UN AÑO</option>
                                </select>
                              </div>
                            </div>
                            @endif

                            {{-- BLOCK ADD Capacitaciones --}}
                            <template id="reg_datos3">
                              <div class="row col-sm-12 pr-0">
                                  <hr class="w-100 border-secondary border-top mt-2 pt-2">
                                  @if($datos->entidad == 1) {{-- aaaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                      <label for="nom_capacitaciones">Nombre <span class="text-danger">*</span></label>
                                      <select  class="form-control" required name="nom_capacitaciones[]">
                                        <option value="">SELECCIONE / CHANGE</option>
                                        <option value="ESPECIALIZACIONGESTION">ESPECIALIZACIÓN EN GESTIÓN EDUCATIVA</option>
                                        <option value="DOCENCIAUNIVERSITARIA">DOCENCIA UNIVERSITARIA</option>
                                      </select>
                                    </div>
                                  </div>
                                  @endif
                                  @if($datos->entidad == 1) {{-- aaaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                      <label for="tipo_capa">Tipo de Capacitación  <span class="text-danger">*</span></label>
                                      <select  class="form-control" required name="tipo_capa"[]>
                                        <option value="">SELECCIONE / CHANGE</option>
                                        <option value="CURSO">CURSO</option>
                                        <option value="DIPLOMADO">DIPLOMADO</option>
                                        <option value="POSGRADO">POSGRADO</option>
                                        <option value="PROGRAMA">PROGRAMA</option>
                                      </select>
                                    </div>
                                  </div>
                                  @endif
                                  @if($datos->entidad == 1) {{-- aaaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                      <label for="centro_estudios_capa">Centro de Estudios   <span class="text-danger">*</span></label>
                                      <select  class="form-control" required name="centro_estudios_capa[]">
                                        <option value="">SELECCIONE / CHANGE</option>
                                        <option value="CURSO">CURSO</option>
                                        <option value="DIPLOMADO">DIPLOMADO</option>
                                        <option value="POSGRADO">POSGRADO</option>
                                        <option value="PROGRAMA">PROGRAMA</option>
                                      </select>
                                    </div>
                                  </div>
                                  @endif
                                  @if($datos->entidad == 1) {{-- aaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group ">
                                      <label for="fecha_ini">Fecha Inicio <span class="text-danger">*</span> </label>
                                      <div class="input-group mb-2">
                                        <input type="date" class="form-control" name="fecha_ini[]" placeholder="{{date('d/m/Y')}}" required value="{{ old('fecha_ini') }}">
                                      </div>
                                    </div>
                                  </div>
                                  @endif
                                  @if($datos->entidad == 1) {{-- aaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group ">
                                      <label for="fecha_termino">Fecha de Término <span class="text-danger">*</span> </label>
                                      <div class="input-group mb-2">
                                        <input type="date" class="form-control" name="fecha_termino[]" placeholder="{{date('d/m/Y')}}" required value="{{ old('fecha_termino') }}">
                                      </div>
                                    </div>
                                  </div>
                                  @endif
                                  @if($datos->entidad == 1) {{-- aaaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                      <label for="condicion_actual">Condición Actual <span class="text-danger">*</span></label>
                                      <select  class="form-control" required name="condicion_actual[]">
                                        <option value="">SELECCIONE / CHANGE</option>
                                        <option value="ESTUDIANTE">ESTUDIANTE</option>
                                        <option value="ESTUDIOS CONCLUIDOS">ESTUDIOS CONCLUIDOS</option>
                                        <option value="EGRESADO">EGRESADO</option>
                                      </select>
                                    </div>
                                  </div>
                                  @endif
                                  @if($datos->entidad == 1) {{-- aaaaa --}}
                                  <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                      <label for="horas_cro">Horas Cronologicas <span class="text-danger">*</span></label>
                                      <select  class="form-control" required name="horas_cro[]">
                                        <option value="">SELECCIONE / CHANGE</option>
                                        <option value="MENORES DE 40 HORAS">MENORES DE 40 HORAS</option>
                                        <option value="MAYORES DE 40 HORAS">MAYORES DE 40 HORAS</option>
                                        <option value="DE TRES MESES A MENOS DE UN AÑO">DE TRES MESES A MENOS DE UN AÑO</option>
                                        <option value="DE MÁS DE UN AÑO">DE MÁS DE UN AÑO</option>
                                      </select>
                                    </div>
                                  </div>
                                  @endif

                                <div class="col-sm-12">
                                  <p><a href="#" class="btn btn-sm btn-danger btn-deleteReg"><span>Quitar</span></a></p>
                                </div>
                              </div>
                              
                            </template>

                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                              <p>
                                <a href="#" id="add_row3" class="btn-link">+ Añadir Capacitaciones Relacionadas al Curso </a>
                              </p>
                            </div>
                          </div>


                          <h4 class="card-title mt-4">IV. Experiencia Laboral</h4>

                          <div class="row" id="filas_contenedor4">

                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="empresa_insti">Empresa / Institución <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="empresa_insti" required  placeholder="Empresa / Institución" value="{{ old('empresa_insti') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="tipo_empresa">Tipo de Empresa <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="tipo_empresa" >
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="PRIVADA">PRIVADA</option>
                                  <option value="ESTATAL">ESTATAL</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="cargo_puesto">Cargo Puesto <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="cargo_puesto" >
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="GERENTE EN EL SISTEMA DE CONTROL GUBERNAMENTAL">GERENTE EN EL SISTEMA DE CONTROL GUBERNAMENTAL</option>
                                  <option value="GERENTE EN LA ADMINISTRACIÓN PÚBLICA">GERENTE EN LA ADMINISTRACIÓN PÚBLICA</option>
                                  <option value="GERENTE EN EL SECTOR PRIVADO">GERENTE EN EL SECTOR PRIVADO</option>
                                  <option value="JEFATURAS">JEFATURAS</option>
                                  <option value="OTROS COMPETENTE A SU PROFESIÓN">OTROS COMPETENTE A SU PROFESIÓN</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="modalidad">Modalidad <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="modalidad" >
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="CONTRATO DE LOCACIÓN DE SERVICIOS">CONTRATO DE LOCACIÓN DE SERVICIOS</option>
                                  <option value="CAS">CAS</option>
                                  <option value="CAP">CAP</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="actividad_desarrollada">Actividad Desarrollada </label>
                                <input type="text" class="form-control text-uppercase" name="actividad_desarrollada"  value="{{ old('actividad_desarrollada') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->email_labor == 1) {{-- aaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="fecha_inicio">Fecha Inicio <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                  <input type="date" class="form-control" name="fecha_inicio" placeholder="{{date('d/m/Y')}}" required value="{{ old('fecha_inicio') }}">
                                </div>
                              </div>
                            </div>
                            @endif
                            @if($datos->email_labor == 1) {{-- aaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="fecha_termino">Fecha de Término <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                  <input type="date" class="form-control" name="fecha_termino" placeholder="{{date('d/m/Y')}}" required value="{{ old('fecha_termino') }}">
                                </div>
                              </div>
                            </div>
                            @endif


                            {{-- BLOCK ADD Capacitaciones --}}
                            <template id="reg_datos4">
                              <div class="row col-sm-12 pr-0">
                                  <hr class="w-100 border-secondary border-top mt-2 pt-2">

                                  

                                <div class="col-sm-12">
                                  <p><a href="#" class="btn btn-sm btn-danger btn-deleteReg"><span>Quitar</span></a></p>
                                </div>
                              </div>
                              
                            </template>


                            
                          </div> {{-- end row --}}

                          <div class="row">
                            
                            <div class="col-sm-12">
                              <p>
                                <a href="#" class="btn-link" id="add_row4">+ Añadir Experiencia Laboral</a>
                              </p>
                            </div>
                          </div>

                          <h4 class="card-title mt-4">V. Experiencia en Docencia</h4>
                          
                          <div class="row">

                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="edoc_institucion">Institución <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="edoc_institucion" id="edoc_institucion">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="UNIVERSIDAD">UNIVERSIDAD</option>
                                  <option value="ESCUELA DE EDUCACIÓN SUPERIOR">ESCUELA DE EDUCACIÓN SUPERIOR</option>
                                  <option value="INSTITUTO">INSTITUTO</option>
                                  <option value="OTROS">OTROS</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="edoc_nombre">Nombre de la Institución  </label>
                                <input type="text" class="form-control text-uppercase" id="edoc_nombre" name="edoc_nombre"  value="{{ old('edoc_nombre') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="edoc_nivel">Nivel <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="edoc_nivel" id="edoc_nivel">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="PREGRADO">PREGRADO</option>
                                  <option value="POSGRADO">POSGRADO</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="edoc_curso">Curso a Cargo </label>
                                <input type="text" class="form-control text-uppercase" id="edoc_curso" name="edoc_curso"  value="{{ old('edoc_curso') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->email_labor == 1) {{-- aaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="edoc_fecha_inicio">Fecha Inicio <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                  <input type="date" class="form-control" id="edoc_fecha_inicio" name="edoc_fecha_inicio" placeholder="{{date('d/m/Y')}}" required value="{{ old('edoc_fecha_inicio') }}">
                                </div>
                              </div>
                            </div>
                            @endif
                            @if($datos->email_labor == 1) {{-- aaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="edoc_fecha_termino">Fecha de Término <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                  <input type="date" class="form-control" id="edoc_fecha_termino" name="edoc_fecha_termino" placeholder="{{date('d/m/Y')}}" required value="{{ old('edoc_fecha_termino') }}">
                                </div>
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12">
                              <p>
                                <a href="#" class="btn-link">+ Añadir Experiencia en Docencia</a>
                              </p>
                            </div>
                            
                          </div>

                          <h4 class="card-title mt-4">Revisar y Seleccionar los Cursos de acuerdo a su experiencia profesional y académica(*) </h4>
                          
                          <div class="row">

                            @if($datos->entidad == 1) {{-- aaaaa --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="edoc_cursos">Elegir Cursos <span class="text-danger">*</span></label>
                                <select  class="form-control" required name="edoc_cursos" id="edoc_cursos">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option value="PREGRADO">PREGRADO</option>
                                </select>
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12">
                              <p>(*) Usted puede seleccionar un máximo de tres (03) cursos</p>
                              <p>
                                <a href="#" class="btn-link">+ Añadir Cursos</a>
                                <a href="#" class="btn-link">- Eliminar</a>
                              </p>
                            </div>
                            
                          </div>

                          <h4 class="card-title mt-4">VI. Declaraciones Juradas</h4>
                          <h6 class="card-title mt-4">CONSIDERACIONES LEGALES / ADMINISTRATIVAS</h6>

                          <div class="row">
                            
                            @if($datos->entidad == 1) {{-- aaaa --}}
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group pl-3">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_1"><span class="h6 pr-1">1.</span> <span class="txtcampo h6 font-weight-normal text-justify">Con registro antecedentes penales. <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_1" id="preg_1-si-1" value="SI"></label>

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_1" id="preg_1-no-1" value="NO"></label>

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_2 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group pl-3">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_2"><span class="h6 pr-1">2.</span> <span class="txtcampo h6 font-weight-normal text-justify">Con registro antecedentes judiciales. <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_2" id="preg_2-si-1" value="SI"></label>

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_2" id="preg_2-no-1" value="NO"></label>

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_3 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group pl-3">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_3"><span class="h6 pr-1">3.</span> <span class="txtcampo h6 font-weight-normal text-justify">Con registro antecedentes policiales.  <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_3" id="preg_3-si-1" value="SI"></label>

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_3" id="preg_3-no-1" value="NO"></label>

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_4 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group pl-3">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_4"><span class="h6 pr-1">4.</span> <span class="txtcampo h6 font-weight-normal text-justify">Estar incluido en un informe de control bajo cualquier tipo de responsabilidad. <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_4" id="preg_4-si-1" value="SI"></label>

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_4" id="preg_4-no-1" value="NO"></label>

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_5 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group pl-3">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_5"><span class="h6 pr-1">5.</span> <span class="txtcampo h6 font-weight-normal text-justify">Sentenciado por incumplimiento a la asistencia familiar. <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_5" id="preg_5-si-1" value="SI"></label>

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_5" id="preg_5-no-1" value="NO"></label>

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_6 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group pl-3">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_6"><span class="h6 pr-1">6.</span> <span class="txtcampo h6 font-weight-normal text-justify">Con registro en la Nómina de Deudores Alimentarios Morosos-REDAM.  <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_6" id="preg_6-si-1" value="SI"></label>

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_6" id="preg_6-no-1" value="NO"></label>

                                </div>
                              </div>
                            </div>
                            @endif

                            
                           
                            @if($datos->terminos == 1)
                            <div class="col-sm-12 col-md-11">
                              <div class="form-check">
                                    <input type="checkbox" id="enc" name="check_auto" class="form-check-input check_click" required>
                                  <label class="form-check-label" for="enc">
                                    He leído y acepto los <a href="#" onclick="eximForm()" data-toggle="modal">Término y Condiciones</a>
                                  
                                  </label>
                                  <p class=" pl-2 text-justify">
                                    Autorizo de manera expresa que mis datos sean cedidos a la Escuela Nacional de Control con la finalidad de poder recibir información de las actividades académicas y culturales
                                  </p>
                                  <p class=" pl-2 text-justify">
                                    La información contenida en el presente documento tiene carácter de Declaración Jurada, para lo cual la Escuela Nacional de Control de la Contraloría General de la República tomará en cuenta la información en ella consignada, reservándose el derecho de llevar a cabo la verificación correspondiente; así como solicitar la acreditación de la misma. 
                                  </p>
                                </div>
                            </div>
                            @endif

                            <input type="hidden" id="eventos_id" name="eventos_id" value="{{ $id_evento }}">
                            <input type="hidden" id="fecha_inicial" name="fecha_inicial" value="{{ $fecha_inicial }}">
                            <input type="hidden" id="fecha_final" name="fecha_final" value="{{ $fecha_final }}">
                            <input type="hidden" id="xemail" name="xemail" value="" >
                            <input type="hidden" id="xcelular" name="xcelular" value="" >



                            <div class="col-sm-12 col-md-12">
                              <div class="form-group ">
                                
                                <div class="col-sm-12 col-md-12 p-4 text-center">
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class="mdi mdi-checkbox-marked-circle "></i>ENVIAR POSTULACIÓN</button>

                                  <div class="bar-loader d-none">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                  </div>

                                </div>
                                
                                <div class="col-sm-12 col-md-12 p-0 mt-3 text-center">
                                  @if($datos->imagen == 1)
                                  <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                                  @endif
                                  
                                </div>
                              </div>
                            </div>
                          </div> {{-- end row --}}

                        </div>
                      </div>
                    </div>


                  </div>

                </form>

                

              </div>
            </div>
          </div>
        </div>

        @if($datos->terminos == 1)
          @include('termino-condiciones.index')
        @endif

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

{{-- <script src="{{ asset('js_a/vendor.bundle.base.js')}}"></script>  
<script src="{{ asset('js_a/vendor.bundle.addons.js')}}"></script> --}}

@endsection

@section('scripts')
<style>
.wizard > .content > .body{position: relative;}
.form-control2 label.form-radio{font-weight: bold;font-size: 14px;}
.form-control2 label.form-radio em{color:#21AFAF;font-style: normal;}
.form-control2 label.form-radio span{color:#556685;}
.texto_foros p{padding-left: 25px;}
.wizard > .content > .body input{display: inline-block;}

h1.card-title{
      font-family: Arial,Helvetica Neue,Helvetica;
    letter-spacing: -1px;
}
.card-body div strong{font-weight: 800;}
</style>

<script>
  $(document).ready(function(){

    $('.dynamic').change(function(){
      if($(this).val() != '')
      {
      var select = $(this).attr("id");
      if(select == "dpto"){
        select = "departamento";
      }
      var value = $(this).val();
      var dependent = $(this).data('dependent');
      var _token = $('input[name="_token"]').val();
      
      $.ajax({
          url:"{{ route('ubigeo.fetch') }}",
          method:"GET",//POST
          //data:{select:select, value:value, _token:_token, dependent:dependent},
          data:{select:select, value:value, dependent:dependent},
          success:function(result)
          {
          $('#'+dependent).html(result);
          }
      })
      }
    });

    $('#dpto').val('');
    $('#dpto').change(function(){
        $('#provincia').val('');
        $('#distrito').val('');
    });

    $('#provincia').change(function(){
        $('#distrito').val('');
    });

    var $form = $('#maestriaForm');
    var $btn = $('#actionSubmit');
    var $loader = $('.bar-loader');

    $($form).submit(function(e){
      //e.preventDefault();
      
      $loader.addClass('d-block');
      $btn.html('Procesando...').prop('disabled','disabled');
      $form.sleep(1000).submit();
      
    });

    $('#add_row').on('click', addRows);
    $(document).on('click', '.btn-deleteReg', removeElement);
      
  });

  /*(function(seconds) {
    var refresh,       
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href = location.href;
            }, seconds * 1000);
        };

    $(document).on('keypress click', function() { intvrefresh() });
    intvrefresh();

  }(60*3)); // define here seconds*/
</script>
@endsection