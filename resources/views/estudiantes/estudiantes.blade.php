@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <div class="main-panel">
        
        <div class="content-wrapper p-0 mt-3">
          <div class="card">
            <div class="card-body">{{-- {{ app('request')->input('a') }} {{ request()->get('eventos_id') }}--}}
              <h4 class="card-title">Leads / Registros CAII <a class="btn btn-link" href="{{ route('caii.index') }}">Volver eventos</a></h4>
              
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-6 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('estudiantes.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="mod" id="mod">
                          <option value="">MODALIDAD</option>
                          @foreach($modalidades as $mod)
                          <option value="{{$mod->modalidad_id}}" 
                            {{ app('request')->input('mod')==$mod->modalidad_id?'selected':'' }}
                            >{{$mod->modalidad }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="st" id="filter-by-date" >
                          <option value="">TIPO</option>
                          @foreach($tipos as $tipo)
                          @if($tipo->id == 1 or $tipo->id == 2 or $tipo->id == 3)
                          <option value="@if($tipo->id == 3)@else{{$tipo->id}}@endif"
                            {{ app('request')->input('st')==$tipo->id ? 'selected':'' }}
                            >{{$tipo->nombre }}</option>
                          @endif
                          @endforeach
                        </select>
                      </div>
                      
                      <div class=" col-sm-1 col-xs-12">

                        <select class="form-control" name="reg" id="filter-by-date">{{-- onchange="submit();" --}}
                          <option selected="selected" value="">REGISTRADOS</option>
                          <option value="SI" {{ app('request')->input('reg')=="SI"?'selected':'' }}>SI</option>
                          <option value="NO" {{ app('request')->input('reg')=="NO"?'selected':'' }}>NO</option>
                        </select>
                      </div>

                      <div class=" col-sm-1 col-xs-12">
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == 15)) selected="" @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == 20)) selected="" @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == 30)) selected="" @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == 50)) selected="" @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == 100)) selected="" @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == 500)) selected="" @endif>500</option>
                          @else
                          <option value="15">15</option><option value="20">20</option><option value="30" >30</option><option value="50" >50</option><option value="100">100</option><option value="500">500</option>{{-- <option value="-1" >Todos</option> --}}
                          @endif
                        </select>
                      </div>

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>


              
              
              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('estudiantes.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-10 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <a href="{{ route('estudiantes.create',['eventos_id'=>session('eventos_id')]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a>
                      @endif

                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                      <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Importar" data-toggle="modal">Invitar VIP</a>
                      @endif

                      <a href="@if($evento_vencido != 1){{ route('invimport.index') }}@else # @endif" class="btn btn-sm @if($evento_vencido != 1) btn-success @else btn-secondary @endif"> Invitar Pre</a>

                      @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                      <a href="@if($evento_vencido != 1){{ route('form_confirmacion.index') }}@else # @endif" class="btn btn-sm @if($evento_vencido != 1) btn-dark @else btn-secondary @endif" title="Aprobar"> Aprobar</a>
                      
                      <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reporte
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('reportes.general')}}">Preinscritos</a>
                            <a class="dropdown-item" href="{{route('reportes.pre_aprobados')}}">Preinscritos Registrados</a>
                            <a class="dropdown-item" href="{{route('reportes.general_inv')}}">Invitados</a>
                            <a class="dropdown-item" href="{{route('reportes.registrados')}}">Invitados Registrados</a>
                            <a class="dropdown-item" href="{{route('reportes.r_actividad')}}">Actividades</a>
                            <a class="dropdown-item" href="{{route('upgrate.index')}}">Upgrate</a>
                            <a class="dropdown-item" href="{{route('bajas.index')}}">Bajas</a>
                            
                          </div>
                      </div>

                      <div class="btn-group d-none d-sm-inline-block" role="group">
                          <button id="btn_baja" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btn_baja">
                            <a class="dropdown-item" href="{{route('historiaemail.index')}}" target="_blank">Historial Mensajes Enviados</a>
                            <a class="dropdown-item" target="_blank" href="{{route('estudiantes.envio_email')}}">Verificar estado de msg enviados</a>
                            <a class="dropdown-item" target="_blank" href="{{route('caii.login')}}">Link login evento</a>
                          </div>
                      </div>
                      <div class="btn-group d-inline-block d-sm-none" role="group">
                        <button id="btn_baja" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Opc
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btn_baja">
                          <a class="dropdown-item" href="{{route('historiaemail.index')}}" target="_blank">Historial Mensajes Enviados</a>
                          <a class="dropdown-item" target="_blank" href="{{route('estudiantes.envio_email')}}">Verificar estado de msg enviados</a>
                          <a class="dropdown-item" target="_blank" href="{{route('caii.login')}}">Link login evento</a>
                        </div>
                    </div>

                      @endif
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Asistencia
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ route('asistencia.create',['eventos_id'=>session('eventos_id')]) }}">Registrar Ingreso y Salida</a>
                            <a class="dropdown-item" href="{{ route('asistencia.act',['eventos_id'=>session('eventos_id')]) }}">Registrar Asistencia por Actividades</a>
                            <div role="separator" class="dropdown-divider "></div>
                            <a class="dropdown-item" href="{{ route('asistencia.index', ['eventos_id' => session('eventos_id')]) }}" target="_blank">Listado de Asistencias</a>
                            <div role="separator" class="dropdown-divider "></div>
                            {{-- <a class="dropdown-item" href="{{route('asistencia.exp', ['id' => 0])}}">Reporte General</a>
                            <a class="dropdown-item" href="{{route('asistencia.exp', ['id' => 1])}}">Reporte de Actividades</a>
                            <a class="dropdown-item" href="{{route('asistencia.exp', ['id' => 2])}}">Reporte para Certificación / 4 asistencias</a> --}}
                            <a class="dropdown-item" href="{{route('reportes.a_general')}}">Reporte General</a>
                            <a class="dropdown-item" href="{{route('reportes.a_actividad')}}">Reporte de Actividades</a>
                          </div>
                        </div>
                      
                      <a href="#" @if($evento_vencido != 1) id="Recordatorio" @endif class="btn btn-sm @if($evento_vencido != 1) btn-dark @else btn-secondary @endif" data-id='{{session('eventos_id')}}' data-mod='caii'><i class="mdi mdi-email icon-md"></i>Recordatorio</a>
                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-2 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $estudiantes_datos->firstItem() }} - {{ $estudiantes_datos->lastItem() }} de
                          {{ $estudiantes_datos->total() }}
                        </span>
                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  

                  <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 3%;"></th>
                              <th style="width: 5%;">FechaReg</th>
                              <th style="width: 8%;"></th>
                              <th style="width: 8%;">DNI</th>
                              <th style="width: 25%;">Apellidos y Nombres</th>

                              <th style="width: 10%;">Cargo</th>
                              <th style="width: 10%;">Entidad</th>
                              <th style="width: 10%;">Grupo</th>
                              <th style="width: 5%;">Modalidad</th>
                              <th style="width: 5%;">Tipo</th>
                              <th style="width: 5%;">Registrado</th>
                              <th style="width: 10%;">Profesión</th>
                              <th style="width: 10%;">País</th>
                              <th style="width: 10%;">Departamento</th>
                              @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                              <th style="width: 5%;">Celular</th>
                              <th style="width: 5%;">Email</th>
                              @endif
                              <th style="width: 5%;">Cambio Tipo</th>
                              <th style="width: 3%;">Estado</th>
                              <th style="width: 3%"></th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($estudiantes_datos as $datos)
                            <?php
                                $v="";
                                if($datos->dtrack == "SI") $v='#fdfcbf';
                                if($datos->dtrack == "NO") $v='#f7d3d3';
                                if($datos->daccedio == "SI"){$v='#A0E8C5';}
                                /* <?php if($datos->dtrack == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->dtrack == "NO") echo "style='background:#f7d3d3;'"?> */
                              ?>
                            <tr role="row" class="odd" style="background:<?=$v;?>">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.show',['id'=>$datos->id])}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                    @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 4){{-- 1 --}}
                                    <a href="#" class="asignarProg" data-id="{{ $datos->dni_doc }}">
                                      <i class="mdi mdi-menu text-secondary icon-md"></i>
                                    </a>
                                    @endif
                                  {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}

                                <td>{{ $datos->created_at->format('d.m.Y') }}<br>{{ $datos->created_at->format('H:i:s') }}</td>
                                </td>
                                <td>
                                  <a href="{{ route('historiaemail.index',['s'=>$datos->dni_doc]) }}" target="_blank">
                                    <i class="mdi mdi-email text-warning icon-md" title="Historial"></i>
                                  </a>
                                </td>
                                <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                    @isset($datos->id)
                                    <a href="{{route('estudiantes.edit', $datos->id)}}" > 
                                      {{ $datos->dni_doc }}
                                      </a>
                                    @else
                                      {{ $datos->dni_doc }}
                                    @endif
                                    
                                  @else
                                    {{ $datos->dni_doc }}
                                  @endif
                                </td> {{-- onclick="openModal()" --}}
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->cargo, 25) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->organizacion, 25) }}</td>
                                {{-- <td> {{ $datos->departamento->nombre, '' }} </td> --}}
                                <td> {{ $datos->grupo, '' }} </td>
                                <td>
                                  <span class="badge mt-1 @if(optional($datos->Modalidad)->modalidad_id==1)badge-dark @endif @if(optional($datos->Modalidad)->modalidad_id==2)badge-danger @endif">{{ \Illuminate\Support\Str::limit($datos->Modalidad->modalidad,7,'') }} </span>
                                </td>
                                <td>
                                  <span class="badge @if($datos->estudiantes_tipo_id === 1)badge-primary @elseif($datos->estudiantes_tipo_id === 2)badge-success @elseif($datos->estudiantes_tipo_id === 3)badge-danger @else badge-dark @endif ">
                                    {{ optional($datos->tipo)->nombre,'' }}</span>
                                </td>
                                <td class="text-center">{{ $datos->daccedio }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->profesion, 25) }}</td>
                                <td> {{ $datos->pais, '' }} </td>
                                <td> {{ $datos->region, '' }} </td>
                                @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                <td>
                                  @if($datos->celular != "") {{ $datos->codigo_cel." ".$datos->celular }} @endif
                                </td>
                                <td>{{ $datos->email }}</td>
                                @endif
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                
                                <td class="text-center">
                                  @if($datos->cambio_tipo == 0)
                                    <i class="mdi mdi-bookmark-check text-secondary h4" title="NO"></i>
                                  @else
                                    <i class="mdi mdi-bookmark-check text-dark h4" title="SI"></i>
                                  @endif
                                </td>
                                <td class="text-center">
                                  @if($datos->estado == 0)
                                    <i class="mdi mdi-account-circle text-secondary h4" title="Inactivo"></i>
                                  @else
                                    <i class="mdi mdi-account-circle text-success h4" title="Activo"></i>
                                  @endif
                                </td>
                                <td>
                                  @if($evento_vencido != 1)<a href="#" class="badge badge-danger dar_baja" data-dni='{{ $datos->dni_doc }}' data-evento='{{ session('eventos_id') }}' title="Dar de baja">Dar de baja</a>@endif</td>
                                
                                
                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $estudiantes_datos->appends(request()->query())->links() !!}
                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}

                </div>
              </div> {{-- end cap_form_list --}}
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


