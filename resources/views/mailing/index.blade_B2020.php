@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      <div class="main-panel">
        
        <div class="content-wrapper mt-3">
          <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-transform-none">Mailing</h4>
                  
                  @if(Session::has('message-import'))
                  <p class="alert alert-info">{{ Session::get('message-import') }}</p>
                  @endif

                  <div id="capaEstudiantes" class="row">

                    <div class="col-12">
                      <form  action="{{route('mailing.store')}}" method="POST" id="form_html" style="display: inline;">

                        @csrf
                        

                      <div class="row">
                        <div class="col-xs-12 col-sm-6 mb-4">
                          <div class="col-xs-12 col-sm-12 col-lg-12">
                            <h4 class="card-title mt-0">Plantillas <a class="btn btn-link" href="{{ route('plantillaemail.create') }}">Crear HTML</a></h4>
                            <div class="bloque_plantilla border  mb-4 pt-2" style="height: 300px;overflow-x: auto;overflow-y: auto; ">
                              <ul class="">
                                @foreach ($plantilla_datos as $datos)
                                <li>
                                  <a href="#1" id="{{ $datos->id }}">
                                    <input type="radio" class="form btn-html" name="checkHTML" value="{{ $datos->id }}" data-xid="{{ $datos->id }}" >
                                      <span class="openHTML" data-id="{{ $datos->id }}">{{ $datos->nombre }}
                                        {{-- <em class="color-gris text-small" style="font-size: 10px;display: block;">{{ $datos->flujo_ejecucion }}</em> --}}</span>
                                  </a>
                                </li>
                                @endforeach
                              </ul>
                            </div>
                          </div>
                          
                        </div> {{-- end col-sm-3 --}}

                        <div class="col-xs-12 col-sm-6 mb-4" id="campo_2">
                          <div class=" col-sm-12 col-xs-12 mt-4 mb-2 mb-2">
                            <span class="text-white">&nbsp;</span>
                          </div>
                            <div class=" col-sm-12 col-xs-12 mb-2">
                              <select class="form-control" name="evento" id="evento">
                                <option selected="selected" value="">EVENTO</option>
                                @foreach($eventos as $evento)
                                <option value="{{$evento->id}}">{{$evento->nombre_evento }}</option>
                                @endforeach
                              </select>
                            </div>

                            <div class=" col-sm-12 col-xs-12 mb-2">
                              <select class="form-control" name="grupo" id="grupo">
                                <option selected="selected" value="">GRUPOS</option>
                                @foreach($tipos as $tipo)
                                <option value="{{$tipo->nombre}}">{{$tipo->nombre }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class=" col-sm-12 col-xs-12 mb-2">
                              <select class="form-control" name="pais" id="pais">
                                <option selected="selected" value="">PAÍS</option>
                                <option value="PERÚ">PERÚ</option>
                                @foreach($paises as $pais)
                                <option value="{{$pais->name}}">{{$pais->name }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class=" col-sm-12 col-xs-12 mb-2">
                              <select class="form-control" name="depa" id="depa">
                                <option selected="selected" value="">DEPARTAMENTOS</option>
                                <option value="LIMA">LIMA</option>
                                @foreach($departamentos_datos as $dep)
                                <option value="{{$dep->nombre}}">{{$dep->nombre }}</option>
                                @endforeach
                              </select>
                              <input type="hidden" name="valor" id="valor" value="0">
                            </div>

                            <div class="col-xs-12 col-sm-12 mt-3">
                              {{-- <div class="col-sm- form-group">
                                <label class=" col-form-label">
                                    <input type="checkbox" id="chek_enviarTodos" name="chek_enviarTodos" value="1" ><span id="spanConceder"> Enviar a toda la base de datos</span>
                                </label>
                              </div> --}}
                              <button type="submit" class="btn btn-dark btn-block" name="enviarCorreos" id="enviarCorreos">Enviar Correos</button>
                          </div>
                          

                        </div>


                        
                      </div>
                     
                    

                       {{-- end close form --}}
                      </form>


                        {{-- modal openHTML --}}
                        <div class="modal fade ass" id="openHTML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-800" role="document">
                            <div class="modal-content">
                              
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span> 
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="row" id="plantillaHTML"></div>

                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- modal openHTML --}}
                    </div>

                  </div> {{-- end cap_form_list --}}

                  
                </div>
              </div>
            </div>
          </div>
              
        </div> <!-- end listado table -->

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


<style>
.bloque_plantilla ul li {
    list-style: none;
}
</style>

@endsection
@section('scripts')
<script>
var check_todo = 0;

    $('#chek_enviarTodos').click(function(e) {

      if ($('#chek_enviarTodos').is(':checked')) {

        var check_enviar = $('#chek_enviarTodos').val();
        console.log('check_enviar: activado ='+check_enviar);

        if ($('._check').is(':checked')) {

          $('input[type=checkbox]:checked').each(function(i,j){
            $(".btn-delete").prop('checked', false);
          });
        }

        check_todo = 1;
         
      }else{
        console.log('check_enviar desactivado');
      }

    });

    
    // validar envio de mailing
    var campo   = 0;
    var num     = 0;
    var num_a   = 0;

    function valid (a, b){
      num = parseInt(a);
 
        if(b == ""){
          num_a = a - 1;
        }else if(b > 0){
          num_a = a + 1;
        }else if(b != ""){
          num_a = a + 1;
        }else{
          num_a = a - 1;
        }
        
        $('#valor').val(num_a);
        return num_a;
    }

    $('#evento').change(function(){
        var a = parseInt($('#valor').val());
        var b = $('#evento').val();
        valid(a, b);
    });

    $('#grupo').change(function(){
        var a = parseInt($('#valor').val());
        var b = $('#grupo').val();
        valid(a, b);

    });

    $('#pais').change(function(){
        var a = parseInt($('#valor').val());
        var b = $('#pais').val();
        valid(a, b);
    });
    $('#depa').change(function(){
        var a = parseInt($('#valor').val());
        var b = $('#depa').val();
        valid(a, b);
    });


    //$('#enviarCorreos').click(function(e) {
    $('#form_html').submit(function(e) {
      
      console.log('check_todo');
      $('#enviarCorreos').attr('disabled', true);

      check_todo   = 1;
      var check_plantilla = 0;

      if ($('.btn-html').is(':checked')) {
        
        $('li input:radio:checked').each(function(i,j){
          check_plantilla = $(this).val();
        });

      }else{

        swal("Advertencia", "Debe seleccionar una plantilla HTML", "warning");
        $('.bloque_plantilla').css('background','#d5ebf3');
        $('#enviarCorreos').attr('disabled', false);
        return false;
      }

      let valor  = $('#valor').val();
      if(valor == 0){
        $('#campo_2').css('background','#d5ebf3');
        swal("Advertencia", "Debe seleccionar una opción", "warning");
        $('#enviarCorreos').attr('disabled', false);
        return false;
      }
      $('#enviarCorreos').text('Procesando...');


    });
</script>
@endsection
