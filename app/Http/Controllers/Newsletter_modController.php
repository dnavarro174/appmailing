<?php

namespace App\Http\Controllers;
use DB;
use Cache;
use App\Newsletter;
use App\Estudiante;
use App\TipoDoc;
use App\AccionesRolesPermisos;
//use Mail;
use Alert;
use Auth;

use Illuminate\Http\Request;

class Newsletter_modController extends Controller
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

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $newsletter_datos = Newsletter::join('estudiantes', 'estudiantes.dni_doc','=','newsletters.estudiante_id')
                ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno','estudiantes.email','estudiantes.created_at','estudiantes.updated_at')
                ->where("estudiantes.dni_doc", "LIKE", '%'.$search.'%')
                ->orWhere("estudiantes.nombres", "LIKE", '%'.$search.'%')
                ->orWhere("estudiantes.ap_paterno", "LIKE", '%'.$search.'%')
                ->orWhere("estudiantes.ap_materno", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'tipo.page.'.request('page', 1);
            $newsletter_datos = Cache::rememberForever($key, function(){
                return Newsletter::join('estudiantes', 'estudiantes.dni_doc','=','newsletters.estudiante_id')
                ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno','estudiantes.email','estudiantes.created_at','estudiantes.updated_at')
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }
                          
        return view('newsletter.newsletter', compact('newsletter_datos', 'permisos'));

    }

 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function eliminar_newsletter (Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Newsletter::where('estudiante_id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Registros borradoss.','Eliminado');
        return redirect()->route('newsletter.index');
    }
}
