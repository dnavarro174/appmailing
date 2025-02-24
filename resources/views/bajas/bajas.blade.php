@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        
        <div class="content-wrapper mt-2">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Bajas <a href="{{route('estudiantes.index')}}" class="btn btn-link">Volver Participantes</a></h4>
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        {{-- @if($text_search){{$text_search}} @endif --}}
                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('bajas.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
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
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >BUSCAR</button>
                        
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  
                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $f_datos->firstItem() }} - {{ $f_datos->lastItem() }} de
                        {{ $f_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}
                    
                  </div> {{-- end row --}}


                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 460px; padding-bottom: 49px;">
                      {{-- <div class="col-sm-12 table-responsive-lg"> --}}
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              {{-- <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th> --}}
                              <th style="width: 3%;"></th>
                              <th style="width: 8%;">DNI</th>
                              <th style="width: 40%;">Apellidos_y_Nombres</th>
                              <th style="width: 15%;">Evento</th>
                              <th style="width: 15%;">Mensaje</th>
                              {{-- <th style="width: 5%;">Modalidad</th> --}}
                              <th style="width: 5%;">Tipo</th>
                              <th style="width: 10%;">Cargo</th>
                              <th style="width: 10%;">Entidad</th>
                              <th style="width: 12%;">Profesión</th>
                              <th style="width: 10%;">Grupo</th>
                              <th style="width: 10%;">País</th>
                              <th style="width: 10%;">Departamento</th>
                              <th style="width: 5%;">Celular</th>
                              <th style="width: 5%;">Email</th>
                              <th style="width: 5%;">FechaReg</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($f_datos as $datos)
                            <tr role="row" class="odd" <?php if($datos->track == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->track == "NO") echo "style='background:#f7d3d3;'"?>>
                              {{-- <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td> --}}
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 2)
                                  <a href="{{ route('bajas.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 2)
                                  <a href="{{ route('bajas.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                              
                                <td>{{ $datos->dni_doc }}</td>
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->evento->nombre_evento,15) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->msg, 50) }}</td>
                                
                                <td><span class="badge @if($datos->tipo_id === 1)badge-dark @elseif($datos->tipo_id === 2)badge-success @elseif($datos->tipo_id === 3)badge-danger @else badge-primary @endif ">{{ $datos->tipo_baja->nombre,'' }}</span></td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->cargo, 25) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->organizacion, 25) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($datos->profesion, 25) }}</td>
                                <td>{{ $datos->grupo }}</td>
                                <td>{{ $datos->pais }}</td>
                                <td>{{ $datos->region }}</td>
                                
                                <td>{{ $datos->codigo_cel.$datos->celular }}</td>
                                <td>{{ $datos->email }}</td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                                <td>{{ $datos->created_at }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>

                        {!! $f_datos->appends(request()->query())->links() !!}
                        
                      </div>
                    </div>
                  </div>

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