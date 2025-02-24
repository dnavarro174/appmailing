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
                  
                  <h4 class="card-title text-transform-none">Creación del Evento</h4>
                
                  <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('eventos.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="nombre_evento" class="col-sm-2 col-form-label d-block">Evento <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_evento" placeholder="Nombre del Evento *" value="{{ old('nombre_evento') }}" />
                          
                        </div>
                      </div>

                      <div class="form-group row">

                        {{-- <div class="loader-demo-box">
                          <div class="pixel-loader"></div>
                        </div> --}}
                        
                        <label for="descripcion" class="col-sm-2 col-form-label d-block">Descripción</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción" class="form-control" name="descripcion" id="" cols="30" rows="5">{{ old('descripcion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="fecha_texto" class="col-sm-2 col-form-label d-block">Fecha en Texto <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="fecha_texto" placeholder="Ejm: 18 de Setiembre" value="{{ old('fecha_texto') }}" />
                          {{-- <input class="form-control" data-inputmask="'alias': 'email'"> --}}
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

                      {{-- <div class="form-group row">
                        <label for="fechaf_evento" class="col-sm-2 col-form-label ">Fecha Final <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="datetime-local" id="fechaf_evento" name="fechaf_evento" class="form-control" placeholder="" value="{{ \Carbon\Carbon::parse(now())->format('Y-m-d\TH:i') }}">

                          {!! $errors->first('fechaf_evento', '<span class=error>:message</span>') !!}
                        </div>
                      </div> --}}
                      
                      <div class="form-group row">
                        <label for="hora" class="col-sm-2 col-form-label">Hora Inicio<span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                          <input type="text" required="" class="form-control timepicker1" autocomplete="off" name="hora" placeholder="Hora Inicio" value="{{ old('hora') }}" />
                        </div>

                        <label for="hora_fin" class="col-sm-2 col-form-label ">Hora Fin <span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                          <input required="" type="text" class="form-control timepicker2" autocomplete="off" name="hora_fin" placeholder="Hora Fin" value="{{ old('hora_fin') }}" />
                        </div>

                        <label for="hora_fin" class="col-sm-2 col-form-label ">Hora Cierre <span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                          <select class="form-control text-uppercase" id="hora_cerrar" name="hora_cerrar" required="">
                            <option value="" selected="">SELECCIONE</option>
                            <option value="30" {{ old('hora_cerrar') == 30 ? 'selected' : '' }} >30 MIN ANTES</option>
                            <option value="60" {{ old('hora_cerrar') == 60 ? 'selected' : '' }}>1 HORA ANTES</option>
                            <option value="120" {{ old('hora_cerrar') == 120 ? 'selected' : '' }}>2 HORAS ANTES</option>
                          </select>
                        </div>

                      </div>
                     

                      <div class="form-group row">
                        <label for="lugar" class="col-sm-2 col-form-label">Lugar <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="lugar" required="" placeholder="Lugar" value="{{ old('lugar') }}" />
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
                        <div class="col-sm-10 capa-auto_conf {{-- poner espacio --}}">
                          <select class="form-control text-uppercase" id="auto_conf" name="auto_conf">
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email_asunto" class="col-sm-3 col-form-label">Asunto para la Confirmación <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" name="email_asunto" placeholder="Ejm: Se ha registrado satistactoriamente al..." required="" value="{{ old('email_asunto') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email_id" class="col-sm-3 col-form-label">Enviado desde <span class="text-danger">*</span></label>
                        <div class="col-sm-9 capa-email_id {{-- poner espacio --}}">
                          <select class="form-control" id="email_id" name="email_id" required="">
                            <option value="">SELECCIONE / CHANGE</option>
                            @foreach($emails as $e)
                            <option @if($e->id == old('email_id')) selected @endif value="{{$e->id}}">{{$e->nombre}} - {{$e->email}}</option>
                            @endforeach
                          </select>
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
                            <div class="col-sm-5">
                              <div class="form-check">
                                <div class="col-sm-10 form-check form-check-flat">
                                  <label class="form-check-label">
                                    <input id="confirm_msg" name="confirm_msg" type="checkbox" class="form-check-input" value="1"> Mensaje Whatsapp <i class="input-helper"></i></label>
                                </div>
                              </div>
                            </div>
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
                        <label for="p_inscripcion_cerrado" class="col-sm-12 col-form-label d-block font-weight-bold">Evento Cerrado (HTML) <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                          <textarea required="" placeholder="Evento Cerrado (HTML)" class="form-control" name="p_inscripcion_cerrado" id="" cols="30" rows="10">{{ old('p_inscripcion_cerrado') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            <a download="" class="pl-4" href="{{url('')}}/files/ma/modelo_periodo_cerrado.html" target="_blank">Descargar plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                          </div>
                        </div>
                      </div>

                      {{-- plantilla 3 --}}
                      <div class="form-group row hidden_email">
                        <label for="p_conf_registro" class="col-sm-12 col-form-label d-block">Confirmación de registro del evento (HTML)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro del evento (HTML)" class="form-control" name="p_conf_registro" id="" cols="30" rows="10">{{ old('p_conf_registro') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                            <a download="" class="pl-4" href="{{url('')}}/files/ma/modelo_confirmacion_de_registro.html" target="_blank">Descargar plantilla <i class="mdi mdi-eye text-primary icon-md" title="Ver HTML"></i></a>
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group row hidden_whatsapp">
                        <label for="p_conf_registro_2" class="col-sm-12 col-form-label d-block">Confirmación de registro del evento (Whatsapp)</label>
                        <div class="col-sm-12">
                          <textarea placeholder="Confirmación de registro del evento (Whatsapp)" class="form-control" name="p_conf_registro_2" id="" cols="30" rows="10">{{ old('p_conf_registro_2') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            10,000 caracteres
                          </div>
                        </div>
                      </div>

                      

                      {{-- plantilla 4 --}}
                      <div class="form-group row hidden_email">
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
                        <label for="gafete" class="col-sm-3 col-form-label">Gafete</label>
                        <div class="col-sm-9 capa-gafete {{-- poner espacio --}}">
                          <select class="form-control text-uppercase" id="gafete" name="gafete">
                            <option value="2">NO</option>
                            <option value="1">SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row d-none" id="campo_gafete" >
                        {{-- <label for="gafete_html" class="col-sm-2 col-form-label"></label> --}}
                        <div class="col-sm-3 capa-gafete">
                          <h4 class="card-title h6">Seleccionar plantilla</h4>
                          <div class="bloque_plantilla mb-4 py-2" style="height:185px;overflow-x: auto;overflow-y: auto; ">
                            <ul class=" p-0" style="list-style:none; ">
                              @foreach ($plantilla_datos as $html)
                              <li class="d-flex justify-content-between mb-1">
                                <a class="openHTML_2" data-id="{{ $html->id }}" href="#"><span>{{ $html->nombre }}</span></a>
                                <a class="badge badge-sm badge-dark" download="" href="/files/mod_gafetes/{{ $html->html }}.html" id="{{ $html->id }}">descargar</a>
                              </li>
                              @endforeach
                            </ul>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <textarea class="form-control" placeholder="FORM HTML GAFETE / QUE DESEA QUE SE MUESTRE EN EL GAFETE" name="gafete_html" id="gafete_html" cols="30" rows="15">{{ old('gafete_html') }}</textarea>
                            <div class="col alert alert-light border-0 mb-0 text-right">
                              50,000 caracteres
                            </div>
                        </div>
                      </div>

                      {{-- <div class="form-group row">
                        <label for="grupo" class="col-sm-2 col-form-label">Grupo</label>
                        <div class="col-sm-10">
                          <select class="form-control text-uppercase" id="grupo" name="grupo">
                            <option value="caii">CAII</option>
                            <option value="encomunicacion">ENComunicacion</option>
                            <option value="enconocimiento">ENConocimiento</option>
                            <option value="encultura">ENCultura</option>
                          </select>
                        </div>
                      </div> --}}


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y Continuar Paso 2</button>
                        
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