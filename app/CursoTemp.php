<?php

/* use Illuminate\Database\Eloquent\Model; */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoTemp extends Model
{
    //protected $table = 'cursos_temp';
    //protected $fillable = ['id,nom_curso,descripcion,modalidad_id,tipo_id,cat_curso_id,sede_id,sesiones,horas_aca,repetido,mensaje,idCurso'];
    use HasFactory;
    protected $table = 'm4_cursos_temp';
    public $timestamps = false;

}
