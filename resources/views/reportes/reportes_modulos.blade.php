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
                            <h4 class="card-title">Reporte por Módulos</h4>


                            <div class="row" id="capBusqueda">
                                <div class="col-sm-12">
                                    <form action="{{route('reportes.modulos')}}">
                                        <div class="form-row">
                                            <div class=" col-sm-1 col-xs-12">
                                                <label class="form-control border-0 text-right">Buscar por:</label>
                                            </div>
                                            <div class=" col-sm-2 col-xs-12">
                                                <select class="form-control" name="tipo" id="tipo">
                                                    <option value="">MÓDULOS</option>
                                                    <option value="1" {{$tipo==1?'selected':''}}>EVENTOS</option>
                                                    <option value="2" {{$tipo==2?'selected':''}}>CAII</option>
                                                    <option value="3" {{$tipo==3?'selected':''}}>MAESTRÍA</option>
                                                    <option value="4" {{$tipo==4?'selected':''}}>MAILING</option>
                                                    <option value="8" {{$tipo==8?'selected':''}}>DD.JJ</option>{{-- TIPO 8: DDJJ--}}
                                                </select>
                                            </div>
                                            <div class=" col-sm-1 col-xs-12 div_ddjj @if(isset($_GET['tipo'])&&$_GET['tipo']==8) @else d-none @endif">
                                                {{-- @if("2021"==$_GET['y']) @endif --}}
                                                <select class="form-control" name="y" id="y">
                                                    <option value="">AÑO</option>
                                                    @foreach($year as $ye)
                                                    <option value="{{$ye['year']}}" @if(isset($_GET["y"])) @if($_GET["y"]==$ye['year']) selected @endif @endif>{{$ye['year']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class=" col-sm-1 col-xs-12 div_ddjj @if(isset($_GET['tpo'])&&$_GET['tipo']==8) @else d-none @endif">
                                                <select class="form-control" name="tpo" id="tpo">
                                                    <option value="">TIPO</option>
                                                    <option value="APROBADAS" @if(isset($_GET["tpo"])) @if($_GET["tpo"]=="APROBADAS") selected @endif @endif>APROBADAS</option>
                                                    <option value="RECHAZADAS"@if(isset($_GET["tpo"]))  @if($_GET["tpo"]=="RECHAZADAS") selected @endif @endif>RECHAZADAS</option>
                                                </select>
                                            </div>
                                            <div class=" col-sm-1 col-xs-12 div_ddjj @if(isset($_GET['tpo_ins'])&&$_GET['tipo']==8) @else d-none @endif">
                                                <select class="form-control" name="tpo_ins" id="tpo_ins">
                                                    <option  value="">TIPO.INSCRIPCION</option>
                                                    <option @if(isset($_GET["tpo_ins"])) @if($_GET["tpo_ins"]=="CLASES EN VIVO") selected @endif @endif value="CLASES EN VIVO">CLASES EN VIVO</option>
                                                    <option @if(isset($_GET["tpo_ins"])) @if($_GET["tpo_ins"]=="FORMATO MOOC") selected @endif @endif value="FORMATO MOOC">FORMATO MOOC</option>
                                                    <option @if(isset($_GET["tpo_ins"])) @if($_GET["tpo_ins"]=="AUTOINSTRUCTIVO") selected @endif @endif value="AUTOINSTRUCTIVO">AUTOINSTRUCTIVO</option>
                                                    <option @if(isset($_GET["tpo_ins"])) @if($_GET["tpo_ins"]=="PRESENCIAL") selected @endif @endif value="PRESENCIAL">PRESENCIAL</option>
                                                    <option @if(isset($_GET["tpo_ins"])) @if($_GET["tpo_ins"]=="HIBRIDO") selected @endif @endif value="HIBRIDO">HIBRIDO</option>
                                                </select>
                                            </div>
                                            
                                            <div class=" col-sm-2 col-xs-12">
                                                <div class="form-group">
                                                    <div id="datepicker-popup" class="input-group date datepicker">
                                                        <input type="text" class="form-control form-border" name="fecha1" id="fecha1" value="{{ $fecha1 }}" placeholder="Fecha desde">
                                                        <span class="input-group-addon input-group-append border-left">
                                                          <span class="mdi mdi-calendar input-group-text"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" col-sm-2 col-xs-12">
                                                <div class="form-group">
                                                    <div id="datepicker-popup2" class="input-group date datepicker">
                                                        <input type="text" class="form-control form-border" name="fecha2" id="fecha2" value="{{ $fecha2 }}" placeholder="Fecha hasta">
                                                        <span class="input-group-addon input-group-append border-left">
                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-sm-1 col-xs-12">
                                                <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div id="capaEstudiantes" class="row">
                                <div class="col-12">

                                    {{ Form::open(array('route' => array('campanias.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                                    <div class="row">{{-- cap: opciones --}}

                                        <div class="col-xs-12  col-sm-8 text-left mb-4">
                                            @if(isset($_GET['tipo']))
                                            {{-- <a href="{{ route('reportes.exporta')."?tipo={$tipo}&fecha1={$fecha1}&fecha2={$fecha2}" }}" title="Exportar" class="btn btn-success btn-sm icon-btn "><i class="mdi mdi-file-excel-box text-white icon-md"></i>
                                            Exportar</a>  --}}
                                            <a href="{{ route('exportar_excel')."?tipo={$tipo}&fecha1={$fecha1}&fecha2={$fecha2}&y={$y}" }}" title="Exportar" class="btn btn-success btn-sm icon-btn "><i class="mdi mdi-file-excel-box text-white icon-md"></i>
                                                Exportar</a>
                                            
                                            @endif

                                        </div> {{-- end derecha --}}

                                        <div class="col-xs-12 col-sm-4 text-right mb-4">
                                            <span class="small pull-left">
                                              <strong>Mostrando</strong>
                                              {{ $data["count"]??0 }} registros
                                            </span>
                                        </div>{{-- end izq --}}

                                    </div> {{-- end cap: opciones --}}
                                    <div id="order-listing_wrapper"{{--  class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" --}}>
                                        @if($tipo>0&&$tipo!=4)
                                        <div class="row">
                                            <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
                                                <table id="order-listing" class="table table-hover table-sm">
                                                    <thead class="thead-dark">
                                                    <tr role="row">
                                                        <th class="sorting" colspan="8">{{$titulo}}</th>
                                                    </tr>
                                                    <tr role="row">
                                                        <th class="sorting text-center" style="width: 3%;">#</th>
                                                        <th class="sorting" style=";">Nombre</th>
                                                        <th class="sorting" style="width: 5%;">Registrados</th>
                                                        <th class="sorting" style="width: 5%;">{{$tipo==8||$tipo==10 ?'DJ.Aprobados':($tipo!=3?'Asistidos':'Aptos Examen')}}</th>
                                                        @if($tipo==8||$tipo==10)<th class="sorting" style="width: 5%;">DJ.Rechazados</th>@endif
                                                        <th class="sorting" style="width: 8%;">{{$tipo!=3?'Fecha Inicio':'Aprobaron Examen'}}</th>
                                                        <th class="sorting" style="width: 10%;">{{$tipo!=3?'Fecha Fin':'Fecha Inicio'}}</th>
                                                        <th class="sorting" style="width: 5%;">{{$tipo!=3?'Gafete':'Fecha Fin'}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($data["data"] as $i=>$v)
                                                    <tr role="row" >
                                                        <td align="center">{{$v["id"]}}</td>{{-- {{ $i+1 }}  --}}
                                                        <td>
                                                            @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                                            <a href="
                                                                @if($tipo==1 or $tipo==2) 
                                                                    {{route('leads.index', ['eventos_id'=>$v["id"]])}}
                                                                @elseif($tipo==3)
                                                                    {{route('leads.index', ['eventos_id'=>$v["id"], 'tipo'=>4 ])}}
                                                                @elseif($tipo==8)
                                                                    {{route('leads.index', ['eventos_id'=>$v["id"], 'tipo'=>8 ])}}
                                                                @elseif($tipo==10)
                                                                    {{route('leads.index', ['eventos_id'=>$v["id"], 'tipo'=>10 ])}}
                                                                @else
                                                                    #
                                                                @endif
                                                                "
                                                                class="btn btn-link p-0" target="_blank">
                                                                {{ \Illuminate\Support\Str::limit($v["nombre"],85) }}
                                                                </a>
                                                            @else
                                                                {{ $v["nombre"] }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center pr-1">{{ $v["registrados"] }}</td>
                                                        <td class="text-center">{{-- {{ $tipo!=3?$v["asistieron"]:($tipo==8?$v["aptos"]:$v["aptos"]) }} --}} 
                                                            @if($tipo==8||$tipo==10)
                                                                {{$v["aptos"]}} {{-- aprobados --}}
                                                            @elseif($tipo!=3)
                                                                {{$v["asistieron"]}}
                                                            @else
                                                            {{$v["aptos"]}}
                                                            @endif
                                                        </td>
                                                        @if($tipo==8||$tipo==10)<td class="text-center">{{ $v["rechazados"] }} {{-- rechazados --}}</td>@endif
                                                        <td  class="text-center">{{ $tipo!=3?$v["fecha"]:$v["aprobados"] }} </td>
                                                        <td>{{ $tipo!=3?$v["fecha2"]:$v["fecha"] }}</td>
                                                        <td align="center" class="text-center">
                                                            @if($tipo!=3)
                                                                <span class="badge <?php if($v["gafete"] == "SI") echo "badge-dark"?> <?php if($v["gafete"] == "NO") echo "badge-secondary"?>">{{$v["gafete"]}}</span>
                                                            @else
                                                                {{$v["fecha2"]}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                        @empty
                                                    @endforelse
                                                    @if($tipo!=3&&$tipo!=8)
                                                    <tr role="row">
                                                        <td></td>
                                                        <td class="text-right pr-1">Total con Gafete</td>
                                                        <td class="text-center pr-1">{{ $data["total_gafete"] }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr role="row">
                                                        <td></td>
                                                        <td class="text-right pr-1">Total sin Gafete</td>
                                                        <td class="text-center pr-1">{{ $data["total_sin_gafete"] }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @endif

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                        @if($tipo==4)
                                            <div class="row">
                                                <div class="table-responsive fixed-height" style="height: 500px; padding-bottom: 49px;">{{-- table-responsive-lg --}}{{--  --}}
                                                    <table id="order-listing" class="table table-hover table-sm">
                                                        <thead class="thead-dark">
                                                        <tr role="row">
                                                            <th class="sorting" colspan="6">{{$titulo}}</th>
                                                        </tr>
                                                        <tr role="row">
                                                            <th class="sorting text-center" style="width: 3%;">#</th>
                                                            <th class="sorting" style="width: 80%;">Nombre Campaña</th>
                                                            <th class="sorting" style="width: 10%;">Total Participantes</th>
                                                            <th class="sorting" style="width: 10%;">Total Enviados</th>
                                                            <th class="sorting" style="width: 10%;">Total Rebotados</th>
                                                            <th class="sorting" style="width: 10%;">Fecha</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($data["data"] as $i=>$v)
                                                            <tr role="row" class="odd" <?php if($i%2==0) echo "style='background:#e6f9ef;'"?> <?php //if($i%2!=0) echo "style='background:#f7d3d3;'"?>>
                                                                <td align="center">{{ $i+1 }}</td>
                                                                <td>
                                                                    @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                                                        <a href="{{route('campanias.reportes', $v["id"])}}" class="btn btn-link p-0" target="_blank">
                                                                            {{ $v["nombre"] }}
                                                                        </a>
                                                                    @else
                                                                        {{ $v["nombre"] }}
                                                                    @endif
                                                                </td>
                                                                <td class="text-center pr-1">{{ $v["participantes"] }}</td>
                                                                <td class="text-center pr-1">{{ $v["entregados"] }}</td>
                                                                <td class="text-center pr-1">{{ $v["rebotados"] }}</td>
                                                                <td>{{ $v["fecha"] }}</td>
                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                        <tr role="row">
                                                            <td></td>
                                                            <td class="text-center pr-1">TOTALES</td>
                                                            <td class="text-center pr-1">{{ $data["total"] }}</td>
                                                            <td class="text-center pr-1">{{ $data["entregados"] }}</td>
                                                            <td class="text-center pr-1">{{ $data["rebotados"] }}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
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

@section('scripts')
<script>
$(document).ready(function(){
  $('#tipo').on('change',(e)=>{
    let tpo=e.target.value;
    let url = "{{route("ddjj_year")}}";
    if(tpo==8){
      $('.div_ddjj').removeClass('d-none');
      $.get(url,function(resp, resul){
          $('#y').empty();
          if(resp.length>0){
            console.log("valor resul ="+resp.length);
            $("#y").html(resp); 
          
          }else{
          	console.log("0 Registros.");
          }
        });
    }else{
      $('.div_ddjj').addClass('d-none');
    }
    console.log(url);
  });
});

</script>
@endsection