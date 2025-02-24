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
.div2{margin:auto;margin-left:4.4cm;border:1px dashed #444;width: 10cm;height: 18cm /*width: 290px;height: 550px;*/}
.table_top{width: 800px;}
.table{width: 10cm;height: 18cm;}/* height:524.41px */
.tb_foros{}
.nombre_g h2, .nombre_g span{margin-left: 20px;}
.nombre_g h2{font-size: 20px;font-weight: 500}
.color_oscuro{color:#556685;font-size: 14px;font-weight: bold;text-align: center;text-transform: uppercase;}
/*td{overflow:hidden;white-space:nowrap;text-overflow:ellipsis}*/
.foro_1{white-space: inherit;text-align: center;}
.foro_2{white-space: inherit;}
.titulo_top strong{color:#C13139;}
.identificador{font-size: 16px;}
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
							<span class="titulo_top"><strong>IMPRIMA </strong>su gafete para ingresar a Conferencia Anual Internacional por la<br> Integridad - CAII</span>
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
							  	<img src="http://enc-ticketing.org/tkt/caii/img-gafete/head_logo.png" alt="cabecera" style="position: relative;z-index: 2;width: 377px;" />
							  </td>
							</tr>
							<tr>
							  <td colspan="3"><br />
							  </td>
							</tr>
							<tr>
							  <td style="width: 55%" colspan="2" align="left" class="nombre_g">
								<h2>
									<?=($nombresG)?><br>
									<strong><?=($apellidosG)?></strong><br>
									<strong><?=($apellidosG_2)?></strong>
								</h2>
								<span id="lbl_Indentificador" class="identificador">Identificador: <?=$codigoG?></span>
							  </td>
							  <td style="width: 45%" align="center" valign="middle" style="vertical-align: middle;">
							  	<img src="http://enc-ticketing.org/tkt/caii/img-gafete/qr.png" width="100" height="100" alt="qr" />
							  	<!-- <img src="data:image/png;base64, {{ base64_encode(QrCode::encoding('UTF-8')->format('png')->size(100)->margin(.5)->generate('$codigoG')) }} " /> -->
							  	<br>
							  </td>
							</tr>
							<tr>
							  <td colspan="3"><br /><br />
							  </td>
							</tr>
							<tr>
							  <td colspan="3">
							  	<img src="http://enc-ticketing.org/tkt/caii/img-gafete/line.png" width="377" alt="titu" />
							  </td>
							</tr>
							<tr>
							  <td colspan="3"><br />
							  </td>
							</tr>
							<tr>
						  		<td width="20%" class="foro_1">
						  			<strong class="color_oscuro">06 dic<br> 14:30 hrs.</strong> 
						  		</td>
						  		<td colspan="2" class="foro_2" width="80%"><strong>{{ $foro_1_tit }}:</strong> {{ $foro_1 }} </td>
						  	</tr>
							<tr>
							  <td colspan="3"><br />
							  </td>
							</tr>
							  	<tr>
							  		<td width="20%" class="foro_1">
							  			<strong class="color_oscuro">07 dic<br>14:00 hrs.</strong> 
							  		</td>
							  		<td colspan="2" class="foro_2" width="80%"><strong>{{ $foro_2_tit }}:</strong> {{ $foro_2 }} </td>
								
							  
							</tr>
							<tr>
							  <td colspan="3"><br /><br /><br /><br /><br /><br /><br /><br />
							  </td>
							</tr>
							
							<tr>
							  <td colspan="3" class="full_width" valign="bottom">
							  	<img src="http://enc-ticketing.org/tkt/caii/img-gafete/foot.png" alt="footer" style="position: relative;z-index: 2;width: 377px;"/>
							  	
							  </td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
</div>
</body>
</html>