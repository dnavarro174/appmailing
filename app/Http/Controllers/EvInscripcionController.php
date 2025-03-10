<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\Estudiante, App\Emails;
use App\Newsletter;
use App\Departamento;
use App\ConsultaDNI;

//use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;
use Mail;
use Excel;
use Alert;
use Auth;

use function PHPUnit\Framework\isNull;

class EvInscripcionController extends Controller
{
 
    public function index(Request $request)
    {   
        try {
            
            if(isset($request->id)){
                $eventos_id = $request->id;
            }else{

                alert()->success('El código del evento no existe', 'Advertencia');
                return redirect()->route('eventos.index');
            }
          
            return redirect('evento/ev/create', compact('eventos_id'));

        } catch (Exception $e) {
            return 'Error';
        }
        
    }

    public function create(Request $request)
    {   
        if(isset($request->id)){
            $id_evento = $request->id;

            $n = DB::table('eventos')->where('id', $id_evento)->count();
            if($n == 0){
                return abort(404);
                //return redirect()->route('eventos.index');
            }

            $tipos = DB::table('tipo_documento')->get();
            $grupos = DB::table('est_grupos')->get();
            $dominios = DB::table('tb_email_permitos')->get();
            
            //$datos = DB::table('eventos')->where('id', $id_evento)->first();

            $datos = DB::table('eventos as e')
            				->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',$id_evento)
                            ->orderBy('e.id', 'desc')
                            ->first();

            if(!isset($datos)){
                return abort(404);
            }

            $fecha_inicial = $datos->fechai_evento;
            $fecha_final = $datos->fechaf_evento;

            $hora_cerrar = isset($datos->hora_cerrar)?$datos->hora_cerrar : 0;
            $hora_cierre = $datos->hora;
            $fecha_cierre = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
            $fecha_cierre = $fecha_cierre.' '.$hora_cierre;

            $cerrar_evento = \Carbon\Carbon::parse($fecha_cierre);
            $cerrar_evento = $cerrar_evento->subMinutes($hora_cerrar);
            $hoy = Carbon::now();

            //$dt->addMinutes(61); $dt->subMinute();
            //dd($hoy . " mayor que: ".$cerrar_evento);
            //return "fecha_limite: $f_limite - hoy: $hoy";
            
            // CIERRE DE FORM  //greaterThan()
            
            if($hoy->greaterThanOrEqualTo($cerrar_evento) or $datos->vacantes <= $datos->inscritos_invi){
            //if($datos->vacantes <= $datos->inscritos_invi){

                return view('eventos.ev.eventos_cerrado', compact('datos'));
            }

            $countrys = DB::table('country')->select('name','phonecode')->orderBy('name','ASC')->get();
            $departamentos = Departamento::departamentos(51);
        
            return view('eventos.ev.create', compact('dominios','countrys', 'departamentos', 'tipos', 'grupos', 'datos', 'id_evento', 'fecha_inicial','fecha_final'));
            
        }else{

            alert()->warning('El código del evento no existe', 'Advertencia');
            return redirect('eventos');

        }

        
    }

    public function getDepartamentos(Request $request,$id){
        if($request->ajax()){
            $provincias = Departamento::departamentos($id);
            return response()->json($provincias);
        }
    }
    
