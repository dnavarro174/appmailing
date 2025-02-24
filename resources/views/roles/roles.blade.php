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
              <h4 class="card-title">Listado de Roles</h4>
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        {{-- @if($text_search){{$text_search}} @endif --}}

                        <?php
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('roles.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                         
                      </div>
                      

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >Buscar</button>
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

                  {{ Form::open(array('route' => array('roles.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  
                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                      <a href="{{ route('roles.create') }}" title="Agregar Nuevo" class="btn btn-dark">
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
                          {{ $roles_datos->firstItem() }} - {{ $roles_datos->lastItem() }} de
                          {{ $roles_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width:2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              
                              <th style="width:5%;"></th>
                              <th style="width: 25%;">Rol</th>
                              <th style="width: 70%;">Descripción</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($roles_datos as $datos)
                            @if($datos->id != 1)
                              <tr role="row" class="odd">
                                <td><input type="checkbox" class="form btn-delete" name="ids_roles[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                  <td class="text-center">
                                    @if(@isset($permisos['permisos']['permiso']) and  $permisos['permisos']['permiso'] == 1)
                                    <a href="{{ route('roles.permisos',$datos->id)}}" class="">
                                      <i class="mdi mdi-account-key text-dark icon-md" title="Permisos"></i></a>
                                    @endif

                                    @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                    <a href="{{ route('roles.edit',$datos->id)}}" class=""><i class="mdi mdi-pencil text-info icon-md" title="Editar"></i></a>
                                    @endif
                                  </td>
                                  
                                  <td>{{ $datos->rol }}</td>
                                  <td>{{ $datos->descripcion }}</td>
                              </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table>
                        {!! $roles_datos->appends(request()->query())->links() !!}
                        
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