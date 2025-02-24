{{-- {!! $html !!} --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
</head>
<body>
<table align="center" width="650" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="201" align="center"><a href="https://www.enc.edu.pe" target="_blank"><img src="https://enc-ticketing.org/comunicaciones/enconocimiento/conferencias_magistrales/liderazgo_comportamiento/img/logo_Escuela.jpg" alt="" width="152" height="75" border="0" /></a></td>
      <td width="204" align="right"></td>
      <td width="245" align="center"><a href="http://www.contraloria.gob.pe/wps/portal/portalcgrnew/siteweb/inicio/" target="_blank"><img src="https://enc-ticketing.org/comunicaciones/enconocimiento/conferencias_magistrales/liderazgo_comportamiento/img/logo_CGR.jpg" alt="" width="210" height="75" border="0" /></a></td>
    </tr>
  </tbody>
</table>
<table align="center" width="650" bgcolor="white" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="http://enc-ticketing.org/tkt/images/header_mail.jpg" alt="" width="650" height="262" align="right"></td>
  </tr>
  <tr>
    <td height="20px"></td>
  </tr>
  <tr>
    <td style="font-family:arial"><table width="650" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="55">&nbsp;</td>
            <td width="540">
              <p>Sr(a): {{ $nombre }}</p>

              <p style="font-size: 14px; font-family: arial; text-align: justify">

              La Escuela Nacional de Control le informa que ha entrado en vigencia el Decreto Legislativo 1390. En ese sentido, para seguir informándole acerca de nuestros servicios educativos, requerimos de su consentimiento para enviarle email a su correo electronico: {{ $email }} <br>
                <br>
                <?php 
                  //$encrypted = Crypt::encryptString('Hello world.');

                  //$decrypted = Crypt::decryptString($encrypted);

                  //echo $decrypted;
                ?>
              
              <table align="center" width="480" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr>
                    <td align="center">
                      <a href="http://localhost:8080/tkt_v12.1/public/newsletter/confirmacion/{{ Crypt::encryptString($id) }}" target="_blank">
                        <img src="http://enc-ticketing.org/tkt/images/bot_1.png" alt="" width="220" height="59" /></td>
                      </a>
                    <td>&nbsp;</td>
                    <td align="center"><img src="http://enc-ticketing.org/tkt/images/bot_2.png" alt="" width="220" height="59" /></td>
                  </tr>
                </tbody>
              </table>
              <br>
              <br>
              Agradecemos su comprensión y reafirmamos nuestro compromiso en trabajar por el desarrollo profesional de los funcionarios y servidores públicos<br>
              <br>
              Atentamente,<br>
              <strong>Escuela Nacional de Control </strong>
              </p></td>
            <td width="55">&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
  </tr>
</table>
<table align="center" width="650" bgcolor="white" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20px"></td>
  </tr>
</table>
<table align="center" width="650" bgcolor="#8d8d8d" link="#ffffff" vlink="#ffffff" alink="#ffffff">
  <tr>
    <td bgcolor="#8d8d8d" align="center"><a href="http://www.enc.edu.pe"><img src="https://2.bp.blogspot.com/-dLvYvvhUUWQ/WZMXEpBY-bI/AAAAAAAACuk/7AVUdC--_zcSii_4ulOl_CBJAhr2H8Y2wCLcBGAs/s1600/logo.jpg" target="_blank" width="148" height="83" alt=""></a></td>
    <td bgcolor="#8d8d8d"><a href="https://www.google.com.pe/maps/place/Escuela+Nacional+de+Control/@-12.0812803,-77.0343429,15z/data=!4m2!3m1!1s0x0:0xff3e7f7ba88db20a?sa=X&ved=0ahUKEwjv2oPDqrjSAhVJ32MKHe8bCRoQ_BIIbjAK" target="_blank"> <font size="1" face="Arial" color="#ffffff"> Jr. Bartolomé Herrera #255<br>
      Lince<br>
      Lima - Perú </font> </a></td>
    <td bgcolor="#8d8d8d"><font size="1" face="Arial" color="#ffffff"> informes@enc.edu.pe<br>
      Central Telefónica +511 200 8430<br>
      Anexos: 5539 - 5540<br>
      Horarios: L-V 8:30 am. - 5:30 pm. </font></td>
    <td bgcolor="#8d8d8d"><a href="https://www.facebook.com/Escuela-Nacional-de-Control-245321429240557/" target="_blank"><img src="https://3.bp.blogspot.com/-OsguZW860k4/WZMXC0JaVLI/AAAAAAAACuU/duyYBV_BYmwn0MvZvT4Nsb8Y-vZa6wgWgCLcBGAs/s1600/face.jpg" width="30" height="30" target="_blank" border="0" alt=""></a> <a href="https://twitter.com/ENCContraloria" target="_blank"><img src="https://4.bp.blogspot.com/-U6eXBLl8Qr4/WZMXEzkcyYI/AAAAAAAACuo/0LBWvoGWikwXymjBbQz3K79lNCmr7cdrACLcBGAs/s1600/tw.jpg" target="_blank" width="30" height="30" alt=""></a> <a href="https://www.linkedin.com/company/escuela-nacional-de-control?trk=top_nav_home" target="_blank"><img src="https://1.bp.blogspot.com/-hLHqtgL2spE/WZMXEDAUfLI/AAAAAAAACug/nbeSAx-1b-4ocK7jPZzp-6ZGOQMRopbrgCLcBGAs/s1600/li.jpg" width="30" height="30" target="_blank" border="0" alt=""></a> <a href="https://www.youtube.com/channel/UCm_JIlna83tVXmZsxrQ0f6Q" target="_blank"><img src="https://3.bp.blogspot.com/-P1YAs8-LNkE/WZMXFAh4Z6I/AAAAAAAACus/JraOjQ6RywUdRIYlbl190IYDwsSi-KtagCLcBGAs/s1600/yt.jpg" width="30" height="30" target="_blank" border="0" alt=""></a> <a href="https://www.flickr.com/photos/147606161@N05/" target="_blank"><img src="https://1.bp.blogspot.com/-gyV_QcwfWfk/WZMXDO_lp-I/AAAAAAAACuY/8NY4uFT5Qyg8ZvyASdMUx2qTFhidLEF5QCLcBGAs/s1600/fl.jpg" width="30" height="30" target="_blank" border="0" alt=""></a></td>
  </tr>
</table>
</body>
</html>