    public function getDNI(Request $request,$id,$evento=0){
        
        if($request->ajax()){
            #Paso 1: si dni esta en el evento, si no hay datos, pasar a paso 2
            $datos = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                    ->select('estudiantes.tipo_id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','de.eventos_id','estudiantes.grupo','estudiantes.codigo_cel', 'de.estudiantes_tipo_id', 'de.modalidad_id')
                    ->where('de.estudiantes_id',$id)
                    ->where('de.eventos_id',$evento)
                    ->get();
            $n = count($datos);

            if($n==1){
                $nom = $datos[0]->nombres.' '.$datos[0]->ap_paterno;
                $mod = $datos[0]->Modalidad->modalidad;

                $datos = array (
                    'caso'      => 1,
                    'existe'    => 'SI',
                    'msg'       => "$nom usted se encuentra registrado en la modalidad: $mod.  Para mayor información envia un correo electronico a: caii2023@enc.edu.pe ",
                    'datos'     => ''
                );
                return $datos;
            } 
            
            #Paso 2: mostrar informacion participante
            $datos = Estudiante::
                            select('estudiantes.tipo_id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.pais','estudiantes.region','estudiantes.organizacion','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','estudiantes.email','estudiantes.codigo_cel')
                            ->where('dni_doc',$id)
                            ->get();

            $n = count($datos);
            if($n>=1){
                // HIDE EMAIL Y CELL
                $d_email = hideEmail($datos[0]->email);
                $d_cel = hideCel($datos[0]->celular);
                $xdatos = array(
                    'email'     => $d_email,
                    'celular'   => $d_cel
                );

                $datos = array (
                    'caso'      => 2,
                    'existe'    => 'SI',
                    'datos'     => $datos
                );

            }else{
                $datos = array();
                $xdatos = array();
            }

            return compact('datos','xdatos');
            //return response()->json($datos);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request,[
                'dni_doc'   =>  'required',
            ]);

            $xemail = "";
            $xemail_dominio = "";
            $xemail_dominio = $request->input('email_dominio');
            $xcelular = "";
            $tipo_xid = 5;


        if($request->input('email') && $request->input('email_dominio'))
        {
            $xemail = $request->input('email');
            $arr = explode('@',$xemail);
            $xemail = $arr[0].$xemail_dominio;

        }elseif($request->input('xemail') != ""){
            $xemail = $request->input('xemail');
            $arr = explode('@',$xemail);
            $xemail = $arr[0].$xemail_dominio;
        }else{
            $xemail = $request->input('email2');
        }
        
        if($request->input('celular'))
        {
            $xcelular = $request->input('celular');
        }else{
            $xcelular = $request->input('xcelular');
        }

        $dni_doc = mb_strtoupper($request->input('dni_doc'));
        $id_evento = $request->input('eventos_id');

        $check_est = Estudiante::where('dni_doc', $dni_doc)->count();

        $appat = mb_strtoupper($request->ap_paterno);
        $apmat = mb_strtoupper($request->ap_materno);
        $nombr = mb_strtoupper($request->nombres);
        $grup = mb_strtoupper($request->grupo);
        $carg = mb_strtoupper($request->cargo);
        $orga = mb_strtoupper($request->organizacion);
        $prof = mb_strtoupper($request->profesion);
        $codcel = mb_strtoupper($request->codigo_cel);
        $pais = mb_strtoupper($request->pais);
        $region = mb_strtoupper($request->region);
        $tpodoc = mb_strtoupper($request->tipo_doc);
        
