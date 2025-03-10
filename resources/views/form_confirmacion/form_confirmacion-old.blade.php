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
              <h4 class="card-title">Pre-Inscritos <a href="{{route('estudiantes.index')}}" class="btn btn-link">Ver Participantes</a></h4>
              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-4 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="{{ app('request')->input('s')?app('request')->input('s'):'' }}">



                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('form_confirmacion.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <p>
                          <a href="#" id="activarBusqueda">Realizar Sorteo</a>
                        </p>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="mod" id="mod">
                          <option value="">MODALIDAD</option>
                          @foreach($modalidades as $mod)
                          <option value="{{$mod->modalidad_id}}"
                            {{ app('request')->input('mod')==$mod->modalidad_id?'selected':'' }}
                            >{{$mod->modalidad }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="st" id="st" >
                          <option value="">TIPO</option>
                          @foreach($tipos as $tipo)
                          @if(in_array($tipo->id, [1, 2, 3]))
                          <option value="@if($tipo->id == 3)@else{{$tipo->id}}@endif"
                            {{ app('request')->input('st')==$tipo->id ? 'selected':'' }}
                            >{{$tipo->nombre }}</option>
                          @endif
                          @endforeach
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="gr" id="gr" >
                          <option value="">GRUPOS</option>
                            @foreach($grupos as $tipo)
                            <option {{ app('request')->input('gr')==$tipo->codigo ? 'selected':'' }}
                            value="{{$tipo->codigo}}">{{$tipo->nombre}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="apro" id="apro" >
                          <option value="">APRO / NO APRO</option>
                            <option {{ app('request')->input('apro')=='SI' ? 'selected':'' }} value="SI">Aprobado</option>
                            <option {{ app('request')->input('apro')=='NO' ? 'selected':'' }} value="NO">No Aprobados</option>
                            <option {{ app('request')->input('apro')=='N' ? 'selected':'' }} value="N">No revisado</option>
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select class="form-control" name="region" id="region" >
                          <option value="">REGION</option>
                            <option {{ app('request')->input('region')==1 ? 'selected':'' }} value="1">Lima</option>
                            <option {{ app('request')->input('region')==2 ? 'selected':'' }} value="2">Regiones</option>
                            <option {{ app('request')->input('region')==3 ? 'selected':'' }} value="3">Internacionales</option>
                        </select>
                      </div>

                      <div class="col-sm-1 col-xs-12">
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

                      <div class=" col-sm-1 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >BUSCAR</button>
                      </div>

                    </div>
                  </form>
                </div>
                <div id="capa_Busqueda" class="row px-2 col-sm-12 d-none">{{-- col-sm-12 py-2 px-0 flex-fill --}}

                    <div class="col-xs-12 col-md-4 pl-3">
                      <div class="form-group row">
                        <label class="col-sm-6 col-form-label d-flex">
                          <input type="number" class="form-control w-30 flex" placeholder="NÚMERO" id="randon" name="randon" value="">

                        </label>{{-- ALEATORIO --}}
                        <div class="col-sm-3">
                          <div class="form-radio">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="randon" id="randon1" value="1" checked="">
                              Randon
                            <i class="input-helper"></i></label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-radio">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="randon" id="randon2" value="2">
                              Primeros
                            <i class="input-helper"></i></label>
                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-2">
                      <div class="col-sm- form-group">
                          <label class="d-block col-form-label mt-2">
                              <input type="checkbox" id="chkNo" name="inv" value="1"><span id="spanConceder">
                                  Rechazar Pre-Inscripciones
                              </span>
                          </label>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-1">

                        <button type="button" class="form-control btn btn-dark  mt-2" id="btnSorteo" style="font-size: 11px;">
                          SORTEAR <i class="mdi mdi-help"></i>
                        </button>

                    </div>


                </div>
              </div>

              @if(Session::has('si'))     {{-- dd(Session::get('test')); --}}
              <p class="alert alert-success" style="background: #f9f6a3 !important;">
                <strong>Inscripciones Aprobadas:</strong>
                {!! Session::get('si') !!}
              </p>
              @endif
              @if(Session::has('no'))
              <p class="alert alert-danger">
                <strong>Inscripciones Rechazadas:</strong>
                {!! Session::get('no') !!}
              </p>
              @endif
              @if(Session::has('error'))
                @if(Session::get('error') != "")
                  <p class="alert alert-danger">
                    <strong>No enviados:</strong>
                    {!! Session::get('error') !!}
                  </p>
                @endif
              @endif


              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('form_confirmacion.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <div class="row">

                    <div class="col-xs-12  col-sm-8 text-left mb-4">


                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                      @endif
                      {{--  --}}


                      <button  type="submit" class="btn btn-secondary" name="enviar_confirmacion" id="enviar_confirmacion">Enviar Confirmación</button>
                      {{-- <div class="col-sm-8" style="float: left;">
                              <div style="padding: 10px;">Selec. <strong>SI:</strong> <span id="seleccionados">1</span> Selec. <strong>NO:</strong> <span id="seleccionadosNO">1</span></div>
                            </div> --}}

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $f_datos->firstItem() }} - {{ $f_datos->lastItem() }} de
                        {{ $total = $f_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}

                  </div> {{-- end row --}}


                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="table-responsive fixed-height" >
                      {{-- <div class="col-sm-12 table-responsive-lg">  style="height: 460px; padding-bottom: 49px;" --}}
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              {{-- <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th> --}}
                              <th style="width: 3%;"></th>
                              <th style="width: 3%;">#</th>
                              <th style="width: 3%;">Confirmación</th>
                              <th style="width: 8%;">DNI</th>
                              <th style="width: 40%;">Apellidos_y_Nombres</th>
                              <th style="width: 15%;">Grupo</th>
                              <th style="width: 10%;">Modalidad</th>
                              <th style="width: 10%;">Tipo</th>
                              <th style="width: 15%;">Email</th>
                              <th style="width: 10%;">Cargo</th>
                              <th style="width: 10%;">Entidad</th>
                              <th style="width: 12%;">Profesión</th>
                              <th style="width: 10%;">Celular</th>
                              <th style="width: 10%;">País</th>
                              <th style="width: 10%;">Departamento</th>
                              <th style="width: 10%;">Registrado</th>
                              <th style="width: 5%;">FechaReg</th>

                            </tr>
                          </thead>
                          <tbody>

                            @foreach ($f_datos as $datos)
                            <?php
                                $v="";
                                if($datos->dtrack == "SI") $v='#fdfcbf';
                                if($datos->dtrack == "NO") $v='#f7d3d3';
                                if($datos->daccedio == "SI"){$v='#A0E8C5';}
                              ?>
                            <tr role="row" class="odd" style="background:<?=$v;?>">
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('form_confirmacion.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 2)
                                  <a href="{{ route('form_confirmacion.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>

                                  @endif

                                </td>
                                <td>{{ $datos->id }}</td>
                                <td>
                                    <input type="checkbox" class="aemail"
                                      <?php if($datos->track == "SI" or $datos->track == "NO") echo "disabled"?>
                                      name="selection_si[]" id="confi{{ $datos->id }}" data-id="{{ $datos->id }}" value="{{ $datos->id }}">
                                    Si
                                    <input type="checkbox" class="NOemail"
                                      <?php if($datos->track == "SI" or $datos->track == "NO") echo "disabled"?>
                                      name="selection_no[]" id="confi_no{{ $datos->id }}" data-id="{{ $datos->id }}" value="{{ $datos->id }}">
                                    No
                                </td>
                                <td>{{ $datos->dni_doc }}</td>
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ $datos->dgrupo }}</td>
                                <td>
                                  <span class="badge mt-1 @if(optional($datos->Modalidad)->modalidad_id==1)badge-dark @endif @if(optional($datos->Modalidad)->modalidad_id==2)badge-danger @endif">{{ \Illuminate\Support\Str::limit($datos->Modalidad->modalidad,7,'') }} </span>
                                </td>
                                <td>
                                  <span class="badge @if($datos->estudiantes_tipo_id === 1)badge-primary @elseif($datos->estudiantes_tipo_id === 2)badge-success @elseif($datos->estudiantes_tipo_id === 3)badge-danger @else badge-dark @endif ">
                                    {{ optional($datos->tipo)->nombre,'' }}</span>
                                </td>
                                <td>{{ $datos->email }}</td>
                                <td>{{ $datos->cargo }}</td>
                                <td>{{ $datos->organizacion }}</td>
                                <td>{{ $datos->profesion }}</td>
                                <td>{{ $datos->codigo_cel.$datos->celular }}</td>
                                <td>{{ $datos->pais }}</td>
                                <td>{{ $datos->region }}</td>
                                <td class="text-center">{{ $datos->accedio }}</td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                                <td>{{ $datos->created_at->format('d/m/Y') }}</td>

                            </tr>
                            @endforeach
                          </tbody>
                        </table>

                        {!! $f_datos->appends(request()->query())->links() !!}

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
<input type="hidden" id="totales" value="{{$total??0}}">

@endsection
@section('scripts')
<script>
var mod = $('#mod');
var tipo = $('#st');
var grupo = $('#grupo');

$("#btnSorteo").click(function(e){
  e.preventDefault();
  var random = $('#randon').val();
  //alert('click '+random);
  console.log(mod.val());


});


</script>
<script>
    (function($) {
        var total = parseInt($('#totales').val());
        var $random = $('#randon');
        var $btnSorteo = $('#btnSorteo');
        var $random1 = $('#randon1');
        var $random2 = $('#randon2');
        var $chkNo = $('#chkNo');
        var $activarBusqueda = $('#activarBusqueda');
        var $capa_Busqueda = $('#capa_Busqueda');

        function verificaBoton(){
            var random = parseInt($random.val()) || 0;
            var msg = '';
            if(random<=0)msg='Random invalido';
            else if(random>total)msg='El valor del rango no puede ser mayor que la cantidad de registros';
            if (msg != ''){
                swal(msg, {icon: "error"});
                return;
            }
            var u = new URL('{!! request()->fullUrl() !!}');
            var t= $random1.prop('checked')?1:2;
            u.searchParams.append('rdn', random);
            u.searchParams.append('type', t);
            if($chkNo.prop('checked'))u.searchParams.append('cn', 1);
            window.location.href = u
        }
        function CambiarTextoConfirmacion(){
            var msg = $chkNo.prop('checked') ? 'Enviar NO Confirmación' : 'Enviar Confirmación';
            $('#enviar_confirmacion').text(msg);
        }

        function ocultarActivarBusqueda(){
            $activarBusqueda.text('Ocultar Sorteo');

        }
        function mostrarActivarBusqueda(){
            var t = $activarBusqueda.text();
            if(t == 'Realizar Sorteo'){
                var texto = 'Ocultar Sorteo';
                $capa_Busqueda.removeClass('d-none');
            }else{
                var texto = 'Realizar Sorteo';
                $capa_Busqueda.addClass('d-none');
            }
            $activarBusqueda.text(texto);
        }

        $(document).ready(function (){
            $btnSorteo.on('click', verificaBoton);
            $chkNo.on('click', CambiarTextoConfirmacion);
            $activarBusqueda.on('click', function(e){
                e.preventDefault();
                mostrarActivarBusqueda();
                return false;
            });
        });


        $('#mod,#st,#gr,#apro,#region').on('change',function(){
            $(this).parents('form').submit();
        });

    })(jQuery);
</script>
@endsection
