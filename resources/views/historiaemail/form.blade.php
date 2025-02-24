
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="descripcion">Descripción</label>
                        <textarea type="text" class="form-control border-primary text-uppercase" id="descripcion" name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_inicio">Fecha Inicio</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_inicio" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ old('fecha_inicio') }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_inicio', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_fin">Fecha Fin</label>
                        <div id="datepicker-popup2" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_fin" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ old('fecha_fin') }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_fin', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_evento_id">Tipo de Evento {{-- <span class="text-danger">*</span> --}}</label>
                        <select class="form-control border-primary text-uppercase" autofocus name="tipo_evento_id" id="tipo_evento_id">
                            <option value="0">SELECCIONAR...</option>
                            @foreach($tipo_evento as $tipo)
                              <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado">
                          <option value="0">SELECCIONE</option>
                          <option value="1">Activo</option>
                          <option value="2">Inactivo</option>
                        </select>
                      </div>
                    </div>
                    

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('eventos.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>