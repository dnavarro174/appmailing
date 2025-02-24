@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layout.menutop_setting_panel')
      <!-- end menu_user -->
      
      <!-- partial -->
      <div class="main-panel">
        
        <div class="content-wrapper ">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Newsletters</h4>
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-12 form-inline">
                  
                  <form action="{{ route('newsletter.index') }}" method="GET" class="form-inline pull-right">
                    <div class="form-group mb-2">
                      <input type="text" class="form-control border-primary text-uppercase" id="s" name="s" placeholder="Buscar" value="">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mb-2" id="buscar" {{-- onclick="buscarusuario()" --}} >Buscar</button>

                    <?php
                     if (isset($_GET['s'])){ ?>
                     <a class="ml-2 small" href=' {{route('newsletter.index')}} '>Borrar busqueda</a>
                    <?php } ?>
                  </form>
                </div>
              </div>

              
              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('newsletter.eliminar_newsletter'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec">Borrar Seleccionados</button>
                      @endif
                      {{--  --}}
                      
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                      <a href="{{ route('estudiantes.create') }}" class="btn btn-outline-success">{{-- <i class="mdi mdi-account-multiple text-success icon-md" title=""></i> --}} Nuevo</a>
                      @endif

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $newsletter_datos->firstItem() }} - {{ $newsletter_datos->lastItem() }} de
                        {{ $newsletter_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}
                    
                  </div> {{-- end row --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead>
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 3%;"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 3%;">#</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 8%;">DNI</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 60%;">Apellidos_y_Nombres</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 15%;">Email</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 5%;">Fecha_Registro</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" style="width: 5%;">Fecha_Actualización</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($newsletter_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->dni_doc }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.edit',[$datos->id, 'new'])}}" class="">{{-- ['id' => 1] --}}
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('estudiantes.show',[$datos->id, 'new'])}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif

                                </td>
                                <td>{{ $datos->id }}</td>
                                <td>{{ $datos->dni_doc }} </td> 
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ $datos->email }}</td>
                                <td>{{ $datos->created_at->format('d/m/Y') }}</td>
                                <td>{{ $datos->updated_at->format('d/m/Y H:m:s') }}</td>
                                
                            </tr>
                            @endforeach
                          </tbody>
                        </table>

                        {!! $newsletter_datos->appends(request()->query())->links() !!}

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