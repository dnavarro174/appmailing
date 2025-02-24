<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datospersonales extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'adoc_datos_personales';
}
