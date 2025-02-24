@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper ">
          <div class="row justify-content-center">
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Ajustes Generales</h4>
                
                  <form class="forms-sample pr-4 pl-4" action="{{ route('ajustes.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="nombre_periodo" class="col-sm-4 col-form-label d-block">Nombre de Correo Electrónico <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_periodo" placeholder="Nombre del Periodo *" value="{{ old('nombre_periodo') }}" />
                          
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="nombre_periodo" class="col-sm-4 col-form-label d-block">Correo electrónico <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_periodo" placeholder="Nombre del Periodo *" value="{{ old('nombre_periodo') }}" />
                          
                        </div>
                      </div>
                      <div class="form-group row" id="auto">
                        <label for="auto_conf" class="col-sm-8 col-form-label">Desea incluir imágenes de cabecera y pie de página en el formulario</label>
                        <div class="col-sm-4">
                          <div class="form-group row">
                            <div class="col-sm-12">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_email" name="imagen" type="checkbox" class="form-check-input" value="1" > SI <i class="input-helper"></i></label>
                                  {{-- @if($datos->imagen == 1) checked="" @endif --}}
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div> {{-- end confirmacion --}}
                      
                      

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y continuar paso 2</button>
                      </div>
                    </div>

                  </form>
                  
                </div>
              </div>
            </div>
          </div>

          
          @include('email.view_html.view_html')
          
          
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
@section('scripts')
<style>
.hidden_email, .hidden_whatsapp{display: none;}
</style>
<script>
$('document').ready(function(){
  // seleccionar todos
    $('#confirm_email').change(function() {
      if ($('#confirm_email').is(':checked')) {
        $('.hidden_email').css('display','block');
      }else{
        $('.hidden_email').css('display','none');
      }

    });

    $('#confirm_msg').change(function() {

      if ($('#confirm_msg').is(':checked')) {
        $('.hidden_whatsapp').css('display','block');
      }else{
        $('.hidden_whatsapp').css('display','none');
      }

    });

});
//confirm_msg
//hidden_email
//hidden_whatsapp
</script>
@endsection