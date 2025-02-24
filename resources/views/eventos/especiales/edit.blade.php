@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-4">
          <div class="row justify-content-center">
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card mt-4">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Evento</h4>
                  <p class="card-description">
                    {{-- Lorem ipsum dolor sit amet --}}
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('eventos-es.update', $datos->id) }}" method="post"> 
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                    <div class="form-group row">
                        <label for="nombre_evento" class="col-sm-2 col-form-label ">Evento <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_evento" placeholder="Nombre del Evento *" value="{{ $datos->nombre_evento }}" />
                          <input type="hidden" name="cod_plantilla" value="{{$datos->cod_plantilla}}">
                        </div>
                      </div>

                      {{-- evento para controlar el evento --}}
                      <div class="form-group row">
                        <label for="fechai_evento" class="col-sm-2 col-form-label ">Fecha Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                          <div id="datepicker-popup" class="input-group date datepicker">
                            <input required="" type="text" class="form-control form-border" name="fechai_evento" 
                            value="{!! \Carbon\Carbon::parse($datos->fechai_evento)->format('d/m/Y') !!}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                        <label for="hora" class="col-sm-2 col-form-label ">Hora Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                          <input required="" type="text" class="form-control timepicker1" autocomplete="off" name="hora" placeholder="Hora Inicio" value="{{ $datos->hora }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="fechaf_evento" class="col-sm-2 col-form-label d-block">Fecha Fin <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                          <div id="datepicker-popup2" class="input-group date datepicker">
                            <input required="" type="text" class="form-control form-border" name="fechaf_evento" value="{!! \Carbon\Carbon::parse($datos->fechaf_evento)->format('d/m/Y') !!}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          {!! $errors->first('fechaf_evento', '<span class=error>:message</span>') !!}
                        </div>
                        <label for="hora_fin" class="col-sm-2 col-form-label ">Hora Fin <span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                          <input required="" type="text" class="form-control timepicker2" autocomplete="off" name="hora_fin" placeholder="Hora Fin" value="{{ $datos->hora_fin }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label ">Vacantes <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input required="" type="number" class="form-control" name="vacantes" placeholder="Vacantes" value="{{ $datos->vacantes }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="auto_conf" class="col-sm-2 col-form-label">Tendrá Confirmación</label>
                        <div class="col-sm-10 capa-auto_conf {{-- poner espacio --}}">
                          <select class="form-control text-uppercase" id="auto_conf" name="auto_conf">
                            <option value="0" @if($datos->auto_conf == 0) selected="" @endif>NO</option>
                            <option value="1" @if($datos->auto_conf == 1) selected="" @endif>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email_asunto" class="col-sm-3 col-form-label">Asunto para la Confirmación <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" name="email_asunto" placeholder="Ejm: Se ha registrado satistactoriamente al..." required="" value="{{ $datos->email_asunto }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email_id" class="col-sm-3 col-form-label">Enviado desde <span class="text-danger">*</span></label>
                        <div class="col-sm-9 capa-email_id {{-- poner espacio --}}">
                          <select class="form-control" id="email_id" name="email_id" required="">
                            <option value="">SELECCIONE / CHANGE</option>
                            @foreach($emails as $e)
                            <option @if($e->id == $datos->email_id) selected @endif value="{{$e->id}}">{{$e->nombre}} - {{$e->email}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="form-group row @if($datos->auto_conf == 0) d-none @endif" id="auto_conf_div">
                        <label for="auto_conf" class="col-sm-2 col-form-label ">Confirmación por</label>
                        <div class="col-sm-10">

                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_email"  name="confirm_email" type="checkbox" class="form-check-input" value="1"
                                      @if($datos->confirm_email == 1) checked="" @endif> Email <i class="input-helper"></i></label>
                                </div>

                                {{-- <label class="form-check-label">
                                  <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios1" value="" checked="">
                                  Free
                                <i class="input-helper"></i></label> --}}
                              </div>
                            </div>
                            {{-- <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_msg" name="confirm_msg" type="checkbox" class="form-check-input" value="1"
                                    @if($datos->confirm_msg == 1) checked="" @endif> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div> --}}
                          </div>

                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="p_conf_registro_gracias" class="col-sm-12 col-form-label d-block font-weight-bold">Pantallazo confirmación al finalizar el registro</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Pantallazo confirmación al finalizar el registro" class="form-control" name="p_conf_registro_gracias" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            
                            @if($datos->p_conf_registro_gracias != "")
                            <a class="pl-4" href="{{url('')}}/files/html/{{ $datos->p_conf_registro_gracias }}.html" target="_blank">Ver plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                            
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 8 --}}
                      <div class="form-group row">
                        <label for="p_inscripcion_cerrado" class="col-sm-12 col-form-label d-block font-weight-bold">Evento Cerrado (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Evento Cerrado (HTML)" class="form-control" name="p_inscripcion_cerrado" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            
                            @if($datos->p_inscripcion_cerrado != "")
                            <a class="pl-4" href="{{url('')}}/files/html/{{ $datos->p_inscripcion_cerrado }}.html" target="_blank">Ver plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 3 --}}
                      @if($datos->confirm_email == 1)
                      <div class="form-group row  capa_active">
                        <label for="p_conf_registro" class="col-sm-12 col-form-label d-block">Confirmación de registro del evento (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro del evento (HTML)" class="form-control" name="p_conf_registro" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            @if($datos->p_conf_registro != "")
                            <a class="pl-4" href="{{url('')}}/files/html/{{ $datos->p_conf_registro }}.html" target="_blank">Ver plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                            
                          </div>
                        </div>
                      </div>
                      @endif

                      @if($datos->confirm_msg == 1)
                      <div class="form-group row">
                        <label for="p_conf_registro_2" class="col-sm-12 col-form-label d-block">Confirmación de registro del evento (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro del evento (Whatsapp)" class="form-control" name="p_conf_registro_2" id="" cols="30" rows="10">{{ $datos->p_conf_registro_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      @endif

                      

                      {{-- plantilla 4 --}}
                      @if($datos->confirm_email == 1)
                      <div class="form-group row capa_active">
                        <label for="p_recordatorio" class="col-sm-12 col-form-label d-block">Recordatorio (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (HTML)" class="form-control" name="p_recordatorio" id="" cols="30" rows="10"></textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            
                            @if($datos->p_recordatorio != "")
                            <a class="pl-4" href="{{url('')}}/files/html/{{ $datos->p_recordatorio }}.html" target="_blank">Ver plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            @endif
                          </div>
                        </div>
                      </div>
                      @endif

                      @if($datos->confirm_msg == 1)
                      <div class="form-group row">
                        <label for="p_recordatorio_2" class="col-sm-12 col-form-label d-block">Recordatorio (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (Whatsapp)" class="form-control" name="p_recordatorio_2" id="" cols="30" rows="10">{{ $datos->p_recordatorio_2 }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>
                      @endif


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        
                        <a href="{{ route('eventos-es.index') }}" class="btn btn-light">Volver al listado</a>
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

  {{-- modal openHTML_plantilla --}}
                  <div class="modal fade ass" id="openHTML_plantilla" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-800" role="document">
                      <div class="modal-content">
                        
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span> 
                          </button>
                        </div>
                        <div class="modal-body pt-0">
                          <div class="row" id="plantillaHTML">
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  {{-- modal openHTML --}}

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
        $('.capa_active').css('display','block');
      }else{
        $('.capa_active').css('display','none');
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