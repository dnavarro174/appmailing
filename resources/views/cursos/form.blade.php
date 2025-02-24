<div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="nom_curso">Curso <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary text-uppercase" id="nom_curso" name="nom_curso" placeholder="Curso" value="{{ old('nom_curso') }}" >
                        {!! $errors->first('nom_curso', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="descripcion">Descripción</label>
                        <textarea class="form-control border-primary" name="descripcion" id="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cat_curso_id">Categoría</label>
                        <select class="form-control border-primary text-uppercase" id="cat_curso_id" name="cat_curso_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($cat_cursos_datos as $datos)
                          <option value="{{ $datos->id }}"
                            {{ (old("cat_curso_id") == $datos->id ? "selected":"") }}
                            >{{ $datos->categoria }}</option>

                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_id">Tipo de Curso</label>
                        <select class="form-control border-primary text-uppercase" id="tipo_id" name="tipo_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_tipos_datos as $datos)
                          <option value="{{ $datos->tipo_id }}"
                            {{ old('tipo_id')== $datos->tipo_id ? "selected":""}}
                            >{{ $datos->tipo }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="modalidad_id">Modalidades</label>
                        <select class="form-control border-primary text-uppercase" id="modalidad_id" name="modalidad_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_modalidades_datos as $datos)
                          <option value="{{ $datos->modalidad_id }}"
                            {{ old('modalidad_id')==$datos->modalidad_id ? "selected":"" }}
                            >{{ $datos->modalidad }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="sede_id">Sedes</label>
                        <select class="form-control border-primary text-uppercase" id="sede_id" name="sede_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_sedes_datos as $datos)
                          <option value="{{ $datos->sede_id }}"
                            {{ old('sede_id')==$datos->sede_id ? "selected":""}}
                            >{{ $datos->sede }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Sesiones</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="sesiones" name="sesiones" placeholder="Sesiones" value="{{ old('sesiones') }}">
                        {!! $errors->first('sesiones', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Horas Académicas</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="horas_aca" name="horas_aca" placeholder="Horas Académicas" value="{{ old('horas_aca') }}">
                        {!! $errors->first('horas_aca', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado">
                          <option value="0">SELECCIONE</option>
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
                        <a href="{{ route('cursos.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>