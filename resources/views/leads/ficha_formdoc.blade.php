@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper p-0 mt-3">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Ficha de Inscripción</h4>
                    <span class="badge badge-danger">{{\Illuminate\Support\Str::limit(session('evento')['nombre'],40)}}</span>
                  </div>
                  <p class="card-description">
                  </p>
                  <a href="{{ route('leads.index', array('tipo'=>$_GET['tipo'])) }}"
                  style="position: fixed;bottom: 70px;" type="button" title="Regresar" class="btn btn-dark btn-rounded p-2">
                    <i class="mdi mdi-keyboard-backspace"></i> Regresar</a> 
                    <a href="{{ route('leads.fichaexcel', array('id'=>$datos->id_estudiante)) }}"
                        style="position: fixed;bottom: 20px;" type="button" title="Regresar" class="btn btn-success btn-rounded p-2">
                          <i class="mdi mdi-file-excel"></i> Excel</a>
                          

                  {{-- formulario  --}}
                  
                  <div class="container">
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <table border="0"><tr><td colspan="5"><b>I. Datos Personales</b></td></tr></table>
                                <table border="1" class="w-100">
                                    <caption></caption>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="3" rowspan="2" with="250"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>APELLIDO
                                                PATERNO</strong></td>
                                        <td colspan="3" rowspan="2" with="250"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>APELLIDO
                                                MATERNO</strong></td>
                                        <td colspan="3" rowspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NOMBRES</strong></td>
                                        <td colspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>DOCUMENTO DE IDENTIDAD</strong></td>
                                    </tr>
                                    <tr bgcolor="#c0c0c0">
                                        <td
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>TIPO</strong></td>
                                        <td
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NÚMERO</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">{{$datos->ap_paterno}}</td>
                                        <td colspan="3">{{$datos->ap_materno}}</td>
                                        <td colspan="3">{{$datos->nombres}}</td>
                                        <td align="center">{{$datos->tipo_doc}}</td>
                                        <td align="center">{{$datos->dni_doc}}</td>
                                    </tr>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="4"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CORREO
                                                INSTITUCIONAL</strong></td>
                                        <td colspan="4"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CORREO
                                                ELECTR&#xd3;NICO PERSONAL</strong></td>
                                        <td colspan="3"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CELULAR</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">{{$datos->email_labor}}</td>
                                        <td colspan="4">{{$datos->email}}</td>
                                        <td colspan="3">{{$datos->celular}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.INICIO-->
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <br>
                                <table border="0"><tr><td colspan="5"><b></b></td></tr><tr><td colspan="5"><b>II. Formación Académica</b></td></tr></table>
                                <table border="1" class="w-100">
                                    <caption></caption>
                                    <col>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td rowspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NIVEL ACADÉMICO</strong></td>
                                        <td colspan="2" rowspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CARRERA PROFESIONAL</strong></td>
                                        <td colspan="2" rowspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>ESPECIALIDAD</strong></td>
                                        <td colspan="2" rowspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CENTRO DE ESTUDIOS</strong></td>
                                        <td colspan="3"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE OBTENCIÓN DEL GRADO/TÍTULO</strong></td>
                                        <td colspan="1" rowspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>N° DE APOSTILLADO</strong></td>
                                    </tr>
                                    <tr bgcolor="#c0c0c0">
                                        <td
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>(DIA)</strong></td>
                                        <td
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>(MES)</strong></td>
                                        <td
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>(AÑO)</strong></td>
                                    </tr>
                                    @forelse($formaciones as $f)
                                    <tr>
                                        <td>{{$f->nivel_academico}}</td>
                                        <td colspan="2">{{$f->carr_profesional}}</td>
                                        <td colspan="2">{{$f->especialidad}}</td>
                                        <td colspan="2">{{$f->institucion}}</td>{{--  --}}
                                        @php
                                            $dia_tit = strtotime($f->fecha_tit);
                                        @endphp
                                        <td class="center">{{date("d",$dia_tit)}}</td>
                                        <td class="center">{{date("m",$dia_tit)}}</td>
                                        <td class="center">{{date("Y",$dia_tit)}}</td>
                                        <td class="center">{{$f->napostillado}}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.FIN-->
                    <!--/.INICIO-->
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <br>
                                <table border="0" class="w-100"><tr><td colspan="5"><b></b></td></tr><tr><td colspan="5"><b>III. Capacitaciones Complementarias (Últimos 5 años)</b></td></tr></table>
                                <table border="1" class="w-100">
                                    <caption></caption>
                                    <col>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="3"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NOMBRE DE LA CAPACITACIÓN</strong></td>
                                        <td colspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>TIPO DE CAPACITACIÓN</strong></td>
                                        <td colspan="3"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CENTRO DE ESTUDIOS</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE INICIO</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE TÉRMINO</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>HORAS CRONOLÓGICAS</strong></td>
                                    </tr>
                                    @forelse($capacitaciones as $c)
                                    <tr>
                                        <td colspan="3">{{$c->nombre_cap}}</td>
                                        <td colspan="2">{{$c->tipo_cap}}</td>
                                        <td colspan="3">{{$c->institucion}}</td>
                                        <td colspan="1">{{$c->fecha_inicio_cap}}</td>
                                        <td colspan="1">{{$c->fecha_fin_cap}}</td>
                                        <td colspan="1">{{$c->horas_cron}}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.FIN-->
                    <!--/.INICIO-->
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <br>
                                <table border="0"><tr><td colspan="5"><b></b></td></tr><tr><td colspan="5"><b>IV. Experiencia Laboral</b></td></tr></table>
                                <table border="1" class="w-100">
                                    <caption></caption>
                                    <col>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>EMPRESA / INSTITUCIÓN</strong></td>
                                        <td  colspan="2"
                                             style="text-align:center;margin-left:auto;margin-right:auto;"><strong>TIPO DE EMPRESA</strong></td>
                                        <td  colspan="2"
                                             style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CARGO O PUESTO</strong></td>
                                        <td  colspan="1"
                                             style="text-align:center;margin-left:auto;margin-right:auto;"><strong>MODALIDAD DE CONTRATO</strong></td>
                                        <td  colspan="2"
                                             style="text-align:center;margin-left:auto;margin-right:auto;"><strong>ACTIVIDAD DESARROLLADA</strong></td>
                                        <td  colspan="1"
                                             style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE INICIO</strong></td>
                                        <td  colspan="1"
                                             style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE TÉRMINO</strong></td>
                                    </tr>
                                    @forelse($experiencias as $exp)
                                    <tr>
                                        <td colspan="2">{{$exp->nom_empresa}}</td>
                                        <td colspan="2">{{$exp->tipo_industria}}</td>
                                        <td colspan="2">{{$exp->puesto_cargo}}</td>
                                        <td colspan="1">{{$exp->modalidad_contrato}}</td>
                                        <td colspan="2">{{$exp->actividad_desarrollada}}</td>
                                        <td colspan="1">{{$exp->fecha_inicio_lab}}</td>
                                        <td colspan="1">{{$exp->fecha_fin_lab}}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.FIN-->
                    <!--/.INICIO-->
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <br>
                                <table border="0"><tr><td colspan="5"><b></b></td></tr><tr><td colspan="5"><b>V. Experiencia en Docencia</b></td></tr></table>
                                <table class="w-100" border="1">
                                    <caption></caption>
                                    <col>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>INSTITUCIÓN</strong></td>
                                        <td colspan="3"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NOMBRE DE LA INSTITUCIÓN</strong></td>
                                        <td colspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NIVEL</strong></td>
                                        <td colspan="2"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CURSO A CARGO</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE INICIO</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>FECHA DE TÉRMINO</strong></td>
                                    </tr>
                                    @forelse($experiencias2 as $exp)
                                    <tr>
                                        <td colspan="2">{{$exp->institucion_exp}}</td>
                                        <td colspan="3">{{$exp->nombre_institucion}}</td>
                                        <td colspan="2">{{$exp->nivel}}</td>
                                        <td colspan="2">{{$exp->curso_a_cargo}}</td>
                                        <td colspan="1">{{$exp->fecha_inicio_exp}}</td>
                                        <td colspan="1">{{$exp->fecha_fin_exp}}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.FIN-->
                    <!--/.INICIO-->
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <br>
                                <table border="0"><tr><td colspan="5"><b></b></td></tr><tr><td colspan="5"><b>VI. Declaraciones Juradas</b></td></tr></table>
                                <table class="w-100"  border="1">
                                    <caption></caption>
                                    <col>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="9"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CONSIDERACIONES LEGALES /ADMINISTRATIVAS</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>SI</strong></td>
                                        <td colspan="1"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>NO</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="9">1.- Con registro en la Nómina de  Deudores Alimentarios Morosos.</td>
                                        <td colspan="1" class="center">{{$datos->preg_1=="SI"?"X":""}}</td>
                                        <td colspan="1" class="center">{{$datos->preg_1=="NO"?"X":""}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9">2.- Con pago pendiente de reparación civil impuesta por una condena ya cumplida.</td>
                                        <td colspan="1" class="center">{{$datos->preg_2=="SI"?"X":""}}</td>
                                        <td colspan="1" class="center">{{$datos->preg_2=="NO"?"X":""}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9">3.- Condenado por delito doloso con sentencia de autoridad de cosa juzgada.</td>
                                        <td colspan="1" class="center">{{$datos->preg_3=="SI"?"X":""}}</td>
                                        <td colspan="1" class="center">{{$datos->preg_3=="NO"?"X":""}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9">4.- Con registro antecedentes penales.</td>
                                        <td colspan="1" class="center">{{$datos->preg_4=="SI"?"X":""}}</td>
                                        <td colspan="1" class="center">{{$datos->preg_4=="NO"?"X":""}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9">5.-  Consignado en el Registro Nacional de Sanciones contra Servidores Civiles.</td>
                                        <td colspan="1" class="center">{{$datos->preg_5=="SI"?"X":""}}</td>
                                        <td colspan="1" class="center">{{$datos->preg_5=="NO"?"X":""}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.FIN-->
                    <!--/.INICIO-->
                    <div class="row">
                        <div class="w-100 span12">
                            <div class="content">
                                <br>
                                <table border="0"><tr><td colspan="5"><b></b></td></tr><tr><td colspan="5"><b>VII. Cursos que podría dictar</b></td></tr></table>
                                <table class="w-100"  border="1">
                                    <caption></caption>
                                    <col>
                                    <tbody>
                                    <tr bgcolor="#c0c0c0">
                                        <td colspan="8"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>CURSO</strong></td>
                                        <td colspan="3"
                                            style="text-align:center;margin-left:auto;margin-right:auto;"><strong>LÍNEA DE CAPACITACIÓN</strong></td>
                                    </tr>
                                    @forelse($cursos as $c)
                                    <tr>
                                        <td colspan="8">{{$c->detalle_cursos}}</td>
                                        <td colspan="3">{{$c->linea_capacitacion}}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!--/.content-->
                        </div>
                        <!--/.span9-->
                    </div>
                    <!--/.FIN-->
                </div>

                  <div class="form-group row masinfo">
                    <div class="col-sm-12 text-center mt-4">

                      <?php
                        $ruta = '';$opc ='';
                        if(isset($_GET['opc'])){
                          $ruta = route('leads.index', array('opc'=>$_GET['opc']));
                          $opc ='<input type="hidden" name="opc" value="'.$_GET['opc'].'" />';
                        }else{
                          if(isset($_GET['tipo'])){
                            $ruta = route('leads.index', array('tipo'=>$_GET['tipo']));
                          }
                        }
                        ?>
                        {!! $opc !!}
                      


                      <a href="{{ $ruta }}" class="btn btn-inverse-dark btn-fw">Volver al listado</a>

                      {{-- <button type="button" class="btn btn-primary btn-sm" onclick="showToastPosition('bottom-right')">Bottom-right</button> --}}

                    </div>
                  </div>
                  
                </div>
              </div>
            </div>

          </div>
          
        </div>
        

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
  <style>
    table td{padding: 3px;font-family: calibri; font-size:14px;color:#555;}
    .center{text-align: center;}
  </style>

@endsection