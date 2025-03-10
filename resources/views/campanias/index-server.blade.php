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
              <h4 class="card-title">Campañas</h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('campanias.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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
                  {{-- {{ Form::open(array('route' => array('campanias.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }} --}}
                  <form action="{{route('campanias.eliminarVarios')}}" id="form-delete" style='display:inline' method="post">
                      @csrf

                  <div class="row">{{-- cap: opciones --}}

                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <a href="{{ route('campanias.create', ['eventos_id'=>2]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i> Agregar Nuevo
                        </a>
                      @endif
                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_bd" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $camps->firstItem() }} - {{ $camps->lastItem() }} de
                          {{ $camps->total() }}
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
                              <th class="sorting" style="width: 2%;">#</th>
                              <th class="sorting" style="width: 8%;">Fecha</th>
                              <th class="sorting" style="width: 50%;">Nombre</th>
                              <th class="sorting" style="width: 50%;">Asunto</th>
                              {{-- <th class="sorting" style="width: 20%;"></th> --}}{{-- pausar campaña --}}
                              <th class="sorting" style="width: 20%;">Email</th>
                              <th class="sorting" style="width: 2%;">HTML</th>
                              <th class="sorting" style="width: 10%;">Filtro</th>
                              <th class="sorting" style="width: 8%;">Audi.Total</th>
                              <th class="sorting" style="width: 8%;">Enviados</th>
                              <th class="sorting" style="width: 8%;">Errores</th>
                              <th class="sorting" style="width: 8%;">#Accedio</th>
                            </tr>
                          </thead>
                          <tbody>

                            @foreach ($camps as $datos)
                            @php($totalf = $datos->total != 0 ? $datos->total : $datos->enviados+$datos->errores )
                            <tr role="row" class="odd" <?php if($datos->dtrack == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->dtrack == "NO") echo "style='background:#f7d3d3;'"?>>
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                @if(@isset($permisos['editarr']['permiso']) and  $permisos['editarr']['permiso'] == 1){{-- 1 --}}
                                <a href="{{ route('campanias.edit',$datos->id)}}"  data-id="{{ $datos->dni_doc }}">
                                  <i class="mdi mdi-pencil text-info icon-md"></i>
                                </a>
                                @endif
                              {{-- @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                              <a href="{{ route('campanias.show',$datos->id)}}" class="">
                                <i class="mdi mdi-eye text-dark icon-md" title="Mostrar"></i>
                              </a>
                              @endif --}}
                                @if( ($datos->enviados+$datos->errores) < $totalf )
                                    @if($verifica = \App\Models\JobCampanias::first($datos->id))
                                        @if($verifica == 1)
                                            <a href="{{ route('campanias.pause', $datos->id) }}"><i class='mdi mdi-pause text-danger icon-md' title="Pausar"></i></a>
                                        @else
                                            <a href="{{ route('campanias.play', $datos->id) }}"><i class='mdi mdi-play text-success icon-md' title="Continuar"></i></a>
                                        @endif
                                    @endif
                                @endif
                                </td>
                                <td class="text-center">{{ $datos->id }}</td>
                                <td>{{ $datos->created_at->format('d/m/y') }}<br>{{ $datos->created_at->format('H:i') }}</td>
                                <td>
                                  <a href="{{route('campanias.reportes', $datos->id)}}" class="p-0">{{ $datos->nombre?\Illuminate\Support\Str::limit($datos->nombre,70,'...'):'' }}</a>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->asunto, 70, '...') }}</td>
                                {{-- <td>
                                  <a href="#" class="badge badge-dark">Pausar Campaña</a><br>
                                  <a href="#" class="badge badge-danger">Cancelar Campaña</a><br>
                                </td> --}}
                                <td>{{\Illuminate\Support\Str::limit($datos->from_nombre, 25, '...')}}<br>{{$datos->from_email}}</td>
                                <td class="text-center">
                                  <a href="#" id="{{ $datos->id }}" title="Ver Plantilla ID:{{ $datos->checkHTML }}">
                                      <span class="openHTML" data-id="{{ $datos->checkHTML }}">
                                        <i class="mdi mdi-eye text-dark icon-md"></i>
                                      </span>
                                  </a>
                                </td>
                                <td>
                                  @if($datos->grupo)
                                  <span class="badge badge-light text-dark p-0 m-0">{{ $datos->grupo }}</span><br>
                                  @endif
                                  @if($datos->region)
                                  <span class="badge badge-light text-danger p-0 m-0">{{ $datos->pais }} / {{ $datos->region }}</span></td>
                                  @endif
                                  @if($datos->Evento)
                                  <br>
                                  <span class="badge badge-light text-primary p-0 m-0"><strong>Ev: </strong>
                                    {{ \Illuminate\Support\Str::limit($datos->Evento->nombre_evento,15,'...') }}</span>
                                  @endif
                                {{-- <td class="text-center">{{ $datos->total }}</td> --}}
                                <td class="text-center">{{ $totalf}}</td>
                                <td class="text-center">{{ $datos->enviados }}</td>
                                <td class="text-center">{{ $datos->errores }}</td>
                                <td class="text-center">{{ $datos->result->accedio }}</td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                            </tr>
                            @endforeach
                          </tbody>
                        </table>

                        {!! $camps->appends(request()->query())->links() !!}
                      </div>
                    </div>
                  </div>
                  </form>
                  {{--{{ Form::close() }}  end close form --}}

                </div>
              </div> {{-- end cap_form_list --}}
            </div>
          </div>
        </div> <!-- end listado table -->

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
                                              <div class="row" id="plantillaHTML"></div>

                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              {{-- modal openHTML --}}

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
