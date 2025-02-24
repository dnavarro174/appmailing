<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'ubigeos';
	protected $fillable = ['nombre','ubigeo_id'];

	public static function provincias($id){
        //return Provincia::all();

        return DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $id.'%','id2' => $id, 'id3' => 4]);

		//return Provincia::where('ubigeo_id','like', $id.'%')
		//->where('ubigeo_id','like', 'l%')->get();

		//->where('nombre','like','l%')->get();
		//->orWhere('name', 'like', '%' . Input::get('name') . '%')->get();

		//otro metodo
		//$users = DB::select('select * from users where active = ?', [1]);
        //return view('user.index', ['users' => $users]);
	}
}
