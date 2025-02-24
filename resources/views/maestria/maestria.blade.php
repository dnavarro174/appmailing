@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
      <!-- end menu_user -->
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      
      @include('layout.menu_iz')
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        
        <div class="content-wrapper pt-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Leads / Registros</h4>


              <div class="row" id="capBusqueda" style="display: none;">
                <div class="col-12 form-inline">
                  
                  {{-- <form action="{{ route('estudiantes.index') }}" method="GET" class="form-inline"> --}}

                    <div class="form-group mb-2">
                      <select class="form-control border-primary text-uppercase valid" id="select_filtro_pais" name="select_filtro_pais" >
                        <option value="0">DEPARTAMENTOS</option>
                        @foreach ($departamentos_datos as $depa)
                          <option value="{{$depa->ubigeo_id}}">{{$depa->nombre}}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                      <input type="text" class="form-control border-primary text-uppercase" id="s" name="s" placeholder="Buscar" value="">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2" id="buscar" onclick="buscarusuario()" >Buscar</button>
                    {{-- <hr><span >  Resultados en  listado <b>< //listadopais; ></b></span> --}}
                  {{-- </form> --}}
                </div>
              </div>
              


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('estudiantes.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <div class="col-sm text-right mb-4">

                    @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                    <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal">{{-- <i class="mdi mdi-upload text-primary icon-md" title=""></i> --}} Exportar / Importar</a>
                    @endif

                    @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                    <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                    @endif
                    {{--  --}}
                    
                    @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                    <a href="{{ route('estudiantes.create') }}" class="btn btn-outline-success">{{-- <i class="mdi mdi-account-multiple text-success icon-md" title=""></i> --}} Nuevo</a>
                    @endif
                  </div>

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                          <thead>
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 3%;"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Order #: activate to sort column ascending" style="width: 3%;">#</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased On: activate to sort column ascending" style="width: 8%;">DNI</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Customer: activate to sort column ascending" style="width: 40%;">Apellidos_y_Nombres</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 10%;">Cargo</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 12%;">Profesión</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 10%;">Distrito</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 10%;">Celular</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 10%;">Telefono</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Base Price: activate to sort column ascending" style="width: 15%;">Email</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased Price: activate to sort column ascending" style="width: 5%;">FechaReg</th>
                              {{-- <th class="sorting_desc" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($estudiantes_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  <a href="#" class="asignarProg" data-id="{{ $datos->dni_doc }}">
                                    <i class="mdi mdi-menu text-secondary icon-md" title="Asignar Cod.Programación"></i>
                                  </a>
                                  @endif

                                  {{-- <form style="display: inline;" method="POST" action="{{ route('estudiantes.destroy', $datos->id)}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}

                                    
                                    <button type="submit" class="button_submit" title="Eliminar"><img src="images/ico/trash.png" class="acciones" width="14" alt="edit icono"></button>
                                    
                                  </form> --}}
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td><a href="#" data-id="{{$datos->dni_doc }}" class="estudianteHistorial" data-toggle="modalHistorial" >{{ $datos->dni_doc }}</a></td> {{-- onclick="openModal()" --}}
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ $datos->cargo }}</td>
                                <td>{{ $datos->profesion }}</td>
                                <td>{{ $datos->departamento->nombre or '' }}</td>{{-- falta departamento --}}
                                <td>{{ $datos->celular }}</td>
                                <td>{{ $datos->telefono }}</td>
                                <td>{{ $datos->email }}</td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                                <td>{{ $datos->created_at->format('d/m/Y') }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
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
        <h5 class="modal-title" id="exampleModalLabel">Importar / Exportar - Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> 
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group row">
            <h4 class="col-md-3 mt-1">Export</h4>
            <div class="col-md-9">
              <a href="{{ route('estudiantes.export') }}" class="btn btn-secondary btn-block">Exportar</a>
              <span class="help-block with-errors"></span>

            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <h4>Import</h4>
              <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                <input type="file" name="file" id="archivo" class="dropify" required>
                <button type="button" class="dropify-clear">Quitar</button>

                <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>

              <span class="help-block with-errors"></span>

            </div>
          </div>
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
    <div class="modal-content" style="max-height: 600px">
    

      <div class="card">
        <div class="card-body" style=" overflow: scroll; ">
          <iframe src="{{ route('estudiantes.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none;    border: 1px solid #e6e6e6;"></iframe>

          <form class="form-inline"  id="estudiantesImportSave" name="estudiantesImportSave" action="{{ route('estudiantes.importsave') }}" method="post" >
            {!! csrf_field() !!}
          <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox">
                  <label><input class="rnr-checkbox" id="chkPrimeraFila" name="chkPrimeraFila" type="checkbox" value="1" checked> Cabeceras de columnas en la primera línea</label>
                </div>
              </div>
            <div class="col-xs-12 col-sm-4">
                <div id="dateFormatSettings1" class="rnr-is-control form-group">
                  
                    <label class="pr-2" style="font-size: 15px">Formato de fecha: </label>
                 
                  
                    <input id="txtFormatoF" name="txtFormatoF" type="text" value="dd/mm/yyyy" class="form-control border-primary">
                  
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

        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCerrarIf">Cerrar</button>
        <button type="button" class="btn btn-dark" id="btnSumImport">Importar Datos</button>
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
                  {{-- <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                  </tr> --}}
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

{{-- Detalle programación --}}

<div class="modal modalCodProg fade" id="modalCodProg" tabindex="-1" role="dialog" aria-labelledby="heTitle" aria-hidden="true">
  <div class="modal-dialog " role="document">{{-- modal-lg --}}
    <form class=""  id="detalleProgramacion" name="detalleProgramacion" action="{{ route('estudiantes.enviar_det_programacion') }}" method="post" >
          {!! csrf_field() !!}
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="heTitle">Asignar <strong> programación</strong> al estudiante: </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
              
              <div class="col-md-12" id="detProgramacion">
                
                <table class="table table_his">
                  <thead class="thead-inverse">
                    <tr>
                      <th>COD.PROG</th>
                      <th>NOMBRE</th>
                      <th>DESDE</th>
                      <th>HASTA</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    </tr>
                  </tbody>
                </table>
                <input type="hidden" name="totalRows" id="totalRows" value="">

              </div>
            </div>
          



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="enviar_det_programacion">Asignar CódProgramacìón</button>
        </div>
        
      </div>
    </form>
  </div>
</div>
{{--  --}}


{{-- form importar --}}

@endsection