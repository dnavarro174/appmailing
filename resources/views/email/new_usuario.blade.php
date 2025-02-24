<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body{font-family: arial, helvetica;line-height: 26px;}
.text-center{
  text-align: center;
}
.parrafo{
  font-size: 16px; font-family: arial;color:#666;padding: 0 10%;
}
.boton{
  padding: 10px;background: linear-gradient(88deg, #1345b7, #05bdfd);color:white;border:0;border-radius: 6px;text-decoration: none;
}
.boton:hover{background: linear-gradient(88deg, #1345b7, #053b91);}
</style>
</head>
<body>

<table align="center"  width="650" cellspacing="0">
  <tr>
    <td align="center"><img src="http://enc-ticketing.org/tktv2/public/images/backup_enc_2.jpg" alt="Backup Escuela Nacional de Control" width="650" align="right"></td>
  </tr>
  <tr>
    <td height="20px"></td>
  </tr>
  <tr>
    <td height="20px" >
      <h3 style="font-size: 21px; font-family: arial;color:#000;text-align: center;">
        Usuario Creado
      </h3>
      <p class="parrafo">
        Se ha realizado satisfactoriamente la creación del usuario.<br>
        <strong>Usuario:</strong> {{ $user }}<br>
        <strong>Contraseña:</strong> {{ $password }} <br>
        
      </p>
      <p class="text-center">
        <a href="https://www.enc-ticketing.org/login" class="boton">
          Iniciar Sesión
        </a>
        
      </p>

    </td>
  </tr>
  <tr>
    <td height="20px"></td>
  </tr>
  <tr>
    <td height="20px"></td>
  </tr>
</table>


<table align="center" width="650" border="0" cellspacing="0" cellpadding="0" bgcolor="#eaeaea" link="#ffffff" vlink="#ffffff" alink="#ffffff">
      <tbody><tr>
    <td width="32"></td>
    <td width="261" style="border-top: solid 1px #fff"><img src="https://enc-ticketing.org/tktv2/public/images/img_planhtml/copy_ENC.png" target="_blank" width="261" height="39" alt=""></td>
    <td width="153" style="border-top: solid 1px #fff" align="center"><a href="http://www.enc.edu.pe/" target="_blank"><img src="https://enc-ticketing.org/tktv2/public/images/img_planhtml/logo_ENC.jpg" alt="" width="120" height="71"></a></td>
    <td width="182" style="border-top: solid 1px #fff"><a href="http://www.contraloria.gob.pe/wps/portal/portalcgrnew/siteweb/inicio/" target="_blank"><img src="https://enc-ticketing.org/tktv2/public/images/img_planhtml/logo_CGR.png" alt="" width="170" height="44"></a></td>
    <td width="22">&nbsp;</td>
  </tr>
    </tbody></table>
</body>
</html>