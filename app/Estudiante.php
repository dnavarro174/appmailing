<?php

namespace App;

use DB;
use App\DateTranslator; // trigeer de fecha
use App\Departamento;
#use App\Estudiante;
use App\Estudiantes_tipo;
use App\Tc_modalidade;
use App\GradoProf;
use App\TipoDoc, App\CatCurso, App\Area;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{

  use DateTranslator;

  protected $table = 'estudiantes';
	//protected $fillable = ['nombres','ap_paterno','ap_materno'];

  public function scopeNombres($query, $s)
  {
      if($s)
        return $query->where('nombres', 'LIKE', "%s%");
  }

	public function scopeSearche($query, $s)
	{
		//dd('Scope: '.$s);
		$query->where('nombres','like',$s.'%');

	}
	/*public function delpais()
    {
    	return $this->hasOne('App\Ubigeos', 'ubigeo_id', 'ubigeo_ubigeo_id');
    }*/

	public function scopeBusqueda($query,$pais,$dato=""){
        //dd('dato='. $pais);
        if($pais==0){

          $resultado = DB::select('select * from estudiantes where nombres like :dato1 or ap_paterno like :dato2 or ap_materno like :dato3 or email like :dato4', ['dato1' => '%'.$dato.'%', 'dato2' => '%'.$dato.'%', 'dato3' => '%'.$dato.'%', 'dato4' => '%'.$dato.'%']);
        }else{
          //ejm: select * from estudiantes where ubigeo_ubigeo_id like '02%' and (nombres like '%idania%' or ap_paterno like '%idania%')

          //select * from users where pais = $pais  and (nombres like %$dato% or apellidos like %$dato%  or email like  %$dato% )

          //en el parametro pais el % va al final para que elija todos los que comienzan con ese codigo
          $resultado = DB::select('select * from estudiantes where ubigeo_ubigeo_id like :pais and (nombres like :dato or ap_paterno like :dato2)', ['pais' => $pais.'%','dato' => '%'.$dato.'%', 'dato2' => '%'.$dato.'%']);
          
        }  
        return  $resultado;
  }


  public function departamento(){
      return $this->belongsTo(Departamento::class,'ubigeo_ubigeo_id','ubigeo_id');
  }

  public function tipo(){
    return $this->belongsTo(Estudiantes_tipo::class,'estudiantes_tipo_id','id');
  }
  //Modalidad caii: presencial y virtual
  public function Modalidad(){
    return $this->belongsTo(Tc_modalidade::class,'modalidad_id','modalidad_id');#
  }

  /*FALTA: hasMany - public function Estudiante(){
    return $this->hasMany('App\estudiantes_act_detalle','modalidad_id','modalidad_id');#
  } */

  public function grado(){
      return $this->belongsTo(GradoProf::class,'gradoprof','id');
  }

  public function tipodoc(){
      return $this->belongsTo(TipoDoc::class,'tipo_documento_documento_id','id');
  }

  public function curso(){
    return $this->hasOne(CatCurso::class,'id','curso_id');
  }

  # Mod. Correos ENC
  public function areas(){
    return $this->belongsTo(Area::class,'area_id','id');
  }

}

