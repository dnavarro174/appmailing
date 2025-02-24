@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        
        <div class="content-wrapper p-0 mt-3">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Listado Tipo Documento</h4>
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-12 form-inline">
                  
                  <form action="{{ route('tipo_doc.index') }}" method="GET" class="form-inline pull-right">
                    <div class="form-group mb-2">
                      <input type="text" class="form-control border-primary text-uppercase" id="s" name="s" placeholder="Buscar" value="">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mb-2" id="buscar" {{-- onclick="buscarusuario()" --}} >Buscar</button>

                    <?php
                     if (isset($_GET['s'])){ ?>
                     <a class="ml-2 small" href=' {{route('tipo_doc.index')}} '>Borrar busqueda</a>
                    <?php } ?>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  {{ Form::open(array('route' => array('tipo_doc.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      <button type="submit" class="btn btn-secondary btn-sm" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>{{--  --}}

                      <a href="{{ route('tipo_doc.create') }}" class="btn btn-outline-success btn-sm">Agregar Nuevo</a>

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $tipo_doc_datos->firstItem() }} - {{ $tipo_doc_datos->lastItem() }} de
                        {{ $tipo_doc_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}
                    
                  </div> {{-- end row --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead>
                            <tr role="row">
                              <th style="width:1%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width:5%;">Acciones</th>
                              <th class="sorting item" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width:5%;">Item</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width:75%;">Tipo</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($tipo_doc_datos as $datos)
                              <tr role="row" class="odd">
                                <td>
                                  <input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}" >
                                </td>
                                <td>
                                  
                                  
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('tipo_doc.edit',$datos->id)}}">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('tipo_doc.show',$datos->id)}}">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif


                                </td>
                                <td class="text-center">{{ $datos->id }}</td>
                                <td><a href="{{ route('tipo_doc.edit',$datos->id)}}">{{ $datos->tipo_doc }}</a></td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $tipo_doc_datos->appends(request()->query())->links() !!}
                        
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

  <!-- plugins:js -->
  

@endsection