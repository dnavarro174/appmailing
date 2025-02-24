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
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Usuarios @enc</h4>
                    <span class="badge badge-dark">Módulo: {{\Illuminate\Support\Str::limit(session('evento')['nombre'],40)}}</span>
                  </div>
                                    

                  @if (session('alert'))
                      <div class="alert alert-warning">
                          {!! session('alert') !!}
                      </div>
                  @endif

                  @if(session()->has('info'))
                    <div class="alert alert-success" role="alert">
                      {{ session('info') }}
                    </div>
                    
                    <a href="{{ route('correos.index') }}" class="btn btn-success">Volver al listado</a>

                  @else
                  <form class="forms-sample" id="estudiantesForm" action="{{ route('correos.store') }}" method="post">
                    {!! csrf_field() !!}
                     
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_doc">Tipo Doc<span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" name="tipo_doc" id="cboTipDoc">
                            {{-- <option value="">SELECCIONAR</option> --}}
                            @foreach($tipo_doc as $tipoDoc)
                              <option value="{{ $tipoDoc->id }}">{{ $tipoDoc->tipo_doc }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="inputdni">DNI / ID <span class="text-danger">*</span></label>
                        <input class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "8" id="inputdni" name="inputdni" placeholder="DNI / ID" value="{{ old('inputdni') }}" autofocus>
                        {!! $errors->first('inputdni', '<span class=error>:message</span>') !!}
                      </div>


                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="nombres">Nombres <span class="text-danger">*</span></label>
                        <input type="text" required="" class="form-control text-uppercase" id="nombres" name="nombres" placeholder="Nombres / Name" value="{{ old('nombres') }}">
                        {!! $errors->first('nombres', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="ap_paterno">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="ap_paterno" name="ap_paterno" required="" placeholder="Apellido Paterno / Last Name" value="{{ old('ap_paterno') }}">
                        {!! $errors->first('ap_paterno', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="ap_materno">Apellido Materno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="ap_materno" name="ap_materno" placeholder="Apellido Materno" required="" value="{{ old('ap_materno') }}">
                        {!! $errors->first('ap_materno', '<span class=error>:message</span>') !!}
                      </div>
                    </div>


                    <div id="cboPais" class="row cboPais">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="pais">País <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="pais" name="pais">
                          <option value="">SELECCIONE</option>
                          <option value="PERU">PERU</option>
                          @foreach($countrys as $country)
                            <option class="text-uppercase" value="{{$country->name}}">{{$country->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboDepartamento">Departamentos </label>
                        <select class="form-control text-uppercase" id="dpto" name="region">
                          <option value="">SELECCIONE</option>
                          @foreach ($departamentos_datos as $ubigeo)
                          <option value="{{ $ubigeo->nombre }}">{{ $ubigeo->nombre }}</option>
                          @endforeach
                        </select>

                      </div>
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="area">Área <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="area" name="area">
                          <option value="">SELECCIONE</option>
                          @foreach($areasenc as $a)
                            <option class="text-uppercase" value="{{$a->id}}">{{$a->nombre}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="emailenc">Correo @enc</label>
                        <input type="text" disabled="" class="form-control text-uppercase" name="emailenc" placeholder="usuario@enc.edu.pe" value="{{ old('emailenc') }}">
                        {!! $errors->first('emailenc', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo de recuperación <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email_recu" name="email_recu" required="" placeholder="Correo electrónico personal / Email" value="{{ old('email_recu') }}">
                        {!! $errors->first('email_recu', '<span class=error>:message</span>') !!}
                      </div>
                      {{-- <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="accedio">Correo Generado</label>
                        <select class="form-control text-uppercase" id="accedio" name="accedio">
                          <option value="">SELECCIONE</option>
                          <option value="SI"
                          {{ old('accedio')=="SI" ? "selected":""}}
                          >Activo</option>
                          <option value="NO"
                          {{ old('accedio')=="NO" ? "selected":""}}
                          >Inactivo</option>
                        </select>
                      </div> --}}
                    </div>
                  

                   
                    <!-- <div class="row">
                      <div class="col-sm-4 form-group">

                      </div>
                    </div> -->

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit2" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                      
                        <a href="{{ route('correos.index') }}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>
                    
                    <input type="hidden" name="existe" id="existe" value="">
                    <input type="hidden" name="eventos_id" id="eventos_id" value="76">

                  </form>
                  @endif
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