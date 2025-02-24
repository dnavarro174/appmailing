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
         .div2{margin:auto;margin-left:4.4cm;border:1px dashed #444;width: 12cm;height: 15cm; overflow: hidden;}
         .table_top{width: 800px;}
         .table{width: 12cm;}/*height: 18cm;*/
         .ml-4{margin-left: 20px;}.mr-4{}
         .left{padding-left:15px;}
         .right{padding-right:15px;}
         .nombre_g div.h2{margin: 0 0 10px 21px;padding: 0;font-weight: 500;}
         .nombre,.apellido{color:#232323;font-size: 32px;font-family: Arial,Helvetica Neue, Helvetica, sans-serif;}
         .code_qr{margin: 0 18px 0 0 ;}
         .foro_1,.foro_2{font-size: 10px;}
         .foro_1{vertical-align: top;padding-bottom:2px;color:#E22734;width: 18%;padding-left: 20px;padding-right: 4px;}
         .foro_2{white-space: inherit; text-align: justify; padding:0 25px 5px 0;width: 82%;color: #232323;}
         .titulo_act {text-align: left;}
         .titulo_act span{text-align: left;margin: 5px 0 3px 20px;display: block;}
         .titulo_top strong{color:#C13139;}
         .mt-4{display: block;padding-top: 2px;}
         .identificador{font-size: 16px;color:#666;margin-left:21px;}
         .fecha_txt{font-size: 13px;text-align: center;color:#E22734;padding: 3px 0;font-weight: bold;box-sizing: border-box;}
         .img_footer{background: url("https://enc-ticketing.org/img_caii/Credencial-footer.jpg")  no-repeat 88px 523px;position: relative;}
         .img_footer{text-align: center;}
         .center{text-align: center;}
         .logo_text{font-size: 10px;text-align: center;padding: 0;margin:0;}
         .act{padding-left: 24px;color:#232323;font-size: 15px;margin:0 15px 15px;font-weight: bold;padding-bottom: 5px;text-align: left;}
         .text_small{padding-left: 24px;color:#666;font-size: 12px;text-align: left;padding-bottom: 5px;}
         
      </style>
   </head>
   <body>
      <div class="container">
         <br>
         <div style="margin-left: 50px;">
            <table>
               <tr>
                  <td><img src="https://enc-ticketing.org/images/img-gafete/printer.png" height="50" width="50" /></td>
                  <td width="20"> </td>
                  <td class="">
                     <div>
                        <span class="titulo_top"><strong>IMPRIMA </strong>su gafete para ingresar a Conferencia Anual Internacional por la Integridad<br> - CAII 2023</span>
                     </div>
                  </td>
               </tr>
            </table>
         </div>
         <br>
         <div style="margin-left: 50px;">
            <span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">1. Imprima este documento en una hoja A4 y recorte su GAFETE por la línea de los puntos.</span><br>
            <span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">2. Preséntelo junto con su DNI para acreditarse y obtener su constancia de participación.</span><br>
            <span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">3. Al momento de registrar su ingreso le entregarán un portagafete.</span><br>
            <span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">4. Deberá llevar durante todo el evento su gafete de forma visible.</span>
         </div>
         <br>
         <br>
         <div style="margin-left: 50px;"><img src="https://enc-ticketing.org/images/img-gafete/cut.png" height="50" width="50" style="margin-top: 10px;" /></div>
         <br>
         <div class="div2 img_footer">
            <div class="div1">
               <table border="0" cellpadding="0" cellspacing="0" class="table">
                  <tbody>
                     <tr>
                        <td colspan="3">
                           <img src="https://www.enc-ticketing.org/img_caii/header_pantallazo_caii.jpg" alt="cabecera" style="position: relative;z-index: 2;width: 12cm;margin-top:10px;" />
                        </td>
                     </tr>
                     
                     <tr> <td colspan="3"></td> </tr>
                     <tr>
                        <td colspan="2" align="left" class="nombre_g" width="600">
                           <span style="font-size: 30px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;margin-left:21px;color:#232323;"><?=($nombresG)?></span>
                           <div class="h2" style="text-align: left;">
                              <span class="apellido">
                                 <strong><?=($apellidosG)?></strong>
                              </span>
                           </div>
                           
                           <span id="lbl_Indentificador" class="identificador">PARTICIPANTE</span>
                        </td>

                        <td width="100" style="vertical-align: middle;" align="right">
                           <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(120)->margin(.5)->generate($codigoG)) }} " class="code_qr mr-4" /><br>
                           <span style="text-align: center;font-weight:bold;display: block;margin-right: 4px;"><?=$codigoG?></span>
                           <!-- <img  class="code_qr mr-4" src="http://enc-ticketing.org/tkt/caii/img-gafete/qr.png" /> -->
                        </td>
                        
                     </tr>
                     <tr> <td colspan="3"><br /> </td> </tr>
                     <!-- <tr>
                        <td colspan="3">
                           <table class="table">
                              <tr>
                                 <td class="foro_1">
                                    <strong class="color_oscuro">
                                       11:15 HRS.
                                    </strong> 
                                 </td>
                                 <td class="foro_2">
                                    <strong>PANEL  1:</strong> 
                                       Sentando  las  bases  para  disuadir  y  detectar  casos  de  corrupción  –  Datos  abiertos,  Analítica 
                                 </td>
                              </tr>
                              <tr> 
                                 <td class="foro_1">
                                    <strong class="color_oscuro">
                                       14:45 HRS.
                                    </strong> 
                                 </td>
                                 <td class="foro_2">
                                    <strong>PANEL  3:</strong> 
                                         Análisis  de  inteligencia  para  detectar:  fundamentos  desde  la  psicología
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr> -->
                    <tr> 
                        <td colspan="3" class="act">Mis actividades específicas<br /></td> 
                    </tr>

                    <tr>
                        <td class="foro_1">
                              <strong class="color_oscuro">
                                 08:00 HRS.
                              </strong> 
                        </td>
                        <td class="foro_2" colspan="2">
                              <strong>Registro de participantes</strong> <br>
                              Ingreso a conferencias magistrales
                        </td>
                  </tr>

                     
                     <!-- BEGIN CODIGO -->
                     <?php  foreach($foros as $i=>$d){?>
                        <tr>
                           <td align="center" colspan="3" class="fecha_txt">
                              <strong>
                              <?php $date=date_create($d["fecha_desde"]);
                              echo date_format($date, "d/m/Y");
                              ?>
                              </strong> 
                           </td>
                        </tr>
                        <?php foreach($d["horas"] as $j=>$d2){ ?>
                        <tr> 
                           <td class="foro_1">
                              <strong class="color_oscuro">
                                 {{ $d2["hora_inicio"] }} hrs.
                              </strong> 
                           </td>
                           <td class="foro_2" colspan="2">
                              <strong>{{$d2["titulo"] }}</strong> 
                               {{$d2["subtitulo"]}}
                           </td>
                        </tr>
                     <?php } } ?>
                     <!-- FIN CODIGO -->
                     <!-- <tr>
                        <td colspan="3" class="center">
                           <img src="https://enc-ticketing.org/img_caii/Credencial-footer.jpg" alt="img cgr" width="200" class="img_footer"><br>
                           <p class="logo_text">Conferencia Anual Internacional por la Integridad</p>
                        </td>
                     </tr> -->
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </body>
</html>