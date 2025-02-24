<?php

namespace App;
use DB;
use App\Baja;
use App\Evento;
use App\Estudiantes_tipo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    protected $table = 'estudiantes_baja';
	
	public function tipo_baja(){
      return $this->belongsTo(Estudiantes_tipo::class,'tipo_id','id');
    }

    public function evento(){
        return $this->belongsTo(Evento::class,'eventos_id');
    }
}
