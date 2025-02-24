<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultaDNI extends Model
{
    protected $table = 'estudiantes_act_detalle';
	protected $fillable = ['estudiantes_id','eventos_id'];

	public static function selectDNI($id,$evento=''){
		//return "id: $id - evento: $evento";

		$data = Estudiante::
					select('tipo_id','dni_doc','nombres','ap_paterno','ap_materno','pais','region','organizacion', 'cargo', 'profesion','celular','email','codigo_cel', 'grupo')
                    ->where('dni_doc',$id)
                    ->count();
        $existe = "";
        if($data>0){
        	$data = Estudiante::
					select('tipo_id','dni_doc','nombres','ap_paterno','ap_materno','pais','region','organizacion', 'cargo', 'profesion','celular','email', 'codigo_cel', 'grupo')
                    ->where('dni_doc',$id)
                    ->first();
        	$existe = 1;
        }else{
        	$existe = 0;
        }

		$evento = ConsultaDNI::
				where('estudiantes_id','=', $id)
				->where('eventos_id', $evento)
				->count();

		if($evento > 0){
			$existe = 2;
			$evento = true;
		}

		return compact('data','existe');
	}

}
