<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Plantillaemail;
use App\estudiantes_prog_det;

use App\Tipo_evento;
use App\Programacione;
use App\AccionesRolesPermisos;
use Excel;
use Alert;
use Auth;

class PlantillaemailController extends Controller
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
        if(!isset( session("permisosTotales")["crm"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "crm";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        //$plantilla_datos = Plantillaemail::all();

        if($request->get('s')){
            $search = $request->get('s');

            $plantilla_datos = Plantillaemail::where("nombre", "LIKE", '%'.$search.'%')
            ->orWhere("asunto", "LIKE", '%'.$search.'%')
            ->orWhere("lista", "LIKE", '%'.$search.'%')
            ->orWhere("flujo_ejecucion", "LIKE", '%'.$search.'%')
            /*->orWhere("created_at", "LIKE", '%'.$search.'%')*/
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'plantilla.page.'.request('page', 1);
            $plantilla_datos = Cache::rememberForever($key, function(){
                return Plantillaemail::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }

        return view('plantillaemail.plantillaemail', compact('plantilla_datos','permisos')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        
        return view('plantillaemail.create'); 
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
            'nombre'=>'required',
            'asunto'=>'required',
            //'flujo_ejecucion'=>'required',
        ]);

        $nom = mb_strtoupper($request->input('nombre'));
        $asu = $request->input('asunto');

        $cancelar = $request->input('cancelar');
        $html     = $request->input('plantillahtml');
        $html     .= "<eee>";
        $html     .= $cancelar;
        $fl       = $request->input('flujo_ejecucion');
        $si       = $request->input('plantilla_si');
        $no       = $request->input('plantilla_no');
        $now      = Carbon::now();


        $b = "";
        if($fl == "LEY-27419"){
            $b = "SI";
        }
        /*if($fl == "MAILING"){
            $no = $cancelar;
        }*/

        $plant = new Plantillaemail;
        $plant->nombre           = $nom;
        $plant->asunto           = $asu;
        $plant->plantillahtml    = $html;
        $plant->flujo_ejecucion  = $fl;
        $plant->gafete           = $b; 
        $plant->plantilla_gafete = $si;
        $plant->plantilla_extra  = $no;// HTML cancelar suscripcion
        $plant->created_at       = $now;
        $plant->updated_at       = $now;
        $plant->save();
        
        Cache::flush();

        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        //return redirect()->route('mailing.index');
        //return redirect()->back();
        return redirect()->route('campanias.create',['eventos_id'=>2,'list'=>1]);

    }


    public function show($id)
    {
        //$plantilla_datos = DB::table('plantillaemail')->where('id', $id)->first();
        $plantilla_datos = Plantillaemail::findOrFail($id);
        $cod_prog = Programacione::select('codigo','nombre')->get();

        return view('plantillaemail.show',compact('plantilla_datos','cod_prog'));
    }


    public function edit($id)
    {

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $plantilla_datos = Plantillaemail::findOrFail($id);
        $separador = "<eee>";
        $bloques   = explode($separador, $plantilla_datos->plantillahtml);
        $bloques_count = count($bloques);
        $html_1 = "";
        $html_2 = "";
        if($bloques_count==2){
            $html_1 = $bloques[0];
            $html_2 = $bloques[1];
        }elseif($bloques_count==1){
            $html_1 = $bloques[0];
        }else{ }


        return view('plantillaemail.edit',compact('plantilla_datos','html_1','html_2'));
    }


    public function update(Request $request, $id)
    {

        $nom = mb_strtoupper($request->input('nombre'));
        $asu = $request->input('asunto');

        $cancelar = $request->input('cancelar');
        $html     = $request->input('plantillahtml');
        $html     .= "<eee>";
        $html     .= $cancelar;
        $fl       = $request->input('flujo_ejecucion');
        $si       = $request->input('plantilla_si');
        $no       = $request->input('plantilla_no');
        $now = Carbon::now();

        $b = "";
        if($fl == "LEY-27419"){
            $b = "SI";
        }
        
        //Actualizamos 
        Plantillaemail::where('id',$id)->update([
             'nombre'           => $nom,
             'asunto'           => $asu,
             'plantillahtml'    => $html,
             'flujo_ejecucion'  => $fl,
             'gafete'           => $b,
             'plantilla_gafete' => $si,
             'plantilla_extra'  => $no, // HTML cancelar suscripcion
             'updated_at'=>Carbon::now()
        ]);

        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');

        return redirect()->back();
    }


    public function destroy($id)
    {
        Plantillaemail::where('id',$id)->delete();
        
        return redirect()->route('plantillaemail.index');
    }

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Plantillaemail::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('plantillaemail.index');
    }

    public function enviar_email(){
        //return "Enviar Email";

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "crm";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $plantilla_datos = Plantillaemail::all();

         return view('plantillaemail.enviar_email', compact('plantilla_datos','permisos'));

        
    }

    // ver plantilla html
    public function verHTML(Request $request,$id){
        if($request->ajax()){

            $plantillaHTML = Plantillaemail::where('id',$id)->first();
            //$plantillaHTML = Plantillaemail::select('plantillahtml')->where('id',$id)->first();
            //$provincias = Provincia::provincias($id);
            return response()->json($plantillaHTML);
        }
    }

    //procesaremailsxlote
    public function procesarEmail(Request $request,$id){
        if($request->ajax()){

            $datos = DB::table('plantillaemail as p')
                                ->join('estudiantes_prog_det as de', 'p.lista', '=', 'de.programacion_id')
                                ->join('estudiantes as e','de.estudiantes_id','=','e.dni_doc')
                                ->where('p.id', '=', $id)
                                ->select('p.id as idplantilla','e.id','e.nombres','e.ap_paterno', 'e.dni_doc', 'e.email', 'p.lista','p.nombre','p.asunto','p.flujo_ejecucion','de.programacion_id')
                                ->get();
            // select * from (plantillaemail as p inner join estudiantes_prog_det as de on p.lista = de.programacion_id) inner join estudiantes as e on de.estudiantes_id = e.dni_doc where p.id = 1 
            
            // TIPO INVITACION

            foreach ($datos as $dato) {
                //$n++;
                $nom = $dato->nombres . ' '.$dato->ap_paterno;
               
                DB::table('historia_email')->insert([
                    'fecha'         => Carbon::now(),
                    'estudiante_id'=>$dato->dni_doc,
                    'plantillaemail_id'=>$dato->idplantilla,
                    'eventos_id'=> 0,
                    'fecha_envio'   => '2000-01-01',
                    'asunto'=>$dato->asunto,
                    'nombres'=>$nom,
                    'email'=>$dato->email,
                    'actividades_id'=>$dato->lista,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
            }
            return response()->json($datos);
            
        }
    }

    public function procesarPlantilla(){
        // link: https://enc-ticketing.org/tktv2/public/pruebaHTML
        // link local: http://localhost:8080/tkt_v12.1/public/pruebaHTML 

            $datos = DB::select("select p.id as idplantilla, e.id,e.nombres,e.ap_paterno, e.ap_materno, e.dni_doc, e.email, p.lista, p.nombre, p.asunto, p.flujo_ejecucion, de.programacion_id from (plantillaemail as p inner join estudiantes_prog_det as de on p.lista = de.programacion_id) inner join estudiantes as e on de.estudiantes_id = e.dni_doc where p.flujo_ejecucion='CONFIRMACION' and e.dni_doc in (SELECT estudiante_id FROM `historia_email` WHERE (created_at BETWEEN '2018-11-09' and '2018-11-27') and asunto LIKE '%CONFIRMADA%')");

            //select p.id as idplantilla, e.id,e.nombres,e.ap_paterno, e.ap_materno, e.dni_doc, e.email, p.lista, p.nombre, p.asunto, p.flujo_ejecucion, de.programacion_id from (plantillaemail as p inner join estudiantes_prog_det as de on p.lista = de.programacion_id) inner join estudiantes as e on de.estudiantes_id = e.dni_doc where p.flujo_ejecucion='CONFIRMACION' and e.dni_doc in (SELECT estudiante_id FROM `historia_email` WHERE (created_at BETWEEN '2018-11-09 10:56:48' and '2018-11-27') and asunto LIKE '%CONFIRMADA%')

            
            // TIPO INVITACION
            $id = 0;
            $enviados = "";

            foreach ($datos as $dato) {
                //$n++;
                $nom = $dato->nombres . ' '.$dato->ap_paterno.' '.$dato->email;

                $enviados = $enviados . $id .' - '. $nom.'<br>';
               
                DB::table('historia_email')->insert([
                    'fecha'         => Carbon::now(),
                    'estudiante_id'=>$dato->dni_doc,
                    'plantillaemail_id'=>$dato->idplantilla,
                    'eventos_id'=> 0,
                    'fecha_envio'   => '2000-01-01',
                    'asunto'=>$dato->asunto,
                    'nombres'=>$nom,
                    'email'=>$dato->email,
                    'programaciones_id'=>$dato->lista,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);

                $id++;
            }
            return $enviados;
            
    
    }


    
}
