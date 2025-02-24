<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Tipo_evento;
use App\Actividade;
use App\Emails;

class Evento extends Model
{
    protected $dates = ['fechai_evento','fechaf_evento','fechaf_pre_evento','fechaf_insc_evento',];

    /*public function tipo_evento(){
    	return $this->belongsTo(Tipo_evento::class,'tipo_evento_id');
    }*/
    public function actividades() {
	   return $this->hasMany(Actividade::class,'id_eventos');
	}

    //Email para los correos
    public function Email() {
        return $this->belongsTo(Emails::class,'email_id','id');
    }
    /* public function Email(){
        return $this->belongsTo(Tc_modalidade::class,'modalidad_id','modalidad_id');#
    } */
}
