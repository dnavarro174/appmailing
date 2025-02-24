<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
	protected $table = 'user_role';
    protected $primary_key 	= "id";
	//public 	$timestamps 	= false;	
    protected $fillable = ['id','user_id','role_id'];
}
