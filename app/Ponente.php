<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ponente extends Model
{
     
    protected $table = 'ponentes';
    protected $fillable = ['nombre','ap_paterno','ap_materno','email','email_2','telefono','telefono_2'];
}
