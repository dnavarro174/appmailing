<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body{}
        table {
            border-collapse: collapse;
        }
        /*td{overflow:hidden;white-space:nowrap;text-overflow:ellipsis}*/
        div.container{width:700px;margin:0 2%;padding-left:15px;}
        h1{font-size:20px;color:#666;}
        table.table{width:720px;color:#999;font-family:arial;border:1px solid #666;}
        table.table td{padding:4px 6px;white-space:pre-line !important; vertical-align: top;}
        table.table td span{font-weight:700;color:#333;}
        span.title{font-size:18px;}
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
            font-size: 14px;
            color: #999;
        }
        div .h1{color:orange;font-family: calibri; font-size: 25px;}
    </style>
</head>
<body>
<div class="container">
    <br>
    <h1 class="h1">DECLARACIÓN JURADA fff</h1>

    <table style="" class="print_table table" border="0" align="center">
        <tr>
            <td ><span class="title"><strong>I. Datos Personales</strong></span></td>
            <td ></td>
            <td ></td>
        </tr>
        <tr>
            <td ><span>Código Curso</span></td>
            <td colspan="2" style="white-space: pre-line !important;"><span>Nombre Curso</span></td>
        </tr>
        <tr>
            <td>{{$datos->cod_curso}}</td>
            <td colspan="2" style="white-space: pre-line !important;">
            {{$datos->nom_curso}}
            <!-- SISTEMA ELECTRÓNICO DE CONTRATACIONES DEL<br> ESTADO (SEACE) Y CONTRATACIONES ELECTRÓNICAS -->
            </td>

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
            <td><span>Correo Electrónico Institucional</span></td>
            <td><span>Correo Electrónico Personal</span></td>
            <td><span>Celular</span></td>
        </tr>
        <tr>
            <td>{{$datos->email}}</td>
            <td>{{$datos->email_labor}}</td>
            <td>{{$datos->celular}}</td>
        </tr>
        <tr>
            <td><span>En condición de colaborador (a) en:</span></td>
            <td colspan="2"><span></span></td>

        </tr>
        <tr>
            <td>{{$datos->dgrupo}}</td>
            <td colspan="2">{{$datos->organizacion}}</td>

        </tr>
        <tr>
            <td><span>cargo</span></td>
            <td><span>Nivel</span></td>
            <td><span></span></td>
        </tr>
        <tr>
            <td>{{$datos->cargo}}</td>
            <td>{{$datos->entidad}}</td>
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
                $f_ini = strtotime($datos->fecha_inicio);
                $f_fin = strtotime($datos->fecha_fin);
            @endphp
            <td>{{date("d/m/Y", $f_ini)}}</td>
            <td>{{date("d/m/Y", $f_fin)}}</td>
        </tr>
        <tr>
            <td colspan="3"><span></span></td>

        </tr>
        <tr>
            <td colspan="3"><span><strong>DECLARO BAJO JURAMENTO LO SIGUIENTE:</strong></span></td>
        </tr>
        <tr>
            <td colspan="3">

                <table >
                    <tr>
                        <td width="90%">

                            1. Haber sido sancionado (a) por la comisión de falta o infracción grave o muy grave, de carácter<br>
                            disciplinario o funcional, en la Contraloría General de la República, ni estar en el Registro Nacional<br>
                            de Sanciones contra Servidores Civiles

                        </td>
                        <td width="10%">{{$datos->preg_1}}</td>
                    </tr>
                    <tr>
                        <td>2. Ser funcionario de confianza</td>
                        <td>{{$datos->preg_2}}</td>
                    </tr>
                    <tr>
                        <td>3. Mantener deudas actualmente exigibles con la Escuela Nacional de Control </td>
                        <td>{{$datos->preg_3}}</td>
                    </tr>
                    <tr>
                        <td>
                            4. Haber sido sentenciado (a) por incumplimiento a la asistencia alimentaria, ni figurar en el Registro<br>
                            de Deudores Alimentarios Morosos - REDAM</td>
                        <td>{{$datos->preg_4}}</td>
                    </tr>
                    <tr>
                        <td>
                            5. Haber sido condenado (a) por delito doloso con sentencia de autoridad de cosa juzgada, ni registrar<br> antecedentes
                            policiales, judiciales ni penales vigentes</td>
                        <td>{{$datos->preg_5}}</td>
                    </tr>
                    <tr>
                        <td>
                            6.	Haber sido desaprobado en alguna de las actividades académicas impartidas por la ENC, en la<br>
                            que haya sido beneficiado con una beca en el último año.</td>
                        <td>{{$datos->preg_6}}</td>
                    </tr>
                    <tr>
                        <td>
                            <p>
                                Formulo la presente declaración en virtud del Principio de Presunción de Veracidad previsto en el numeral 1.7 del <br>
                                artículo IV del Título Preliminar y el artículo 49° del TUO de la Ley N° 27444, Ley del Procedimiento Administrativo <br>
                                General, aprobado por Decreto Supremo N° 006-2017-JUS, sometiéndome a la verificación y/o fiscalización posterior<br>
                                que la autoridad administrativa tenga a bien efectuar y, en caso de incurrir en falsedad, a las sanciones administrativas,<br>
                                civiles o penales, correspondientes, conforme a lo dispuesto en el numeral 1.16 del artículo IV del Título <br>
                                Preliminar del TUO de la Ley Nº 27444, firmando la presente en señal de conformidad.

                            </p>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
