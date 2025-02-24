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
                  
                  <h4 class="card-title text-transform-none">Creación del Evento Virtual</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('eventos.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="nombre_evento" class="col-sm-2 col-form-label d-block">Evento <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_evento" placeholder="Nombre del Evento *" value="{{ old('nombre_evento') }}" />
                        </div>
                      </div>
                      {{-- evento para controlar el evento --}}
                      <div class="form-group row">
                        <label for="fechai_evento" class="col-sm-2 col-form-label d-block">Fecha Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div id="datepicker-popup" class="input-group date datepicker">
                            <input required="" type="text" class="form-control form-border" name="fechai_evento" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          {!! $errors->first('fechai_evento', '<span class=error>:message</span>') !!}
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="fechaf_evento" class="col-sm-2 col-form-label d-block">Fecha Fin <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div id="datepicker-popup2" class="input-group date datepicker">
                            <input required="" type="text" class="form-control form-border" name="fechaf_evento" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          {!! $errors->first('fechaf_evento', '<span class=error>:message</span>') !!}
                        </div>
                      </div>



                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        
                        <a href="{{ route('eventos.index') }}" class="btn btn-light">Volver al listado</a>
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
console.log('Ready eventos');
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