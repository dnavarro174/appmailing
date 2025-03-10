<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;
use Cache;
use Carbon\Carbon;
use App\Estudiante, App\Emails, App\estudiantes_act_detalle, App\Models\Mod_ddjj;
use App\Newsletter;
use App\Departamento;
use App\Area;
use App\AccionesRolesPermisos;
use Mail;
use Alert;
use App\Imaddjj,App\Ubdepartamento, App\Ubprovincia, App\Ubdistrito,App\CatCurso, App\Evento;
use Auth;
use PDF;
use DateTime;
use File;
use ZipArchive;

class ddjjLinkRegistroController extends Controller
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
            }

            if($id_evento == 75)
                $active_campo_link = 1;
            else
                $active_campo_link = 0;

            $tipos = DB::table('tipo_documento')->get();
            $dominios = DB::table('tb_email_permitos')->get();
            $grados = DB::table('e_grado_profesional')->get();
            $cursos = DB::table('m4_cursos')->select('id','nom_curso','cod_curso')
                    ->where('evento_id',$request->id)
                    ->where('status',1)
                    ->orderBy('nom_curso','ASC')->get();

            $modalidad = DB::table('m4_cursos')->select('id','modalidad')
                    ->where('evento_id',$request->id)
                    ->groupBy('modalidad')
                    ->orderBy('modalidad','ASC')->get();

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
            //if($hoy->greaterThanOrEqualTo($cerrar_evento) or $datos->vacantes <= $datos->inscritos_invi){
            if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
            
                if($datos->vacantes <= $datos->inscritos_invi)
                    return view('eventos.ev.eventos_cerrado', compact('datos'));

                $countrys = DB::table('country')->select('name','phonecode')->get();
                $departamentos = Departamento::departamentos(51);
                $datos_reg = [];
                if($request->t && $request->id && $request->d && $request->de)
                {
                    // Jalar datos de participante
                    if($request->t == 'obs'){
                        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')->where('de.estudiantes_id',$request->d);
                        $q->join('m4_ddjj as m','m.detalle_id','=','de.id');//`m`.`detalle_id` = `de`.`id`
                        $q->select('estudiantes.*','de.*','m.*');
                        $q->where('de.eventos_id',$request->id);
                        $q->where('m.detalle_id',$request->de);

                        $datos_reg = $q->first();
                        $query = str_replace(array('?'), array('\'%s\''), $q->toSql());$query = vsprintf($query, $q->getBindings());
                        //dd($datos_reg->curso);//Relacion modelo: Estudiante
                        $cant = $q->get();
                        if(count($cant)>0) {
                            return view('ddjj.form-inscripcion', compact('datos_reg','cursos','modalidad','dominios','countrys', 'departamentos', 'tipos', 'grados', 'datos', 'id_evento', 'fecha_inicial','fecha_final','active_campo_link'));
                        }
                    }
                    
                }
            
                return view('ddjj.form-inscripcion', compact('datos_reg','cursos','modalidad','dominios','countrys', 'departamentos', 'tipos', 'grados', 'datos', 'id_evento', 'fecha_inicial','fecha_final','active_campo_link'));

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
            'email'        => 'required|email',
            'nombres'      => 'required',
            'dni_doc'      => 'required',
            'foto'         => 'required|max:70',
            'nombre_curso' => 'required',
            'pais'         => 'required',
            'departamento' => 'required',
            //'provincia'    => 'required',
            //'distrito'     => 'required',
        ]);
        
        $id_evento = $request->input('eventos_id');

        if($id_evento){
            $n = DB::table('eventos')->where('id', $id_evento)->count();
            if($n == 0)return abort(404);
        }

        // Obtenemos datos del evento
        $datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',$id_evento)
                            ->orderBy('e.id', 'desc')
                            ->first();
        // periodo:
        $pe        = $datos->lugar;
        $email_bbc = $datos->fecha_texto;

        $fecha_inicial = $datos->fechai_evento;
        $fecha_final   = $datos->fechaf_evento;
        
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

        // CIERRE DE FORM  //greaterThan()
        if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
            // ingresar
            
        }else{
            // evento cerrado
            return view('eventos.ev.eventos_cerrado', compact('datos'));
        }
        
        $dni_doc   = $request->dni_doc;
        $codcurso  = $request->cod_curso;
        $no_curso  = $request->nombre_curso;
        $cod_curso = $request->nom_curso;//IDcurso
        $tipo      = $request->t?$request->t:0;
        $d         = $request->d?$request->d:0;
        $de        = $request->de?$request->de:0;
        
        if($tipo==0){
            // validación no se repida: x dni y cod_curso
            $existeReg = estudiantes_act_detalle::join('m4_ddjj as dj','dj.detalle_id','=','estudiantes_act_detalle.id')
                ->where('estudiantes_act_detalle.estudiantes_id',$dni_doc)
                ->where('dj.curso_id',$cod_curso)
                ->where('dj.evento_id',$id_evento)
                ->count();
        
            if($existeReg >= 1){
                alert()->warning('Alerta',"El Participante ya esta registrado en el curso: $no_curso");
                return redirect()->back();
            }
        }

            $tipo_xid = 7; //FORM DJ TB:estudiantes_tipo
            $xemail = $request->input('email');
            
        /* if($request->input('email') && $request->input('email_dominio'))
        {
            $xemail = $request->input('email');
            $arr = explode('@',$xemail);
            $xemail = $arr[0].$xemail_dominio;
        }else{
            $xemail = $request->input('xemail');
            $arr = explode('@',$xemail);
            $xemail = $arr[0].$xemail_dominio;
        } */
        
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
        $dep    = $request->input('departamento');
        $prov   = $request->input('provincia');
        $dis    = $request->input('distrito');
        $ema    = $xemail;
        $ema2   = $request->input('email_labor');
        $codc   = $request->input('codigo_cel');
        $cel    = $xcelular;
        $tel    = $request->input('telefono');
        $disca  = mb_strtoupper($request->input('discapacidad'));
        $car    = mb_strtoupper($request->input('cargo'));
        $org    = mb_strtoupper($request->input('organizacion'));
        $prof   = mb_strtoupper($request->input('profesion'));
        $ent    = mb_strtoupper($request->input('entidad'));
        $gprof  = mb_strtoupper($request->input('gradoprof'));
        $ip     = request()->ip();
        $nav    = get_browser_name($_SERVER['HTTP_USER_AGENT']);
        $link   = $request->input('link_detalle');
        
        // tb: estudiantes_act_detalle
        $gru    = mb_strtoupper($request->input('grupo'));
        
        // tb: m4_ddjj

        /* $fe_ini = date("Y-m-d H:i:s", strtotime($request->input('fech_ini')));
        $fe_fin = date("Y-m-d H:i:s", strtotime($request->input('fech_fin'))); */

        $contrato = $request->si_cgr;
        $f_fin = $request->input('fecha_fin');
        
        if(!$contrato){
            $f_fin = date("Y-m-d H:i:s", strtotime($f_fin));
        }else{
            $contrato = 'INDETERMINADO';
        }
        $f_ini = date("Y-m-d H:i:s", strtotime($request->input('fecha_inicio')));

        $fe_fin = $request->fech_fin;
        $fe_ini = $request->fech_ini;
        if(validar_fecha_espanol($fe_ini)){ 
            $valores = explode('/', $fe_ini);
            $fe_ini = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;
            
        }else{
            $flag_error = 1;
        }
        
        if(validar_fecha_espanol($fe_fin)){ 
            $valores = explode('/', $fe_fin);
            $fe_fin = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;
            
        }else{
            $flag_error = 1;
        }
        
        // error fechas
        if($flag_error == 1) {
            alert()->warning('Advertencia','Error en los campos de las fechas');
            return redirect()->back();
        }

        $moda     = $request->input('moda_contractual');
        $id_curso = $request->input('nom_curso');
        $cod_curso = $request->input('cod_curso');
        
        $preg_1 = $request->preg_1;
        $preg_2 = $request->preg_2;
        $preg_3 = $request->preg_3;
        $preg_4 = $request->preg_4;
        $preg_5 = $request->preg_5;
        $preg_6 = $request->preg_6;

        // firma digital
        if($request->foto){

            $img_firma = $request->file('foto');
            $firma = "firma-{$dni_doc}-{$id_evento}.".$img_firma->getClientOriginalExtension();
            $img_firma->move('storage/ddjj-firmas', $firma);
            
        }else{
            $firma = '';
        }
        
        $terminos    = $request->input('check_auto');

        $data_es = [
            'tipo_documento_documento_id'=> $tipdoc,
            
            'nombres'     => $nom,
            'ap_paterno'  => $appat,
            'ap_materno'  => $apmat,
            'direccion'   => $dir,
            'pais'        => $pais,
            'region'      => $dep,
            'provincia'   => $prov,
            'distrito'    => $dis,
            'email'       => $ema,
            'email_labor' => $ema2,
            'codigo_cel'  => $codc,
            'celular'     => $cel,
            #'telefono'    => $tel,
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
            'created_at'  => Carbon::now(),
            
        ];

        $data_det = [
            /* 
            0: SELECCIONE
            1: SI
            2: NO
            */

            //Aprobo = 3 => valor que significa que NO SE selecciono SI / NO
            'estudiantes_id'     => $dni_doc,
            'eventos_id'         => $id_evento,
            'dgrupo'             => $gru,
            'actividades_id'     => 0,// Aprobo
            'confirmado'         => 0,// accedio a beca
            //'fecha_conf'       => $fechadepo,//fecha pago
            'dato_extra'         => '',
            'estudiantes_tipo_id'=> $tipo_xid,
            'daccedio'           => 'SI',
            'estado'             => 1,
            'created_at'         => Carbon::now(),
        ];
        
        $check_est = Estudiante::where('dni_doc', $dni_doc)->count();
        
        if($check_est == 0){
            // guardar
            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

                $data_es['dni_doc']    = $dni_doc;

                DB::table('estudiantes')->insert($data_es);

                $id_estudiante = DB::getPdo()->lastInsertId();
                $id_estudiante = isset($id_estudiante) ? $id_estudiante : 0 ;

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

                DB::table('estudiantes_act_detalle')->insert($data_det);

                $id_detalle = DB::getPdo()->lastInsertId();
                $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;

        }else{
            // actualizar
            
            $data_es['updated_at'] = Carbon::now();

            $check_est = Estudiante::where('dni_doc', $dni_doc)
                        ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                        ->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();

            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

                $estt = Estudiante::where('dni_doc', $dni_doc)->select('id')->first();
                
                $id_estudiante = isset($estt->id) ? $estt->id : 0 ;

                DB::table('estudiantes')->where('dni_doc',$dni_doc)->update($data_es);

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

                // Actualizar campos:
                
                #falta validar para que cuando es tipo = obs se actualice y
                if($tipo==="obs"){
                    
                    $n = estudiantes_act_detalle::where('estudiantes_id',$dni_doc)
                            ->where('eventos_id',$id_evento)
                            ->where('id', $de)
                            ->count();

                    if($n>0){
                        estudiantes_act_detalle::where('estudiantes_id',$dni_doc)
                            ->where('eventos_id',$id_evento)
                            ->where('id', $de)
                            ->update($data_det);

                        $id_detalle = $de;
                    }

                }else{
                    
                    // Para permitir varios registros con el mismo DNI
                    /* estudiantes_act_detalle::where('estudiantes_id',$dni_doc)
                        ->where('eventos_id',$id_evento)
                        ->where('estudiantes_tipo_id', $tipo_xid)
                        ->delete(); */

                    estudiantes_act_detalle::insert($data_det); 
                    
                    $id_detalle = DB::getPdo()->lastInsertId();
                    $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;
                }

        } // fin actualizar
        
        if($id_detalle > 0 and $id_estudiante>0){
            $n = Mod_ddjj::where('detalle_id',$de)
                    ->where('evento_id',$id_evento)
                    ->count();

            $data_ddjj = [
                'estudiante_id'   => $id_estudiante,
                'detalle_id'      => $id_detalle,
                'curso_id'        => $id_curso,
                'cod_curso'       => $cod_curso,
                //'nom_curso'       => $nom_curso,
                //'f_ini_curso'     => $fe_ini,
                //'f_fin_curso'     => $fe_fin,
                'evento_id'       => $id_evento,
                'moda_contractual'=> $moda,
                'fecha_inicio'    => $f_ini,
                'fecha_fin'       => $f_fin,
                'contrato'        => $contrato,
                'preg_1'          => $preg_1,
                'preg_2'          => $preg_2,
                'preg_3'          => $preg_3,
                'preg_4'          => $preg_4,
                'preg_5'          => $preg_5,
                'preg_6'          => $preg_6,
                'firma'           => $firma
            ];
            
            if($n>0){
                $ddjj = Mod_ddjj::where('detalle_id',$de)
                    ->where('evento_id',$id_evento)
                    ->update($data_ddjj);

            }else{
                Mod_ddjj::insert($data_ddjj);
                $m4_detalle_id = DB::getPdo()->lastInsertId();
            }

        }
        
        $path = "storage/ddjj/$pe";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        
        $file = "storage/ddjj/$pe/".$id_evento."-".$dni_doc."-".$id_detalle.".pdf";
        #test view
        //return view('ddjj.ficha_ddjj_pdf', ["datos"=>$this->getDataPDFDDJJ($dni_doc,$id_evento,$id_detalle)]);

        # Forzar carga img
        $pdf = PDF::loadView('ddjj.ficha_ddjj_pdf', ["datos"=>$this->getDataPDFDDJJ($dni_doc,$id_evento,$id_detalle)]);
        $pdf->setPaper('A4','landscape');
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $pdf->save($file);
        
        /*# en el SERVIDOR
        $pdf = PDF::loadView('ddjj.ficha_ddjj_pdf', ["datos"=>$this->getDataPDFDDJJ($dni_doc,$id_evento,$id_detalle)])
                ->setPaper('A4')->setOptions(['defaultFont' => 'Arial'])->save($file);
        */
        
                    // MSG EMAIL 
                    if($datos->confirm_email == 1){

                        // Obtenemos datos del evento
                        // ENVIAR EMAIL CON DJ

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

                        $msg_text = $datos->p_conf_registro;
                        $msg_cel  = $datos->p_conf_registro_2;

                        //obtengo la plantilla
                        $file_plantilla=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                        fwrite($file_plantilla,$plant_confirmacion);
                        fclose($file_plantilla);

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
                        
                        if($email != "" AND filter_var($email, FILTER_VALIDATE_EMAIL) AND $msg_text!=""){

                            Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email,$pdf,$file){
                                $mensaje->from($datos_email['from'], $datos_email['from_name'])
                                ->to($datos_email['email'], $datos_email['name'])
                                ->subject($datos_email["asunto"]);
                                if($datos_email['email_bbc']!="")$mensaje->bcc($datos_email['email_bbc']);
                                    
                                $mensaje->attach($file);
                            });
                                //Lo procesa en la memoria
                                //$mensaje->attachData($pdf->output(), basename($file));

                            DB::table('historia_email')->insert([
                                'tipo'              =>  'EMAIL',
                                'fecha'             => Carbon::now(),
                                'estudiante_id'     => $dni,
                                'plantillaemail_id' => $id_plantilla,
                                'flujo_ejecucion'   => $flujo_ejecucion,
                                'eventos_id'        => $id_plantilla,
                                'fecha_envio'       => Carbon::now(),
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
                  
                    // MSG WHATS 
                    if($datos->confirm_msg == 1){

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
        
        Cache::flush();
        return view('ddjj.gracias', compact('datos'));
    }

    public function getDataPDFDDJJ($dni_doc, $evento_id, $detalle_id = null){
        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('de.eventos_id',$evento_id)
            ->where('estudiantes.dni_doc',$dni_doc)        ;
        $q->join('m4_ddjj as dd','de.id','=','dd.detalle_id')
            ->where('dd.detalle_id',$detalle_id)
            ->select('*')
            ->orderBy('dd.id', 'DESC');
        return $q->get()->first();
        
    }

    function generaPDF($id_evento,$dni,$detalle_id=null){
        
        $datos = DB::table('eventos as e')
                            ->select('e.lugar')
                            ->where('e.id',$id_evento)
                            ->first();
        
        // periodo: codigo
        $pe = $datos->lugar;

        $path = public_path()."/storage/ddjj/$pe";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = "storage/ddjj/$pe/".$id_evento."-".$dni."-".$detalle_id.".pdf";

        $pdf = PDF::loadView('ddjj.ficha_ddjj_pdf', ["datos"=>$this->getDataPDFDDJJ($dni,$id_evento,$detalle_id)]);
        $pdf->setPaper('A4','landscape');
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $pdf->save($file);
        return $pdf->stream();
        #return $pdf->download();
    }

    function getCurso($cod_curso,$evento_id){
        
        $datos = DB::table('m4_cursos')->where('id',$cod_curso)
            ->where('evento_id',$evento_id)
            ->where('status',1)
            ->get();
        return compact('datos');
    }

    function getTipoInscripcion(Request $request){
        
        $mod = $request->get('mod');
        $evento_id = $request->get('evento_id');
        
        $data = DB::table('m4_cursos')
            //->where('tpo',1)
            ->where('status',1)
            ->where('modalidad',$mod)
            ->where('evento_id',$evento_id)
            ->get();
        
        $output = '<option value="" class="text-uppercase">SELECCIONE CURSO</option>';
        foreach($data as $row){
            $output .= '<option value="'.$row->id.'">'.$row->nom_curso.'</option>';
        }
        echo $output;
        
    }

    public function comprimido_zip($id){
        
            $evento = Evento::where('id',$id)->select('lugar')->firstOrFail();
        
            $zip = new ZipArchive();
            $fileName = "ddjj-$id-".time().".zip";

            if($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE){
                #$path_pdf = public_path("storage/ddjj/".$evento->lugar);
                #$path2 = url("storage/ddjj/".$evento->lugar);
                //Devuelve TRUE
                $directory = "storage/ddjj/".$evento->lugar;
                $existe_directory = file_exists( $directory );

                #dd($path_pdf, $path2, $existe_directory);
                #if (!File::exists($path_pdf)){
                if (!$existe_directory){
                    alert()->warning('Advertencia','La Declaración Jurada no tiene archivos');
                    return redirect()->back();
                }
                $files = File::files($directory);

                foreach($files as $key => $value){
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                $zip->close();
            }

            return response()->download(public_path($fileName));
        
    }

    public function migracion(Request $request)
    {
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        //$dat = Imaddjj::skip(0)->take(1000)->get();//1000
        
        echo "Iniciando.<br>";
        echo Carbon::now();
        echo "<br>";exit();
        $tipo_xid = 7; //FORM DJ TB:estudiantes_tipo
        $id_evento = 163; //140 ID evento de prueba
        //DB::table('m4_ddjj')->truncate();
        
        foreach($dat as $key => $d ){
            //if($key <= 10){
                echo "Codigo:  $key - DNI: $d->doc_iden / $d->codigo_curso - $d->curso <br>";

                $nombres = $d->nombres;
                
                $ap_paterno = $d->ap_paterno;
                $ap_materno = $d->ap_materno;
                $tipo_doc = $d->tipo_doc;
                $doc_iden = $d->doc_iden;
                $dni_doc = $d->doc_iden;
                $direccion = $d->direccion;
                $departamento = $d->departamento;
                $provincia = $d->provincia;
                $distrito = $d->distrito;
    
                //jalar departamento
                //jalar provincia
                //jalar distrito
    
                //$sql="SELECT * FROM ubdepartamento where idDepa=".$departamento." ORDER BY departamento asc";
                $depa = Ubdepartamento::where('idDepa',$departamento)->select('departamento')->first();
                $dep = $depa->departamento;
    
                //$sql2="SELECT * FROM ubprovincia where idProv=".$provincia." ORDER BY provincia asc";
                $depa = Ubprovincia::where('idProv',$provincia)->select('provincia')->first();
                $prov = $depa->provincia;
    
                //$sql="SELECT * FROM ubdistrito where idDist=".$distrito." ORDER BY distrito asc";
                $depa = Ubdistrito::where('idDist',$distrito)->select('distrito')->first();
                $dis = $depa->distrito;
                //dd($dep,$prov,$dis);
    
                $email = $d->email;
                $email_2 = $d->email_2;
                $celular = $d->celular;
                $grupo = $d->condicion_colab;//grupo
                $org = $d->condicion_colab_2;//organizacion
                $cargo = $d->cargo;
                $nivel_estudio = mb_strtoupper($d->nivel_estudio);//gradoprof
    
                
                $acepto_terminos = $d->acepto_termininos;
                $fecha_reg = $d->fecha_reg;
                $ip = $d->ip;
                
                //tb: m4_ddjj
                $cod_curso = $d->codigo_curso;
                $nom_curso = $d->curso;
    
                $moda = $d->modalidad;//moda_contractual
                $f_ini = $d->fecha_ini_cont;
                $f_fin = $d->fecha_fin_cont;
    
                $preg_1 = $d->condicion_1;
                $preg_2 = $d->condicion_2;
                $preg_3 = $d->condicion_3;
                $preg_4 = $d->condicion_4;
                $preg_5 = $d->condicion_5;
                $preg_6 = $d->condicion_6;
    
                // si existe en tb: estudiantes
    
                $check_est = Estudiante::where('dni_doc', $dni_doc)->count();
            
                if($check_est == 0){
                    // guardar
                  
                        DB::table('estudiantes')->insert([
                            'tipo_documento_documento_id'=> $tipo_doc,
                            'dni_doc'     => $dni_doc,
                            'nombres'     => $nombres,
                            'ap_paterno'  => $ap_paterno,
                            'ap_materno'  => $ap_materno,
                            'direccion'   => $direccion,
                            'region'      => $dep,
                            'provincia'   => $prov,
                            'distrito'    => $dis,
                            'email'       => $email,
                            'email_labor' => $email_2,
                            'codigo_cel'  => '51',
                            'celular'     => $celular,
                            'cargo'       => $cargo,
                            'grupo'       => $grupo,
                            'organizacion'=> $org,
                            'entidad'   => $nivel_estudio,
                            'ip'          => $ip,
                            'estado'      => 1,
                            'tipo_id'     => $tipo_xid,//tb_estudiantes_tipo
                            'created_at'  => $fecha_reg,
                            //'updated_at'  => Carbon::now(),
                        ]);
    
                        $id_estudiante = DB::getPdo()->lastInsertId();
                        $id_estudiante = isset($id_estudiante) ? $id_estudiante : 0 ;
    
                        DB::table('eventos')->where('id', $id_evento)
                                            ->increment('inscritos_invi', 1);
                        
    
                }else{
                    // actualizar

                        $estt = Estudiante::where('dni_doc', $dni_doc)->select('id')->first();
                        //dd($estt->id);
                        $id_estudiante = isset($estt->id) ? $estt->id : 0 ;
    
                        DB::table('estudiantes')->where('dni_doc',$dni_doc)->update([
                            'tipo_documento_documento_id'=> $tipo_doc,
                            //'dni_doc'     => $dni_doc,
                            'nombres'     => $nombres,
                            'ap_paterno'  => $ap_paterno,
                            'ap_materno'  => $ap_materno,
                            'direccion'   => $direccion,
                            'region'      => $dep,
                            'provincia'   => $prov,
                            'distrito'    => $dis,
                            'email'       => $email,
                            'email_labor' => $email_2,
                            'codigo_cel'  => '51',
                            'celular'     => $celular,
                            'cargo'       => $cargo,
                            'grupo'       => $grupo,
                            'organizacion'=> $org,
                            'entidad'   => $nivel_estudio,
                            'ip'          => $ip,
                            'estado'      => 1,
                            'tipo_id'     => $tipo_xid,//tb_estudiantes_tipo
                            'created_at'  => $fecha_reg,
                            //'updated_at'  => Carbon::now(),
                        ]);
    
                        $autoincrem = DB::table('eventos')->where('id', $id_evento)
                                            ->increment('inscritos_invi', 1);
    
                } // fin actualizar

                $ide = DB::table('estudiantes_act_detalle')
                                            ->where('estudiantes_id',$dni_doc)
                                            ->where('eventos_id',$id_evento)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->select('id')->count();
                                            
                        // si existe estudiantes_act_detalle
                        if($ide == 0){
                            // si es nuevo
                            DB::table('estudiantes_act_detalle')->insert([
                                'estudiantes_id'     => $dni_doc,
                                'eventos_id'         => $id_evento,
                                'dgrupo'             => $grupo,
                                'estudiantes_tipo_id'=> $tipo_xid,
                                'daccedio'           => $acepto_terminos,
                                'estado'             => 1,
                                'created_at'         => $fecha_reg
                            ]);

                            $id_detalle = DB::getPdo()->lastInsertId();
                            $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;
                            
                        }else{
                            // actualiza estudiantes_act_detalle
                            DB::table('estudiantes_act_detalle')
                                    ->where('estudiantes_id',$dni_doc)
                                    ->where('eventos_id',$id_evento)
                                    ->where('estudiantes_tipo_id', $tipo_xid)
                                    ->update([
                                        #'estudiantes_id'     => $dni_doc,
                                        #'eventos_id'         => $id_evento,
                                        #'estudiantes_tipo_id'=> $tipo_xid,
                                        'dgrupo'             => $grupo,
                                        'daccedio'           => $acepto_terminos,
                                        'estado'             => 1,
                                        'created_at'         => $fecha_reg
                                    ]);
        
                            $ide = DB::table('estudiantes_act_detalle')
                                            ->where('estudiantes_id',$dni_doc)
                                            ->where('eventos_id',$id_evento)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->select('id')->first();

                            $id_detalle = $ide->id;
                        }
                        
                        $id_m4_dj = DB::table('m4_ddjj')
                                ->where('estudiante_id',$id_estudiante)
                                ->where('detalle_id',$id_detalle)
                                ->count();

                        // si existe m4_ddjj
                        if($id_m4_dj==0) {
                            DB::table('m4_ddjj')->insert([
                                'evento_id'       => $id_evento,
                                'estudiante_id'   => $id_estudiante,
                                'detalle_id'      => $id_detalle,
                                'cod_curso'       => $cod_curso,
                                'nom_curso'       => $nom_curso,
                                'moda_contractual'=> $moda,
                                'fecha_inicio'    => $f_ini,
                                'fecha_fin'       => $f_fin,
                                'preg_1'          => $preg_1,
                                'preg_2'          => $preg_2,
                                'preg_3'          => $preg_3,
                                'preg_4'          => $preg_4,
                                'preg_5'          => $preg_5,
                                'preg_6'          => $preg_6
        
                            ]);

                        }else{
                            
                            $buscarActividad = DB::table('actividades')->where('titulo','=',$cod_curso)->count();

                            if($buscarActividad == 0){
                                DB::table('actividades')->insert([
                                    'titulo'          => $cod_curso,
                                    'subtitulo'       => $nom_curso,
                                    'eventos_id'      => $id_evento,
                                    'created_at'      => now(),
                                ]);
    
                                $id_actividad = DB::getPdo()->lastInsertId();
                                $id_actividad = isset($id_actividad) ? $id_actividad : 0 ;

                            }else{
                                //'DDJJ: '.
                                $act = DB::table('actividades')->where('titulo','=',$cod_curso)->first();
                                $id_actividad = $act->id;
                                $titulo = $act->titulo;
                            }

                            $siRegistradoDetalle = DB::table('actividades_estudiantes')
                                ->where('eventos_id','=',$id_evento)
                                ->where('estudiantes_id','=',$dni_doc)
                                ->where('actividad_id','=',$id_actividad)
                                ->count();
                                
                            if($siRegistradoDetalle==0){
                                DB::table('actividades_estudiantes')->insert([
                                    'eventos_id'      => $id_evento,
                                    'estudiantes_id'  => $dni_doc,
                                    'actividad_id'    => $id_actividad,
                                    'fecha_reg'       => now(),
            
                                ]);

                            }
                        } 

                    
            //}//end if

            // si existe en Tb: estudiantes_act_detalle
            
        }
        echo "Terminado.<br>";
        echo Carbon::now();
    }


}
