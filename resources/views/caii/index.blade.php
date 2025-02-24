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
      <style>
      .bloque_login:hover{background:#f1f1f1;transition:all ease-out .5s;border: 1px solid #dee2e6!important}
      </style> 
        <div class="content-wrapper mt-3">

          <div class="row">
            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics text-center">
                
                
                <div class="card-body bloque_login">
                  <a href="{{ route('caiieventos.create') }}">
                  <div class="highlight-icon bg-info  mr-3  m-auto">
                    <i class="mdi mdi-plus text-white icon-lg"></i>
                  </div></a>

                  <h4 class="mt-4"><a href="{{ route('caiieventos.create') }}">Crear evento</a></h4>
                </div>

              </div>
            </div>
            @endif

            @foreach ($eventos_datos as $datos)

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="{{ route('estudiantes.index', array('eventos_id'=>$datos->id)) }}">{{ $datos->nombre_evento}}</a>
                  </h5>
                  {{-- <p class="card-text">Some quick.</p>--}}
                  
                  
                  <a href="{{ route('caii_pg_presencial.create', array('id'=>$datos->id, 'tipo'=>'A'))}}" target="_blank" class="btn px-2"><span class="badge mt-1 badge-dark"><i class="mdi mdi-link"></i>Presencial</span></a> 
                  <a href="{{ route('caii_pg_virtual.create', array('id'=>$datos->id, 'tipo'=>'V'))}}" target="_blank" class="btn px-2"><span class="badge mt-1 badge-danger"><i class="mdi mdi-link"></i>Virtual</span></a> 
                  <a href="{{route('caii.baja_login', array('id'=>$datos->id))}}" target="_blank" class="btn btn-link px-2"><i class="mdi mdi-link"></i>De baja</a> 
                  
                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                    <div class="dropdown float-right">
                      <button class="btn btn-white dropdown-toggle pr-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                        <i class="mdi mdi-chevron-down h3"></i>{{-- mdi-dots-vertical --}}
                      </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                          <a class="dropdown-item" href="{{route('caiieventos.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Evento</a>
                           
                          <a class="dropdown-item addActividades" data-id="{{$datos->id}}" href="{{ url('') }}/eventos_list_dias/{{$datos->id}}" data-toggle="modal" data-target="modalActividades"><i class="mdi mdi-message-processing"></i> Crear Actividades</a>
                          <a class="dropdown-item" href="{{route('caii_plantilla.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Plantillas</a>
                          <a class="dropdown-item" href="{{route('caii_form.edit', $datos->id)}}"><i class="mdi mdi-plus-circle"></i> Editar Formulario</a>
                          
                          
                          <form id="formEvento" style="display: inline;" method="POST" action="{{ route('caiieventos.destroy', $datos->id)}}">
                            {!! csrf_field() !!}
                            {!! method_field('DELETE') !!}
                            <button class="dropdown-item" type="submit" id="btnDeleteEvento"><i class="mdi mdi-delete"></i> Borrar</button>
                          </form>

                        </div>
                      
                    </div>
                  @endif


                </div>
                <div class="card-footer ">
                  <small class="text-muted">Creado: {{ \Carbon\Carbon::parse($datos->fechai_evento)->format('d/m/Y')}}</small>
                  <small class="text-muted float-right">Termina: {{ \Carbon\Carbon::parse($datos->fechaf_evento)->format('d.m.Y') }}</small>
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
                        <h5 class="modal-title" id="heTitle">Crear Actividades</h5>
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
              {{-- inicio modal --}}
              <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content"> 
                    <form  id="f_actividad" name="f_actividad" method="post"  action="{{ route('actividades_form.store') }}" class="formarchivo" >
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
                      <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
              {{-- fin modal --}}



@endsection