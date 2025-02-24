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
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Leads / Registros</h4>
                    <span class="badge badge-danger"><strong>Evento:</strong> {{\Illuminate\Support\Str::limit(session('evento')['nombre'],40)}}</span>
                  </div>
                  
                  <p class="card-description">
                    <input type="hidden" id="eventos_id" value="{{session('eventos_id')}}">
                  </p>
                                    

                  @if (session('alert'))
                      <div class="alert alert-warning">
                          {!! session('alert') !!}
                      </div>
                  @endif

                  @if(session()->has('info'))
                    <div class="alert alert-success" role="alert">
                      {{ session('info') }}
                    </div>
                    
                    <a href="{{ route('estudiantes.index') }}" class="btn btn-dark">Volver al listado</a>

                  @else
                  <form class="forms-sample" id="estudiantesForm" action="{{ route('estudiantes.store') }}" method="post">
                    {!! csrf_field() !!}
                    @include ('estudiantes.form')

                    <input type="hidden" name="existe" id="existe">

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