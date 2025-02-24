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
                <h4 class="card-title">Auditoría Leads
                  <a class="btn btn-link" href="{{ url('') }}"><i class="mdi text-link mdi-keyboard-backspace"></i> Volver</a>
                </h4>

              </div>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" id="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('auditoriae.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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

                  {{ Form::open(array('route' => array('auditoriae.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}

                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <a href="{{ route('auditoriae.create') }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a>
                      @endif

                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                      <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Importar" data-toggle="modal"><i class="mdi mdi-upload text-white icon-btn"></i></a>
                      @endif
                      @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                      <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reporte
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('reportes.e_registrados')}}?t=6">Registrados</a>
                          </div>
                      </div>
                      @endif
                      @if(@isset($permisos['nuevo']['permiso']) and $permisos['nuevo']['permiso'] == 1)
                        <a href="#" id="Recordatorio" class="btn btn-sm btn-dark" data-id='{{session('correos_id')}}' data-mod='eventos'><i class="mdi mdi-email icon-md"></i>Generar Correos</a>
                      @endif
                      @if(@isset($permisos['exportar_importar']['permiso']) and $permisos['exportar_importar']['permiso'] == 1)
                        <a href="#" id="Exportar" class="btn btn-sm  btn-success" data-id='{{session('correos_id')}}' data-mod='eventos'><i class="mdi mdi-email icon-md"></i>Exportar</a>
                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif

                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $ae_datos->firstItem() }} - {{ $ae_datos->lastItem() }} de
                          {{ $ae_datos->total() }}
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
                              <th style="width: 2%;">#</th>
                              <th style="width: 10%;">Acción</th>
                              <th style="width: 10%;">Usuario</th>
                              <th style="width: 10%;">DNI</th>
                              <th style="width: 20%;">Apellidos_y_Nombres</th>
                              <th style="width: 10%;">Cargo</th>
                              <th style="width: 12%;">Profesión</th>
                              {{-- <th style="width: 10%;">Region</th> --}}
                              <th style="width: 10%;">Celular</th>
                              <th style="width: 10%;">Telefono</th>
                              <th style="width: 15%;">Email</th>
                              <th style="width: 5%;">FechaReg.</th>
                            </tr>
                          </thead>
                          <tbody>

                            @foreach ($ae_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('auditoriae.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('auditoriae.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->accion }}</td>
                                <td>{{ $datos->usuario }}</td>
                                <td>{{ $datos->dni_doc }}</td>
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ $datos->cargo }}</td>
                                <td>{{ $datos->profesion }}</td>
                                {{-- <td>{{ $datos->region }}</td> --}}
                                <td>{{ $datos->celular }}</td>
                                <td>{{ $datos->telefono }}</td>
                                <td>{{ $datos->email }}</td>
                                <td>{{ $datos->created_at->format('d/m/Y h:m:s') }}</td>
                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $ae_datos->appends(request()->query())->links() !!}
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


@endsection


