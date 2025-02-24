<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use App\AuditoriaP;
use App\TipoDoc;
use App\Departamento;
use App\AccionesRolesPermisos;
use Alert;
use Auth;

class AuditoriasProgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["auditoria"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "auditoria";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS
        
        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $ap_datos = AuditoriaP::where("codigo", "LIKE", '%'.$search.'%')
            ->orWhere("nombre", "LIKE", '%'.$search.'%')
            ->orWhere("denominacion", "LIKE", '%'.$search.'%')
            ->orWhere("tipo", "LIKE", '%'.$search.'%')
            ->orWhere("modalidad", "LIKE", '%'.$search.'%')
            ->orWhere("nombre_curso", "LIKE", '%'.$search.'%')
            ->orWhere("area_tematica", "LIKE", '%'.$search.'%')
            ->orWhere("docente", "LIKE", '%'.$search.'%')
            ->orWhere("aula", "LIKE", '%'.$search.'%')
            ->orWhere("piso", "LIKE", '%'.$search.'%')
            ->orWhere("fecha_desde", "LIKE", '%'.$search.'%')
            ->orWhere("fecha_hasta", "LIKE", '%'.$search.'%')
            ->orWhere("accion", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate(15);

        }else{

            $key = 'tipo.page.'.request('page', 1);
            $ap_datos = Cache::rememberForever($key, function(){
                return AuditoriaP::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }
        
        //$ap_datos = AuditoriaP::orderBy('id','desc')->get();

        return view('auditoriap.index', compact('ap_datos', 'permisos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["auditoria"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        

        $ap_datos = AuditoriaP::findOrFail($id);

        //dd($ap_datos);
        return view('auditoriap.show', compact('ap_datos'));
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["auditoria"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            AuditoriaP::where('id',$value)->delete(); 
        }

        Cache::flush();

        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('auditoriap.index');
    }
}
