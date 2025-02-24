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
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Leads / Registros
                                    <a class="btn btn-link" href="{{ route('mcat.index',[$modulo->id]) }}"><i class="mdi text-link mdi-keyboard-backspace"></i> Volver eventos</a>
                                </h4>
                                <span class="badge badge-danger">{{\Str::limit( $product->title,40)}}</span>
                            </div>

                            <div class="row" id="capBusqueda">
                                <div class="col-sm-12">
                                    <form action="{{route('mlead.index', [$modulo->id, $product->id])}}" method="get">
                                        <div class="form-row">
                                            <div class=" col-sm-8 col-xs-12">
                                                <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="{{$input['s']}}">

                                                <?php if (isset($_GET['s'])){ ?>
                                                <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href='{{route('mlead.index', [$modulo->id, $product->id])}}'><i class='mdi mdi-close text-lg-left'></i></a>
                                                <?php } ?>

                                            </div>

                                            <div class=" col-sm-2 col-xs-12">
                                                <select class="form-control" name="g" id="g" onchange="submit();">
                                                    <option selected="selected" value="">GRUPOS</option>
                                                    @foreach($grupos as $tipo)
                                                        <option value="{{$tipo->codigo}}" @if($tipo->codigo==$input["g"]) selected @endif>{{$tipo->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class=" col-sm-2 col-xs-12">

                                              <select class="form-control" name="reg" id="filter-by-date" onchange="submit();">
                                                <option selected="selected" value="">REGISTRADOS</option>
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                              </select>
                                            </div> --}}

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

                            <div id="capaEstudiantes" class="row">
                                <div class="col-12">

                                    {{ Form::open(array('route' => array('mlead.eliminarVarios', [$modulo->id, $product->id]), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                                    <div class="row">{{-- cap: opciones --}}

                                        <div class="col-xs-12  col-sm-8 text-left mb-4">
                                            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                                                <a href="{{ route('mlead.create', [$modulo->id, $product->id]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                                                    <i class="mdi mdi-plus text-white icon-md" ></i>
                                                </a>
                                            @endif

                                            @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                                                <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Importar" data-toggle="modal"><i class="mdi mdi-upload text-white icon-btn"></i></a>
                                            @endif
                                            @if(@isset($permisos['reportes']['permiso']) and  $permisos['reportes']['permiso'] == 1)
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Reporte
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <a class="dropdown-item" href="{{route('mlead.registrados', [$modulo->id, $product->id])}}">Registrados</a>
                                                        <a class="dropdown-item" href="{{request()->fullUrlWithQuery(["export"=>1])}}">Lista</a>
                                                    </div>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button id="btn_baja" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opciones
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btn_baja">
                                                        <a class="dropdown-item" href="{{route('historiaemail.index')}}" target="_blank">Historial Mensajes Enviados</a>
                                                        <a class="dropdown-item" target="_blank" href="{{route('estudiantes.envio_email')}}">Verificar estado de msg enviados</a>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)

                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Asistencia
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <a class="dropdown-item" href="{{ route('asistencia.create',['eventos_id'=>session('eventos_id')]) }}">Registrar Ingreso y Salida</a>
                                                        <div role="separator" class="dropdown-divider "></div>
                                                        <a class="dropdown-item" href="{{ route('asistencia.index', ['eventos_id' => session('eventos_id')]) }}" target="_blank">Listado de Asistencias</a>
                                                        <div role="separator" class="dropdown-divider "></div>
                                                        <a class="dropdown-item" href="{{route('reportes.a_general')}}">Reporte General</a>
                                                    </div>
                                                </div>

                                                <a href="#" @if($vencido != 1) id="Recordatorio" @endif class="btn btn-sm @if($vencido != 1) btn-dark @else btn-secondary @endif" data-id='{{session('eventos_id')}}' data-mod='eventos'><i class="mdi mdi-email icon-md"></i>Recordatorio</a>
                                            @endif

                                            @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                                <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                                            @endif

                                        </div> {{-- end derecha --}}
                                        <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $inscritos->firstItem() }} - {{ $inscritos->lastItem() }} de
                          {{ $inscritos->total() }}
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
                                                        {{-- <th style="width: 2%;">#</th> --}}
                                                        @foreach ($campos as $campo)
                                                            @if($campo->visible&&!in_array($campo->m_field_id, [12,14, 16, 17, 18, 19,20]))
                                                            <th>{{$campo->title}}</th>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach ($inscritos as $datos)
                                                        <tr >
                                                            <td><input type="checkbox" class="form btn-delete" name="students[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                                            <td><a href="{{ route('mlead.edit', [$modulo->id, $product->id, $datos->id])}}" target="_blank" class="btn btn-link"><i class="mdi mdi-pencil"></i></a>
                                                            </td>
                                                            @foreach ($campos as $campo)
                                                                @if($campo->visible&&!in_array($campo->m_field_id, [12,14, 16, 17, 18, 19,20]))
                                                                <td>@php($val=$datos->formatValue($campo, ["groups"=>$grupos, "doctypes"=>$doctypes]))
                                                                    @if(false)
                                                                    @else
                                                                        @if($campo->m_field_id==7)
                                                                            {{ strlen($val) == 8 ? \Carbon\Carbon::createFromFormat("Ymd", $val)->format('d/m/Y') :$val }}
                                                                        @elseif($campo->m_field_id==15)
                                                                            <a href="{{ asset("images/m_{$campo->m_category_id}/{$val}") }}">{{ $val }}</a>
                                                                        @else
                                                                            {{ $val }}
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>


                                                {!! $inscritos->appends(request()->query())->links() !!}
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
    {{-- form importar --}}
    <div class="modal fade ass" id="Modal_estudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="" class="formarchivo" enctype="multipart/form-data" >
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Importar Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        {{-- <div class="form-group row">
                          <h4 class="col-md-3 mt-1">Export</h4>
                          <div class="col-md-9">
                            <a href="{{ route('leads.export') }}" class="btn btn-secondary btn-block">Exportar</a>
                            <span class="help-block with-errors"></span>
                          </div>
                        </div> --}}
                        @if($vencido != 1)
                            <div class="form-group row">
                                {{-- <h4 class="col-md-3 mt-1">Import</h4> --}}
                                <div class="col-md-12">
                                    <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                                        <input type="file" name="file" id="archivo" class="dropify" required>
                                        <button type="button" class="dropify-clear">Quitar</button>

                                        <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>

                                    <span class="help-block with-errors"></span>

                                </div>
                            </div>
                        @else
                            <p>Evento finalizado.</p>
                        @endif
                        <div style="display:none;" id="cargador_excel" class="content-wrapper p-0" align="center">  {{-- msg cargando --}}
                            <div class="card bg-white" style="background:#f3f3f3 !important;" >
                                <div class="">
                                    <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                                    <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                                </div>
                            </div>
                        </div>{{-- msg cargando --}}



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-dark" id="btnImport1">Importar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- form importar --}}

@endsection
