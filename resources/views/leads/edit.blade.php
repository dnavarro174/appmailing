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
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Editar Leads / Registros
                      @if($_GET['tipo']==8)
                          <span class="badge badge-success">OCI</span>
                          @php
                          $genera_pdf = 'dj_form.generate-pdf';
                          @endphp
                        @endif
                        @if($_GET['tipo']==10)
                          <span class="badge badge-danger">CGR</span>
                          @php
                          $genera_pdf = 'djcgr_form.generate-pdf';
                          @endphp
                        @endif
                    </h4>
                    <span class="badge @if($_GET['tipo']==8) badge-success @elseif($_GET['tipo']==10) badge-danger @else badge-dark @endif">{{\Illuminate\Support\Str::limit(session('evento')['nombre'],110)}}</span>
                  </div>
                  
                  <p class="card-description">
                    
                    @if (session()->has('status-dj'))

                        <a href="{{route($genera_pdf,[session('eventos_id'),$estudiantes_datos->dni_doc,$cursos_m4->detalle_id])}}" title="Hacer click" target="_blank"><span class="btn btn-small btn-danger">Descargar DDJJ</span></a>

                    @endif
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('leads.update', $estudiantes_datos->id) }}" method="post" enctype="multipart/form-data">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}

                    <div class="row">
                      <div class="col-sm-2 form-group">
                        <label class=" col-form-label" for="cboTipDoc">Tipo Doc / Type <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" name="cboTipDoc" id="cboTipDoc">
                            <option value="">SELECCIONAR...</option>
                            @foreach($tipo_doc as $tipoDoc)
                              <option value="{{ $tipoDoc->id }}" @if ($tipoDoc->id === $estudiantes_datos->tipo_documento_documento_id)
                                  selected
                                @endif
                                >{{ $tipoDoc->tipo_doc }}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label class=" col-form-label" for="dni_doc">DNI / ID <span class="text-danger">*</span></label>
                        <input readonly="" class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                          type="@if ($estudiantes_datos->tipo_documento_documento_id == 1) number @else text @endif"
                          @if ($estudiantes_datos->tipo_documento_documento_id == 1) maxlength='8' @else maxlength='15' @endif id="dni_doc" name="dni_doc" placeholder="DNI / ID" value="{{ $estudiantes_datos->dni_doc }}" autofocus>
                        {!! $errors->first('dni_doc', '<span class=error>:message</span>') !!}
                      </div>

                     
                      {{-- tipo 4: maestria / tipo 7: eventos especiales / DDJJ--}}
                      {{-- <span class="text-danger">*</span> --}}
                      <div class="col-sm-12 col-md-4 form-group @if($_GET['tipo']==4 or $_GET['tipo']==7 or $_GET['tipo']==8 or $_GET['tipo']==10) d-none @endif">
                          <label class=" col-form-label" for="grupo">Grupo / Group </label>
                          <select class="form-control text-uppercase" name="grupo" id="grupo" class="codigo_cel">
                            <option value="">SELECCIONE / CHANGE</option>
                            @foreach($grupos as $tipo)
                            <option value="{{$tipo->codigo}}"
                              @if ($tipo->codigo === $estudiantes_datos->dgrupo) selected @endif
                              >{{$tipo->nombre}}</option>
                            @endforeach
                          </select>
                      </div>
                      
                      <div class="col-sm-12 col-md-4 form-group d-none @if($_GET['tipo']==4) d-block @endif">
                          <label class=" col-form-label" for="confirmado">Apto para el proceso de Admisión </label>
                          <select class="form-control text-uppercase"  name="confirmado" id="confirmado">
                            <option value="0">SELECCIONE / CHANGE</option>
                            <option value="1"
                              @if (1 === $estudiantes_datos->confirmado) selected @endif
                              >APTO</option>
                            <option value="2"
                              @if (2 === $estudiantes_datos->confirmado) selected @endif
                              >NO APTO</option>
                          </select>
                      </div>
                      <div class="col-sm-12 col-md-4 form-group d-none @if($_GET['tipo']==4) d-block @endif">
                          <label class=" col-form-label" for="actividades_id">Aprobó Examen</label>
                          <select class="form-control text-uppercase"  name="actividades_id" id="actividades_id" >
                            <option value="0">SELECCIONE / CHANGE</option>
                            <option value="1"
                              @if (1 === $estudiantes_datos->actividades_id) selected @endif
                              >APROBÓ</option>
                            <option value="2"
                              @if (2 === $estudiantes_datos->actividades_id) selected @endif
                              >NO APROBÓ</option>
                          </select>
                      </div>

                      {{--  campos DDJJ  --}}
                      @if($_GET['tipo']==8 or $_GET['tipo']==10)
                      <div class="col-sm-12 col-md-4 form-group d-none @if($_GET['tipo']==8 or $_GET['tipo']==10) d-block @endif">
                        <label class=" col-form-label" for="confirmado">Accedió a la Beca </label>
                        <select class="form-control text-uppercase confirmado"  name="confirmado" id="confirmado">
                          <option value="0">SELECCIONE / CHANGE</option>
                          <option value="1"
                            @if (1 === $estudiantes_datos->confirmado) selected @endif
                            >SI</option>
                          <option value="2"
                            @if (2 === $estudiantes_datos->confirmado) selected @endif
                            >NO</option>
                          <option value="3"
                            @if (3 === $estudiantes_datos->confirmado) selected @endif
                            >RECHAZADO</option>
                        </select>
                      </div>
                      
                      <div class="col-sm-12 col-md-4 form-group d-none @if($_GET['tipo']==8 or $_GET['tipo']==10) d-block @endif">
                        <label class=" col-form-label" for="actividades_id">Aprobo </label>
                        <select class="form-control text-uppercase"  name="actividades_id" id="actividades_id">
                          <option value="0">SELECCIONE / CHANGE</option>
                          <option value="1"
                            @if (1 === $estudiantes_datos->actividades_id) selected @endif
                            >SI</option>
                          <option value="2"
                            @if (2 === $estudiantes_datos->actividades_id) selected @endif
                            >NO</option>
                        </select>
                      </div>
                      @endif

                    </div>



                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputNombres">Nombres / Name <span class="text-danger">*</span></label>
                        <input type="text" required="" class="form-control text-uppercase" id="inputNombres" name="inputNombres" placeholder="Nombres / Name" value="{{ $estudiantes_datos->nombres }}">
                        {!! $errors->first('inputNombres', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputApe_pat">Apellido Paterno / Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputApe_pat" name="inputApe_pat" required="" placeholder="Apellido Paterno / Last Name" value="{{ $estudiantes_datos->ap_paterno }}">
                        {!! $errors->first('inputApe_pat', '<span class=error>:message</span>') !!}
                        
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="inputApe_mat">Apellido Materno </label>
                        <input type="text" class="form-control text-uppercase" id="inputApe_mat" name="inputApe_mat" placeholder="Apellido Materno" value="{{ $estudiantes_datos->ap_materno }}">
                        {!! $errors->first('inputApe_mat', '<span class=error>:message</span>') !!}
                      </div>
                    </div>


                    <div id="cboPais" class="row cboPais">
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="pais">País / Country <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" required="" id="pais" name="pais">
                          <option value="">SELECCIONE</option>
                          <option value="PERU">PERU</option>
                          @foreach($countrys as $country)
                            <option class="text-uppercase" @if ($country->name === $estudiantes_datos->pais) selected @endif value="{{$country->name}}" data-id='{{$country->phonecode}}'>{{$country->name}}</option>
                          @endforeach
                        </select>

                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label class=" col-form-label" for="cboDepartamento">Departamentos / Departments @if ($estudiantes_datos->pais == "PERU")<span class="text-danger">*</span>@endif</label>
                        <select class="form-control text-uppercase" @if ($estudiantes_datos->pais == "PERU") required="" @endif id="cboDepartamento" name="region">
                          <option value="">SELECCIONE</option>
                            @foreach ($departamentos_datos as $ubigeo)
                            <option value="{{ $ubigeo->nombre }}" 
                              @if ($ubigeo->nombre === $estudiantes_datos->region)
                                    selected
                                  @endif>{{ $ubigeo->nombre }}</option>
                            @endforeach
                        </select>

                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboProvincia">Provincia</label>
                        <select @if($_GET['tipo']==4)disabled=""@endif class="form-control text-uppercase" id="cboProvincia" name="cboProvincia">
                          <option value="">SELECCIONE</option>
                          <option value="{{$estudiantes_datos->provincia}}" @if($estudiantes_datos->provincia!="") selected @endif>{{ $estudiantes_datos->provincia }}</option>
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboDistrito">Distrito</label>
                        <select @if($_GET['tipo']==4)disabled=""@endif class="form-control text-uppercase" id="cboDistrito" name="cboDistrito">
                          <option value="">SELECCIONE</option>
                          <option value="{{$estudiantes_datos->distrito}}" @if($estudiantes_datos->distrito!="") selected @endif>{{ $estudiantes_datos->distrito }}</option>
                        </select>
                      </div> 
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Profesión-Ocupación / Profession-Occupation </label>
                        <input type="text" class="form-control text-uppercase" id="inputProfesion" name="inputProfesion" placeholder="Profesión-Ocupación" value="{{ $estudiantes_datos->profesion }}">
                        {!! $errors->first('inputProfesion', '<span class=error>:message</span>') !!}
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="inputOrganizacion">Entidad / Entity 
                          @if($_GET['tipo']>=8 and $_GET['tipo']<=10) @else<span class="text-danger">*</span>@endif
                        </label>
                        <input type="text" class="form-control text-uppercase" id="inputOrganizacion" name="inputOrganizacion" 
                        @if($_GET['tipo']>=8 and $_GET['tipo']<=10) @else required @endif
                           placeholder="Entidad / Entity" value="{{ $estudiantes_datos->organizacion }}">
                        {!! $errors->first('inputOrganizacion', '<span class=error>:message</span>') !!}
                      </div>

                    
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Cargo / Charge <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputCargo" name="inputCargo" required="" placeholder="Cargo / Charge" value="{{ $estudiantes_datos->cargo }}">
                        {!! $errors->first('inputCargo', '<span class=error>:message</span>') !!}
                      </div>
                      {{-- SE QUITO:2023 <span class="text-danger">*</span> --}}
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo electrónico personal / Email </label>
                        <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Correo electrónico personal / Email" value="{{ $estudiantes_datos->email }}">
                        {!! $errors->first('inputEmail', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo alternativo </label>
                        <input type="text" class="form-control" id="email_labor" name="email_labor" r placeholder="Email alternativo" value="{{ $estudiantes_datos->email_labor }}">
                        {!! $errors->first('email_labor', '<span class=error>:message</span>') !!}
                      </div>

                      {{-- SE QUITO:2023 <span class="text-danger">*</span> --}}
                      <div class="col-sm-4 form-group">
                        <div class="form-group">
                          <label class="col-form-label" for="celular">Número Celular </label>
                          <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width: 120px;">
                              
                              <select class="form-control text-uppercase" name="codigo_cel" id="codigo_cel">
                                <option value="">Seleccione</option>
                                <option value="51">PERU</option>
                                @foreach($countrys as $country)
                                <option value="{{$country->phonecode}}" @if($country->phonecode == $estudiantes_datos->codigo_cel) selected="" @endif>{{$country->nicename}}</option>
                                @endforeach
                              </select>
                            </div>
                            <input type="text" class="form-control" id="celular" name="inputCelular" placeholder="CELULAR" value="{{ $estudiantes_datos->celular }}" required="">
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Teléfono </label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" value="{{ $estudiantes_datos->telefono }}">
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="track">Track</label>
                        <input type="text" class="form-control text-uppercase" id="track" name="track" placeholder="Track" value="{{ $estudiantes_datos->dtrack }}">
                        {!! $errors->first('track', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="accedio">Registrado</label>
                        <input type="text" class="form-control text-uppercase" id="accedio" name="accedio" placeholder="Accedio" value="{{ $estudiantes_datos->daccedio }}">
                        {!! $errors->first('accedio', '<span class=error>:message</span>') !!}
                        <?php
                        $ruta = '';$opc ='';
                        if(isset($_GET['opc'])){
                          $ruta = route('leads.index', array('opc'=>$_GET['opc']));
                          $opc ='<input type="hidden" name="opc" value="'.$_GET['opc'].'" />';
                        }else{
                          if(isset($_GET['tipo'])){
                            $ruta = route('leads.index', array('eventos_id'=>session('eventos_id')));
                          }
                        }
                        ?>
                        {!! $opc !!}

                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="cboEstado">Estado</label>
                        <select class="form-control text-uppercase" name="cboEstado" id="cboEstado" required="">
                          <option value="">Seleccione</option>
                          <option value="1" @if(1== $estudiantes_datos->estado) selected="" @endif>ACTIVO</option>
                          <option value="0" @if(0== $estudiantes_datos->estado) selected="" @endif>INACTIVO</option>
                        </select>
                      </div>
                        @if($_GET['tipo']==4)
                          <input type="hidden" name="grupo" value="{{$estudiantes_datos->dgrupo}}">
                        @endif
                      
                    </div>

                    {{-- Form para DDJJ --}}
                    
                    @if(count($cursos)>0)

                    <div class="row">
                      <div class="col-sm-3 form-group">
                        <label class=" col-form-label" for="nom_curso">Curso </label>{{-- required --}}
                        <select class="form-control text-uppercase" name="nom_curso" id="nom_curso">
                            <option value="">SELECCIONAR...</option>
                            @foreach($cursos as $curso)
                              <option value="{{ $curso->id }}" @if ($curso->cod_curso == $cursos_m4->cod_curso)
                                  selected
                                @endif
                                >{{ $curso->nom_curso }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="nom_curso2" id="nom_curso2">
                        <input type="hidden" name="cod_curso" id="cod_curso" value="1">
                        <input type="hidden" name="mod_curso" id="mod_curso">
                        <input type="hidden" name="fech_ini" id="fech_ini">
                        <input type="hidden" name="fech_fin" id="fech_fin">
                        <input type="hidden" name="id_detalle" id="id_detalle" value="{{$id_detalle}}">
                      </div>
                      <div class="col-sm-3 form-group">
                        <label class="col-form-label" id="foto">Firma</label>
                        <input type="file" class="form-control text-uppercase" id="foto" name="foto" placeholder="Firma" value="{{ $estudiantes_datos->dtrack }}">
                        {!! $errors->first('foto', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-2 form-group">
                        <label class="col-form-label" id="nota">Nota</label>
                        <input type="number" class="form-control text-uppercase" id="nota" name="nota" placeholder="Nota" value="{{ $cursos_m4->nota }}">
                        {!! $errors->first('nota', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="obs">Observaciones</label>
                        <textarea type="text" class="form-control " rows="6" id="obs" name="obs" placeholder="Observaciones">{{ $cursos_m4->obs }}</textarea>
                        {!! $errors->first('obs', '<span class=error>:message</span>') !!}
                      </div>
                      
                      
                    </div>

                    @endif
            
                    
                    <div class="form-group row masinfo">
                      <div class="col-sm-12 text-center mt-4">
                        <input type="hidden" name="eventos_id" id="eventos_id" value="{{session('eventos_id')}}">
                        <input type="hidden" name="tipo" id="tipo" value="{{$_GET['tipo']}}">

                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <div class="btn-group" role="group">
                          {{-- @if($evento_vencido == 1) disabled title="Evento Finalizado" @endif --}}
                          <button  id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <i class="mdi mdi-settings"></i> Opciones
                          </button>
                          <div class="dropdown-menu bg-light" aria-labelledby="btnGroupDrop1">
                            
                            <a class="dropdown-item solicitud" data-tipo='confirmacion' data-dni='{{$estudiantes_datos->dni_doc}}' data-evento='{{$eventos_id}}' href="#">Reenviar Confirmación</a>
                            <a class="dropdown-item solicitud" data-tipo='recordatorio' data-dni='{{$estudiantes_datos->dni_doc}}' data-evento='{{$eventos_id}}' href="#">Reenviar Recordatorio</a>
                          </div>
                        </div>

                        @if(Request::has('new'))
                        <a href="{{ route('newsletter.index')}}" class="btn btn-light">Volver al listado</a>
                        @endif

                        <a href="{{ $ruta }}" class="btn btn-light">Volver al listado</a>

                        {{-- <button type="button" class="btn btn-primary btn-sm" onclick="showToastPosition('bottom-right')">Bottom-right</button> --}}

                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">

                  <h4 class="card-title">HISTORIAL DE: {{$estudiantes_datos->nombres }}</h4>
                        
                        <table class="table table-striped">
                          <thead class="thead-dark">
                            <tr>
                              <th>#</th>
                              <th>Evento</th>
                              <th>Fecha Desde</th>
                              <th>Fecha Hasta</th>
                              <th>GAFETE</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($datos_h as $h)
                            <tr>
                                <th scope="row">{{$h->id}}</th>
                                <td>{{$h->nombre_evento}}</td>
                                <td>{{\Carbon\Carbon::parse($h->fechai_evento)->format('d.m.Y')}}</td>
                                <td>{{ \Carbon\Carbon::parse($h->fechaf_evento)->format('d.m.Y H:i')}}</td>
                                <td>
                                  @if($h->gafete == 1)<a href="{{url('/')}}/storage/confirmacion/{{$h->id.'-'.$estudiantes_datos->dni_doc}}.pdf" class="btn btn-small" target="_blank"><i class="mdi mdi-file-pdf"></i></a>@endif
                                </td>
                                <td>
                                  <?php 
                                  $f_act = \Carbon\Carbon::now(); 
                                  $f_fin = \Carbon\Carbon::parse($h->fechaf_evento);
                                  ?>
                                  @if($f_act >= $f_fin ) <label class="badge badge-secondary">Vencido</label>
                                  @else <label class="badge badge-success">Activo</label>
                                  @endif
                                </td>
                            </tr>
                              @foreach($datos_act as $act)
                                @if($act->eventos_id == $h->id)
                                  <tr>
                                    <th scope="row"></th>
                                    <td>{{$act->titulo ." ". $act->subtitulo}}</td>
                                    <td>{{$act->hora_inicio}}</td>
                                    <td>{{$act->hora_final}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif

  
                              @endforeach
                            @endforeach
                          </tbody>
                        </table>
                  
                </div>
              </div>
            </div>
          </div>
          
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
<script>
$(document).ready(function(){
  $('.confirmado').change(function(e){
    e.preventDefault();
    let rechazado = $(this).val();
    if(rechazado==2){
      swal("Advertencia", "Por favor indicar un mensaje en el campo * Observaciones * para que el participante subsane su D.J.", "warning");
      $('#obs').css('background','#b3ffff4d').val('');
      if(rechazado==3)$('#obs').css('background','rgb(253 232 230)').val('');
      return false;
    }
    

  });


});
</script>
@endsection
