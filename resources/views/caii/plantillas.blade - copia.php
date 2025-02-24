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
                      <div class="form-group row">
                        <label for="p_preregistro_conf" class="col-sm-3 col-form-label">Pre-Registro: Tendrá Confirmación? </label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_preregistro_conf" name="p_preregistro_conf" onchange="Confirmacion('p_preregistro_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_preregistro_email')==1 or old('p_preregistro_msg')==1)?'selected':'' }} >SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row {{ (old('p_preregistro_email')==1 or old('p_preregistro_msg')==1)?'':'d-none' }} p_preregistro_conf_1">
                        <label for="p_preregistro_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_preregistro_email" onclick="checkMostrar('p_preregistro','email','1')" id="p_preregistro_email"  type="checkbox" class="form-check-input" value="{{ old('p_preregistro_email') }}" {{ (old('p_preregistro_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_preregistro','msg','msg1')" name="p_preregistro_msg" id="p_preregistro_msg" type="checkbox" class="form-check-input" value="{{ old('p_preregistro_msg') }}" {{ (old('p_preregistro_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id="p_preregistro_email_1" class="form-group row {{ (old('p_preregistro_email')==1)?'':'d-none' }}">
                        <label for="p_preregistro_asunto" class="col-sm-3 col-form-label text-">Asunto Pre - Registro<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_preregistro_asunto" id="p_preregistro_asunto" placeholder="Asunto" value="{{ old('p_preregistro_asunto') }}">
                        </div>
                      </div>
                        
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div id="p_preregistro_1" class="form-group row {{ (old('p_preregistro_email')==1)?'':'d-none' }} ">
                        <label for="p_preregistro" class="col-sm-12 col-form-label d-block">Pre - Registro (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Pre - Registro (HTML)" class="form-control" name="p_preregistro" id="" cols="30" rows="10">{{ old('p_preregistro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_prereg.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres 
                          </div>
                        </div>
                      </div>
                      
                      <div id="p_preregistro_msg1" class="form-group row {{ (old('p_preregistro_msg')==1)?'':'d-none' }}">
                        <label for="p_preregistro_2" class="col-sm-12 col-form-label d-block">Pre - Registro (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Pre - Registro (Whatsapp)" class="form-control" name="p_preregistro_2" id="" cols="30" rows="10">{{ old('p_preregistro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            1,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="p_conf_preregistro" class="col-sm-12 col-form-label d-block">Confirmación Pre-Registro (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación Pre-Registro (HTML)" class="form-control" name="p_conf_preregistro" cols="30" rows="10">{{ old('p_conf_preregistro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download href="{{url('')}}/files/plantillas/plantilla_pre_confirmacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 2 --}}
                      {{-- ASUNTO CONFIRMACION Usuario --}}
                      <div class="form-group row">
                        <label for="p_conf_inscripcion_conf" class="col-sm-4 col-form-label">Usuario y contraseña: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_conf_inscripcion_conf" name="p_conf_inscripcion_conf" onchange="Confirmacion('p_conf_inscripcion_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_conf_inscripcion_email')==1 or old('p_conf_inscripcion_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row {{ (old('p_conf_inscripcion_email')==1 or old('p_conf_inscripcion_msg')==1)?'':'d-none' }} p_conf_inscripcion_conf_1">
                        <label for="p_conf_inscripcion_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_conf_inscripcion_email" onclick="checkMostrar('p_conf_inscripcion','email','1')" id="p_conf_inscripcion_email"  type="checkbox" class="form-check-input" value="{{ old('p_conf_inscripcion_email') }}" {{ (old('p_conf_inscripcion_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_conf_inscripcion','msg','msg1')" name="p_conf_inscripcion_msg" id="p_conf_inscripcion_msg" type="checkbox" class="form-check-input" value="{{ old('p_conf_inscripcion_msg') }}" {{ (old('p_conf_inscripcion_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id="p_conf_inscripcion_email_1" class="form-group row {{ (old('p_conf_inscripcion_email')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_asunto" class="col-sm-3 col-form-label text-">Asunto Usuario y contraseña <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_conf_inscripcion_asunto" id="p_conf_inscripcion_asunto" placeholder="Asunto" value="{{ old('p_conf_inscripcion_asunto') }}">
                        </div>
                      </div>

                      {{-- end ASUNTO CONFIRMACION --}}
                      
                      <div id="p_conf_inscripcion_1" class="form-group row {{ (old('p_conf_inscripcion_email')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion" class="col-sm-12 col-form-label d-block">Usuario y Contraseña (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (HTML)" class="form-control" name="p_conf_inscripcion" id="" cols="30" rows="10">{{ old('p_conf_inscripcion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_usuario.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div id="p_conf_inscripcion_msg1" class="form-group row {{ (old('p_conf_inscripcion_msg')==1)?'':'d-none' }}">
                        <label for="p_conf_inscripcion_2" class="col-sm-12 col-form-label d-block">Usuario y Contraseña (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Usuario y Contraseña (Whatsapp)" class="form-control" name="p_conf_inscripcion_2" id="" cols="30" rows="10">{{ old('p_conf_inscripcion_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 3 --}}
                      {{-- ASUNTO CONFIRMACION Confirmación de registro --}}
                      <div class="form-group row">
                        <label for="p_conf_registro_conf" class="col-sm-6 col-form-label strong text-bold">Confirmación de registro, actividad y gafete: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_conf_registro_conf" name="p_conf_registro_conf" onchange="Confirmacion('p_conf_registro_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_conf_registro_email')==1 or old('p_conf_registro_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row {{ (old('p_conf_registro_email')==1 or old('p_conf_registro_msg')==1)?'':'d-none' }} p_conf_registro_conf_1">
                        <label for="p_conf_registro_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_conf_registro_email" onclick="checkMostrar('p_conf_registro','email','1')" id="p_conf_registro_email"  type="checkbox" class="form-check-input" value="{{ old('p_conf_registro_email') }}" {{ (old('p_conf_registro_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_conf_registro','msg','msg1')" name="p_conf_registro_msg" id="p_conf_registro_msg" type="checkbox" class="form-check-input" value="{{ old('p_conf_registro_msg') }}" {{ (old('p_conf_registro_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id="p_conf_registro_email_1" class="form-group row {{ (old('p_conf_registro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_asunto" class="col-sm-3 col-form-label text-">Asunto Confirmación Registro<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_conf_registro_asunto" id="p_conf_registro_asunto" placeholder="Asunto" value="{{ old('p_conf_registro_asunto') }}">
                        </div>
                      </div>

                      {{-- end ASUNTO CONFIRMACION --}}

                      <div id="p_conf_registro_1" class="form-group row {{ (old('p_conf_registro_email')==1)?'':'d-none' }}">
                        <label for="p_conf_registro" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (HTML)" class="form-control" name="p_conf_registro" id="" cols="30" rows="10">{{ old('p_conf_registro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_confirmacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div id="p_conf_registro_msg1" class="form-group row {{ (old('p_conf_registro_msg')==1)?'':'d-none' }}">
                        <label for="p_conf_registro_2" class="col-sm-12 col-form-label d-block">Confirmación de registro, actividad y gafete (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro, actividad y gafete (Whatsapp)" class="form-control" name="p_conf_registro_2" id="" cols="30" rows="10">{{ old('p_conf_registro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="p_conf_registro_gracias" class="col-sm-12 col-form-label d-block">Pantallazo confirmación al finalizar el registro</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Sus datos han sido registrados correctamente, se le enviará automáticamente un correo electrónico de confirmación con su GAFETE personalizado." class="form-control" name="p_conf_registro_gracias" id="" cols="30" rows="10">{{ old('p_conf_registro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_pantallazo.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 4 --}}
                      {{-- ASUNTO CONFIRMACION Recordatorio p_recordatorio--}}
                      <div class="form-group row">
                        <label for="p_recordatorio_conf" class="col-sm-6 col-form-label strong text-bold">Recordatorio: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_recordatorio_conf" name="p_recordatorio_conf" onchange="Confirmacion('p_recordatorio_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_recordatorio_email')==1 or old('p_recordatorio_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row {{ (old('p_recordatorio_email')==1 or old('p_recordatorio_msg')==1)?'':'d-none' }}  p_recordatorio_conf_1">
                        <label for="p_recordatorio_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_recordatorio_email" onclick="checkMostrar('p_recordatorio','email','1')" id="p_recordatorio_email"  type="checkbox" class="form-check-input" value="{{ old('p_recordatorio_email') }}" {{ (old('p_recordatorio_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_recordatorio','msg','msg1')" name="p_recordatorio_msg" id="p_recordatorio_msg" type="checkbox" class="form-check-input" value="{{ old('p_recordatorio_msg') }}" {{ (old('p_recordatorio_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id="p_recordatorio_email_1" class="form-group row {{ (old('p_recordatorio_email')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_asunto" class="col-sm-3 col-form-label text-">Asunto Recordatorio<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_recordatorio_asunto" id="p_recordatorio_asunto" placeholder="Asunto" value="{{ old('p_recordatorio_asunto') }}">
                        </div>
                      </div>
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div id="p_recordatorio_1" class="form-group row {{ (old('p_recordatorio_email')==1)?'':'d-none' }}">
                        <label for="p_recordatorio" class="col-sm-12 col-form-label d-block">Recordatorio (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (HTML)" class="form-control" name="p_recordatorio" id="" cols="30" rows="10">{{ old('p_recordatorio') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_recordatorio.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div id="p_recordatorio_msg1" class="form-group row {{ (old('p_recordatorio_msg')==1)?'':'d-none' }}">
                        <label for="p_recordatorio_2" class="col-sm-12 col-form-label d-block">Recordatorio (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (Whatsapp)" class="form-control" name="p_recordatorio_2" id="" cols="30" rows="10">{{ old('p_recordatorio_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 5 --}}
                      {{-- ASUNTO CONFIRMACION Negación p_negacion--}}
                      <div class="form-group row">
                        <label for="p_negacion_conf" class="col-sm-6 col-form-label strong text-bold">Negación: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_negacion_conf" name="p_negacion_conf" onchange="Confirmacion('p_negacion_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_negacion_email')==1 or old('p_negacion_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row {{ (old('p_negacion_email')==1 or old('p_negacion_msg')==1)?'':'d-none' }} p_negacion_conf_1">
                        <label for="p_negacion_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_negacion_email" onclick="checkMostrar('p_negacion','email','1')" id="p_negacion_email"  type="checkbox" class="form-check-input" value="{{ old('p_negacion_email') }}" {{ (old('p_negacion_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_negacion','msg','msg1')" name="p_negacion_msg" id="p_negacion_msg" type="checkbox" class="form-check-input" value="{{ old('p_negacion_msg') }}" {{ (old('p_negacion_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id="p_negacion_email_1" class="form-group row {{ (old('p_negacion_email')==1)?'':'d-none' }}">
                        <label for="p_negacion_asunto" class="col-sm-3 col-form-label text-">Asunto Negación<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_negacion_asunto" id="p_negacion_asunto" placeholder="Asunto" value="{{ old('p_negacion_asunto') }}">
                        </div>
                      </div>
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div id="p_negacion_1" class="form-group row {{ (old('p_negacion_email')==1)?'':'d-none' }}">
                        <label for="p_negacion" class="col-sm-12 col-form-label d-block">Negación (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (HTML)" class="form-control" name="p_negacion" id="" cols="30" rows="10">{{ old('p_negacion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_negacion.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div id="p_negacion_msg1" class="form-group row {{ (old('p_negacion_msg')==1)?'':'d-none' }}">
                        <label for="p_negacion_2" class="col-sm-12 col-form-label d-block">Negación (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Negación (Whatsapp)" class="form-control" name="p_negacion_2" id="" cols="30" rows="10">{{ old('p_negacion_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 6 --}}
                      {{-- ASUNTO CONFIRMACION Baja de Evento p_baja_evento --}}
                      <div class="form-group row">
                        <label for="p_baja_evento_conf" class="col-sm-6 col-form-label strong text-bold">Baja de Evento: Tendrá Confirmación?</label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase valid" id="p_baja_evento_conf" name="p_baja_evento_conf" onchange="Confirmacion('p_baja_evento_conf')" aria-invalid="false">
                            <option value="0">NO</option>
                            <option value="1" {{ (old('p_baja_evento_email')==1 or old('p_baja_evento_msg')==1)?'selected':'' }}>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row {{ (old('p_baja_evento_email')==1 or old('p_baja_evento_msg')==1)?'':'d-none' }}  p_baja_evento_conf_1">
                        <label for="p_baja_evento_email" class="col-sm-3 col-form-label text-">Confirmación por</label>
                        <div class="col-sm-9">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input name="p_baja_evento_email" onclick="checkMostrar('p_baja_evento','email','1')" id="p_baja_evento_email"  type="checkbox" class="form-check-input" value="{{ old('p_baja_evento_email') }}" {{ (old('p_baja_evento_email')==1)?'checked':'' }}> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input onclick="checkMostrar('p_baja_evento','msg','msg1')" name="p_baja_evento_msg" id="p_baja_evento_msg" type="checkbox" class="form-check-input" value="{{ old('p_baja_evento_msg') }}" {{ (old('p_baja_evento_msg')==1)?'checked':'' }}> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div id="p_baja_evento_email_1" class="form-group row {{ (old('p_baja_evento_email')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_asunto" class="col-sm-3 col-form-label text-">Asunto Baja de Evento<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control valid" name="p_baja_evento_asunto" id="p_baja_evento_asunto" placeholder="Asunto" value="{{ old('p_baja_evento_asunto') }}">
                        </div>
                      </div>
                      
                      {{-- end ASUNTO CONFIRMACION --}}

                      <div id="p_baja_evento_1" class="form-group row {{ (old('p_baja_evento_email')==1)?'':'d-none' }}">
                        <label for="p_baja_evento" class="col-sm-12 col-form-label d-block">Baja de Evento Pantallazo (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (HTML)" class="form-control" name="p_baja_evento" id="" cols="30" rows="10">{{ old('p_baja_evento') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_debaja.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <div id="p_baja_evento_msg1" class="form-group row {{ (old('p_baja_evento_msg')==1)?'':'d-none' }}">
                        <label for="p_baja_evento_2" class="col-sm-12 col-form-label d-block">Baja de Evento (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Baja de Evento (Whatsapp)" class="form-control" name="p_baja_evento_2" id="" cols="30" rows="10">{{ old('p_baja_evento_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 7 --}}
                      <div class="form-group row">
                        <label for="p_preinscripcion_cerrado" class="col-sm-12 col-form-label d-block">Preinscripciones Cerradas (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Preinscripciones Cerradas (HTML)" class="form-control" name="p_preinscripcion_cerrado" id="" cols="30" rows="10">{{ old('p_preinscripcion_cerrado') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_pre_cerrado.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="form-group row">
                        <label for="p_preinscripcion_cerrado_2" class="col-sm-12 col-form-label d-block">Preinscripciones Cerradas (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Preinscripciones Cerradas (Whatsapp)" class="form-control" name="p_preinscripcion_cerrado_2" id="" cols="30" rows="10">{{ old('p_preinscripcion_cerrado_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div> -->

                      {{-- plantilla 8 --}}
                      <div class="form-group row">
                        <label for="p_inscripcion_cerrado" class="col-sm-12 col-form-label d-block">Evento Cerrado (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Evento Cerrado (HTML)" class="form-control" name="p_inscripcion_cerrado" id="" cols="30" rows="10">{{ old('p_inscripcion_cerrado') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            <a download="" class="pl-4" href="{{url('')}}/files/plantillas/plantilla_evento_cerrado.html" target="_blank">Descargar plantilla</a>
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="form-group row">
                        <label for="p_inscripcion_cerrado_2" class="col-sm-12 col-form-label d-block">Incripciones Cerradas (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Incripciones Cerradas (Whatsapp)" class="form-control" name="p_inscripcion_cerrado_2" id="" cols="30" rows="10">{{ old('p_inscripcion_cerrado_2') }}</textarea>
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
  console.log('Ejecutando create...');
  $('document').ready(function(){

    $('#prereg_confirm_email').click(function() {
      // Verifica si el checkbox está marcado
      if ($(this).is(':checked')) {
          $(this).val(1);
      } else {
          $(this).val(0);
       }
        });

    $('#prereg_confirm_msg').click(function() {
      // Verifica si el checkbox está marcado
      if ($(this).is(':checked')) {
          $(this).val(1);
      } else {
          $(this).val(0);
       }
    });

  });

</script>