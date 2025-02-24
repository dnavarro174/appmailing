<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use DB;
use Carbon\Carbon;
use App\Estudiante, App\Evento;
use App\Plantillaemail;
use App\Historiaemail;
use App\Programacione;
use App\Departamento;
use App\Newsletter;
use App\Estudiantes_vista;
use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;
use Mail;
use Alert;

class MailingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //ENVIAR EMAIL

    public function index(Request $request){
    
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        
        ////PERMISOS
        if(Cache::has('permisos.all')){
            $permisos = Cache::get('permisos.all');

        }else{

            $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
            $permParam["modulo_alias"] = "crm";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

            Cache::put('permisos.all', $permisos, 1);

        }
        ////FIN DE PERMISOS

        $plantilla_datos = Plantillaemail::orderBy('id','desc')->get();
        $tipos = DB::table('est_grupos')->get();
        $paises = DB::table('country')->select('id','name')->get();
        //$prof = Estudiante::select(DB::raw('distinct(profesion) as profesion'))->get();
        $eventos = Evento::select('id','nombre_evento')->orderBy('nombre_evento','asc')->get();
        
        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
        
        return view('mailing.index', compact('estudiantes_datos', 'plantilla_datos', 'permisos', 'tipos','paises', 'departamentos_datos', 'eventos'));

    }

    // method post envio email
    public function store(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        // form radio button
        $radio   = $request->get('checkHTML');
        $evento  = $request->get('evento');
        $grupo   = $request->get('grupo');
        $pais    = $request->get('pais');
        $depa    = $request->get('depa');

        if($radio){
            $id_html = $request->checkHTML;
            $plantilla = Plantillaemail::findOrFail($id_html);
            $flujo_ejecucion = $plantilla->flujo_ejecucion;
            $asunto          = $plantilla->asunto;
            $id_plantilla    = $plantilla->id;

            //falta agregar envio por: evento - grupo - pais - depa
            if($evento and !$grupo and !$pais and !$depa){

                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();
                

            }elseif($evento and $grupo and !$pais and !$depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();
            }elseif($evento and $grupo and $pais and !$depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.pais', $pais)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();
            }elseif($evento and $grupo and $pais and $depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.pais', $pais)
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($grupo and !$evento and !$pais and !$depa){
            /* b */

                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();
                //dd($participantes);

            }elseif($grupo and $evento and !$pais and !$depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();
                                
            }elseif($grupo and $evento and $pais and !$depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.pais', $pais)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($pais and !$evento and !$grupo and !$depa){
            /* c */

                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('estudiantes.pais', $pais)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($pais and $evento and !$grupo and !$depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('estudiantes.pais', $pais)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($pais and $evento and $grupo and !$depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.pais', $pais)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($depa and !$evento and !$grupo and !$pais){
            /* d */
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($depa and $evento and !$grupo and !$pais){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($depa and $evento and $grupo and !$pais){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif(!$evento and !$grupo and $pais and $depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('estudiantes.pais', $pais)
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif(!$evento and $grupo and !$pais and $depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.pais', $pais)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif(!$evento and $grupo and $pais and $depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.dgrupo','like', '%'.$grupo.'%')
                                ->where('estudiantes.pais', $pais)
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif(!$evento and !$grupo and !$pais and $depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();

            }elseif($evento and !$grupo and !$pais and $depa){
                $participantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('de.eventos_id',$evento)
                                ->where('estudiantes.region', $depa)
                                ->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno')
                                ->get();
            }else{
                return "Error: Intentelo nuevamente.";
            }
            //dd(count($participantes));
                $xemail = 0;
                $cant = count($participantes);
                if($cant == 0){
                    alert()->warning('No existe participantes en esta búsqueda.','Mensaje');
                    return back();
                }

                foreach ($participantes as $key => $part) {
                    $email = $part->email;
                    $dni   = $part->estudiantes_id;
                    $nom   = $part->nombres .' '.$part->ap_paterno;

                    if($email){
                        $xemail += 1;
                        DB::table('historia_email')->insert([
                            'tipo'              => 'EMAIL',
                            'fecha'             => Carbon::now(),
                            'eventos_id'        => ($evento)?$evento:0,
                            'flujo_ejecucion'   => $flujo_ejecucion,
                            'estudiante_id'     => $dni,
                            'plantillaemail_id' => $id_html,
                            'fecha_envio'       => '2000-01-01',
                            'asunto'            => $asunto,
                            'nombres'           => $nom,
                            'email'             => $email,
                            'created_at'        => Carbon::now(),
                            'updated_at'        => Carbon::now(),
                            'from_nombre'       => 'ENC',
                            'from_email'        => 'info@enc.edu.pe',
                        ]);
                    }
                }
                session(['eventos_id'=> -1]);
                // $key
                alert()->success('Se enviarón '.$xemail.' mensajes.', 'Mensaje')->persistent('close');
                //return back();
                return redirect()->route('mailing.index', ['history'=>1]);
            

        }

    }

    public function verHTML(Request $request,$id){
        if($request->ajax()){
            $plantillaHTML = Plantillaemail::where('id',$id)->first();
            //$plantillaHTML = Plantillaemail::select('plantillahtml')->where('id',$id)->first();
            //$provincias = Provincia::provincias($id);
            return response()->json($plantillaHTML);
        }
    }
}
