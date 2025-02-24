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
            <div class="card-body">
              <h4 class="card-title">Actividades Académicas</h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-6 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('academico.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

                      </div>
                      
                      <div class=" col-sm-2 col-xs-12">

                        <select class="form-control" name="st" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="">UNIDAD</option>
                          <option value="1">SUB DIRECCIÓN ACADEMICA</option>
                          <option value="2">DIRECCIÓN DE POSTGRADO</option>
                        </select>
                      </div>
                      <div class=" col-sm-2 col-xs-12">

                        <select class="form-control" name="t" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="">TIPO</option>
                          <option value="1">PROGRAMA</option>
                          <option value="2">CURSO</option>
                          <option value="3">OTROS</option>
                        </select>
                      </div>

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
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
                    </div>
                  </form>
                </div>
              </div>


              
              
              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('academico.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        {{-- <a href="{{ route('academico.create',['id'=>session('eventos_id')]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a> --}}
                        <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Nuevo
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ route('academico.create', ['id'=>1]) }}" id="opt_programa"><i class="mdi mdi-plus-circle"></i> Programa</a>
                            <a class="dropdown-item" href="{{ route('academico.create', ['id'=>2]) }}" id="opt_curso"><i class="mdi mdi-plus-circle"></i> Curso</a>
                            <a class="dropdown-item" href="{{ route('academico.create', ['id'=>3]) }}" id="opt_otro"><i class="mdi mdi-plus-circle"></i> Otros</a>
                          </div>
                      </div>
                      @endif

                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                      <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Exportar / Importar" data-toggle="modal"><i class="mdi mdi-upload text-white icon-btn"></i></a>
                      @endif
                      @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                      
                      <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reporte
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('reportes.general')}}">Cursos</a>
                            <a class="dropdown-item" href="{{route('reportes.general')}}">Programas</a>
                            <a class="dropdown-item" href="{{route('reportes.general')}}">Otros</a>
                          </div>
                      </div>
                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $aca_datos->firstItem() }} - {{ $aca_datos->lastItem() }} de
                          {{ $aca_datos->total() }}
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
                              {{-- <th style="width: 2%;">#</th> --}}
                              <th style="width: 8%;">CÓDIGO</th>
                              <th style="width: 25%;">NOMBRE</th>

                              <th style="width: 10%;">DESCRIPCIÓN</th>
                              <th style="width: 10%;">FECHA INCIO</th>
                              <th style="width: 10%;">FECHA FIN</th>
                              <th style="width: 5%;">HORA INICIO</th>
                              <th style="width: 5%;">HORA FIN</th>
                              <th style="width: 10%;">UNIDAD</th>
                              <th style="width: 10%;">MODALIDAD</th>
                              <th style="width: 10%;">SESIONES</th>
                              <th style="width: 5%;">TIPO CONTROL</th>
                              <th style="width: 5%;">LUGAR</th>
                              <th style="width: 5%;">FECHAREG</th>
                              <th style="width: 3%;">ESTADO</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($aca_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('academico.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-dark icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('academico.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                </td>
                                <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                    <a href="{{route('est.index', ['academico_id'=>$datos->id])}}" class="btn btn-link p-0"> 
                                    {{ $datos->codigo }}
                                    </a>
                                  @else
                                    {{ $datos->codigo }}
                                  @endif
                                </td> {{-- onclick="openModal()" --}}
                                <td>{{ $datos->nombre }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->descripcion, 25) }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->f_incio)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->f_final)->format('d.m.Y') }} </td>
                                <td>{{ \Carbon\Carbon::parse($datos->h_inicio)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->h_final)->format('H:i') }}</td>
                                <td>
                                  <span class="badge @if($datos->unidad_area === 1)badge-primary @elseif($datos->unidad_area === 2)badge-success @elseif($datos->unidad_area === 3)badge-danger @else($datos->unidad_area === 4)badge-dark @endif">
                                    @if($datos->unidad_area === 1) Sub Dirección Academica @else($datos->unidad_area === 2) Sub Dirección de Postgrado @endif
                                  </span>
                                </td>
                                <td> {{ $datos->modalidad or '' }} </td>
                                <td>{{ $datos->sesiones }}</td>
                                <td>
                                  <span class="badge @if($datos->tipo_control === 1)badge-primary @elseif($datos->tipo_control === 2)badge-success @else($datos->tipo_control === 4)badge-dark @endif">
                                    @if($datos->tipo_control === 1) PROGRAMA @elseif($datos->tipo_control === 2) CURSO @else($datos->tipo_control === 2) OTROS @endif
                                  </span>
                                </td>
                                <td>{{ $datos->lugar }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->created_at)->format('d.m.Y') }}</td>
                                <td class="text-center">
                                  @if($datos->suspendido == 1)
                                    <i class="mdi mdi-account-circle text-secondary h4" title="Inactivo"></i>
                                  @else
                                    <i class="mdi mdi-account-circle text-success h4" title="Activo"></i>
                                  @endif
                                </td>
                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $aca_datos->appends(request()->query())->links() !!}
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
      <form  id="f_cargar_datos_cursos" name="f_cargar_datos_cursos" method="post"  action="{{ route('academico.import') }}" class="formarchivo" enctype="multipart/form-data" >
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
              <a href="{{ route('academico.export') }}" class="btn btn-secondary btn-block">Exportar</a>
              <span class="help-block with-errors"></span>
            </div>
          </div> --}}
          @if($evento_vencido != 1)
          <div class="form-group row">
            {{-- <h4 class="col-md-3 mt-1">Import</h4> --}}
            <div class="col-md-12">
              <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                <input type="file" name="file" id="archivo" class="dropify" required>
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
        <button type="submit" class="btn btn-dark" id="btnImport1">Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade ass" id="Modal_organizar_cursos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 95%; margin-top:2%; ">
    <div class="modal-content" style="max-height: 600px;">
    
      <div class="card">
        <div class="card-body" style=" overflow: scroll;max-height:520px;">
          <iframe src="{{ route('academico.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none; border: 1px solid #e6e6e6;"></iframe>

          <form class="form-inline"  id="cursosImportSave" name="cursosImportSave" action="{{ route('academico.importsave') }}" method="post" >
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
                 
                  
                    <input id="txtFormatoF" name="txtFormatoF" type="text" value="dd/mm/yyyy" class="form-control border-secondary">
                  
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
        <button type="button" class="btn btn-dark" id="btnSumImport_cursos">Importar Datos</button>

      </div>

    </div>

  </div>
</div>

{{-- form importar --}}

@endsection