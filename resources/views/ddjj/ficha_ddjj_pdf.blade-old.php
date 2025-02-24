<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
        font-size: 11px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        /*font-family: 'Montserrat', sans-serif;*/
        padding:0px;margin:0px;
        }

        table {border-collapse: collapse;}
        table td {padding:2px 6px;}
        div.container{width:700px;margin:4% 3% 0 3%;padding-left:15px;}
        table.table{width:720px;color:#999;border:1px solid #666;}
        table table td{padding:4px 6px; vertical-align: top;}/* white-space:pre-line !important; */
        table.table td span{font-weight:700;color:#333;}
        span.title{font-size:14px;}
        table table.table td p{
            white-space:pre-line !important;
        }
        @page {
            size: 21cm 29.7cm;
            margin: 0;
        }
        table.table{
            border: 0;
        }
        body {
            font-size: 13px;
            color: #999;
        }
        div .h1{color:black; font-size: 14px;margin: 6px 0 3px;}
    </style>
</head>
<body>
<div class="container">
    <span>
        <img src="{{url('images/ev/Header1.jpg')}}" alt="header" width="700">
    </span>
    <h1 class="h1">DECLARACIÓN JURADA</h1>

    <table class="print_table table" border="0" align="center">
        <tr>
            <td style="width:30%;"><h2 class="h1">I. Datos Personales<h2 class="h1"></td>
            <td style="width:30%;"></td>
            <td style="width:30%;"></td>
        </tr>
        @php
            if($datos->f_ini_curso != "0000-00-00 00:00:00" or $datos->f_fin_curso != "0000-00-00 00:00:00"){
                $f_ini  = strtotime($datos->f_ini_curso);
                $inicio = date("d/m/Y", $f_ini);
                $f_fin  = strtotime($datos->f_fin_curso);
                $fin    = date("d/m/Y", $f_fin);
            }
        @endphp
        <tr>
            <td ><span>Código Curso</span></td>
            <td colspan="2" style="white-space: pre-line !important;"><span>Nombre Curso</span></td>
        </tr>
        <tr>
            <td style="vertical-align: top;white-space: initial !important;">
                {{$datos->curso->cod_curso}}
            </td>    {{-- white-space: initial; --}}
            <td colspan="2" style="white-space: initial !important;">{{$datos->curso->nom_curso}}</td>

        </tr>

        <tr>
            <td ><span>Fecha Inicio</span></td>
            <td ><span>Fecha Fin</span></td>
            <td ></td>
        </tr>
        <tr>
            <td>{{$datos->curso->fech_ini}}</td>
            <td>{{$datos->curso->fech_fin}}</td>
            <td></td>
        </tr>
        <tr>
            <td><span>Nombres</span></td>
            <td><span>Apellido Paterno</span></td>
            <td><span>Apellido Materno</span></td>
        </tr>
        <tr>
            <td>{{$datos->nombres}}</td>
            <td>{{$datos->ap_paterno}}</td>
            <td>{{$datos->ap_materno}}</td>
        </tr>
        <tr>
            <td><span>Documento de Identidad</span></td>
            <td colspan="2"><span>Dirección </span></td>
        </tr>
        <tr>
            <td>
                @if($datos->tipo_documento_documento_id==1)DNI: @endif
                @if($datos->tipo_documento_documento_id==2)PASAPORTE: @endif
                @if($datos->tipo_documento_documento_id==3)C.E.: @endif
                @if($datos->tipo_documento_documento_id==4)OTROS: @endif
                {{$datos->dni_doc}}</td>
            <td colspan="2">{{$datos->direccion}}</td>
        </tr>
        <tr>
            <td><span>Departamento</span></td>
            <td><span>Provincia</span></td>
            <td><span>Distrito</span></td>
        </tr>
        <tr>
            <td>{{$datos->region}}</td>
            <td>{{$datos->provincia}}</td>
            <td>{{$datos->distrito}}</td>
        </tr>
        <tr>
            <td><span>Correo Electrónico {{-- Institucional --}}</span></td>
            <td><span>{{-- Correo Electrónico Personal --}}</span></td>
            <td><span>Celular</span></td>
        </tr>
        <tr>
            <td>{{$datos->email}}</td>
            <td>{{-- {{$datos->email_labor}} --}}</td>
            <td>{{$datos->celular}}</td>
        </tr>
        <tr>
            <td colspan="3"><span>En condición de colaborador (a) en:</span></td>
            

        </tr>
        <tr>
            <td>{{$datos->dgrupo}}</td>
            <td colspan="2">{{$datos->organizacion}}</td>

        </tr>
        <tr>
            <td><span>Cargo</span></td>
            <td><span>Nivel</span></td>
            <td><span></span></td>
        </tr>
        <tr>
            <td>{{$datos->cargo}}</td>
            <td>{{$datos->gradoprof}}</td>
            <td></td>
        </tr>

        <tr>
            <td><span>Modalidad Contractual</span></td>
            <td><span>Fecha Inicio de Contrato</span></td>
            <td><span>Fecha Fin de Contrato</span></td>
        </tr>
        <tr>
            <td>{{$datos->moda_contractual}}</td>
            @php
                if($datos->fecha_inicio != "0000-00-00 00:00:00" or $datos->fecha_fin != "0000-00-00 00:00:00"){
                    $f_ini  = strtotime($datos->fecha_inicio);
                    $inicio = date("d/m/Y", $f_ini);
                    //$f_fin  = strtotime($datos->fecha_fin);
                    //$fin    = date("d/m/Y", $f_fin);
                }
                if($datos->contrato == "INDETERMINADO"){
                    $fin = $datos->contrato;
                }else{
                    $f_fin  = strtotime($datos->fecha_fin);
                    $fin    = date("d/m/Y", $f_fin);
                }
            @endphp
            <td>{{isset($inicio)?$inicio:''}}</td>
            <td>{{isset($fin)?$fin:''}}</td>
        </tr>
        <tr>
            <td colspan="3"><span></span></td>

        </tr>
        <tr>
            <td colspan="3"><h2 class="h1">DECLARO BAJO JURAMENTO LO SIGUIENTE:</h2></td>
        </tr>
        <tr>
            <td colspan="3">
                <table>
                    <tr>
                        <td width="94%">
                        1. Haber sido sancionado(a) por la comisión de falta o infracción grave o muy grave, de carácter disciplinario o funcional, en la Contraloría General de la República, ni estar en el Registro Nacional de Sanciones contra Servidores Civiles
                        </td>
                        <td width="6%" align="center">{{$datos->preg_1}}</td>
                    </tr>
                    <tr>
                        <td>2. Ser funcionario de confianza</td>
                        <td align="center">{{$datos->preg_2}}</td>
                    </tr>
                    <tr>
                        <td>3. Mantener deudas actualmente exigibles con la Escuela Nacional de Control </td>
                        <td align="center">{{$datos->preg_3}}</td>
                    </tr>
                    <tr>
                        <td>
                        4. Haber sido sentenciado (a) por incumplimiento a la asistencia alimentaria, ni figurar en el Registro de Deudores Alimentarios Morosos - REDAM</td>
                        <td align="center">{{$datos->preg_4}}</td>
                    </tr>
                    <tr>
                        <td>
                        5. Haber sido condenado (a) por delito doloso con sentencia de autoridad de cosa juzgada, ni registrar antecedentes policiales, judiciales ni penales vigentes</td>
                        <td align="center">{{$datos->preg_5}}</td>
                    </tr>
                    <tr>
                        <td>
                        6. Haber sido desaprobado en alguna de las actividades académicas impartidas por la ENC, en la que haya sido beneficiado con una beca en el año en curso. </td>
                        <td align="center">{{$datos->preg_6}}</td>
                    </tr>
                    
                    


                </table>
            </td>
            
        </tr>
        <tr>
            <td colspan="3" style="text-align: justify;">
                    Formulo la presente declaración en virtud del Principio de Presunción de Veracidad previsto en el numeral 1.7 del artículo IV del Título Preliminar y el artículo 49° del TUO de la Ley N° 27444, Ley del Procedimiento Administrativo General, aprobado por Decreto Supremo N° 006-2017-JUS, sometiéndome a la verificación y/o fiscalización posterior que la autoridad administrativa tenga a bien efectuar y, en caso de incurrir en falsedad, a las sanciones administrativas, civiles o penales, correspondientes, conforme a lo dispuesto en el numeral 1.16 del artículo IV del Título Preliminar del TUO de la Ley Nº 27444, firmando la presente en señal de conformidad.
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="text-align:center;padding:10px 0 0 0;margin-top:10px;">
                    {{--  <img src="{{$_SERVER['DOCUMENT_ROOT'].'/images/form/a/logo_1613684921.png'}}" width="100" height="100" alt="{{$datos->dni_doc}}" />--}}  
                    <img src='{{$_SERVER['DOCUMENT_ROOT']."/storage/ddjj-firmas/$datos->firma"}}' width="200" height="100" alt="{{$datos->dni_doc}}" />

                    {{-- <img src="{{public_path('storage/ddjj-firmas/firma-11111111-186.JPG')}}" alt="firma" width="200" style="height: auto;"> --}}
                    {{-- test<img src="{{url('storage/ddjj-firmas/firma-11111111-186.JPG')}}" alt="firma" width="200" style="height: auto;" --}}
                </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
