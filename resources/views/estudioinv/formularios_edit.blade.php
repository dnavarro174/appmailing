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
          <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Editar Formulario</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('grupo-estudio-investigacion_form.update', $datos->eventos_id) }}" method="post" enctype="multipart/form-data" >
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                      <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                          @if(count($errors)>0)
                            <div class="alert alert-danger">
                              Error al subir la imagen:<br>
                              <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                              </ul>
                            </div>
                          @endif
                        </div>
                      </div>
                    
                      <div class="form-group row">
                        <label for="plantilla" class="col-sm-2 col-form-label d-block">Descripción </label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción en HTML" class="form-control" name="descripcion" id="" cols="30" rows="5">{{ $datos->descripcion_form }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="form-group row" id="auto">
                        <label for="auto_conf" class="col-sm-8 col-form-label">Desea incluir imágenes de cabecera y pie de página en el formulario</label>
                        <div class="col-sm-4">
                          <div class="form-group row">
                            <div class="col-sm-12">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_email" name="imagen" type="checkbox" class="form-check-input" value="1" @if($datos->imagen == 1) checked @endif> SI <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div> {{-- end confirmacion --}}
                      
                      <div class="form-group row show_block @if($datos->imagen != 1) d-none @endif">
                        <label for="img_cabecera" class="col-sm-12 col-form-label d-block">Imagen Cabecera <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <img src="{{ asset('images/form')}}/{{$datos->img_cabecera}}" alt="Img header" class="img-fluid @if($datos->imagen != 1) d-none @endif">

                           <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>{{$datos->img_cabecera}} / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_cabecera" id="img_cabecera" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ $datos->img_cabecera }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>
                      
                      <div class="form-group row show_block @if($datos->imagen != 1) d-none @endif">
                        <label for="img_footer" class="col-sm-12 col-form-label d-block">Imagen Footer <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <img src="{{ asset('images/form')}}/{{$datos->img_footer}}" alt="Img footer" class="img-fluid @if($datos->imagen != 1) d-none @endif">
                           <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>{{$datos->img_footer}} / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_footer" id="img_footer" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ $datos->img_footer }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>
                      
                      

                      <div class="form-group row">
                        <label for="auto_conf" class="col-sm-2 col-form-label">Campos</label>
                        <div class="col-sm-10">

                          <div class="form-group row">
                            <h4>Acerca de la contribución / About the contribution</h4>
                          </div>

                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="tipo_doc" type="checkbox" class="form-check-input" value="1" @if($datos->tipo_doc == 1) checked @endif> Título de documento de trabajo  / Title of the paper<i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="dni" type="checkbox" class="form-check-input" value="1" @if($datos->dni == 1) checked @endif> Keywords <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="grupo" type="checkbox" class="form-check-input" value="1" @if($datos->grupo == 1) checked @endif> Resumen / Abstract  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="cv" type="checkbox" class="form-check-input" value="1" @if($datos->cv == 1) checked @endif> Cargar la investigación PDF  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>

                            
                            
                          </div>

                          <div class="form-group row">
                            <h4>Autor principal / Main autho</h4>
                          </div>

                          {{-- fila 2 --}}
                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="nombres" type="checkbox" class="form-check-input" value="1" @if($datos->nombres == 1) checked @endif> Nombre / First name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_paterno" type="checkbox" class="form-check-input" value="1" @if($datos->ap_paterno == 1) checked @endif> Apellidos / Last name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="entidad" type="checkbox" class="form-check-input" value="1" @if($datos->entidad == 1) checked @endif> Organización / Organization <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          {{-- fila 3 --}}
                          <div class="form-group row">
                            
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="departamentos" type="checkbox" class="form-check-input" value="1" @if($datos->departamentos == 1) checked @endif> Departamento, equipo de investigación / Department, research group <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="pais" type="checkbox" class="form-check-input" value="1" @if($datos->pais == 1) checked @endif> País / Country <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email" type="checkbox" class="form-check-input" value="1" @if($datos->email == 1) checked @endif> Correo electrónico institucional / Institutional email address <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group row">
                            <h4>Autor ponente / Presenting author </h4>
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="distrito" type="checkbox" class="form-check-input" value="1" @if($datos->distrito == 1) checked @endif> Título / Title <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_materno" type="checkbox" class="form-check-input" value="1" @if($datos->ap_materno == 1) checked @endif> Apellidos / Last name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_materno" type="checkbox" class="form-check-input" value="1" @if($datos->ap_materno == 1) checked @endif> Nombres / First name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="compago" type="checkbox" class="form-check-input" value="1" @if($datos->compago == 1) checked @endif> Fecha de nacimiento / Date of birth <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="decjur" type="checkbox" class="form-check-input" value="1" @if($datos->decjur == 1) checked @endif> País de nacimiento / Country of birth <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ficins" type="checkbox" class="form-check-input" value="1" @if($datos->ficins == 1) checked @endif> País de residencia / Country of residence <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="direccion" type="checkbox" class="form-check-input" value="1" @if($datos->direccion == 1) checked @endif> Nacionalidad / Nacionality <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="nvoucher" type="checkbox" class="form-check-input" value="1" @if($datos->nvoucher == 1) checked @endif> Número de pasaporte / Passport number <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="gradoprof" type="checkbox" class="form-check-input" value="1" @if($datos->gradoprof == 1) checked @endif> Adjunte foto del pasaporte / Upload a photo of your passport  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="discapacidad" type="checkbox" class="form-check-input" value="1" @if($datos->discapacidad == 1) checked @endif> Breve biografía / A short Biography <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="fechadepo" type="checkbox" class="form-check-input" value="1" @if($datos->fechadepo == 1) checked @endif>  Datos de la organización / Organization details <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          

                          <div class="form-group row">
                            <h4>Contacto Persona Detalles / Contact Person Details </h4>
                          </div>

                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="cargo" type="checkbox" class="form-check-input" value="1" @if($datos->cargo == 1) checked @endif> Nombre completo / Full name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="profesion" type="checkbox" class="form-check-input" value="1" @if($datos->profesion == 1) checked @endif> Posición / Position  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="provincia" type="checkbox" class="form-check-input" value="1" @if($datos->provincia == 1) checked @endif> Organización y departamento / Organization and department <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="celular" type="checkbox" class="form-check-input" value="1" @if($datos->celular == 1) checked @endif> Número de teléfono / Phone number <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email_labor" type="checkbox" class="form-check-input" value="1" @if($datos->email_labor == 1) checked @endif> Email  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>

                          {{-- fila 5 --}}
                          <div class="form-group row">

                            <div class="col-sm-8">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label" id="terminos">
                                    <input name="terminos" type="checkbox" class="form-check-input" value="1" @if($datos->terminos == 1) checked @endif> Términos y condiciones <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Finalizar</button>
                        <a href="{{ route('grupo-estudio-investigacion.index') }}" class="btn btn-light">Volver atrás</a>
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

@section('scripts')
<style>
.hidden_email, .hidden_whatsapp{display: none;}
</style>
<script>
console.log('Ready eventos');
$('document').ready(function(){

  // seleccionar todos

    $('#confirm_email').change(function() {
      if ($('#confirm_email').is(':checked')) {
        $('.hidden_email').css('display','block');
        $('#img_cabecera, #img_footer').prop('required',true);
      }else{
        $('.hidden_email').css('display','none');
        $('#img_cabecera, #img_footer').removeAttr('required');
      }

    });

    $('#confirm_msg').change(function() {

      if ($('#confirm_msg').is(':checked')) {
        $('.hidden_whatsapp').css('display','block');
      }else{
        $('.hidden_whatsapp').css('display','none');
      }

    });

});
</script>

@endsection