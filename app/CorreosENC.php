<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorreosENC extends Model
{
    protected $table = 'tb_correosenc';
    protected $fillable = ['estudiantes_id','emailenc','password','area_id'];

}
