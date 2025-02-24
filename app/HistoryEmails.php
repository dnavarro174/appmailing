<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryEmails extends Model
{
    protected $table = 'history_emails';
    protected $fillable = [//'tipo','flujo','plantilla_id','actividad_id','asunto','from_nombre','from_email','envio',
        'campania_id','evento_id',
        'fecha_envio','email','estudiante_id','nombre',
        'ape_pat','ape_mat','dni','cel_cod','cel_nro',
        'accedio','msg_text','msg_cel','status'
        ];
    //
}
