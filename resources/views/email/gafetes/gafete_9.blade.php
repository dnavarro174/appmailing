<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<style>
body{font-size: 14px;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;color: #444; }
.nombre_g h2{font-size: 28px;font-weight: 500;text-align: center;margin: 10px 0 20px 0;padding: 0;}
.identificador{font-size: 18px;color:#666;}
	.divi {width: 350px;}
</style>
</head>

<body>
	
	<table align="center" width="435" border="0">
	<tr>
	<td width="433">
		<table align="center">
					<tr style="margin-bottom: 10px;">
						<td width="50"><img src="http://enc-ticketing.org/tkt/caii/img-gafete/printer.png" height="50" width="50" /></td>
						<td width="20"> </td>
						<td width="350">
							<div class="divi"><span class="titulo_top"><strong>IMPRIMA </strong>su gafete para ingresar a la II Jornada de Fortalecimiento del Conocimiento							para Regidores y Jefes de OCI
							de las Municipalidades Provinciales</span></div>
						</td>
					</tr>
			<tr>
			<td colspan="3" style="margin-bottom: 10px;">
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">1. Imprima este documento en una hoja A4 y recorte su GAFETE por la línea de los puntos.</span><br>
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">2. Preséntelo junto con su DNI para acreditarse y obtener su constancia de participación.</span><br>
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">3. Al momento de registrar su ingreso le entregarán un portagafete.</span><br>
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">4. Deberá llevar durante todo el evento su gafete de forma visible.</span>
				</td>
			</tr>
			<tr>
			<td colspan="3"  style="margin-bottom: 10px;">
				<img src="http://enc-ticketing.org/tkt/caii/img-gafete/cut.png" height="50" width="50" />
				</td>
			</tr>
				</table>
		</td>
	</tr>
<tr>
	<td align="left">
	<table width="439" style="border:1px dashed #444;">
		<tr align="center">
	<td width="433">
	<img src="https://enc-ticketing.org/comunicaciones/enconocimiento/ii_jornada_regidores/1113x372.png" alt="" style="width: 11.5cm">
	</td>
	</tr>
		<tr>
		<td height="136" align="center" class="nombre_g">
								<h2>
									<?=($nombresG)?><br>
									<strong><?=($apellidosG)?><br>
									<?=($apellidosG_2)?></strong>
								</h2>
								<span id="lbl_Indentificador" class="identificador">Identificador: <?=$codigoG?></span>
							  </td>
		</tr>
		<tr align="center">
	<td>
	<img src="https://enc-ticketing.org/comunicaciones/encomunicacion/CAII2019/PARTICIPANTE_GAF.png" width="437" alt="titu" />
	</td>
	</tr>
		<tr>
							  <td align="center" height="120">
							  	<img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(180)->margin(.5)->generate($codigoG)) }} " />
							  </td>
								</tr>
		 <td align="center" style="margin-bottom: 10px;"><font style="font-family:Arial; font-size: 17px; color: #263783"><strong>12 y 13 de DICIEMBRE DE 2019<br>
            </strong></font></td>
</table>
		
</tr>
	</td>
</table>
	
	
</body>
</html>