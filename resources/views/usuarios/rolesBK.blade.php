@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
      <!-- end menu_user -->
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      
      @include('layout.menu_iz')
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Roles de Usuario</h4>
                  
                  

                  <form method="post" class="forms-sample" id="userrolesForm"  action="{{ route('usuarios.storeRoles') }}">
                    {!! method_field('POST') !!}
                    {!! csrf_field() !!}


                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="usuario">Usuario </label>
                        <input type="text" class="form-control border-primary text-uppercase" id="name" name="name" placeholder="Nombre" value="{{ $usuarios_datos->name or old('name') }}" disabled >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="tipo_id">Rol</label>
                        <select class="form-control border-primary text-uppercase" name="cboRol[]" id="cboRol" size=10 multiple="" class="custom-scroll" style='height: 100%;'>
                          @foreach ($roles as $rol)
                          <option value="1">{{$rol["rol"]}}</option>
                          @endforeach 
                        </select>
                      </div>
                      
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
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