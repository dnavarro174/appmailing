                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Rol <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="rol" name="rol" placeholder="Rol" value="{{ old('name') }}" >
                        {!! $errors->first('rol', '<span class=error>:message</span>') !!}
                      </div>
                    </div>


                    
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Descripción </label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" value="{{ old('descripcion') }}" >
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div> 
                      
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('roles.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>