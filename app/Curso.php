<?php
//php artisan --version  // saber version laravel

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CatCurso;

class Curso extends Model
{
    protected $fillable = ['nom_curso','descripcion','modalidad_id','tipo_id','cat_curso_id','sede_id','sesiones','horas_aca','estado'];

    public function cat_curso(){
    	return $this->belongsTo(CatCurso::class,'cat_curso_id');
    }



    /*public function hasCatCurso(array $categorias)
	{
		foreach ($categorias as $categoria){
			if($this->cat_curso_id === $categoria){
				return true;
			}
		}

	}*/

    /*public function hasRoles(array $roles)
	{
		foreach ($roles as $role){
			if($this->role === $role){
				return true;
			}
		}

	}*/

}
