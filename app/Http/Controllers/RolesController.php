<?php

namespace App\Http\Controllers;
use Cache;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Usuario;

use App\Roles;
use App\Modulos;
use App\ModulosAcciones;
use App\AccionesRolesPermisos;

use Auth;
use Alert;

class RolesController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["roles"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "roles";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $roles_datos = Roles::where("rol", "LIKE", '%'.$search.'%')
            ->orWhere("descripcion", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'roles.page.'.request('page', 1);
            $roles_datos = Cache::rememberForever($key, function(){
                return Roles::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }

        //$roles_datos = Roles::all();
        return view('roles.roles',compact('roles_datos','permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["roles"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'rol'=>'required'
        ]);

        $verRol = Roles::where("rol",$request->input('rol'))->first();

        if(!($verRol)){
            $rol = new Roles();
            $rol->rol = $request->input('rol');
            $rol->descripcion = $request->input('descripcion');
            
            $rol->created_at = Carbon::now();
            $rol->updated_at = Carbon::now();
            $rol->save();

            Cache::flush();

            alert()->success('Mensaje Satisfactorio','Registro grabado.');

            //return redirect()->route('estudiantes.create')->with('error','Success message');
            return redirect()->route('roles.index');
        }else{
            alert()->success('El Rol ya existe','Mensaje Advertencia');
            //dd('ssss');
            return redirect()->back();
            //return redirect()->route('roles.create');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuarios_datos = Usuario::where('id',$id)->first();
        //$usuarios_datos = Usuario::findOrFail($id);
        return view('roles.show',compact('usuarios_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["roles"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        $modulos = Modulos::orderBy("nom_modulo","ASC")->get();
        $rol = Roles::find($id);

        $arr1 = [];
        $c1 = 0;
        foreach ($modulos as $modulo) {
            $arr1[$c1]["nom_modulo"] = $modulo->nom_modulo;
            $arr1[$c1]["id_modulo"] = $modulo->id;
            $modulos_acciones = ModulosAcciones::where("idModulo", $modulo->id)
                                    ->orderBy("id","ASC")
                                    ->get();
            $arr2 = [];
            $c2 = 0;
            foreach ($modulos_acciones as $modulo_accion) {
                $arr2[$c2]["accion"] = $modulo_accion->accion;
                $arr2[$c2]["descripcion"] = $modulo_accion->descripcion;
                $arr2[$c2]["idAccion"] = $modulo_accion->id;
                //Ver si el rol tiene permiso
                $verPer = AccionesRolesPermisos::where("idRol",$id)
                                        ->where("idModuloAccion",$modulo_accion->id)
                                        ->first();
                $permiso = 0;
                if($verPer){
                    $permiso = $verPer->permiso;
                }

                $arr2[$c2]["permiso"] = $permiso;
                $c2++;
            }
            $arr1[$c1]["acciones"] = $arr2;

            $c1++;
        }
        
        $rol_datos = Roles::where('id',$id)->first();
        
        return view('roles.edit',compact('rol_datos','id','arr1'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /*$editUsuario = Usuario::where("id",$id)->first();

        if(!($editUsuario)){
            //$editUsuario = new Usuario();
            $editUsuario->name = $request->input('name');
            $editUsuario->email = $request->input('email');
            $editUsuario->password = $request->input('password');
            $editUsuario->updated_at = Carbon::now();
            $editUsuario->save();
        }*/

        DB::table('roles')->where('id',$id)->update([
            
             'rol'=>$request->input('rol'),
             'descripcion'=>$request->input('descripcion'),
             'updated_at'=>Carbon::now(),
        ]);


        $idRol      = $id;
        $total      = $request['totalRows'];
        DB::table('acciones_roles_permisos')
            //->where('idModuloAccion', $idAccion)
            ->where('idRol', $idRol)
            ->delete();
        for($x=1; $x<= $total ; $x++){
            
            $detalle = new AccionesRolesPermisos();
            $detalle->idModuloAccion    =   $request['idAccion_'.$x];  
            $detalle->idRol             =   $idRol;   
            $detalle->permiso           =   $request['permiso_'.$x];   
            $detalle->save();
        }

        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
        
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function permisos($id)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["roles"]["permisos"]["permisos"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $modulos = Modulos::orderBy("nom_modulo","ASC")->get();
        $rol = Roles::find($id);

        $arr1 = [];
        $c1 = 0;
        foreach ($modulos as $modulo) {
            $arr1[$c1]["nom_modulo"] = $modulo->nom_modulo;
            $arr1[$c1]["id_modulo"] = $modulo->id;
            $modulos_acciones = ModulosAcciones::where("idModulo", $modulo->id)
                                    ->orderBy("id","ASC")
                                    ->get();
            $arr2 = [];
            $c2 = 0;
            foreach ($modulos_acciones as $modulo_accion) {
                $arr2[$c2]["accion"] = $modulo_accion->accion;
                $arr2[$c2]["descripcion"] = $modulo_accion->descripcion;
                $arr2[$c2]["idAccion"] = $modulo_accion->id;
                //Ver si el rol tiene permiso
                $verPer = AccionesRolesPermisos::where("idRol",$id)
                                        ->where("idModuloAccion",$modulo_accion->id)
                                        ->first();
                $permiso = 0;
                if($verPer){
                    $permiso = $verPer->permiso;
                }

                $arr2[$c2]["permiso"] = $permiso;
                $c2++;
            }
            $arr1[$c1]["acciones"] = $arr2;

            $c1++;
        }

        return view("roles.permisos", 
            [   'id'            => $id, 
                'arr1'          => $arr1,
                'rol'           => $rol,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storepermisos(Request $request)
    {
        try {
            $idRol      = $request['idRol'];
            $total      = $request['totalRows'];
            DB::table('acciones_roles_permisos')
                //->where('idModuloAccion', $idAccion)
                ->where('idRol', $idRol)
                ->delete();
            for($x=1; $x<= $total ; $x++){
                
                $detalle = new AccionesRolesPermisos();
                $detalle->idModuloAccion    =   $request['idAccion_'.$x];  
                $detalle->idRol             =   $idRol;   
                $detalle->permiso           =   $request['permiso_'.$x];   
                $detalle->save();
            }

            Cache::flush();

            /*$idRol      = $request['idRol'];
            $idModulo   = $request['idModulo'];                
            $idAccion   = $request['idAccion'];
            $permiso   = (int)$request['permiso'];

            DB::table('acciones_roles_permisos')
                ->where('idModuloAccion', $idAccion)
                ->where('idRol', $idRol)
                ->delete();

            $detalle = new AccionesRolesPermisos();
            $detalle->idModuloAccion    =   $idAccion;
            $detalle->idRol             =   $idRol;
            $detalle->permiso           =   $permiso ; 
            $detalle->save(); */

            $this->actualizarSesion();
            

        }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storepermisosall(Request $request)
    {
        try {
            $idRol      = $request['idRol2'];
            $respuesta = "";
            if($request['hdnConceder'] == 1){                

                DB::table('acciones_roles_permisos')
                    ->where('idRol', $idRol)
                    ->delete();

                $modAcc = ModulosAcciones::get();
                foreach ($modAcc as $modAc) {
                    $detalle = new AccionesRolesPermisos();
                    $detalle->idModuloAccion    =   $modAc->id;
                    $detalle->idRol             =   $idRol;
                    $detalle->permiso           =   1 ; 
                    $detalle->save();                 
                }                
                $respuesta = "1";
            }else{
                DB::table('acciones_roles_permisos')
                    ->where('idRol', $idRol)
                    ->delete();
                $respuesta = "2";
            }

            Cache::flush();

            $this->actualizarSesion();
            return $respuesta ;

        }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }  
    }    

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["roles"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $ids_roles = $request->ids_roles;
        foreach ($ids_roles as $value) {
            AccionesRolesPermisos::where('idRol',$value)->delete();
            Roles::where('id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('roles.index');
    }



}
