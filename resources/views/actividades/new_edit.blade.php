
                      {{-- <form class="forms-sample pr-4 pl-4" id="actividadForm" action="{{ route('actividades.store) }}" method="post" enctype="multipart/form-data" >
                      {!! csrf_field() !!} --}}
                      <input type="hidden" name="actividad_id" name="actividad_id" value="{{$actividad_id}}">
                      <input type="hidden" name="evento_id" name="evento_id" value="{{$evento_id}}">
                      <input type="hidden" name="num" name="num" value="{{$num}}">
                      
                      <div class="form-group row">
                        <label for="titulo" class="col-sm-2 col-form-label d-block">Título<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                      <input type="text" required="" class="form-control" name="titulo" placeholder="Título de la actividad *" value="{{$actividad['titulo'], old('titulo')}}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="subtitulo" class="col-sm-2 col-form-label d-block">Subtítulo</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="subtitulo" placeholder="Subtítulo" value="{{$actividad['subtitulo'] , old('subtitulo') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="desc_actividad" class="col-sm-2 col-form-label d-block">Descripción Actividad</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción Actividad" class="form-control" name="desc_actividad" id="" cols="30" rows="5">{{ $actividad['desc_actividad'] , old('desc_actividad') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="desc_ponentes" class="col-sm-2 col-form-label d-block">Descripción Ponentes</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción Ponentes" class="form-control" name="desc_ponentes" id="" cols="30" rows="5">{{ $actividad['desc_ponentes'] , old('desc_ponentes') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="subtitulo" class="col-sm-2 col-form-label d-block">Fecha</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="fecha" readonly value="{{ $fecha }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="hora_inicio" class="col-sm-2 col-form-label">Hora Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <!--
                          <input type="time" required="" class="form-control" name="hora_inicio" placeholder="Hora" value="{{ old('hora_inicio') }}" /> -->

                          <input type="text" required="" class="form-control timepicker1" name="hora_inicio" placeholder="Hora" value="{{  $actividad['hora_inicio'] , old('hora_inicio') }}"  style="text-align: left !important;" data-autoclose="true" autocomplete="off"/>
 
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="hora_final" class="col-sm-2 col-form-label">Hora Final <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <!--
                          <input type="time" required="" class="form-control" name="hora_final" placeholder="Hora" value="{{ old('hora_final') }}" />-->
                          <input type="text" required="" class="form-control timepicker2" name="hora_final" placeholder="Hora" value="{{ $actividad['hora_final'] , old('hora_final') }}" data-autoclose="true" autocomplete="off"  style="text-align: left !important;" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="ubicacion" class="col-sm-2 col-form-label">Ubicación</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="ubicacion" placeholder="Ubición" value="{{ $actividad['ubicacion'] , old('ubicacion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label">Vacantes Presencial <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="vacantes" placeholder="Cantidad de vacantes" required value="{{ $actividad['vacantes'] , old('vacantes') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="vacantes_v" class="col-sm-2 col-form-label">Vacantes Virtual <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="vacantes_v" placeholder="Cantidad de vacantes" required value="{{ $actividad['vacantes_v'] , old('vacantes_v') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="enlace" class="col-sm-2 col-form-label">Enlace</label>
                        <div class="col-sm-10">
                          <input type="ur" placeholder="https://example.com"
                          pattern="https://.*"  class="form-control" name="enlace" placeholder="Enlace de sala virtual" value="{{ $actividad['enlace'] , old('enlace') }}" />
                        </div>
                      </div>
                      {{-- <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label">Inscritos</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="inscritos" placeholder="Cantidad de inscritos" value="{{ $actividad['inscritos'] , old('inscritos') }}" />
                        </div>
                      </div> --}}
                      <div class="form-group row">
                        <label for="imagen" class="col-sm-2 col-form-label">Imagen</label>
                        <div class="col-sm-10">
                          <div class="form-group">
                            <input type="file" name="file_img" class="file-upload-default" accept="image/x-png,image/jpeg">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" accept="image/x-png,image/jpeg"  placeholder="Solo formatos: jpg o png">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                              </span>
                            </div>
                          </div>
                        
                        </div>
                      </div>
                      <?php
                      
                      if($imagen!=""){
                      ?>
                      <div>
                        <a href="{{$imagen}}" target="_blank">
                          <img src="{{ url('') }}/{{$imagen}}" style="max-width: 100%">
                        </a>
                        

                      </div>
                      <?php
                        }
                      ?>

                   {{--  <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y Continuar Paso 2</button>
                        
                        <a href="{{ route('caii.index') }}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>--}}
                  {{-- </form> --}}
                        
                        
                      <div id="cargador_excel" class="content-wrapper p-0 d-none" align="center">  {{-- msg cargando --}}
                        <div class="card bg-white" style="background:#f3f3f3 !important;" >
                          <div class="">
                            <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                            <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                          </div>
                        </div>
                      </div>{{-- msg cargando --}}


