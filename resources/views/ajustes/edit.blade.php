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
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title text-transform-none">Ajustes Generales</h4>
                
                  <form class="forms-sample pr-4 pl-4" action="{{ route('ajustes.update',$datos->id) }}" method="post" enctype="multipart/form-data">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="email_nom" class="col-sm-4 col-form-label d-block">Nombre de Correo Electrónico <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                          <input type="text" required="" class="form-control" name="email_nom" placeholder="Nombre de Correo Electrónico" value="{{ $datos->email_nom }}" />
                          
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label d-block">Correo electrónico <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                          <input type="text" required="" class="form-control" name="email" placeholder="Correo electrónico" value="{{ $datos->email }}" />
                          
                        </div>
                      </div>
                      <div class="form-group row show_block ">
                        <label for="img_cabecera" class="col-sm-4 col-form-label d-block">Logo del Sistema <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <img src="{{ asset('images/form/a')}}/{{$datos->logo}}" alt="Img logo" class="img-fluid @if($datos->logo == "") d-none @endif">

                           <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Tamaño: 400px ancho 400px alto / 100 KB</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                          <input type="file" @if($datos->logo == "") required @endif name="logo" id="logo" accept="image/x-png,image/gif,image/jpeg" class="dropify" value="{{ $datos->logo }}">
                          <button type="button" class="dropify-clear">Quitar</button>

                          <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>
                        </div>
                      </div>
                      
                      

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button type="submit" class="btn btn-dark mr-2">Actualizar</button>
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