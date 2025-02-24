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
      <div class="main-panel">
        
        <div class="content-wrapper pt-0 mt-3">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Auditoría Programaciones</h4>
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-12 form-inline">
                  
                  <form action="{{ route('auditoriap.index') }}" method="GET" class="form-inline pull-right">
                    <div class="form-group mb-2">
                      <input type="text" class="form-control text-uppercase" id="s" name="s" placeholder="Buscar" value="">
                    </div>
                    <button type="submit" class="btn btn-sm btn-dark mb-2" id="buscar" {{-- onclick="buscarusuario()" --}} >Buscar</button>

                    <?php
                     if (isset($_GET['s'])){ ?>
                     <a class="ml-2 small" href=' {{route('auditoriap.index')}} '>Borrar busqueda</a>
                    <?php } ?>
                  </form>
                </div>
              </div>

              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('auditoriap.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec">Borrar Seleccionados</button>
                      @endif

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $ap_datos->firstItem() }} - {{ $ap_datos->lastItem() }} de
                        {{ $ap_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}
                    
                  </div> {{-- end row --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead>
                            <tr role="row">
                              <th style="width: 3%;" class="sinpadding"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 2%;"></th>
                              <th style="width: 2%;">#</th>
                              <th style="width: 5%;">Acción</th>
                              <th style="width: 5%;">Usuario</th>
                              <th style="width: 5%;">Código</th>
                              <th style="width: 20%;">Nombre</th>
                              <th style="width: 10%;">Tipo</th>
                              <th style="width: 25%;">Curso</th>
                              <th style="width: 13%;">N° Sesiones</th>
                              <th style="width: 10%;">FechaDesde</th>
                              <th style="width: 10%;">FechaHasta</th>
                              <th style="width: 5%;">FechaReg.</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($ap_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>

                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('auditoriap.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->accion }}</td>
                                <td>{{ $datos->usuario }}</td>
                                <td>{{ $datos->codigo }}</td>
                                <td>{{ $datos->nombre }}</td>
                                <td>{{ $datos->tipo }}</td>
                                <td>{{ $datos->nombre_curso }}</td>
                                <td>{{ $datos->nsesiones }}</td>
                                <td>{{ $datos->fecha_desde }}</td>
                                <td>{{ $datos->fecha_hasta }}</td>
                                <td>{{ $datos->created_at->format('d/m/Y h:m:s') }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $ap_datos->appends(request()->query())->links() !!}

                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}

                </div>
              </div>
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