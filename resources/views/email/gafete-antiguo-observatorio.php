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
         .div2{margin:auto;margin-left:4.4cm;border:1px dashed #444;width: 10cm;height: 18cm; overflow: hidden;}
         .table_top{width: 800px;}
         .table{width: 10cm;}
         .tb_foros{}
         .ml-4{margin-left: 20px;}
         .nombre_g h2{font-size: 20px;font-weight: 500;text-align: center;margin: 0 0 10px 10px;padding: 0;}
         .code_qr{margin: 0 0 0 20px;}
         .foro_1,.foro_2{font-size: 15px;}
         .foro_1{text-align: center;vertical-align: top;padding-bottom:2px;color:#b02930;width: 25%;}
         .foro_2{white-space: inherit; text-align: justify; padding:0 25px 5px 0;width: 75%;}
         .foro_title{white-space: inherit; text-align: center;font-size: 18px;margin-bottom: 15px;width: 100%; }
         .titulo_act {text-align: left;}
         .titulo_act span{text-align: left;margin: 5px 0 3px 20px;display: block;}
         .titulo_top strong{color:#C13139;}
         .identificador{font-size: 17px;color:#666;margin-left: 10px;}
         .fecha_txt{color: #fff;padding: 10px 0 10px 0;background: #b02930;box-sizing: border-box; text-align:center;}
         .img_footer{background: url("https://enc-ticketing.org/images/img-gafete/gafete/footer.jpg")  no-repeat 0px 626px;position: relative;}
      </style>
   </head>
   <body>
      <div class="container">
         <br>
         <div style="margin-left: 50px;">
            <table>
               <tr>
                 <td><img src="https://enc-ticketing.org/comunicaciones/encomunicacion/EVENTOS_2022/12.diciembre/observatorio/logos/ico_descarga.png" height="50" width="50" /></td>
                  <td align="left" class="">
                     <span class="titulo_top"><strong>Descarga </strong>tu gafete virtual para ingresar al Lanzamiento del Observatorio Anticorrupción<br> de la CGR Perú Foro: “La corrupción bajo la lupa”</span>
                     
                  </td>
               </tr>
            </table>
         </div>
         <br>
         <div style="margin-left: 50px;">
            <span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">1. Descarga este Gafete Virtual y muestralo desde tu celular.</span><br>

            <span style="font-size: 14px; font-family: Arial,Helvetica Neue, Helvetica, sans-serif;">2. Preséntelo junto con tu DNI para acreditarse y obtener su constancia de participación.</span>
         </div>
         <br>
         <br>
         
         <br>
         <div class="div2 img_footer" style="align-content: center">
            <div class="div1">
               <table border="0" cellpadding="0" cellspacing="0" class="table">
                  <tbody>
                     
                     <tr>
                        <td colspan="3">
                           <img src="https://enc-ticketing.org/images/img-gafete/gafete/header.jpg" alt="cabecera" style="position: relative;z-index: 2;width: 377px;" />
                        </td>
                     </tr>
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
                     <tr> <td colspan="3"><br /> </td> </tr>

                        <?php foreach($d["horas"] as $j=>$d2){ ?>
                        <tr> 
                           
                           <td class="foro_title" colspan="3">
                              <br><strong>{{$d2["titulo"] }}</strong><br>
                           </td>
                        </tr>
                        <tr> <td colspan="3"><br /> </td> </tr>
                        <tr> 
                           <td class="foro_1">
                              <strong class="color_oscuro">
                                 {{ $d2["hora_inicio"] }} hrs.
                              </strong> 
                           </td>
                           <td class="foro_2" colspan="2">
                              
                               {{$d2["subtitulo"]}}<br><br>
                           </td>
                        </tr>
                        <?php } } ?>
                     <!-- FIN CODIGO -->

                     
                     <tr>
                        <td colspan="3">
                           <img src="https://enc-ticketing.org/images/img-gafete/gafete/subheader.jpg" alt="titu" style="position: relative;z-index: 2;width: 377px;" />
                        </td>
                     </tr>
                     <tr> <td colspan="3"><br /> </td> </tr>
                     <tr>
                        <td style="vertical-align: middle;" align="center">
                           <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(120)->margin(.5)->generate($codigoG)) }} " class="code_qr ml-4" />
                           <!-- <img  class="code_qr ml-4" src="http://enc-ticketing.org/tkt/caii/img-gafete/qr.png" /> -->
                        </td>
                        <td colspan="2" align="left" class="nombre_g">
                           <h2 style="text-align: left;">
                              <?=($nombresG)?><br>
                           <strong><?=($apellidosG)?><br>
                           <?=($apellidosG_2)?></strong>
                           </h2>
                           <span id="lbl_Indentificador" class="identificador">Identificador: <?=$codigoG?></span>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </body>
</html>