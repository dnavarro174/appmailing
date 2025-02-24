<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entidade extends Model
{
    //protected $table = 'entidades';
    protected $fillable = ['entidad'];//solo permite un solo valor
}
