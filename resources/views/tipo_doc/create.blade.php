@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Tipo de Documento</h4>
                  
                  @if(session()->has('info'))
                    <div class="alert alert-success" role="alert">
                      {{-- <h4>{{ session('info') }}</h4> --}}
                      {{ session('info') }}
                    </div>
                    
                    <a href="{{ route('tipo_doc.index') }}" class="btn btn-success">Volver al listado</a>

                  @else
                  <form class="forms-sample" id="tipo_docForm"  action="{{ route('tipo_doc.store') }}" method="post">
                    {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                    {!! csrf_field() !!}
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label">Tipo Documento</label>
                        <input type="text" class="form-control border-primary" id="inputTipoDoc" name="inputTipoDoc" placeholder="Tipo de documento" value="{{ old('inputTipoDoc') }}" autofocus="">
                        {!! $errors->first('inputTipoDoc', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('tipo_doc.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>

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