@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel p-0 mt-3">
        
        <div class="content-wrapper pt-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Historia Email <a class="btn btn-link" href="javascript:window.close();"><i class="mdi text-link mdi-keyboard-backspace"></i> Regresar</a></h4>
              
              <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">
                        <?php
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('historiaemail.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >BUSCAR</button>
                      </div>
                    </div>
                  </form>
         


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('historiaemail.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
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
                          {{ $historiaemail_datos->firstItem() }} - {{ $historiaemail_datos->lastItem() }} de
                          {{ $historiaemail_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th class="p-0 pl-2"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" style="width: 2%;"></th>
                              {{-- <th class="sorting" style="width: 2%;">#</th> --}}
                              <th class="sorting" colspan="1" style="width: 2%;">Tipo</th>
                              <th class="sorting" colspan="1" style="width: 3%;">Flujo</th>
                              <th class="sorting" colspan="1" style="width: 5%;">DNI</th>
                              <th class="sorting" colspan="1" style="width: 20%;">Destinatario</th>
                              <th class="sorting" colspan="1" style="width: 38%;">Asunto</th>
                              <th class="sorting" colspan="1" style="width: 12%;">Email</th>
                              <th class="sorting" colspan="1" style="width: 10%;">Celular</th>
                              <th class="sorting" colspan="1" style="width: 2%;">Evento</th>
                              <th class="sorting" colspan="1" style="width: 5%;">Raiz</th>
                              <th class="sorting" colspan="1" style="width: 5%;">Fech_Envio</th>
                              <th class="sorting" colspan="1" style="width: 5%;">Fech_Registro</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($historiaemail_datos as $datos)
                            <tr role="row" class="odd @if(\Carbon\Carbon::parse($datos->fecha_envio)=="2002-02-02 00:00:00")btn-danger @endif">
                              <td class="p-0 pl-2"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td>@if(@isset($permisos['inicio']['permiso']) and  $permisos['inicio']['permiso'] == 1)
                                    <a href="{{route('historiaemail.edit', $datos->id)}}">
                                      <i class="mdi mdi-pencil text-link icon-md" title="Editar"></i>
                                    </a>@endif</td>
                                <td>{{ $datos->tipo }}</td>
                                <td>{{ $datos->flujo_ejecucion }}</td>
                                <td>{{ $datos->estudiante_id }}</td>
                                <td>{{ $datos->nombres }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->asunto,250)}}</td>
                                <td>
                                  @if(\Carbon\Carbon::parse($datos->fecha_envio)=="2002-02-02 00:00:00")<a class="text-white" href="{{route('historiaemail.edit', $datos->id)}}">
                                  Actualizar: {{ $datos->email }}</a>
                                  @else
                                  {{ $datos->email }}
                                  @endif
                                </td>
                                <td>{{ $datos->celular }}</td>
                                <td>
                                 
                                    {{ $datos->eventos_id }} <i class="mdi mdi-star text-primary"></i>
                                 
                                </td>
                                <td>{{ $datos->actividades_id }}</td>
                                <td class="{{ $datos->fecha_envio=="2000-01-01 00:00:00"?'text-danger':'' }}">{{ \Carbon\Carbon::parse($datos->fecha_envio)->format('d.m.Y H:i:s') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->updated_at)->format('d.m.Y H:i:s') }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        
                        {!! $historiaemail_datos->appends(request()->query())->links() !!}
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