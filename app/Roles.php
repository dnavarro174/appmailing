<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
	protected $table = 'roles';
    protected $primary_key 	= "id";
	//public 	$timestamps 	= false;	
    protected $fillable = ['id','rol','descripcion'];
}
