<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Compromiso</title>
    <style>

        body{
        font-size: 11px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        padding:0px;margin:0px;
        
        }
        h1{font-size: 14px;font-weight: bold;}
        .right{text-align: right;}
        .center{text-align: center;}
        table,th,td{border:1px solid black;padding: 0;border-spacing: 0;border-collapse: collapse;}
        table td {padding:2px 6px;height: 25px;}
        table td p{padding:0;margin:0;}
        div.container{width:670px;margin:3% auto;padding:0 15px;box-sizing: border-box;}/*  */
        
        span.title{font-size:14px;}
        table table.table td p{
            white-space:pre-line !important;
        }
        ol{padding-left: 16px;}
        ol, p{text-align: justify;font-size: 11px;line-height: 13px;}
        ol li{line-height: 18px;list-style:none;}
        .parrafo, p{
            font-size: 11px;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            letter-spacing:0.5px;line-height: 16px;
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
            color: #111;
        }
        div .h1{color:black; font-size: 14px;margin: 6px 0 3px;}
        .bg-red{background-color: rgb(173, 5, 5);}
        .bg-red p{color:white;}
        .padd-left-65{padding-left: 65px;}
        .padd-left-15{padding-left: 15px;}
        .div_footer{
            display: block;
            width: 100%;
            position: relative;
            margin-left: 0%;
            font-size: 10px;
        }
        .div_footer p{font-size: 10px;}
        .linea-top{
            width: 60%;
            font-weight: bold;
            margin-top: 20px;
            margin-left: 0%;
            padding-top: 12px;
            border: none;
            border-top: 2px solid black;
        }
        .tab_pdf{
            width: 670px;
            padding: 15px;
            display: block;
            border: none;
        }
        .mt-25{margin-top: 25px;}
        .mt-50{margin-top: 50px;}
        .mx-2{margin-left: 25px;margin-right: 25px;}
        .page-break{page-break-after: always;}
        .raya{text-decoration:underline;}
    </style>
