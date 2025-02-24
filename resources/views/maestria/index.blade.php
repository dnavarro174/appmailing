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
              
              <h4 class="card-title">FORMATOS DE REQUERIMIENTOS <a href="{{ route('campanias.index') }}" class="btn btn-link">
                  <i class="mdi text-link mdi-keyboard-backspace"></i>
                Volver al listado</a>
              </h4>
              
              
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('maestria.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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

                  {{ Form::open(array('route' => array('maestria.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <a href="{{ route('maestria.create') }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a>
                      @endif

                     
                      @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                      <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reporte
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('reportes.e_registrados')}}">Registrados</a>
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
                          {{ $maestria_datos->firstItem() }} - {{ $maestria_datos->lastItem() }} de
                          {{ $maestria_datos->total() }}
                        </span>
                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}


                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" style="width: 3%;">{{-- permisos --}}</th>
                              <th class="sorting" style="width: 2%;">#</th>
                              <th class="sorting" style="width: 2%;">Requerimiento</th>
                              <th class="sorting" style="width: 8%;">Fecha Req.</th>
                              <th class="sorting" style="width: 40%;">Descripción</th>
                              <th class="sorting" style="width: 10%;">U.O_Solicitante</th>
                              <th class="sorting" style="width: 12%;">U.O_Beneficiada</th>
                              <th class="sorting" style="width: 10%;">Tipo B/S</th>
                              <th class="sorting" style="width: 10%;">FechaReg</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($maestria_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('maestria.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('maestria.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif

                                  {{-- <form style="display: inline;" method="POST" action="{{ route('maestria.destroy', $datos->id)}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}

                                    
                                    <button type="submit" class="button_submit" title="Eliminar"><img src="images/ico/trash.png" class="acciones" width="14" alt="edit icono"></button>
                                    
                                  </form> --}}
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td><a href="{{ route('maestria.edit',$datos->id)}}" title="Editar" >{{ $datos->numeror }}</a></td>
                                <td>{{ $datos->fecha_req }}</td>
                                <td>{{ $datos->descripcion }}</td>
                                <td>{{ $datos->uo_solicitante }}</td>
                                <td>{{ $datos->uo_beneficiada }}</td>
                                <td>{{ $datos->tipo_bs }}</td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                                <td>{{ $datos->created_at->format('d/m/Y') }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        
                        {!! $maestria_datos->appends(request()->query())->links() !!}

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


{{-- form modalHistorial --}}
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
    <form class=""  id="detalleProgramacion" name="detalleProgramacion" action="{{-- {{ route('maestria.enviar_det_programacion') }} --}}" method="post" >
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