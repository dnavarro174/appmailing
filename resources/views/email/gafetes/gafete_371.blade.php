<meta charset="utf-8">
      <style>
      @page{margin:0}
      @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap');

      body{
         font-size: 12px;
         /* font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; */
         font-family: 'Montserrat', sans-serif;
         color: #444;
         padding:0px;margin:0px;
      }
      .container{
         width:100%;
         max-width:400px;
      }
         .capa_2{text-align:center;padding:20px 0;}
         .capa_2 h2{
            color:#AB101A;
            font-size:12px;
            text-transform:uppercase;
            padding:0; margin:0;
         }
         .capa_2 h4{
            font-size:13px;
            color:#4A4544;padding:0; margin:0;margin-top:15px;
         }
         .bg_red{background:#B12B32;}
         .capa_3{
            /* display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap:20px; */
            width: 100%;
            display: block;
            
         }
         .capa_3 table{
            margin:0 auto;
            color:white;
         }
         table td p.text{
            font-size:10px;

         }
         .capa_3 table td{ text-align:left;}
         .columna{ color:white; padding:8px 20px; 
            
            /* display: flex;
            flex-direction: column;
            justify-content: space-between; */
         }
         .capa_3 table td p.img{text-align:center;}
         .col-medio{ border-right:1px solid #f99191e3;border-left:1px solid #f99191e3;padding:0 20px;}

         .datos {
            padding:20px;
         }
         .datos p{padding:0;margin:0;}
         .datos .tit{ color:#625F5C;}
         .datos .nombres{
            color:#AC111B;
            font-weight:bold;
            text-transform:uppercase;
         }
         .datos .qr{
            margin:10px 0;
         }
         .datos .center {
            text-align:center;
         }
         
      </style>
   
   
      <div class="container img_footer">
         <div class="capa_1 bg_red"><!-- LOGO HEADER -->
           <img src="https://enc-ticketing.org/comunicaciones/2024/Recursos/CabeceraGafeteA.jpg" alt="logo header" width="315">
         </div>
         <div class="capa_2">
            <h2>Responsabilidad Penal, Civil y Administrativa en el Control Gubernamental</h2>

            <h4 style="font-size: 8px;">
               LUGAR: <strong>Auditorio de la Escuela Nacional de Control<br> 
               Bartolomé Herrera 255 Lince</strong>
           </h4>
        </div>
         <div class="capa_3 bg_red">
            <table style="text-align:center;">
               <tbody><tr>
                 <td>
                   <p class="img">
                        <img src="https://enc-ticketing.org/comunicaciones/encomunicacion/EVENTOS_2022/12.diciembre/observatorio/gafete/ico_1.png" width="40" height="40" alt="icono fecha">
                    </p> 
                    <p class="text">
                     FECHA:<br><strong>22 de noviembre</strong>
                    </p>
                  </td>
                 <td>
                     <p class="img">
                        <img src="https://enc-ticketing.org/comunicaciones/encomunicacion/EVENTOS_2022/12.diciembre/observatorio/gafete/ico_2.png" width="40" height="40" alt="icono fecha">
                     </p>
                     <p class="text">
                     HORA:<br>
                     <strong>09:00 hrs.</strong>
                     </p>
                  </td>
               </tr>
            </tbody></table>
            
         </div>
         <div class="capa_4">
            <div class="datos">
               <p class="tit">Participante</p>
               <p class="nombres">{{$nombresG}} {{$apellidosG}}</p>
               <p class="qr center">
                 <img class="code_qr" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->margin(.5)->generate($codigoG)) }} ">
                  <!-- <img  class="code_qr ml-4" src="https://enc-ticketing.org/images/g/qr.png" />  -->
               </p>
               <p class="identificador tit center">Identificador: <strong>{{$codigoG}}</strong></p>
            </div>
         </div>
         <!-- linea pie de pagina -->
         <div class="capa_4">
           <img src="https://enc-ticketing.org/comunicaciones/encomunicacion/EVENTOS_2022/12.diciembre/observatorio/gafete/fondo.png" alt="fondo" width="315">
         </div>
         <!-- linea pie de pagina -->
           
      </div>