</head>
<body>
<div class="container">
    
    
    <div class="mx-2">
        
        @include('layout.pdf.header')
    
    <h1 class="center">CARTA DE COMPROMISOS COMO BENEFICIARIA/O DE CAPACITACI&Oacute;N</h1>
    <p class="parrafo">Por medio del presente se deja constancia que la/el suscrita/o participa de la acci&oacute;n de capacitaci&oacute;n programada por la Contralor&iacute;a General de la Rep&uacute;blica (CGR) a trav&eacute;s de la Subgerencia de Pol&iacute;ticas y Desarrollo Humano - POLDEH, seg&uacute;n lo siguiente:</p>
    
    <table border="1">{{-- width="670" --}}
    <tbody>
    <tr>
    <td width="170" class="bg-red">
    <p>Apellidos y Nombres</p>
    </td>
    <td width="270" colspan="2">
    <p>{{$datos->ap_paterno}} {{$datos->ap_materno}}, {{$datos->nombres}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Documento de identidad</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->dni_doc}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Categor&iacute;a/Puesto</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->categoria}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>&Oacute;rgano o Unidad Org&aacute;nica</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->unidad_organica}}</p>{{-- &Oacute;RGANOS DE CONTROL INSTITUCIONAL --}}
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Tipo de capacitaci&oacute;n de formaci&oacute;n laboral</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->tpo_capa}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Nombre de la Capacitaci&oacute;n</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->curso->nom_curso}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Proveedor de Capacitaci&oacute;n</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->provee_capa}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Fecha de inicio</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->fech_ini}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Fecha fin</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->fech_fin}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>N&uacute;mero de horas de la capacitaci&oacute;n</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->horas}} {{-- HORAS CRONOL&Oacute;GICAS --}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red" rowspan="2">
    <p>Financiamiento de la Capacitaci&oacute;n</p>
    </td>
    <td width="200">
    <p>COSTOS DIRECTOS</p>
    </td>
    <td class="right">
    <p class="right">S/ @if(is_numeric($datos->cto_directo)){{number_format($datos->cto_directo,2)}} @endif</p>
    </td>
    </tr>
    <tr>
    <!-- combine una fila -->
    <td>
    <p>COSTOS INDIRECTOS</p>
    </td>
    <td class="right">
    <p class="right">S/ @if(is_numeric($datos->cto_indirecto)){{number_format($datos->cto_indirecto,2)}} @endif</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red ">
    <p>Valor de la Capacitaci&oacute;n[1]</p>
    </td>
    <td  colspan="2">
    <p class="right">S/ {{number_format($datos->valor_capa,2)}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Tiempo de Permanencia[2]</p>
    </td>
    <td  colspan="2">
    <p>@if($datos->time_perma!=""){{round($datos->time_perma)}} DÍAS CALENDARIOS @endif</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Materia de Capacitaci&oacute;n</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->materia_capa}}</p>
    </td>
    </tr>
    <tr>
    <td class="bg-red">
    <p>Modalidad</p>
    </td>
    <td  colspan="2">
    <p>{{$datos->modalidad}}</p>
    </td>
    </tr>
    </tbody>
    </table>

    <p>Que, de acuerdo a lo dispuesto en los numerales 6.4.2.3 y 6.4.2.4 de la <strong>Directiva "Normas para la Gesti&oacute;n del Proceso de Capacitaci&oacute;n en las Entidades P&uacute;blicas", </strong>aprobada por Resoluci&oacute;n de Presidencia Ejecutiva N&deg; 141-2016-SERVIR-PE, me comprometo a suscribir la presente carta y con ello asumir los compromisos, penales y consideraciones que se detallan a continuaci&oacute;n:</p>
    <p><strong>Compromisos que asumo como beneficiaria/o de capacitaci&oacute;n:</strong></p>
    <ol>
        <li>a) Permanecer en la CGR el tiempo establecido o devolver el &iacute;ntegro del valor de la capacitaci&oacute;n se&ntilde;alada o, en caso corresponda, el remanente de dicho valor.</li>
        <li>b) Aprobar o cumplir con la calificaci&oacute;n m&iacute;nima determinada por la POLDEH o establecida por el proveedor de capacitaci&oacute;n. Asimismo, cuando la acci&oacute;n de capacitaci&oacute;n no demande una calificaci&oacute;n, el/la beneficiario/a de capacitaci&oacute;n debe acreditar su asistencia con el documento correspondiente.</li>
    </ol>

    <div class="div_footer">
        <hr class="linea-top">
        <p>[1] El monto del valor de la capacitación, será calculado en función a lo establecido en el numeral 6.4.2.3. de la Directiva "Normas para la Gestión del Proceso de Capacitación en las Entidades Públicas"
        </p>
        <p>[2] El tiempo de permanencia, será calculado en función a lo establecido en el numeral 6.4.2.3. de la Directiva "Normas para la Gestión del Proceso de Capacitación en las Entidades Públicas"</p>
    </div>

    <div class="page-break"></div>

    <div class="parrafo">
        <ol class="mt-50">
            <li>c) Cumplir con los requerimientos de evaluaci&oacute;n de la capacitaci&oacute;n que sean se&ntilde;alados por la POLDEH.</li>
            <li>d) Transmitir los conocimientos adquiridos a otros/as colaboradores/as de la entidad, cuando lo solicite la CGR, en el plazo m&aacute;ximo de tres (03) meses calendario.</li>
        </ol>
        <p>Las obligaciones generadas por los compromisos descritos, se empiezan a computar desde el d&iacute;a h&aacute;bil siguiente de concluida la acci&oacute;n de capacitaci&oacute;n, o la comisi&oacute;n de servicios o licencia otorgada, cuando corresponda. Estas obligaciones son factibles de postergarlas &uacute;nicamente por causas justificadas o periodo de vacaciones del/la colaborador/a, seg&uacute;n lo previsto en el art&iacute;culo 19 del Reglamento General de la Ley N&deg; 30057, Ley del Servicio Civil.</p>
        <p><strong>Penalidades por incumplimiento de los compromisos se&ntilde;alados:</strong></p>
        <ol>
            <li>e) En caso de incumplimiento al tiempo de permanencia o compromiso a) por causas que me son imputables, devolver&eacute; el valor de la capacitaci&oacute;n o el remanente del valor de la capacitaci&oacute;n, seg&uacute;n corresponda.</li>
            <li>f) En caso de incumplimiento al compromiso b), autorizo expresamente al &oacute;rgano competente, a realizar las acciones respectivas para el cumplimiento de la obligaci&oacute;n, en funci&oacute;n al valor de la capacitaci&oacute;n calculado.</li>
            <li>g) Declaro conocer que, en caso de incumplimiento del compromiso c) del presente documento, no podr&eacute; ser beneficiario de otra acci&oacute;n de capacitaci&oacute;n por el periodo de seis (06) meses luego de culminada la capacitaci&oacute;n. Asimismo, s&eacute; que dicho incumplimiento se registrar&aacute; en mi legajo personal.</li>
            <li>h) Declaro conocer que, en caso de incumplimiento del compromiso del presente documento, se registrara dicho incumplimiento en mi legajo personal.</li>
        </ol>
        <p>&nbsp;</p>
        <p class="right">{{$datos->region}}, <span class="raya">{{date('d')}}</span> de <span class='raya'>{{$meses[\Carbon\Carbon::parse(now())->format('n')-1]}}</span>  de <span class="raya">{{date('Y')}}</span></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p style="text-align:center;padding:0;margin:0;">
            {{-- <img src='{{$_SERVER['DOCUMENT_ROOT']."/storage/ddjj-firmas/$datos->firma"}}' width="200" style="height: auto;" alt="{{$datos->dni_doc}}" /> --}}
            <img src='{{url("")."/storage/ddjj-firmas/$datos->firma"}}' width="200" height="100" alt="{{$datos->dni_doc}}" />
        </p>
        <p class="center" style="margin-top: -10px;"><strong>________________________</strong></p>
        <p class="center"><strong>FIRMA</strong></p>
        <p class="padd-left-15">
            DNI: <span>{{$datos->dni_doc}}</span><br>
            C&Oacute;DIGO: <span>{{$datos->codigo}}</span><br>{{-- codigo --}}
            CORREO PERSONAL: <span>{{$datos->email}}</span><br>
            TEL&Eacute;FONO: <span>{{$datos->celular}}</span>
        </p>
    </div>
 
    </div>
</div>


</body>
</html>