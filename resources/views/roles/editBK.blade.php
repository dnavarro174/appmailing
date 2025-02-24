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
                  
                  <h4 class="card-title">Editar Rol</h4>
                  
                  <form class="forms-sample" id="cursosForm"  action="{{ route('roles.update', $rol_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="usuario">Nombre de Rol <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary text-uppercase" id="rol" name="rol" placeholder="Rol" value="{{ $rol_datos->rol or old('rol') }}" >
                        {!! $errors->first('rol', '<span class=error>:message</span>') !!}
                      </div>                      
                    </div>
                    <div class="row">
                      <div class="col-sm-8 form-group">
                        <label class=" col-form-label" for="usuario">Descripción <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary text-uppercase" id="descripcion" name="descripcion" placeholder="Descripción" value="{{ $rol_datos->descripcion or old('descripcion') }}" >
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div> 
                      
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('roles.index')}}" class="btn btn-light">Volver al listado</a>
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