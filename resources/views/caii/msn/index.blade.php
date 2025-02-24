@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Mensajes MSN</h4>

                  @if (session('alert'))
                      <div class="alert alert-success">
                          {{ session('alert') }}
                      </div>
                  @endif

                
                  {{-- <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('caiieventos.store') }}" method="post">
                    {!! csrf_field() !!} --}}
                    
                      <div class="form-group row">
                        <label for="nombre_evento" class="col-sm-2 col-form-label d-block">Plan </label>
                        <div class="col-sm-10">
                          <input disabled="" type="text" required="" class="form-control" name="nombre_evento" placeholder="Nombre del Evento *" value="{{ $datos->nom_plan }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cant" class="col-sm-2 col-form-label d-block">Cantidad</label>
                        <div class="col-sm-10">
                          <input disabled="" type="text" required="" class="form-control" name="cant" placeholder="" value="{{ $datos->cant }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cant" class="col-sm-2 col-form-label d-block">Utilizados</label>
                        <div class="col-sm-10">
                          <input disabled="" type="text" required="" class="form-control" name="cant" placeholder="" value="{{ $datos->mensajes }}" />
                        </div>
                      </div>

                      <div class="form-group text-center mt-4">
                        
                        <a href="{{ url('/')}}" class="btn btn-light">Volver al home</a>
                      </div>


                  {{-- </form> --}}
                  
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