<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estudiantes_act_detalle extends Model
{
    protected $table = 'estudiantes_act_detalle';
    protected $fillable = ['id,estudiantes_id,programacion_id','actividades_id','confirmado'];
    public $timestamps = false;

}
