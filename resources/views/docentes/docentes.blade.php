@extends('layout.home')

@section('content')
<input type="hidden" id="v_url" value="{{ url('') }}">
  <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_actividad" name="f_actividad" method="post"  action="{{ route('actividades.store') }}" class="formarchivo" >
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
              <h4 class="card-title text-transform-none">Docentes <a class="btn btn-link" href="{{route('academico.index')}}">Ver Académicos</a></h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        <?php 
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('docentes.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                      </div>

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >BUSCAR</button>
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

                  {{ Form::open(array('route' => array('docentes.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                   <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $ddatos->firstItem() }} - {{ $ddatos->lastItem() }} de
                          {{ $ddatos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th aria-label="Actions: activate to sort column ascending" style="width: 2%;"></th>
                              <th style="width: 10%;">DNI</th>
                              <th style="width: 40%;">Docente</th>
                              <th style="width: 10%;">Fecha Reg.</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i = 1; ?>
                            @foreach ($ddatos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                <a href="#" class="addAct bg-light ml-3 rounded-circle addAct3" onclick="formActividad('1','12-12-2019','{{$datos->id}}', 'docente','{{ url('') }}')" title='Crear Actividad' data-toggle="modal" data-target="Modal_add_actividad">
                                      <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                    </a>
                                  @endif

                                </td>
                                <td>{{ $datos->dni_doc}}</td>
                                <td>{{ $datos->ap_paterno }} {{ $datos->ap_materno }}, {{ $datos->nombre_doc }}</td>
                                <td>{{ date('d.m.Y', strtotime($datos->created_at)) }}</td>
                                
                            </tr>
                            <?php $i++; ?>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $ddatos->appends(request()->query())->links() !!}
                        
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