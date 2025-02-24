<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usuario;
use App\Estudiante;
use App\Actividade;
use App\Evento;

class Asistencia_evento extends Model
{
    protected $table = 'asistencia_eventos';
    //protected $fillable = ['fecha','hora','estudiante_id', 'actividad_id','evento_id'];

    public function usuario(){
    	return $this->belongsTo(Usuario::class,'usuario_id');
    }

    public function actividad(){
    	return $this->belongsTo(Actividade::class,'actividad_id');
    }

    public function evento(){
    	return $this->belongsTo(Evento::class,'evento_id');
    }

    public function estudiante(){
    	//return $this->belongsTo(Estudiante::class,'estudiantes', 'dni_doc','estudiante_id');
    	return $this->belongsTo(Estudiante::class,'estudiante_id');
    }
}
