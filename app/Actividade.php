<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Evento;

class Actividade extends Model
{
    protected $fillable = ['eventos_id','titulo',
    'subtitulo', 
    'desc_actividad', 
    'desc_ponentes', 
    'vacantes', 
    'vacantes_v', 
    'enlace', 
    'inscritos', 
    'inscritos_v', 
    'fecha_desde', 
    'fecha_hasta', 
    'hora_inicio', 
    'hora_final', 
    'ubicacion', 
    'imagen', 
    'enlace', 
    'estado'];

    /*public function evento(){
    	return $this->belongsTo(Evento::class,'eventos_id');
    }*/
}

