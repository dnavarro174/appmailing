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
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row justify-content-center">
                    <div class="col-md-8 grid-margin stretch-card">
                      <div id="unidad_enc" class="list-group w-100">
                        <a id="unida_enc_a" href="#" data-unidad='1' class="list-group-item list-group-item-action text-center">
                          Sub Dirección Academica
                        </a>
                        <a id="unida_enc_b" href="#" data-unidad='2' class="list-group-item list-group-item-action text-center">Sub Dirección de Postgrado</a>
                      </div>
                      
                    </div>
                  </div>
    
                  <div id="capa_control" style="display: none;">
                    <?php if (isset($_GET['id'])){if($_GET['id']==1){$n = "nuevo Programa";$xid=1;}elseif($_GET['id']==2){$n = "nuevo Curso";$xid=2;}else{$n = "nueva Actividad";$xid=3;}   } ?>
                    <h4 class="card-title text-transform-none">Crear  {{$n}} </h4>

                    @if (session('alert'))
                        <div class="alert alert-success">
                            {{ session('alert') }}
                        </div>
                    @endif

                  
                    <form class="forms-sample pr-4 pl-4" id="academicoForm" action="{{ route('academico.store') }}" method="post">
                      {!! csrf_field() !!}
                        
                        <input type="hidden" name="unidad_area" id="unidad_area" value="0">
                        <input type="hidden" name="tipo_control" id="tipo_control" value="{{$xid}}">


                      <div class="row">
                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="codigo">Codigo <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" autofocus="" name="codigo" id="codigo" required="" value="{{ old('nombres') }}" class="form-control text-uppercase">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="nombre">Nombre de Curso <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" name="nombre" id="nombre" required="" value="{{ old('nombre') }}" class="form-control text-uppercase">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="linea">Línea de Capacitación <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <select name="linea" id="linea" class="form-control" required="">
                                <option value="">SELECCIONE</option>
                                <option value="GP">GESTIÓN PÚBLICA</option>
                                <option value="FC">FORMACIÓN COMPLEMENTARIA</option>
                                <option value="CG">CONTROL GUBERNAMENTAL</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="modalidad">Modalidad <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" name="modalidad" id="modalidad" required="" value="{{ old('modalidad') }}" class="form-control text-uppercase">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="sesiones">Sesiones <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" name="sesiones" id="sesiones" required="" value="{{ old('sesiones') }}" class="form-control text-uppercase">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="h_cronologicas">H Cronologicas <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" name="h_cronologicas" id="h_cronologicas" required="" value="{{ old('h_cronologicas') }}" class="form-control text-uppercase">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                          <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <div class="input-group mb-2">
                              <textarea name="descripcion" id="descripcion" cols="30" rows="4" class="form-control">{{ old('descripcion') }}</textarea>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label for="f_inicio">Fecha Inicio <span class="text-danger">*</span></label>
                            <div id="datepicker-popup" class="input-group date datepicker">
                              <input required="" type="text" class="form-control form-border" name="f_inicio" id="f_inicio" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label for="f_final">Fecha Fin <span class="text-danger">*</span></label>
                            <div id="datepicker-popup2" class="input-group date datepicker">
                              <input required="" type="text" class="form-control form-border" name="f_final" id="f_final" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                              <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="h_inicio">Hora Inicio <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" required="" class="form-control timepicker1" autocomplete="off" name="h_inicio" id="h_inicio" placeholder="{{ date('H:m') }}" value="{{ date('H:m') }}" />
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="h_final">Hora Fin <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="text" required="" class="form-control timepicker2" autocomplete="off" name="h_final" id="h_final" placeholder="{{ date('H:m') }}" value="{{ date('H:m') }}" />
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="vacantes">Vacantes <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="number" required="" class="form-control" autocomplete="off" name="vacantes" id="vacantes"  value="" />
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                          <div class="form-group m-0 p-0">
                            <label for="f_fin">Días</label>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                              <div class="form-check">
                                  <div class="col-sm-12 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input id="L" name="L" type="checkbox" class="form-check-input" value="1"> Lun <i class="input-helper"></i><i class="input-helper"></i></label>
                                  </div>
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                              <div class="form-check">
                                  <div class="col-sm-12 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input id="M" name="M" type="checkbox" class="form-check-input" value="1"> Mar <i class="input-helper"></i><i class="input-helper"></i></label>
                                  </div>
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                              <div class="form-check">
                                  <div class="col-sm-12 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input id="MI" name="MI" type="checkbox" class="form-check-input" value="1"> Mie <i class="input-helper"></i><i class="input-helper"></i></label>
                                  </div>
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                              <div class="form-check">
                                  <div class="col-sm-12 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input id="J" name="J" type="checkbox" class="form-check-input" value="1"> Jue <i class="input-helper"></i><i class="input-helper"></i></label>
                                  </div>
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                              <div class="form-check">
                                  <div class="col-sm-12 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input id="V" name="V" type="checkbox" class="form-check-input" value="1"> Vie <i class="input-helper"></i><i class="input-helper"></i></label>
                                  </div>
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                              <div class="form-check">
                                  <div class="col-sm-12 form-check form-check-flat">
                                    <label class="form-check-label">
                                      <input id="S" name="S" type="checkbox" class="form-check-input" value="1"> Sab <i class="input-helper"></i><i class="input-helper"></i></label>
                                  </div>
                                </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <div class="col-sm-12 form-check form-check-flat">
                                      <label class="form-check-label">
                                        <input id="D" name="D" type="checkbox" class="form-check-input" value="1"> Dom <i class="input-helper"></i><i class="input-helper"></i></label>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        
                      <div class="row col-sm-12">
                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="docentes">Docente <span class="text-danger">*</span> <a href="#" id="addDoc" class="btn btn-link addAct py-0" style="font-size: 12px;"  onclick="formActividad('1','{{str_replace('/','-',12/12/2019)}}','0', 'docente','{{ url('') }}')">Agregar Docente</a></label>
                            <div class="input-group mb-2">
                              <input type="text" name="docentes" id="docentes" class="form-control" value="" required="">
                              <input type="hidden" name="dni_doc" id="dni_doc" class="form-control" value="" required="">
                              
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4 lugar_hidden"></div>

                        <div class="col-sm-12 col-md-4">
                          <div class="form-group">
                            <label for="lugar">Lugar <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input type="hidden" name="lugar_id" id="lugar_id" value="">
                              <select name="lugar" id="lugar" class="form-control" required="">
                                <option value="">SELECCIONE</option>
                                @foreach($departamentos_datos as $dep)
                                  <option class="text-uppercase" lugar='{{$dep->ubigeo_id}}' value="{{$dep->nombre}}">{{$dep->nombre}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-4 lugar_show" style="display: none;">
                          <a href="#" id="aulaCheck" class="btn btn-danger btn-sm mt-4">Verificar Disponibilidad</a>
                          <div class="spinner-border spinn" style="display: none;" role="status">
                            <span class="sr-only">Loading...</span>
                          </div>
                        </div>
                        
                      </div>{{-- end row --}}

                      <div id="aulas" class="row col-sm-7 ">
                        {{-- @include('academico.aulas') --}}
                        
                        
                      </div>{{-- end row --}}


                      </div>



                      <div class="form-group row">
                        <div class="col-sm-12 text-center mt-4">
                          <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                          
                          <a href="{{ route('academico.index') }}" class="btn btn-light">Volver al listado</a>
                        </div>

                      </div>

                    </form>
                  </div>
                  
                  
                </div>
              </div>
            </div>
          </div>
          
          
        </div>

        {{--  --}}
        <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content"> 
              <form  id="f_actividad" name="f_actividad" method="post"  action="{{ route('docentes.store') }}" class="formarchivo" >
                  {!! csrf_field() !!}
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span> 
                </button>
              </div>
              <div class="modal-body pt-0 form-act">
              </div>
              <div class="modal-footer">
                <a href="{{ route('docentes.index' )}}" id="addDoc" class="btn btn-link addAct">Listado Docentes</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        {{-- fin modal --}}
        

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
#unidad_enc a{transition:all ease-in 1s;}
#unidad_enc a:hover{background: #007bff;color:white;transition:all ease-out 1s;}
</style>
<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.themes.min.css') }}"> --}}
<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js_a/autocomplete.js')}}"></script>

<script>
console.log('Leyendo unidad');

$('document').ready(function(){

  $(".easy-autocomplete").css('width','100%');
  $("#unida_enc_a").click(function(e) {
    e.preventDefault();
    let unidad = $(this).data('unidad');
    $('#unidad_area').val(unidad);
    $('#unidad_enc').slideUp();
    $('#capa_control').show('slow');
  });

  $("#unida_enc_b").click(function(e) {
    e.preventDefault();
    console.log('click b');
    let unidad = $(this).data('unidad');
    $('#unidad_area').val(unidad);
    $('#unidad_enc').slideUp();
    $('#capa_control').show('slow');
  });

  // seleccionar todos
    $('#lugar').change(function() {

      let d = $("#lugar option:selected");
      let lugar_id = d.attr('lugar');
      $('#lugar_id').val(lugar_id);

      if(d.text()=="LIMA"){
        $(".lugar_show").show();
        $(".lugar_hidden").hide();
      }else{
        $(".lugar_show").hide();
        $(".lugar_hidden").show();
      }

    });

    $('#confirm_msg').change(function() {

      if ($('#confirm_msg').is(':checked')) {
        $('.hidden_whatsapp').css('display','block');
      }else{
        $('.hidden_whatsapp').css('display','none');
      }

    });

    // obligar que seleccione un check
    /*$('#actionSubmit').click(function(e){
      e.preventDefault();
       if( $('input:checkbox').is(':checked') ) {
          alert('Seleccionado');
      }else{
        console.log('no checked');
        return false;
      }


    });*/

    $('#aulaCheck').click(function(e){
      e.preventDefault();
      $('.spinn').css('display', 'block');
      loadHorario();
      $('.spinn').css('display', 'none');
    });

    // mostrar aulas disponibles
    function loadHorario(){
      let url = "/loadAulas";
      let p = {
        f_inicio: $('#f_inicio').val(),
        f_final: $('#f_final').val(),
        hinicio: $('#h_inicio').val(),
        hfin: $('#h_final').val(),
        L: $('#L').val(),
        M: $('#M').val(),
        MI: $('#MI').val(),
        J: $('#J').val(),
        V: $('#V').val(),
        S: $('#S').val(),
        D: $('#D').val()
      }
      $.get(url, p, function(rest){
        //console.log(rest);
        $('#aulas').html(rest);
      })
    }

});
//confirm_msg
//hidden_email

//hidden_whatsapp
</script>

@endsection