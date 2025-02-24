@extends('layout.home')

@section('content')

<div class="horizontal-menu bg_fondo">
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
            <div class="row">
              <div class="col-xs-12 col-md-12 col-lg-12">
                <form class="forms-sample" id="caiiForm" action="{{ route('caii.update', $caii_datos->id) }}" method="post" enctype="multipart/form-data" autocomplete="on">
                  {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                  <div class="row ">

                    <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                      <div class="card">
                        <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                        
                        <div class="card-body">
                          <h4 class="card-title text-transform-none">Señor(a): {{ $caii_datos->nombres }} {{ $caii_datos->ap_paterno }}</h4>
                          <p class="card-text">
                            Para el correcto registro de su participación a las conferencias magistrales y actividades que se realizarán en la CAAI 2019, agradeceremos validar sus datos:
                          </p>
                          <p class="card-text" style="color:#999;">
                            For the correct registration of your participation to the lectures and activities that will be held at the CAAI 2019, we would appreciate validating your data:
                          </p>
                          {{-- {!! $datos->descripcion_form_2 !!} --}}
                          <p class="card-text">
                            (Usted puede editar y rellenar los campos) / (You can edit and fill in the fields)<br>
                            <strong class="text-danger">* Campos obligatorios / Required fields</strong>
                          </p>


                        </div>
                      </div>
                    </div>

                   

                    <div id="capa_uno" class="col-sm-12 col-md-12  grid-margin stretch-card capa_uno">
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
                                  <div class="input-group-prepend">
                                    <select disabled="" class="form-control" required="" name="tipo_doc" id="cboTipDoc" class="codigo_cel">
                                      @foreach($tipos as $tipo)
                                      <option value="{{$tipo->id}}" @if($tipo->id == $caii_datos->tipo_documento_documento_id) selected="" @endif 
                                        >{{$tipo->tipo_doc}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif
                            @if($datos->dni == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">{{--  --}}
                                <label for="dni_doc">DNI / ID <span class="text-danger">*</span></label>
                                <input disabled="" type="@if ($caii_datos->tipo_documento_documento_id == 1) number @else text @endif" class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "8" id="dni_doc" name="dni_doc" required=""  placeholder="DNI/ID" value="{{ $caii_datos->dni_doc }}">
                                <input type="hidden" name="dni_doc2" value="{{ $caii_datos->dni_doc }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->grupo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="grupo">Grupo / Group <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <div class="input-group-prepend">
                                    <select class="form-control" required="" name="grupo" id="grupo" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      @foreach($grupos as $tipo)
                                      <option value="{{$tipo->codigo}}" @if($tipo->codigo == $caii_datos->grupo) selected="" @endif 
                                        >{{$tipo->nombre}}</option>
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
                                <input type="text" class="form-control text-uppercase" id="nombres" name="nombres"  placeholder="Nombres/Name" required="" value="{{ $caii_datos->nombres }}" {{-- style="border-bottom: 2px solid;" --}}>
                              </div>
                            </div>
                            @endif
                            @if($datos->ap_paterno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_paterno">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno"  placeholder="Apellido Paterno/Last Name" required="" value="{{ $caii_datos->ap_paterno }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->ap_materno == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" required=""  placeholder="Apellido Materno" value="{{ $caii_datos->ap_materno }}">
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
                                      @foreach($countrys as $country) <option class="text-uppercase" @if(strtolower($caii_datos->pais) == strtolower($country->name)) selected="" @endif value="{{$country->name}}">{{$country->name}}</option> @endforeach
                                    </select>
                                 {{-- <option class="text-uppercase" value="{{$country->phonecode}}">{{$country->name}}</option> --}}
                              </div>
                            </div>
                            @endif

                            @if($datos->departamentos == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="region">Departamentos / Departments @if ($caii_datos->pais == "PERU") <span class="text-danger" id="required_region">*</span> @endif</label>
                                  <select class="form-control text-uppercase" id="cboDepartamento" name="region" @if ($caii_datos->pais == "PERU") required="" @endif>

                                    @if($datos->pais == 1)
                                      <option value="">SELECCIONE</option>
                                      @foreach($departamentos as $dep)
                                        @if ($caii_datos->pais == "PERU")
                                          <option class="text-uppercase" @if($caii_datos->region == $dep->nombre) selected="" @endif value="{{$dep->nombre}}">{{$dep->nombre}}</option>
                                        @endif
                                      @endforeach
                                    @endif
                                    {{-- <option value="{{ $caii_datos->region }}">{{ $caii_datos->region }}</option> --}}
                                  </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->profesion == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="profesion">Profesión-Ocupación / Profession-Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="profesion" name="profesion"  required="" placeholder="Profesión-Ocupación/Profession-Occupation" value="{{ $caii_datos->profesion }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="organizacion">Entidad / Entity <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="organizacion" name="organizacion" required=""  placeholder="Entidad / Entity" value="{{ $caii_datos->organizacion }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->cargo == 1)
                              @if($datos->pais == 1)
                                <div class="col-sm-12 col-md-4"></div>
                              @endif
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="cargo">Cargo / Charge <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cargo" name="cargo" required=""  placeholder="Cargo/Charge" value="{{ $caii_datos->cargo }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->email == 1)

                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="email">Correo electrónico personal / Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"  placeholder="Correo electrónico personal/Email" value="{{ $caii_datos->email }}" required=""> 
                              </div>
                            </div>
                            @endif
                            @if($datos->celular == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="codigo_cel">Código del País / Country Code <span class="text-danger">*</span></label>
                                <select class="form-control text-uppercase" required="" name="codigo_cel" id="codigo_cel" class="codigo_cel">
                                      <option value="">SELECCIONE / CHANGE</option>
                                      <option value="51">PERU</option>
                                      @foreach($countrys as $country) <option class="text-uppercase" @if($caii_datos->codigo_cel == $country->phonecode) selected="" @endif value="{{$country->phonecode}}">{{$country->name}}</option> @endforeach
                                    </select>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="celular">Celular / Mobile <span class="text-danger">*</span></label>
                                <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "9" id="celular" name="celular"  placeholder="998877665" value="{{ $caii_datos->celular }}" required="">
                              </div>
                            </div>
                            @endif
                            
                            {{-- @if($datos->terminos == 1) --}}
                            <div class="col-sm-12 col-md-12">
                              <div class="form-group ">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" name="check_constancia" class="form-check-input" required="">
                                    He verificado mis datos 
                                    {{-- Dejo constancia que los datos registrados son correctos, asimismo declaro tener completo conocimiento del programa referido a la CAII {{ date('Y') }} --}}
                                  <i class="input-helper"></i></label>
                                  <p style="padding-left: 10px;"> I have verified my data
                                  {{-- I leave evidence that the registered data are correct, also I declare to have complete knowledge of the program referred to the CAII {{ date('Y') }} --}}
                                </p>
                                  {{-- Dejo constancia que los datos registrados son correctos / I leave evidence that the registered data is correct --}}
                                </div>

                                <div class="form-check">
                                  <label class="form-check-label" for="enc">
                                    <input type="checkbox" id="enc" name="check_auto" class="form-check-input" required="">
                                    He leído y acepto los Término y Condiciones
                                  
                                  </label>
                                    {{-- Autorizo de manera expresa que mis datos sean cedidos a la Escuela Nacional de Control con la finalidad de poder recibir información de las actividades académicas y culturales --}}

                                  <p style="padding-left: 10px;">I have read and accept the  <a href="#" onclick="eximForm()" data-toggle="modal">Terms and Conditions</a>

                                  {{-- I authorize that my data can be used by Escuela Nacional de Control for receiving information of academic and cultural activities --}}</p>
                                </div>
                              </div>

                              <div class="col-sm-12 col-md-12">
                                <div class="col-sm-12 col-md-12 p-4 text-center">
                                  <button type="submit" class="btn btn-dark mr-2">Guardar y continuar / Save and continue</button>
                                </div>
                                <div class="col-sm-12 col-md-12 p-0 mt-3 text-center">
                                  <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                                </div>
                                
                              </div>

                            </div>
                            {{-- @endif --}}
                          </div> {{-- end row --}}

                         {{-- end row --}}
                          
                        </div>
                      </div>
                    </div>


                    {{-- end capa_dos --}}

                  </div>


                </form>
                
              </div>
            </div>
          </div>

          @if($datos->terminos == 1)
            @include('termino-condiciones.index')
          @endif
          
          
        </div>
<style>
.wizard > .content > .body{
  position: relative;
}

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
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

<script src="{{ asset('js_a/vendor.bundle.base.js')}}"></script>  
<script src="{{ asset('js_a/vendor.bundle.addons.js')}}"></script>




@endsection
