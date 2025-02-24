<?php

namespace App\Http\Controllers;
use App\Docente;
use Illuminate\Http\Request;
use App\AccionesRolesPermisos, App\Actividade;
use DB;
use Cache;

class DocentesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    { 
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "academico";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        Cache::flush();
        if($request->get('s')){
            $search = $request->get('s');

            $ddatos = Docente::where("dni_doc", "LIKE", '%'.$search.'%')
            ->orWhere("nombre_doc", "LIKE", '%'.$search.'%')
            ->orWhere(DB::raw('CONCAT(nombre_doc," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$search.'%')
            ->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombre_doc)'), 'LIKE' , '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate(15);

        }else{

            $key = 'actividades.page.'.request('page', 1);
            $ddatos = Cache::rememberForever($key, function(){
                return Docente::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }
        //$ddatos = Actividade::all();

        return view('docentes.docentes', compact('ddatos','permisos')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
                $evento_id = $request->input('evento_id');
                $fecha = $request->input('programaciones_id');
                $actividad_id = $request->input('actividad_id');
                $actividad_id_orig = $request->input('dni_doc');
                $dni_doc = $request->input('dni_doc');

                $nom = mb_strtoupper($request->input('nombre_doc'));
                $a_pat   = mb_strtoupper($request->input('ap_paterno'));
                $a_mat   = mb_strtoupper($request->input('ap_materno'));
                
                $nomarch = "";

                if($actividad_id==0){//NEW

                    $n = Docente::where('dni_doc',$dni_doc)->count();
                    if ($n > 0) {
                        return \Response::json(['error' => "El Docente ya existe con ese DNI"], 404); 
                        exit;
                    }

                    $actividad = new Docente();
                    $actividad->dni_doc = $request->input('dni_doc');
                    $actividad->nombre_doc = $nom;
                    $actividad->ap_paterno = $a_pat;
                    $actividad->ap_materno = $a_mat;
                    $actividad->save();

                }else{//UPDATE
                    $actividad = Docente::find($actividad_id) ;
                    $actividad->dni_doc = $request->input('dni_doc');
                    $actividad->nombre_doc = $nom;
                    $actividad->ap_paterno = $a_pat;
                    $actividad->ap_materno = $a_mat;
                    $actividad->save();
                }
                $nom_c = $nom." ".$a_pat;
                $rs = array('docente' => $nom_c, 'dni_doc' => $dni_doc, 'ok' => 'exito' );

                return $rs;

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 
    }

    public function findDocentes(Request $req){
        return Docente::where('nombre_c', 'like', "%".$req->input('q')."%")
            ->get();
    }

    public function addDocentes($evento_id, $fecha, $actividad_id, $num)
    { 
        $fecha = strtotime( $fecha);
        $fecha = date('d/m/Y', $fecha); 
        
        $actividad = Docente::find($actividad_id);
        
        $fecha_desde = null;
        
        return view('docentes.add_docentes', compact('evento_id', 'fecha', 'actividad_id', 'num', 'actividad' )); 
    }

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Docente::where('id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('docentes.index');
    }

    
}
