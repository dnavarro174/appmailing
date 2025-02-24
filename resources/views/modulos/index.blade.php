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
                            <h4 class="card-title">Modulos</h4>

                            <div class="row" id="capBusqueda">
                                <div class="col-sm-12">
                                    <form>
                                        <div class="form-row">
                                            <div class=" col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                                                <?php if (isset($_GET['s'])){ ?>
                                                <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('modulos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                                                <?php } ?>

                                            </div>
                                            <div class=" col-sm-2 col-xs-12">
                                                <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
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
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if(Session::has('message-import'))
                                <p class="alert alert-info">{{ Session::get('message-import') }}</p>
                            @endif
                            <div id="capaModulos" class="row">
                                <div class="col-12">
                                    {{-- {{ Form::open(array('route' => array('campanias.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }} --}}
                                        <div class="row">{{-- cap: opciones --}}
                                            <div class="col-xs-12  col-sm-8 text-left mb-4">
                                                @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                                                    <a href="{{ route('modulos.create', ['eventos_id'=>2]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
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
                          {{ $mcategories->firstItem() }} - {{ $mcategories->lastItem() }} de
                          {{ $mcategories->total() }}
                        </span>
                                            </div>{{-- end izq --}}
                                        </div> {{-- end cap: opciones --}}

                                        <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                                            <div class="row">
                                                <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
                                                    <table id="order-listing" class="table table-hover table-sm">
                                                        <thead class="thead-dark">
                                                        <tr role="row">
                                                            <th style="width: 3%;"></th>
                                                            <th class="sorting" style="width: 2%;">#</th>
                                                            <th class="sorting" style="width: 50%;">Nombre</th>
                                                            <th class="sorting" style="width: 50%;">Descripción</th>
                                                            {{-- <th class="sorting" style="width: 20%;">Slug</th> --}}
                                                            <th class="sorting" style="width: 5%;"></th>
                                                            <th></th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach ($mcategories as $datos)
                                                            <tr role="row" class="odd" <?php if($datos->dtrack == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->dtrack == "NO") echo "style='background:#f7d3d3;'"?>>
                                                                <td nowrap="" align="center">
                                                                    @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1){{-- 1 --}}
                                                                    <a href="{{ route('modulos.edit',$datos->id)}}" title="Editar" data-id="{{ $datos->dni_doc }}">
                                                                        <i class="mdi mdi-pencil text-dark icon-md"></i>
                                                                    </a>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">{{ $datos->id }}</td>
                                                                <td><a href="{{ route('mcat.index', [$datos->id])}}" class="">{{ $datos->name }}</a></td>
                                                                <td>{{ $datos->description }}</td>
                                                                {{-- <td>{{ $datos->slug }}</td> --}}
                                                                <td><a href="{{ route('modulos.plantilla.form', [$datos->id])}}" class="">
                                                                        <i class="mdi mdi-email-open-outline ext-primary icon-md" title="Plantilla"></i>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                                                        <form style="display: inline;" method="POST" action="{{route('modulo.destroy', $datos->id)}}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="dropdown-item" type="submit" class="btn-borrar"><i class="mdi mdi-delete text-danger icon-md" title="Borrar"></i> </button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                    {!! $mcategories->appends(request()->query())->links() !!}
                                                </div>
                                            </div>
                                        </div>
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
