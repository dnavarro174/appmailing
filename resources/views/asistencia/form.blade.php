
                    <div class="row">
                      <div class="col-sm-8 form-group">
                        <label class=" col-form-label" for="asistencia">Asistencia</label>
                        <input type="text" class="form-control border-primary text-uppercase" id="asistencia" name="asistencia" value="{{ old('asistencia') }}">
                        {!! $errors->first('asistencia', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="fecha">Fecha</label>
                        <p>{{  date('d/m/Y') }}</p>
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="hora">Hora</label>
                        <p>{{  date('H:i:s') }}</p>
                      </div>
                    </div>

                  

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('asistencia.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>