<?php

namespace App;
use Jenssegers\Date\Date; // importar fecha convertir idioma

trait DateTranslator{

	//se crea mutadores para convertir la fecha de ingles a español - para refactorizar esta traducción se crea un triggear

  	public function getCreatedAtAttribute($date){

    	return new Date($date);

  	}

  	public function getUpdatedAtAttribute($date){

    	return new Date($date);

  	}
}