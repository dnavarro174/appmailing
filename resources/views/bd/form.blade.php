                    <div class="row">
                      <div class="col-sm-2 form-group">
                        <label class=" col-form-label" for="cboTipDoc">Tipo Doc / Type <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" name="cboTipDoc" id="cboTipDoc">
                            <option value="">SELECCIONAR...</option>
                            @foreach($tipo_doc as $tipoDoc)
                              <option value="{{ $tipoDoc->id }}">{{ $tipoDoc->tipo_doc }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label class=" col-form-label" for="inputdni">DNI / ID <span class="text-danger">*</span></label>
                        <input class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "8" id="inputdni" name="inputdni" placeholder="DNI / ID" value="{{ old('inputdni') }}" autofocus>
                        {!! $errors->first('inputdni', '<span class=error>:message</span>') !!}
                      </div>

                      {{-- <div class="col-sm-4 form-group mt-4">
                        <div class="form-check">
                            <label class="form-check-label" for="enc">
                            <input type="checkbox" id="enc" name="check_newsletter" class="form-check-input" value="1">
                              Agregar al Newsletter</label>
                        </div>
                      </div> --}}
                      <div class="col-sm-12 col-md-4 form-group">
                        <label class=" col-form-label" for="grupo">Grupo / Group <span class="text-danger">*</span></label>
                        <select  class="form-control text-uppercase" required="" name="grupo" id="grupo" class="codigo_cel">
                          <option value="">SELECCIONE / CHANGE</option>
                          @foreach($grupos as $tipo)
                          <option value="{{$tipo->codigo}}">{{$tipo->nombre}}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputNombres">Nombres / Name <span class="text-danger">*</span></label>
                        <input type="text" required="" class="form-control text-uppercase" id="inputNombres" name="inputNombres" placeholder="Nombres / Name" value="{{ old('inputNombres') }}">
                        {!! $errors->first('inputNombres', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputApe_pat">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputApe_pat" name="inputApe_pat" required="" placeholder="Apellido Paterno / Last Name" value="{{ old('inputApe_pat') }}">
                        {!! $errors->first('inputApe_pat', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputApe_mat">Apellido Materno </label>
                        <input type="text" class="form-control text-uppercase" id="inputApe_mat" name="inputApe_mat" placeholder="Apellido Materno" value="{{ old('inputApe_mat') }}">
                        {!! $errors->first('inputApe_mat', '<span class=error>:message</span>') !!}
                      </div>
                    </div>


                    <div id="cboPais" class="row cboPais">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="pais">País / Country <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="pais" name="pais">
                          <option value="">SELECCIONE</option>
                          <option value="PERU">PERU</option>
                          @foreach($countrys as $country)
                            <option class="text-uppercase" value="{{$country->name}}">{{$country->name}}</option>
                          @endforeach
                        </select>

                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboDepartamento">Departamentos / Departments <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="cboDepartamento" name="region">
                          <option value="">SELECCIONE</option>
                          @foreach ($departamentos_datos as $ubigeo)
                          <option value="{{ $ubigeo->nombre }}">{{ $ubigeo->nombre }}</option>
                          @endforeach
                        </select>

                      </div>
                      {{-- <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboProvincia">Provincia</label>
                        <select class="form-control text-uppercase" id="cboProvincia" name="cboProvincia">
                          <option value="">SELECCIONE</option>
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboDistrito">Distrito</label>
                        <select class="form-control text-uppercase" id="cboDistrito" name="cboDistrito">
                          <option value="">SELECCIONE</option>
                        </select>
                      </div> --}}
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Profesión-Ocupación / Profession-Occupation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputProfesion" name="inputProfesion" placeholder="Profesión-Ocupación" required="" value="{{ old('inputProfesion') }}">
                        {!! $errors->first('inputProfesion', '<span class=error>:message</span>') !!}
                      </div>

                    </div>
    



                    <div class="row">
                      
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="inputOrganizacion">Entidad / Entity <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputOrganizacion" name="inputOrganizacion" required="" placeholder="Entidad / Entity" value="{{ old('inputOrganizacion') }}">
                        {!! $errors->first('inputOrganizacion', '<span class=error>:message</span>') !!}
                      </div>

                    
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Cargo / Charge <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputCargo" name="inputCargo" required="" placeholder="Cargo / Charge" value="{{ old('inputCargo') }}">
                        {!! $errors->first('inputCargo', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo electrónico personal / Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputEmail" name="inputEmail" required="" placeholder="Correo electrónico personal / Email" value="{{ old('inputEmail') }}">
                        {!! $errors->first('inputEmail', '<span class=error>:message</span>') !!}
                      </div>


                      <div class="col-sm-4 form-group">
                        <div class="form-group">
                          <label class="col-form-label" for="celular">Número Celular <span class="text-danger">*</span></label>
                          <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width: 120px;">
                              
                              <select class="form-control text-uppercase" name="codigo_cel" id="codigo_cel" required="">
                                <option value="">Seleccione</option>
                                <option value="51">PERU</option>
                                @foreach($countrys as $country)
                                <option value="{{$country->phonecode}}">{{$country->nicename}}</option>
                                @endforeach
                              </select>
                            </div>
                            <input type="text" class="form-control" id="celular" name="inputCelular" placeholder="CELULAR" value="" required="">
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Teléfono </label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" value="{{ old('telefono') }}">
                        {!! $errors->first('telefono', '<span class=error>:message</span>') !!}
                      </div>

                      {{-- <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="track">Track</label>
                        <input type="text" class="form-control text-uppercase" id="track" name="track" placeholder="Track" value="{{ old('track') }}">
                        {!! $errors->first('track', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="accedio">Registrado</label>
                        <input type="text" class="form-control text-uppercase" id="accedio" name="accedio" placeholder="Accedio" value="{{ old('accedio') }}">
                        {!! $errors->first('accedio', '<span class=error>:message</span>') !!}
                      </div> --}}
                      
                    </div>

                    {{--<div class="row">
                    
                       <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="entidad">Entidad </label>
                        <input type="text" class="form-control" id="entidad" name="entidad" placeholder="Entidad" value="{{ old('entidad') }}">
                      </div> --}}

                      {{-- <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control text-uppercase" id="cboEstado" name="cboEstado">
                          <option value="">SELECCIONE</option>
                          <option value="1"
                          {{ old('cboEstado')==1 ? "selected":""}}
                          >Activo</option>
                          <option value="2"
                          {{ old('cboEstado')==2 ? "selected":""}}
                          >Inactivo</option>

                        </select>
                      </div> 

                    </div>--}}
                    <!-- <div class="row">
                      <div class="col-sm-4 form-group">

                      </div>
                    </div> -->

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                      
                        <a href="{{ URL::previous() }}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>