        if($check_est == 0){
            // guardar
            if(!is_null($request->check_auto)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

                DB::table('estudiantes')->insert([
                     'dni_doc'=> $dni_doc,
                     'ap_paterno'=> $appat,
                     'ap_materno'=> $apmat,
                     'nombres'=> $nombr,
                     'grupo'=> $grup,
                     'cargo'=> $carg,
                     'organizacion'=> $orga,
                     'profesion'=> $prof,
                     'codigo_cel'=> $codcel,
                     'celular'=>$xcelular,
                     'email'=>$xemail,
                     'accedio'  => 'SI',
                     'estado'   => 1,
                     'pais'=> $pais,
                     'region'=> $region,
                     'tipo_id'=>$tipo_xid,
                     'tipo_documento_documento_id' => $tpodoc,
                     'ip'=>request()->ip(),
                     'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                     'updated_at'=>Carbon::now(),
                     'created_at'=>Carbon::now()
                ]);

                DB::table('eventos')->where('id', $request->input('eventos_id'))
                                      ->increment('inscritos_invi', 1);
                
                $check_new = Newsletter::where('estudiante_id', $dni_doc)->count();

                if($check_new == 0){

                    DB::table('newsletters')->insert([
                         'estado'=>'1',
                         'estudiante_id'=>$dni_doc,
                         'created_at'=>Carbon::now(),
                         'updated_at'=>Carbon::now()
                    ]);
                }

                DB::table('estudiantes_act_detalle')->insert([
	                 'estudiantes_id'   => mb_strtoupper($dni_doc),
	                 'eventos_id'       => $request->input('eventos_id'),
	                 'actividades_id'   => 0,
	                 'estudiantes_tipo_id'=> $tipo_xid,
	                 'confirmado'       => 0,
	                 'estado'           => 1,

                    'dgrupo'       => mb_strtoupper($request->input('grupo')),
                    'created_at'=>Carbon::now(),
                    'daccedio'      => 'SI'
                ]);

        }else{
            // actualizar

            $check_est = Estudiante::where('dni_doc', $dni_doc)
                        ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                        //->where('tipo_id',1) // tipo: pre inscritos
                        //->orWhere('tipo_id',2) // tipo: INVITADOS
                        ->where('estudiantes_act_detalle.eventos_id',$request->input('eventos_id'))
                        ->count();
            
            if($check_est >= 1){
                return redirect('eventos/ev/create?id='.$request->input('eventos_id'))->with('dni', 'Sus datos ya se encuentran registrados.');
            }

                DB::table('estudiantes')->where('dni_doc',$dni_doc)->update([
                     
                     'ap_paterno'=> $appat,
                     'ap_materno'=> $apmat,
                     'nombres'=> $nombr,
                     #'grupo'=> $grup,
                     'cargo'=> $carg,
                     'organizacion'=> $orga,
                     'profesion'=> $prof,
                     'codigo_cel'=> $codcel,
                     'celular'=>$xcelular,
                     'email'=>$xemail,
                     'accedio'  => 'SI',
                     'estado'   => 1,
                     'pais'=> $pais,
                     'region'=> $region,
                     'tipo_id'=>$tipo_xid,
                     'tipo_documento_documento_id' => $tpodoc,
                     'ip'=>request()->ip(),
                     'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                     'updated_at'=>Carbon::now(),
                     'created_at'=>Carbon::now()
                ]);

                $autoincrem = DB::table('eventos')->where('id', $request->input('eventos_id'))
                                    ->increment('inscritos_invi', 1);
                
                $check_new = Newsletter::where('estudiante_id', $dni_doc)->count();

                if($check_new == 0){

                    DB::table('newsletters')->insert([
                         'estado'=>'1',
                         'estudiante_id'=>$dni_doc,
                         'created_at'=>Carbon::now(),
                         'updated_at'=>Carbon::now()
                    ]);
                }
                
	            DB::table('estudiantes_act_detalle')->where('estudiantes_id',$dni_doc)
	                                    ->where('eventos_id',$request->input('eventos_id'))
	                                    ->where('estudiantes_tipo_id', $tipo_xid)
	                                    ->delete();

	            DB::table('estudiantes_act_detalle')->insert([
	                 'estudiantes_id'  => mb_strtoupper($dni_doc),
	                 'eventos_id'      => $request->input('eventos_id'),
	                 'actividades_id'  => 0,
	                 'estudiantes_tipo_id'=> $tipo_xid,
	                 'confirmado'   => 0,
	                 'estado'       => 1,
                     'dgrupo'       => $grup,
                    'created_at'=>Carbon::now(),
	                'daccedio'      => 'SI'
	            ]);

        } // fin actualizar


        $rs_datos = DB::table("eventos as e")
                    ->join("e_plantillas as p", "e.id","=","p.eventos_id")
                    ->where("e.id", $id_evento)
                    ->select("p.p_conf_registro","e.confirm_msg","e.confirm_email",'e.nombre_evento','p.p_conf_registro_2','e.gafete_html','p.p_conf_registro_gracias','e.email_id','e.email_asunto','e.auto_conf')
                    ->get();
            
                
                if($rs_datos[0]->auto_conf == 1){
                    // ENVIAR GAFETE Y EMAIL

                    $celular = $request->input('codigo_cel').$xcelular;
                    $email   = $xemail;
                    $nombre  = $request->input('nombres');
                    $dni     = $dni_doc;
                    $nombres_ape = $nombr." ".$appat;
                    $nombres_apat = $appat;
                    $nombres_amat = $apmat;

                    $flujo_ejecucion = 'CONFIRMACION';
                    
                    if(!empty($rs_datos[0]->email_asunto) && !empty($rs_datos[0]->email_id)){
                        $asunto = '[CONFIRMACIÓN] '.$rs_datos[0]->email_asunto;
                        $from = Emails::find($rs_datos[0]->email_id);
                        $from_email = $from->email;
                        $from_name  = $from->nombre;
                    }else{
                        $asunto = '[CONFIRMACIÓN] '.$rs_datos[0]->nombre_evento;
                        $from_email = config('mail.from.address');
                        $from_name  = config('mail.from.name');
                    }

                    $msg_text = $rs_datos[0]->p_conf_registro;// plantila emailp_preregistro_2
                    $msg_cel  = $rs_datos[0]->p_conf_registro_2;// plantila whats
                    
                }

                    $id_plantilla = $id_evento; //ID EVENTO
                    $plant_confirmacion = $rs_datos[0]->p_conf_registro;
                    $plant_confirmacion_2 = $rs_datos[0]->p_conf_registro_2;
                    $gafete_html = $rs_datos[0]->gafete_html;

                    //obtengo la plantilla

                    $file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                    fwrite($file,$plant_confirmacion);
                    fclose($file);

                    $file=fopen(resource_path().'/views/gafete/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                    fwrite($file,$gafete_html);
                    fclose($file);

                    if($rs_datos[0]->confirm_email == 1){
                        /*Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                            $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
                            $mensaje->attach($datos_email['file']);
                        });*/

                        if($email != ""){

                            DB::table('historia_email')->insert([
                                'tipo'              =>  'EMAIL',
                                'fecha'             => Carbon::now(),
                                'estudiante_id'     => $dni,
                                'plantillaemail_id' => $id_plantilla,
                                'flujo_ejecucion'   => $flujo_ejecucion,
                                'eventos_id'        => $id_plantilla,
                                'fecha_envio'       => '2000-01-01',//Carbon::now(),
                                'asunto'            => $asunto,
                                'nombres'           => $nombre,
                                'email'             => $email,
                                'celular'           => '',//$celular,
                                'msg_text'          => $msg_text,
                                'msg_cel'           => '',//$msg_cel,
                                'created_at'        => Carbon::now(),
                                'updated_at'        => Carbon::now(),
                                'from_nombre'       => $from_name,
                                'from_email'        => $from_email,
                            ]);
                        }

                    }
                  
                    if($rs_datos[0]->confirm_msg == 1){

                        // MSG WHATS 
                        if($celular != "" && strlen($celular)>= 9){
                    
                            DB::table('historia_email')->insert([
                                'tipo'              =>  'WHATS',
                                'fecha'             => Carbon::now(),
                                'estudiante_id'     => $dni,
                                'plantillaemail_id' => $id_plantilla,
                                'flujo_ejecucion'   => $flujo_ejecucion,
                                'eventos_id'        => $id_plantilla,
                                'fecha_envio'       => '2000-01-01',
                                'asunto'            => $asunto,
                                'nombres'           => $nombre,
                                'email'             => '',//$email,
                                'celular'           => $celular,
                                'msg_text'          => '',//$msg_text
                                'msg_cel'           => $msg_cel,
                                'created_at'        => Carbon::now(),
                                'updated_at'        => Carbon::now()
                            ]);
                        }
                    }
        

        return view('eventos.ev.gracias', compact('rs_datos'));

        //return redirect()->route('ev.gracias',array('id'=>$id_evento));
    }

    public function gracias(Request $request){
        dd('Gracias');
        $id_evento = $request->id;

        $datos = DB::table('eventos as e')
                    ->join('e_plantillas as p', 'e.id','=','p.eventos_id')->where('e.id', $id_evento)->first();

        if(!$datos){
            return redirect()->back();
        }
        return view('ev.gracias', compact('datos'));

    }
    
}
