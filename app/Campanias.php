<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Evento;

class Campanias extends Model
{
    protected $fillable = ["nombre","eventos_id","checkHTML","grupo","pais","region","organizacion","profesion","total","enviados","errores","from_id","all"
    ,"tipo","flujo","plantilla_id","asunto","from_nombre","from_email","actividad_id", "inv", "inv_id"
    ];

    //
    public function Evento(){
        return $this->belongsTo(Evento::class,'eventos_id','id');
    }
}
