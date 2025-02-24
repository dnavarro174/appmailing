<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramacionesTemp extends Model
{
    protected $table = 'programaciones_temp';
    //protected $fillable = ['id,codigo,nombre,tipo,modalidad,nombre_curso,area_tematica,docente,aula,nsesiones,fecha_desde,fecha_hasta,horario,repetido,mensaje,idProgramacion'];
    protected $fillable = ['codigo,nombre,tipo,modalidad,nombre_curso,area_tematica,docente,aula,piso,nsesiones,fecha_desde,fecha_hasta,frecuencia,estado,repetido,mensaje,idProgramacion'];
    public $timestamps = false;

}
