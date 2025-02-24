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
                  
                  <h4 class="card-title">Editar Leads / Registros</h4>
                  <p class="card-description">
                    <strong>Evento:</strong> {{session('academico_id')}}
                    <input type="hidden" id="eventos_id" value="{{session('academico_id')}}">
                    {{-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem fugit odit laudantium alias, soluta veniam eligendi obcaecati ea dolorem voluptas, assumenda debitis quasi aut cumque repellendus numquam earum aperiam iste! --}}
                  </p>
                  <form class="forms-sample" id="estudiantesForm"  action="{{ route('est.update', $estudiantes_datos->id) }}" method="post">
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
                        <label class=" col-form-label" for="inputdni">DNI / ID <span class="text-danger">*</span></label>
                        <input readonly="" class="form-control text-uppercase" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                          type="@if ($estudiantes_datos->tipo_documento_documento_id == 1) number @else text @endif"
                          @if ($estudiantes_datos->tipo_documento_documento_id == 1) maxlength='8' @else maxlength='15' @endif id="inputdni" name="inputdni" placeholder="DNI / ID" value="{{ $estudiantes_datos->dni_doc }}" autofocus>
                        {!! $errors->first('inputdni', '<span class=error>:message</span>') !!}
                      </div>

                      {{-- <div class="col-sm-4 form-group mt-4">
                        <div class="form-check">
                            <label class="form-check-label" for="enc">
                            <input type="checkbox" id="enc" name="check_newsletter" class="form-check-input" value="1">
                              Agregar al Newsletter</label>
                        </div>
                      </div> --}}
                      <div class="col-sm-12 col-md-4 form-group">
                        <label class=" col-form-label" for="grupo">Grupo / Group {{$estudiantes_datos->dgrupo}}<span class="text-danger">*</span></label>
                        <select  class="form-control text-uppercase" required="" name="grupo" id="grupo" class="codigo_cel">
                          <option value="">SELECCIONE / CHANGE</option>
                          @foreach($grupos as $tipo)
                          <option value="{{$tipo->codigo}}"
                            @if ($tipo->codigo === $estudiantes_datos->dgrupo)
                                  selected
                                @endif
                            >{{$tipo->nombre}}</option>
                          @endforeach
                        </select>
                      </div>

                      
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
                      {{-- <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboProvincia">Provincia</label>
                        <select class="form-control text-uppercase" id="cboProvincia" name="cboProvincia">
                          <option value="">SELECCIONE</option>
                        </select>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" for="cboDistrito">Distrito</label>
                        <select class="form-control text-uppercase" id="cboDistrito" name="cboDistrito">
                          <option value="">SELECCIONE</option>
                        </select>
                      </div> --}}
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Profesión-Ocupación / Profession-Occupation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputProfesion" name="inputProfesion" placeholder="Profesión-Ocupación" required="" value="{{ $estudiantes_datos->profesion }}">
                        {!! $errors->first('inputProfesion', '<span class=error>:message</span>') !!}
                      </div>

                    </div>
    



                    <div class="row">
                      
                      
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="inputOrganizacion">Entidad / Entity <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputOrganizacion" name="inputOrganizacion" required="" placeholder="Entidad / Entity" value="{{ $estudiantes_datos->organizacion }}">
                        {!! $errors->first('inputOrganizacion', '<span class=error>:message</span>') !!}
                      </div>

                    
                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Cargo / Charge <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="inputCargo" name="inputCargo" required="" placeholder="Cargo / Charge" value="{{ $estudiantes_datos->cargo }}">
                        {!! $errors->first('inputCargo', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label">Correo electrónico personal / Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputEmail" name="inputEmail" required="" placeholder="Correo electrónico personal / Email" value="{{ $estudiantes_datos->email }}">
                        {!! $errors->first('inputEmail', '<span class=error>:message</span>') !!}
                      </div>


                      <div class="col-sm-4 form-group">
                        <div class="form-group">
                          <label class="col-form-label" for="celular">Número Celular <span class="text-danger">*</span></label>
                          <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width: 120px;">
                              
                              <select class="form-control text-uppercase" name="codigo_cel" id="codigo_cel" required="">
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
                        if(isset($_GET['opc'])){
                          $ruta = route('est.index', array('opc'=>$_GET['opc']));
                          $opc ='<input type="hidden" name="opc" value="'.$_GET['opc'].'" />';
                        }else{
                          $ruta = route('est.index'); //URL::previous();
                          $opc ='';
                        }
                        ?>
                        {!! $opc !!}

                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="col-form-label" id="cboEstado">Estado</label>
                        
                        <select class="form-control text-uppercase" name="cboEstado" id="cboEstado" required="">
                          <option value="">Seleccione</option>
                          <option value="1" @if(1== $estudiantes_datos->estado) selected="" @endif>Activo</option>
                          <option value="0" @if(0== $estudiantes_datos->estado) selected="" @endif>Inactivo</option>
                        </select>

                      </div>
                      
                    </div>
            
                    
                    <div class="form-group row masinfo">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <div class="btn-group" role="group">
                          <button @if($evento_vencido == 1) disabled title="Evento Finalizado" @endif id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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