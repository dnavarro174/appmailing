<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plantillaemail extends Model
{
	protected $table = 'plantillaemail';
    protected $fillable = ['nombre','asunto','plantillahtml','flujo_ejecucion','gafete','lista','evento_id'];//,'created_at','updated_at'

    /*public function tipo_evento(){
    	return $this->belongsTo(Tipo_evento::class,'tipo_evento_id');
    }*/

    public function inscritos(){

    	return $this->hasMany(estudiantes_prog_det::class,'programacion_id','lista');// para sacar los inscritos
    	
    	
  	}
}
