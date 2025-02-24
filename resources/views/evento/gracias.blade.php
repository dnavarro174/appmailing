<?php
#$fp = fopen(url('') . '/files/html/'.$rs_datos->p_conf_registro_gracias.'.html','r');
$fp = fopen(url('') . '/files/html/'.$pantallazo.'.html','r');

        //$file = fopen("test.txt","r");
        //Output lines until EOF is reached
        while(! feof($fp)) {
          $line = fgets($fp);
          //echo $line. "<br>";
          echo $line;
        }

        fclose($fp);

?>
<!-- datos->p_conf_registro_gracias -->