<?php
$fp = fopen(url('') . '/files/html/'.$datos->p_conf_registro.'.html','r');

        //$file = fopen("test.txt","r");
        //Output lines until EOF is reached
        while(! feof($fp)) {
          $line = fgets($fp);
          //echo $line. "<br>";
          echo $line;
        }

        fclose($fp);

?>


{{-- {!! $datos->p_preregistro !!} --}}

