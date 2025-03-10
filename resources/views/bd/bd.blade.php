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
              <h4 class="card-title">Base de Datos</h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-8 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="@if(isset($_GET['s'])){{$_GET['s']}}@endif">

                        <?php if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('bd.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>

                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select name="status" id="status" onchange="submit()" class="form-control">
                          <option value="">SELECCIONE</option>
                          <option value="1" @if(request()->get('status')==="1") selected="" @endif>ACTIVOS</option>
                          <option value="0" @if(request()->get('status')==="0") selected="" @endif>INACTIVOS</option>
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == "15")) selected="" @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == "20")) selected="" @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == "30")) selected="" @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == "50")) selected="" @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == "100")) selected="" @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == "500")) selected="" @endif>500</option>
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




              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif

              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('bd.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}

                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        {{-- <a href="{{ route('leads.create', ['eventos_id'=>2]) }}" title="Agregar" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a> --}}
                      @endif

                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                      <a href="#" onclick="eximForm()" class="btn btn-sm btn-secondary" title="Exportar / Importar" data-toggle="modal"><i class="mdi mdi-upload icon-btn"></i></a>
                      <a href="{{request()->fullUrlWithQuery(["export"=>1])}}" class="btn btn-sm btn-success d-none" id="exportar" disabled >Exportar</a>
                      @endif


                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary d-none" disabled="" id="delete_bd" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif


                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $estudiantes_datos->firstItem() }} - {{ $estudiantes_datos->lastItem() }} de
                          {{ $estudiantes_datos->total() }}
                        </span>
                      </div>{{-- end izq --}}

                  </div> {{-- end cap: opciones --}}

                    <div class="row d-none" id="progreso">
                        <div class="col p-2">
                            <div class="progress progress-xl">
                                <div class="progress-bar" role="progressbar" ></div>
                            </div>
                        </div>
                    </div>

                  <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 3%;"></th>
                              {{-- <th style="width: 2%;">#</th> --}}
                              <th style="width: 8%;">DNI</th>
                              <th style="width: 25%;">Apellidos y Nombres</th>

                              <th style="width: 10%;">Cargo</th>
                              <th style="width: 10%;">Entidad</th>
                              <th style="width: 10%;">Profesión</th>
                              <th style="width: 10%;">Grupo</th>
                              <th style="width: 10%;">País</th>
                              <th style="width: 10%;">Departamento</th>
                              {{-- <th style="width: 5%;">Registrado</th> --}}
                              @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                              <th style="width: 5%;">Celular</th>
                              <th style="width: 5%;">Email</th>
                              @endif
                              <th style="width: 5%;">FechaReg</th>
                              <th style="width: 3%;">Estado</th>
                            </tr>
                          </thead>
                          <tbody>

                            @foreach ($estudiantes_datos as $datos)
                            <tr role="row" class="odd" <?php if($datos->dtrack == "SI") echo "style='background:#a0e8c5;'"?> <?php if($datos->dtrack == "NO") echo "style='background:#f7d3d3;'"?>>
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('leads.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                    @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 4){{-- 1 --}}
                                    <a href="#" class="asignarProg" data-id="{{ $datos->dni_doc }}">
                                      <i class="mdi mdi-menu text-secondary icon-md"></i>
                                    </a>
                                    @endif
                                    <a href="#" title="Ver Historial" class="estudianteHistorial" data-id="{{ $datos->dni_doc }}">
                                      <span class="badge ml-2 badge-dark">Historial</span>
                                    </a>
                                </td>
                                <td>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                    <a href="{{route('bd.edit', $datos->id)}}" class=" p-0">{{ $datos->dni_doc }}</a>
                                  @else
                                    {{ $datos->dni_doc }}
                                  @endif
                                </td> {{-- onclick="openModal()" --}}
                                <td>{{ $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres }}</td>
                                <td>{{ \Str::limit($datos->cargo, 25) }}</td>
                                <td>{{ \Str::limit($datos->organizacion, 25) }}</td>
                                <td>{{ \Str::limit($datos->profesion, 25) }}</td>
                                {{-- <td> {{ $datos->departamento->nombre or '' }} </td> --}}
                                <td> {{ $datos->grupo  }} </td>
                                <td> {{ $datos->pais  }} </td>
                                <td> {{ $datos->region  }} </td>
                                
                                {{-- <td class="text-center">{{ $datos->daccedio }}</td> --}}
                                @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                <td>
                                  @if($datos->celular != "") {{ $datos->codigo_cel." ".$datos->celular }} @endif
                                </td>
                                <td>{{ $datos->email }}</td>
                                @endif
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}

                                <td>{{ $datos->created_at->format('d.m.Y H:m:s') }}</td>
                                <td class="text-center">
                                  @if($datos->estado == 0)
                                    <i class="mdi mdi-account-circle text-secondary h4" title="Inactivo"></i>
                                  @else
                                    <i class="mdi mdi-account-circle text-success h4" title="Activo"></i>
                                  @endif
                                </td>



                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $estudiantes_datos->appends(request()->query())->links() !!}
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


{{-- form importar --}}
<div class="modal fade ass" id="Modal_estudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('bd.import') }}" class="formarchivo" enctype="multipart/form-data" >
          {!! csrf_field() !!}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Importar Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0">
          {{-- <div class="form-group row">
            <h4 class="col-md-3 mt-1">Export</h4>
            <div class="col-md-9">
              <a href="{{ route('leads.export') }}" class="btn btn-secondary btn-block">Exportar</a>
              <span class="help-block with-errors"></span>
            </div>
          </div> --}}
          <div class="form-group row">
            {{-- <h4 class="col-md-3 mt-1">Import</h4> --}}
            <div class="col-md-12">
              <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                <input type="file" name="file" id="archivo" class="dropify" required>
                <button type="button" class="dropify-clear">Quitar</button>

                <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>

              <span class="help-block with-errors"></span>

            </div>
          </div>
        <div style="display:none;" id="cargador_excel" class="content-wrapper p-0" align="center">  {{-- msg cargando --}}
          <div class="card bg-white" style="background:#f3f3f3 !important;" >
            <div class="">
              <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
              <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
            </div>
          </div>
        </div>{{-- msg cargando --}}



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnImport1" >Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade ass" id="Modal_organizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 95%; margin-top:2%; ">
    <div class="modal-content" style="max-height: 600px;">

      <div class="card">
        <div class="card-body" style=" overflow: scroll;max-height:520px;">
          <iframe src="{{ route('bd.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none; border: 1px solid #e6e6e6;"></iframe>

          <form class="form-inline"  id="estudiantesImportSave" name="estudiantesImportSave" action="{{ route('bd.importsave') }}" method="post" >
            {!! csrf_field() !!}
          <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox">
                  <label> <input class="rnr-checkbox" id="chkPrimeraFila" name="chkPrimeraFila" type="checkbox" value="1" checked> Cabeceras de columnas en la primera línea</label>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div id="dateFormatSettings1" class="rnr-is-control form-group">

                    <label class="pr-2" style="font-size: 15px">Formato de fecha: </label>


                    <input id="txtFormatoF" name="txtFormatoF" type="text" value="dd/mm/yyyy" class="form-control border-primary">

                </div>
              </div>
              {{-- <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox text-left">
                  <label class="d-flex justify-content-start text-dark font-weight-bold"> <input class="rnr-checkbox" id="chkE_invitacion" name="chkE_invitacion" type="checkbox" value="1" > Enviar Invitación</label>
                </div>
              </div> --}}
              <div style="display:none;" id="cargador_excel2" class="content-wrapper p-0" align="center">{{-- end div cargando --}}
                <div class="card bg-white text-center p-3 border0" style="background:#fff !important;" >
                  <div class="row col-12">
                    <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                    <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                  </div>
                </div>
              </div> {{-- end div cargando --}}
          </div>

          <div class="row">
              <table id="tbl_estudiantes_imp_ord" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
              </table>
              <input type="hidden" name="totCol" id="totCol">
              <input type="hidden" name="hdnTabla" id="hdnTabla">
          </div>

          </form>
        </div>
      </div>
      <div class="modal-footer">
        <div id="resultado" style="display:none;">Cargando...</div>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="btnRegresar" >Regresar</button>

        <button type="button" class="btn btn-secondary" id="btnCerrar" {{-- data-dismiss="modal" --}}>Cerrar</button>
        <button type="button" class="btn btn-dark" id="btnSumImport">Importar Datos</button>

      </div>

    </div>

  </div>
</div>
{{-- form importar --}}

{{-- Historial --}}
<div class="modal modalHistorial fade" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="heTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">{{-- modal-lg --}}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="heTitle">Historial Participante: </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0">
          <div class="form-group row">

            <div class="col-md-12" id="historiaE">

              <table class="table table_his">
                <thead class="thead-dark">
                  <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Evento</th>
                    <th>Fecha Desde</th>
                    <th>Fecha Hasta</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>

            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        {{-- <button type="submit" class="btn btn-primary" id="btnImport1">Importar</button> --}}
      </div>

    </div>
  </div>
</div>

@endsection
@section('scripts')
    <script>
        var $request;
        var xtotal = {{ $estudiantes_datos->total() ?? '0' }};
        $(document).ready(function(){
            $('#exportar').on('click',function(e){
                e.preventDefault();

                var disabled =  $('#exportar').attr('disabled');
                if(disabled){return false;}
                $('#exportar').attr('disabled',true);
                var start = +new Date();
                var url=$(this).attr('href');

                /*
                $.getJSON(url,function(data){
                   if(data)window.location.href=data.url;
                })
                */
                let percentValue = 0, percentIncrement = 25, time =500,
                    progressBar = $('.progress-bar'), $progreso = $('#progreso');
                $progreso.removeClass('d-none');
                if(xtotal<100)percentIncrement = 50;
                else if(xtotal<1000){
                    percentIncrement = 40;
                }
                else if(xtotal<2000){
                    percentIncrement = 25;
                }
                else if(xtotal<2000){
                    percentIncrement = 25;
                }
                else if(xtotal<4000){
                    percentIncrement = 10;
                }
                else if(xtotal<6000){
                    percentIncrement = 8;
                }
                else if(xtotal<8000){
                    percentIncrement = 6;
                }
                else if(xtotal<10000){
                    percentIncrement = 5;
                }
                else if(xtotal<15000){
                    percentIncrement = 3;
                }
                else if(xtotal<40000){
                    percentIncrement = 1;
                }
                else if(xtotal<40000){
                    percentIncrement = 1;
                    time = 700;
                }
                else if(xtotal<50000){
                    percentIncrement = 1;
                    time = 850;
                }
                else if(xtotal<60000){
                    percentIncrement = 1;
                    time = 1000;
                }
                else if(xtotal<65000){
                    percentIncrement = 1;
                    time = 1100;
                }
                else if(xtotal<70000){
                    percentIncrement = 1;
                    time = 1350;
                }
                else{
                    percentIncrement = 1;
                    time=1400;
                }
                progressBar.css("width", "1%").html("1%");
                timer = setInterval(startBar, time);
                if ($request != null){
                    $request.abort();
                    $request = null;
                }
                $request = $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(){
                        if (percentValue < 100) percentValue = 100-percentIncrement;
                    }
                }).done(function(data){
                    var end = +new Date();
                    console.log(xtotal,'>>>>>Tiempo Transcurrido>>>>>>>>>>',end - start);
                    progressBar.css("width", "100%").html("100%");
                    if(data.success)
                      window.location.href=data.url;
                    $progreso.addClass('d-none');
                    $('#exportar').attr('disabled',false);
                    xhr = false;
                }).fail(function(jqxhr, textStatus, error ){
                    var err = textStatus + ", " + error;
                    console.log( "Request Failed: " + err );
                    $progreso.addClass('d-none');
                    $('#exportar').attr('disabled',false);
                    xhr = false;
                });

                function startBar(){
                    if (percentValue < 100) {
                        percentValue += percentIncrement;
                        if(percentValue < 100)
                        progressBar.css("width", percentValue + "%").html(percentValue + "%");else
                        clearInterval(timer);
                    } else {
                        clearInterval(timer);
                    }
                }
                /*
               var urlTime=url+'&time=1';*/
               return false;
            });
            $('#exportar').attr('disabled',false).removeClass("d-none");
            $('#delete_bd').removeClass("d-none");
        });
    </script>
@endsection
