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
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Historia Email</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('historiaemail.update', $datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    

                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="tipo" class=" col-form-label" >Tipo</label>
                        <input type="text" disabled="" class="form-control" name="tipo" value="{{ $datos->tipo }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label for="email" class=" col-form-label" >Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $datos->email }}">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 form-group">
                        <label for="celular" class=" col-form-label">Celular</label>
                        <input type="text" class="form-control" name="celular" value="{{ $datos->celular }}">
                      </div>
                      <div class="col-sm-6 form-group">
                        <label for="fecha_envio" class=" col-form-label">Fecha Envio </label>
                        <input type="text" class="form-control" name="fecha_envio" value="{{ \Carbon\Carbon::parse($datos->fecha_envio) }}">
                        <label for="fecha_envi" class=" col-form-label">Si envió fallo, copiar y pegar en el campo => <strong class="font-weight-bold">2000-01-01 00:00:00</strong></label>
                        <label for="fecha_envi" class=" col-form-label p-0">para poder reenviar el mailing</label>
                      </div>
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('historiaemail.index')}}" class="btn btn-light">Volver al listado</a>
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