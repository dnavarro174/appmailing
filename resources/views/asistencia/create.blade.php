@extends('layout.home')

@section('content')


  <!-- partial -->
  <div class="container-scroller bg_fondo2">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one" >
        <div class="row w-100">
          <div class="col-lg-8 mx-auto">            
            <div class="auto-form-wrapper wrapper_login bg-light">
              <p class="text-center">
                <img class="text-center img-fluid" src="{{URL::route('home')}}/images/logo_ticketing.png" width="220" alt="Logo Ticketing" />
                {{-- <img class="text-center" src="https://enc-ticketing.org/tktv2/public/images/head_form.png"  alt="Logo Ticketing" /> --}}
              </p>
                <h4 class="text-center pt-4 pb-3"><strong class="text-danger">Registro de</strong> Asistencias</h4>
                {{-- <h5>
                  {{'Evento: '. session('t') }}
                </h5> --}}
              <div class="col-lg-8 mx-auto">
                <form class="forms-sample" id="asistenciaForm" action="{{ route('asistencia.store') }}" method="post">
                  {!! csrf_field() !!}

                  <div class="form-group row">
                    <label for="asistencia" class="col-sm-3 col-form-label text-right d-none d-sm-block">DNI:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="asistencia_dni" id="asistencia_dni" placeholder="DNI" value="" autofocus="" required="" />
                    </div>
                  </div>

                  <div class="form-group row mb-0">
                    <label for="fecha" class="col-sm-3 col-form-label text-right d-none d-sm-block">Fecha:</label>
                    <div class="col-sm-9">
                      <label class="col-form-label">{{  date('d/m/Y') }}</label>
                    </div>
                  </div>
                  <div class="form-group row mb-0" >
                    <label for="fecha" class="col-sm-3 col-form-label text-right d-none d-sm-block">Hora:</label>
                    <div class="col-sm-9">
                      <label class="col-form-label">{{  date('H:i:s') }}</label>
                    </div>
                  </div>
                  <div id="hora"></div>

                  @if(session()->has('info'))
                  <div class="form-group row mb-0">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                      <div class="alert alert-fill-success" role="alert" id="block_reg">
                          <i class="mdi mdi-alert-circle"></i>
                          {{ session('info') }} - Registrados: {{session('cant')}}
                        </div>
                    </div>
                  </div>


                    
                  @endif
                  
                  <div class="form-group text-center mt-4">
                    <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-danger ">Registrar</button>

                    <?php
                    if(session('t')==2){
                      $ruta ="leads.index";  
                    }else{
                      $ruta ="estudiantes.index";  
                    }
                    ?>
                    @if(@isset($permisos['inicio']['permiso']) and  $permisos['inicio']['permiso'] == 1)
                      <a href="{{ route($ruta) }}" class="btn btn-light">Volver al listado</a>
                    @endif
                  </div>
                 
                </form>
                
              </div>


            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
      <!-- main-panel ends -->
   
@endsection

@section('scripts')

<script>
var btnRegister = $('#actionSubmit');
$("#asistenciaForm").on("submit", function(e) {
  e.preventDefault();
  console.log('guardando...');

  let url = "/asistencia";
  var token = $(this).attr('data-action');

  $.post( "{{route('asistencia.store')}}", {
      asistencia_dni: $("#asistencia_dni").val(),
      "_token": "{{ csrf_token() }}"
  },function(data){
    /*console.log(data);*/
      
  }).done(function( data ) {
    $('#hora').empty();
    $('#asistencia_dni').val('');

    var rs_data = `<div class='form-group row mb-0'>
      <div class='col-sm-3'></div>
      <div class='col-sm-9'>
        <div class='alert alert-fill-success' role='alert' id='block_reg'>
            <i class='mdi mdi-alert-circle'></i>
            ${data.msg}
          </div>
      </div>
    </div> `
    
    $('#hora').append(rs_data);

  }).always(function(){
      $('#actionSubmit').attr('disabled', true);
  });

    
});



$("#asistencia_dni").on("input", function(e) {

  e.preventDefault();
  var input = $(this);
  var val = input.val();
  val = $.trim(val);
  
  btnRegister.attr('disabled', true);

  $('#block_reg').css('display','none');

  if (val.length > 4) {

    let url = "consulta/"+val+"";

    $.get(url,function(resp,data){
        //console.log(resp);
        let rs_data = "";
        
        //if(resp.length>0){

          $('#hora').empty();
          // CASO TIPO 1: - LA FECHA NO EXISTE EN LOS EVENTOS REGISTRADOS
                      //  - DE LA FECHA QUE SI EXISTE, PERO EN LA HORA QUIZAS NO AYA NINGUNA ACTIVIDAD --> EN EL ERROR DEBE INDICAR  
          if(resp.tipo == 1){

            rs_data = '<div class="form-group row mb-0  text-white">'
                      +'<label class="col-sm-3 col-form-label text-right d-none d-sm-block"></label>'
                      +'<label class="col-sm-3 col-form-label  d-block d-sm-none"></label>'
                      +'<div class="col-sm-9  bg-'+resp.error+'">'
                        +'<p class=" text-center pt-3">'+resp.msg+'</p>'
                      +'</div>'
                      +'</div>';

            /* TIPO 2: EL PARTICIPANTE YA SE HA REGISTRADO*/
          }else{
            rs_data = '<div class="form-group row mb-0 ">'
                      +'<label class="col-sm-3 col-form-label text-right d-none d-sm-block">PARTICIPANTE:</label>'
                      +'<label class="col-sm-3 col-form-label  d-block d-sm-none">PARTICIPANTE:</label>'
                      +'<div class="col-sm-9 bg-success text-white">'
                        +'<h4 class="h6 pt-2 mb-0">'+resp.nombres+'</h4>'
                      +'</div>'
                      +'</div>'
                      +'<div class="form-group row mb-0">'
                      +'<label class="col-sm-3 col-form-label text-right d-none d-sm-block">EVENTO:</label>'
                      +'<label class="col-sm-3 col-form-label d-block d-sm-none">EVENTO:</label>'
                      +'<div class="col-sm-9 bg-success text-white">'
                        +'<label class="col-form-label">'+resp.titulo+ '</label>'
                      +'</div>'
                      +'</div>';

            btnRegister.attr('disabled',false);
          }
            
            $('#hora').append(rs_data);

      });

  }else{
    $('#hora').empty();
  }

});

// submit
$('form#asistenciaForm').submit(function (e) {
  btnRegister.attr('disabled', true);
});

</script>

@endsection