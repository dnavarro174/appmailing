@extends('layout.home')

@section('content')
<input type="hidden" id="v_url" value="{{ url('') }}">
  <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_actividad" name="f_actividad" method="post"  action="{{ route('actividades_form.store') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edición de la Actividad</h5>
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
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
     
      <!-- partial -->
      <div class="main-panel">
        
        <div class="content-wrapper p-0 mt-3">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title text-transform-none">Listado de Actividades - <a href="{{route('caii.index')}}">Ver eventos</a></h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        <?php 
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{ URL::previous() }} '><i class='mdi mdi-close text-lg-left'></i></a>
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

                  {{ Form::open(array('route' => array('actividades.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                   <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        
                        {{-- <a href="{{ route('actividades.create') }}" title="Nuevo" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a> --}}

                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $actividades_datos->firstItem() }} - {{ $actividades_datos->lastItem() }} de
                          {{ $actividades_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 1%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 1%;"></th>
                              <th style="width: 10%;">Título</th>
                              <th style="width: 22%;">Subtítulo</th>
                              <th style="width: 15%;">Ubicación</th>
                              <th style="width: 6%;">Fecha Inicio</th>
                              <th style="width: 5%;">Fecha Fin</th>
                              <th class="text-center" style="width: 5%;">Vacantes_Pre</th>
                              <th class="text-center" style="width: 5%;">Inscritos_Pre</th>
                              <th class="text-center" style="width: 5%;">Vacantes_Vir</th>
                              <th class="text-center" style="width: 5%;">Inscritos_Vir</th>
                              <th style="width: 5%;">Fecha Reg.</th>
                              {{-- <th class="sorting_desc" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                              
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i = 1; ?>
                            @foreach ($actividades_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <?php
                                  $arrf1 = explode(" ", $datos->fecha_desde);
                                  $arrf2 = explode("-", $arrf1[0]);
                                  $ffec = $arrf2[2]."-".$arrf2[1]."-".$arrf2[0];
                                  ?>
                                <a href="#" class="addAct bg-light ml-3 addAct3" onclick="formActividad('{{$datos->eventos_id}}','{{$ffec}}','{{$datos->id}}', '{{$i}}','{{ url('') }}')" data-toggle="modal" data-target="Modal_add_actividad">
                                      <i class="mdi mdi-pencil text-dark icon-md" title="Editar Actividad"></i>
                                    </a>
                                  @endif

                                </td>
                                <td>{{ $datos->titulo}}</td>
                                <td>{{ $datos->subtitulo}}</td>
                                <td>{{ $datos->ubicacion}}</td>
                                <td>{{ $datos->hora_inicio }}</td>
                                <td>{{ $datos->hora_final }}</td>
                                <td class="text-center">{{ $datos->vacantes }}</td>
                                <td class="text-center">{{ $datos->inscritos }}</td>
                                <td class="text-center">{{ $datos->vacantes_v }}</td>
                                <td class="text-center">{{ $datos->inscritos_v }}</td>
                                
                                <td>{{ date('d.m.Y H:i:s', strtotime($datos->created_at)) }}</td>
                                
                            </tr>
                            <?php $i++; ?>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $actividades_datos->appends(request()->query())->links() !!}
                        
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