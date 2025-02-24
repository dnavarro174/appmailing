<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class AccionesRolesPermisos extends Model
{
	protected $table = 'acciones_roles_permisos';
    protected $primary_key 	= "id";
	public 	$timestamps 	= false;	
    protected $fillable = ['id','idModuloAccion','idRol','permiso'];    


	public static function getRolesByUser($idUser){
		$usrls = $mnivel1 =   DB::table('user_role')->where('user_id',$idUser)->get();
		$rolesArr = "";
		foreach ($usrls as $usrl) {
			//echo $usrl->role_id ."<br>";
			$rolesArr .= $usrl->role_id.",";
		}
		$rolesArr = substr($rolesArr, 0,-1);
		return $rolesArr;
	}

	public static function getPermisosByRolesController($request = "")
	{
		$Permisos = DB::table('modulos')
				->select('nom_modulo',
					'descripcion',
					'accion',
					'alias',
					'permiso',
					DB::raw("modulos.alias as alias_modulo")
				)
				->leftJoin('modulos_acciones', 'modulos_acciones.idModulo', '=', 'modulos.id')
				->leftJoin('acciones_roles_permisos', 'acciones_roles_permisos.idModuloAccion', '=', 'modulos_acciones.id')
				//->where('modulos.id','>',0);
				->where('permiso',1);

		if($request){
			if(isset($request["modulo_alias"]) && $request["modulo_alias"]!=""){
				$Permisos->where('modulos.alias', '=', $request["modulo_alias"] );
			}
			if(isset($request["accion"]) && $request["accion"]!=""){
				$Permisos->where('accion', '=', $request["accion"] );
			}
			if(isset($request["roles"]) && $request["roles"]!=""){
				$Permisos->whereRaw(" idRol in (".($request["roles"]).") ");
			}else{
				$Permisos->whereRaw(" idRol in (0) ");
			}
		}

		$permisos = $Permisos->get();
		$arrPerm = [];
		foreach ($permisos as $perm) {
			$arrPerm[$perm->accion]["descripcion"] = $perm->descripcion;
			$arrPerm[$perm->accion]["permiso"] = $perm->permiso;
			$arrPerm[$perm->accion]["modulo"] = $perm->nom_modulo;
			$arrPerm[$perm->accion]["alias_modulo"] = $perm->alias_modulo;
		}
		return $arrPerm;
	}


	public static function getPermisosInicioByRolesController($request = "")
	{
		$Permisos = DB::table('modulos')
				->select('nom_modulo',
					'descripcion',
					'accion',
					'alias',
					'permiso',
					DB::raw("modulos.alias as alias_modulo")
				)
				->leftJoin('modulos_acciones', 'modulos_acciones.idModulo', '=', 'modulos.id')
				->leftJoin('acciones_roles_permisos', 'acciones_roles_permisos.idModuloAccion', '=', 'modulos_acciones.id')
				//->where('modulos.id','>',0);
				->where('permiso',1);

		if($request){

			if(isset($request["accion"]) && $request["accion"]!=""){
				$Permisos->where('accion', '=', $request["accion"] );
			}
			if(isset($request["roles"]) && $request["roles"]!=""){
				$Permisos->whereRaw(" idRol in (".($request["roles"]).") ");
			}else{
				$Permisos->whereRaw(" idRol in (0) ");
			}
		}

		$permisos = $Permisos->get();
		$arrPerm = [];
		foreach ($permisos as $perm) {
			$arrPerm[] = $perm->alias_modulo;
			/*$arrPerm[$perm->alias_modulo]["descripcion"] = $perm->descripcion;
			$arrPerm[$perm->alias_modulo]["permiso"] = $perm->permiso;
			$arrPerm[$perm->alias_modulo]["modulo"] = $perm->nom_modulo;
			$arrPerm[$perm->alias_modulo]["alias_modulo"] = $perm->alias_modulo;*/
		}
		return array_unique($arrPerm);
	}





	public static function getPermisosTotalesByRolesController($request = "")
	{

		$modulos = DB::table('modulos')
				->select(
					DB::raw("DISTINCT(modulos.id) as idModulo"),
					DB::raw("modulos.alias as alias_modulo")
				)
				->leftJoin('modulos_acciones', 'modulos_acciones.idModulo', '=', 'modulos.id')
				->leftJoin('acciones_roles_permisos', 'acciones_roles_permisos.idModuloAccion', '=', 'modulos_acciones.id')
				->where('permiso',1)
				->whereRaw(" idRol in (".($request["roles"]).") ")
				->get();

		$arrMod = [];
		foreach ($modulos as $modulo) {
			$arrMod[$modulo->alias_modulo]["id"] = $modulo->idModulo;
			$arrMod[$modulo->alias_modulo]["modulo"] = $modulo->alias_modulo;
			//Permisos
			$Permisos = DB::table('modulos_acciones')
							->leftJoin('acciones_roles_permisos', 'acciones_roles_permisos.idModuloAccion', '=', 'modulos_acciones.id')
							->select(
								'accion',
								//'alias',
								'permiso'
							)
						->where('modulos_acciones.idModulo','=',$modulo->idModulo)
						->where('permiso',1)
						->whereRaw(" idRol in (".($request["roles"]).") ")
						->get();
			$arrPerm = [];
			foreach ($Permisos as $perm) {
				
				$arrPerm[$perm->accion]["permiso"] = $perm->permiso;
				//$arrPerm[$perm->accion]["alias"] = $perm->alias;
			}
			$arrMod[$modulo->alias_modulo]["permisos"] = $arrPerm;
		}

		return $arrMod;
	}



}
