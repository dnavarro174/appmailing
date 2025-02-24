
                    <div class="row">
                      <div class="col-xs-12 col-sm-5">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                          <input type="text" class="form-control text-uppercase" autofocus id="nombre" name="nombre" required="" value="{{ old('nombre') }}">
                          {!! $errors->first('nombre', '<span class=error>:message</span>') !!}
                        </div>
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="asunto">Asunto <span class="text-danger">*</span></label>
                          <input type="text" class="form-control " id="asunto" name="asunto" required="" value="{{ old('asunto') }}">
                          {!! $errors->first('asunto', '<span class=error>:message</span>') !!}
                        </div>
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="flujo_ejecucion">Flujo Ejecución <span class="text-danger">*</span></label>
                          <select class="form-control text-uppercase" name="flujo_ejecucion" id="flujo_ejecucion" required="">
                              <option value="">SELECCIONAR</option>
                              <option value="LEY-27419">LEY 27419</option>
                              <option selected value="MAILING">MAILING</option>
                          </select>
                          {!! $errors->first('flujo_ejecucion', '<span class=error>:message</span>') !!}
                              {{-- <option value="INVITACION">INVITACION CAII</option>
                              <option value="CONFIRMACION">CONFIRMACION CAII</option>
                              <option value="RECORDATORIO">RECORDATORIO CAII</option>
                              <option value="NOINVITADO">NO INVITADO CAII</option> --}}
                              {{-- <option value="NEWSLETTER">NEWSLETTER</option> --}}

                        </div>

                      </div> {{-- end column 1 --}}

                      <div class="col-xs-12 col-sm-7">
                        <div class="col-sm-12 form-group">
                          <label class=" col-form-label" for="plantillahtml">HTML</label>
                          <textarea type="text" class="form-control " id="plantillahtml" name="plantillahtml" rows="19">{{ old('plantillahtml') }}</textarea>
                          {!! $errors->first('plantillahtml', '<span class=error>:message</span>') !!}
                        </div>

                          <div class="col-sm-12 form-group d-none auto_conf_div">
                            <label class=" col-form-label" for="plantilla_si">HTML Acepta confirmación</label>
                            <textarea type="text" class="form-control " id="plantilla_si" name="plantilla_si" rows="6">{{ old('plantilla_si') }}</textarea>
                            {!! $errors->first('plantilla_si', '<span class=error>:message</span>') !!}
                          </div>

                          <div class="col-sm-12 form-group d-none auto_conf_div">
                            <label class=" col-form-label" for="plantilla_no">HTML NO Acepta confirmación</label>
                            <textarea type="text" class="form-control " id="plantilla_no" name="plantilla_no" rows="6">{{ old('plantilla_no') }}</textarea>
                            {!! $errors->first('plantilla_no', '<span class=error>:message</span>') !!}
                          </div>
                         {{-- end confirmacion --}} 


                      </div> {{-- end column 2 --}}

                      <div class="col-xs-12 col-sm-5"></div>
                      <div class="col-xs-12 col-sm-7">
                        <div class="col-sm-12">
                          <textarea id="summernote" name="cancelar">
                            <div style="width: 650px;margin:0 auto;background: #e6eaed;font-family: arial;">
                              <div style="width: 100%;margin:0 auto;padding: 20px 38px;box-sizing: border-box;border-top: 1px solid white;margin-top: 3px;">
                                <span id="nom" style="color: #131c21;"><?php echo '{{$nombres}}'?></span>
                                <p style="color: #7d8182;margin:4px 0;">Hacer clic en CANCELAR SUSCRIPCIÓN para dejar de recibir mensajes de la Escuela Nacional de Control</p><!-- $no = "/au/enc/11111111/0/35";-->
                                <p><a href="<?php echo '{{$dni_url}}'?>" target="_blank" style="color: #005ff9;font-weight: 500; text-align: center;text-decoration: none;" class="btn-link">CANCELAR SUSCRIPCIÓN</a></p>
                                <p style="text-align:center;"><strong>Importante:</strong> Este correo no admite respuesta por esta vía.</p>
                              </div>
                            </div>
                          </textarea>
                        </div>
                      </div>


                    </div> {{-- end row --}}

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('campanias.create')}}" class="btn btn-light">Volver a Mailing</a>
                        <a href="{{ route('plantillaemail.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>

