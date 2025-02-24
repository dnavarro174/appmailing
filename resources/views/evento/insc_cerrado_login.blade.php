<?php 
$fp = fopen(url('') . '/files/html/'.$datos->p_inscripcion_cerrado.'.html','r');

        //$file = fopen("test.txt","r");
        //Output lines until EOF is reached
        while(! feof($fp)) {
          $line = fgets($fp);
          //echo $line. "<br>";
          echo $line;
        }

        fclose($fp);

?>
{{-- @extends('layout.home')

@section('content')
     partial PLANTILLA 8: INSCRIPCIONES CERRADAS
  	{!! $datos->p_inscripcion_cerrado !!}
@endsection --}}