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
                  
                  <h4 class="card-title text-transform-none">Creación de las Plantillas HTML</h4>
                  
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('caii_plantilla.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      {{-- ASUNTO CONFIRMACION Pre-Registro --}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">1. PRE-INSCRIPCION</h4>
                      </div>
                      {{-- <div class="form-group row">
                        <label for="p_preregistro_conf" class="col-sm-3 col-form-label">Pre-Registro: Tendrá Confirmación? </label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_preregistro_conf" name="p_preregistro_conf" onchange="Confirmacion('p_preregistro_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_preregistro_email')==1 or old('p_preregistro_msg')==1)?'selected':'' }} >SI</option>
                          </select>
                        </div>
                      </div> --}}
                      <div class="form-group row p_preregistro_conf_1">
                        <label for="p_preregistro_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_preregistro_email" onclick="checkMostrar('p_preregistro','email','p_preregistro_correo')" id="p_preregistro_email"  type="checkbox" class="form-check-input" value="{{ old('p_preregistro_email') }}" {{ (old('p_preregistro_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_preregistro','msg','p_preregistro_w')" name="p_preregistro_msg" id="p_preregistro_msg" type="checkbox" class="form-check-input" value="{{ old('p_preregistro_msg') }}" {{ (old('p_preregistro_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>{{-- font-weight-bold --}}

                      @php 
                        $pre = '[Presencial]';
                        $vir = '[Virtual]';
                      @endphp

                      <div class="p_preregistro_correo form-group row {{ (old('p_preregistro_email')==1)?'':'d-none' }}">{{--  --}}
                        <label for="p_preregistro_asunto" class="col-sm-3 col-form-label">Asunto {{ $pre }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_preregistro_asunto" id="p_preregistro_asunto" placeholder="Asunto" value="{{ old('p_preregistro_asunto') }}">
                        </div>
                      </div>
                      <div  class="p_preregistro_correo form-group row {{ (old('p_preregistro_email')==1)?'':'d-none' }}">
                        <label for="p_preregistro_asunto_v" class="col-sm-3 col-form-label">Asunto {{ $vir }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_preregistro_asunto_v" id="p_preregistro_asunto_v" placeholder="Asunto" value="{{ old('p_preregistro_asunto_v') }}">
                        </div>
                      </div>

                        
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div class="form-group row ">
                        <label for="p_preregistro" class="col-sm-12 col-form-label d-block">Pantallazo final Preinscripción (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Pantallazo final Preinscripción (HTML)" class="form-control" name="p_preregistro" id="p_preregistro" cols="30" rows="6">{{ old('p_preregistro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_prereg.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres 
                          </div>
                        </div>
                      </div>
                      <div class=" form-group row ">
                        <label for="p_preregistro_v" class="col-sm-12 col-form-label d-block">Pantallazo final Preinscripción (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Pantallazo final Preinscripción (HTML)" class="form-control" name="p_preregistro_v" id="p_preregistro_v" cols="30" rows="6">{{ old('p_preregistro_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_prereg.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres 
                          </div>
                        </div>
                      </div>

                      
                      
                      <div  class="p_preregistro_w form-group row {{ (old('p_preregistro_msg')==1)?'':'d-none' }}">
                        <label for="p_preregistro_2" class="col-sm-12 col-form-label d-block">Mensaje de notificación final (Whatsapp) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mensaje de notificación final (Whatsapp)" class="form-control" name="p_preregistro_2" id="p_preregistro_2" cols="30" rows="6">{{ old('p_preregistro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            1,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div  class="p_preregistro_w form-group row {{ (old('p_preregistro_msg')==1)?'':'d-none' }}">
                        <label for="p_preregistro_2_v" class="col-sm-12 col-form-label d-block">Mensaje de notificación final (Whatsapp) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mensaje de notificación final (Whatsapp)" class="form-control" name="p_preregistro_2_v" id="p_preregistro_2_v" cols="30" rows="6">{{ old('p_preregistro_2_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            1,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="p_preregistro_correo form-group row {{ (old('p_preregistro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_preregistro" class="col-sm-12 col-form-label d-block">Mailing de Confirmación (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mailing Confirmación Pre-Registro (HTML)" class="form-control" name="p_conf_preregistro" cols="30" rows="6">{{ old('p_conf_preregistro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download href="{{url('')}}/files/plantillas/plantilla_pre_confirmacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="p_preregistro_correo form-group row {{ (old('p_preregistro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_preregistro_v" class="col-sm-12 col-form-label d-block">Mailing de Confirmación (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mailing Confirmación Pre-Registro (HTML)" class="form-control" name="p_conf_preregistro_v" cols="30" rows="6">{{ old('p_conf_preregistro_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download href="{{url('')}}/files/plantillas/plantilla_pre_confirmacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>


                      {{-- plantilla 2 --}}
                      {{-- ASUNTO CONFIRMACION Usuario --}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">2. APROBACIÓN</h4>
                      </div>
                      {{-- <div class="form-group row">
                        <label for="p_conf_inscripcion_conf" class="col-sm-4 col-form-label">Usuario y contraseña: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_conf_inscripcion_conf" name="p_conf_inscripcion_conf" onchange="Confirmacion('p_conf_inscripcion_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_conf_inscripcion_email')==1 or old('p_conf_inscripcion_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div> --}}
                      <div class="form-group row">
                        <label for="p_conf_inscripcion_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_conf_inscripcion_email" onclick="checkMostrar('p_conf_inscripcion','email','p_conf_inscripcion_correo')" id="p_conf_inscripcion_email"  type="checkbox" class="form-check-input" value="{{ old('p_conf_inscripcion_email') }}" {{ (old('p_conf_inscripcion_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                    {{--  <input name="p_preregistro_email" onclick="checkMostrar('p_preregistro','email','p_preregistro_correo')" id="p_preregistro_email"  type="checkbox" class="form-check-input" value="{{ old('p_preregistro_email') }}" {{ (old('p_preregistro_email')==1)?'checked':'' }}> Email  --}}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_conf_inscripcion','msg','p_conf_inscripcion_w')" name="p_conf_inscripcion_msg" id="p_conf_inscripcion_msg" type="checkbox" class="form-check-input" value="{{ old('p_conf_inscripcion_msg') }}" {{ (old('p_conf_inscripcion_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="p_conf_inscripcion_correo form-group row {{ (old('p_conf_inscripcion_email')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_asunto" class="col-sm-3 col-form-label text-">Asunto Usuario y contraseña {{ $pre }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_conf_inscripcion_asunto" id="p_conf_inscripcion_asunto" placeholder="Asunto" value="{{ old('p_conf_inscripcion_asunto') }}">
                        </div>
                      </div>

                      <div class="p_conf_inscripcion_correo form-group row {{ (old('p_conf_inscripcion_email')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_asunto_v" class="col-sm-3 col-form-label text-">Asunto Usuario y contraseña {{ $vir }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_conf_inscripcion_asunto_v" id="p_conf_inscripcion_asunto_v" placeholder="Asunto" value="{{ old('p_conf_inscripcion_asunto_v') }}">
                        </div>
                      </div>

                      {{-- end ASUNTO CONFIRMACION --}}
                      
                      <div class="p_conf_inscripcion_correo form-group row {{ (old('p_conf_inscripcion_email')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion" class="col-sm-12 col-form-label d-block">Mailing Usuario y Contraseña (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mailing Usuario y Contraseña (HTML)" class="form-control" name="p_conf_inscripcion" id="" cols="30" rows="6">{{ old('p_conf_inscripcion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_usuario.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div id="" class="p_conf_inscripcion_correo form-group row {{ (old('p_conf_inscripcion_email')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_v" class="col-sm-12 col-form-label d-block">Mailing Usuario y Contraseña (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (HTML)" class="form-control" name="p_conf_inscripcion_v" id="" cols="30" rows="6">{{ old('p_conf_inscripcion_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_usuario.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div id="" class="p_conf_inscripcion_w form-group row {{ (old('p_conf_inscripcion_msg')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_2" class="col-sm-12 col-form-label d-block">Mensaje de Usuario y Contraseña (Whatsapp) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (Whatsapp)" class="form-control" name="p_conf_inscripcion_2" id="" cols="30" rows="6">{{ old('p_conf_inscripcion_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div id="" class="p_conf_inscripcion_w form-group row {{ (old('p_conf_inscripcion_msg')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_2_v" class="col-sm-12 col-form-label d-block">Mensaje de Usuario y Contraseña (Whatsapp) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (Whatsapp)" class="form-control" name="p_conf_inscripcion_2_v" id="" cols="30" rows="6">{{ old('p_conf_inscripcion_2_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 3 --}}
                      {{-- ASUNTO CONFIRMACION Confirmación de registro --}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">3. INSCRIPCIÓN</h4>
                      </div>
                      {{-- <div class="form-group row">
                        <label for="p_conf_registro_conf" class="col-sm-6 col-form-label strong text-bold">Confirmación de registro, actividad y gafete: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_conf_registro_conf" name="p_conf_registro_conf" onchange="Confirmacion('p_conf_registro_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_conf_registro_email')==1 or old('p_conf_registro_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div> --}}
                      <div class="form-group row">
                        <label for="p_conf_registro_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_conf_registro_email" onclick="checkMostrar('p_conf_registro','email','p_conf_registro_correo')" id="p_conf_registro_email"  type="checkbox" class="form-check-input" value="{{ old('p_conf_registro_email') }}" {{ (old('p_conf_registro_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_conf_registro','msg','p_conf_registro_w')" name="p_conf_registro_msg" id="p_conf_registro_msg" type="checkbox" class="form-check-input" value="{{ old('p_conf_registro_msg') }}" {{ (old('p_conf_registro_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="p_conf_registro_correo form-group row {{ (old('p_conf_registro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_asunto" class="col-sm-3 col-form-label text-">Asunto Confirmación Registro {{ $pre }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_conf_registro_asunto" id="p_conf_registro_asunto" placeholder="Asunto" value="{{ old('p_conf_registro_asunto') }}">
                        </div>
                      </div>
                      <div class="p_conf_registro_correo form-group row {{ (old('p_conf_registro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_asunto_v" class="col-sm-3 col-form-label text-">Asunto Confirmación Registro {{ $vir }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_conf_registro_asunto_v" id="p_conf_registro_asunto_v" placeholder="Asunto" value="{{ old('p_conf_registro_asunto_v') }}">
                        </div>
                      </div>

                      {{-- end ASUNTO CONFIRMACION --}}

                      <div class="p_conf_registro_correo form-group row {{ (old('p_conf_registro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_registro" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (HTML)" class="form-control" name="p_conf_registro" cols="30" rows="6">{{ old('p_conf_registro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_confirmacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div id="" class="p_conf_registro_correo form-group row {{ (old('p_conf_registro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_v" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (HTML)" class="form-control" name="p_conf_registro_v" cols="30" rows="6">{{ old('p_conf_registro_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_confirmacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      
                      <div class="p_conf_registro_w form-group row {{ (old('p_conf_registro_msg')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_2" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (Whatsapp) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (Whatsapp)" class="form-control" name="p_conf_registro_2" cols="30" rows="6">{{ old('p_conf_registro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="p_conf_registro_w form-group row {{ (old('p_conf_registro_msg')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_2_v" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (Whatsapp) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (Whatsapp)" class="form-control" name="p_conf_registro_2_v" cols="30" rows="6">{{ old('p_conf_registro_2_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class=" form-group row">
                        <label for="p_conf_registro_gracias" class="col-sm-12 col-form-label d-block">Pantallazo confirmación al finalizar el registro {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Sus datos han sido registrados correctamente, se le enviará automáticamente un correo electrónico de confirmación con su GAFETE personalizado." class="form-control" name="p_conf_registro_gracias" id="" cols="30" rows="6">{{ old('p_conf_registro_gracias') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_pantallazo.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class=" form-group row">
                        <label for="p_conf_registro_gracias_v" class="col-sm-12 col-form-label d-block">Pantallazo confirmación al finalizar el registro {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Sus datos han sido registrados correctamente, se le enviará automáticamente un correo electrónico de confirmación con su GAFETE personalizado." class="form-control" name="p_conf_registro_gracias_v" id="" cols="30" rows="6">{{ old('p_conf_registro_gracias_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_pantallazo.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 4 --}}
                      {{-- ASUNTO CONFIRMACION Recordatorio p_recordatorio--}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">4. RECORDACIÓN</h4>
                      </div>

                      {{-- <div class="form-group row">
                        <label for="p_recordatorio_conf" class="col-sm-6 col-form-label strong text-bold">Recordatorio: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_recordatorio_conf" name="p_recordatorio_conf" onchange="Confirmacion('p_recordatorio_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_recordatorio_email')==1 or old('p_recordatorio_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div> --}}
                      <div class="form-group row">
                        <label for="p_recordatorio_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_recordatorio_email" onclick="checkMostrar('p_recordatorio','email','p_recordatorio_correo')" id="p_recordatorio_email"  type="checkbox" class="form-check-input" value="{{ old('p_recordatorio_email') }}" {{ (old('p_recordatorio_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_recordatorio','msg','p_recordatorio_w')" name="p_recordatorio_msg" id="p_recordatorio_msg" type="checkbox" class="form-check-input" value="{{ old('p_recordatorio_msg') }}" {{ (old('p_recordatorio_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="p_recordatorio_correo form-group row {{ (old('p_recordatorio_email')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_asunto" class="col-sm-3 col-form-label text-">Asunto Recordatorio {{ $pre }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_recordatorio_asunto" id="p_recordatorio_asunto" placeholder="Asunto" value="{{ old('p_recordatorio_asunto') }}">
                        </div>
                      </div>
                      <div class="p_recordatorio_correo form-group row {{ (old('p_recordatorio_email')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_asunto_v" class="col-sm-3 col-form-label text-">Asunto Recordatorio {{ $vir }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_recordatorio_asunto_v" id="p_recordatorio_asunto_v" placeholder="Asunto" value="{{ old('p_recordatorio_asunto_v') }}">
                        </div>
                      </div>
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div class="p_recordatorio_correo form-group row {{ (old('p_recordatorio_email')==1)?'':'d-none' }}">
                        <label for="p_recordatorio" class="col-sm-12 col-form-label d-block">Mailing Recordatorio (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mailing Recordatorio (HTML)" class="form-control" name="p_recordatorio" id="" cols="30" rows="6">{{ old('p_recordatorio') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_recordatorio.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="p_recordatorio_correo form-group row {{ (old('p_recordatorio_email')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_v" class="col-sm-12 col-form-label d-block">Mailing Recordatorio (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Mailing Recordatorio (HTML)" class="form-control" name="p_recordatorio_v" id="" cols="30" rows="6">{{ old('p_recordatorio_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_recordatorio.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div  class="p_recordatorio_w form-group row {{ (old('p_recordatorio_msg')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_2" class="col-sm-12 col-form-label d-block">Recordatorio (Whatsapp) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (Whatsapp)" class="form-control" name="p_recordatorio_2" id="" cols="30" rows="6">{{ old('p_recordatorio_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div  class="p_recordatorio_w form-group row {{ (old('p_recordatorio_msg')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_2_v" class="col-sm-12 col-form-label d-block">Recordatorio (Whatsapp) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (Whatsapp)" class="form-control" name="p_recordatorio_2_v" id="" cols="30" rows="6">{{ old('p_recordatorio_2_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 5 --}}
                      {{-- ASUNTO CONFIRMACION Negación p_negacion--}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">5. NEGACIÓN</h4>
                      </div>
                      
                      <div class="form-group row ">
                        <label for="p_negacion_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_negacion_email" onclick="checkMostrar('p_negacion','email','p_negacion_correo')" id="p_negacion_email"  type="checkbox" class="form-check-input" value="{{ old('p_negacion_email') }}" {{ (old('p_negacion_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_negacion','msg','p_negacion_w')" name="p_negacion_msg" id="p_negacion_msg" type="checkbox" class="form-check-input" value="{{ old('p_negacion_msg') }}" {{ (old('p_negacion_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="p_negacion_correo form-group row {{ (old('p_negacion_email')==1)?'':'d-none' }}">
                        <label for="p_negacion_asunto" class="col-sm-3 col-form-label text-">Asunto Negación {{ $pre }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_negacion_asunto" id="p_negacion_asunto" placeholder="Asunto" value="{{ old('p_negacion_asunto') }}">
                        </div>
                      </div>
                      <div class="p_negacion_correo form-group row {{ (old('p_negacion_email')==1)?'':'d-none' }}">
                        <label for="p_negacion_asunto_v" class="col-sm-3 col-form-label text-">Asunto Negación {{ $vir }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_negacion_asunto_v" id="p_negacion_asunto_v" placeholder="Asunto" value="{{ old('p_negacion_asunto_v') }}">
                        </div>
                      </div>
                      
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div class="p_negacion_correo form-group row {{ (old('p_negacion_email')==1)?'':'d-none' }}">
                        <label for="p_negacion" class="col-sm-12 col-form-label d-block">Negación (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (HTML)" class="form-control" name="p_negacion" cols="30" rows="6">{{ old('p_negacion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_negacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="p_negacion_correo form-group row {{ (old('p_negacion_email')==1)?'':'d-none' }}">
                        <label for="p_negacion_v" class="col-sm-12 col-form-label d-block">Negación (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (HTML)" class="form-control" name="p_negacion_v" cols="30" rows="6">{{ old('p_negacion_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_negacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div class="p_negacion_w form-group row {{ (old('p_negacion_msg')==1)?'':'d-none' }}">
                        <label for="p_negacion_2" class="col-sm-12 col-form-label d-block">Negación (Whatsapp) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (Whatsapp)" class="form-control" name="p_negacion_2" cols="30" rows="6">{{ old('p_negacion_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="p_negacion_w form-group row {{ (old('p_negacion_msg')==1)?'':'d-none' }}">
                        <label for="p_negacion_2_v" class="col-sm-12 col-form-label d-block">Negación (Whatsapp) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (Whatsapp)" class="form-control" name="p_negacion_2_v" cols="30" rows="6">{{ old('p_negacion_2_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 6 --}}
                      {{-- ASUNTO CONFIRMACION Baja de Evento p_baja_evento --}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">6. BAJA DE EVENTO</h4>
                      </div>

                      <div class="form-group row">
                        <label for="p_baja_evento_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_baja_evento_email" onclick="checkMostrar('p_baja_evento','email','p_baja_evento_correo')" id="p_baja_evento_email"  type="checkbox" class="form-check-input" value="{{ old('p_baja_evento_email') }}" {{ (old('p_baja_evento_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_baja_evento','msg','p_baja_evento_w')" name="p_baja_evento_msg" id="p_baja_evento_msg" type="checkbox" class="form-check-input" value="{{ old('p_baja_evento_msg') }}" {{ (old('p_baja_evento_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="p_baja_evento_correo form-group row {{ (old('p_baja_evento_email')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_asunto" class="col-sm-3 col-form-label text-">Asunto Baja de Evento {{ $pre }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_baja_evento_asunto" id="p_baja_evento_asunto" placeholder="Asunto" value="{{ old('p_baja_evento_asunto') }}">
                        </div>
                      </div>
                      <div class="p_baja_evento_correo form-group row {{ (old('p_baja_evento_email')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_asunto_v" class="col-sm-3 col-form-label text-">Asunto Baja de Evento {{ $vir }} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_baja_evento_asunto_v" id="p_baja_evento_asunto_v" placeholder="Asunto" value="{{ old('p_baja_evento_asunto_v') }}">
                        </div>
                      </div>
                      
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div class="p_baja_evento_correo form-group row {{ (old('p_baja_evento_email')==1)?'':'d-none' }}">
                        <label for="p_baja_evento" class="col-sm-12 col-form-label d-block">Baja de Evento Pantallazo (HTML) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (HTML)" class="form-control" name="p_baja_evento" cols="30" rows="6">{{ old('p_baja_evento') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_debaja.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="p_baja_evento_correo form-group row {{ (old('p_baja_evento_email')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_v" class="col-sm-12 col-form-label d-block">Baja de Evento Pantallazo (HTML) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (HTML)" class="form-control" name="p_baja_evento_v" cols="30" rows="6">{{ old('p_baja_evento_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_debaja.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div class="p_baja_evento_w form-group row {{ (old('p_baja_evento_msg')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_2" class="col-sm-12 col-form-label d-block">Baja de Evento (Whatsapp) {{ $pre }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (Whatsapp)" class="form-control" name="p_baja_evento_2" cols="30" rows="6">{{ old('p_baja_evento_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="p_baja_evento_w form-group row {{ (old('p_baja_evento_msg')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_2_v" class="col-sm-12 col-form-label d-block">Baja de Evento (Whatsapp) {{ $vir }}</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (Whatsapp)" class="form-control" name="p_baja_evento_2_v" cols="30" rows="6">{{ old('p_baja_evento_2_v') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 7 --}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">7. PANTALLAZO PREINSCRIPCIONES CERRADAS </h4>
                        {{-- <p>Para no acceder al formulario de preinscripciones.</p> --}}
                      </div>

                      <div class="form-group row">
                        <label for="p_preinscripcion_cerrado" class="col-sm-12 col-form-label d-block">Preinscripciones Cerradas (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Preinscripciones Cerradas (HTML)" class="form-control" name="p_preinscripcion_cerrado" cols="30" rows="6">{{ old('p_preinscripcion_cerrado') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_pre_cerrado.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="form-group row">
                        <label for="p_preinscripcion_cerrado_2" class="col-sm-12 col-form-label d-block">Preinscripciones Cerradas (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Preinscripciones Cerradas (Whatsapp)" class="form-control" name="p_preinscripcion_cerrado_2" id="" cols="30" rows="6">{{ old('p_preinscripcion_cerrado_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div> -->

                      {{-- plantilla 8 --}}
                      <div class="row">
                        <h4 class="card-title text-transform-none">8. PANTALLAZO EVENTO CERRADO</h4>
                        {{-- <p>Para no acceder al login de registro.</p> --}}
                      </div>
                      <div class="form-group row">
                        <label for="p_inscripcion_cerrado" class="col-sm-12 col-form-label d-block">Evento Cerrado (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Evento Cerrado (HTML)" class="form-control" name="p_inscripcion_cerrado" cols="30" rows="6">{{ old('p_inscripcion_cerrado') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_evento_cerrado.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="form-group row">
                        <label for="p_inscripcion_cerrado_2" class="col-sm-12 col-form-label d-block">Incripciones Cerradas (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Incripciones Cerradas (Whatsapp)" class="form-control" name="p_inscripcion_cerrado_2" id="" cols="30" rows="6">{{ old('p_inscripcion_cerrado_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div> -->
                      <?php
                        echo "<input type='hidden' name='eventos_id' value='$eventos_id'>";
                      ?>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y Continuar Paso 3</button>
                        
                        <!-- <a href="{{ route('caiieventos.create') }}" class="btn btn-light">Volver al listado</a> -->{{-- caii.index --}}
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
<script>

</script>
@endsection