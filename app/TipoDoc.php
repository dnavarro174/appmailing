<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDoc extends Model
{
    protected $table= "tipo_documento";
    protected $fillable= ["id","tipo_doc"];
}
