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
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Actividades</h4>
                  {{-- <p class="card-description">
                    
                  </p> --}}

                  

                  @if(session()->has('info'))
                    <div class="alert alert-success" role="alert">
                      {{ session('info') }}
                    </div>
                    
                    <a href="{{ route('actividades.index') }}" class="btn btn-success">Volver al listado</a>

                  @else
                  <form class="forms-sample" id="estudiantesForm" action="{{ route('actividades.store') }}" method="post">
                    {!! csrf_field() !!}
                    @include ('actividades.form')

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