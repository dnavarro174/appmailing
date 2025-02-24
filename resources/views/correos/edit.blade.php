@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper pt-4 mt-3">
          <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Usuarios @enc</h4>
                    <span class="badge badge-dark">Módulo: {{\Illuminate\Support\Str::limit(session('evento')['nombre'],40)}}</span>
                  </div>
                  
                  <p class="card-description">
                    <input type="hidden" id="correos_id" value="{{session('correos_id')}}">
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('correos.update', ['id'=>$estudiantes_datos->id, 'idcorreo'=>$estudiantes_datos->idcorreo]) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_doc">Tipo Doc <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" name="tipo_doc" id="cboTipDoc">
                            <option value="">SELECCIONAR...</option>
                            @foreach($tipo_doc as $tipoDoc)
                              <option value="{{ $tipoDoc->id }}" @if ($tipoDoc->id == $estudiantes_datos->tipo_documento_documento_id)
                                  selected
                                @endif
                                >{{ $tipoDoc->tipo_doc }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="inputdni">DNI / ID <span class="text-danger">*</span></label>
                        <input readonly="" class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                          type="@if ($estudiantes_datos->tipo_documento_documento_id == 1) number @else text @endif"
                          @if ($estudiantes_datos->tipo_documento_documento_id == 1) maxlength='8' @else maxlength='15' @endif id="dni_doc" name="inputdni" placeholder="DNI / ID" value="{{ $estudiantes_datos->dni_doc }}" autofocus>
                        {!! $errors->first('inputdni', '<span class=error>:message</span>') !!}
                      </div>

                      
                    </div>


                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="nombres">Nombres / Name <span class="text-danger">*</span></label>
                        <input type="text" required="" class="form-control text-uppercase" id="nombres" name="nombres" placeholder="Nombres / Name" value="{{ $estudiantes_datos->nombres }}">
                        {!! $errors->first('nombres', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="ap_paterno">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno" required="" placeholder="Apellido Paterno / Last Name" value="{{ $estudiantes_datos->ap_paterno }}">
                        {!! $errors->first('ap_paterno', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="ap_materno">Apellido Materno </label>
                        <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" placeholder="Apellido Materno" value="{{ $estudiantes_datos->ap_materno }}">
                        {!! $errors->first('ap_materno', '<span class=error>:message</span>') !!}
                      </div>
                    </div>


                    <div id="cboPais" class="row cboPais">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="pais">País / Country <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="pais" name="pais">
                          <option value="">SELECCIONE</option>
                          <option value="PERU">PERU</option>
                          @foreach($countrys as $country)
                            <option class="text-uppercase" @if ($country->name === $estudiantes_datos->pais) selected @endif value="{{$country->name}}" data-id='{{$country->phonecode}}'>{{$country->name}}</option>
                          @endforeach
                        </select>

                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboDepartamento">Departamentos / Departments @if ($estudiantes_datos->pais == "PERU") @endif</label>
                        <select class="form-control text-uppercase" id="cboDepartamento" name="region">
                          <option value="">SELECCIONE</option>
                            @foreach ($departamentos_datos as $ubigeo)
                            <option value="{{ $ubigeo->nombre }}" 
                              @if ($ubigeo->nombre === $estudiantes_datos->region)
                                    selected
                                  @endif>{{ $ubigeo->nombre }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="area">Área <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="area" name="area">
                          <option value="">SELECCIONE</option>
                          @foreach($areasenc as $a)
                            <option value="{{$a->id}}" @if ($a->id === $estudiantes_datos->area_id) 
                                selected 
                              @endif>{{$a->nombre}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
  

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo @enc </label>
                        <input type="text" class="form-control" id="emailenc" name="emailenc" required="" value="{{ $estudiantes_datos->emailenc }}">
                        {!! $errors->first('emailenc', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Contraseña </label>
                        <input type="text" class="form-control" id="password" name="password" required="" value="{{ $estudiantes_datos->password }}">
                        {!! $errors->first('password', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo de recuperación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="email_recu" name="email_recu" required="" placeholder="Correo electrónico personal / Email" value="{{ $estudiantes_datos->email }}">
                        {!! $errors->first('email_recu', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="estado">Estado</label>
                        <select class="form-control text-uppercase" name="estado" id="estado" required="">
                          <option value="">SELECCIONE</option>
                          <option value="1" @if(1== $estudiantes_datos->estado) selected="" @endif>ACTIVO</option>
                          <option value="0" @if(0== $estudiantes_datos->estado) selected="" @endif>INACTIVO</option>
                        </select>
                      </div>

                      
                    </div>
                    
                    <div class="form-group row masinfo">
                      <div class="col-sm-12 text-center mt-4">
                        {{-- @if($evento_vencido == 1) disabled title="Evento Finalizado" @endif --}}
                        {{-- <div class="btn-group" role="group">
                          <button  id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <i class="mdi mdi-settings"></i> Opciones
                          </button>
                          <div class="dropdown-menu bg-light" aria-labelledby="btnGroupDrop1">
                            
                            <a class="dropdown-item solicitud" data-tipo='confirmacion' data-dni='{{$estudiantes_datos->dni_doc}}' data-evento='{{$eventos_id}}' href="#">Reenviar Confirmación</a>
                            <a class="dropdown-item solicitud" data-tipo='recordatorio' data-dni='{{$estudiantes_datos->dni_doc}}' data-evento='{{$eventos_id}}' href="#">Reenviar Recordatorio</a>
                          </div>
                        </div> --}}

                        <button id="actionSubmit" value="Actualizar" type="submit" class="btn btn-dark mr-2">Actualizar</button>

                        <a href="{{ route('correos.index') }}" class="btn btn-light">Volver al listado</a>

                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>

          </div>
          
        </div>
        

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layout.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

@endsection