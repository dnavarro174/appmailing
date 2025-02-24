<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento_form extends Model
{
	protected $table = 'e_formularios';
    protected $dates = ['fechai_evento','fechaf_evento','fechaf_pre_evento','fechaf_insc_evento',];

    /*public function tipo_evento(){
    	return $this->belongsTo(Tipo_evento::class,'tipo_evento_id');
    }*/
    
}
