<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\Estudiante, App\Emails, App\Preguntas;
use App\Newsletter;
use App\Departamento;
//use App\ConsultaDNI;

//use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;
use Mail;
//use Excel;
use Alert;
use Auth;

class formLinkRegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $dominios = DB::table('tb_email_permitos')->get();
            $grados = DB::table('e_grado_profesional')->get();

            $grupos = DB::table('e_preguntas_grupo')->get();
            
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
            // hora de inicio de form 
            $hora_inicio = $datos->hora;
            // hora de cierre de form 
            $hora_cierre = $datos->hora_fin;


            $fecha_inicio = \Carbon\Carbon::parse($fecha_inicial)->format('Y-m-d');
            $fecha_inicio = $fecha_inicio.' '.$hora_inicio;
            $abrir_evento = \Carbon\Carbon::parse($fecha_inicio);

            
            $fecha_cierre = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
            $fecha_cierre = $fecha_cierre.' '.$hora_cierre;
            $cerrar_evento = \Carbon\Carbon::parse($fecha_cierre);

            //$cerrar_evento = $cerrar_evento->subMinutes($hora_cerrar);
            $hoy = Carbon::now();

            // ABRIR Y CERRAR FORM  //greaterThan() greaterThanOrEqualTo
            if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
            
                if($datos->vacantes <= $datos->inscritos_invi)
                    return view('eventos.ev.eventos_cerrado', compact('datos'));

                $countrys = DB::table('country')->select('name','phonecode')->get();
                $departamentos = Departamento::departamentos(51);
            
                return view('eventos.especiales.form-inscripcion', compact('dominios','countrys', 'departamentos', 'tipos', 'grados', 'datos', 'id_evento', 'fecha_inicial','fecha_final', 'grupos'));

            }else{
                return view('eventos.ev.eventos_cerrado', compact('datos'));
            }
            
        }else{

            alert()->warning('El código del evento no existe', 'Advertencia');
            return redirect('eventos');

        }

        
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            //'email' => 'required|min:9|max:100|email|regex:/^[a-zA-Z\s]+$/',
            'nombres' => 'required',
            'dni_doc' =>  'required',
            'ap_paterno' => 'required',
            'grupo'      => 'required',
            'pregunta'   => 'required',
        ]);

        $id_evento = $request->input('eventos_id');
        if($id_evento){

            $n = DB::table('eventos')->where('id', $id_evento)->count();
            if($n == 0){
                return abort(404);
                //return redirect()->route('eventos.index');
            }
        }

        // Obtenemos datos del evento

        $datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',$id_evento)
                            ->orderBy('e.id', 'desc')
                            ->first();

        $fecha_inicial = $datos->fechai_evento;
        $fecha_final = $datos->fechaf_evento;
        // hora de inicio de form 
        $hora_inicio = $datos->hora;
        // hora de cierre de form 
        $hora_cierre = $datos->hora_fin;


        $fecha_inicio = \Carbon\Carbon::parse($fecha_inicial)->format('Y-m-d');
        $fecha_inicio = $fecha_inicio.' '.$hora_inicio;
        $abrir_evento = \Carbon\Carbon::parse($fecha_inicio);

        
        $fecha_cierre = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
        $fecha_cierre = $fecha_cierre.' '.$hora_cierre;
        $cerrar_evento = \Carbon\Carbon::parse($fecha_cierre);

        //$cerrar_evento = $cerrar_evento->subMinutes($hora_cerrar);
        $hoy = Carbon::now();

        // ABRIR Y CERRAR FORM  //greaterThan() greaterThanOrEqualTo
        if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
        
            if($datos->vacantes <= $datos->inscritos_invi)
                return view('eventos.ev.eventos_cerrado', compact('datos'));

        }else{
            return view('eventos.ev.eventos_cerrado', compact('datos'));
        }


            $xemail = "";
            $xemail_dominio = "";
            $xemail_dominio = $request->input('email_dominio');
            $xcelular = "";
            $tipo_xid = 6;


        /*if($request->input('email') && $request->input('email_dominio'))
        {
            $xemail = $request->input('email');
            $arr = explode('@',$xemail);
            $xemail = $arr[0].$xemail_dominio;
        }else{
            $xemail = $request->input('xemail');
            $arr = explode('@',$xemail);
            $xemail = $arr[0].$xemail_dominio;
        }
        
        if($request->input('celular'))
        {
            $xcelular = $request->input('celular');
        }else{
            $xcelular = $request->input('xcelular');
        }*/

        $xemail = $request->input('email');

        // tb: estudiantes
        $tipdoc = 1; //$request->input('tipo_doc');
        $dni_doc= mb_strtoupper($request->input('dni_doc'));
        $nom    = mb_strtoupper($request->input('nombres'));
        $appat  = mb_strtoupper($request->input('ap_paterno'));
        $apmat  = mb_strtoupper($request->input('ap_materno'));
        $dir    = mb_strtoupper($request->input('direccion'));
        $pais   = mb_strtoupper($request->input('pais'));
        $dep    = $request->input('departamento');
        $ema    = $xemail;
        $codc   = $request->input('codigo_cel');
        $cel    = $xcelular;
        $tel    = $request->input('email_labor');
        $disca  = mb_strtoupper($request->input('discapacidad'));
        $gru    = mb_strtoupper($request->input('grupo'));
        $car    = mb_strtoupper($request->input('cargo'));
        $org    = mb_strtoupper($request->input('organizacion'));
        $prof   = mb_strtoupper($request->input('profesion'));
        $ent    = mb_strtoupper($request->input('entidad'));
        $gprof  = $request->input('gradoprof');
        $ip     = request()->ip();
        $nav    = get_browser_name($_SERVER['HTTP_USER_AGENT']);
        $link   = $request->input('link_detalle');

        // tb: estudiantes_act_detalle
        $autorizo_email    = $request->input('autorizo');


        // tb: preguntas
        
        $pregunta = $request->input('pregunta');
        $grupo_id = $request->input('grupo_id');
        $prov     = $request->input('provincia');
        $dis      = $request->input('distrito');


        $terminos    = $request->input('check_auto');
        $link = route('form_link.create', ['id'=>$id_evento]);

        $check_est = Estudiante::where('dni_doc', $dni_doc)->count();

        if($check_est == 0){
            // guardar
            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

                DB::table('estudiantes')->insert([
                     'tipo_documento_documento_id'=> $tipdoc,
                     'dni_doc'     => $dni_doc,
                     'nombres'     => $nom,
                     'ap_paterno'  => $appat,
                     'ap_materno'  => $apmat,
                     'direccion'   => $dir,
                     'pais'        => $pais,
                     'region'      => $dep,
                     'email'       => $ema,
                     'codigo_cel'  => $codc,
                     'celular'     => $cel,
                     'telefono'    => $tel,
                     'discapacitado'=> $disca,
                     //'grupo'       => $gru,
                     'cargo'       => $car,
                     'organizacion'=> $org,
                     'profesion'   => $prof,
                     'entidad'     => $ent,
                     'gradoprof'   => $gprof,
                     'ip'          => $ip,
                     'navegador'   => $nav,

                     'estado'      => 1,
                     'tipo_id'     => $tipo_xid,//tb_estudiantes_tipo
                     'updated_at'  => Carbon::now(),
                     'created_at'  => Carbon::now(),
                ]);

                DB::table('eventos')->where('id', $id_evento)
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
                     'estudiantes_id'     => $dni_doc,
                     'eventos_id'         => $id_evento,
                     'dgrupo'             => $gru,
                     //'actividades_id'     => $apto_a,
                     'confirmado'         => $autorizo_email,
                     //'dato_extra'         => $ffecha,
                     'estudiantes_tipo_id'=> $tipo_xid,
                     'daccedio'           => 'SI',
                     'estado'             => 1,
                     'created_at'         => Carbon::now(),
                ]);

                $id_detalle = DB::getPdo()->lastInsertId();
                $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;

                $correlativo = Preguntas::where('eventos_id', $id_evento)
                        //->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();

                $correlativo = isset($correlativo) ? $correlativo : 0 ;
                $correlativo += 1;

                DB::table('e_preguntas')->insert([
                     'detalle_id' => $id_detalle,
                     'pregunta'   => $pregunta,
                     'grupo_id'   => $grupo_id,
                     'provincia'  => $prov,
                     'distrito'   => $dis,
                     'correlativo'=> $correlativo,
                     'eventos_id' => $id_evento,
                ]);

                $id_pregunta = DB::getPdo()->lastInsertId();
                $id_pregunta = isset($id_pregunta) ? $id_pregunta : 0 ;

        }else{
            // actualizar

            $check_est = Estudiante::where('dni_doc', $dni_doc)
                        ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                        ->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();
            
            /*if($check_est >= 1){
                //return redirect('form-cgr?id='.$id_evento)->with('dni', 'Sus datos ya se encuentran registrados.');
                alert()->warning('Sus datos ya se encuentran registrados.', 'Advertencia');
                return redirect()->back();
            }*/

            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

                DB::table('estudiantes')->where('dni_doc',$dni_doc)->update([
                     'nombres'     => $nom,
                     'ap_paterno'  => $appat,
                     'ap_materno'  => $apmat,
                     'direccion'   => $dir,
                     'pais'        => $pais,
                     'region'      => $dep,
                     'email'       => $ema,
                     'codigo_cel'  => $codc,
                     'celular'     => $cel,
                     'telefono'    => $tel,
                     'accedio'     => $disca,
                     //'grupo'       => $gru,
                     'cargo'       => $car,
                     'organizacion'=> $org,
                     'profesion'   => $prof,
                     'entidad'     => $ent,
                     'gradoprof'   => $gprof,
                     'ip'          => $ip,
                     'navegador'   => $nav,

                     'estado'      => 1,
                     'tipo_id'     => $tipo_xid,//tb_estudiantes_tipo
                     'updated_at'  => Carbon::now(),
                ]);

                $autoincrem = DB::table('eventos')->where('id', $id_evento)
                                    ->increment('inscritos_invi', 1);
                
                
                $check_new = Newsletter::where('estudiante_id', $dni_doc)->count();

                
                /*
                DB::table('estudiantes_act_detalle')->where('estudiantes_id',$dni_doc)
                            ->where('eventos_id',$id_evento)
                            ->where('estudiantes_tipo_id', $tipo_xid)
                            ->delete();*/

                DB::table('estudiantes_act_detalle')->insert([
                    'estudiantes_id'     => $dni_doc,
                    'eventos_id'         => $id_evento,
                    'dgrupo'             => $gru,
                    //'actividades_id'     => $apto_a,
                    'confirmado'         => $autorizo_email,
                    //'dato_extra'         => $ffecha,
                    'estudiantes_tipo_id'=> $tipo_xid,
                    'daccedio'           => 'SI',
                    'estado'             => 1,
                    'created_at'         => Carbon::now(),
                ]);

                $id_detalle = DB::getPdo()->lastInsertId();
                $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;

                $correlativo = Preguntas::where('eventos_id', $id_evento)
                        //->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();

                $correlativo = isset($correlativo) ? $correlativo : 0 ;
                $correlativo += 1;

                DB::table('e_preguntas')->insert([
                     'detalle_id' => $id_detalle,
                     'pregunta'   => $pregunta,
                     'grupo_id'   => $grupo_id,
                     'provincia'  => $prov,
                     'distrito'   => $dis,
                     'correlativo'=> $correlativo,
                     'eventos_id' => $id_evento,
                ]);

                $id_pregunta = DB::getPdo()->lastInsertId();
                $id_pregunta = isset($id_pregunta) ? $id_pregunta : 0 ;

        } // fin actualizar


            // Obtenemos datos del evento
            // ENVIAR EMAIL

            $celular = $codc.$xcelular;
            $email   = $xemail;
            $nombre  = $nom;
            $dni     = $dni_doc;
            $nombres = $nom." ".$appat;
            $nombres_apat = $appat;
            $nombres_amat = $apmat;

            $flujo_ejecucion = 'CONFIRMACION';
            $asunto = '[CONFIRMACIÓN] '.$datos->email_asunto;
            $id_plantilla = $id_evento; //ID EVENTO
            $from = Emails::findOrFail($datos->email_id);
            $plant_confirmacion   = $datos->p_conf_registro;
            $plant_confirmacion_2 = $datos->p_conf_registro_2;

            $msg_text = $datos->p_conf_registro;// plantila emailp_preregistro_2
            $msg_cel  = $datos->p_conf_registro_2;// plantila whats

            //obtengo la plantilla

            $file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
            fwrite($file,$plant_confirmacion);
            fclose($file);

            $datos_html = array (
                'nombre'      => $nombres,
                'email'       => $email,
                'dni'         => $dni,
                'pregunta'    => $pregunta,
                'tematica'    => $gru,
                'id_pregunta' => $id_pregunta,
                'title'       => $asunto,
                'link'        => $link,
                'correlativo' => $correlativo
            );

            if($autorizo_email == "1") {

                $datos_email = array(
                        'estudiante_id' => $dni,
                        'email'    => $email,
                        'from'     => $from->email,
                        'from_name'=> $from->nombre,
                        'name'     => $nombres,
                        'asunto'   => $asunto,
                    );

                $data = array(
                    'detail'    => "Mensaje enviado",
                    'html'      => $msg_text,
                    'email'     => $email,
                    'id'        => $dni,
                    'nombre'    => $nombres,
                    'pregunta'    => $pregunta,
                    'tematica'    => $gru,
                    'id_pregunta' => $id_pregunta,
                    'link'        => $link,
                    'correlativo' => $correlativo
                );

                    /*CONDICION CON PARAMETRO DEL FORM EVENTO*/
                    if($datos->confirm_email == 1){
                        
                        if($email != "" AND filter_var($email, FILTER_VALIDATE_EMAIL)){

                            Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                $mensaje->from($datos_email['from'], $datos_email['from_name'])
                                ->to($datos_email['email'], $datos_email['name'])
                                ->subject($datos_email["asunto"]);
                            });

                            DB::table('historia_email')->insert([
                                'tipo'              =>  'EMAIL',
                                'fecha'             => Carbon::now(),
                                'estudiante_id'     => $dni,
                                'plantillaemail_id' => $id_plantilla,
                                'flujo_ejecucion'   => $flujo_ejecucion,
                                'eventos_id'        => $id_plantilla,
                                'fecha_envio'       => Carbon::now(),//'2000-01-01'
                                'asunto'            => $asunto,
                                'nombres'           => $nombre,
                                'email'             => $email,
                                'celular'           => '',//$celular,
                                'msg_text'          => $msg_text,
                                'msg_cel'           => '',//$msg_cel,
                                'created_at'        => Carbon::now(),
                                'updated_at'        => Carbon::now()
                            ]);

                        }

                    }

        }

        return view('eventos.especiales.gracias', compact('datos', 'datos_html'));
    }

    public function show($id)
    {
        //
    }

    
}
