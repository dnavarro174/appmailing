<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CatCurso;

class Imaddjj extends Model
{
    //protected $connection = 'mysql2';
    //protected $table = 'ima_ddjj';
    protected $table = 'm4_ddjj';

    /* public function CursoDJ(){
        return $this->belongsTo(CatCurso::class,'curso_id','id');
    }

    public function curso(){
        return $this->hasOne(CatCurso::class,'curso_id','id');
    } */
    
}
