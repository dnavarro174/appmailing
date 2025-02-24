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
                  
                  <h4 class="card-title text-transform-none">Creación del Formulario</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('grupo-estudio-investigacion_form.store') }}" method="post" enctype="multipart/form-data" >
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
                          <textarea placeholder="Descripción (Puede ingresar codigo HTML * opcional)" class="form-control" name="descripcion" id="" cols="30" rows="5">{{ old('descripcion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="form-group row" id="auto">
                        <label for="auto_conf" class="col-sm-8 col-form-label">Desea incluir imágenes de cabecera y pie de página en el formulario</label>
                        <div class="col-sm-4 pr-0">
                          <div class="form-group row">
                            <div class="col-sm-12">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_email" name="imagen" type="checkbox" class="form-check-input" value="1"> SI <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            {{-- <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_msg" name="confirm_msg" type="checkbox" class="form-check-input" value="1"> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div> --}}
                          </div>

                        </div>
                      </div> {{-- end confirmacion --}}

                      {{-- Check cabezera y footer --}}

                      <div class="form-group row hidden_email">
                        <label for="img_cabecera" class="col-sm-12 col-form-label d-block">Imagen Cabecera <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                           <div class="dropify-wrapper" style="height: 130px;"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .jpg o .png / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_cabecera" id="img_cabecera" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ old('img_cabecera') }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>

                      <div class="form-group row hidden_email">
                        <label for="img_footer" class="col-sm-12 col-form-label d-block">Imagen Footer <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                           <div class="dropify-wrapper" style="height: 130px;"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .jpg o .png / 1113px ancho / 800 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" name="img_footer" id="img_footer" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ old('img_footer') }}">
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
                                    <input name="tipo_doc" type="checkbox" class="form-check-input" value="1" checked> Título de documento de trabajo  / Title of the paper<i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="dni" type="checkbox" class="form-check-input" value="1" checked> Keywords <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="grupo" type="checkbox" class="form-check-input" value="1" checked> Resumen / Abstract  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="cv" type="checkbox" class="form-check-input" value="1" checked> Cargar la investigación PDF  <i class="input-helper"></i></label>
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
                                    <input name="nombres" type="checkbox" class="form-check-input" value="1" checked> Nombre / First name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_paterno" type="checkbox" class="form-check-input" value="1" checked> Apellidos / Last name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="entidad" type="checkbox" class="form-check-input" value="1" checked> Organización / Organization <i class="input-helper"></i></label>
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
                                    <input name="departamentos" type="checkbox" class="form-check-input" value="1" checked> Departamento, equipo de investigación / Department, research group <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="pais" type="checkbox" class="form-check-input" value="1" checked> País / Country <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email" type="checkbox" class="form-check-input" value="1" checked> Correo electrónico institucional / Institutional email address <i class="input-helper"></i></label>
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
                                    <input name="distrito" checked="" type="checkbox" class="form-check-input" value="1"> Título / Title <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_materno" checked="" type="checkbox" class="form-check-input" value="1"> Apellidos / Last name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ap_materno" checked="" type="checkbox" class="form-check-input" value="1"> Nombres / First name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="compago" type="checkbox" class="form-check-input" value="1" checked> Fecha de nacimiento / Date of birth <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="decjur" type="checkbox" class="form-check-input" value="1" checked> País de nacimiento / Country of birth <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="ficins" type="checkbox" class="form-check-input" value="1" checked> País de residencia / Country of residence <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="direccion" type="checkbox" class="form-check-input" value="1" checked> Nacionalidad / Nacionality <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="nvoucher" type="checkbox" class="form-check-input" value="1" checked> Número de pasaporte / Passport number <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="gradoprof" type="checkbox" class="form-check-input" value="1" checked> Adjunte foto del pasaporte / Upload a photo of your passport  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="discapacidad" type="checkbox" class="form-check-input" value="1" checked> Breve biografía / A short Biography <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="fechadepo" type="checkbox" class="form-check-input" value="1" checked>  Datos de la organización / Organization details <i class="input-helper"></i></label>
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
                                    <input name="cargo" type="checkbox" class="form-check-input" value="1" checked> Nombre completo / Full name <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="profesion" type="checkbox" class="form-check-input" value="1" checked> Posición / Position  <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="provincia" type="checkbox" class="form-check-input" value="1" checked> Organización y departamento / Organization and department <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="celular" type="checkbox" class="form-check-input" value="1" checked> Número de teléfono / Phone number <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4 pr-0">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="email_labor" type="checkbox" class="form-check-input" value="1" checked> Email  <i class="input-helper"></i></label>
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
                                    <input name="terminos" type="checkbox" class="form-check-input" value="1" checked> Términos y condiciones <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <?php
                              echo "<input type='hidden' name='eventos_id' value='$eventos_id'>";
                            ?>
                            
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
  $('#confirm_email').prop('checked',false);

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