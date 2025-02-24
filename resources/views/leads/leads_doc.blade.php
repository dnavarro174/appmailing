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
              <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Leads / Registros 

                  @if(!session('evento')['maestria'])
                  <a class="btn btn-link" href="{{ route('eventos.index') }}"><i class="mdi text-link mdi-keyboard-backspace"></i> Volver eventos</a>
                  @else
                  <a class="btn btn-link" href="{{ route('grupo-doc.index') }}"><i class="mdi text-link mdi-keyboard-backspace"></i> Volver</a>
                  @endif
                </h4>
                <span class="badge badge-danger">{{\Illuminate\Support\Str::limit(session('evento')['nombre'],40)}}</span>
                
              </div>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">
                        <input type="hidden" name="tipo" id="tipo" value="{{$_GET['tipo']}}">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('leads.index', ['tipo'=>$_GET['tipo']])}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

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

                  {{ Form::open(array('route' => array('leads.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <input type="hidden" name="xtipo" id="xtipo" value="{{$_GET['tipo']}}">

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 2)
                        @if($_GET['tipo']!=4)
                        <a href="{{ route('leads.create', ['tipo'=>4]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn "><i class="mdi mdi-plus text-white icon-md" ></i></a>
                        @endif
                      @endif
                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 2)
                        @if($_GET['tipo']!=4)
                        <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Importar" data-toggle="modal"><i class="mdi mdi-upload text-white icon-btn"></i></a>
                        @endif
                      @endif
                      @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                      <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reporte
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('reportes.e_registrados')}}?t=9">Registrados</a>
                          </div>
                      </div>
                      @endif
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        @if(!session('evento')['maestria'])
                        <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Asistencia
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ route('asistencia.create',['eventos_id'=>session('eventos_id')]) }}">Registrar Ingreso y Salida</a>
                            <div role="separator" class="dropdown-divider "></div>
                            <a class="dropdown-item" href="{{ route('asistencia.index', ['eventos_id' => session('eventos_id')]) }}" target="_blank">Listado de Asistencias</a>
                            <div role="separator" class="dropdown-divider "></div>
                            <a class="dropdown-item" href="{{route('reportes.a_general')}}">Reporte General</a>
                          </div>
                        </div>
                        @endif
                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif

                      
                      {{-- <a href="{{ route('leads.migracion', ['tipo'=>9]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn "><i class="mdi mdi-plus text-white icon-md" ></i> Importar</a> --}}
                        
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $estudiantes_datos->firstItem() }} - {{ $estudiantes_datos->lastItem() }} de
                          {{ $estudiantes_datos->total() }}
                        </span>
                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  

                  <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="text-center" style="width: 3%;">#</th>
                              <th style="width: 5%;">Fecha</th>
                              <th class="text-center" style="width: 8%;">DNI</th>
                              <th style="width: 25%;">Apellidos y Nombres</th>
                              <th style="width: 5%;">Email</th>
                              <th style="width: 5%;">Email2</th>
                              <th style="width: 5%;">Celular</th>
                              <th style="width: 10%;">COND.1</th>
                              <th style="width: 10%;">COND.2</th>
                              <th style="width: 10%;">COND.3</th>
                              <th style="width: 5%;">COND.4</th>
                              <th style="width: 5%;">COND.5</th>
                              <th style="width: 5%;">COND.6</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($estudiantes_datos as $datos)
                            <tr role="row" class="odd" <?php if($datos->dtrack == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->dtrack == "NO") echo "style='background:#f7d3d3;'"?>>
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                    <a href="{{route('leads.edit',['id'=>$datos->id,'tipo'=>$_GET['tipo']])}}" title="Editar"><i class="mdi mdi-pencil text-dark icon-md"></i></a>
                                  
                                  @endif
                                </td>
                                <td>{{ $datos->created_at->format('d/m/y') }} <br>{{ $datos->created_at->format('H:i:s') }}</td>
                                <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                    <a href="{{route('leads.ficha',['id'=>$datos->id,'tipo'=>$_GET['tipo']])}}">{{ $datos->dni_doc }}</a>
                                  @else
                                    {{ $datos->dni_doc }}
                                  @endif
                                </td>
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                
                                
                                <td>{{ $datos->email }}</td>
                                <td>{{ $datos->email_labor }}</td>
                                <td>
                                  @if($datos->celular != "") {{ $datos->codigo_cel." ".$datos->celular }} @endif
                                </td>
                                <td class="text-center">{{ $datos->preg_1, '' }}</td>
                                <td class="text-center">{{ $datos->preg_2, '' }}</td>
                                <td class="text-center">{{ $datos->preg_3, '' }}</td>
                                <td class="text-center">{{ $datos->preg_4, '' }}</td>
                                <td class="text-center">{{ $datos->preg_5, '' }}</td>
                                <td class="text-center">{{ $datos->preg_6, '' }}</td>
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
      <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('leads.import') }}" class="formarchivo" enctype="multipart/form-data" >
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
              <a href="{{ route('leads.export') }}" class="btn btn-secondary btn-block">Exportar</a>
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
          @else
          <p>Evento finalizado.</p>
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

<div class="modal fade ass" id="Modal_organizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 95%; margin-top:2%; ">
    <div class="modal-content" style="max-height: 600px;">
    
      <div class="card">
        <div class="card-body" style=" overflow: scroll;max-height:520px;">
          <iframe src="{{ route('leads.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none; border: 1px solid #e6e6e6;"></iframe>

          <form class="form-inline"  id="estudiantesImportSave" name="estudiantesImportSave" action="{{ route('leads.importsave') }}" method="post" >
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
              {{-- <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox text-left">
                  <label class="d-flex justify-content-start text-dark font-weight-bold"> <input class="rnr-checkbox" id="chkE_invitacion" name="chkE_invitacion" type="checkbox" value="1" > Enviar Invitación</label>
                </div>
              </div> --}}
              <div style="display:none;" id="cargador_excel2" class="content-wrapper p-0">{{-- end div cargando --}}
                <div class="card bg-white text-center p-3 border0" style="background:#35b0ff !important;">
                  <div class="row " style="display: flex;justify-content: center;">
                    <label class="text-dark">&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                    <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label class="text-dark">Cargando registros excel...</label>
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
        <button type="button" class="btn btn-dark" id="btnSumImport">Importar Datos</button>

      </div>

    </div>

  </div>
</div>
{{-- form importar --}}

@endsection