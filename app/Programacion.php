<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programacion extends Model
{
    protected $table = "programacion";
    protected $fillable = ['cursos_curso_id','fecha_ini','fecha_fin','hora_inicio','hora_fin','aula','vacante','docente','frecuencia'];

    public function curso(){
    	return $this->belongsTo(Curso::class,'cursos_curso_id');
    }


    public function cat_curso(){
    	return $this->belongsTo(CatCurso::class);
    }

}
