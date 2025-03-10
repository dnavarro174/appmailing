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
      
      <div class="main-panel">
        
        <div class="content-wrapper p-0 mt-3">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title text-transform-none">Listado de Asistencia <a class="btn btn-link" href="{{ route('estudiantes.index') }}">Volver Participantes</a></h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class="col-sm-8 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href='{{route('asistencia.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

                      </div>
                      <div class="col-sm-1 col-xs-12">
                        <select class="form-control" name="fe" id="fe">
                          <option value="">SELECCIONE</option>
                          @foreach($dias as $key => $d)
                          <option value="{{ $d['fecha'] }}">{{ $d['fecha'] }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="col-sm-1 col-xs-12">
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
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >BUSCAR</button>
                        
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

                  {{ Form::open(array('route' => array('asistencia.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                 
                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-2">
                      
                      
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 2)
                      <a href="#" onclick="eximForm()" title="Exportar" class="btn btn-secondary" data-toggle="modal"><i class="mdi mdi-upload text-white icon-btn"></i></a>
                      {{-- <a href="{{ route('asistencia.asignar_foros') }}" class="btn btn-outline-danger">Asignar Foros</a> --}}
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
                            
                            <div role="separator" class="dropdown-divider "></div>
                            @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                              <a class="dropdown-item" href="{{route('reportes.a_general')}}">Reporte General</a>
                              <a class="dropdown-item" href="{{route('reportes.a_actividad')}}">Reporte de Actividades</a>
                            @endif
                          </div>
                        </div>
                      @endif
                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec">Borrar Seleccionados</button>
                      @endif

                      

                    </div>
                    {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-2 float-right">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $eventos_datos->firstItem() }} - {{ $eventos_datos->lastItem() }} de
                        {{ $eventos_datos->total() }}
                      </span>

                    </div> {{-- end izq --}}
                    
                  </div> {{-- end row --}}
                  

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 3%;">Item</th>
                              <th style="width: 4%;">DNI</th>
                              <th style="width: 10%;">Nombres</th>
                              <th style="width: 10%;">Ap_Paterno</th>
                              <th style="width: 10%;">Ap_Materno</th>
                              {{-- @if($tipo <> "") --}}
                              <th style="width: 15%;">Eventos</th>
                              <th style="width: 15%;">Actividades</th>
                              {{-- @endif --}}
                              <th style="width: 8%;">Fecha</th>
                              <th style="width: 8%;">Hora</th>
                              <th style="width: 10%;">Usuario</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($eventos_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              {{-- <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('asistencia.edit',$datos->id)}}" class=""><img src="{{ asset('images/ico/edit.png')}}" class="acciones" width="14" alt="edit icono" title="Editar"></a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('asistencia.show',$datos->id)}}" class=""><img src="{{ asset('images/ico/lupa.png')}}" class="acciones" width="14"  title="Mostrar"></a>
                                  @endif

                                </td> --}}
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->estudiantes_id,'dni' }}</td>
                                <td>{{ $datos->nombres,'nombre' }}</td>
                                <td>{{ $datos->ap_paterno, '' }}</td>
                                <td>{{ $datos->ap_materno, '' }}</td>
                                <td>@if(isset($datos->evento))
                                  {{ \Illuminate\Support\Str::limit($datos->evento->nombre_evento, 36) }}
                                  @endif
                                </td>
                                <td>@if(isset($datos->actividad_id)){{ \Illuminate\Support\Str::limit($datos->actividad->titulo,25) }}@else GENERAL @endif</td>
                                <td>{{ $datos->fecha }}</td>
                                <td>{{ $datos->hora }}</td>
                                <td>{{ $datos->name, ""}}</td>
                                {{-- <td>{{ $datos->usuario_id or 'usuario' }}</td> --}}
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $eventos_datos->appends(request()->query())->links() !!}
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
      <form  id="exportar_asistencia" name="exportar_asistencia" method="post" action="{{ route('asistencia.export') }}" class="formarchivo" enctype="multipart/form-data" >
          {!! csrf_field() !!}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Exportar Asistencia </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> 
        </button>
      </div>
      <div class="modal-body">
            
          <div class="form-group row">
            <div class="col-xs-12 col-md-7">
              <select name="tipo" id="tipo" class="form-control" required="">
                <option value="">SELECCIONE</option>
                <option value="0">Todos / del evento que esta asociado</option>
                <option value="1">Reporte para certificación</option>
              </select>
              
            </div>
            <div class="col-xs-12 col-md-5 p-0">
              <button type="submit" class="btn btn-dark btn-block" id="ExportarAsistencia"><i class="mdi mdi-cloud-check text-white icon-btn"></i> Descargar</button>{{-- enviar_det_programacion --}}
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
      </form>
    </div>
  </div>
</div>

@endsection