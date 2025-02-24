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
              <h4 class="card-title text-transform-none">Listado de Usuarios</h4>
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        {{-- @if($text_search){{$text_search}} @endif --}}
                        <?php
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('usuarios.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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
              </div>{{-- end busqueda --}}


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif

              
              
              <div class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('usuarios.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                      <a href="{{ route('usuarios.create') }}" title="Agregar Nuevo" class="btn btn-dark">
                        <i class="mdi mdi-plus text-white icon-md" ></i>
                      </a>
                      @endif
                      
                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                      @endif

                      
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $usuarios_datos->firstItem() }} - {{ $usuarios_datos->lastItem() }} de
                          {{ $usuarios_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}
                  
                 

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width:2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th aria-label="" style="width: 5%;"></th>
                              <th aria-label="Item #: activate to sort column ascending" style="width:5%;">Item</th>
                              <th aria-label="Curso" style="width: 30%;">Usuario</th>
                              <th aria-label="Categoría" style="width: 30%;">Email</th>
                              <th aria-label="Sesiones" style="width: 10%;">FechaRegistro</th>
                              <th style="width: 3%;">Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($usuarios_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td class="text-center">
                                  @if(@isset($permisos['roles']['permiso']) and  $permisos['roles']['permiso'] == 1)
                                  <a href="{{ route('usuarios.roles',$datos->id)}}">
                                    <i class="mdi mdi-key text-warning icon-md" title="Editar Rol"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('usuarios.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar Usuario"></i>
                                  </a>
                                  @endif
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->name }}</td>
                                <td>{{ $datos->email }}</td>{{-- categoria // @if(Auth::check()) ? yes : no @endif --}}
                                <td>{{ \Carbon\Carbon::parse($datos->created_at)->format('d.m.Y H:i:s') }} </td>
                                <td class="text-center">
                                  @if($datos->estado == 2)
                                    <i class="mdi mdi-account-circle text-secondary h4" title="Inactivo"></i>
                                  @else
                                    <i class="mdi mdi-account-circle text-success h4" title="Activo"></i>
                                  @endif
                                </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $usuarios_datos->appends(request()->query())->links() !!}

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
{{-- form importar --}}
<!--
<div class="modal fade ass" id="Modal_roles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      test
    </div>
  </div>
</div>
-->

<div class="modal fade" id="Modal_roles" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"></div>
  </div>
</div>
@endsection
