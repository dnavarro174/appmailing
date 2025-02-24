<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'ubigeos';
	protected $fillable = ['nombre','ubigeo_id'];

	public static function departamentos($id){

		return DB::select('select * from ubigeos where ubigeo_id <= 25 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id3' => 2]);

	}

}
