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
          <div class="row justify-content-center">
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card mt-4">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Evento </h4>
                  <p class="card-description">
                    
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('caiieventos.update', $datos->id) }}" method="post"> 
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                    <div class="form-group row">
                        <label for="nombre_evento" class="col-sm-2 col-form-label text-right">Evento <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="nombre_evento" placeholder="Nombre del Evento *" value="{{ $datos->nombre_evento }}" />
                        </div>
                      </div>
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label text-right">Descripción</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" name="descripcion" style="font-size: 13px;line-height: 20px;" cols="30" rows="10" placeholder="Descripción">{{ $datos->descripcion }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                            <a href="#" class="openHTML_plantilla" data-name='descripcion' data-id="{{ $datos->id }}">
                              <i class="mdi mdi-eye text-primary icon-md m-0" title="Ver HTML"></i>
                            </a>
                          </div>
                        </div>
                      </div>

                    <div class="form-group row">
                        <label for="fecha_texto" class="col-sm-2 col-form-label text-right">Fecha en Texto</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="fecha_texto" placeholder="Fecha" value="{{ $datos->fecha_texto }}" />
                          {{-- <input class="form-control" data-inputmask="'alias': 'email'"> --}}
                        </div>
                      </div>
                      {{-- evento para controlar el evento --}}
                      <div class="form-group row">
                        <label for="fechai_evento" class="col-sm-2 col-form-label text-right">Fecha Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div id="datepicker-popup" class="input-group date datepicker">
                            <input required="" type="text" class="form-control" name="fechai_evento" 
                            value="{!! \Carbon\Carbon::parse($datos->fechai_evento)->format('d/m/Y') !!}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="fechaf_evento" class="col-sm-2 col-form-label text-right">Fecha Final <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="datetime-local" id="fechaf_evento" name="fechaf_evento" class="form-control" value="{{ \Carbon\Carbon::parse($datos->fechaf_evento)->format('Y-m-d\TH:i') }}" placeholder="{{date('d/m/Y')}}">
                          {{-- <div id="datepicker-popup2" class="input-group date datepicker">
                            <input required="" type="text" class="form-control" name="fechaf_evento" 
                            value="{!! \Carbon\Carbon::parse($datos->fechaf_evento)->format('d/m/Y') !!}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div> --}}
                        </div>
                      </div>
                      {{-- evento: controlar pre-registro en form--}}
                      <div class="form-group row">
                        <label for="fechaf_pre_evento" class="col-sm-2 col-form-label text-right">Fecha Cierre de Inscripción<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          {{-- <div id="datepicker-popup3" class="input-group date datepicker">
                            <input required="" type="text" class="form-control" name="fechaf_pre_evento" 
                            value="{!! \Carbon\Carbon::parse($datos->fechaf_pre_evento)->format('d/m/Y') !!}">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div> --}}
                          <!-- // hora
                          <div class="input-group date" id="timepicker-example" data-target-input="nearest">
                            <div class="input-group" data-target="#timepicker-example" data-toggle="datetimepicker">
                              <input type="text" name="hora_2" class="form-control datetimepicker-input" data-target="#timepicker-example" value="">
                              <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
                            </div>
                          </div> -->
                          

                          <input type="datetime-local" id="fechaf_pre_evento" name="fechaf_pre_evento" class="form-control" value="{{ \Carbon\Carbon::parse($datos->fechaf_pre_evento)->format('Y-m-d\TH:i') }}" placeholder="{{date('d/m/Y')}}">

                        </div>
                      </div>
                      {{-- evento: controlar form foros--}}
                     

                      <div class="form-group row">
                        <label for="hora" class="col-sm-2 col-form-label text-right">Hora</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control timepicker1" autocomplete="off" name="hora" placeholder="Hora" value="{{ $datos->hora }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="lugar" class="col-sm-2 col-form-label text-right">Lugar</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="lugar" placeholder="Lugar" value="{{ $datos->lugar }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label text-right">Vacantes</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="vacantes" placeholder="Vacantes" value="{{ $datos->vacantes }}" />
                        </div>
                      </div>
                  
                    {{-- ASUNTO CONFIRMACION --}}
                    <div class="form-group row">
                      <label for="auto_conf" class="col-sm-2 col-form-label text-right" title="">Tendrá Confirmación </label>
                      <div class="col-sm-10 capa-auto_conf ">
                        <select class="form-control text-uppercase valid" id="auto_conf" name="auto_conf" aria-invalid="false">
                          <option value="0" selected="">NO</option>
                          <option value="1" {{ $datos->auto_conf==1?'selected':'' }}>SI</option>
                        </select>
                      </div>
                    </div>

                    {{-- <div class="form-group row {{ $datos->auto_conf=="0"?'d-none':'' }} campos_opcles">
                      <label for="email_asunto" class="col-sm-3 col-form-label">Asunto para la Confirmación <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="email_asunto" placeholder="Ejm: Se ha registrado satistactoriamente al..." required="" value="{{ $datos->email_asunto }}" />
                      </div>
                    </div> --}}
                    <div class="form-group row {{ $datos->auto_conf=="0"?'d-none':'' }} campos_opcles">
                      <label for="email_id" class="col-sm-2 col-form-label text-right">Enviado desde <span class="text-danger">*</span></label>
                      <div class="col-sm-10 capa-email_id {{-- poner espacio --}}">
                        <select class="form-control" id="email_id" name="email_id" required="">
                          <option value="">SELECCIONE / CHANGE</option>
                          @foreach($emails as $e)
                          <option @if($e->id == $datos->email_id) selected @endif value="{{$e->id}}">{{$e->nombre}} - {{$e->email}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    {{-- <div class="form-group row {{ $datos->auto_conf=="0"?'d-none':'' }} campos_opcles">
                      <label for="auto_conf" class="col-sm-3 col-form-label text-">Confirmación por</label>
                      <div class="col-sm-9">

                        <div class="form-group row">
                          <div class="col-sm-4">
                            <div class="form-check">
                              <div class="col-sm-10 form-check form-check-flat">
                                <label class="form-check-label">
                                  <input name="confirm_email" type="checkbox" class="form-check-input" value="1"
                                    @if($datos->confirm_email == 1) checked="" @endif> Email <i class="input-helper"></i></label>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="form-check">
                              <div class="col-sm-10 form-check form-check-flat">
                                <label class="form-check-label">
                                  <input name="confirm_msg" type="checkbox" class="form-check-input" value="1"
                                  @if($datos->confirm_msg == 1) checked="" @endif> Mensaje Whatsapp <i class="input-helper"></i></label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> --}}

                      <div class="form-group row">
                        <label for="gafete" class="col-sm-2 col-form-label text-right">Gafete</label>
                        <div class="col-sm-10">
                          <select class="form-control text-uppercase" id="gafete" name="gafete">
                            <option value="2"  @if($datos->gafete == 2) selected="" @endif>NO</option>
                            <option value="1"  @if($datos->gafete == 1) selected="" @endif>SI</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row @if($datos->gafete == 2)  d-none @endif" id="campo_gafete" >
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
                            <textarea class="form-control" placeholder="FORM HTML GAFETE" name="gafete_html" id="gafete_html" cols="30" rows="15">{{ $datos->gafete_html }}</textarea>
                            <div class="col alert alert-light border-0 mb-0 text-right">
                              50,000 caracteres
                              <a href="#" class="openHTML_plantilla" data-name='gafete_html' data-id="{{ $datos->id }}">
                              <i class="mdi mdi-eye text-primary icon-md m-0" title="Ver HTML"></i>
                            </a>
                            </div>
                       
                        </div>
                      </div>

                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        
                        <a href="{{ route('caii.index') }}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
          
          
        </div>
        @include('email.view_html.view_html')

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
<script>
  console.log('Ejecutando...');

  
</script>
@endsection