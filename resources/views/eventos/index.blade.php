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
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('eventos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                      </div>
                      <div class=" col-sm-2 col-xs-12">
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

          <div class="row">
            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics text-center">
                
                <div class="card-body bloque_login">
                  {{-- <a href="{{ route('eventos.create') }}"> --}}
                  <div class="highlight-icon bg-info  mr-3  m-auto">
                    <i class="mdi mdi-plus text-white icon-lg"></i>
                  </div>{{-- </a> --}}

                    <div class="dropdown p-0 m-0">
                      <button class="btn btn-white dropdown-toggle p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                        {{--  --}}
                        <h4 class="mt-4"><a href="{{ route('eventos.create') }}">Crear evento <i class="mdi mdi-chevron-down h3"></i></a></h4>
                      </button>
                      
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="{{route('eventos.create', ['tipo'=>1])}}"><i class="mdi mdi-brush"></i> Evento Presencial</a>
                          <a class="dropdown-item" href="{{route('eventos.create', ['tipo'=>2])}}"><i class="mdi mdi-brush"></i> Evento Mailing</a>{{-- Simple - Virtual --}}
                        </div>
                       
                    </div>
                </div>

              </div>
            </div>
            @endif

            @foreach ($eventos_datos as $datos)

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="{{ route('leads.index', array('eventos_id'=>$datos->id)) }}">{{ \Illuminate\Support\Str::limit($datos->nombre_evento,70)}}</a> 
                  </h5>
                  {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}
                  <p class="card-text">
                  @if($datos->tipo==2)<span class="badge badge-pill badge-dark">Mailing</span>@else 
                    <a href="{{ route('ev.create', array('id'=>$datos->id))}}" target="_blank" class="btn btn-link"><i class="mdi mdi-link"></i> Inscripción</a> 
                  @endif
                  </p>

                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                    <div class="dropdown float-right">
                      <button class="btn btn-white dropdown-toggle pr-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                        <i class="mdi mdi-chevron-down h3"></i>{{-- mdi-dots-vertical --}}
                      </button>
                      
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="{{route('eventos.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Evento</a>
                          @if($datos->tipo==1)
                            <a class="dropdown-item" href="{{route('eventos_form.edit', $datos->id)}}"><i class="mdi mdi-plus-circle"></i> Editar Formulario</a>
                          @endif
                          <form id="formEvento" style="display: inline;" method="POST" action="{{ route('eventos.destroy', $datos->id)}}">
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
            {!! $eventos_datos->appends(request()->query())->links() !!}
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



  {{-- Detalle programación --}}

       {{-- Detalle programación --}}

              <div class="modal modalAct fade" id="modalAct" tabindex="-1" role="dialog" aria-labelledby="heTitle" aria-hidden="true" id="modalActividades">
                <div class="modal-dialog modal-sm" role="document">{{-- modal-lg --}}
                  <form class="" id="detalleProgramacion" action="{{ route('caiieventos.enviar') }}" method="post" >
                        {!! csrf_field() !!}
                    <div class="modal-content">
                      
                      <div class="modal-header">
                        <h5 class="modal-title" id="heTitle">Crear Actividades </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span> 
                        </button>
                      </div>
                      <div class="modal-body pt-0" style="max-height: 280px; overflow: scroll;">
                       


                      </div>
                      <div class="modal-footer">
                        <a href="{{route('actividades.index')}}" class="btn btn-link" title='Crear Actividad'>
                          Ver actividades
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        {{-- <button type="submit" class="btn btn-primary" id="enviar_det_programacion">Asignar CódProgramacìón</button> --}}
                      </div>
                      
                    </div>
                  </form>
                </div>
              </div>
              {{--  --}}
              <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content"> 
                    <form  id="f_actividad" name="f_actividad" method="post"  action="{{ route('actividades.store') }}" class="formarchivo" >
                        {!! csrf_field() !!}
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Creación de la Actividad</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span> 
                      </button>
                    </div>
                    <div class="modal-body pt-0 form-act">


                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>{{-- btnImport1 --}}
                    </div>
                    </form>
                  </div>
                </div>
              </div>
              {{-- fin modal --}}

@endsection
