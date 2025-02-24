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
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label">Tipo Documento</label>
                        <input disabled type="text" class="form-control border-primary" id="inputTipoDoc" name="inputTipoDoc" placeholder="Nombre de documento" value="{{ $tipo_doc_datos->tipo_doc }}" autofocus>
                        {!! $errors->first('inputTipoDoc', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button disabled id="actionSubmit" value="Guardar" type="submit" class="btn btn-success mr-2">Guardar</button>
                        <a href="{{ route('tipo_doc.index')}}" class="btn btn-light">Volver al listado</a>
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