<?php

namespace App\Http\Controllers;
use DB;
use Cache;
use Jenssegers\Date\Date;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Estudiante;
use App\Departamento;
use App\Plantillaemail;
use App\Newsletter;
use App\Evento, App\Emails;
use Mail;
use Alert;
use PDF;
use Auth;

class CaiiController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth', ['only' => ['validacionlogin', 'edit', 'update', 'seleccionar_act']]);
    }*/

    public function index(Request $request)
    {
        return view('evento.login');

    }

    public function create()
    {
        //
    }

    public function store(Request $request){

    }

    public function validacionlogin(Request $request)
    {
        //if($request->ajax()){
            try {
                
                $user = $request->username;
                $pass = $request->password;
     
                //falta mejorar 
                $count = DB::table('estudiantes')
                                    ->join('users', 'estudiantes.dni_doc', '=', 'users.name')
                                    ->whereRaw('users.name = ? and users.password = ?', [$user, $pass])
                                    ->select('name')
                                    ->count();

                if($count > 0){

                    $accedio = "SI";
                    $estado = 0;
                    $datos_login = DB::table('estudiantes')
                                    ->join('users', 'estudiantes.dni_doc', '=', 'users.name')
                                    ->whereRaw('users.name = ? and users.password = ? and users.estado = ?  and estudiantes.accedio = ?', [$user, $pass,$estado, $accedio])
                                    ->select('name')
                                    ->count();
                    

                    if($datos_login > 0){
                        return redirect('evento')->with('login', 'Usted ya ha registro su actividad. De requerir ayuda, por favor contactar a inscripciones@enc.edu.pe');
                    }

                        $datos_login_2 = DB::table('estudiantes')
                                    ->join('users', 'estudiantes.dni_doc', '=', 'users.name')
                                    ->whereRaw('users.name = ? and users.password = ? and users.estado = ?', [$user, $pass,$estado])
                                    ->select('name')
                                    ->count();

                        if($datos_login_2 > 0){
                        
                            return redirect('evento')->with('finalizo', 'Usuario no registrado');

                        }

                        //$encryptedPassword = encrypt($password);
                        //$decryptedPassword = decrypt($encryptedPassword);

                        $datos_login = DB::table('estudiantes')
                                    ->join('users', 'estudiantes.dni_doc', '=', 'users.name')
                                    ->whereRaw('users.name = ? and users.password = ?', [$user, $pass])
                                    ->select('name')
                                    ->first();
                        $fecha_hoy = (\Carbon\Carbon::now()->toDateString());

                        $datos_login = DB::table('estudiantes as e')
                                        //->select('e.dni_doc','ev.fechaf_evento')
                                        ->join('estudiantes_act_detalle as de', 'e.dni_doc','=','de.estudiantes_id')
                                        ->join('eventos as ev','de.eventos_id','=','ev.id')
                                        ->join('actividades as act', 'act.eventos_id','=','ev.id')
                                        ->where('ev.fechaf_evento','>=',$fecha_hoy)
                                        ->where('e.dni_doc','=',$user)
                                        ->orderBy('ev.id','asc')
                                        ->first();
                        #dd($datos_login,$fecha_hoy);
                        
                        if($datos_login == null) return redirect('evento')->with('finalizo', 'El evento ya finalizo');
                        
                        //$code = encrypt($datos_login->dni_doc);
                        $code = ($datos_login->dni_doc);
                        session(['user' => $user, 'pass'=>$pass, 'code'=> $code, 'eventos_id'=> $datos_login->eventos_id]);
  
                        return redirect('evento/caii/'.$code.'/edit');
                    
                }else{
                    return redirect('evento')->with('login_no', 'El usuario y la contraseña son incorrectos');
                }
                
                
            } catch (\Exception $e) {
                
                return \Response::json(['error' => $e->getMessage() ], 404); 
            }

        //}
    }

    public function show($id)
    {
        //
    }

    public function getDepartamentos(Request $request,$id){
        if($request->ajax()){
            $provincias = Departamento::departamentos($id);
            return response()->json($provincias);
        }
    }

    public function edit($id, Request $request) //$id
    { 
        if (!session()->has('user')) {
            return redirect()->route('caii.login', array('id'=>session('eventos_id')));
        }

        $existePlantVirtual = DB::table('e_plantillas_virtual')->where('eventos_id',session('eventos_id'))->count();
        if($existePlantVirtual==1){
            $datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                            ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                            ->where('eventos.id',session('eventos_id'))
                            ->orderBy('eventos.id', 'desc')
                            ->first();
        }else{
            $datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                            ->where('eventos.id',session('eventos_id'))
                            ->orderBy('eventos.id', 'desc')
                            ->first();
            
        }

        $f_limite = \Carbon\Carbon::parse($datos->fechaf_evento);
        $hoy = Carbon::now();

        // CIERRE DE FORM LOGIN INSCRITOS
        if($hoy->greaterThan($f_limite)){

            return view('evento.insc_cerrado_login',compact('datos'));
        }

        $caii_datos = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
        ->where('de.estudiantes_id',($id))
        ->first();
        
        $countrys = DB::table('country')->select('name','phonecode')->get();
        $tipos = DB::table('tipo_documento')->get();
        #$grupos = DB::table('est_grupos')->get();
        $grupos = DB::table('est_grupos')->whereNotNull('eventos_id')->get();
        $departamentos = Departamento::departamentos(51);

        return view('evento.edit',compact('caii_datos', 'datos','departamentos', 'countrys', 'tipos', 'grupos'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'dni_doc'    =>  'required',
            'nombres'    =>  'required',
            'ap_paterno' =>  'required',
            'ap_materno' =>  'required',
            'grupo'      =>  'required',
            'codigo_cel' =>  'required',
            'celular'    =>  'numeric|digits:9|min:9',
            'email'      =>  'required|string|email|max:255',
        ]);
        
        if (!session()->has('user')) {
            return redirect()->route('caii.login', array('id'=>session('eventos_id')));
        }else{
            
            DB::table('estudiantes')->where('dni_doc',$request->dni_doc)->update([
                 'ap_paterno'=>mb_strtoupper($request->input('ap_paterno')),
                 'ap_materno'=>mb_strtoupper($request->input('ap_materno')),
                 'nombres'=>mb_strtoupper($request->input('nombres')),
                 'grupo'=>mb_strtoupper($request->input('grupo')),
                 'cargo'=>mb_strtoupper($request->input('cargo')),
                 'organizacion'=>mb_strtoupper($request->input('organizacion')),
                 'profesion'=>mb_strtoupper($request->input('profesion')),
                 'codigo_cel'=>$request->input('codigo_cel'),
                 'celular'=>$request->input('celular'),
                 'email'=>$request->input('email'),
                 'pais'=>mb_strtoupper($request->input('pais')),
                 'region'=>mb_strtoupper($request->input('region')),
                 //'tipo_id'=>1,
                 'updated_at'=>Carbon::now(),
                 //'accedio'=>'SI'
                 //'track'=>$request->input('track'),
            ]);

            if(!is_null($request->check_new)){

                $check_new = Newsletter::where('estudiante_id', $request->input('dni_doc2'))->count();

                if($check_new == 0){
                    DB::table('newsletters')->insert([
                         'estado'=>'1',
                         'estudiante_id'=>$request->input('dni_doc2'),
                         'created_at'=>Carbon::now(),
                         'updated_at'=>Carbon::now()
                    ]);
                }

            }

            return redirect()->route('caii.seleccionar', array('eventos_id' => session('eventos_id')));
        }
    }

    public function destroy($id) {}


    public function seleccionar_act(Request $request)
    {

        if (!session()->has('user')) return redirect()->route('caii.login');
        #session('user')
        //$eventos_id = decrypt(session('eventos_id'));
        $eventos_id = session('eventos_id');

        $tpoEst = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                    ->where('de.estudiantes_id',session('user'))
                    ->select('de.modalidad_id')
                    ->first();
        $modalidad = $tpoEst->modalidad_id;
        $eventos = DB::table('eventos')
                    ->select('id','fechai_evento','fechaf_evento')
                    ->where('id',$eventos_id)
                    ->first();

            $fechai_evento = Carbon::parse($eventos->fechai_evento);
            $fechaf_evento = Carbon::parse($eventos->fechaf_evento);

        $cant_dias = ($fechaf_evento->diffInDays($fechai_evento))+1;

        $actividades = DB::table('actividades as a')
                        ->join('eventos as e','a.eventos_id','=','e.id')
                        ->select('a.id','a.fecha_desde','e.id as evento','a.titulo','a.subtitulo', 'a.hora_inicio','a.vacantes','a.inscritos','a.ubicacion','a.imagen','a.desc_ponentes','a.desc_actividad','a.vacantes_v','a.inscritos_v')
                        ->where('eventos_id',session('eventos_id'))
                        ->orderBy('fecha_desde')
                        ->orderBy('hora_inicio')
                        ->orderBy('titulo')
                        ->get();

        $data=array();
        
        $fecha_desde2='';
        $i=-1;
        if(count($actividades)>0){
            foreach($actividades as $j=>$actividad){
                $hora_inicio=$actividad->hora_inicio;
                $fecha_desde=$actividad->fecha_desde;
                if($fecha_desde!==$fecha_desde2){$data[++$i]=array("fecha_desde"=>$fecha_desde,"horas"=>array());$hora_inicio2='';$i2=-1;}
                if($hora_inicio!==$hora_inicio2)$data[$i]["horas"][++$i2]=array("hora_inicio"=>$hora_inicio,"actividades"=>array());
                $fila=array(
                    "titulo"      => $actividad->titulo,
                    "subtitulo"   => $actividad->subtitulo,
                    "id"          => $actividad->id,
                    "vacantes"    => $actividad->vacantes,
                    "vacantes_v"  => $actividad->vacantes_v,
                    "inscritos"   => $actividad->inscritos,
                    "inscritos_v" => $actividad->inscritos_v,
                    "ubicacion"   => $actividad->ubicacion,
                    "imagen"      => $actividad->imagen,
                    "desc_ponentes"    =>  $actividad->desc_ponentes,
                    "desc_actividad"   =>  $actividad->desc_actividad
                    /*otras columnas*/
                );
                $data[$i]["horas"][$i2]["actividades"][]=$fila;
                $hora_inicio2=$hora_inicio;
                $fecha_desde2=$fecha_desde;
            }
        }
        
        $datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',session('eventos_id'))
                            ->orderBy('e.id', 'desc')
                            ->first();
        if($modalidad==2)
            return view('evento.seleccionar_act_virtual', compact('eventos','cant_dias', 'actividades', 'datos','data'));
        else
            return view('evento.seleccionar_act', compact('eventos','cant_dias', 'actividades', 'datos','data'));
   
    }

    public function confirmar_act(Request $request)
    {
        if (!session()->has('user') or !session()->has('eventos_id')) {
            return redirect()->route('caii.login');
        }
        
            $val_reg = DB::table('estudiantes as e')
                ->join('estudiantes_act_detalle as de','e.dni_doc','=','de.estudiantes_id')
                ->join('actividades_estudiantes as a','e.dni_doc','=','a.estudiantes_id')
                ->join('actividades as act','a.actividad_id','=','act.id')
                ->select('act.titulo','act.subtitulo')
                ->where('a.eventos_id',session('eventos_id'))
                ->where('e.dni_doc',session('user'))
                ->where('de.eventos_id',session('eventos_id'))
                ->where('de.estudiantes_id',session('user'))
                ->where('de.daccedio','SI')
                ->get();
                #->count();
            #dd($val_reg);
            $act_sel = '';
            if(count($val_reg) >= 1){
            
                $act_sel = "Usted ya selecciono sus actividades<br>";
                
                foreach ($val_reg as $val) {
                    $act_sel .= "Actividad: ".$val->titulo ." ". $val->subtitulo."<br>" ;        
                }
                
                return redirect()->route('caii.seleccionar',['eventos_id'=>session('eventos_id')])->with('actividades_selec', $act_sel);
                
            }

            $varios = ($request->input('varios'));
            
            $input_2 = "";
            $si_hay_act = DB::table('actividades')
                    ->where('eventos_id',session('eventos_id'))
                    ->count();
            if($si_hay_act == 0){

                return redirect()->back()->with('no_actividades', 'No existen actividades para registrar');
            }
     
            DB::table('estudiantes')->where('dni_doc',session('user'))->update([
                    'accedio'  =>  'SI'
                ]);

            $rs = DB::table('estudiantes_act_detalle')
                        ->where('eventos_id', session('eventos_id'))
                        ->where('estudiantes_id', session('user'))
                        ->update([
                            'dtrack'       => "SI",
                            'daccedio'     => "SI",
                            'confirmado'   => 1,
                            'fecha_conf'   => Carbon::now()
                        ]);
            
            // eventos y plantilla:
            $existePlantVirtual = DB::table('e_plantillas_virtual')->where('eventos_id',session('eventos_id'))->count();
            if($existePlantVirtual==1){
                $datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                                ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                                ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                                ->where('eventos.id',session('eventos_id'))
                                ->orderBy('eventos.id', 'desc')
                                ->first();
            }else{
                $datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                                ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                                ->where('eventos.id',session('eventos_id'))
                                ->orderBy('eventos.id', 'desc')
                                ->first();
            }
            
            $rs_estudiante = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                            ->where('de.estudiantes_id',session('user'))
                            ->where('estudiantes.dni_doc',session('user'))
                            ->where('de.eventos_id',session('eventos_id'))
                            ->first();
            
            $modalidad = $rs_estudiante->modalidad_id;
            $from_name  = $datos->Email->nombre;
            $from_email = $datos->Email->email;
    
            $celular = $rs_estudiante->celular;
            $codigo_celular = $rs_estudiante->codigo_cel;
    
            $email = $rs_estudiante->email;
            $dni = $rs_estudiante->dni_doc;
            $nombres_ape = $rs_estudiante->nombres ." ".$rs_estudiante->ap_paterno;
            $nombres_apat = $rs_estudiante->ap_paterno;
            $nombres_amat = $rs_estudiante->ap_materno;
            
            // PRESENCIAL 
            if($modalidad==1){
                $asunto = $datos->p_conf_registro_asunto;
                $msg_text = $datos->p_conf_registro;// plantila email
                $msg_cel = $datos->p_conf_registro_2;//msg what
                $pantallazo = $datos->p_conf_registro_gracias;
            }else{
                // VIRTUAL
                $asunto = $datos->p_conf_registro_asunto_v;
                $msg_text = $datos->p_conf_registro_v;// plantila email
                $msg_cel = $datos->p_conf_registro_2_v;// msg what
                $pantallazo = $datos->p_conf_registro_gracias_v;
            }
    
            $confirm_email = $datos->p_conf_registro_email;
            $confirm_msg   = $datos->p_conf_registro_msg;

            # AUMENTAR LOS VOTOS
            foreach ($varios as $voto) {
                    
                $id_actividad = $voto;
                $inscritos = $modalidad==2?'inscritos_v':'inscritos';
   
                // save incrementar campo inscritos
                DB::table('actividades')->where('id', $id_actividad)
                                      ->increment($inscritos, 1);
                
                 $sv = DB::table('actividades_estudiantes')->insert([
                    'actividad_id'  =>  $id_actividad,
                    'estudiantes_id'  =>  session('user'),
                    'eventos_id'  =>  session('eventos_id'),
                    'confirmado'  =>  'S',
                    'fecha_reg'  =>  Carbon::now()->format('d/m/Y')
                ]);

                // AGREGAR HISTORIAL DE LA PERSONA
                $tipo_id = DB::table('estudiantes')
                                    ->select('id','dni_doc','tipo_id')
                                    ->where('dni_doc',session('user'))->first();

                if($tipo_id->tipo_id == 1){
                    $fl = 'P';
                }else{
                    $fl = 'I';
                }

            } //foreach

                // ENVIAR GAFETE Y EMAIL
                
                $email = $rs_estudiante->email;
                $cod_celular = $rs_estudiante->codigo_cel;
                $celular = $rs_estudiante->celular;
                $nombre = $rs_estudiante->nombres;
                $nombres_ape = $rs_estudiante->nombres.' '.$rs_estudiante->ap_paterno;
                $nombres_apat = $rs_estudiante->ap_paterno;
                $nombres_amat = $rs_estudiante->ap_materno;

                $plant_confirmacion = $msg_text;

                $flujo_ejecucion = 'CONFIRMACION';
                $id_plantilla = session('eventos_id');

                $gafete_html = $datos->gafete_html;
                $eventos_id = $datos->id;

                //obtengo la plantilla
                $file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                fwrite($file,$plant_confirmacion);
                fclose($file);

                $file=fopen(resource_path().'/views/gafete/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");
                fwrite($file,$gafete_html);
                fclose($file);


                // PDF ---------------------
                $id_lista = $id_plantilla;

                                    //select * FROM actividades as f, actividades_estudiantes as p where (f.id = p.actividad_id) and p.estudiantes_id= '01000001'
                                    $actividades = DB::table('actividades as a')
                                                    //->select('a.id','a.hora_inicio')
                                                    ->join('actividades_estudiantes as de', 'a.id','=','de.actividad_id')
                                                    ->where('a.eventos_id',$id_lista)
                                                    ->where('estudiantes_id', $dni)
                                                    ->orderBy('fecha_desde')
                                                    ->orderBy('hora_inicio')
                                                    ->orderBy('titulo')
                                                    ->get();

                                    
                                    $rs_data=array();
                                    $fecha_desde2='';
                                    $i=-1;
    
                                    if(count($actividades)>0){
                                        foreach($actividades as $j=>$actividad){
                                            $hora_inicio=$actividad->hora_inicio;
                                            $fecha_desde=$actividad->fecha_desde;
                                            if($fecha_desde!==$fecha_desde2){$rs_data[++$i]=array("fecha_desde"=>$fecha_desde,"horas"=>array());$hora_inicio2='';$i2=-1;}
                                            //if($hora_inicio!==$hora_inicio2)$rs_data[$i]["horas"][++$i2]=array("hora_inicio"=>$hora_inicio,"actividades"=>array());
                                            $fila=array(
                                                "titulo"      => $actividad->titulo,
                                                "subtitulo"   => $actividad->subtitulo,
                                                "hora_inicio" => $actividad->hora_inicio,
                                                'enlace'      => $actividad->enlace
                                                /*otras columnas*/
                                            );
                                            //$rs_data[$i]["horas"][$i2]["actividades"][]=$fila;
                                            $rs_data[$i]["horas"][]=$fila;
                                            //$hora_inicio2=$hora_inicio;
                                            $fecha_desde2=$fecha_desde;
                                        }
                                    }

                                    $eventos = DB::table('eventos')
                                                ->select('id','fechai_evento','fechaf_evento')
                                                ->where('id',$id_lista)
                                                ->first();

                                    $fechai_evento = Carbon::parse($eventos->fechai_evento);
                                    $fechaf_evento = Carbon::parse($eventos->fechaf_evento);

                                    $cant_dias = ($fechaf_evento->diffInDays($fechai_evento))+1;

                                    $rs_fecha = DB::table('eventos')
                                                ->select('id','nombre_evento',DB::raw('DATE_FORMAT(fechai_evento, "%d de %M de %Y") as fecha_inicio' ), DB::raw('DATE_FORMAT(fechaf_evento, "%d de %M de %Y") as fecha_fin'),'fechai_evento')
                                                ->where('id',1)
                                                ->first();
                            
                                    // PDF
                                    $codigoG = $dni;
                                    $nombresG  = explode(' ',$nombre);
                                    $nombresG  = $nombresG[0];
                                    $apellidosG = $nombres_apat;
                                    $apellidosG_2 = $nombres_amat;

                                    //arrar para generar PDF
                                    $data = array(
                                        'codigoG'  => $codigoG,
                                        'nombresG' => $nombresG,
                                        'apellidosG' => $apellidosG,
                                        'apellidosG_2' => $apellidosG_2,
                                        'foros'     => $rs_data,
                                        'fecha'     => $rs_fecha,
                                        'cant_dias' => $cant_dias
                                    );
                                    
                                    //obtener gafete
                                    $gafete_html = "";
                                    if($datos->gafete==1) $gafete_html = $datos->gafete_html;

                                    // PRESENCIAL 
                                    if($modalidad==1){
                                    
                                        // GAFETE 
                                        $gafete=fopen(resource_path().'/views/email/gafetes/gafete_'.$id_lista.'.blade.php','w') or die ("error creando fichero!");

                                        $leido = fwrite($gafete,$gafete_html);
                                        fclose($gafete);


                                        //$pdf = PDF::loadView('evento.gafete', $data );
                                        //return PDF::loadView('evento.gafete', $data )->save('storage/gafete_caii/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');

                                        $file = 'storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf';
                                        //$file = 'storage/confirmacion/12345678.pdf';
                                        $directory = 'storage/confirmacion/'; 

                                        //Devuelve true
                                        //$exists = is_file( $file );

                                        // SOLO PARA CREAR NUEVAMENTE LOS GAFETES
                                        //if(!is_file($file)){
                                    
                                        if($id_lista == 269 or $id_lista == 167){
                                            // PDF tipo personalizado
                                            $pdf = PDF::loadView('email.gafetes.gafete_'.$id_lista.'', $data )
                                                    ->setPaper([0, 0, 420, 235], 'landscape')
                                                    ->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
                                        }else{
                                            // PDF tipo A4
                                            $pdf = PDF::loadView('email.gafetes.gafete_'.$id_lista, $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
                                        }
                                    
                                    }

                                    //Devuelve false
                                    /*$exists = is_file( $directory );
                                    //Devuelve true
                                    $exists = file_exists( $file );
                                    //Devuelve TRUE
                                    $exists = file_exists( $directory );*/

                                    $datos_email = array(
                                        'estudiante_id' => $dni,
                                        'email'     => $email,
                                        'from'      => $from_email,
                                        'from_name' => $from_name,
                                        'name'      => $nombre,
                                        'flujo_ejecucion' => $flujo_ejecucion,
                                        'asunto'    => $asunto,
                                        //'html_id'   => $id_plantilla,
                                        'lista'     => $id_lista,
                                        'file'      => $file
                                    );

                                    // envio array a plantilla confirmacion
                                    $data = array(
                                        'foro'      =>  '',
                                        'foro_2'    =>  '',
                                        'nombres'   => $nombres_ape,
                                        'dni_dni'   => $dni,
                                        'foros'     => $rs_data,
                                        'fecha'     => $rs_fecha,
                                        'cant_dias' => $cant_dias,
                                        'modalidad' => $modalidad==2?'VIRTUAL':'PRESENCIAL'
                                    );

                    // PDF ---------------------
                    
                    if($confirm_email == 1){

                        if($email != ""){

                            if($datos->gafete==1){
                                // si tiene gafete
                                if($modalidad==1){

                                    Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                        $mensaje->from($datos_email['from'], $datos_email['from_name']);
                                        $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
                                        $mensaje->attach($datos_email['file']);
                                    });
                                }else{
                                    Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                        $mensaje->from($datos_email['from'], $datos_email['from_name']);
                                        $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
                                        
                                    });
                                }

                            }else{
                                // si no tiene gafete
                                Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                    //$mensaje->from('admin@enc.pe','Admin');
                                    $mensaje->from($datos_email['from'], $datos_email['from_name']);
                                    $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
                                });
                            }

                            DB::table('historia_email')->insert([
                                'tipo'              =>  'EMAIL',
                                'fecha'             => Carbon::now(),
                                'estudiante_id'     => $dni,
                                'plantillaemail_id' => $id_plantilla,
                                'flujo_ejecucion'   => $flujo_ejecucion,
                                'eventos_id'        => $id_plantilla,
                                'fecha_envio'       => Carbon::now(),//'2000-01-01',
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
                  
                    if($confirm_msg == 1){

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

                /*DB::table('users')->where('name', session('user'))->update([
                            'estado'=> '0'
                        ]);

                session()->forget('user','eventos_id');*/

            return view('evento.gracias', compact('pantallazo'));
      
    } // end public function store

    public function login_baja(Request $request)
    {
        if(isset($request->id)){
            session(['eventos_id' => $request->id] );
        }
        return view('login.baja_login');
    }

    public function baja(Request $request){
        
        try {

            if (!$request->session()->has('eventos_id')) {

                return redirect()->back()->with('login_no', 'El link para darse de baja es incorrecto.');
            }

            $user = trim($request->username);
            $pass = trim($request->password);

            $count = DB::table('users as u')
                    ->where('u.name', $user)
                    ->where('u.password', $pass)
                    ->select('name')
                    ->count();
            
            if($count == 0){
                return redirect()->back()->with('login_no', 'El usuario y la contraseña son incorrectos');
            }

            $count = DB::table('estudiantes_act_detalle')
                    ->where('estudiantes_id', $user)
                    ->where('eventos_id',session('eventos_id'))
                    ->where('confirmado', 1)
                    ->where('daccedio', 'SI')
                    ->count();
            
            if($count == 0){
                return redirect()->back()->with('login_no', 'Usted no ha completado el proceso de registro');
            }

            session(['baja_user' => $user ] );

            return redirect()->route('caii.baja_create');
            
        } catch (Exception $e) {
            dd('error');
            Auth::logout();
            $xid=session('eventos_id');
            return redirect('/login_baja?id='.$xid);
            
        }

    }

    public function baja_create(Request $request){

        if (!$request->session()->has('baja_user')) {

            return redirect('/login_baja');
        }
        return view('login.baja_confirmacion');

    }

    
    // x ajax
    public function baja_manual(Request $request, $dni, $evento){
        
        $rs_sql = DB::table('estudiantes as e')
                    ->select('de.actividad_id')
                    ->join('actividades_estudiantes as de', 'e.dni_doc', '=', 'de.estudiantes_id')
                    #->join('users as u', 'e.dni_doc', '=', 'u.name')
                    ->where('e.dni_doc', $dni)
                    #->where('u.name', $dni)
                    ->where('de.eventos_id', $evento)
                    #->where('u.estado', 1)
                    ->count();
        #dd($rs_sql);
        if($rs_sql > 0){
            #Para actividades
            $rs_sql = DB::table('estudiantes as e')
                    ->select('de.actividad_id','de.eventos_id')
                    #->join('users as u', 'e.dni_doc', '=', 'u.name')
                    ->join('actividades_estudiantes as de', 'e.dni_doc', '=', 'de.estudiantes_id')
                    ->where('e.dni_doc', $dni)
                    #->where('u.name', $dni)
                    ->where('de.eventos_id', $evento)
                    #->where('u.estado', 1)
                    ->get();

            $existePlantVirtual = DB::table('e_plantillas_virtual')->where('eventos_id',$evento)->count();
            if($existePlantVirtual==1){
                $rs_datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                                ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                                ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                                ->where('eventos.id',$evento)
                                ->orderBy('eventos.id', 'desc')
                                ->first();
            }else{
                $rs_datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                                ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                                ->where('eventos.id',$evento)
                                ->orderBy('eventos.id', 'desc')
                                ->first();
            }

            // ADD REGISTRO DE BAJA
            $rs_estudiante = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                            ->where('de.estudiantes_id',$dni)
                            ->where('estudiantes.dni_doc',$dni)
                            ->where('de.eventos_id',$evento)
                            ->first();
            
            $modalidad = $rs_estudiante->modalidad_id;
            $tipo = "p_baja_evento";
            $flujo_ejecucion='BAJA_EVENTO';

            #$respt = bajaEvento($modalidad, $rs_estudiante, $rs_datos,$tipo,$evento,$flujo_ejecucion);
            $respt = creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,$evento,$flujo_ejecucion);
            
            if($respt['ok']=="ok"){
                // ADD REGISTRO DE BAJA

                DB::table('users')->where('name',$dni)->delete();
              
                $rs_upd = DB::table('estudiantes_act_detalle')
                    ->where('eventos_id', $evento)
                    ->where('estudiantes_id', $dni)
                    ->delete();

                DB::table('estudiantes')->where('dni_doc',$dni)->update([
                    'track'   => '',
                    'accedio' => '',
                ]); 

                ## QUITAR VOTOS ACTIVIDADES    
                foreach ($rs_sql as $act) {
                    $inscritos = $modalidad==2?'inscritos_v':'inscritos';

                    DB::table('actividades')->where('id', $act->actividad_id)
                        ->where('eventos_id', $evento)
                        #->decrement('inscritos', 1);
                        ->decrement($inscritos, 1);

                    DB::table('actividades_estudiantes')
                        ->where('eventos_id', $evento)
                        ->where('estudiantes_id', $dni)
                        ->where('actividad_id', $act->actividad_id)
                        ->delete();
                }
                    DB::table('estudiantes_baja')
                        ->where('eventos_id', $evento)
                        ->where('dni_doc', $dni)
                        ->delete();

                    DB::table('estudiantes_baja')->insert([
                        'eventos_id'        => $evento,
                        'msg'               => "",#$request->input('dar_baja'),
                        'dni_doc'=>         $dni,
                        'ap_paterno'=>      $rs_estudiante->ap_paterno,
                        'ap_materno'=>      $rs_estudiante->ap_materno,
                        'nombres'=>         $rs_estudiante->nombres,
                        'grupo'=>           $rs_estudiante->grupo,
                        'cargo'=>           $rs_estudiante->cargo,
                        'organizacion'=>    $rs_estudiante->organizacion,
                        'profesion'=>       $rs_estudiante->profesion,
                        'codigo_cel'=>      $rs_estudiante->codigo_cel,
                        'celular'=>         $rs_estudiante->celular,
                        'email'=>           $rs_estudiante->email,
                        'created_at'        =>Carbon::now(),
                        'updated_at'        =>Carbon::now(),
                        'estado'            =>1,
                        'pais'              => $rs_estudiante->pais,
                        'region'            => $rs_estudiante->region,
                        'tipo_documento_documento_id'=>$rs_estudiante->tipo_documento_documento_id,
                        'tipo_id'           => $rs_estudiante->tipo_id
                    ]);
                    
                #DB::table('estudiantes')->where('dni_doc',$dni)->delete();
                Cache::flush();
    
            }

            DB::table('users')->where('name',$dni)->delete();

            return 1;

        }else{
            return 0;
        }

    }

    public function baja_store(Request $request){
        
        $rs_sql = DB::table('estudiantes as e')
                    ->select('de.actividad_id')
                    ->join('actividades_estudiantes as de', 'e.dni_doc', '=', 'de.estudiantes_id')
                    ->where('e.dni_doc', session('baja_user'))
                    ->where('de.eventos_id', session('eventos_id'))
                    ->count();

        if($rs_sql > 0){

            $rs_sql = DB::table('estudiantes as e')
                    ->select('de.actividad_id','de.eventos_id')
                    ->join('actividades_estudiantes as de', 'e.dni_doc', '=', 'de.estudiantes_id')
                    ->where('e.dni_doc', session('baja_user'))
                    ->where('de.eventos_id', session('eventos_id'))
                    ->get();


            foreach ($rs_sql as $act) {

                DB::table('actividades')->where('id', $act->actividad_id)
                    ->decrement('inscritos', 1);

                DB::table('actividades_estudiantes')
                    ->where('eventos_id', session('eventos_id'))
                    ->where('estudiantes_id', session('baja_user'))
                    ->where('actividad_id', $act->actividad_id)
                    ->delete();
            }
            
            $existePlantVirtual = DB::table('e_plantillas_virtual')->where('eventos_id',session('eventos_id'))->count();
            if($existePlantVirtual==1){
                $rs_datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                                ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                                ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                                ->where('eventos.id',session('eventos_id'))
                                ->orderBy('eventos.id', 'desc')
                                ->first();
            }else{
                $rs_datos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                                ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                                ->where('eventos.id',session('eventos_id'))
                                ->orderBy('eventos.id', 'desc')
                                ->first();
            }

            $rs_estudiante = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
                            ->where('de.estudiantes_id',session('baja_user'))
                            ->where('estudiantes.dni_doc',session('baja_user'))
                            ->where('de.eventos_id',session('eventos_id'))
                            ->first();
            
            $modalidad = $rs_estudiante->modalidad_id;
            $tipo = "p_baja_evento";
            $flujo_ejecucion = "BAJA_EVENTO";
            #crea 2 registros, EMAIL, WHATS para ser enviado por
            $respt = creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,session('eventos_id'),$flujo_ejecucion);
            
            if($respt['ok']=="ok"){
                // ADD REGISTRO DE BAJA

                DB::table('users')->where('name',session('baja_user'))->delete();
              
                $rs_upd = DB::table('estudiantes_act_detalle')
                    ->where('eventos_id', session('eventos_id'))
                    ->where('estudiantes_id', session('baja_user'))
                    ->delete();

                DB::table('estudiantes')->where('dni_doc',session('baja_user'))->update([
                    'track'   => '',
                    'accedio' => '',
                ]);

                    DB::table('estudiantes_baja')->insert([
                        'eventos_id'        => session('eventos_id'),
                        'msg'               => $request->input('dar_baja'),
                        'dni_doc'=>         session('baja_user'),
                        'ap_paterno'=>      $rs_estudiante->ap_paterno,
                        'ap_materno'=>      $rs_estudiante->ap_materno,
                        'nombres'=>         $rs_estudiante->nombres,
                        'grupo'=>           $rs_estudiante->grupo,
                        'cargo'=>           $rs_estudiante->cargo,
                        'organizacion'=>    $rs_estudiante->organizacion,
                        'profesion'=>       $rs_estudiante->profesion,
                        'codigo_cel'=>      $rs_estudiante->codigo_cel,
                        'celular'=>         $rs_estudiante->celular,
                        'email'=>           $rs_estudiante->email,
                        'created_at'        =>Carbon::now(),
                        'updated_at'        =>Carbon::now(),
                        'estado'            =>1,
                        'pais'              => $rs_estudiante->pais,
                        'region'            => $rs_estudiante->region,
                        'tipo_documento_documento_id'=>$rs_estudiante->tipo_documento_documento_id,
                        'tipo_id'           => $rs_estudiante->tipo_id
                    ]);
                    
                #DB::table('estudiantes')->where('dni_doc',$dni)->delete();
    
                $request->session()->forget('baja_user','eventos_id');
            }
            $msg_text = $respt['pantallazo'];
            return view('login.baja_adios',compact('msg_text'));
            
        }else{
            $xid=session('eventos_id');
            return redirect('/login_baja?id='.$xid)->with('login_no', 'Usted no tiene actividades registradas para dar de baja.');
        }
        
        #return view('login.baja_adios',compact('msg_text'));
    }


    public function confirmacion(){
        return view('evento.gracias');
    }

    

   

}
