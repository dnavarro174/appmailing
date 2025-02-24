<div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="user_name" name="name" required="" placeholder="Nombre" value="{{ old('name') }}" >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="user_email" name="email" placeholder="Email" required="" value="{{ old('email') }}" >
                        {!! $errors->first('email', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="user_password">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="user_password" name="user_password" required="" placeholder="Contraseña" value="{{ old('user_password') }}" >
                        {!! $errors->first('user_password', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('usuarios.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>