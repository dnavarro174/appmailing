@extends('layout.home')

@section('content')

<div class="horizontal-menu bg_fondo" >
    <!-- partial:partials/_navbar.html -->

    {{-- @include('layout.nav_superior') --}}
    <!-- end encabezado -->
    <!-- partial -->

    <div class="container-fluid page-body-wrapper">
      <!-- end menu_right -->
      <!-- partial -->

      <div class="main-panel">
        <div class="content-wrapper pt-0" style="background: none;">
          <div class="container">
            <div class="row justify-content-center">{{-- $datos->activo == 2 --}}
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="caiiForm" action="{{ route('caii_pg.store') }}" method="post" enctype="multipart/form-data" autocomplete="on">

                    {!! csrf_field() !!}

				          <div class="row ">
                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">

                        <!--card-img-top -->
                        <div class="card-body">
                          <h4 class="card-title">PRE INSCRÍBETE / PRE-REGISTER</h4>
                          {!! $datos->descripcion_form !!}

                          <p class="card-text">
							               <strong class="text-danger">* Campos obligatorios / Required fields</strong>
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

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">

                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Datos Personales / Personal Data</h4>

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
                                <label for="tipo_doc">Tipo Doc / Type <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend w-100">
                                    <select class="form-control" required name="tipo_doc" id="cboTipDoc" class="codigo_cel">
                                      @foreach($tipos as $tipo)
                                      <option value="{{$tipo->id}}"
                                        {{ old('tipo_doc')==$tipo->id ? 'selected':'' }}
                                        >{{$tipo->tipo_doc}}</option>
                                      @endforeach
                                    </select>

                                    @error('tipo_doc')
                                    {!! $errors->first('tipo_doc', '<span class=error>:message</span>') !!}
                                    @enderror
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->dni == 1)

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="dni_doc">DNI / ID <span class="text-danger">*</span></label>
                                <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength="8" id="dni_doc" name="dni_doc" required  placeholder="DNI/ID" value='{{ old('dni_doc') }}'>

                                @error('dni_doc')
                                {!! $errors->first('dni_doc', '<span class=error>:message</span>') !!}
                                @enderror
                              </div>

                            </div>

                            @endif

                            @if($datos->grupo == 1)

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="grupo">Grupo / Group <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend w-100">
                                    <select class="form-control" required name="grupo" id="grupo" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($grupos as $tipo)
                                      <option 
                                      {{ old('grupo')==$tipo->codigo ? 'selected':'' }}
                                      value="{{$tipo->codigo}}">{{$tipo->nombre}}</option>
                                      @endforeach
                                    </select>

                                    @error('grupo')
                                    {!! $errors->first('grupo', '<span class=error>:message</span>') !!}
                                    @enderror
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->nombres == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="nombres">Nombres / Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nombres" name="nombres"  placeholder="Nombres/Name" required value="{{ old('nombres') }}">

                                @error('nombres')
                                {!! $errors->first('nombres', '<span class=error>:message</span>') !!}
                                @enderror
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_paterno == 1)

                            <div class="col-sm-12 col-md-4">

                              <div class="form-group ">
                                <label for="ap_paterno">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno"  placeholder="Apellido Paterno/Last Name" required value="{{ old('ap_paterno') }}">

                                @error('ap_paterno')
                                {!! $errors->first('ap_paterno', '<span class=error>:message</span>') !!}
                                @enderror
                              </div>

                            </div>

                            @endif

                            @if($datos->ap_materno == 1)

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" required  placeholder="Apellido Materno" value="{{ old('ap_materno') }}">

                                @error('ap_materno')
                                {!! $errors->first('ap_materno', '<span class=error>:message</span>') !!}
                                @enderror
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
                                      <option class="text-uppercase"
                                      {{ old('pais')==$country->name ? 'selected':'' }}
                                       value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>

                                    @error('pais')
                                    {!! $errors->first('pais', '<span class=error>:message</span>') !!}
                                    @enderror
                              </div>
                            </div>
                            @endif

                            @if($datos->departamentos == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="region">Departamentos / Departments <span class="text-danger" id="required_region">*</span></label>
                                  <select class="form-control text-uppercase" id="cboDepartamento" name="region" required>
                                    <option value="">SELECCIONE / CHANGE</option>
                                    @if($datos->pais != 1)
                                      @foreach($departamentos as $dep)
                                        <option class="text-uppercase" value="{{$dep->nombre}}">{{$dep->nombre}}</option>
                                      @endforeach
                                    @endif
                                    @if(old('region'))
                                        <option value="{{ old('region') }}" selected>{{ old('region') }}</option>
                                      @endif
                                  </select>
                                  
                                  @error('region')
                                  {!! $errors->first('region', '<span class=error>:message</span>') !!}
                                  @enderror
                              </div>
                            </div>
                            @endif

                            @if($datos->profesion == 1)

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="profesion">Profesión-Ocupación / Career-Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="profesion" name="profesion"  required placeholder="Profesión-Ocupación/Profession-Occupation" value="{{ old('profesion') }}">
                                @error('profesion')
                                {!! $errors->first('profesion', '<span class=error>:message</span>') !!}
                                @enderror
                              </div>
                            </div>
                            @endif

                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="organizacion">Entidad / Entity (Escriba nombre exacto) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="organizacion" name="organizacion" required  placeholder="Entidad / Entity" value="{{ old('organizacion') }}">
                                @error('organizacion')
                                {!! $errors->first('organizacion', '<span class=error>:message</span>') !!}
                                @enderror
                              </div>

                            </div>

                            @endif



                            @if($datos->cargo == 1)

                              @if($datos->pais == 1)

                                <div class="col-sm-12 col-md-4"></div>

                              @endif

                            <div class="col-sm-12 col-md-4">

                              <div class="form-group ">

                                <label for="cargo">Cargo / Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cargo" name="cargo" required  placeholder="Cargo/Charge" value="{{ old('cargo') }}">
                                @error('cargo')
                                {!! $errors->first('cargo', '<span class=error>:message</span>') !!}
                                @enderror

                              </div>

                            </div>

                            @endif


                            @if($datos->email == 1)

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="email">Correo electrónico personal / Email <span class="text-danger">*</span> <a href="#" id="editEmail" style='display:none;'>Editar</a></label>
                                <input type="email" onchange="validarEmail('email_caii')" class="form-control" id="email_caii" name="email"  placeholder="Correo electrónico personal/Email" required value="{{ old('email') }}">

                                @error('email')
                                {!! $errors->first('email', '<span class=error>:message</span>') !!}
                                @enderror
                                <span id='errorEmail' class="d-none error">La dirección de email es incorrecta!</span>
                                
                              </div>
                            </div>
                            @endif

                            @if($datos->celular == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="codigo_cel">Código del País / Zip Code<span class="text-danger">*</span></label>
                                <select class="form-control text-uppercase" required name="codigo_cel" id="codigo_cel" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="51">PERU</option>
                                      @foreach($countrys as $country)
                                      <option class="text-uppercase" 
                                      {{ old('codigo_cel')==$country->phonecode ? 'selected':'' }}
                                      value="{{$country->phonecode}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="celular">Celular / Mobile <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "9" id="celular" name="celular"  placeholder="998877665" value="{{ old('celular') }}" required>
                                  @error('celular')
                                  {!! $errors->first('celular', '<span class=error>:message</span>') !!}
                                  @enderror
                              </div>
                            </div>
                            @endif

                            <input type="hidden" id="eventos_id" name="eventos_id" value="{{ $id_evento }}">
                            <input type="hidden" id="fecha_inicial" name="fecha_inicial" value="{{ $fecha_inicial }}">
                            <input type="hidden" id="fecha_final" name="fecha_final" value="{{ $fecha_final }}">
                            <input type="hidden" id="xemail" name="xemail" value="" >
                            <input type="hidden" id="xcelular" name="xcelular" value="" >
                            <input type="hidden" id="modalidad" name="modalidad" value="{{ $modalidad }}" >
                            <input type="hidden" id="tipo_est" name="tipo_est" value="{{ $tipo_est }}" >

                            <div class="col-sm-12 col-md-12">
                              <div class="form-group ">
                                
                                <div class="col-sm-12 px-0 pt-2">

                                  
                                  <div class="alert alert-warning mb-0" role="alert">
                                    Este formulario le permite SOLICITAR una vacante. De obtenerla, se le confirmará al número celular y correo electrónico registrado. <br> This form allows you to APPLY for a vacancy. If obtained, the registered cell number and email will be confirmed.
                                  </div>

                                </div>

                                <div class="col-sm-12 col-md-12 p-4 text-center">
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">PREINSCRIBIRSE / SEND</button>
                                </div>
                                <div class="col-sm-12 col-md-12 p-0 mt-3 text-center">
                                  <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
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

        <style>
        .wizard > .content > .body{position: relative;}
        .form-control2 label.form-radio{font-weight: bold;font-size: 14px;}
        .form-control2 label.form-radio em{color:#21AFAF;font-style: normal;}
        .form-control2 label.form-radio span{color:#556685;}
        .texto_foros p{padding-left: 25px;}
        .wizard > .content > .body input{display: inline-block;}
        </style>


        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layout.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div><!-- main-panel ends -->

    </div><!-- page-body-wrapper ends -->

  </div><!-- container-scroller -->
{{-- <script src="{{ asset('js_a/vendor.bundle.base.js')}}"></script>  
<script src="{{ asset('js_a/vendor.bundle.addons.js')}}"></script> --}}
@endsection
@section('scripts')
<script>
  console.log('test email');
  function validarEmail(val) {
  let valor = $('#'+val).val();
  if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
    
    $('#errorEmail').addClass('d-none');
    //alert("La dirección de email " + valor + " es correcta!.");
  } else {
    $('#errorEmail').removeClass('d-none');
    return false;
    //alert("La dirección de email es incorrecta!.");
    
  }
}
</script>
@endsection
