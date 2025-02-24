<?php

namespace App\Http\Controllers;
use App\Datos_email;
use App\Departamento;
use App\Emails;
use App\Estudiante;
use App\Evento;
use App\Historiaemail;
use App\HistoryEmails;
use App\JobHistory;
use App\Jobs\SendEmails;
use App\Jobs\SendEmailsCampanias;
use App\Jobs\SendHistoryEmails;
use App\Jobs\SendParticipantesCampanias;
use App\Models\InvImportacion;
use App\Models\JobCampanias;
use App\Plantillaemail;
use App\Repositories\CampaniaRepository;
use App\Usuario;
use App\Whatsapp;
use App\Models\InvStudents;
use Cache;
use Carbon\Carbon;
use DB;

use App\AccionesRolesPermisos;
use App\Campanias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class CampaniasController extends Controller
{
    # restablecer las colas de failedjobs a jobs
    #JobCampanias::retry(379); #379 es el id de campañas

    #restaurar failed jobs de todas las campañas
    #JobCampanias::retryAll();

    #agregue restaurar todos los failed jobs que no son campañas
    #JobCampanias::retryNoCampanias();

    private $urlQueue = "";
    public function __construct()
    {
        $this->urlQueue = "https://enc-ticketing.org/";
        #$this->urlQueue = "https://mailing2.enc-ticketing.org/";
        //$this->urlQueue = "https://enc-ticketing.org/amailing_laravel8/";
        $this->middleware('auth');
    }
    public static function getNoEmails()
    {
        //$noemails = DB::table('noemails')->select('email')->get();
        $noemails = DB::table('noemails')->select('email')->where('status','=',1)->get();
        $emails = array();
        if(count($noemails)>0)
            foreach ($noemails as $ob)array_push($emails,$ob->email);
        return $emails;
    }

    public function retry(Campanias $campania)
    {
        JobCampanias::retry($campania->id);
        return redirect()->back();
    }
    public function play(Campanias $campania)
    {
        JobCampanias::play($campania->id);
        return redirect()->back();
    }
    public function pause(Campanias $campania)
    {
        JobCampanias::pause($campania->id);
        return redirect()->back();
    }

    public function index(Request $request, CampaniaRepository $campaniaRepository)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        //phpinfo();
        Session::forget("personalizados");
        $campanias_data = Campanias::all();

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "crm";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        Cache::flush();
        if($request->get('s')){

            $search = $request->get('s');

            $campanias_data = Campanias::where("nombre", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

        }else{

            $key = 'campania.page.'.request('page', 1);
            $campanias_data = Cache::rememberForever($key, function() use ($pag){
                return Campanias::orderBy('id', request('sorted', 'DESC'))
                    ->paginate($pag);

            });

        }
        //$campanias_data = Campanias::all();
        $camps = $campanias_data;

        if(count($campanias_data)>0){
            /* foreach($campanias_data as $v){
                $total = $v->total??0;
                $enviados = $v->enviados??0;
                $errores = $v->errores??0;
                $s = $enviados+$errores;
                if($s<$total&&$total>0)$campaniaRepository->actualizaCampania($v->id);
            } */
            foreach($campanias_data as $i=>$v){
                $total = $v->total??0;
                $enviados = $v->enviados??0;
                $errores = $v->errores??0;
                $s = $enviados+$errores;
                $result=(object)$campaniaRepository->totalAccedio($v->id);
                $camps[$i]->result = $result;
                $total=$result->total;
                $v->total=$total;
                if($s<$total&&$total>0){
                    $xx = $campaniaRepository->actualizaCampania($v->id);
                    if($xx){
                        $v->total=$xx->total;
                        $v->errores=$xx->errores;
                        $v->enviados=$xx->enviados;
                    }
                }
            }
        }


        return view("campanias.index", compact('camps', 'permisos'));

    }
    public function create(Request $request)
    {
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

        $plantilla_datos = Plantillaemail::orderBy('id','desc')->limit(20)->get();
        $tipos = DB::table('est_grupos')->get();
        $paises = DB::table('country')->select('id','name')->get();
        //$prof = Estudiante::select(DB::raw('distinct(profesion) as profesion'))->get();
        $eventos = Evento::select('id','nombre_evento')->orderBy('nombre_evento','asc')->get();


        $column = 'organizacion';
        $organizaciones = DB::table('estudiantes')->select($column)->distinct()->orderBy($column,'asc')->get()->pluck($column);
        $column = 'profesion';
        $profesiones = DB::table('estudiantes')->select($column)->distinct()->orderBy($column,'asc')->get()->pluck($column);

        $emails = Emails::orderBy("nombre",'asc')->get();

        $estudiantes_datos ='';


        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
            ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
            ->get();

        $imp_datos = InvImportacion::select('id','nombre')->orderBy('nombre')->get();

        return view('campanias.create', compact('estudiantes_datos', 'plantilla_datos', 'permisos', 'tipos','paises', 'departamentos_datos', 'eventos','organizaciones','profesiones','emails', 'imp_datos'));
    }

    public function store(Request $request, CampaniaRepository $repository){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["nuevo"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        // form radio button
        $radio   = $request->get('checkHTML');
        $evento  = $request->get('evento');
        $grupo   = $request->get('grupo');
        $pais    = $request->get('pais');
        $depa    = $request->get('depa');
        $nombre  = $request->get('nombre');
        $organizacion   = $request->get('organizacion');
        $profesion      = $request->get('profesion');
        $participantes  = $request->get('participantes');
        $all            = $request->get('all');
        $from_id        = $request->get('from_id');

        $mailing_test   = $request->get('mailing_test');
        $email_test     = $request->get('email_test');

        $inv     = $request->get('inv')??0;
        $inv_id     = $request->get('inv_id')??0;

        if($radio){
            $id_html = $request->checkHTML;
            $plantilla = Plantillaemail::findOrFail($id_html);
            $flujo_ejecucion = $plantilla->flujo_ejecucion;
            $asunto          = $plantilla->asunto;
            $id_plantilla    = $plantilla->id;
            $id_plantilla    = $plantilla->id;

            $search = $this->requestToArraySearch($request);
            $noemails = CampaniasController::getNoEmails();

            $from = Emails::findOrFail($from_id);
            //ENVIO DE PRUEBA
            if($mailing_test==1){
                $separator = ";";
                $emails = explode($separator, $email_test);
                $email_count = count($emails);
                ;
                if($email_count>0){
                    $i = 0;
                    $plantilla_id = $id_plantilla;
                    $evento_id = $evento;
                    foreach($emails as $email){
                        $i++;
                        $email= trim($email);
                        if(in_array($email,$noemails))continue;
                        $default = (object) [];
                        $default->nombre = "EMAIL  {$i}";
                        $default->ape_pat = "De Prueba";
                        $default->ape_mat = "";
                        $default->email = $email;
                        $default->flujo = $flujo_ejecucion;
                        $default->msg_text = "";
                        $default->dni = "99999999";
                        $default->asunto = $asunto;
                        $default->evento_id = $evento_id;
                        $default->from_nombre = $from->nombre;
                        $default->from_email = $from->email;
                        $default->tipo = "EMAIL";
                        $default->msg_cel = "";
                        $default->msg_cel = "";
                        $default->id = 0;
                        $default->plantilla_id = $plantilla_id;
                        $repository->send((array)$default);
                        //$repository->send(0, $default);
                        //dd($default); exit();
                    };
                }
                $search = $this->requestToArraySearch($request);
                $search['history']=1;
                alert()->success("Se enviaron : {$email_count} correos de prueba", 'Mensaje')->persistent('Cerrar');
                //return redirect()->route('campanias.create', ["data"=>$search]);
                return redirect()->back()->withInput($request->all());
            }
            //FIN ENVIO DE PRUEBA

            // de function participantes

            $search = $this->requestToArraySearch($request);
            $inv = $search['inv'];//ADDED
            $table = $inv == 1 ? 'inv_estudiantes': 'estudiantes';

            $q = $this->generateQuery($search);
            $q->select(DB::raw('COUNT(DISTINCT('.$table.'.email)) as estudiantes'));
            $q->getQuery()->groups=null;

            $q->orderBy($table.'.nombres','DESC');
            $query = str_replace(array('?'), array('\'%s\''), $q->toSql());$query = vsprintf($query, $q->getBindings());
            $n = $q->get()->first()->estudiantes??0;
            //return $query;
            //return $n;
            $participantes = $n;
            $cant = $n;

            // de function participantes


            /* $q = $this->generateQuery($search);
            $q->select(DB::raw('COUNT(estudiantes.id) as estudiantes'));
            $cant = $q->get()->first()->estudiantes??0;
            $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
            $query = vsprintf($query, $q->getBindings());
            $participantes = $cant;
            */
            //dd($query);

            //echo "{$query} --- {$participantes}";exit;

            $tipo = "EMAIL";
            $actividad_id = 0;
            $from_nombre = $from->nombre;
            $from_email = $from->email;
            $plantilla_id = $id_plantilla;
            $flujo = $flujo_ejecucion;
            //GRABAR EVENTO
            $campania = Campanias::create([
                "nombre" => $nombre,
                "eventos_id" => $evento,
                "checkHTML" => $radio,
                "grupo" => $grupo,
                "pais" => $pais,
                "region" => $depa,
                "organizacion" => $organizacion,
                "profesion" => $profesion,
                "total" => $cant,
                "from_id"=>$from_id,
                "all"=>$all

                ,"tipo"=>$tipo,
                "flujo"=>$flujo_ejecucion,
                "plantilla_id"=>$id_plantilla,
                "asunto"=>$asunto,
                "from_nombre"=>$from_nombre,
                "from_email"=>$from_email,
                "actividad_id"=>$actividad_id,

                "inv"=>$inv,
                "inv_id"=>$inv_id,
            ]);
            $campania_id = $campania->id;



            //ENVIAR A LA COLA
            $data = compact('radio','evento','grupo','pais','depa','organizacion','profesion','participantes','campania_id','from_id','all',
                "tipo","flujo","plantilla_id","asunto","from_nombre","from_email","actividad_id", 'inv', 'inv_id'
            );
            foreach($data as $i=>$v)if(!$v)$data[$i]= (string)$v;
            //$url = route('campanias.queue',$campania_id);
            $version = phpversion();
            if($version>=10){
                SendParticipantesCampanias::dispatch($data)->onConnection('database')->onQueue("emails");
            }else{//forzar carga de la cola desde laravel 8
                SendParticipantesCampanias::dispatch($data)->onConnection('database')->onQueue("emails");
                /* #Se Usaba para la version Laravel5.6
                $url = "{$this->urlQueue}campanias/queue/{$campania_id}";
                $hh = ["Content-Type: application/x-www-form-urlencoded"];
                $success = $this->fileGetContents($url,$hh,$data,"POST");
                if(!$success){
                    //Mensaje error
                } */
            }


            alert()->success("Mensaje", "Se enviaron a: {$participantes} participantes la campaña: {$nombre}")->persistent('close');
            return redirect()->route('campanias.index', ['history'=>1]);



            //falta agregar envio por: evento - grupo - pais - depa
            $xemail = 0;

            //$cant = 0;
            if($cant == 0){
                alert()->warning('No existe participantes en esta búsqueda.','Mensaje');
                return back();
            }
            $q->select('de.estudiantes_id', 'estudiantes.email', 'estudiantes.nombres', 'estudiantes.ap_paterno');
            $participantes = $q->get();

            foreach ($participantes as $key => $part) {
                $email = $part->email;
                $dni   = $part->estudiantes_id;
                $nom   = $part->nombres .' '.$part->ap_paterno;

                if($email){
                    $xemail += 1;
                    //$data = DB::table('historia_email')->insert([
                    $data = Historiaemail::create([
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
                        'campania_id'             => $campania_id,
                        /*'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()*/
                    ]);


                    //JOB2
                    /*

                    $job = (new SendEmailsCampanias(new JobHistory,$data))
                        //->delay(Carbon::now()->addMinutes(1))
                        ->onQueue("historias");
                    */
                    //dispatch($job);
                    //dd(1);                    exit;

                    //SendEmailsCampanias::dispatch(new JobHistory,$data)->onConnection('database')->onQueue("historias");dd(1);



                }
            }
            session(['eventos_id'=> -1]);
            // $key
            alert()->success('Se enviarón '.$xemail.' mensajes.', 'Mensaje')->persistent('close');
            //return back();
            //exit;
            $this->envio_email();
            return redirect()->route('campanias.index', ['history'=>1]);

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

    //Envio Controller

    public function envio_email(){
        //https://www.enc-ticketing.org/envio_email

        $datos_email = Datos_email::count();

        $mensaje = "";
        $send = "";

        $prin = "";$xid = ""; $error = "";
        if($datos_email > 0){
            $datos_email = Datos_email::all();

            foreach ($datos_email as $key => $value) {

                $id = $value->id;
                $id_participante = $value->participante_id;
                //$id_plantilla = $value->plantillaemail_id;
                //$laplantilla = $value->plantillahtml;

                $id_lista 	= $value->lista;
                $dni 		= $value->dni;

                $asunto 	= $value->asunto;
                $nombres 	= $value->nombres.' '.$value->apellido_paterno;

                $nombre 	= $value->nombres;
                $nombres_ape = $value->nombres ." ".$value->apellido_paterno;
                $nombres_apat = $value->apellido_paterno;
                $nombres_amat = $value->apellido_materno;

                $flujo_ejecucion = $value->flujo_ejecucion;
                $xtipo = $value->tipo;

                if($value->tipo == "EMAIL"){

                    $msg_text 	= $value->msg_text;//plantila email p_preregistro_2
                    $email 		= $value->email;
                    $email 		= trim($email);

                    $datos_email = array(
                        'estudiante_id' => $dni,
                        'email' => $email,
                        'name'  => $nombres,
                        'flujo_ejecucion' => $flujo_ejecucion,
                        'asunto'    => $asunto,
                        'html_id'    => $id_lista,
                        'lista'      => $id_lista
                    );

                    // MAILING
                    if($flujo_ejecucion == "MAILING"){
                        $id_plantilla = $value->plantillaemail_id;
                        // RECUPERO LA PLANTILLA
                        $file = $this->getPlantilla($id_plantilla);
                        $datos_email = array(
                            'estudiante_id' => $dni,
                            'email' => $email,
                            'name'  => $nombres,
                            'flujo_ejecucion' => $flujo_ejecucion,
                            'asunto'    => $asunto,
                            'html_id'    => $id_plantilla,
                            'lista'      => $id_lista
                        );

                        $data = array('detail'    => "Mensaje enviado",'html'      => $msg_text,'email'     => $email,'id'        => $dni,'nombre'    => $nombres);
                        $param = 'email.'.$id_plantilla;
                        $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                        SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");
                        $this->actualizaHM($id);
                    }

                    // CONFIGURAR DIFERENTES VISTAS CON UN SOLO CLICK
                    if($flujo_ejecucion == "NEWSLETTER"){
                        $data = array(
                            'detail'    => "Mensaje enviado",
                            'html'      => $msg_text,
                            'email'     => $email,
                            'id'        => $dni,
                            'nombre'    => $nombres
                        );
                        $param = 'email.envio_newsletter';
                        $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                        SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");
                    }
                    // INVITACION
                    if($flujo_ejecucion == "INVITACION"){
                        // EXTRAER USUARIO Y PASS
                        //SELECT * FROM users where name='10000001' limit 1
                        $cant_usuario = Usuario::where('name',$dni)
                            ->where('estado',1)
                            ->select('name','password')->count();

                        if($cant_usuario > 0){
                            $usuario = Usuario::where('name',$dni)
                                ->where('estado',1)
                                ->select('name','password')
                                ->orderBy('id','DESC')
                                ->first();
                            $usu = $usuario->name;
                            $pass = $usuario->password;
                            $data = array(
                                'detail'    => "Mensaje enviado",
                                'html'      => $msg_text,
                                'email'     => $email,
                                'id'        => $dni,
                                'nombres'   => $nombres,
                                'usuario'	=> $usu,
                                'pass'		=> $pass
                            );
                            $param = "email.".$msg_text;
                            $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                            SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");
                            $this->actualizaHM($id);
                        }else{
                            $this->actualizaHM($id,false);
                        }
                    }

                    //CONFIRMACION
                    if($flujo_ejecucion == "CONFIRMACION" OR $flujo_ejecucion == "RECORDATORIO"){
                        //select * FROM actividades as f, actividades_estudiantes as p where (f.id = p.actividad_id) and p.estudiantes_id= '01000001'
                        $actividades = DB::table('actividades as a')
                            //->select('a.id','a.hora_inicio')
                            ->join('actividades_estudiantes as de', 'a.id','=','de.actividad_id')
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
                                    "titulo"    =>$actividad->titulo,
                                    "subtitulo" =>$actividad->subtitulo,
                                    "hora_inicio"    =>  $actividad->hora_inicio
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
                            ->count();
                        if($eventos==0){
                            $this->actualizaHM($id,false);
                            return "No existe evento para el envío del mailing. ID historia_email: $id - EVENTO: $id_lista - DNI: $dni";
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
                            ->where('id',$id_lista)//1
                            ->first();
                        // PDF
                        $codigoG = $dni;
                        $nombresG  = $nombre;
                        $apellidosG = $nombres_apat;
                        $apellidosG_2 = $nombres_amat;

                        //arrar para generar PDF
                        $data = array(
                            'codigoG' => $codigoG,
                            'nombresG' => $nombre,
                            'apellidosG' => $apellidosG,
                            'apellidosG_2' => $apellidosG_2,
                            'foros'		=> $rs_data,
                            'fecha'		=> $rs_fecha,
                            'cant_dias'		=> $cant_dias
                        );

                        //obtener gafete
                        $nrs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$id_lista)
                            ->where('gafete',1)->count();

                        $gafete_html = "";

                        if($nrs_gafete > 0){

                            $rs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$id_lista)
                                ->where('gafete',1)->first();
                            $gafete_html = $rs_gafete->gafete_html;

                        }

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
                        $pdf = PDF::loadView('email.gafetes.gafete_'.$id_lista.'', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
                        /*$pdf = PDF::loadView('evento.gafete', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');*/
                        //}

                        //Devuelve false
                        /*$exists = is_file( $directory );
                        //Devuelve true
                        $exists = file_exists( $file );
                        //Devuelve TRUE
                        $exists = file_exists( $directory );*/

                        $datos_email = array(
                            'estudiante_id' => $dni,
                            'email' 	=> $email,
                            'name'  	=> $nombre,
                            'flujo_ejecucion' => $flujo_ejecucion,
                            'asunto'    => $asunto,
                            //'html_id'   => $id_plantilla,
                            'lista'     => $id_lista,
                            'file'      => $file
                        );

                        // envio array a plantilla confirmacion
                        $data = array(
                            //'detail'    => "Mensaje enviado",
                            'foro'      =>  '',
                            'foro_2'    =>  '',
                            'nombres'   => $nombres_ape,
                            'foros'		=> $rs_data,
                            'fecha'		=> $rs_fecha,
                            'cant_dias'	=> $cant_dias
                        );

                        $data["nrs_gafete"]=$nrs_gafete;
                        $param = "email.".$msg_text;
                        $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                        SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");
                        /*
                        if($nrs_gafete > 0){
                            // si tiene gafete


                            $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                            SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");

                            Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                //$mensaje->from('admin@enc.pe','Admin');
                                $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
                                $mensaje->attach($datos_email['file']);
                            });

                        }else{
                            // si no tiene gafete

                            Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
                                //$mensaje->from('admin@enc.pe','Admin');
                                $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
                            });
                        }
                        */
                        $this->actualizaHM($id);
                    }

                    // NOINVITADO
                    if($flujo_ejecucion == "NOINVITADO"){

                        $data = array(
                            'detail'    => "Mensaje enviado",
                            'html'      => $msg_text,
                            'email'     => $email
                        );
                        $param = 'email.'.$msg_text;
                        $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                        SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");
                        $this->actualizaHM($id,false);
                    }

                } // end email
                if($value->tipo == "WHATS"){

                    $paq_msn = DB::table('tb_msn')->where('id',1)->first();

                    if($paq_msn->mensajes >= $paq_msn->cant){

                        $data = array('detail'=>"Mensaje enviado",'email'=>'encomunicacion@enc.edu.pe','nombre'=>'Ticketing','asunto'=>'PAQUETE AGOTADO','cant'=>$paq_msn->cant);
                        $datos_email["name"] = $data["nombre"];
                        $datos_email["email"] = $data["email"];
                        $datos_email["asunto"] = $data["asunto"];
                        $param = 'email.notificacion';
                        $xdata = compact("id_plantilla","datos_email","data","flujo_ejecucion","param");
                        SendEmails::dispatch($xdata)->onConnection('database')->onQueue("emails");
                        /*
                        Mail::send('email.notificacion', $data, function ($mensaje) use ($data){
                            $mensaje->to($data['email'], $data['name'])->subject($data["asunto"]);
                        });
                        */
                    }else{

                        if($value->celular != "" && strlen($value->celular)>= 5){

                            $celular 	= $value->celular;
                            $msg_cel  	= $value->msg_cel;// plantila whats
                            $msg_cel 	= trim($msg_cel);

                            /////////////
                            $mensaje .= "Mensajes whatsapp:<br>";

                            if($flujo_ejecucion == "PREREGISTRO" ){

                                $cant_usuario = Usuario::where('name',$dni)
                                    ->where('estado',1)
                                    ->select('name','password')->count();

                                if($cant_usuario > 0){

                                    $data = array(
                                        'body'      => $msg_cel,//msg_cel
                                        'celular'   => $celular,
                                        'pdf_url'   => ''

                                    );

                                    $send = $this->sendTo($data);

                                    ////////////////////////

                                    if($send){
                                        $this->actualizaHM($id);

                                        DB::table('tb_msn')->where('id', $paq_msn->id)
                                            ->increment('mensajes', 1);
                                    }else{
                                        $this->actualizaHM($id,false);
                                    }

                                    /*return response()->json([
                                        //"status" => $usuarios
                                        "status" => $send
                                    ]);*/

                                }

                            }
                            // PREREGISTRO
                            if($flujo_ejecucion == "PREREGISTRO" ){

                                $texto_test =  $msg_cel;
                                $data = array(
                                    'body'      => $texto_test,//msg_cel
                                    'celular'   => $celular,
                                    'pdf_url'   => ''
                                );

                                $send = $this->sendTo($data);

                                if($send){
                                    $this->actualizaHM($id);

                                    DB::table('tb_msn')->where('id', $paq_msn->id)
                                        ->increment('mensajes', 1);
                                }else{
                                    $this->actualizaHM($id,false);
                                }
                                /*return response()->json([
                                        //"status" => $usuarios
                                        "status" => $send
                                ]);*/

                            }

                            if($flujo_ejecucion == "INVITACION" ){

                                // EXTRAER USUARIO Y PASS
                                //SELECT * FROM users where name='10000001' limit 1
                                $cant_usuario = Usuario::where('name',$dni)
                                    ->where('estado',1)
                                    ->select('name','password')->count();

                                if($cant_usuario > 0){

                                    $usuario = Usuario::where('name',$dni)
                                        ->where('estado',1)
                                        ->select('name','password')->first();

                                    $dni      = $usuario->name;
                                    $password = $usuario->password;

                                    /*$texto_test = $msg_cel."\n\nUsuario: *$dni*\n"
                                                        ."Contraseña: *$password*\n";*/
                                    $texto_test =  "Usuario: *$dni*\n"
                                        ."Contraseña: *$password*\n\n".$msg_cel;

                                    $data = array(
                                        'body'      => $texto_test,//msg_cel
                                        'celular'   => $celular,
                                        'pdf_url'   => ''

                                    );

                                    $send = $this->sendTo($data);

                                    ////////////////////////

                                    if($send){
                                        $this->actualizaHM($id);

                                        DB::table('tb_msn')->where('id', $paq_msn->id)
                                            ->increment('mensajes', 1);
                                    }else{
                                        $this->actualizaHM($id,false);
                                    }

                                }

                            }

                            //CONFIRMACION
                            if($flujo_ejecucion == "CONFIRMACION" OR $flujo_ejecucion == "RECORDATORIO"){

                                // si dni existe en tb actividades_estudiantes -
                                // PDF
                                $codigoG = $dni;
                                $nombresG  = $nombre;
                                $apellidosG = $nombres_apat;
                                $apellidosG_2 = $nombres_amat;

                                //arrar para generar PDF
                                $data = array(
                                    'codigoG' => $codigoG,
                                    'nombresG' => $nombre,
                                    'apellidosG' => $apellidosG,
                                    'apellidosG_2' => $apellidosG_2
                                );


                                $file = 'storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf';
                                //$file = 'storage/confirmacion/12345678.pdf';
                                $directory = 'storage/confirmacion/';

                                //Devuelve true
                                //$exists = is_file( $file );

                                // SOLO PARA CREAR NUEVAMENTE LOS GAFETES
                                if(is_file($file)){

                                    //$pdf = PDF::loadView('evento.gafete', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
                                    //$pdf_url = "http://enc-ticketing.org/tktv2/public/storage/confirmacion/2-10000096.pdf";

                                    //obtener gafete
                                    $nrs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$id_lista)
                                        ->where('gafete',1)->count();

                                    $pdf_url = "";

                                    // ENVIA MSG CON GAFETE
                                    if($nrs_gafete > 0){

                                        //$pdf_url = "http://localhost/tkt_des/public/".$file;
                                        $pdf_url = "https://enc-ticketing.org/demo/public/".$file;
                                        //$pdf_url = public_path()."/".$file;
                                    }

                                    $data = array(
                                        'body'      => $msg_cel,
                                        'celular'   => $celular,
                                        'pdf_url'   => $pdf_url
                                    );

                                    $send = $this->sendTo($data);

                                    if($send){
                                        $this->actualizaHM($id);

                                        DB::table('tb_msn')->where('id', $paq_msn->id)
                                            ->increment('mensajes', 1);
                                    }else{
                                        $this->actualizaHM($id,false);
                                    }

                                }else{
                                    return 'La URL del PDF no existe';
                                }


                            }

                            // NOINVITADO
                            if($flujo_ejecucion == "NOINVITADO"){

                                $data = array(
                                    'body'      => $msg_cel,
                                    'celular'   => $celular,
                                    'pdf_url'   => ''
                                );

                                $send = $this->sendTo($data);

                                if($send){
                                    $this->actualizaHM($id);

                                    DB::table('tb_msn')->where('id', $paq_msn->id)
                                        ->increment('mensajes', 1);
                                }else{
                                    $this->actualizaHM($id,false);
                                }
                            }

                            if($flujo_ejecucion == "BAJA_EVENTO"){

                                $data = array(
                                    'body'      => $msg_cel,
                                    'celular'   => $celular,
                                    'pdf_url'   => ''
                                );

                                $send = $this->sendTo($data);


                                if($send){
                                    $this->actualizaHM($id);

                                    DB::table('tb_msn')->where('id', $paq_msn->id)
                                        ->increment('mensajes', 1);
                                }else{
                                    $this->actualizaHM($id,false);
                                }
                            }


                        }
                    }

                } // end whats

            }
            echo "<h1>Proceso de Envío de Correos</h1>";
            echo $error;
            echo $mensaje;
            var_dump($send);
        }else{
            echo "<h1>0 Correos Enviados</h1>";
        }

    } // function

    private function sendTo(...$usuarios) {

        $result = [];

        foreach ( $usuarios as $usuario ) {

            $telefono = $usuario['celular'];
            $body = $usuario['body'];

            if($usuario['pdf_url']!=""){

                $pdf_url = $usuario['pdf_url'];
                $ano = date('Y');
                $file = 'GAFETE_CAII'.$ano.'.pdf';

                $result[] = Whatsapp::send($telefono, $body);
                $result[] = Whatsapp::send($telefono, $pdf_url, $file);

            }else{

                $result[] = Whatsapp::send($telefono, $body);

            }

        }

        return $result;
    }

    function getPlantilla($plantilla_id){
        // RECUPERO LA PLANTILLA
        $rs_plantilla = Plantillaemail::where('id',$plantilla_id)->first();

        $flujo_ejecucion = $rs_plantilla->flujo_ejecucion;
        $laplantilla = $rs_plantilla->plantillahtml;

        $file=fopen(resource_path().'/views/email/'.$plantilla_id.'.blade.php','w') or die ("error creando fichero!");

        fwrite($file,$laplantilla);
        fclose($file);
        return $file;
    }

    function actualizaHM($id,$hoy= true){
        $fecha_envio = $hoy ?  Carbon::now() : '2010-01-01';
        DB::table('historia_email')->where('id',$id)->update([
            'fecha_envio'	=>	$fecha_envio
        ]);
    }

    public function show($id)
    {

    }

    function participantes(Request $request){
        $search = $this->requestToArraySearch($request);
        $inv = $search['inv'];//ADDED
        $inv_id = $search['inv_id'];//ADDED
        $table = $inv == 1 ? 'inv_estudiantes': 'estudiantes';

        $q = $this->generateQuery($search);
        $q->select(DB::raw('COUNT(DISTINCT('.$table.'.email)) as estudiantes'));
        $q->getQuery()->groups=null;

        if($inv!=1)$q->where($table.'.estado','=',1);
        if($inv==1)$q->where($table.'.error','=',0);
        if($inv==1&&$inv_id>0)$q->where('inv_estudiantes.import_id',$inv_id);

        $query = str_replace(array('?'), array('\'%s\''), $q->toSql());$query = vsprintf($query, $q->getBindings());
        $n = $q->get()->first()->estudiantes??0;
        //return $query;
        return $n;
    }

    static function generateQuery($search){
        $evento = $search["evento"];
        $grupo = $search["grupo"];
        $pais = $search["pais"];
        $depa = $search["depa"];
        $organizacion = $search["organizacion"];
        $profesion = $search["profesion"];
        $all = $search["all"];
        $noemails = CampaniasController::getNoEmails();

        $inv = $search['inv'];//ADDED
        $inv_id = $search['inv_id'];//ADDED
        $table = $inv == 1 ? 'inv_estudiantes': 'estudiantes';
        //dd($noemails);

        if($inv!=1){
            if($all!=1&&($evento||$grupo))
                $q = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc');
            else
                $q = Estudiante::select('id');
        }else
            $q = InvStudents::select('id');


            if($all!=1){
                if( $inv != 1 && ($evento||$grupo)){
                    if($evento)$q->where('de.eventos_id',$evento);
                    if($grupo)$q->where('de.dgrupo','like', '%'.$grupo.'%');
                }
                if($pais)$q->where($table.'.pais', $pais);
                if($depa)$q->where($table.'.region', $depa);
                if($organizacion)$q->where($table.'.organizacion', $organizacion);
                if($profesion)$q->where($table.'.profesion', $profesion);
            }
            if(count($noemails))foreach ($noemails as $email)$q->where($table.'.email','NOT LIKE',"%".$email."%");
            if($inv!=1)$q->where('estudiantes.estado','=',1);
            if($inv==1)$q->where('inv_estudiantes.error','=',0);
            if($inv==1&&$inv_id>0)$q->where('inv_estudiantes.import_id',$inv_id);



        $q->where(function ($query) use ($table) {
                $query->whereNotNull($table.'.email')
                    ->where($table.'.email', "!=", '')
                ;
            });
            $q->select('*');
            $q->groupBy($table.'.email');
        //$query = str_replace(array('?'), array('\'%s\''), $q->toSql());$query = vsprintf($query, $q->getBindings());
        //dd($query);
        return $q;
    }

    static function requestToArraySearch(Request $request){
        $evento  = $request->get('evento');
        $grupo   = $request->get('grupo');
        $pais    = $request->get('pais');
        $depa    = $request->get('depa');
        $organizacion = $request->get('organizacion');
        $profesion    = $request->get('profesion');
        $all          = $request->get('all');
        $mailing_test = $request->get('mailing_test');
        $inv   = $request->get('inv')??0;
        $inv_id   = $request->get('inv_id')??0;

        $email_test   = $request->get('email_test');
        return compact("evento", "grupo", "pais", "depa", "organizacion", "profesion", "all","mailing_test","email_test", "inv", "inv_id");
    }

    /*
    static function totalStatus($campania_id){
        $q = HistoryEmails::select('status',DB::raw('count(*) as total'))->where("campania_id",$campania_id)->groupBy('status');
        $sum = $status_0  = $status_1 = $status_2 = $status_3 = $status_4 = 0;
        $histories = $q->get();
        if(count($histories)>0) {
            foreach ($histories as $h) {
                if($h->status == 0)$status_0 = $h->total;
                if($h->status == 1)$status_1 = $h->total;
                if($h->status == 2)$status_2 = $h->total;
                if($h->status == 3)$status_3 = $h->total;
                if($h->status == 4)$status_4 = $h->total;
            }
        }
        $sum = $status_0 + $status_1 + $status_2 + $status_3 + $status_4;
        return compact("sum", "status_0", "status_1", "status_2", "status_3","status_4");
    }*/

    public function reporte(Campanias $campania, CampaniaRepository $repository)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        //Session::forget("personalizados");
        $id = $campania->id;
        $total = $repository->totalXStatus($id);
        $q = HistoryEmails::where("campania_id",$id)->whereBetween('status',[1,2])->orderBy("id",'asc');
        $lista1 = $q->limit(10)->get();
        $lista1_total = $q->count()??0;

        $lista2 = HistoryEmails::where("campania_id",$id)->orderBy("id",'asc')->limit(10)->get();//->where('envio',1)
        $lista1_count = count($lista1)??0;
        $lista2_count = count($lista2)??0;
        $lista1_dif = $lista1_total - $lista1_count;
        $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();

        return view("campanias.reportes", compact('total', 'campania','lista1','lista2','lista1_count','lista1_total','lista1_dif','lista2_count','personalizados'));
    }

    public function buscaDni($dni, $personalizados)
    {
        if(count($personalizados)>0){
            foreach($personalizados as $reg)
                if($reg["dni"]==$dni)return true;
        }
        return false;
    }

    public function saveper(Request $request)
    {
        $save  = $request->get('save');
        $search  = $request->get('search');
        $campania_id  = $request->get('id');
        $dni = $request->get('dni');
        $dni = trim($dni);
        //if($dni=='')
        if($search==1){
            /*
            if($dni==''){
                alert()->success("DNI invalido", 'Mensaje')->persistent('close');
                return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
            }
            $estudiante = Estudiante::where("dni_doc",$dni)->first();
            if(!$estudiante){
                alert()->success("No existe estudiante con el dni {$dni} ingresado", 'Mensaje')->persistent('close');
                return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
            }
            $existe = HistoryEmails::where("dni",$dni)->where("campania_id",$campania_id)->first();
            if($existe){
                alert()->success("El participante con el dni {$dni} se encuentra registrado en la campaña", 'Mensaje')->persistent('close');
                return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
            }
            $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();
            if($this->buscaDni($dni, $personalizados)){
                alert()->success("DNI {$dni} registrado en la lista", 'Mensaje')->persistent('close');
                return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
            }
            */
            $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();
            $res = $this->validaDNI(compact("dni","campania_id","personalizados"));
            if(!$res["success"]){
                alert()->success($res["message"], 'Mensaje')->persistent('close');
                return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
            }
            $estudiante = $res["data"];
            $nombre = trim("{$estudiante->nombres} {$estudiante->ap_paterno} {$estudiante->ap_materno}");
            Session::push('personalizados', [
                'id' => $estudiante->id,
                'dni' => $estudiante->dni_doc,
                'nombre' => $estudiante->nombres,
                'ape_pat' => $estudiante->ap_paterno,
                'ape_mat' => $estudiante->ap_materno,
                'nombres' => $nombre,
                'email' => $estudiante->email,
                'check' => 1
            ]);
        }
        if($save==1){
            $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();
            $personalizados  = $request->get('checks');
            if(count($personalizados)==0){
                alert()->success("No hay participantes", 'Mensaje')->persistent('close');
                return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
            }
            $campania = Campanias::find($campania_id);
            $plantilla_id = $campania->checkHTML;
            $evento = $campania->eventos_id;
            $from_id = $campania->from_id;
            $plantilla = Plantillaemail::find($plantilla_id);
            $flujo_ejecucion = $plantilla->flujo_ejecucion;
            $asunto = $plantilla->asunto;
            //$id_plantilla = $plantilla->id;
            $from = Emails::findOrFail($from_id);

            foreach($personalizados as $reg){
                //$estudiante_id = $reg["id"];
                $estudiante_id = $reg;
                $estudiante = Estudiante::find($estudiante_id);
                $nombre = $estudiante->nombres;
                $dni = $estudiante->dni_doc;
                $ape_pat = $estudiante->ap_paterno;
                $ape_mat = $estudiante->ap_materno;
                $email = $estudiante->email;
                $cel_cod = $estudiante->codigo_cel;
                $cel_nro = $estudiante->celular;
                $accedio = $estudiante->accedio;
                //$evento = $estudiante->eventos_id;
                $data = [
                    "tipo" => "EMAIL",
                    'flujo' => $flujo_ejecucion,
                    'plantilla_id' => $plantilla_id,
                    'campania_id' => $campania_id,
                    'evento_id' => ($evento) ? $evento : 0,
                    'actividad_id' => 0,//REV
                    'fecha_envio' => '2000-01-01',
                    'email' => $email,
                    'asunto' => $asunto,
                    'estudiante_id' => $estudiante_id,
                    'nombre' => $nombre??'',
                    'ape_pat' => $ape_pat??'',
                    'ape_mat' => $ape_mat??'',
                    'dni' => $dni,
                    'cel_cod' => $cel_cod??'',
                    'cel_nro' => $cel_nro??'',
                    'accedio' => $accedio??'',
                    'msg_text' => '',
                    'msg_cel' => '',
                    'from_nombre' => $from->nombre,
                    'from_email' => $from->email,
                    'status' => 0,
                    'envio'=>1
                ];
                $xdata = HistoryEmails::create($data);
                SendHistoryEmails::dispatch($xdata->id)->onConnection('database')->onQueue("emails")
                    ->delay(Carbon::now()->addMinutes(0.2));;

            }
            Session::forget("personalizados");
        }
        return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
    }

    public function actualiza(Request $request, CampaniaRepository $repository)
    {
        $id  = $request->get('id');
        $campania_id  = $request->get('campania_id');
        $estudiante_id  = $request->get('estudiante_id');
        $email  = $request->get('email');
        $is_valid_email = (trim($email)=='' || filter_var($email, FILTER_VALIDATE_EMAIL) === false)?0:1;
        if(!$is_valid_email){
            alert()->success("Email invalido", 'Mensaje')->persistent('close');
            return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
        }
        Estudiante::where('id',$estudiante_id)->update([
            'email'	=>	$email
        ]);
        HistoryEmails::where('id',$id)->update([
            'status' =>	0,
            'email'  => $email
        ]);

        $historyEmail = HistoryEmails::find($id)->toArray();
        $campania = Campanias::find($campania_id,[
            "tipo","flujo","plantilla_id","actividad_id",
            "asunto","from_nombre","from_email"]);
        $historyEmail["tipo"] = $campania->tipo;
        $historyEmail["flujo"] = $campania->flujo;
        $historyEmail["plantilla_id"] = $campania->plantilla_id;
        $historyEmail["actividad_id"] = $campania->actividad_id;
        $historyEmail["asunto"] = $campania->asunto;
        $historyEmail["from_nombre"] = $campania->from_nombre;
        $historyEmail["from_email"] = $campania->from_email;
        $data = $repository->send($historyEmail);

        return redirect()->route('campanias.reportes', ['campania'=>$campania_id]);
    }

    public function verificadni(Request $request){
        $dni = $request->get('dni')??'';
        $campania_id = $request->get('campania_id')??'';
        $all = $request->get('all')??'';
        $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();
        $res = $this->validaDNI(compact("dni","campania_id","personalizados","all"));
        if($res["success"]){
            $estudiante = $res["data"];
            if($estudiante){
                $nombre = trim("{$estudiante->nombres} {$estudiante->ap_paterno} {$estudiante->ap_materno}");
                Session::push('personalizados', [
                    'id' => $estudiante->id,
                    'dni' => $estudiante->dni_doc,
                    'nombre' => $estudiante->nombres,
                    'ape_pat' => $estudiante->ap_paterno,
                    'ape_mat' => $estudiante->ap_materno,
                    'nombres' => $nombre,
                    'email' => $estudiante->email,
                    'check' => 1
                ]);
            }
            $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();
            $data = [];
            if(count($personalizados)>0)foreach ($personalizados as $v)array_push($data,$v);
            return ["success"=>true,"data"=>$data];
        }
        return $res;
    }

    public function validaDNI($data){
        $campania_id = $data["campania_id"];
        $dni = $data["dni"];
        $personalizados = $data["personalizados"];
        $all = $data["all"];
        $dni = trim($dni);
        if($all!=0)return ["success"=>true,"message"=>"","data"=>[]];
        if($dni=='')
            return ["success"=>false,"message"=>"Ingrese DNI"];
        if(strlen($dni)!=8||intval($dni)<1)
            return ["success"=>false,"message"=>"DNI invalido"];
        $estudiante = Estudiante::where("dni_doc",$dni)->first();
        if(!$estudiante)
            return ["success"=>false,"message"=>"No existe estudiante con el dni {$dni} ingresado"];
        $email = $estudiante->email;
        $is_valid_email = (trim($email)=='' || filter_var($email, FILTER_VALIDATE_EMAIL) === false)?0:1;
        if(!$is_valid_email)
            return ["success"=>false,"message"=>"El participante {$estudiante->nombres} {$estudiante->ap_paterno} no tiene email válido"];
        $existe = HistoryEmails::where("dni",$dni)->where("campania_id",$campania_id)->first();
        if($existe)
            return ["success"=>false,"message"=>"El participante con el dni {$dni} se encuentra registrado en la campaña"];
        if($this->buscaDni($dni, $personalizados))
            return ["success"=>false,"message"=>"DNI {$dni} registrado en la lista"];
        //AGREGAR SESION
        return ["success"=>true,"message"=>"","data"=>$estudiante];
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["eliminar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $ids = $request->tipo_doc;
        if(count($ids)>0){
            foreach($ids as $id){
                Campanias::where('id',$id)->delete();
                Historiaemail::where('campania_id',$id)->delete();
                HistoryEmails::where('campania_id',$id)->delete();
            }
        }


        Cache::flush();
        alert()->error('Registros borrados.','Eliminado');
        return redirect()->back();
    }

    function runQueue($id, Request $request){
        $data = $request->toArray();
        //$keys = array_keys($data);
        try {
            SendParticipantesCampanias::dispatch($data)->onConnection('database')->onQueue("emails");
        }
        catch(\Exception $e) {
            return false;
        }
        return true;
    }

    function fileGetContents($url, $headers = [],$params=[], $method = 'GET',$transform=0)
    {
        $no_headers= true;
        if(is_array($headers)&&count($headers)>0){
            $no_headers = false;
            foreach($headers as $i=>$v)
                if($v!="")$headers[$i].="\r\n";
                else unset($headers[$i]);
        }
        if($method=='GET'&&$no_headers)return file_get_contents($url);
        $opts = [];
        $opts['http']['method']=$method;
        if(!$no_headers)$opts['http']['header']=$headers;
        if(count($params)>0)	$opts['http']['content'] = http_build_query($params);
        $context = stream_context_create($opts);
        $html = file_get_contents($url, false, $context);
        if($transform==1)return mb_convert_encoding($html, 'UTF-8', mb_detect_encoding($html, 'UTF-8, ISO-8859-1', true));
        return $html;
    }


    // Funcionalidad para agregar exepciones desde un formulario
    public function noemails(Request $request)
    {
        $rules = [
        'email' => 'required|min:1|unique:noemails,email',
        ];
            $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        //save or edit
        $id = $request->get('id');
        $status = $request->get('status');
        $email = $request->get('email');
        $save = $request->get('save');
        $delete = $request->get('delete');
        $errors=new Collection;
        if($delete==1)DB::table('noemails')->where('id',$id)->delete();

        if($save==1){
            $data = ['email' => $email,'status'=>$status>0?1:0];
            if($id>0)$rules['email'].=",{$id}";
            $validator = Validator::make($data, $rules);
            $errors = $validator->errors();
            if (!$validator->fails()) {
                if($id>0)DB::table('noemails')->where('id',$id)->update($data);
                else DB::table('noemails')->insert($data);
            }
        }
        $page = 6;
        $noemails = DB::table('noemails')->paginate($page);
        $estados = [1=>"SI",0=>"NO"];
        $campos = ["id"=>"","email"=>"","status"=>1];
        $noemail = (object)$campos;

        return view("campanias.noemails", compact('noemails','estados','noemail','errors'));
    }

    public function emails_errores($id){

        $q = HistoryEmails::where("campania_id",$id)->whereBetween('status',[1,2])
                ->select('campania_id','email','status','dni')
                ->orderBy("id",'asc');
        //$lista1 = $q->limit(10)->get();
        $lista1 = $q->get();
        echo "ID - EMAIL - DNI<br>";
        foreach($lista1 as $i=>$a){
            echo $i+1 ." -- ". $a->email." -- ".$a->dni;
            echo "<br>";
        }
        //dd($lista1);
    }

}
