<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
<style>
body{font-size: 14px;font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;color: #444; }
.container{width: 800px;margin:0 auto;}
#col1{float:left;width:50%;padding:15px}
#col2{float:left;width:50%;padding:15px}
.row:after{content:"";display:table;clear:both}
/*.div1{width:5cm;height:5cm;padding:0;margin:0}
.div2{margin:auto;width:12mm;height:136mm;padding:0 -4px 0 0;border:1px dashed #444}*/
.div2{margin:auto;margin-left:4.4cm;border:1px dashed #444;width: 11cm;height: 14cm; /*width: 290px;height: 550px;*/}
.table_top{width: 800px;}
.table{width: 11cm;height: 14cm;}/* height:524.41px */
.tb_foros{}

.nombre_g h2{font-size: 28px;font-weight: 500;text-align: center;margin: 10px 0 20px 0;padding: 0;}
.color_oscuro{color:#556685;font-size: 14px;font-weight: bold;text-align: center;text-transform: uppercase;}
/*td{overflow:hidden;white-space:nowrap;text-overflow:ellipsis}*/
.foro_1{white-space: inherit;text-align: center;}
.foro_2{white-space: inherit;}
.titulo_top strong{color:#C13139;}
.identificador{font-size: 18px;color:#666;}
</style>
</head>
<body>
<div class="container">
			<br>
			<div style="margin-left: 50px;">
				<table>
					<tr>
						<td><img src="http://enc-ticketing.org/tkt/caii/img-gafete/printer.png" height="50" width="50" /></td>
						<td width="20"> </td>
						<td class="">
							<span class="titulo_top"><strong>IMPRIMA </strong>su gafete para ingresar a la Jornada de Capacitación a Autoridades <br>
							 Virtualmente Electas.</span>

							 <!-- la Jornada de Capacitación a Autoridades Virtualmente Electas -->

						</td>
					</tr>
				</table>
			</div><br>
					
			<div style="margin-left: 50px;">
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">1. Imprima este documento en una hoja A4 y recorte su GAFETE por la línea de los puntos.</span><br>
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">2. Preséntelo junto con su DNI para acreditarse y obtener su constancia de participación.</span><br>
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">3. Al momento de registrar su ingreso le entregarán un portagafete.</span><br>
				<span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">4. Deberá llevar durante todo el evento su gafete de forma visible.</span>
			</div>

			<br>
			<br><div style="margin-left: 50px;"><img src="http://enc-ticketing.org/tkt/caii/img-gafete/cut.png" height="50" width="50" /></div><br>

				<div class="div2">
					<div class="div1">
			 			<table width="290" border="0" cellpadding="0" cellspacing="0" class="table">
						  <tbody>
							<tr>
							  <td colspan="3" >
							  	<img src="http://enc-ticketing.org/tktv2/public/images/gafetes/header_gafete.jpg" alt="cabecera" style="position: relative;z-index: 2;width: 415px;" />
							  </td>
							</tr>
							<tr>
							  <td colspan="3">
							  </td>
							</tr>
							<tr>
							  <td colspan="3" align="center">
							  	<img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(120)->margin(.5)->generate($codigoG)) }} " />
							  </td>
							</tr>
							<tr>
							  <td colspan="3"><br />
							  </td>
							</tr>
							<tr>
							  <td colspan="3" align="center" class="nombre_g">
								<h2>
									<?=($nombresG)?><br>
									<strong><?=($apellidosG)?> 
									<?=($apellidosG_2)?></strong>
								</h2>
								<span id="lbl_Indentificador" class="identificador">Identificador: <?=$codigoG?></span><br>
								<span id="lbl_Indentificador" class="identificador"><?=$grupo?></span>
							  </td>
							</tr>
							<tr>
							  <td colspan="3"><br />
							  </td>
							</tr>
							<tr>
							  <td colspan="3" class="full_width" valign="bottom">
							  	<img src="http://enc-ticketing.org/tktv2/public/images/gafetes/footer_gafete.jpg" alt="footer" style="width: 415px;"/>
							  	
							  </td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
</div>
</body>
</html>