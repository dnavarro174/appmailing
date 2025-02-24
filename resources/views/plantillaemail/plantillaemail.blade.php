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
              <h4 class="card-title">Plantillas HTML <a href="{{ route('campanias.create')}}" class="btn btn-link">Ir a Mailing</a></h4>
              
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('leads.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

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

                  {{ Form::open(array('route' => array('plantillaemail.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      {{-- @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                      <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                      @endif --}}
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                      <a href="{{ route('plantillaemail.create') }}" class="btn btn-dark">Nuevo</a>
                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                      @endif

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $plantilla_datos->firstItem() }} - {{ $plantilla_datos->lastItem() }} de
                        {{ $plantilla_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}
                    
                  </div> {{-- end row --}}

                

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 1%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" style="width: 2%;"></th>
                              
                              <th style="width: 18%;">Nombre</th>
                              <th class="sorting" style="width: 25%;">Asunto</th>
                              
                              <th class="sorting" style="width:6%;">FechaReg.</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($plantilla_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('plantillaemail.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('plantillaemail.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif

                                </td>
                                
                                <td>{{ $datos->nombre }}</td>
                                <td>{{ $datos->asunto }}</td>
                               
                                <td>{{ $datos->created_at->format('d/m/Y h:m:s') }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $plantilla_datos->appends(request()->query())->links() !!}
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