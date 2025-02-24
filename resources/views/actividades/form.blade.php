<div class="row">
                      <div class="col-sm-3 form-group">
                        <label class=" col-form-label" for="programaciones_id">Código Programación <span class="text-danger">*</span></label>
                        <select class="form-control border-primary text-uppercase required" autofocus name="programaciones_id" id="programaciones_id">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cod_prog as $prog)
                              <option value="{{ $prog->codigo }}" {{ old('programaciones_id') == $prog->codigo ? 'selected' : '' }}>{{ $prog->codigo .' - '. $prog->nombre }}</option>
                            @endforeach
                            
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="ponente_id">Ponente <span class="text-danger">*</span></label>
                        <select class="form-control border-primary text-uppercase required" autofocus name="ponente_id" id="ponente_id">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cod_pon as $ponente)
                              <option value="{{ $ponente->id }}" {{ old('ponente_id') == $ponente->id ? 'selected' : '' }}>{{ $ponente->id .' - '. $ponente->nombre }}</option>
                            @endforeach
                        </select>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="nomactividad">Nombre</label>
                        <input type="text" class="form-control border-primary text-uppercase required" id="nomactividad" name="nomactividad" value="{{ old('nomactividad') }}" >
                        {!! $errors->first('nomactividad', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="descripcion">Descripción</label>
                        <textarea type="text" class="form-control border-primary text-uppercase" id="descripcion" name="descripcion" rows="4" >{{ old('descripcion') }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_desde">Fecha Desde</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_desde" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ old('fecha_desde') }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_desde', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha_hasta">Fecha Hasta</label>
                        <div id="datepicker-popup2" class="input-group date datepicker border-primary">
                          <input type="text" name="fecha_hasta" class="form-control border-primary text-uppercase" placeholder="01/01/2018" value="{{ old('fecha_hasta') }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('fecha_hasta', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="aforo">Aforo</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="aforo" name="aforo" placeholder="Aforo" value="{{ old('aforo') }}">
                        {!! $errors->first('aforo', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Hora Inicio</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="hora_inicio" class="form-control border-primary text-uppercase" placeholder="00:00" value="{{ old('hora_inicio') }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('hora_inicio', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Hora Fin</label>
                        <div id="datepicker-popup" class="input-group date datepicker border-primary">
                          <input type="text" name="hora_final" class="form-control border-primary text-uppercase" placeholder="00:00" value="{{ old('hora_final') }}">
                          <span class="input-group-addon input-group-append border-left">
                            <span class="mdi mdi-calendar input-group-text"></span>
                          </span>
                        </div>
                        {!! $errors->first('hora_final', '<span class=error>:message</span>') !!}
                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Ubicación</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="ubicacion" name="ubicacion" placeholder="Ubicación" value="{{ old('ubicacion') }}">
                        {!! $errors->first('ubicacion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Inscritos</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="inscritos" name="inscritos" placeholder="Inscritos" value="{{ old('inscritos') }}">
                        {!! $errors->first('inscritos', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado">
                          <option value="">SELECCIONE</option>
                          <option value="1"
                          {{ old('cboEstado')==1 ? "selected":""}}
                          >Activo</option>
                          <option value="2"
                          {{ old('cboEstado')==2 ? "selected":""}}
                          >Inactivo</option>
                        </select>
                      </div>
                    </div>
                    

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>

                        @if(Request::has('act'))
                          <a href="{{ route('programaciones.index')}}" class="btn btn-light">Volver al listado</a>
                        @else
                          <a href="{{ route('actividades.index')}}" class="btn btn-light">Volver al listado</a>
                        @endif

                      </div>
                    </div>