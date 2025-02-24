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
              <div class="col-xs-12 col-md-11 col-lg-11">
                <form id="maestriaForm" action="{{ route('form_link.store') }}" method="post" autocomplete="on">

                  {!! csrf_field() !!}

                  <div class="row ">
                    @if($datos->imagen == 1)
                      <div class="col-sm-12 col-md-12  grid-margin stretch-card">
                        <div class="card">
                          <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="{{$datos->nombre_evento}} {{date('Y')}}" class="img-fluid">
                          
                          <!--card-img-top -->
                          {{-- <div class="card-body">
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
                          </div> --}}
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

                          <h4 class="card-title">Datos de contacto </h4>
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
                            
                            

                            @if($datos->ap_paterno == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="ap_paterno">Apellidos  <span class="text-danger">*</span></label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-9">
                                <div class="form-group ">
                                  <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno"  placeholder="Apellidos" required="" value="{{ old('ap_paterno') }}">
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->ap_materno == 1)
                            {{-- <div class="col-sm-12 col-md-4 ap_materno">
                              <div class="form-group ">
                                <label for="ap_materno">Apellido Materno / Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" required=""  placeholder="Apellido Materno" value="{{ old('ap_materno') }}">
                              </div>
                            </div> --}}
                            @endif

                            @if($datos->nombres == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-9">
                                <div class="form-group ">
                                  
                                  <input type="text" class="form-control text-uppercase" id="nombres" name="nombres"  placeholder="Nombres" required="" value="{{ old('nombres') }}">
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->tipo_doc == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="tipo_doc">Tipo de Documento <span class="text-danger">*</span></label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                  <select class="form-control" required="" name="tipo_doc" id="cboTipDoc" class="codigo_cel">
                                    @foreach($tipos as $tipo)
                                    <option {{ old('tipo_doc')==$tipo->id? 'selected' : ''}} value="{{$tipo->id}}">{{$tipo->tipo_doc}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            @endif

                            @if($datos->dni == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="dni_doc">Número de DNI <span class="text-danger">*</span></label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group ">
                                  <input class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "8" id="dni_doc_" name="dni_doc" required=""  placeholder="DNI" value="{{ old('dni_doc') }}" />
                                </div>
                              </div>
                            </div>
                            @endif



                          <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="dni_">Lugar de residencia <span class="text-danger">*</span></label>
                                </div>
                              </div>  

                            @if($datos->pais == 1)
                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                    <select class="form-control" required name="pais" id="pais" class="pais text-uppercase">
                                      <option value="">PAÍS *</option>
                                      <option value="PERU">PERU</option>
                                      @foreach($countrys as $country)
                                      <option value="{{$country->name}}">{{$country->name}}</option>
                                      @endforeach
                                    </select>
                              </div>
                            </div>
                            @endif

                            @if($datos->departamentos == 1)
                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                @if($datos->pais != 1)<input type="hidden" name="pais" value="PERU">@endif
                                  <select class="form-control text-uppercase dynamic" id="dpto" name="departamento" data-dependent='provincia' required>
                                    <option value="">DEPARTAMENTO *</option>
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
                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                  <select class="form-control text-uppercase dynamic" id="provincia" name="provincia" data-dependent='distrito' required>
                                    <option value="">PROVINCIA *</option>
                                  </select>
                              </div>
                            </div>
                            @endif
                            @if($datos->distrito == 1)
                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                  <select class="form-control text-uppercase" id="distrito" name="distrito" required>
                                    <option value="">DISTRITO *</option>
                                  </select>
                              </div>
                            </div>
                            @endif
                          </div>

                            @if($datos->email == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="email">Correo electrónico <a href="#" id="editEmail" style='display:none;'>Editar</a></label>
                                </div>
                              </div>

                              <div class="col-sm-12 col-md-9">
                                <div class="form-group ">
                                  <div class="input-group mb-2">
                                    <input type="email" class="form-control" id="email2" name="email" placeholder="Correo electrónico" value="{{ old('email') }}">
                                  </div>
                                </div>

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
                                <input type="text" class="form-control text-uppercase" id="profesion" name="profesion"  required="" placeholder="Profesión-Ocupación/Profession-Occupation" value="{{ old('profesion') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->entidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="organizacion">Lugar donde labora / Place where he works <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="organizacion" name="organizacion" required  placeholder="Entidad / Entity" value="{{ old('organizacion') }}">
                              </div>
                            </div>
                            @endif

                            @if($datos->cargo == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group ">
                                <label for="cargo">Cargo / Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="cargo" name="cargo" required  placeholder="Cargo/Charge" value="{{ old('cargo') }}">
                              </div>
                            </div>
                            @endif
                            @if($datos->direccion == 1)
                            <div class="col-sm-12 col-md-8">
                              <div class="form-group ">
                                <label for="direccion">Dirección / Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="direccion" name="direccion" required=""  placeholder="Dirección" value="{{ old('direccion') }}">
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
                                  <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "9" id="celular" name="celular"  placeholder="999888777" value="{{ old('celular') }}" required>
                              </div>
                            </div>
                            @endif
                            @if($datos->email_labor == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="email_labor">Telefóno / Telephone <span class="text-danger">*</span> <a href="#" id="editCel" style='display:none;'>Editar</a></label>
                                  <input class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "9" id="email_labor" name="email_labor"  placeholder="01000000" value="{{ old('email_labor') }}" required>
                              </div>
                            </div>
                            @endif
                            @if($datos->discapacidad == 1)
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="discapacidad">Discapacidad / Disability </label>
                                  <input class="form-control text-uppercase" type="text" id="discapacidad" name="discapacidad"  placeholder="" value="{{ old('discapacidad') }}">
                              </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-12">
                              <h4 class="card-title my-4">Pregunta Ciudadana  </h4>
                            </div>

                            @if($datos->grupo == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="grupo">Temática <span class="text-danger">*</span></label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-9">
                                <div class="form-group">
                                  <div class="input-group mb-2">
                                    <div class="input-group-prepend w-100">
                                      <select class="form-control" required="" name="grupo" id="grupo" class="codigo_cel">
                                        <option value="">SELECCIONE</option>
                                        @foreach($grupos as $tipo)
                                        <option data-grupo="{{$tipo->id}}" {{ old('grupo')==$tipo->nombre? 'selected' : ''}} value="{{$tipo->nombre}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <input type="hidden" name="grupo_id" id="grupo_id" value="0">
                            </div>
                            @endif

                            @if($datos->grupo == 1)
                            <div class="row col-md-12">
                              <div class="col-sm-12 col-md-3">
                                <div class="form-group pt-2 mb-0">
                                  <label for="pregunta">Descripción de la pregunta <span class="text-danger">*</span></label>
                                </div>
                              </div>
                              <div class="col-sm-9 col-md-9">
                                <div class="form-group">
                                    <textarea required="" class="form-control text-" style="font-size: 16px;line-height: 25px;" name="pregunta" id="pregunta" maxlength="500" cols="30" rows="10">{{ old('pregunta') }}</textarea>
                                    <p class="text-right pt-2">
                                      máximo 500 caracteres incluyendo espacios
                                    </p>
                                </div>
                              </div>
                            </div>
                            @endif

                            
                            <div class="col-sm-12 col-md-8">
                              <div class="form-check">
                                    <input type="checkbox" id="autorizo" name="autorizo" class="form-check-input check_click" value="1">
                                  <label class="form-check-label" for="autorizo">
                                    Autorizo que se me notifique la confirmación de la recepción de mi pregunta de manera electrónica
                                  </label>
                                </div>
                            </div>
                            


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
                                  <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class="mdi mdi-checkbox-marked-circle "></i>ENVIAR PREGUNTA</button>

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

    $('#grupo').change(function(){
        let a = $(this).find(':selected').attr('data-grupo');
        $("#grupo_id").val(a);
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

</script>
@endsection