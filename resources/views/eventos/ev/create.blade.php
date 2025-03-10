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
                <form class="forms-sample" id="caiiForm" action="{{ route('ev.store') }}" method="post" enctype="multipart/form-data" autocomplete="on">

                  {!! csrf_field() !!}

				          <div class="row ">
                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">

                        <!--card-img-top -->
                        <div class="card-body">

                          <H1 class="card-title">{{$datos->nombre_evento}}</H1>
                          <div class="row pb-3">
                            <div class="col-xs-12 col-sm-4">
                              <strong>Fecha:</strong> {{$datos->fecha_texto}} 
                            </div>
                            <div class="col-xs-12 col-sm-4">
                              <strong>Hora:</strong> {{$datos->hora}}
                            </div>
                            <div class="col-xs-12 col-sm-4">
                              <strong>Lugar:</strong> {{$datos->lugar}}
                            </div>
                          </div>
                          <p>
                            
                            {!! $datos->descripcion_form !!}
                          </p>

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
                                    <select class="form-control" required="" name="tipo_doc" id="cboTipDoc" class="codigo_cel">
                                      @foreach($tipos as $tipo)
                                      <option value="{{$tipo->id}}">{{$tipo->tipo_doc}}</option>
                                      @endforeach
                                    </select>
                              </div>

                            </div>

                            @endif

                            @if($datos->dni == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="dni_doc">DNI / ID NUMBER <span class="text-danger">*</span></label>
                                <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "8" id="dni_doc" name="dni_doc" required=""  placeholder="DNI/ID NUMBER" value="">
                              </div>
                            </div>
                            @endif

                            @if($datos->grupo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="grupo">Grupo / Group <span class="text-danger">*</span></label>
                                    <select class="form-control" required="" name="grupo" id="grupo" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($grupos as $tipo)
                                      <option value="{{$tipo->codigo}}">{{$tipo->nombre}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->nombres == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="nombres">Nombres / Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nombres" name="nombres"  placeholder="Nombres/Name" required="" value="{{ old('nombres') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_paterno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_paterno">Apellido Paterno / Surname <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno" placeholder="Apellido Paterno / Last Name" value="{{ old('ap_paterno') }}" required="">
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno / Additional Surname @if($_GET['id']==118 or $_GET['id']==119) @else <span class="text-danger">*</span>@endif</label>
                                <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno"   placeholder="Apellido Materno" value="{{ old('ap_materno') }}" @if($_GET['id']==118 or $_GET['id']==119) @else required="" @endif>
                              </div>
                            </div>
                            @endif

                            @if($datos->pais == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="pais">País / Country <span class="text-danger">*</span></label>
                                    <select class="form-control" required="" name="pais" id="pais" class="pais text-uppercase">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="PERU">PERU</option>
                                      @foreach($countrys as $country)
                                      <option class="text-uppercase" value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->departamentos == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="region">Departamentos / Departments <span class="text-danger" id="required_region">*</span></label>
                                  <select class="form-control text-uppercase" id="cboDepartamento" name="region" required="">
                                    <option value="">SELECCIONE</option>
                                    @if($datos->pais != 1)
                                      @foreach($departamentos as $dep)
                                        <option class="text-uppercase" value="{{$dep->nombre}}">{{$dep->nombre}}</option>
                                      @endforeach
                                    @endif
                                  </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->profesion == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="profesion">Profesión-Ocupación / Career-Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="profesion" name="profesion"  required="" placeholder="Profesión-Ocupación/Profession-Occupation" value="">
                              </div>
                            </div>
                            @endif

                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="organizacion">Entidad / Entity (Escriba nombre exacto)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="organizacion" name="organizacion" required=""  placeholder="Entidad / Entity" value="">
                              </div>
                            </div>
                            @endif

                            @if($datos->cargo == 1)
                                {{-- @if($datos->pais == 1)
                                  <div class="col-sm-12 col-md-4"></div>
                                @endif --}}
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="cargo">Cargo / Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cargo" name="cargo" required=""  placeholder="Cargo/Charge" value="">
                              </div>
                            </div>
                            @endif

                            @if($datos->email == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="email">Correo electrónico personal / Email <span class="text-danger">*</span> <a href="#" id="editEmail" style='display:none;'>Editar</a></label>
                                {{-- <input type="email" class="form-control" id="email" name="email"  placeholder="Correo electrónico personal/Email" value="" required=""> --}}

                                {{-- <label class="sr-only" for="email">Email</label> --}}
                                <div class="input-group mb-2">
                                  <input type="text" class="form-control" id="email" name="email" placeholder="EMAIL" required="">
                                  <div class="input-group-prepend">
                                    <select class="form-control" required="" name="email_dominio" id="email_dominio">
                                      <option value="">SELECCIONE</option>
                                      @foreach($dominios as $dominio)
                                      <option class="text-uppercase" value="{{$dominio->dominio}}">{{$dominio->dominio}}</option>
                                      @endforeach
                                      <option value="OTRO">OTRO</option>
                                    </select>
                                  </div>
                                    <input type="text" class="form-control" id="email2" name="email2" placeholder="EMAIL" style="display: none;">
                                  <span class="text-danger small pt-2 d-none" id="salida"></span>
                                </div>
                              </div>

                            </div>
                            @endif

                            @if($datos->celular == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="codigo_cel">Código del País / Zip Code<span class="text-danger">*</span></label>
                                <select class="form-control text-uppercase" required="" name="codigo_cel" id="codigo_cel" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="51">PERU</option>
                                      @foreach($countrys as $country)
                                      <option class="text-uppercase" value="{{$country->phonecode}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="celular">Celular / Mobile <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "text"  maxlength = "9" id="celular" name="celular"  placeholder="998877665" value="{{ old('celular') }}" required>
                              </div>
                            </div>
                            @endif
                            @if($datos->terminos == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-check">
                                    <input type="checkbox" id="enc" name="check_auto" class="form-check-input check_click" required="">
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
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">ENVIAR / SEND</button>{{-- SOLICITAR ENTRADA --}}
                                </div>
                                <div class="col-sm-12 pt-2">
                                  <div class="alert alert-warning mb-0 text-center" role="alert">
                                    (Cada registro es una entrada)
                                  </div>
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

@endsection