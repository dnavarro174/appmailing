<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'ubigeos';
	protected $fillable = ['nombre','ubigeo_id'];

	public static function distritos($id){
		return DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $id.'%','id2' => $id, 'id3' => 6]);
		//return Distrito::where('ubigeo_id','like',$id.'%')->get();
	}
}