{{-- form importar --}}
<div class="modal fade ass" id="Modal_estudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('estudiantes.import') }}" class="formarchivo" enctype="multipart/form-data" >
          {!! csrf_field() !!}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Importar Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> 
        </button>
      </div>
      <div class="modal-body pt-0">
          {{-- <div class="form-group row">
            <h4 class="col-md-3 mt-1">Export</h4>
            <div class="col-md-9">
              <a href="{{ route('estudiantes.export') }}" class="btn btn-secondary btn-block">Exportar</a>
              <span class="help-block with-errors"></span>
            </div>
          </div> --}}
          @if($evento_vencido != 1)
          <div class="form-group row">
            {{-- <h4 class="col-md-3 mt-1">Import</h4> --}}
            <div class="col-md-12">
              <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                <input type="file" name="file" id="archivo" class="dropify" required accept=".xls, .xlsx, .csv">
                <button type="button" class="dropify-clear">Quitar</button>

                <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>

              <span class="help-block with-errors"></span>

            </div>
          </div>
          @endif
        <div style="display:none;" id="cargador_excel" class="content-wrapper p-0" align="center">  {{-- msg cargando --}}
          <div class="card bg-white" style="background:#f3f3f3 !important;" >
            <div class="">
              <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
              <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
            </div>
          </div>
        </div>{{-- msg cargando --}}



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnImport1">Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade ass" id="Modal_organizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 95%; margin-top:2%; ">
    <div class="modal-content" style="max-height: 600px;">
    
      <div class="card">
        <div class="card-body" style=" overflow: scroll;max-height:520px;">
          <iframe src="{{ route('estudiantes.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none; border: 1px solid #e6e6e6;"></iframe>

          <form class="form-inline"  id="estudiantesImportSave" name="estudiantesImportSave" action="{{ route('estudiantes.importsave') }}" method="post" >
            {!! csrf_field() !!}
          <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox">
                  <label> <input class="rnr-checkbox" id="chkPrimeraFila" name="chkPrimeraFila" type="checkbox" value="1" checked> Cabeceras de columnas en la primera línea</label>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div id="dateFormatSettings1" class="rnr-is-control form-group">
                  
                    <label class="pr-2" style="font-size: 15px">Formato de fecha: </label>
                 
                  
                    <input id="txtFormatoF" name="txtFormatoF" type="text" value="dd/mm/yyyy" class="form-control border-primary">
                  
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox text-left">
                  <label class="d-flex justify-content-start text-dark font-weight-bold"> <input class="rnr-checkbox" id="chkE_invitacion" name="chkE_invitacion" type="checkbox" value="1" checked> Enviar Invitación</label>
                </div>
              </div>
              <div style="display:none;" id="cargador_excel2" class="content-wrapper p-0" align="center">{{-- end div cargando --}}
                <div class="card bg-white text-center p-3 border0" style="background:#fff !important;" >
                  <div class="row col-12">
                    <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                    <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                  </div>
                </div>
              </div> {{-- end div cargando --}}
          </div>
            
          <div class="row">
              <table id="tbl_estudiantes_imp_ord" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
              </table>
              <input type="hidden" name="totCol" id="totCol">
              <input type="hidden" name="hdnTabla" id="hdnTabla">
          </div>

          </form>
        </div>
      </div>
      <div class="modal-footer">
        <div id="resultado" style="display:none;">Cargando...</div>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="btnRegresar" >Regresar</button>

        <button type="button" class="btn btn-secondary" id="btnCerrar" {{-- data-dismiss="modal" --}}>Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnSumImport">Importar Datos</button>

      </div>

    </div>

  </div>
</div>

<div class="modal modalHistorial fade" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="heTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">{{-- modal-lg --}}
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="heTitle">Historial Estudiante: </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> 
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group row">
            
            <div class="col-md-12" id="historiaE">
              
              <table class="table table_his">
                <thead class="thead-inverse">
                  <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Denominación</th>
                    <th>Fecha Desde</th>
                    <th>Fecha Hasta</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>

            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        {{-- <button type="submit" class="btn btn-primary" id="btnImport1">Importar</button> --}}
      </div>
      
    </div>
  </div>
</div>

{{-- form importar --}}

@endsection