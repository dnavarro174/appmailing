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
                <form class="forms-sample border-top shadow " id="ddjjForm" action="{{ route('djcgr_link.store') }}" method="post" enctype="multipart/form-data" autocomplete="on" >

                  {!! csrf_field() !!}

                  <div class="row ">
                    @if($datos->imagen == 1)
                      <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                        <div class="card">
                          <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                          
                          <!--card-img-top -->
                          <div class="card-body">
                            <h1 class="card-title text-center mb-3 display-4" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
                            <p>
                              {!! $datos->descripcion_form !!} 
                            </p>

                            @if(Session::has('dni'))
                            <p class="alert alert-danger">{{ Session::get('dni') }}</p>
                            @endif
                            @if(Session::has('dni_registrado'))
                            <p class="alert alert-warning">{{ Session::get('dni_registrado') }}</p>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card mb-0">

                      <div class="card rounded border" >
                        <div class="card-body" >
                          @if($datos->imagen != 1)
                          <h1 class="card-title text-center mb-3 display-4" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
                          <p>
                            {!! $datos->descripcion_form !!}
                          </p>
                          @endif

                          <h4 class="card-title">Datos Personales</h4>
                          <p class="card-text">
                             <strong class="text-danger">* Campos obligatorios </strong><br>
                             ●  Es responsabilidad del solicitante consignar sus datos correctamente.<br>
                             ●  Solo colaboradores con contrato CAP y CAS pueden postular a una beca.
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
                            

                            <div class="col-sm-12 col-md-3">
                              <div class="form-group ">
                                <label for="codigo_colaborador">Código del Colaborador <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "5" id="codigo_colaborador" name="codigo_colaborador" required  placeholder="Código del Colaborador" value="{{ $datos_reg->codigo?? old('codigo_colaborador') }}" />
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-3">
                              <div class="form-group ">
                                <label for="categ">Categoría <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" id="categ" name="categ" required  placeholder="Categoría" value="{{ $datos_reg->categoria?? old('categ') }}" />
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-3">
                              <div class="form-group ">
                                <label for="unidad_organica">Unidad Orgánica <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" type="text" id="unidad_organica" name="unidad_organica" required  placeholder="Unidad Orgánica" value="{{ $datos_reg->unidad_organica?? old('unidad_organica') }}" />
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            {{-- <p>
                              <pre>{{$datos_reg->curso}}</pre>
                            </p> --}}
                            @if($datos->nom_curso == 1)
                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                <label for="tipo_ins">Modalidad <span class="text-danger">*</span></label>
                                <select class="form-control dynamic_tipo" required name="tipo_ins" id="tipo_ins2">
                                  <option value="">SELECCIONE</option>
                                  @foreach($modalidad as $mod)
                                    @if(isset($_GET['t']))
                                    <option value="{{$mod->modalidad}}" {{ $mod->modalidad==$datos_reg->curso->modalidad ? 'selected' : ''}}>{{$mod->modalidad}}</option>
                                    @else
                                    <option {{ $mod->modalidad==old('tipo_ins')? 'selected' : ''}} value="{{$mod->modalidad}}">{{$mod->modalidad}}</option>                                  
                                    @endif
                                  @endforeach
                                  
                                </select>
                                <input type="hidden" name="modalidad" id="modalidad" value="{{ old('modalidad') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->nom_curso == 1)
                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                <label for="nom_curso">Nombre Curso <span class="text-danger">*</span></label>
                                
                                <select class="form-control" required name="nom_curso" id="nom_curso">
                                  <option value="">SELECCIONE</option>
                                  @if($datos_reg)
                                  @foreach($cursos as $curso)
                                  <option {{ $curso->id==$datos_reg->curso_id? 'selected' : ''}} value="{{$curso->id}}">{{$curso->nom_curso}}</option>
                                  @endforeach
                                  @endif
                                </select>
                                <input type="hidden" name="nombre_curso" id="nombre_curso" value="{{ $datos_reg->curso_id??old('nombre_curso') }}">
                                <input type="hidden" name="t" value="{{isset($_GET['t'])?$_GET['t'] : ''}}">
                                <input type="hidden" name="d" value="{{isset($_GET['d'])?$_GET['d'] : ''}}">
                                <input type="hidden" name="de" value="{{isset($_GET['de'])?$_GET['de'] : ''}}">
                                <input type="hidden" name="tpo" value="2">
                              </div>
                            </div>
                            @endif

                            @if($datos->cod_curso == 1)
                            
                            <div class="col-sm-12 col-md-2">
                              <div class="form-group ">
                                <label for="cod_curso">Código Curso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cod_curso" name="cod_curso"  placeholder="COD.CURSO" required value="{{ $datos_reg->curso->cod_curso??old('cod_curso') }}">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-2">
                              <div class="form-group ">
                                <label for="fech_ini">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="fech_ini" name="fech_ini" required value="{{ $datos_reg->curso->fech_ini??old('fech_ini') }}" placeholder="dd/mm/yyyy">
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-2">
                              <div class="form-group ">
                                <label for="fech_fin">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="fech_fin" name="fech_fin" required value="{{ $datos_reg->curso->fech_fin??old('fech_fin') }}" placeholder="dd/mm/yyyy">
                              </div>
                            </div>
                            @endif

                          </div>
                          <div class="row">

                            @if($datos->tipo_doc == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="tipo_doc">Tipo de Documento <span class="text-danger">*</span></label>
                                <select class="form-control" required name="tipo_doc" id="cboTipDoc" class="codigo_cel">
                                  @foreach($tipos as $tipo)
                                    @if(isset($_GET['t']))
                                    <option {{ $tipo->id==$datos_reg->tipo_documento_documento_id? 'selected' : ''}} value="{{$tipo->id}}">{{$tipo->tipo_doc}}</option>
                                    @else
                                    <option {{ $tipo->id==old('tipo_doc')? 'selected' : ''}} value="{{$tipo->id}}">{{$tipo->tipo_doc}}</option>                                  
                                    @endif
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->dni == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="dni_doc">DNI / ID <span class="text-danger">*</span></label>
                                <input class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "8" id="dni_doc2" name="dni_doc" required  placeholder="DNI/ID" value="{{ $datos_reg->dni_doc?? old('dni_doc') }}" />
                              </div>
                            </div>
                            @endif

                            @if($datos->nombres == 1)
                            @php 
                            if(isset($datos_reg->curso->valor_capa))
                             $valor = number_format($datos_reg->curso->valor_capa,2);
                            else {
                              $valor = 0;
                            }
                            @endphp
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="valor_capacitacion">Valor de la capacitación <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase text-right valor_capacitacion" id="valor_capacitacion" name="valor_capacitacion" readonly  placeholder="S/" required value="{{ $valor?? old('valor_capacitacion') }}">
                                <input type="hidden" class="valor_capacitacion" id="valor_capa" name="valor_capa" value="{{ $valor?? old('valor_capacitacion') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->grupo == 2)
                            <div class="col-sm-12 col-md-4"> {{-- NO SIRVE --}}
                              <div class="form-group">
                                <label for="grupo">Grupo / Group <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend">
                                    <select class="form-control" required name="grupo" id="grupo" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($grupos as $tipo)
                                      <option {{ old('grupo')==$tipo->codigo? 'selected' : ''}} value="{{$tipo->codigo}}">{{$tipo->nombre}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->nombres == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="nombres">Nombres / Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nombres" name="nombres"  placeholder="Nombres / Name" required value="{{ $datos_reg->nombres?? old('nombres') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_paterno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_paterno">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno"  placeholder="Apellido Paterno/Last Name" required value="{{ $datos_reg->ap_paterno?? old('ap_paterno') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4 ap_materno">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" required  placeholder="Apellido Materno" value="{{ $datos_reg->ap_materno?? old('ap_materno') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->pais == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="pais">País / Country <span class="text-danger">*</span></label>
                                    <select class="form-control" required name="pais" id="pais" class="pais text-uppercase">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="PERU">PERU</option>
                                      @foreach($countrys as $country)
                                        @if($datos_reg)
                                        <option value="{{$country->name}}" {{$datos_reg->pais == $country->name?'selected':''}}>{{$country->name}}</option>
                                        @else
                                        <option value="{{$country->name}}" {{old('pais') == $country->name?'selected':''}}>{{$country->name}}</option>

                                        @endif
                                      @endforeach
                                    </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->departamentos == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="region">Departamentos / Departments <span class="text-danger required_camp">*</span></label>
                                  <select class="form-control text-uppercase dynamic" id="dpto" name="departamento" data-dependent='provincia'>{{-- required --}}
                                    <option value="">SELECCIONE / CHANGE</option>
                                    @if($datos->pais == 1)
                                      @foreach($departamentos as $dep)
                                        @if($datos_reg)
                                        <option {{ $datos_reg->region==$dep->nombre? 'selected' : ''}} value="{{$dep->nombre}}">{{$dep->nombre}}</option>
                                        @else
                                        <option {{ old('departamento')==$dep->nombre? 'selected' : ''}} value="{{$dep->nombre}}">{{$dep->nombre}}</option>
                                        @endif
                                      @endforeach
                                    @endif
                                  </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->provincia == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="provincia">Provincia / Province <span class="text-danger required_camp" id="required_provincia">*</span></label>
                                  <select class="form-control text-uppercase dynamic" id="provincia" name="provincia" data-dependent='distrito'>{{-- required --}}
                                    <option value="">SELECCIONE / CHANGE</option>
                                    @if($datos_reg)
                                    <option value="{{$datos_reg->provincia}}" selected>{{$datos_reg->provincia}}</option>
                                    @else
                                    <option value="{{ old('provincia') }}">{{ old('provincia') }}</option>
                                    @endif
                                  </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->distrito == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="distrito">Distrito / District <span class="text-danger required_camp" id="required_distrito">*</span></label>
                                  <select class="form-control text-uppercase" id="distrito" name="distrito" >{{-- required --}}
                                    <option value="">SELECCIONE / CHANGE</option>
                                    @if($datos_reg)
                                    <option value="{{$datos_reg->distrito}}" selected>{{$datos_reg->distrito}}</option>
                                    @else
                                    <option value="{{ old('distrito') }}">{{ old('distrito') }}</option>
                                    @endif
                                  </select>
                              </div>
                            </div>
                            @endif


                            @if($datos->email == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-group ">
                                <label for="email">Correo electrónico debe coincidir con el registrado en el SIGE  <span class="text-danger">*</span> {{-- <a href="#" id="editEmail" style='display:none;'>Editar</a> --}}</label>
                                <input type="email" class="form-control" id="email2" name="email"  placeholder="Correo electrónico debe coincidir con el registrado en el SIGE" value="{{$datos_reg->email ?? old('email')}}" required>

                                
                                {{-- <div class="input-group mb-2">
                                  <input type="text" class="form-control" id="email" name="email" placeholder="CORREO" required value="{{ old('email') }}">
                                  <div class="input-group-prepend">
                                    <select class=" form-control" required name="email_dominio" id="email_dominio">
                                      <option value="">SELECCIONE</option>
                                      @foreach($dominios as $dominio)
                                      <option {{ old('email_dominio')==$dominio->dominio? 'selected' : ''}} value="{{$dominio->dominio}}">{{$dominio->dominio}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <span class="text-danger small pt-2 d-none" id="salida"></span> 
                                </div> --}}
                              </div>

                            </div>
                            @endif

                            @if($datos->email_labor == 2) {{-- ocultar --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="email_labor">Correo electrónico institucional <span class="text-danger">*</span> <a href="#" id="editEmail" style='display:none;'>Editar</a></label>

                                {{-- <label class="sr-only" for="email_labor">Email</label> --}}
                                <div class="input-group mb-2">
                                  <input type="text" class="form-control" id="email_labor" name="email_labor" placeholder="CORREO" required value="{{ old('email_labor') }}">
                                  {{-- <div class="input-group-prepend">
                                    <select class=" form-control" required name="email_dominio" id="email_dominio">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($dominios as $dominio)
                                      <option {{ old('email_dominio')==$dominio->dominio? 'selected' : ''}} value="{{$dominio->dominio}}">{{$dominio->dominio}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <span class="text-danger small pt-2 d-none" id="salida"></span> --}}
                                </div>
                              </div>

                            </div>
                            @endif

                            @if($datos->direccion == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="direccion">Dirección / Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="direccion" name="direccion" required  placeholder="Dirección" value="{{ $datos_reg->direccion??old('direccion') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->celular == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="codigo_cel">Código del País / Zip Code <span class="text-danger">*</span></label>
                                <select class="form-control text-uppercase" required name="codigo_cel" id="codigo_cel" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="51" selected="">PERU</option>
                                      @foreach($countrys as $country)
                                      <option value="{{$country->phonecode}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="celular">Celular / Mobile <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "9" id="celular" name="celular"  placeholder="999888777" value="{{ $datos_reg->celular ??old('celular') }}" required>
                              </div>
                            </div>
                            @endif



                            @if($datos->grupo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="grupo">En condición de colaborador (a) en  <span class="text-danger">*</span></label>
                                <select style="width:100%;" class="form-control" required name="grupo" id="grupo" class="codigo_cel">
                                  <option value="">SELECCIONE / CHANGE</option>
                                    @if($datos_reg)
                                    <option value="CGR" {{"CGR"==$datos_reg->grupo?'selected':''}}>CONTRALORÍA GENERAL DE LA REPÚBLICA</option>
                                    <option value="OCI" {{"OCI"==$datos_reg->grupo?'selected':''}}>ÓRGANO DE CONTROL INSTITUCIONAL</option>
                                    @else
                                    <option value="CGR" {{"CGR"==old('grupo')?'selected':''}}>CONTRALORÍA GENERAL DE LA REPÚBLICA</option>
                                    <option value="OCI" {{"OCI"==old('grupo')?'selected':''}}>ÓRGANO DE CONTROL INSTITUCIONAL</option>
                                    @endif
                                  
                                  {{-- <option value="CGR-OCI">CGR ASIGNADO A UN OCI</option> --}}

                                </select>
                              </div>
                            </div>
                            @endif

                            

                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="organizacion">Lugar donde labora / Place where he works </label>
                                <input type="text" class="form-control text-uppercase" id="organizacion" name="organizacion" placeholder="Entidad / Entity" value="{{ $datos_reg->organizacion??old('organizacion') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="cargo">Cargo / Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cargo" name="cargo" required  placeholder="Cargo/Charge" value="{{ $datos_reg->cargo??old('cargo') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->gradoprof == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="gradoprof">Nivel <span class="text-danger">*</span></label>
                                <select style="width:100%;" class="form-control text-uppercase" required name="gradoprof" id="gradoprof" class="codigo_cel">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  @if($datos_reg)
                                  <option value="TÉCNICO" {{"TÉCNICO"==$datos_reg->gradoprof?'selected':''}}>TÉCNICO</option> 
                                  <option value="PROFESIONAL" {{"PROFESIONAL"==$datos_reg->gradoprof?'selected':''}}>PROFESIONAL</option>
                                  @else
                                  <option value="TÉCNICO" {{"TÉCNICO"==old('gradoprof')?'selected':''}}>TÉCNICO</option> 
                                  <option value="PROFESIONAL" {{"PROFESIONAL"==old('gradoprof')?'selected':''}}>PROFESIONAL</option>
                                  @endif
                                  
                                  <!-- <option value="FUNCIONARIO">FUNCIONARIO</option>  -->
                                </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->discapacidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="moda_contractual">Modalidad Contractual <span class="text-danger">*</span></label>
                                <select style="width:100%;" class="form-control text-uppercase" required name="moda_contractual" id="moda_contractual" class="codigo_cel" required>
                                  <option value="">SELECCIONE / CHANGE</option>
                                  @if($datos_reg)
                                  <option value="728" {{"728"==$datos_reg->moda_contractual?'selected':''}}>D.L. N° 728</option> 
                                  <option value="1057" {{"1057"==$datos_reg->moda_contractual?'selected':''}}>D.L. N° 1057 (CAS)</option>
                                  <option value="276" {{"276"==$datos_reg->moda_contractual?'selected':''}}>D.L. N° 276</option>
                                  @else
                                  <option value="728" {{"728"==old('moda_contractual')?'selected':''}}>D.L. N° 728</option> 
                                  <option value="1057" {{"1057"==old('moda_contractual')?'selected':''}}>D.L. N° 1057 (CAS)</option>
                                  <option value="276" {{"276"==old('moda_contractual')?'selected':''}}>D.L. N° 276</option>
                                  @endif
                                  
                                </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->opc_0 == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="fecha_inicio">Fecha Inicio de Contrato <span class="text-danger">*</span></label>
                                <input type="date" class="form-control text-uppercase" id="fecha_inicio" name="fecha_inicio"  placeholder="" required 
                                  @if($datos_reg)
                                  value="{{ \Carbon\Carbon::parse($datos_reg->fecha_inicio)->format('Y-m-d')??old('fecha_inicio') }}">
                                  @else
                                  value="{{old('fecha_inicio') }}">
                                  @endif
                              </div>
                            </div>
                            @endif

                            @if($datos->opc_1 == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group " id="div_ct">
                                <label for="fecha_fin">Fecha Fin de Contrato <span class="text-danger">*</span></label>
                                <input type="date" class="form-control text-uppercase" id="fecha_fin" name="fecha_fin"  placeholder="" required 
                                  @if($datos_reg)
                                  value="{{ \Carbon\Carbon::parse($datos_reg->fecha_fin)->format('Y-m-d')??old('fecha_fin') }}"> 
                                  @else
                                  value="{{ old('fecha_fin') }}"> 
                                  @endif
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-4"> {{-- aaa valid campo --}}
                              <div class="form-check mb-4">
                                  <input type="checkbox" id="si_cgr" name="si_cgr" class="form-check-input check_click" value="1" 
                                  @if($datos_reg)
                                  {{"1"==$datos_reg->contrato?'checked':''}}
                                  @else
                                  {{"1"==old('si_cgr')?'checked':''}}
                                  @endif
                                  >
                                  <label class="form-check-label" for="si_cgr">Contrato Indeterminado </label>
                              </div>
                            </div>
                            @endif

                            
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="foto" title="Por favor adjuntar su firma">Adjuntar la foto de tu firma en formato jpg igual que en tu dni. Tamaño max. 70 KB <span class="mdi mdi-upload"></span> <span class="text-danger">*</span>
                                </label>
                                  <input id="foto" accept="image/jpg, image/jpeg" class="form-control" title="Por favor adjuntar su firma" type="file" name="foto" value="{{ old('foto') }}" required>
                                  @if($errors->has('foto'))
                                    <div class="error">{{ $errors->first('foto') }}</div>
                                  @endif
                              </div>
                            </div>
                            {{-- <a href="#" class="open_modal" data-id="modal_open" onclick="openModal()" data-toggle="modal">ver modelo</a> --}}


                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title my-4">DECLARO BAJO JURAMENTO LO SIGUIENTE: </h4>
                            </div>

                            @if($datos->preg_1 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group ">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_1"><span class="number pr-1">1.</span> <span class="txtcampo h6 font-weight-normal text-justify">Haber sido sancionado(a) por la comisión de falta o infracción grave o muy grave, de carácter disciplinario o funcional, en la Contraloría General de la República, ni estar en el Registro Nacional de Sanciones contra Servidores Civiles <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_1" id="preg_1-si-1" value="SI" 
                                  @if($datos_reg)
                                  {{"SI"==$datos_reg->preg_1?'checked':''}}></label>
                                  @else
                                  {{"SI"==old('preg_1')?'checked':''}}></label>
                                  @endif

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_1" id="preg_1-no-1" value="NO" 
                                  @if($datos_reg)
                                  {{"NO"==$datos_reg->preg_1?'checked':''}}></label>
                                  @else
                                  {{"NO"==old('preg_1')?'checked':''}}></label>
                                  @endif

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_2 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group ">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_2"><span class="number pr-1">2.</span> <span class="txtcampo h6 font-weight-normal text-justify">Ser funcionario de confianza <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_2" id="preg_2-si-1" value="SI" 
                                  @if($datos_reg)
                                  {{"SI"==$datos_reg->preg_2?'checked':''}}></label>
                                  @else
                                  {{"SI"==old('preg_2')?'checked':''}}></label>
                                  @endif

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_2" id="preg_2-no-1" value="NO" 
                                    @if($datos_reg)
                                    {{"NO"==$datos_reg->preg_2?'checked':''}}></label>
                                    @else
                                    {{"NO"==old('preg_2')?'checked':''}}></label>
                                    @endif

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_3 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group ">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_3"><span class="number pr-1">3.</span> <span class="txtcampo h6 font-weight-normal text-justify">Mantener deudas actualmente exigibles con la Escuela Nacional de Control <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_3" id="preg_3-si-1" value="SI" 
                                  @if($datos_reg)
                                  {{"SI"==$datos_reg->preg_3?'checked':''}}></label>
                                  @else
                                  {{"SI"==old('preg_3')?'checked':''}}></label>
                                  @endif

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_3" id="preg_3-no-1" value="NO" 
                                  @if($datos_reg)
                                  {{"NO"==$datos_reg->preg_3?'checked':''}}></label>
                                  @else
                                  {{"NO"==old('preg_3')?'checked':''}}></label>
                                  @endif

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_4 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group ">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_4"><span class="number pr-1">4.</span> <span class="txtcampo h6 font-weight-normal text-justify">Haber sido sentenciado (a) por incumplimiento a la asistencia alimentaria, ni figurar en el Registro de Deudores Alimentarios Morosos - REDAM <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_4" id="preg_4-si-1" value="SI" 
                                  @if($datos_reg)
                                  {{"SI"==$datos_reg->preg_4?'checked':''}}></label>
                                  @else
                                  {{"SI"==old('preg_4')?'checked':''}}></label>
                                  @endif

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_4" id="preg_4-no-1" value="NO" 
                                  @if($datos_reg)
                                  {{"NO"==$datos_reg->preg_4?'checked':''}}></label>
                                  @else
                                  {{"NO"==old('preg_4')?'checked':''}}></label>
                                  @endif

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_5 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group ">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_5"><span class="number pr-1">5.</span> <span class="txtcampo h6 font-weight-normal text-justify">Haber sido condenado (a) por delito doloso con sentencia de autoridad de cosa juzgada, ni registrar antecedentes policiales, judiciales ni penales vigentes <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_5" id="preg_5-si-1" value="SI" 
                                  @if($datos_reg)
                                  {{"SI"==$datos_reg->preg_5?'checked':''}}></label>
                                  @else
                                  {{"SI"==old('preg_5')?'checked':''}}></label>
                                  @endif

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_5" id="preg_5-no-1" value="NO" 
                                  @if($datos_reg)
                                  {{"NO"==$datos_reg->preg_5?'checked':''}}></label>
                                  @else
                                  {{"NO"==old('preg_5')?'checked':''}}></label>
                                  @endif

                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->preg_6 == 1)
                            <div class="col-sm-9 col-md-9">
                              <div class="form-group ">
                                <label class="d-flex flex-row bd-highlight mb-3" for="preg_6"><span class="number pr-1">6.</span> <span class="txtcampo h6 font-weight-normal text-justify">Haber sido desaprobado en alguna de las actividades académicas impartidas por la ENC, en la que haya sido beneficiado con una beca en el año en curso. <em class="text-danger">*</em></span> </label>
                                  
                              </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                              <div class="form-group">
                                <div class="txt_center">

                                  <label class="px-4 pt-2 number">Si <input type="radio" required name="preg_6" id="preg_6-si-1" value="SI" 
                                  @if($datos_reg)
                                  {{"SI"==$datos_reg->preg_6?'checked':''}}></label>
                                  @else
                                  {{"SI"==old('preg_6')?'checked':''}}></label>
                                  @endif

                                  <label class="px-4 pt-2 number">No <input type="radio" required name="preg_6" id="preg_6-no-1" value="NO" 
                                  @if($datos_reg)
                                  {{"NO"==$datos_reg->preg_6?'checked':''}}></label>
                                  @else
                                  {{"NO"==old('preg_6')?'checked':''}}></label>
                                  @endif

                                </div>
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-11 col-md-11">
                              <div class="form-group text-justify border-top pt-4 pl-4">
                                <h4>IMPORTANTE:</h4>
                                
                                <p class="mb-0">                                  
                                  ● Antes de marcar la opción "enviar", verifique los datos registrados en el documento.<br>
                                  ● En caso de obtener la condición de desaprobado o no alcanzar con la calificación mínima requerida, corresponderá devolver el valor de la capacitación o el remanente, según corresponda.<br>
                                  ● Al seleccionar la opción “enviar”, usted está aceptando su participación al curso o programa. De acceder a la beca se le enviará al correo registrado su Declaración Jurada y Carta de compromiso generada.<br>
                                  ● Al registrar su preinscripción a una beca en la ENC, usted está declarando haber leído las Condiciones del participante (SIGE) y los <a href="https://enc-ticketing.org/comunicaciones/enconocimiento/academico/requisitos.pdf" target="_blank">requisitos para postular</a> a la beca.
                                </p>
                                {{-- <p>
                                  ● Al llenar esta Declaración Jurada <span class="text-danger">no se requiere</span> enviarla al correo encinscripcniones@contraloria.gob.pe, encmooc@enc.edu.pe. Solo debe hacer clic en el botón enviar una vez todo este llenado correctamente.
                                </p> --}}
                                  
                              </div>
                            </div>

                            @if($datos->terminos == 1)
                            <div class="col-sm-12 col-md-11">
                              <div class="form-check">
                                    <input type="checkbox" id="check_auto_1" name="check_auto_1" class="form-check-input check_click" required>
                                  <label class="form-check-label" for="check_auto_1">
                                    Este formulario tiene carácter de declaración jurada, en virtud del principio de veracidad establecido en el numeral 1.7 del Art. IV del Título preliminar del Texto Único Ordenado de la Ley N° 27444, Ley del Procedimiento Administrativo General, sujetándose a las responsabilidades civiles, penales y administrativas que corresponda, en caso de que mediante cualquier acción de verificación posterior se compruebe su falsedad.
                                  </label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-11">
                              <div class="form-check">
                                    <input type="checkbox" id="check_auto" name="check_auto" class="form-check-input check_click" required>
                                  <label class="form-check-label" for="check_auto">
                                    He leído y acepto los <a href="#" onclick="eximForm()" data-toggle="modal">Término y Condiciones</a>
                                  
                                </label>
                                  <span class="small" style="position: relative;right: -9px;">
                                    Autorizo de manera expresa que mis datos sean cedidos a la Escuela Nacional de Control con la finalidad de poder recibir información de las actividades académicas y culturales
                                  </span>
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
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class="mdi mdi-checkbox-marked-circle "></i>ENVIAR</button>

                                  <div class="bar-loader w-50 p-3 d-none" style="font-weight: bold;">
                                    Cargando, por favor espere...
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
  <div class="modal fade modal_open" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document"> {{-- modal-lg --}}
      <div class="modal-content">
        
        <div class="modal-header d-flex">
          
        </div>
        <div class="modal-body pt-0 pb-0">
            <div class="form-group row">
              <div class="col-md-12">
                <div class="g-lista text-center">
                    <img src="{{url('images/g/foto_inscripcion_mcg.jpg')}}" alt="foto inscripcion mcg" width="547">
                </div>

              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>

  <!-- container-scroller -->

@endsection

@section('scripts')
<style>
.swal-title{font-size: 21px;}
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
    //$('#tipo_ins2 option[value=""]').attr("selected", "selected");
    if($('#tipo_ins2').val()=="")
      $('#tipo_ins2').val('');
    //alert('tiene valor');
    
    // Get Tipo Inscripción
    $('.dynamic_tipo').change(function(){
      if($(this).val() != '')
      {
      var evento_id = $('#eventos_id').val();
      var mod = $(this).val();
      $.ajax({
          url:"{{ route('tipo.inscripcion') }}",
          method:"GET",
          data:{evento_id:evento_id, mod:mod},
          success:function(result)
          {
          $('#nom_curso').html(result);
          $('#cod_curso').val("");
          $('#fech_ini').val("");
          $('#fech_fin').val("");
          }
      })
      }
    });

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

    var emails = [];
    var $options = $('#email_dominio option');
    if($options.length){
      $options.each(function(e){
        var $this = $(this);
        var text = $this.text();
        emails.push(text);
      });
    }

    // ACTIVAR HIDDEN EMAIL Y CEL
    var email = $('#email');
    var email_dominio = $('#email_dominio');
    var celular = $('#celular');
    var editCel = $('#editCel');
    var editEmail = $('#editEmail');

    $('#editCel').click(function (e){
      e.preventDefault();
      editCel.css('display','none');
      celular.attr('disabled',false).attr('type','number').val('');
    });

    $('#editEmail').click(function (e){
      e.preventDefault();
      editEmail.css('display','none');
      email.attr('disabled',false).val('');
    });

    var $codcurso = $('#cod_curso');

    if($codcurso.val()==''){
      //$('#dpto').val('');
    }

    $('#dpto').change(function(){
        $('#provincia').val('');
        $('#distrito').val('');
    });

    $('#provincia').change(function(){
        $('#distrito').val('');
    });

    var $form   = $('#ddjjForm');
    var $btn    = $('#actionSubmit');
    var $loader = $('.bar-loader');

    $($form).submit(function(e){
      e.preventDefault();

      swal({
          title: "¿Estas seguro de enviar el formulario?",
          text: "Al llenar este formulario se están generando los siguientes documentos: (1) Declaración Jurada (2) Carta de Compromiso. De acceder a la beca, se le enviará por correo electrónico estos documentos firmados electrónicamente por su persona.",
          icon: "warning",
          buttons: ["NO","SI"],
          dangerMode: true,
      })
          .then((ok) => {
              if (ok) {

                $loader.addClass('d-block');
                $btn.html('Procesando...').prop('disabled','disabled');
                //$form.sleep(1000).submit();
                $form.unbind('submit').submit();
                //$(this).unbind('submit').submit()


                //location.href="/estudiantes";

                /* swal(
                        "Mensaje", 
                        "Registro borrado.", {
                          icon: "success",
                      }); */
                
                  /* $.post("{{route("agregar.cursos")}}",{
                      id:id,
                      _token:'{{csrf_token()}}',
                      delete:1
                  },function(data){
                      $('#modalNewFormCursos .modal-body').html(data);
                      swal(
                        "Mensaje", 
                        "Registro borrado.", {
                          icon: "success",
                      });
                      window.setTimeout(focusNombre,100);
                  }); */

              } else {
                
                return false;
              }
          });
      
    });



    /*begin dni*/
    // Buscar por DNI
    var $dni_doc=$("#dni_doc2");
    $dni_doc.bind("change",function(e){
      var evento = $('#eventos_id').val();
      var xdni = $('#dni_doc2').val();
      var xcurso = $('#cod_curso').val();
      //console.log("Form DJ: ",xdni, xcurso, evento);

      var pos=this.value;
      var url = baseURL('')+"getDNI/"+e.target.value+"/"+evento+"";
      
      $.get(url, function(resp,depa){
        
        if(resp.datos.length > 0){
            //console.log(resp);
            // VALIDAR QUE SEA OTRO EVENTO // 7 es Mod DDJJ / estudiantes_tipo_id=7
            if((resp.datos[0].eventos_id == evento && resp.datos[0].tipo_id == 2) || 
                (resp.datos[0].eventos_id == evento && resp.datos[0].tipo_id == 1) ||
                (resp.datos[0].eventos_id == evento) && (resp.datos[0].estudiantes_tipo_id != 7)){
                  
            }else{ 
              console.log('DNI valido');
              let g = resp.datos[0].grupo == null ? 0 : 
                      resp.datos[0].grupo == "" ? 0 : 1 ;

              let p = resp.datos[0].pais == null ? 0 : 
                      resp.datos[0].pais == "" ? 0 : 1 ;

              let r = resp.datos[0].region == null ? 0 : 
                      resp.datos[0].region == "" ? 0 : 1 ;

              /*if(g == 1){
                $('#grupo').append('<option selected value="'+resp.datos[0].grupo+'">'+resp.datos[0].grupo+'</option>');
              }*/
              if(p == 1){
                //$('#pais').append('<option selected value="'+resp.datos[0].pais+'">'+resp.datos[0].pais+'</option>');
                //$('#pais').val(resp.datos[0].pais);
                $('#pais').val('');
              }
              $('#nombres').val(resp.datos[0].nombres);
              $('#ap_paterno').val(resp.datos[0].ap_paterno);
              $('#ap_materno').val(resp.datos[0].ap_materno);
              $('#profesion').val(resp.datos[0].profesion);
              $('#organizacion').val(resp.datos[0].organizacion);
              $('#cargo').val(resp.datos[0].cargo);
              $('#xemail').val(resp.datos[0].email);
              $('#xcelular').val(resp.datos[0].celular);

              // proveedor de dominio
              let cadena_email = resp.datos[0].email;
              let email_usuario = "";
              let email_dominio = "";
              
              let cad_partes = cadena_email.split('@');
              if(cad_partes.length == 2){
                email_usuario = cad_partes[0];
                email_dominio = "@"+cad_partes[1];
              }

              var index = emails.indexOf(email_dominio);
              console.log("index : "+index);
              if(index > -1){
                $('#email_dominio').prop('selectedIndex',index);
              }else{
                $('#email_dominio').prop('selectedIndex',0);
              }
              //$('#email_dominio').val(email_dominio);
              //console.log(email_usuario);console.log(email_dominio);

              editEmail.css('display','inline-block');
              editCel.css('display','inline-block');
              email.val(email_usuario).attr('disabled',true);//resp.xdatos.email
              //email_dominio.val(email_dominio).attr('disabled',true);//resp.xdatos.email
              celular.attr('type','text').attr('disabled',true).val(resp.xdatos.celular);

            }
          
          $.each(resp.datos, function(index, data){

            if(data.eventos_id == evento){

              //console.log(xdni, xcurso, evento);

              //permitir dni duplicados a los grupos DJ
              /*if(data.estudiantes_tipo_id != 7){

                // si dni y cod_curso => alert registrado
                swal(
                    "Advertencia", 
                    "Usted se encuentra registrado. Para mayor información envia un correo electronico a: inscripciones@enc.edu.pe", 
                    "warning"
                    );
  
                  clearForm();
                  $('#dni_doc2').val('');
                  email.val("");
                  celular.val("");

              }*/
            }

          });

        }else{
          //console.log("El DNI no esta registrado.");
          clearForm();

          editCel.css('display','none');
          celular.attr('disabled',false).attr('type','number').val('');

          editEmail.css('display','none');
          email.attr('disabled',false).val('');
        }
      });

    });

    function clearForm(){
      console.log('Clean...');
      
      $('#codigo_colaborador').val("");
      $('.valor_capacitacion').val("");
      $('#categ').val("");
      $('#unidad_organica').val("");
      $('#dni_doc2').val("");
      $('#nombres').val("");
      $('#ap_paterno').val("");
      $('#ap_materno').val("");
      $('#profesion').val("");
      $('#organizacion').val("");
      $('#cargo').val("");
      $('#email').val("");
      $('#email_dominio').val("");
      $('#celular').val("");
      $('#cboDepartamento,#dpto,#distrito,#provincia').val("");
      $('#pais').val("");
      $('#grupo').val("");
      
    }


    var $si_cgr,$div_ct,$fecha_fin;
    $si_cgr = $("#si_cgr");
    $div_ct = $("#div_ct");
    $fecha_fin = $("#fecha_fin");
    $si_cgr.click(function(){
      if(!this.checked){
        $div_ct.removeClass('d-none');
        $fecha_fin.prop("required",true);
      }else{
        $div_ct.addClass('d-none');
        $fecha_fin.removeAttr('required').val("");
      }
    });

    /*begin dni end*/
    /* begin codigo */
    $("#codigo_colaborador").on("input", function(e) {

      e.preventDefault();
      var input = $(this);
      var val = input.val();
      val = $.trim(val);

      let evento = $('#eventos_id').val();

      /* var btnRegister = $('#actionSubmit');
      btnRegister.attr('disabled', true); */
      
      $('#codigo_colaborador').addClass('border-primary');

      if (val.length > 4) {

        let url = "/getCodigo/"+val+"/"+evento;
        console.log(url);

        $.get(url, function(resp,depa){
          console.log(resp.datos);
          if(resp.datos.length > 0){
              //console.log(resp);
              // VALIDAR QUE SEA OTRO EVENTO // 7 es Mod DDJJ / estudiantes_tipo_id=7
              if((resp.datos[0].eventos_id == evento && resp.datos[0].tipo_id == 2) || 
                  (resp.datos[0].eventos_id == evento && resp.datos[0].tipo_id == 1) ||
                  (resp.datos[0].eventos_id == evento) && (resp.datos[0].estudiantes_tipo_id != 7)){
                    
              }else{ 
                console.log('DNI valido');
                let g = resp.datos[0].grupo == null ? 0 : 
                        resp.datos[0].grupo == "" ? 0 : 1 ;

                let p = resp.datos[0].pais == null ? 0 : 
                        resp.datos[0].pais == "" ? 0 : 1 ;

                let r = resp.datos[0].region == null ? 0 : 
                        resp.datos[0].region == "" ? 0 : 1 ;

                
                if(p == 1){$('#pais').val('');}
                
                
                $('#categ').val(resp.datos[0].categoria);
                $('#unidad_organica').val(resp.datos[0].unidad_organica);

                $('#dni_doc2').val(resp.datos[0].dni_doc);

                $('#nombres').val(resp.datos[0].nombres);
                $('#ap_paterno').val(resp.datos[0].ap_paterno);
                $('#ap_materno').val(resp.datos[0].ap_materno);
                $('#profesion').val(resp.datos[0].profesion);
                $('#organizacion').val(resp.datos[0].organizacion);
                $('#cargo').val(resp.datos[0].cargo);
                $('#email2').val(resp.datos[0].email);
                let cel_show = resp.datos[0].celular;
                let cel_hidden = "";
                if(cel_show===""){console.log('xcelular: '+cel_show);}
                  else{
                    cel_show = resp.xdatos.celular;
                    cel_hidden = resp.xdatos.xcelular;
                  }

                $('#xcelular').val(cel_hidden);

                // proveedor de dominio
                let cadena_email = resp.datos[0].email;
                let email_usuario = "";
                let email_dominio = "";
                
                let cad_partes = cadena_email.split('@');
                if(cad_partes.length == 2){
                  email_usuario = cad_partes[0];
                  email_dominio = "@"+cad_partes[1];
                }

                var index = emails.indexOf(email_dominio);
                console.log("index : "+index);
                if(index > -1){
                  $('#email_dominio').prop('selectedIndex',index);
                }else{
                  $('#email_dominio').prop('selectedIndex',0);
                }
                //$('#email_dominio').val(email_dominio);
                //console.log(email_usuario);console.log(email_dominio);

                editEmail.css('display','inline-block');
                editCel.css('display','inline-block');
                email.val(email_usuario).attr('disabled',true);//resp.xdatos.email
                //email_dominio.val(email_dominio).attr('disabled',true);//resp.xdatos.email
                celular.attr('type','text').attr('disabled',true).val(cel_show);

              }
            
            

          }else{
            swal("Mensaje","El usuario del colaborador no existe", {icon: "warning"});
            
            clearForm();

            editCel.css('display','none');
            celular.attr('disabled',false).attr('type','number').val('');

            editEmail.css('display','none');
            email.attr('disabled',false).val('');
          }
        });
        
        

      }else{
        $('#hora').empty();
      }

      });
    /* end codigo */
      
  });

  (function(seconds) {
    var refresh,       
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href = location.href;
            }, seconds * 1000);
        };

    $(document).on('keypress click', function() { intvrefresh() });
    intvrefresh();

  }(60*5)); // define here seconds
</script>
@endsection