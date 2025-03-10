@extends('layout.home')
@section('title'){{$modulo->name}}@endsection
@section('content')
    @section('content')

    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->

        @include('layout.nav_superior')
        <!-- end encabezado -->
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            <!-- partial -->
            <div class="main-panel">
                <style> .bloque_login:hover{background:#f1f1f1;transition:all ease-out .5s;border: 1px solid #dee2e6!important}
                </style>
                <div class="content-wrapper mt-3">

                    <div class="row" id="capBusqueda">
                        <div class="col-sm-12">
                            <form>
                                <div class="form-row">
                                    <div class=" col-sm-8 col-xs-12">
                                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">
                                        <?php if (isset($_GET['s'])){ ?>
                                        <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('mcat.create', $modulo->id)}} '><i class='mdi mdi-close text-lg-left'></i></a>
                                        <?php } ?>
                                    </div>
                                    <div class=" col-sm-2 col-xs-12">
                                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                                            @foreach([15, 20, 30, 500, 100] as $p)
                                                <option value="{{ $p }}" {{ isset($_GET['pag']) && $_GET['pag'] == $p ? 'selected': '' }}>{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class=" col-sm-2 col-xs-12">
                                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                                <div class="card card-statistics text-center">

                                    <div class="card-body bloque_login">
                                        <a href="{{ route('mcat.create', $modulo->id) }}">
                                            <div class="highlight-icon bg-info  mr-3  m-auto">
                                                <i class="mdi mdi-plus text-white icon-lg"></i>
                                            </div>
                                        </a>

                                        <div class="dropdown p-0 m-0">
                                                <h4 class="mt-4"><a href="{{ route('mcat.create', $modulo->id) }}">Crear {{\Str::limit($modulo->name,20)}} </a></h4>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif

                        @foreach ($products as $p)

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('mlead.index', [$modulo->id, $p->id]) }}">{{ is_string($p->d->{$DF['_nombre']})?\Str::limit($p->d->{$DF['_nombre']},70):""}}</a>
                                        </h5>
                                        {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}
                                        <p class="card-text">

                                            <a href="{{ route('mlead.create', [$modulo->id, $p->id])}}" target="_blank" class="btn btn-link"><i class="mdi mdi-link"></i> Link de formulario</a>

                                        </p>

                                        @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                            <div class="dropdown float-right">
                                                <button class="btn btn-white dropdown-toggle pr-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                                                    <i class="mdi mdi-chevron-down h3"></i>{{-- mdi-dots-vertical --}}
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{route('mcat.edit', [$modulo->id, $p->id])}}"><i class="mdi mdi-brush"></i> Editar Evento</a>
                                                    @if($p->tipo==1)
                                                        <a class="dropdown-item" href="{{route('mcat.edit', [$modulo->id, $p->id])}}"><i class="mdi mdi-plus-circle"></i> Editar Formulario</a>
                                                    @endif
                                                    <form id="formEvento"  style="display: inline;" method="POST" action="{{ route('mcat.delete', [ $modulo->id, $p->id])}}">
                                                        {!! csrf_field() !!}
                                                        {!! method_field('DELETE') !!}
                                                        <button class="dropdown-item" type="submit" id="btnDeleteEvento"><i class="mdi mdi-delete"></i> Borrar</button>
                                                    </form>
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        {!! $products->appends(request()->query())->links() !!}
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
