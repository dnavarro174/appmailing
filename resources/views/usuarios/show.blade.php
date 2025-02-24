@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
     
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Usuarios</h4>
                  <p class="card-description"></p>

                  <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="usuario">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary text-uppercase" id="name" name="name" placeholder="Nombre" required="" disabled="" value="{{ $usuarios_datos->name }}" >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control border-primary text-uppercase" id="email" name="email" required="" disabled="" placeholder="Email" value="{{ $usuarios_datos->email  }}" >
                        {!! $errors->first('email', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="password">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control border-primary text-uppercase" id="password" name="password" required="" disabled="" placeholder="Contraseña" value="{{ $usuarios_datos->password }}" >
                        {!! $errors->first('password', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_id">Rol</label>
                        <select class="form-control border-primary text-uppercase" id="tipo_id" name="tipo_id">
                          <option value="">SELECCIONE</option>
                          {{-- @foreach ($tc_tipos_datos as $datos)
                          <option value="{{ $datos->tipo_id }}"
                            {{ old('tipo_id')== $datos->tipo_id ? "selected":""}}
                            >{{ $datos->tipo }}</option>
                          @endforeach --}}
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control border-primary text-uppercase" id="cboEstado" name="cboEstado" disabled="">
                          <option value="0">SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $usuarios_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $usuarios_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2" disabled="">Guardar</button>
                        <a href="{{ route('usuarios.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>

                  

                    
                
                  
                  
                   
                    
                  

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