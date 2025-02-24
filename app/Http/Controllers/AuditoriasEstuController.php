<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use App\AuditoriaE;
use App\TipoDoc;
use App\Departamento;
use App\AccionesRolesPermisos;
use Alert;
use Auth;


class AuditoriasEstuController extends Controller
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

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        
        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $ae_datos = AuditoriaE::where("nombres", "LIKE", '%'.$search.'%')
            ->orWhere("dni_doc", "LIKE", '%'.$search.'%')
            ->orWhere("ap_paterno", "LIKE", '%'.$search.'%')
            ->orWhere("ap_materno", "LIKE", '%'.$search.'%')
            ->orWhere("cargo", "LIKE", '%'.$search.'%')
            ->orWhere("organizacion", "LIKE", '%'.$search.'%')
            ->orWhere("accedio", "LIKE", '%'.$search.'%')
            ->orWhere("email", "LIKE", '%'.$search.'%')
            ->orWhere("email_labor", "LIKE", '%'.$search.'%')
            ->orWhere("profesion", "LIKE", '%'.$search.'%')
            ->orWhere("direccion", "LIKE", '%'.$search.'%')
            ->orWhere("celular", "LIKE", '%'.$search.'%')
            ->orWhere("accion", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }else{

            $key = 'auditoria.page.'.request('page', 1);
            $ae_datos = Cache::rememberForever($key, function() use ($pag){
                return AuditoriaE::orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            });
        }

        //$ae_datos = AuditoriaE::orderBy('id','desc')->get();

        return view('auditoriae.index', compact('ae_datos', 'permisos'));

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

        $tipo_doc = TipoDoc::all();

        $ae_datos = AuditoriaE::findOrFail($id);

        $distrito = $ae_datos->ubigeo_ubigeo_id;

        $dis = substr($distrito,0,4);

        $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis.'%','id2' => $dis]);

        $prov = substr($distrito,0,2);
        $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov.'%','id2' => $prov,'id3' => 4]);

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        //dd($ae_datos);
        return view('auditoriae.show', compact('ae_datos', 'tipo_doc', 'departamentos_datos','provincias_datos','distritos_datos','prov','dis'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["auditoria"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            AuditoriaE::where('id',$value)->delete(); 
        }

        Cache::flush();

        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('auditoriae.index');
    }
}
