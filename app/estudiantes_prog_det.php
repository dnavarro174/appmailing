<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estudiantes_prog_det extends Model
{
    protected $table = 'estudiantes_prog_det';
    protected $fillable = ['id,estudiantes_id,programacion_id'];
    public $timestamps = false;
}
