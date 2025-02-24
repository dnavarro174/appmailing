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
                  
                  <h4 class="card-title text-transform-none">Creación del Grupo</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('grupo-dj.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="nombre_periodo" class="col-sm-2 col-form-label d-block">Grupo <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_periodo" placeholder="Nombre del Grupo *" value="{{ old('nombre_periodo') }}" />
                          
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="fechai_periodo" class="col-sm-2 col-form-label d-block">Fecha Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                          <div id="datepicker-popup" class="input-group date datepicker">
                            <input required="" type="text" class="form-control form-border" name="fechai_periodo" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          {!! $errors->first('fechai_periodo', '<span class=error>:message</span>') !!}
                        </div>

                        <label for="hora" class="col-sm-2 col-form-label ">Hora Inicio <span class="text-danger">*</span></label>
                          <div class="col-sm-3">
                            <input required="" type="text" class="form-control timepicker1" autocomplete="off" name="hora" placeholder="Hora Fin" value="{{ old('hora') }}" />
                          </div>
                      </div>

                      <div class="form-group row">
                        <label for="fechaf_periodo" class="col-sm-2 col-form-label d-block">Fecha Fin <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                          <div id="datepicker-popup2" class="input-group date datepicker">
                            <input required="" type="text" class="form-control form-border" name="fechaf_periodo" value="{{ date('d/m/Y')}}" placeholder="{{date('d/m/Y')}}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          {!! $errors->first('fechaf_periodo', '<span class=error>:message</span>') !!}
                        </div>

                        <label for="hora_fin" class="col-sm-2 col-form-label ">Hora Fin <span class="text-danger">*</span></label>
                          <div class="col-sm-3">
                            <input required="" type="text" class="form-control timepicker2" autocomplete="off" name="hora_fin" placeholder="Hora Fin" value="{{ old('hora_fin') }}" />
                          </div>
                      </div>

                      <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label">Vacantes <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="vacantes" required="" placeholder="Cantidad de vacantes" value="{{ old('vacantes') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="auto_conf" class="col-sm-2 col-form-label">Tendrá Confirmación</label>
                        <div class="col-sm-10 capa-auto_conf ">
                          <select class="form-control text-uppercase" id="auto_conf" name="auto_conf">
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row d-none campos_opcles">
                        <label for="email_asunto" class="col-sm-3 col-form-label">Asunto para la Confirmación <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input id="opc_1" type="text" class="form-control" name="email_asunto" placeholder="Ejm: Se ha registrado satistactoriamente al..."  value="{{ old('email_asunto') }}" />
                        </div>
                      </div>
                      <div class="form-group row d-none campos_opcles">
                        <label for="email_id" class="col-sm-3 col-form-label">Enviado desde <span class="text-danger">*</span></label>
                        <div class="col-sm-9 capa-email_id {{-- poner espacio --}}">
                          <select class="form-control" id="email_id" name="email_id">
                            <option value="">SELECCIONE / CHANGE</option>
                            @foreach($emails as $e)
                            <option @if($e->id == old('email_id')) selected @endif value="{{$e->id}}">{{$e->nombre}} - {{$e->email}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row d-none campos_opcles">
                        <label for="fecha_texto" class="col-sm-3 col-form-label">Confirmación con copia </label>
                        <div class="col-sm-9">
                          <input id="opc_3" type="email" class="form-control" name="fecha_texto" placeholder="Ingrese correo electrónico" value="{{ old('fecha_texto') }}" />
                        </div>
                      </div>

                      <div class="form-group row d-none" id="auto_conf_div">
                        <label for="auto_conf" class="col-sm-2 col-form-label">Confirmación por</label>
                        <div class="col-sm-10">
                          <div class="form-group row">
                            <div class="col-sm-4">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_email" name="confirm_email" type="checkbox" class="form-check-input" value="1"> Email <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
                            {{-- <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_msg" name="confirm_msg" type="checkbox" class="form-check-input" value="1"> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div> --}}
                          </div>

                        </div>
                      </div> {{-- end confirmacion --}}

                      <div class="form-group row">
                        <label for="p_conf_registro_gracias" class="col-sm-12 col-form-label d-block font-weight-bold">Pantallazo confirmación al finalizar el registro <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                          <textarea required="" placeholder="Pantallazo confirmación al finalizar el registro" class="form-control" name="p_conf_registro_gracias" id="" cols="30" rows="10">{{ old('p_conf_registro_gracias') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres

                            <a download="" class="pl-4" href="{{url('')}}/files/ma/modelo_pantallazo_confirmacion.html" target="_blank">Descargar plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                            
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 8 --}}
                      <div class="form-group row">
                        <label for="p_inscripcion_cerrado" class="col-sm-12 col-form-label d-block font-weight-bold">Grupo Cerrado (HTML) <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                          <textarea required="" placeholder="Grupo Cerrado (HTML)" class="form-control" name="p_inscripcion_cerrado" id="" cols="30" rows="10">{{ old('p_inscripcion_cerrado') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            <a download="" class="pl-4" href="{{url('')}}/files/ma/modelo_periodo_cerrado.html" target="_blank">Descargar plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 3 --}}
                      <div class="form-group row campos_opcles d-none">
                        <label for="p_conf_registro" class="col-sm-12 col-form-label d-block">Confirmación de registro del periodo (HTML) <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro del periodo (HTML)" class="form-control" name="p_conf_registro" id="p_conf_registro" cols="30" rows="10">{{ old('p_conf_registro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            <a download="" class="pl-4" href="{{url('')}}/files/ma/modelo_confirmacion_de_registro.html" target="_blank">Descargar plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row hidden_whatsapp">
                        <label for="p_conf_registro_2" class="col-sm-12 col-form-label d-block">Confirmación de registro del periodo (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro del evento (Whatsapp)" class="form-control" name="p_conf_registro_2" id="p_conf_registro_2" cols="30" rows="10">{{ old('p_conf_registro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres

                          </div>
                        </div>
                      </div>

                      

                      {{-- plantilla 4 --}}
                      <div class="form-group row campos_opcles d-none ">
                        <label for="p_recordatorio" class="col-sm-12 col-form-label d-block">Recordatorio (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (HTML)" class="form-control" name="p_recordatorio" id="" cols="30" rows="10">{{ old('p_recordatorio') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            <a download="" class="pl-4" href="{{url('')}}/files/ma/modelo_recordatorio.html" target="_blank">Descargar plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row hidden_whatsapp">
                        <label for="p_recordatorio_2" class="col-sm-12 col-form-label d-block">Recordatorio (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Recordatorio (Whatsapp)" class="form-control" name="p_recordatorio_2" id="" cols="30" rows="10">{{ old('p_recordatorio_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y continuar paso 2</button>
                        
                        <a href="{{ route('grupo-dj.index') }}" class="btn btn-light">Volver al listado</a>
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