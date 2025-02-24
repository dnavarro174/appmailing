<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModulosAcciones extends Model
{
	protected $table = 'modulos_acciones';
    protected $primary_key 	= "id";
	//public 	$timestamps 	= false;	
    protected $fillable = ['id','idModulo','accion','descripcion'];    
}
