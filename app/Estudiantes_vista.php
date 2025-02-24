<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiantes_vista extends Model
{
    protected $table = 'estudiantes_vista';

    public function departamento(){

    	return $this->belongsTo(Departamento::class,'ubigeo_ubigeo_id','ubigeo_id');
    	
  	}
    
}
