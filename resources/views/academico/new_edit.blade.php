                      <input type="hidden" name="actividad_id" name="actividad_id" value="{{$actividad_id}}">
                      <input type="hidden" name="evento_id" name="evento_id" value="{{$evento_id}}">
                      <input type="hidden" name="num" name="num" value="{{$num}}">
                      
                      <div class="form-group row">
                        <label for="dni_doc" class="col-sm-2 col-form-label d-block">DNI <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                      <input type="text" required="" class="form-control" name="dni_doc" placeholder="Título de la actividad *" value="{{$actividad['dni_doc'] or old('dni_doc') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="nombre_doc" class="col-sm-2 col-form-label d-block">Docente <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_doc" placeholder="Nombre del Docente" value="{{$actividad['nombre_doc'] or old('nombre_doc') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="ap_paterno" class="col-sm-2 col-form-label d-block">Ap. Paterno <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input required="" type="text" class="form-control" name="ap_paterno" placeholder="Ap. Paterno" value="{{$actividad['ap_paterno'] or old('ap_paterno') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="ap_materno" class="col-sm-2 col-form-label d-block">Ap. Paterno <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input required="" type="text" class="form-control" name="ap_materno" placeholder="Ap. Paterno" value="{{$actividad['ap_materno'] or old('ap_materno') }}" />
                        </div>
                      </div>