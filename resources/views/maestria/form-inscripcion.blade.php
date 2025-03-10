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
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="maestriaForm" action="{{ route('maestria_link.store') }}" method="post" enctype="multipart/form-data" autocomplete="on">

                  {!! csrf_field() !!}

                  <div class="row ">
                    @if($datos->imagen == 1)
                      <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                        <div class="card">
                          <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                          
                          <!--card-img-top -->
                          <div class="card-body">
                            <h1 class="card-title text-center mb-3" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
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

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">

                      <div class="card">
                        <div class="card-body">
                          @if($datos->imagen != 1)
                          <h1 class="card-title text-center mb-3 display-4" style="color: #dc3545;">{!!$datos->nombre_evento!!}</h1>
                          <p>
                            {!! $datos->descripcion_form !!}
                          </p>
                          @endif

                          <h4 class="card-title">Datos Personales </h4>
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
                            @if($datos->tipo_doc == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="tipo_doc">Tipo de Documento <span class="text-danger">*</span></label>
                                <select class="form-control" required name="tipo_doc" id="cboTipDoc" class="codigo_cel">
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
                            @if($datos->ubigeo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ubigeo">Ubigeo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ubigeo" name="ubigeo"  placeholder="Código Ubigeo" required value="{{ old('ubigeo') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->grupo == 1)
                            <div class="col-sm-12 col-md-4">
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

                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4 fecha_nac">
                              <div class="form-group ">
                                <label for="fecha_nac">Fecha Nacimiento / Birth date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control text-uppercase" id="fecha_nac" name="fecha_nac" required  placeholder="" value="{{ old('fecha_nac') }}">
                              </div>
                            </div>
                            @endif

                            
                            @if($datos->gradoprof == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="gradoprof">Grado Profesional / Professional Grade <span class="text-danger">*</span></label>
                                <select style="width:100%;" class="form-control" required name="gradoprof" id="gradoprof" class="codigo_cel">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  @foreach($grados as $g)
                                  <option @if($g->id==1) selected @endif {{ old('gradoprof')==$g->id? 'selected' : ''}} value="{{$g->id}}">{{$g->nombre}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->profesion == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="profesion">Profesión-Ocupación / Career-Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="profesion" name="profesion"  required placeholder="Profesión-Ocupación/Profession-Occupation" value="{{ old('profesion') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="organizacion">Centro Laboral / Labor Center <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="organizacion" name="organizacion" required  placeholder="Entidad / Entity" value="{{ old('organizacion') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="cargo">Cargo Actual / Actual charge <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cargo" name="cargo" required  placeholder="Cargo/Charge" value="{{ old('cargo') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->sexo == 1)
                            <div class="col-sm-12 col-md-4"> {{-- aaa valid campo --}}
                              <div class="form-group ">
                                <label for="sexo">Género <span class="text-danger">*</span></label>
                                <select class="form-control text-uppercase" required name="sexo" id="sexo">
                                  <option value="">SELECCIONE / CHANGE</option>
                                  <option {{ old('sexo')=='FEMENINO'? 'selected' : ''}} value="F">FEMENINO</option>
                                  <option {{ old('sexo')=='MASCULINO'? 'selected' : ''}} value="M">MASCULINO</option>
                                </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->si_cgr == 1)
                            <div class="col-sm-12 col-md-4"> {{-- aaa valid campo --}}
                              <div class="form-check">
                                  <input type="checkbox" id="si_cgr" name="si_cgr" class="form-check-input check_click" value="1">
                                  <label class="form-check-label" for="si_cgr">Si es trabajador de la Contraloría </label>
                              </div>
                            </div>
                            @endif
                            @if($datos->codigo_cgr == 1)
                            <!--<div class="col-sm-12 col-md-4 div_vacio"></div>-->
                            <div class="col-sm-12 col-md-4 div_cgr " >{{-- aaa valid campo --}}
                              <div class="form-group d-none" id="div_ct">
                                <label for="codigo_cgr">Código de trabajador <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="codigo_cgr" name="codigo_cgr"  placeholder="CÓDIGO DE TRABAJADOR" value="{{ old('codigo_cgr') }}">
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
                                  <input class="form-control" type = "number"  maxlength = "9" id="celular" name="celular"  placeholder="999888777" value="{{ old('celular') }}" required>
                              </div>
                            </div>
                            @endif
                            @if($datos->email_labor == 1)
                            <div class="col-sm-12 col-md-4">{{-- aaa valid campo --}}
                              <div class="form-group">
                                <label for="telefono_labor">Telefóno / Telephone <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" type = "text" id="telefono_labor" name="telefono_labor"  placeholder="01000000" value="{{ old('telefono_labor') }}" required>
                              </div>
                            </div>
                            @endif

                            @if($datos->email == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="email">Correo electrónico personal <span class="text-danger">*</span> <a href="#" id="editEmail" style='display:none;'>Editar</a></label>
                                {{-- <input type="email" class="form-control" id="email" name="email"  placeholder="Correo electrónico personal/Email" value="" required> --}}

                                {{-- <label class="sr-only" for="email">Email</label> --}}
                                <div class="input-group mb-2">
                                  <input type="text" class="form-control" id="email" name="email" placeholder="CORREO" required value="{{ old('email') }}">
                                  <div class="input-group-prepend">
                                    <select class=" form-control" required name="email_dominio" id="email_dominio">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($dominios as $dominio)
                                      <option {{ old('email_dominio')==$dominio->dominio? 'selected' : ''}} value="{{$dominio->dominio}}">{{$dominio->dominio}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <span class="text-danger small pt-2 d-none" id="salida"></span>
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->email_labor2 == 1)
                            <div class="col-sm-12 col-md-4">{{-- aaa valid campo --}}
                              <div class="form-group">
                                <label for="email_labor">Correo electrónico alternativo <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" type="text" id="email_labor" name="email_labor"  placeholder="usuario@gmail.com" value="{{ old('email_labor') }}" required>
                              </div>
                            </div>
                            @endif

                            @if($datos->direccion == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-group ">
                                <label for="direccion">Dirección / Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="direccion" name="direccion" required  placeholder="Dirección" value="{{ old('direccion') }}">
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
                                      <option value="{{$country->name}}">{{$country->name}}</option>
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
                                    @if($datos->pais != 1)
                                      @foreach($departamentos as $dep)
                                        <option {{ old('departamento')==$dep->nombre? 'selected' : ''}} value="{{$dep->nombre}}">{{$dep->nombre}}</option>
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
                                  </select>
                              </div>
                            </div>
                            @endif

                            


                            
                            @if($datos->discapacidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="discapacidad">Discapacidad / Disability <span class="text-danger required_camp">*</span></label>
                                  <input class="form-control text-uppercase" required type="text" id="discapacidad" name="discapacidad"  placeholder="" value="{{ old('discapacidad') }}">
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title my-4">Datos adjuntos / Attachment data </h4>
                            </div>

                            @if($datos->compago == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="compago">Adjunte voucher de pago / Attach payment voucher (imagen o PDF / Máx. Peso 1000KB) <span class="text-danger">*</span></label>
                                  <input id="comprobante_pago" class="form-control" type="file" name="comprobante_pago"  placeholder="" value="{{ old('comprobante_pago') }}" accept="image/jpg, image/jpeg, application/pdf" required>

                                  @if($errors->has('comprobante_pago'))
                                    <div class="error">{{ $errors->first('comprobante_pago') }}</div>
                                  @endif
                              </div>
                            </div>
                            @endif
                            @if($datos->ficins == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="ficha_inscripcion">Ficha de Inscripción / Registration Form (PDF) <span class="text-danger">*</span></label>
                                  <input class="form-control" type="file" id="ficha_inscripcion" name="ficha_inscripcion" accept=".pdf"  placeholder="" value="{{ old('ficha_inscripcion') }}" required>
                                @if($errors->has('ficha_inscripcion'))
                                  <div class="error">{{ $errors->first('ficha_inscripcion') }}</div>
                                @endif
                              </div>
                            </div>
                            @endif
                            @if($datos->decjur == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="declaracion_jurada">Declaraciones Juradas / Declared jurisdictions <span class="text-danger">*</span></label>
                                  <input id="declaracion_jurada" class="form-control " type="file" name="declaracion_jurada" placeholder="" value="{{ old('declaracion_jurada') }}" required>

                                  @if($errors->has('declaracion_jurada'))
                                    <div class="error">{{ $errors->first('declaracion_jurada') }}</div>
                                  @endif
                              </div>
                            </div>
                            @endif
                            @if($datos->cv == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="cv">Curriculum Vitae </label>{{-- <span class="text-danger">*</span> --}}
                                  <input id="cv" class="form-control" type="file" name="cv" placeholder="" value="{{ old('cv') }}">
                                  @if($errors->has('cv'))
                                    <div class="error">{{ $errors->first('cv') }}</div>
                                  @endif
                              </div>
                            </div>
                            @endif

                            @if($datos->foto == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="foto" title="Imagen a color con fondo blanco. Tomada de frente sin gorra y sin gafas o lentes de color oscuro.">Foto ( JPG / PNG / GIF ) Tamaño. max. 50 KB {{-- <span class="text-danger">*</span> --}}

                                  <a href="#" class="open_modal" data-id="modal_open" onclick="openModal()" data-toggle="modal">ver características</a>

                                </label>
                                  <input id="foto" accept="image/jpg, image/jpeg" class="form-control" title="Imagen a color con fondo blanco. Tomada de frente sin gorra y sin gafas o lentes de color oscuro." type="file" name="foto" placeholder="" value="{{ old('foto') }}">
                                  @if($errors->has('foto'))
                                    <div class="error">{{ $errors->first('foto') }}</div>
                                  @endif
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title my-4">Datos del Comprobante / Voucher Data </h4>
                            </div>
                            @if($datos->nvoucher == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="nvoucher">Nº del Voucher / Voucher number <span class="text-danger">*</span></label>
                                  <input class="form-control text-uppercase" type="text" maxlength="16" id="nvoucher" name="nvoucher"  placeholder="NÚMERO" required value="{{ old('nvoucher') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->fechadepo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="fechadepo">Fecha del Depósito / Deposit Date <span class="text-danger">*</span></label>
                                  <input class="form-control" required type="date" id="fechadepo" name="fechadepo"  placeholder="" value="{{ old('fechadepo') }}" min="2015-01-01">
                              </div>
                              {{-- <div class="form-group">
                                <label for="fechadepo">Fecha del Depósito / Deposit Date <span class="text-danger">*</span></label>
                                <div id="datepicker-popup" class="input-group date datepicker">
                                  <input required type="text" class="form-control form-border" name="fechadepo" id="fechadepo" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                                </div>
                              </div> --}}
                            </div>
                            @endif
                           @if($active_campo_link == 1)
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group">
                                <label for="link_detalle">Link detalle</label>
                                  <input class="form-control" type="text" id="link_detalle" name="link_detalle"  placeholder="NÚMERO" value="{{ old('link_detalle') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->terminos == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-check">
                                    <input type="checkbox" id="enc" name="check_auto" class="form-check-input check_click" required>
                                  <label class="form-check-label" for="enc">
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
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class="mdi mdi-checkbox-marked-circle "></i>REGISTRARSE / CHECK IN</button>

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

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card"></div>

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

  <div class="modal fade modal_open" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document"> {{-- modal-lg --}}
      <div class="modal-content">
        
        <div class="modal-header d-flex">
          
        </div>
        <div class="modal-body pt-0 pb-0">
            <div class="form-group row">
              <div class="col-md-12">
                <div class="g-lista text-center">
                    <img src="images/g/foto_inscripcion_mcg.jpg" alt="foto inscripcion mcg" width="547">
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
  var $si_cgr,$div_ct,$codigo_cgr;

  $(document).ready(function(){
    $si_cgr = $("#si_cgr");
    $div_ct = $("#div_ct");
    $codigo_cgr = $("#codigo_cgr");
    $si_cgr.click(function(){
      if(this.checked){
        $div_ct.removeClass('d-none');
        $codigo_cgr.prop("required",true);
      }else{
        $div_ct.addClass('d-none');
        $codigo_cgr.removeAttr('required').val("");
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

}(60*3)); // define here seconds
</script>
@endsection