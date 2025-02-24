<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'users';
    protected $fillable = ['name,email,password,estado,create_at,updated_at'];
 
    public $timestamps = false;
}
