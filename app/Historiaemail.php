<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Plantillaemail;
//use App\Evento;

class Historiaemail extends Model
{
    protected $table = 'historia_email';
    //protected $fillable = ['estudiante_id','plantillaemail_id','eventos_id','asunto','nombres','email', 'msg_text','celular','msg_cel','msg_cel','actividades_id','fecha','fecha_envio'];

protected $fillable = ['estudiante_id','plantillaemail_id','eventos_id','asunto','nombres','email', 'msg_text','celular','msg_cel','msg_cel','actividades_id',
    'fecha','fecha_envio','flujo_ejecucion','campania_id','tipo'];
    
    public function plantilla(){
    	return $this->belongsTo(Plantillaemail::class,'plantillaemail_id');
    }

    /*public function evento(){
    	return $this->belongsTo(Evento::class,'evento_id');
    }*/
}


