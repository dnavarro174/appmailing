@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      <div class="main-panel">
        
        <div class="content-wrapper p-0 mt-3">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title text-transform-none">Mailing</h4>
              

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('mailing..index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

                      </div>
                      
                      {{-- <div class=" col-sm-2 col-xs-12">
                        <select class="form-control" name="st" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="">TIPO</option>
                          @foreach($tipos as $tipo)
                          @if($tipo->id != 4)
                          <option value="@if($tipo->id == 3)@else{{$tipo->id}}@endif">{{$tipo->nombre }}</option>
                          @endif
                          @endforeach
                        </select>
                      </div>
                      <div class=" col-sm-2 col-xs-12">

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

                {{--<div class="col-xs-12 col-sm-6 col-lg-6 form-inline">

                     {{ Form::open(array('route' => array('estudiantes.enviar_email'), 'method' => 'GET', 'role' => 'form', 'id' => 'search','style'=>'display:flex')) }}

                    <div class="form-group">
                      <label for="prog" class="mr-2">Cod. Programación: </label>
                        <select class="form-control border-primary text-uppercase valid" id="prog" name="prog" >
                          <option value="">SELECCIONE</option>
                          @foreach ($programacion_datos as $prog)
                            <option value="{{$prog->codigo}}"
                              @if($prog->codigo === $proga)
                              selected
                              @endif
                              >{{$prog->codigo}}</option>
                          @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mx-sm-3">
                        <select class="form-control border-primary text-uppercase valid" id="depa" name="depa" >
                          <option value="">DEPARTAMENTO</option>
                          @foreach ($departamentos_datos as $depa)
                          <option value="{{$depa->ubigeo_id}}"
                            @if($depa->ubigeo_id === $depas)
                              selected
                            @endif
                            >{{$depa->nombre}}</option>
                          @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary"  id="filtrar">Filtrar</button>
                      @if($proga or $depas) 
                        <div class="form-group mx-sm-3">
                        <a href="{{ route('estudiantes.enviar_email')}}">Borrar busqueda</a>
                      </div>
                      @endif
                    </div>
    
                    {{ Form::close() }}  
                  </div>--}}

                  <div class="col-xs-12 col-sm-12 text-right mb-0">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $estudiantes_datos->firstItem() }} - {{ $estudiantes_datos->lastItem() }} de
                        {{ $estudiantes_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}

                
                  
                {{-- end buscador --}}


              <div class="row">
                
                {{ Form::open(array('route' => array('estudiantes.EmailEstudiantes'), 'method' => 'POST', 'role' => 'form', 'id' => 'form_html','style'=>'display:flex')) }}
                <div class="col-xs-12 col-sm-12 col-lg-12 " style="float: left;">
                  
                  <div class="col-xs-12 col-sm-3 col-md-2 row" style="float: left;">
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                      <h4 class="card-title mt-4">Seleccionar plantilla</h4>
                      <div class="bloque_plantilla border  mb-4 pt-2" style="height: 300px;overflow-x: auto;overflow-y: auto; ">
                        <ul class="">
                          @foreach ($plantilla_datos as $datos)
                          <li>
                            <a href="#1" id="{{ $datos->id }}">
                              <input type="radio" class="form btn-html" name="checkHTML" value="{{ $datos->id }}" data-xid="{{ $datos->id }}" >
                                <span class="openHTML" data-id="{{ $datos->id }}">{{ $datos->nombre }}
                                  <em class="color-gris text-small" style="font-size: 10px;display: block;">{{ $datos->flujo_ejecucion }}</em></span>
                            </a>
                          </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="col-sm- form-group">
                          <label class=" col-form-label">
                              <input type="checkbox" id="chek_enviarTodos" name="chek_enviarTodos" value="1" ><span id="spanConceder"> Enviar a toda la base de datos</span>
                          </label>
                        </div>
                        <button type="submit" class="btn btn-primary" name="enviarCorreos" id="enviarCorreos">Enviar Correos</button>
                    </div>
                  </div> {{-- end columna_3 --}}

                  <div class="col-xs-12 col-sm-9 col-md-10 tabla_enviar_email" style="float: left;overflow-x: auto;overflow-y: auto;">

                    <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                      <div class="row">
                        <div class="col-sm-12 table-responsive-lg">
                          <table id="order-listing" class="table ">
                            <thead>
                              <tr role="row">
                                <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                                
                                <th style="width: 2%;">#</th>
                                <th style="width: 3%;">Accedio</th>
                                {{-- <th style="width: 3%;">Track</th> --}}
                                <th style="width: 30%;">Apellidos_y_Nombres</th>
                                <th style="width: 8%;">DNI</th>
                                <th style="width: 10%;">Email</th>
                                <th style="width: 10%;">Cargo</th>
                                <th style="width: 10%;">Distrito</th>
                                <th style="width: 5%;">Celular</th>
                                <th style="width: 5%;">Telef.</th>
                                <th style="width: 5%;">Telef.Labor</th>
                                <th style="width: 10%;">Email Labor</th>
                                <th style="width: 5%;">CodProg</th>
                                <th style="width: 2;">FechReg</th>
                                {{-- <th class="sorting_desc" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                                
                              </tr>
                            </thead>
                            <tbody>
                              
                              @foreach ($estudiantes_datos as $datos)
                              <tr role="row" class="odd" <?php if($datos->accedio == "SI") echo "style='background:#d5ebf3;'"?>>
                                
                                  <td><input type="checkbox" class="form btn-delete _check" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                
                                  <td align="center">{{ $datos->id }}</td>
                                  <td>{{ $datos->accedio }}</td>
                                  {{-- <td>{{ $datos->track }}</td> --}}
                                  <td>{{ $datos->ap_paterno .' '. $datos->ap_materno.', '. $datos->nombres }}</td>
                                  <td>{{ $datos->dni_doc }}</td>
                                  <td>{{ $datos->email }}</td>
                                  <td>{{ $datos->cargo }}</td>
                                  <td>
                                    @if(is_null($datos->ubigeo_ubigeo_id))
                                      {{ $datos->ubigeo_ubigeo_id }} -- 
                                    @else
                                      {{ $datos->departamento->nombre }} {{-- $datos->ubigeo_ubigeo_id --}}
                                    @endif
                                  </td> {{-- departamento->nombre    SUBSTRING('MySQL con Clase',7)--}}
                                  <td>{{ $datos->celular }}</td>
                                  <td>{{ $datos->telefono }}</td>
                                  <td>{{ $datos->telefono_labor }}</td>
                                  <td>{{ $datos->email_labor }}</td>
                                  <td>{{ $datos->codigo }}</td>
                                  {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                  {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                                  {{-- <td>{{ $datos->created_at->format('d/m/Y') }}</td> --}}
                                  <td>{{ date('d/m/Y', strtotime($datos->created_at)) }}</td>
                                  
                              </tr>
                              @endforeach
                            </tbody>
                          </table>

                          {!! $estudiantes_datos->appends(request()->query())->links() !!}

                        </div>
                      </div>
                    </div>
                  </div> {{-- end tabla_enviar_email --}}



                </div>
              

                {{ Form::close() }} {{-- end close form --}}


                  {{-- modal openHTML --}}
                  <div class="modal fade ass" id="openHTML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-800" role="document">
                      <div class="modal-content">
                        
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Plantilla HTML</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span> 
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="row" id="plantillaHTML">
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        


                      </div>
                    </div>
                  </div>
                  {{-- modal openHTML --}}
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
