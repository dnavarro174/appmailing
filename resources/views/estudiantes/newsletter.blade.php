@extends('layout.home')

@section('content')

<div class="container-scroller">
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        
        <div class="content-wrapper pt-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">NEWSLETTER</h4>

              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif

              <div id="save_message">{{-- ss --}}</div>
              
              <div id="capaEstudiantes" class="row">

                {{-- buscador --}}
                {{ Form::open(array('route' => array('estudiantes.enviar_email'), 'method' => 'GET', 'role' => 'form', 'id' => 'search','style'=>'display:flex')) }}
                  <div class="col-xs-12 col-sm-12 col-lg-12 form-inline">
                    <div class="form-group">
                      <label for="prog" class="mr-2">Cod. Programación: </label>
                        <select class="form-control border-primary text-uppercase valid" id="prog" name="prog" >
                          <option value="">SELECCIONE</option>
                          @foreach ($programacion_datos as $prog)
                            <option value="{{$prog->codigo}}"
                              @if($prog->codigo === $proga)
                              selected
                              @endif
                              >{{$prog->codigo}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="form-group mx-sm-3">
                      <label for="ev" class="mr-2">Evento: </label>
                        <select class="form-control border-primary text-uppercase valid" id="ev" name="ev" >
                          <option value="">SELECCIONE</option>
                        </select>
                    </div>
                    {{-- <div class="form-group mx-sm-3">
                      <label for="fdesde" class="mr-2">Desde: </label>
                      <input type="text" class="form-control border-primary" id="fdesde" name="fdesde" placeholder="01/01/2018">
                    </div>
                    <div class="form-group">
                      <label for="fdesde" class="mr-2">Hasta: </label>
                      <input type="text" class="form-control border-primary" id="fdesde" name="fdesde" placeholder="01/01/2018">
                    </div> --}}
                    <div class="form-group mx-sm-3">
                        <select class="form-control border-primary text-uppercase valid" id="depa" name="depa" >
                          <option value="">DEPARTAMENTO</option>
                          @foreach ($departamentos_datos as $depa)
                          <option value="{{$depa->ubigeo_id}}"
                            @if($depa->ubigeo_id === $depas)
                              selected
                            @endif
                            >{{$depa->nombre}}</option>
                          @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary"  id="filtrar">Filtrar</button>
                      @if($proga or $depas) 
                        <div class="form-group mx-sm-3">
                        <a href="{{ route('estudiantes.enviar_email')}}">Borrar busqueda</a>
                      </div>
                      @endif
                    </div>
    
                  </div>
                {{ Form::close() }} {{-- end close form --}}
                {{-- end buscador --}}


              <div class="col-lg-12">
                {{ Form::open(array('route' => array('estudiantes.EmailEstudiantes'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:flex')) }}
              
                <div class="col-xs-12 col-sm-3 col-lg-3">
                  <h4 class="card-title mt-5">Seleccionar plantilla</h4>
                  <div class="bloque_plantilla border  mb-4 pt-2" style="height: 400px;overflow-x: auto;overflow-y: auto; ">
                    <ul class="">
                      @foreach ($plantilla_datos as $datos)
                      <li>
                        <a href="#" id="{{ $datos->id }}">
                          <input type="radio" class="form btn-html" name="checkHTML" value="{{ $datos->id }}" data-xid="{{ $datos->id }}" >
                            <span class="openHTML" data-id="{{ $datos->id }}">{{ $datos->nombre }} <br><em class="color-gris text-small">{{ $datos->flujo_ejecucion }}</em></span>
                        </a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label class=" col-form-label">
                                  <input type="checkbox" id="chkConceder" name="chkConceder" value="1" ><span id="spanConceder"> Enviar a todos</span>
                              </label>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary" name="enviarCorreos" id="enviarCorreos2">Enviar Correos</button>
                  {{-- <a href="#" id="enviarCorreos" class="btn btn-primary" >ENVIAR CORREOS</a> --}}
                  {{-- <a href="#" class="btn btn-secondary pcorreos" data-correo="">PROCESAR CORREOS</a> --}}
        
                </div>


                <div class="col-xs-12 col-sm-9 col-lg-9">

                  
                  <div class="col-sm text-right mb-4">

                    {{-- @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                    <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                    @endif --}}

                    {{-- BOTON EDITAR Y ELIMINAR

                    @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                    <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                    @endif
                    
                    
                    @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                    <a href="{{ route('estudiantes.create') }}" class="btn btn-outline-success">Agregar Nuevo</a>
                    @endif --}}
                  </div>

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                          <thead>
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              {{-- <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 3%;"></th> --}}
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Order #: activate to sort column ascending" style="width: 5%;">Item</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased On: activate to sort column ascending" style="width: 10%;">DNI</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Customer: activate to sort column ascending" style="width: 40%;">Apellidos y Nombres</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 10%;">Cargo</th>
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
                              <td><input type="checkbox" class="form btn-delete _check" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              {{-- <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.edit',$datos->id)}}" class=""><img src="{{ asset('images/ico/edit.png')}}" class="acciones" width="14" alt="edit icono" title="Editar"></a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.show',$datos->id)}}" class=""><img src="{{ asset('images/ico/lupa.png')}}" class="acciones" width="14"  title="Mostrar"></a>
                                  @endif

                                </td> --}}
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->dni_doc }}</td>
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno.', '. $datos->nombres }}</td>
                                <td>{{ $datos->cargo }}</td>
                                {{-- <td>{{ $datos->departamento->nombre or 'ddd' }}</td> --}}{{-- falta departamento --}}
                                <td>{{ $datos->distrito }}</td>
                                <td>{{ $datos->celular }}</td>
                                <td>{{ $datos->telefono }}</td>
                                <td>{{ $datos->email }}</td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->format('d/m/Y') }}</td> --}}
                                <td>{{ $datos->created_at }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
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
