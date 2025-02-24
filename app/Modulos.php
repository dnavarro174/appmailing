<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
	protected $table = 'modulos';
    protected $primary_key 	= "id";
	//public 	$timestamps 	= false;	
    protected $fillable = ['id','nom_modulo', 'alias'];
}
