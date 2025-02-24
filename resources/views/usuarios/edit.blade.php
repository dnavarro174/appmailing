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
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Usuarios</h4>
                  
                  <form class="forms-sample" id="cursosForm"  action="{{ route('usuarios.update', $usuarios_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control " id="name" name="name" placeholder="Nombre" required="" value="{{ $usuarios_datos->name, old('name') }}" >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="email">Email</label>
                        <input type="email" class="form-control " id="email" name="email" required="" placeholder="Email" value="{{ $usuarios_datos->email, old('email') }}" disabled="">
                        {!! $errors->first('email', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="password">Contraseña - <span class="text-danger">Rellenar sólo si desea cambiar de contraseña</span></label>
                        <input type="text" class="form-control " id="password" name="password" placeholder="Contraseña" value="" >
                        {{-- {!! $errors->first('password', '<span class=error>:message</span>') !!}
                        {{ $usuarios_datos->password, old('password') }} --}}
                      </div>
                    </div>
                    <div class="row">
                      {{-- <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="tipo_id">Rol</label>
                        <select class="form-control " id="tipo_id" name="tipo_id">
                          <option value="">SELECCIONE</option>
                          @foreach ($tc_tipos_datos as $datos)
                          <option value="{{ $datos->tipo_id }}"
                            {{ old('tipo_id')== $datos->tipo_id ? "selected":""}}
                            >{{ $datos->tipo }}</option>
                          @endforeach
                        </select>
                      </div> --}}
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control " id="cboEstado" name="cboEstado">
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
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('usuarios.index')}}" class="btn btn-light">Volver al listado</a>
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