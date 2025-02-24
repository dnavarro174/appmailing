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
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('grupo-estudio-investigacion.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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
                  <a href="{{ route('grupo-estudio-investigacion.create') }}">
                  <div class="highlight-icon bg-dark  mr-3  m-auto">
                    <i class="mdi mdi-plus text-white icon-lg"></i>
                  </div></a>
                  <h4 class="mt-4"><a class="text-dark" href="{{ route('grupo-estudio-investigacion.create') }}">Crear Formulario</a></h4>
                </div>
              </div>
            </div>
            @endif

            @foreach ($eventos_datos as $datos)

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <h5 class="card-title">
                    <a class="text-dark" href="{{ route('leads.index', array('eventos_id'=>$datos->id,'tipo'=>5)) }}">{{ $datos->nombre_evento}}</a>
                  </h5>
                  {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}

                  <a href="{{ route('forme_link.create', array('id'=>$datos->id))}}" target="_blank" class="btn btn-link"><i class="mdi mdi-link"></i> Link para inscripción</a> 
                  {{-- <a href="{{route('caii.baja_login', array('id'=>$datos->id))}}" target="_blank" class="btn btn-link"><i class="mdi mdi-link"></i> De baja</a>  --}}

                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                    <div class="dropdown float-right">
                      <button class="btn btn-white dropdown-toggle pr-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                        <i class="mdi mdi-chevron-down h3"></i>{{-- mdi-dots-vertical --}}
                      </button>
                      
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="{{route('grupo-estudio-investigacion.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Evento</a>
                          <a class="dropdown-item" href="{{route('grupo-estudio-investigacion_form.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Formulario</a>
                          <form id="formEvento" style="display: inline;" method="POST" action="{{ route('grupo-estudio-investigacion.destroy', $datos->id)}}">
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



@endsection
