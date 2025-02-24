<?php

namespace App\Http\Controllers;
use Cache;
use DB;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use App\Baja;
use App\TipoDoc;
use App\Estudiante;
use App\User;
use App\Departamento;
use App\AccionesRolesPermisos;
use Alert;
use Auth;

class BajasController extends Controller
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
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "estudiantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

        ////FIN DE PERMISOS

        if(session('eventos_id') == false){
            return redirect()->route('caii.index');
        }

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        
        Cache::flush();
        if($request->get('s')){
            $s = $request->get('s');

            $f_datos = Baja::where('eventos_id',session('eventos_id'))
                ->where(function ($query) use ($s) {
                    $query->where("dni_doc", "LIKE", '%'.$s.'%')
                        ->orWhere("cargo", "LIKE", '%'.$s.'%')
                        ->orWhere("organizacion", "LIKE", '%'.$s.'%')
                        ->orWhere("accedio", "LIKE", '%'.$s.'%')
                        ->orWhere("email", "LIKE", '%'.$s.'%')
                        ->orWhere("email_labor", "LIKE", '%'.$s.'%')
                        ->orWhere("profesion", "LIKE", '%'.$s.'%')
                        ->orWhere("celular", "LIKE", '%'.$s.'%')
                        ->orWhere(DB::raw('CONCAT(nombres," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$s.'%')
                        ->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$s.'%');
                })
                ->orderBy('id','DESC')
                ->paginate(15);

        }else{

            $key = 'estudiantes.page.'.request('page', 1);
            $f_datos = Cache::rememberForever($key, function(){
                return Baja::where('eventos_id',session('eventos_id'))->orderBy('id', 'desc')
                        ->paginate(15);
            });
        }
        
        //$f_datos = Estudiantes_caii::orderBy('id','desc')->get();
        return view('bajas.bajas', compact('f_datos', 'permisos'));
    }

    
    public function upgrate(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "estudiantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

        ////FIN DE PERMISOS

        if(session('eventos_id') == false){
            return redirect()->route('caii.index');
        }

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        Cache::flush();
        if($request->get('s')){
            $search = $request->get('s');
            
            $f_datos = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','estudiantes.created_at','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','de.modalidad_id')
                ->where('de.eventos_id',session('eventos_id'))
                ->where('de.cambio_tipo',1)
                ->where(function ($query) use ($search) {
                    $query->where("estudiantes.dni_doc", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.cargo", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.grupo", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.organizacion", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.accedio", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.email", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.email_labor", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.profesion", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.direccion", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.pais", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.region", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.celular", "LIKE", '%'.$search.'%')
                    ->orWhere(DB::raw('CONCAT(nombres," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$search.'%')
                    ->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$search.'%');
                })
                ->orderBy('estudiantes.id', request('sorted', 'DESC'))
                ->paginate($pag);

        }else{

            $key = 'estudiantes_upgrate.page.'.request('page', 1);
            $f_datos = Cache::rememberForever($key, function() use ($pag){
                return Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                        ->where('de.cambio_tipo',1)
                        ->where('de.eventos_id',session('eventos_id'))
                        ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.email','estudiantes.accedio','estudiantes.created_at','estudiantes.track','de.modalidad_id','de.estudiantes_tipo_id','de.cambio_tipo')
                        ->orderBy('estudiantes.id', request('sorted', 'DESC'))
                        ->paginate($pag);
            });
        }
        
        //$f_datos = Estudiantes_caii::orderBy('id','desc')->get();
        return view('bajas.upgrate', compact('f_datos', 'permisos'));
    }


    


}
