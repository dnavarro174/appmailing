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
              <h4 class="card-title">
                Eventos CAII
                <a href="{{ route('estudiantes.index') }}" title="Regresar" class="btn btn-link ">Ver Participantes </a>
                {{-- <a href="{{ route('caii.login') }}" target="_blank" class="btn btn-link ">Link Invitados </a> --}}
              </h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-8 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        <?php 
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('caiieventos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                      </div>

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >Buscar</button>
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

                  {{ Form::open(array('route' => array('caiieventos.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        
                        <a href="{{ route('caiieventos.create') }}" title="Nuevo" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a>

                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $eventos_datos->firstItem() }} - {{ $eventos_datos->lastItem() }} de
                          {{ $eventos_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  

                  <div id="order-listing_wrapper" >
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 460px; padding-bottom: 49px;">{{-- table-responsive-lg --}}
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 3%;"></th>
                              <th style="width: 2%;">#</th>
                              <th style="width: 25%;">Nombre Evento</th>
                              {{-- <th style="width: 8%;">Fecha</th> --}}
                              <th style="width: 10%;">Invitados</th>
                              <th style="width: 10%;">Hora</th>
                              <th style="width: 10%;">Lugar</th>
                              <th style="width: 10%;">Vacantes</th>
                              <th style="width: 10%;">Inscritos_pre</th>
                              {{-- <th style="width: 10%;">Auto.Conf</th> --}}
                              <th style="width: 5%;">Activo</th>
                              {{-- <th style="width: 5%;">Grupo</th> --}}
                              <th style="width: 5%;">Fecha_Inicio</th>
                              <th style="width: 5%;">Fecha_Final</th>
                              <th style="width: 5%;">FechaFin_Pre-Inscripción</th>
                              <th style="width: 5%;">FechaFin_Pre-Invitación</th>
                              <th style="width: 5%;">Confirmación Email / Cel.</th>
                              <th style="width: 5%;">FechaReg</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($eventos_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                      
                                      <a href="{{ route('caiieventos.edit',$datos->id) }}" class="">
                                        <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                      </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('caiieventos.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                  
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td><a href="{{ route('caii_pg.create', array('id'=>$datos->id))}}" title="Compartir evento" target="_blank" >{{ $datos->nombre_evento }}</a></td>
                                <td><a href="{{ route('caii.login', array('id'=>encrypt($datos->id)))}}" title="Compartir evento" target="_blank" >{{ 'Login' }}</a></td>
                                {{-- <td>{{ $datos->fecha_texto }}</td> --}}
                                <td>{{ $datos->hora }}</td>
                                <td>{{ $datos->lugar }}</td>
                                <td class="text-center">{{ $datos->vacantes }}</td>
                                <td class="text-center">{{ $datos->inscritos_pre }}</span></td>
                                {{-- <td>{{ $datos->auto_conf }}</td> --}}
                                <td>
                                  
                                  <span class="badge @if($datos->activo === 1)badge-primary @elseif($datos->activo === 2)badge-secondary @elseif($datos->activo === 3)badge-danger @else($datos->activo === 4)badge-dark @endif ">
                                    @if($datos->activo === 1) SI @else NO @endif
                                  </span>
                                  
                                </td>
                                {{-- <td>{{ $datos->grupo }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($datos->fechai_evento)->format('d.m.Y') }}</td>{{-- ->format('d.m.Y') --}}
                                {{-- {!! \Carbon\Carbon::parse($datos->fecha_entrega)->format('d-m-Y') !!} --}}
                                <td>{{ \Carbon\Carbon::parse($datos->fechaf_evento)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->fechaf_pre_evento)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->fechaf_insc_evento)->format('d.m.Y') }}</td>
                                <td>
                                  <i class="mdi mdi-email @if($datos->confirm_email === 1) text-warning @else text-secondary @endif icon-md" title="Email: @if($datos->confirm_email === 1) SI @else NO @endif"></i>
                                  <i class="mdi mdi-message  @if($datos->confirm_msg === 1) text-success @else text-secondary @endif  icon-md" title="Msg: @if($datos->confirm_msg === 1) SI @else NO @endif"></i>
                                </td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}

                                <td>{{ $datos->created_at->format('d.m.Y') }}</td>
                                
                                {{-- <td>
                                  @if($datos->estado === "1")
                                    <span class="badge badge-success">Activo</span>
                                  @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                  @endif
                                </td> --}}
                                
                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $eventos_datos->appends(request()->query())->links() !!}
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