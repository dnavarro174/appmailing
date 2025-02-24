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
              <h4 class="card-title">INFORMACIÓN DE LOS ENVÍOS DE EMAILS</h4>

              
              {{-- <div class="row" id="capBusqueda" style="display: none;">
                <div class="col-12 form-inline">

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
                    
                </div>
              </div> --}}
              


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">

                <div class="col-xs-12 col-sm-3 col-lg-3">
                  <h4 class="card-title mt-5">SELECCIONE UNA PLANTILLA</h4>
                  <div class="bloque_plantilla border  mb-4 pt-2" style="height: 222px;overflow-x: auto;overflow-y: auto; ">
                    <ul class="">
                      @foreach ($plantilla_datos as $datos)
                      <li>
                        <a href="#1" id="{{ $datos->id }}">
                          <input type="radio" class="form btn-html" name="checkHTML" value="{{ $datos->id }}" data-xid="{{ $datos->id }}" >
                            <span class="openHTML" data-id="{{ $datos->id }}">{{ $datos->nombre }}</span>
                        </a>
                      </li>
                      @endforeach
                  </div>


<style>
.bloque_plantilla ul{padding: 0;margin:0;}
.bloque_plantilla ul li{list-style: none;}
.bloque_plantilla ul li a{ display: block;padding: 5px 15px; }
.bloque_plantilla ul li a:hover{background: #E7E7E7;color:#222;text-decoration: none;cursor: pointer;}

</style>
  
                  <a href="#" id="enviarCorreos" class="btn btn-primary" >ENVIAR CORREOS</a>
                  {{-- <a href="#" class="btn btn-secondary pcorreos" data-correo="">PROCESAR CORREOS</a> --}}
        
                </div>
                <div class="col-xs-12 col-sm-9 col-lg-9">

                  {{ Form::open(array('route' => array('plantillaemail.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <div class="col-sm text-right mb-4">

                    {{-- @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                    <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                    @endif --}}

                    @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                    <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                    @endif
                    {{--  --}}
                    
                    @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                    <a href="{{ route('plantillaemail.create') }}" class="btn btn-outline-success">Agregar Nuevo</a>
                    @endif
                  </div>

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                          <thead>
                            <tr role="row">
                              <th style="width: 5%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 45px;"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Order #: activate to sort column ascending" style="
                              width: 5%;">Item</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Nombre: activate to sort column ascending" style="width: 40%;">Nombre</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Asunto: activate to sort column ascending" style="width: 10%;">Asunto</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Base Price: activate to sort column ascending" style="width: 5%;">Gafete</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 10%;">Flujo Ejecucion</th>
                              <th class="sorting" tabindex="0" style="width: 15%;"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased Price: activate to sort column ascending" style="width: 15%;">Fecha Reg.</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($plantilla_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('plantillaemail.edit',$datos->id)}}" class=""><img src="{{ asset('images/ico/edit.png')}}" class="acciones" width="14" alt="edit icono" title="Editar"></a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('plantillaemail.show',$datos->id)}}" class=""><img src="{{ asset('images/ico/lupa.png')}}" class="acciones" width="14"  title="Mostrar"></a>
                                  @endif

                                </td>
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->nombre }}</td>
                                <td>{{ $datos->asunto }}</td>
                                <td>{{ $datos->gafete or 'NO' }}</td>
                                <td>{{ $datos->flujo_ejecucion }}</td>
                                <td><a href="#" class="btn btn-secondary pcorreos" data-correo="{{ $datos->id }}">PROCESAR CORREOS</a></td>
                                <td>{{ $datos->created_at->format('d/m/Y') }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}



                  {{-- modal openHTML --}}
                  <div class="modal fade ass" id="openHTML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-800" role="document">
                      <div class="modal-content">
                        
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span> 
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="row" id="plantillaHTML">
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  {{-- modal openHTML --}}





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
