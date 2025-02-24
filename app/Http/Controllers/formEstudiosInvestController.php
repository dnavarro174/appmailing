<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Estudiante, App\Emails, App\Departamento, App\Evento, App\formEstudiosInvest;
use Mail;
use Alert;

class formEstudiosInvestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request)
    {
        if(isset($request->id)){
            $id_evento = $request->id;

            $n = DB::table('eventos')->where('id', $id_evento)->count();
            if($n == 0){
                return abort(404);
                //return redirect()->route('eventos.index');
            }            

            //$tipos = DB::table('tipo_documento')->get();
            //$grados = DB::table('e_grado_profesional')->get();

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

            if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
                
                $countrys = DB::table('country')->select('name','phonecode')->get();
                $departamentos = Departamento::departamentos(51);
            
                return view('estudioinv.form-inscripcion', compact('countrys', 'departamentos', 'datos', 'id_evento', 'fecha_inicial','fecha_final'));
                
            }else{
                return view('eventos.ev.eventos_cerrado', compact('datos'));
            }
            
            
        }else{

            alert()->warning('Advertencia','El código del evento no existe');
            return redirect('eventos');

        }
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
            //'email' => 'required|min:9|max:100|email|regex:/^[a-zA-Z\s]+$/',
            'first_name' => 'required',
            'p_passport_number' =>  'required',
            //'investigation' => 'required|max:5000',
            //'p_passport_photo' => 'required|max:500',
        ]);

        $id_evento = $request->input('eventos_id');
        if($id_evento){

            $n = Evento::where('id', $id_evento)->count();
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

        $hoy = Carbon::now();

        //dd($hoy, $abrir_evento, $cerrar_evento);

        if($hoy->greaterThan($abrir_evento) and $hoy->lessThanOrEqualTo($cerrar_evento)){
        
        }else{
            // CIERRE DE FORM
            return view('eventos.ev.eventos_cerrado', compact('datos'));
        }
            

      

        $tipo_xid = 4;

        $carpeta = "files-".$id_evento;

        if($request->investigation)
            $investigation = $request->investigation->store('pre_registration/'.$carpeta);
        else
            $investigation = '';

        if($request->p_passport_photo)
            $p_passport_photo = $request->p_passport_photo->store('pre_registration/'.$carpeta);
        else
            $p_passport_photo = '';



        // tb: estudiantes
        $p_title      = $request->input('p_title');
        $p_last_name  = mb_strtoupper($request->input('p_last_name'));
        $p_first_name = mb_strtoupper($request->input('p_first_name'));
        $p_date_birth = mb_strtoupper($request->input('p_date_birth'));
        $p_date_birth = date("d/m/Y", strtotime($p_date_birth));
        $p_country_birth  = $request->input('p_country_birth');
        $p_country_residence = mb_strtoupper($request->input('p_country_residence'));
        $p_nacionality     = mb_strtoupper($request->input('p_nacionality'));
        $p_passport_number = mb_strtoupper($request->input('p_passport_number'));
        //$p_passport_photo  = $request->input('p_passport_photo');
        $p_expiration_date = $request->input('p_expiration_date');
        $p_expiration_date = date("d/m/Y", strtotime($p_expiration_date));
        $p_email    = $request->input('p_email');
        $p_personal_address = mb_strtoupper($request->input('p_personal_address'));
        $title     = mb_strtoupper($request->input('title'));
        $keywords  = mb_strtoupper($request->input('keywords'));
        $abstract  = mb_strtoupper($request->input('abstract'));
        //$investigation  = mb_strtoupper($request->input('investigation'));
        $short_biograph = mb_strtoupper($request->input('short_biograph'));
        
        $ip     = request()->ip();
        $nav    = get_browser_name($_SERVER['HTTP_USER_AGENT']);


        DB::table('inv_personal_details')->insert([
            'p_pronom'     => $p_title,
            'p_last_name'  => $p_last_name,
            'p_first_name' => $p_first_name,
            'p_date_birth' => $p_date_birth,
            'p_country_of_birth'  => $p_country_birth,
            'p_country_residence' => $p_country_residence,
            'p_nacionality'       => $p_nacionality,
            'p_passport_number'   => $p_passport_number,
            'p_passport_photo'    => $p_passport_photo,
            'p_expiration_date'   => $p_expiration_date,
            'p_email'             => $p_email,
            'p_personal_address'  => $p_personal_address,
            'title'               => $title,
            'keywords'            => $keywords,
            'abstract'            => $abstract,
            'investigation'       => $investigation,
            'short_biograph'      => $short_biograph,
            'ip'                  => request()->ip(),
            'updated_at'          => Carbon::now(),
            'created_at'          => Carbon::now(),
        ]);

        $id_datos = DB::getPdo()->lastInsertId();
        $id_datos = isset($id_datos) ? $id_datos : 0 ;
        if($id_datos==0)
            return abort(404);

        $last_name    = mb_strtoupper($request->input('last_name'));
        $first_name   = mb_strtoupper($request->input('first_name'));
        $organization = mb_strtoupper($request->input('organization'));
        $department   = mb_strtoupper($request->input('department'));
        $country      = mb_strtoupper($request->input('country'));
        $email        = $request->input('email');

        DB::table('inv_autor')->insert([
            'id_datos'       => $id_datos,
            'a_first_name'   => $first_name,
            'a_last_name'    => $last_name,
            'a_organization' => $organization,
            'a_department'   => $department,
            'a_country'      => $country,
            'a_email'        => $email,
        ]);

        $c_full_name = mb_strtoupper($request->input('c_full_name'));
        $c_position  = mb_strtoupper($request->input('c_position'));
        $c_organization  = mb_strtoupper($request->input('c_organization'));
        $c_phone_number  = mb_strtoupper($request->input('c_phone_number'));
        $c_email  = ($request->input('c_email'));

        DB::table('inv_contact')->insert([
            'id_datos'       => $id_datos,
            'c_full_name'    => $c_full_name,
            'c_position'     => $c_position,
            'c_organization' => $c_organization,
            'c_phone_number' => $c_phone_number,
            'c_email'        => $c_email,
        ]);


        $type  = mb_strtoupper($request->input('type'));
        $o_position_title  = mb_strtoupper($request->input('o_position_title'));
        $o_organization  = mb_strtoupper($request->input('o_organization_name'));
        $o_department  = mb_strtoupper($request->input('o_department'));
        $o_visiting  = mb_strtoupper($request->input('o_visitin_address'));
        $o_city  = mb_strtoupper($request->input('o_city_postal_code'));
        $o_country  = mb_strtoupper($request->input('o_country'));
        $o_phone  = mb_strtoupper($request->input('o_phone_number'));
        $o_mobile = mb_strtoupper($request->input('o_mobile_number'));
        $o_email  = ($request->input('o_institucion_email'));
        $website  = mb_strtoupper($request->input('o_website'));


        DB::table('inv_organization')->insert([
            'id_datos'         => $id_datos,
            'type'             => $type,
            'o_position_title' => $o_position_title,
            'o_organization'   => $o_organization,
            'o_department'     => $o_department,
            'o_visiting'       => $o_visiting,
            'o_city'           => $o_city,
            'o_country'        => $o_country,
            'o_phone'          => $o_phone,
            'o_mobile'         => $o_mobile,
            'o_email'          => $o_email,
            'website'          => $website,
        ]);

        $t_city  = mb_strtoupper($request->input('t_city'));
        $t_country  = mb_strtoupper($request->input('t_country'));
        $t_city_2  = mb_strtoupper($request->input('t_city_2'));
        $t_country_2  = mb_strtoupper($request->input('t_country_2'));
        $t_departure_date = mb_strtoupper($request->input('t_departure_date'));
        $t_departure_date = date("d/m/Y", strtotime($t_departure_date));
        $t_return_date  = mb_strtoupper($request->input('t_return_date'));
        $t_return_date  = date("d/m/Y", strtotime($t_return_date));
        $lugar_retorno  = !empty($request->input('check'))?$request->input('check'):"eeee";


        DB::table('inv_travel')->insert([
            'id_datos'         => $id_datos,
            't_city'           => $t_city,
            't_country'        => $t_country,
            't_city_2'         => $t_city_2,
            't_country_2'      => $t_country_2,
            't_departure_date' => $t_departure_date,
            't_return_date'    => $t_return_date,
            'lugar_retorno'    => $lugar_retorno,
        ]);


        $terminos    = $request->input('check_auto');

        $tipdoc = 2;
        $dni_doc = $p_passport_number;
        $nom    = $p_first_name;
        $appat  = $p_last_name;
        $apmat  = "";
        $dir    = "";
        $pais   = $p_country_residence;
        $dep    = $p_nacionality;
        $ema    = $p_email;
        $codc   = "";
        $cel    = $o_mobile;
        $tel    = $o_phone;
        $disca  = "";
        $gru    = "";
        $car    = $o_position_title;
        $org    = $o_organization;
        $prof   = $o_department;
        $ent    = "";
        $gprof  = "";
        
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
                     'created_at'  => Carbon::now(),
                ]);

                DB::table('eventos')->where('id', $id_evento)
                                    ->increment('inscritos_invi', 1);

                DB::table('estudiantes_act_detalle')->insert([
                     'estudiantes_id'     => $dni_doc,
                     'eventos_id'         => $id_evento,
                     'dgrupo'             => $type,
                     'actividades_id'     => 0,
                     'confirmado'         => 0,
                     //'fecha_conf'         => '',//fecha pago
                     //'dato_extra'         => '',
                     'estudiantes_tipo_id'=> $tipo_xid,
                     'daccedio'           => 'SI',
                     'estado'             => 1,
                     'created_at'         => Carbon::now(),
                ]);


        }else{
            // actualizar

            $check_est = Estudiante::where('dni_doc', $dni_doc)
                        ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                        ->where('estudiantes_act_detalle.eventos_id',$id_evento)
                        ->count();
            
            if($check_est >= 1){
                return redirect('form-registro?id='.$id_evento)->with('dni', 'Sus datos ya se encuentran registrados.');
            }

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
                ]);

                $autoincrem = DB::table('eventos')->where('id', $id_evento)
                                    ->increment('inscritos_invi', 1);
               
                
                DB::table('estudiantes_act_detalle')->where('estudiantes_id',$dni_doc)
                            ->where('eventos_id',$id_evento)
                            ->where('estudiantes_tipo_id', $tipo_xid)
                            ->delete();

                DB::table('estudiantes_act_detalle')->insert([
                    'estudiantes_id'     => $dni_doc,
                     'eventos_id'         => $id_evento,
                     'dgrupo'             => $type,
                     'actividades_id'     => 0,
                     'confirmado'         => 0,
                     //'fecha_conf'         => '',//fecha pago
                     //'dato_extra'         => '',
                     'estudiantes_tipo_id'=> $tipo_xid,
                     'daccedio'           => 'SI',
                     'estado'             => 1,
                     'created_at'         => Carbon::now(),
                ]);

        } // fin actualizar

                // ENVIAR CONFIRMACION

                $celular = $cel;
                $email   = $ema;
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

                $datos_email = array(
                        'estudiante_id' => $dni,
                        'email'    => $email,
                        'from'     => $from->email,
                        'from_name'=> $from->nombre,
                        //'email_bbc'=> $email_bbc,
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
                                //->bcc($datos_email['email_bbc'])
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

                        }else{
                            alert()->success('Advertencia','El email no es válido');
                            return redirect()->back();
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
        

        return view('estudioinv.gracias', compact('datos'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$eventos_id)
    {
        $n = Evento::where('id', $eventos_id)->count();
        if($n == 0){
            return abort(404);
        }   

        $datos = DB::table('eventos as e')
                        ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                        ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                        ->where('e.id',$eventos_id)
                        ->orderBy('e.id', 'desc')
                        ->first();

        if(!isset($datos)){
            return abort(404);
        }

        if($id>=16 and $id<=25) $id = 15;

        $data = formEstudiosInvest::where('inv_personal_details.id_datos',$id)
                ->join('inv_autor as a','a.id_datos','=','inv_personal_details.id_datos')
                ->join('inv_organization as o','o.id_datos','=','inv_personal_details.id_datos')
                ->join('inv_travel as t','t.id_datos','=','inv_personal_details.id_datos')
                ->join('inv_contact as c','c.id_datos','=','inv_personal_details.id_datos')
                //->select('inv_personal_details.*', 'a.*','o.*')
                ->firstOrFail();
        
        $countrys = DB::table('country')->select('name','phonecode')->get();
        $departamentos = Departamento::departamentos(51);
        
            return view('estudioinv.form-inscripcion-show', compact('countrys', 'departamentos', 'datos', 'data','eventos_id'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
