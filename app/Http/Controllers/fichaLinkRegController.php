<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\Estudiante, App\Emails;
use App\Newsletter;
use App\Departamento;
use App\AccionesRolesPermisos;
use Mail;
use Alert;
use Auth;

class fichaLinkRegController extends Controller
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

                alert()->success('Advertencia','El código del evento no existe');
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
            

            $institucion = DB::table('m4_institucion')->orderBy('institucion','ASC')->get();
            $tipos = DB::table('tipo_documento')->get();
            $dominios = DB::table('tb_email_permitos')->get();
            $grados = DB::table('e_grado_profesional')->get();
            $grupos = DB::table('est_grupos')->get();
            
            //m5_cursos // m5_cursos_2021_3
            $cursos = DB::table('m5_cursos')
                ->orderBy('linea_capacitacion')
                ->orderBy('detalle_cursos')
                ->get();

            $optcursos = [];
            foreach($cursos as $curso){
                $linea_capacitacion = $curso->linea_capacitacion;
                $optcursos[$curso->linea_capacitacion][] = $curso;
            }
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

            $hoy = Carbon::now();

            //dd($hoy, $abrir_evento, $cerrar_evento);

            // ABRIR Y CERRAR FORM  //greaterThan() greaterThanOrEqualTo
            if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
            
            //if($hoy->greaterThanOrEqualTo($cerrar_evento) or $datos->vacantes <= $datos->inscritos_invi){

                if($datos->vacantes <= $datos->inscritos_invi)
                    return view('eventos.ev.eventos_cerrado', compact('datos'));

                $countrys = DB::table('country')->select('name','phonecode')->get();
                $departamentos = Departamento::departamentos(51);
                
                return view('docentes.form-inscripcion', compact('cursos','optcursos','dominios','countrys', 'departamentos', 'tipos', 'grados', 'grupos', 'datos', 'id_evento', 'fecha_inicial','fecha_final','institucion'));

            }else{
                return view('eventos.ev.eventos_cerrado', compact('datos'));
            }
              
          
        }else{

            alert()->warning('Advertencia','El código del evento no existe');
            return redirect('eventos');

        }

    }


    public function store(Request $request)
    {
        // Guardar Modulo 5: Formulario Docentes
        $this->validate($request,[
            //'email' => 'required|min:9|max:100|email|max:300|regex:/^[a-zA-Z\s]+$/',
            'nombres' => 'required',
            'dni_doc' =>  'required',
            //'fecha_inicio' => 'required',
            //'fecha_fin' => 'required',
        ]);

        /* DB::table('m5_datos_personales')->truncate();
                DB::table('m5_form_academica')->truncate();
                DB::table('m5_capacitaciones')->truncate();
                DB::table('m5_experiencia_laboral')->truncate();
                DB::table('m5_experiencia_doc')->truncate();
                DB::table('m5_cursos_doc')->truncate(); 
         */

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
        // periodo:
        
        $email_bbc = $datos->fecha_texto;

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

        $hoy = Carbon::now();

        //dd($hoy, $abrir_evento, $cerrar_evento);
    
        if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
            // ingresar
            
        }else{
            // evento cerrado
            return view('eventos.ev.eventos_cerrado', compact('datos'));
        }
        
        

            $xemail = "";
            $xemail_dominio = "";
            $xemail_dominio = $request->input('email_dominio');
            $xcelular = "";
            $tipo_xid = 8; //tb_estudiantes_tipo


        if($request->input('email') && $request->input('email_dominio'))
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
        }

        // tb: estudiantes
        $tipdoc = $request->input('tipo_doc');
        $dni_doc= mb_strtoupper($request->input('dni_doc'));
        $nom    = mb_strtoupper($request->input('nombres'));
        $appat  = mb_strtoupper($request->input('ap_paterno'));
        $apmat  = mb_strtoupper($request->input('ap_materno'));
        $dir    = mb_strtoupper($request->input('direccion'));
        $pais   = mb_strtoupper($request->input('pais'));
        $dep    = $request->input('departamento_do');
        $prov   = $request->input('provincia_do');
        $dis    = $request->input('distrito_do');
        $ema    = $xemail;
        $codc   = $request->input('codigo_cel');
        $cel    = $xcelular;
        $ema2   = $request->input('email_labor');
        $tel    = $request->input('telefono');
        $disca  = mb_strtoupper($request->input('discapacidad'));
        $gru    = mb_strtoupper($request->input('grupo'));
        $car    = mb_strtoupper($request->input('cargo'));
        $org    = mb_strtoupper($request->input('organizacion'));
        $prof   = mb_strtoupper($request->input('profesion'));
        $ent    = mb_strtoupper($request->input('entidad'));
        $gprof  = '';
        $ip     = request()->ip();
        $nav    = get_browser_name($_SERVER['HTTP_USER_AGENT']);


        $fec_nac = date("Y-m-d", strtotime($request->input('fecha_nac')));
        $preg_1 = $request->preg_1;
        $preg_2 = $request->preg_2;
        $preg_3 = $request->preg_3;
        $preg_4 = $request->preg_4;
        $preg_5 = $request->preg_5;
        $preg_6 = $request->preg_6;

        // tb: estudiantes_act_detalle
        $grupo   = $request->input('tipo_participante'); //dgrupo
        
        
        // tb: m5_datos_personales
        
        $moda     = $request->input('moda_contractual');
        //$tipo_participante = $request->input('tipo_participante');
        $depar_nac  = $request->input('departamento');
        $prov_nac = $request->input('provincia');
        $dis_nac  = $request->input('distrito');
        $edad  = $request->input('edad');

        $data_es = [
            'nombres'     => $nom,
            'ap_paterno'  => $appat,
            'ap_materno'  => $apmat,
            'direccion'   => $dir,
            'fecha_nac'   => $fec_nac,
            'pais'        => $pais,
            'region'      => $dep,
            'provincia'   => $prov,
            'distrito'    => $dis,
            'email'       => $ema,
            'email_labor' => $ema2,
            'codigo_cel'  => $codc,
            'celular'     => $cel,
            'telefono'    => $tel,
            'discapacitado'=> $disca,
            'grupo'       => $gru,
            'cargo'       => $car,
            'organizacion'=> $org,
            'profesion'   => $prof,
            'entidad'     => $ent,
            'gradoprof'   => $gprof,
            'ip'          => $ip,
            'navegador'   => $nav,

            'estado'      => 1,
            'tipo_id'     => $tipo_xid,//tb_estudiantes_tipo
            //'created_at'  => Carbon::now(),
        ];

        $terminos    = $request->input('check_auto');
        
        $check_est = Estudiante::where('dni_doc', $dni_doc)->count();
        
        if($check_est == 0){
            // guardar
            
            $data_es['tipo_documento_documento_id'] = $tipdoc;
            $data_es['dni_doc']    = $dni_doc;
            $data_es['created_at'] = Carbon::now();

                DB::table('estudiantes')->insert($data_es);

                $id_estudiante = DB::getPdo()->lastInsertId();
                $id_estudiante = isset($id_estudiante) ? $id_estudiante : 0 ;

                DB::table('eventos')->where('id', $id_evento)
                                    ->increment('inscritos_invi', 1);
                
                DB::table('estudiantes_act_detalle')->insert([
                     'estudiantes_id'     => $dni_doc,
                     'eventos_id'         => $id_evento,
                     'dgrupo'             => $grupo,
                     'estudiantes_tipo_id'=> $tipo_xid,
                     'daccedio'           => 'SI',
                     'estado'             => 1,
                     'created_at'         => Carbon::now(),
                ]);

                $id_detalle = DB::getPdo()->lastInsertId();
                $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;

                

        }else{
            // actualizar

            $check_est = Estudiante::where('dni_doc', $dni_doc)
                        ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                        ->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();
            
            if($check_est >= 1){
                return redirect('ficha-inscripcion?id='.$id_evento)->with('dni', 'Sus datos ya se encuentran registrados.');
            }

            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

            $data_es['updated_at'] = Carbon::now();

                DB::table('estudiantes')->where('dni_doc',$dni_doc)->update($data_es);
                
                $id_estudiante = Estudiante::where('dni_doc',$dni_doc)->select('id')->first();
                $id_estudiante = $id_estudiante->id;


                $autoincrem = DB::table('eventos')->where('id', $id_evento)
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
                            ->where('eventos_id',$id_evento)
                            ->where('estudiantes_tipo_id', $tipo_xid)
                            ->delete();

                
                DB::table('estudiantes_act_detalle')->insert([
                    'estudiantes_id'     => $dni_doc,
                    'eventos_id'         => $id_evento,
                    'dgrupo'             => $grupo,
                    'estudiantes_tipo_id'=> $tipo_xid,
                    'daccedio'           => 'SI',
                    'estado'             => 1,
                    'created_at'         => Carbon::now(),
               ]);

                $id_detalle = DB::getPdo()->lastInsertId();
                $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;



        } // fin actualizar

        // Guardamos los datos en sus respectivas tablas

                /* DB::table('m5_datos_personales')->truncate();
                DB::table('m5_form_academica')->truncate();
                DB::table('m5_capacitaciones')->truncate();
                DB::table('m5_experiencia_laboral')->truncate();
                DB::table('m5_experiencia_doc')->truncate();
                DB::table('m5_cursos_doc')->truncate(); */

                DB::table('m5_datos_personales')->insert([
                     'estudiante_id'=> $id_estudiante,
                     'detalle_id'   => $id_detalle,
                     'depar_nac'    => $depar_nac,
                     'prov_nac'     => $prov_nac,
                     'dis_nac'      => $dis_nac,
                     'edad'         => $edad,
                     
                     'preg_1'          => $preg_1,
                     'preg_2'          => $preg_2,
                     'preg_3'          => $preg_3,
                     'preg_4'          => $preg_4,
                     'preg_5'          => $preg_5,
                     'preg_6'          => $preg_6

                ]);

                $id_personal = DB::getPdo()->lastInsertId();
                $id_personal = isset($id_personal) ? $id_personal : 0 ;

                // insert tb: m5_form_academica
                $lista_2 = $request->gradoprof;
                $lista_2 = isset($lista_2)?$lista_2:0;
                if(count($lista_2)>0){
                    foreach($lista_2 as $k => $b){
                
                        DB::table('m5_form_academica')->insert([
                            'estudiante_id'=> $id_estudiante,
                            'detalle_id'   => $id_detalle,
                            'id_datos'     => $id_personal,
                            'nivel_academico'   => mb_strtoupper($request->gradoprof[$k]),
                            'carr_profesional'  => mb_strtoupper($request->carr_profesional[$k]),
                            'especialidad'      => mb_strtoupper($request->especialidad[$k]),
                            'centro_estudio_form' => mb_strtoupper($request->centro_estudios[$k]),
                            'fecha_tit'           => $request->fecha_titulacion[$k],
                            'napostillado'        => $request->napostillado[$k],

                        ]);
                    }
                }

                // insert tb: adoc_capacitaciones
                $lista_3 = $request->nom_capacitaciones;
                $lista_3 = isset($lista_3)?$lista_3:0;
                if(count($lista_3)>0){
                    foreach($lista_3 as $k => $b){

                        DB::table('m5_capacitaciones')->insert([
                            'estudiante_id'=> $id_estudiante,
                            'detalle_id'   => $id_detalle,
                            'id_datos'     => $id_personal,
                            'nombre_cap'   => mb_strtoupper($request->nom_capacitaciones[$k]),
                            'tipo_cap'     => mb_strtoupper($request->tipo_capa[$k]),
                            'centro_estudio_cap'=> mb_strtoupper($request->centro_estudios_capa[$k]),
                            'fecha_inicio_cap'  => $request->fecha_ini[$k],
                            'fecha_fin_cap'     => $request->fecha_termino[$k],
                            'horas_cron'        => $request->horas_cro[$k],
                            'condicion_actual'  => $request->condicion_actual[$k]

                        ]);
                    }
                }

                // insert tb: m5_experiencia_laboral
                $lista_4 = $request->empresa_insti;
                $lista_4 = isset($lista_4)?$lista_4:0;
                if(count($lista_4)>0){
                    foreach($lista_4 as $k => $b){

                        DB::table('m5_experiencia_laboral')->insert([
                            'estudiante_id'  => $id_estudiante,
                            'detalle_id'     => $id_detalle,
                            'id_datos'       => $id_personal,
                            'nom_empresa'    => mb_strtoupper($request->empresa_insti[$k]),
                            'tipo_industria' => mb_strtoupper($request->tipo_empresa[$k]),
                            'puesto_cargo'   => mb_strtoupper($request->cargo_puesto[$k]),
                            'modalidad_contrato'     => mb_strtoupper($request->modalidad[$k]),
                            'actividad_desarrollada' => mb_strtoupper($request->actividad_desarrollada[$k]),
                            'fecha_inicio_lab'       => $request->fecha_inicio[$k],
                            'fecha_fin_lab'          => $request->fecha_term[$k]

                        ]);
                    }
                }

                // insert tb: m5_experiencia_doc
                $lista_5 = $request->edoc_institucion;
                $lista_5 = isset($lista_5)?$lista_5:0;
                if(count($lista_5)>0){
                    foreach($lista_5 as $k => $b){

                        DB::table('m5_experiencia_doc')->insert([
                            'estudiante_id'     => $id_estudiante,
                            'detalle_id'        => $id_detalle,
                            'id_datos'          => $id_personal,
                            'institucion_exp'   => mb_strtoupper($request->edoc_institucion[$k]),
                            'nombre_institucion'=> mb_strtoupper($request->edoc_nombre[$k]),
                            'nivel'             => mb_strtoupper($request->edoc_nivel[$k]),
                            'curso_a_cargo'     => mb_strtoupper($request->edoc_curso[$k]),
                            'fecha_inicio_exp'  => $request->edoc_fecha_inicio[$k],
                            'fecha_fin_exp'     => $request->edoc_fecha_termino[$k]

                        ]);
                    }
                }

                // insert tb: m5_cursos_doc
                $lista_6 = $request->doc_cursos;
                $lista_6 = isset($lista_6)?$lista_6:0;
                
                if($lista_6>0){
                    foreach($lista_6 as $k => $b){
    
                        DB::table('m5_cursos_doc')->insert([
                            'id_datos'          => $id_personal,
                            'estudiante_id'     => $id_estudiante,
                            'detalle_id'        => $id_detalle,
                            'id_doc_cursos'     => $request->doc_cursos[$k],
    
                        ]);
                    }

                }


                /* Estudiante::where('id',$id_estudiante)->delete();
                DB::table('estudiantes_act_detalle')->where('id',$id_detalle)->delete();
                dd("guardado"); */

        // fin guardar



                // Obtenemos datos del evento
                // ENVIAR GAFETE Y EMAIL

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

                //$gafete_html = $datos->gafete_html;

                //obtengo la plantilla

                $file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                fwrite($file,$plant_confirmacion);
                fclose($file);

                /*$file=fopen(resource_path().'/views/gafete/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                fwrite($file,$gafete_html);
                fclose($file);*/

                $datos_email = array(
                        'estudiante_id' => $dni,
                        'email'    => $email,
                        'from'     => $from->email,
                        'from_name'=> $from->nombre,
                        'email_bbc'=> $email_bbc,
                        'name'     => $nombres,
                        'asunto'   => $asunto,
                    );

                $data = array(
                    'detail'    => "Mensaje enviado",
                    'html'      => $msg_text,
                    'email'     => $email,
                    'id'        => $dni,
                    'nombre'    => $nombres
                );

                    if($datos->confirm_email == 1){
                        
                        if($email != "" AND filter_var($email, FILTER_VALIDATE_EMAIL)){

                            Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                $mensaje->from($datos_email['from'], $datos_email['from_name'])
                                ->to($datos_email['email'], $datos_email['name'])
                                ->bcc($datos_email['email_bbc'])
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
                  
                    if($datos->confirm_msg == 1){

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
        

        return redirect('ficha-inscripcion?id='.$id_evento)->with('exito', "Se ha registrado satisfactoriamente, con el DNI: {$dni}"); 

        //return view('docentes.gracias', compact('datos'));
    }

    public function show($id)
    {
        //
    }

}
