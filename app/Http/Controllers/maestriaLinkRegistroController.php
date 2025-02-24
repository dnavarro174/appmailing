<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\Estudiante, App\Emails;
use App\Newsletter;
use App\Departamento;
//use App\ConsultaDNI;

//use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;
use Mail;
//use Excel;
use Alert;
use Auth;

class maestriaLinkRegistroController extends Controller
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

            if($id_evento == 75)
                $active_campo_link = 1;
            else
                $active_campo_link = 0;
            

            $tipos = DB::table('tipo_documento')->get();
            $dominios = DB::table('tb_email_permitos')->get();
            $grados = DB::table('e_grado_profesional')->get();
            
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

            //dd($hoy, $abrir_evento, $cerrar_evento);

            //$dt->addMinutes(61); $dt->subMinute();
            //return "fecha_limite: $f_limite - hoy: $hoy";

            // ABRIR Y CERRAR FORM  //greaterThan() greaterThanOrEqualTo
            if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
            
                //if($hoy->greaterThanOrEqualTo($cerrar_evento) or $datos->vacantes <= $datos->inscritos_invi){

                if($datos->vacantes <= $datos->inscritos_invi)
                    return view('eventos.ev.eventos_cerrado', compact('datos'));

                $countrys = DB::table('country')->select('name','phonecode')->get();
                $departamentos = Departamento::departamentos(51);
            
                return view('maestria.form-inscripcion', compact('dominios','countrys', 'departamentos', 'tipos', 'grados', 'datos', 'id_evento', 'fecha_inicial','fecha_final','active_campo_link'));

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
            'comprobante_pago' => 'required|max:1010',
            'declaracion_jurada' => 'required|max:5000',
            'ficha_inscripcion' => 'required|max:5000',
            //'cv' => 'required|max:8000',
            //'foto' => 'required|max:50',
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


        // periodo:
        $pe = $datos->lugar;
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

        $hora_cerrar = isset($datos->hora_cerrar)?$datos->hora_cerrar : 0;
        //$hora_cierre = $datos->hora;
        $fecha_cierre = \Carbon\Carbon::parse($fecha_final)->format('Y-m-d');
        $fecha_cierre = $fecha_cierre.' '.$hora_cierre;

        /*$cerrar_evento = \Carbon\Carbon::parse($fecha_cierre);
        $cerrar_evento = $cerrar_evento->subMinutes($hora_cerrar);*/
        $hoy = Carbon::now();

        $f1 = \Carbon\Carbon::parse(substr($fecha_inicial,0,10).' '.$hora_inicio)->format('Y-m-d H:i');
        $f2 = \Carbon\Carbon::parse(substr($fecha_final,0,10).' '.$hora_cierre)->format('Y-m-d H:i');
        $abrir_evento = \Carbon\Carbon::parse($f1);
        $cerrar_evento = \Carbon\Carbon::parse($f2);

        //dd($hoy, $abrir_evento, $hoy, $cerrar_evento,$hora_inicio,$hora_cierre);

        // CIERRE DE FORM  
        if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
        }else{
            return view('eventos.ev.eventos_cerrado', compact('datos'));
        }


            $xemail = "";
            $xemail_dominio = "";
            $xemail_dominio = $request->input('email_dominio');
            $xcelular = "";
            $tipo_xid = 4;

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
        $dep    = $request->input('departamento');
        $ema    = $xemail;
        $codc   = $request->input('codigo_cel');
        $cel    = $xcelular;
        $tel    = $request->input('telefono_labor');
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
        $periodo   = $pe; //dgrupo
        $apto_a    = 0; // 1: Aprobo examen - camp: confirmado
        $apto_b    = 0; // 1: apto para proceso de examén - camp: actividades_id
        $fechadepo = date("Y-m-d H:i:s",strtotime($request->input('fechadepo')));
        $ffecha    = $request->input('fechadepo');
        // tb: mae_maestria
        
        $nvoucher = $request->input('nvoucher');
        $voucher  = $request->input('voucher');
        $prov     = $request->input('provincia');
        $dis      = $request->input('distrito');

        // nuevos campos Maestría
        $fecha_nac   = $request->input('fecha_nac');
        $sexo        = $request->input('sexo');
        $email_labor = $request->input('email_labor');

        $si_cgr      = $request->input('si_cgr');
        $codigo_cgr  = mb_strtoupper($request->input('codigo_cgr'));
        $ubigeo      = mb_strtoupper($request->input('ubigeo'));
        $foto        = $request->input('foto');


        $fecha_nac = \Carbon\Carbon::parse(substr($fecha_nac,0,10))->format('d-m-Y');

        $terminos    = $request->input('check_auto');
        

        $check_est = Estudiante::where('dni_doc', $dni_doc)->count();

        if($request->comprobante_pago)
            //$ruta1 = $request->comprobante_pago->store('posgrado/'.$pe);
            $ruta1 = $request->file('comprobante_pago')->storeAs("posgrado/{$pe}","{$dni_doc}-{$id_evento}-comp-pago.".$request->file('comprobante_pago')->extension());
        else
            $ruta1 = '';

        if($request->declaracion_jurada)
            $ruta2 = $request->file('declaracion_jurada')->storeAs("posgrado/{$pe}","{$dni_doc}-{$id_evento}-dd-jj.".$request->file('declaracion_jurada')->extension());

        else
            $ruta2 = '';

        if($request->ficha_inscripcion)
            $ruta3 = $request->file('ficha_inscripcion')->storeAs("posgrado/{$pe}","{$dni_doc}-{$id_evento}-ficha.".$request->file('ficha_inscripcion')->extension());
        else
            $ruta3 = '';

        if($request->cv)
            $ruta4 = $request->file('cv')->storeAs("posgrado/{$pe}","{$dni_doc}-{$id_evento}-cv.".$request->file('cv')->extension());
        else
            $ruta4 = '';

        if($request->foto)
            $ruta5 = $request->file('foto')->storeAs("posgrado/{$pe}","{$dni_doc}-{$id_evento}-foto.".$request->file('foto')->extension());
        else
            $ruta5 = '';

            $data_es = [
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
                'updated_at'  => Carbon::now(),
                'fecha_nac'   => $fecha_nac,
                'sexo'        => $sexo,
                'email_labor' => $email_labor             
            ];
            $data_det = compact("id_evento","dni_doc","id_evento","tipo_xid","dni_doc","pe","apto_a","apto_b","fechadepo","ffecha","tipo_xid","ruta1","ruta2","ruta3","ruta4","voucher","nvoucher","prov","dis","link","si_cgr","codigo_cgr","ubigeo","ruta5");

        if($check_est == 0){
            $data_es['tipo_documento_documento_id'] = $tipdoc;
            $data_es['dni_doc'] = $dni_doc;
            $data_es['created_at'] = Carbon::now();

            // guardar
            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }


                DB::table('estudiantes')->insert($data_es);
                $this->procesaEvento($data_det);


        }else{
            // actualizar

            $check_est = Estudiante::where('dni_doc', $dni_doc)
                        ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                        ->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();
            
            if($check_est >= 1){
                return redirect('maestria-inscripcion?id='.$id_evento)->with('dni', 'Sus datos ya se encuentran registrados.');
            }

            if(!is_null($terminos)){
                // no check
            }else{
                // si acepta : Autorizo de manera expresa 
            }

                DB::table('estudiantes')->where('dni_doc',$dni_doc)->update($data_es);

                $this->procesaEvento($data_det,1);
                /*$ruta1 = $request->comprobante_pago->store('posgrado/'.$pe);
                $ruta2 = $request->declaracion_jurada->store('posgrado/'.$pe);
                $ruta3 = $request->ficha_inscripcion->store('posgrado/'.$pe);
                $ruta4 = $request->cv->store('posgrado/'.$pe);*/
                //$ruta4 = $request->voucher->store('posgrado/'.$pe);               

        } // fin actualizar


            //dd($request->all());

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
        

        return view('maestria.gracias', compact('datos'));
    }

    public function show($id)
    {
        //
    }


    #funcion
    public function procesaEvento($data, $edit=0){
        $autoincrem = DB::table('eventos')->where('id', $data["id_evento"])
        ->increment('inscritos_invi', 1);
        $check_new = Newsletter::where('estudiante_id', $data["dni_doc"])->count();

        if($check_new == 0){

            DB::table('newsletters')->insert([
                 'estado'=>'1',
                 'estudiante_id'=>$data["dni_doc"],
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now()
            ]);
        }

        if($edit==1){
            DB::table('estudiantes_act_detalle')->where('estudiantes_id',$data["dni_doc"])
        ->where('eventos_id',$data["id_evento"])
        ->where('estudiantes_tipo_id', $data["tipo_xid"])
        ->delete();
        }

        DB::table('estudiantes_act_detalle')->insert([
            'estudiantes_id'     => $data["dni_doc"],
            'eventos_id'         => $data["id_evento"],
            'dgrupo'             => $data["pe"],
            'actividades_id'     => $data["apto_a"],
            'confirmado'         => $data["apto_b"],
            'fecha_conf'         => $data["fechadepo"],//fecha pago
            'dato_extra'         => $data["ffecha"],
            'estudiantes_tipo_id'=> $data["tipo_xid"],
            'daccedio'           => 'SI',
            'estado'             => 1,
            'created_at'         => Carbon::now(),
        ]);

        $id_detalle = DB::getPdo()->lastInsertId();
        $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;

        DB::table('mae_maestria')->insert([
            'detalle_id'=> $id_detalle,
            'compago'   => $data["ruta1"],
            'decjur'    => $data["ruta2"],
            'ficins'    => $data["ruta3"],
            'cv'        => $data["ruta4"],
            'voucher'   => $data["voucher"],
            'nvoucher'  => $data["nvoucher"],
            //'fechadepo' => $prov,
            'provincia' => $data["prov"],
            'distrito'  => $data["dis"],
            'link_detalle' => $data["link"],
            'si_cgr'    => intval($data["si_cgr"]),
            'codigo_cgr'=> $data["codigo_cgr"],
            'ubigeo'    => $data["ubigeo"],
            'foto'      => $data["ruta5"]
        ]);       


    }
    
}
