<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use App\Asistencia_evento, App\Evento, App\Actividade;
use App\Estudiante;
use App\AccionesRolesPermisos;

use App\Repositories\EstudianteRepository;
use App\Exports\EstudianteExport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use DateInterval;
use DatePeriod;
use Alert;
use Auth;

class AsistenciaController extends Controller
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
        if(!isset( session("permisosTotales")["asistencia"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "asistencia";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if($request->eventos_id != ""){
            session(['eventos_id'=> $request->eventos_id]);
        }

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        $fecha = $request->get('fe')?$request->get('fe'):0;
 
        $evento = Evento::find(session('eventos_id'));
        
        $fini = $evento->fechai_evento;
        $ffin = $evento->fechaf_evento;
        
        $begin = new DateTime($fini);
        $end = new DateTime($ffin);
        //date_add($end, date_interval_create_from_date_string('1 days'));

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $dias = [];
        $cont = 0;
        foreach ($dateRange as $date) {
            $dias[$cont]["fecha"] = $date->format("d/m/Y");

            $actividad = Actividade::where("eventos_id",session('eventos_id'))
                                    ->where("fecha_desde",$date->format("Y-m-d H:i:s"))
                                    ->first();
            if($actividad){
                $dias[$cont]["actividad_id"] = $actividad->id;
            }else{
                $dias[$cont]["actividad_id"] = 0;
            }
            $cont++;
        }
       
        Cache::flush();
        if($request->get('s')){
            $search = $request->get('s');

            $eventos_datos = Asistencia_evento::join('estudiantes as est','est.dni_doc','=','asistencia_eventos.estudiantes_id')
            ->join('users', 'users.id','=', 'asistencia_eventos.usuario_id')
            ->join('eventos as e', 'e.id','=', 'asistencia_eventos.evento_id')
            ->select('asistencia_eventos.id', 'asistencia_eventos.estudiantes_id', 'est.ap_paterno', 'est.ap_materno', 'est.nombres', 'asistencia_eventos.fecha','asistencia_eventos.hora', 'users.name','asistencia_eventos.actividad_id','asistencia_eventos.evento_id')
                ->where("e.id", session('eventos_id'))
                ->where("est.dni_doc", "LIKE", '%'.$search.'%')
                ->where("est.dni_doc", "LIKE", '%'.$search.'%')
                ->orWhere("est.nombres", "LIKE", '%'.$search.'%')
                ->orWhere("est.ap_paterno", "LIKE", '%'.$search.'%')
                ->orWhere("est.ap_materno", "LIKE", '%'.$search.'%')
                ->orWhere("asistencia_eventos.hora", "LIKE", '%'.$search.'%')
                ->orWhere("users.name", "LIKE", '%'.$search.'%')
                ->orWhere("e.nombre_evento", "LIKE", '%'.$search.'%')
                ->orWhere(DB::raw('CONCAT(est.nombres," ", est.ap_paterno," ", est.ap_materno)'), 'LIKE' , '%'.$search.'%')
                ->orWhere(DB::raw('CONCAT(est.ap_paterno," ", est.ap_materno," ", est.nombres)'), 'LIKE' , '%'.$search.'%')
                ->when($fecha>0, function($query) use($fecha){
                    $query->where('asistencia_eventos.fecha','=',$fecha);
                })
                
            ->orderBy('asistencia_eventos.id', request('sorted', 'DESC'))
            ->paginate($pag);

        }else{

            $key = 'asistencia.page.'.request('page', 1);
            $eventos_datos = Cache::rememberForever($key, function() use ($pag, $fecha){
                return Asistencia_evento::join('estudiantes','estudiantes.dni_doc','=','asistencia_eventos.estudiantes_id')
                ->join('users', 'users.id','=', 'asistencia_eventos.usuario_id')
                ->where("asistencia_eventos.evento_id", session('eventos_id'))
                ->select('asistencia_eventos.id', 'asistencia_eventos.estudiantes_id', 'estudiantes.ap_paterno', 'estudiantes.ap_materno', 'estudiantes.nombres', 'asistencia_eventos.fecha','asistencia_eventos.hora', 'users.name','asistencia_eventos.actividad_id','asistencia_eventos.evento_id')
                     ->when($fecha>0, function($query) use($fecha){
                         $query->where('asistencia_eventos.fecha','=',$fecha);
                     })
                ->orderBy('asistencia_eventos.id', request('sorted', 'DESC'))
                ->paginate($pag);

            });
        }

         return view('asistencia.asistencia', compact('eventos_datos','permisos', 'dias')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["asistencia"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "asistencia";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);


        if($request->eventos_id != ""){
            $rs_tipo_asistencia = Evento::findOrFail($request->eventos_id);

            if($rs_tipo_asistencia){
                $e_tipo_id = $rs_tipo_asistencia->eventos_tipo_id;
                session(['eventos_id'=> $request->eventos_id, 't'=>$e_tipo_id]);
            }
        }
        
        if(session('eventos_id') == false){
            return redirect()->route('home');
        }

        return view('asistencia.create', compact('permisos'));
    }

    public function validar_dni (Request $request,$dni)
    {
        //return "Asistencia General";
        if($request->ajax()){

            $dni = trim($dni);
            
            // ESTRUCTURA PARA ASISTENCIA CON FECHA
            $date = Carbon::now();
            $fecha_hoy = $date->format('Y-m-d');
            $fecha_hoy_vista = $date->format('d/m/Y');
            $h_act = $date->format('H:i');

            $minutes = 60;#minutos de tolerancia para la asistencia
            $minutos = 240;#minutos de bloqueo de asistencia
            $h_tolerancia_c = Carbon::parse($h_act)->addMinutes($minutes);
            $h_tolerancia = Carbon::parse($h_tolerancia_c)->format('H:i');
            //return "h_act= $h_act -- h_tolerancia= $h_tolerancia";
            
            /*$fecha_hoy = "2019-12-02";
            $fecha_hoy_vista = "02-12-2019";
            $h_act = "14:30";
            $h_tolerancia = "15:00";*/

            $msg = "";
            $msg_tipo = 1; // 1: error  2: warning 3: succes
            $msg_error = "danger";
            $nombres = "";
            $titulo = "";

            // validar si existe evento en la fecha actual
            #primera consulta
            $rs_act_hoy = DB::table('eventos')
                            ->select('nombre_evento','fechaf_evento','hora','hora_fin','eventos_tipo_id')
                            ->where('id',session('eventos_id'))
                            ->where(DB::raw("str_to_date(fechaf_evento, '%Y-%m-%d')"), '>=', $fecha_hoy)
                            ->orderBy("fechaf_evento")
                            ->get();

            if(count($rs_act_hoy) > 0){

                $tipo_evento = $rs_act_hoy[0]->eventos_tipo_id;

                $flag_pasa = false;
                //TIPO 2: MOD EVENTOS
                if($tipo_evento == 2){
                    $flag_pasa = true;
                }

                $fe = $rs_act_hoy[0]->fechaf_evento;
                $h_ini = $rs_act_hoy[0]->hora;
                $h_fin = $rs_act_hoy[0]->hora_fin;

                $h_ini_a = Carbon::parse($h_ini)->subMinutes($minutes);
                $h_ini_a = Carbon::parse($h_ini_a)->format('H:i');

                $h_fin_a = Carbon::parse($h_fin)->addMinutes($minutes);
                $h_fin_a = Carbon::parse($h_fin_a)->format('H:i');

                //return("H ini: ".$h_ini ." h_ini_a: ".$h_ini_a. " H fin: ".$h_fin ." h_fin_a: ".$h_fin_a);

            }else{
            
                $msg = "No existe ningún evento en el sistema con la<br> fecha: $fecha_hoy_vista";
                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta; // Para la fecha: 12/12/2019 no existe ningún evento
            }
            #segunda consulta
            // VALIDAR CON EL DNI si ya esta registrado
            $fe_mod = Carbon::now()->format('d/m/Y');
            $rs_validar_dni_asistencia = DB::table('asistencia_eventos')
                                    ->where('estudiantes_id', $dni)
                                    ->where('fecha',$fe_mod)
                                    ->orderBy('id','desc')
                                    ->limit(1)
                                    ->get();

            //dni: 72431426 - fecha: 2019-12-02 - actividad: 1 - mod: 02/12/2019
            //return "dni: $dni - fecha: $fe - actividad: $actividad_id - fe_mod: $fe_mod";

            if(count($rs_validar_dni_asistencia) > 0){

                $h_tb = Carbon::parse($rs_validar_dni_asistencia[0]->created_at);
                $hh_act = Carbon::now();
                $dif_minutos = $h_tb->diffInMinutes($hh_act);
                //return $dif_minutos;
                if($dif_minutos <= $minutos){

                    $msg = "PARTICIPANTE REGISTRADO<br>REGISTRADO HACE: ".$dif_minutos. " min";
                    $msg_error = "warning";

                    $respuesta = array(
                        'msg'   => $msg,
                        'tipo'  => $msg_tipo,
                        'error' => $msg_error,
                        'nombres'=>$nombres,
                        'titulo' => $titulo
                    );

                    return $respuesta; 
                }

            }
            
            #tercera consulta
            $data = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                            ->join('eventos as e', 'e.id','=','de.eventos_id')
                            ->select('estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','e.nombre_evento')
                            ->where('de.estudiantes_id',$dni)
                            ->where('de.eventos_id',session('eventos_id'))
                            ->where('de.daccedio','SI')
                            ->get();
            
            if(count($data) > 0){
                $msg_error = "succes";
                $msg = "";
                $msg_tipo = 3;
                $nombres = $data[0]->nombres. " ".$data[0]->ap_paterno. " ".$data[0]->ap_materno;
                $titulo = $data[0]->nombre_evento;

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta; 

            }else{
                $msg_error = "danger";
                $msg_tipo = 1;
                $msg = "PARTICIPANTE NO REGISTRADO";
                $nombres = "";
                $titulo = "";

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta; 
            }
        }
    }

    public function validar_dni_x_hora (Request $request,$dni)
    {
        //return "Asistencia de Actividades";
        if($request->ajax()){
            $dni = trim($dni);
            // ESTRUCTURA PARA ASISTENCIA CON FECHA
            $date = Carbon::now();
            $fecha_hoy = $date->format('Y-m-d');
            $fecha_hoy_vista = $date->format('d/m/Y');
            $h_act = $date->format('H:i');
            $minutes = 45;
            $h_tolerancia_c = Carbon::parse($h_act)->addMinutes($minutes);
            $h_tolerancia = Carbon::parse($h_tolerancia_c)->format('H:i');
            
            // FALTA VALIDAR FECHAS CON 1 HORA ANTES Y DESPUES
            /*$fecha_hoy = "2019-12-03";
            $fecha_hoy_vista = "03/12/2019";
            $h_act = "15:15";//10:30 - 11:20 - 13:00 
            $h_tolerancia = "16:15";//11:15 - 12:05 - 13:45*/

            $msg = "";
            $msg_tipo = 1; // 1: error  2: warning 3: succes
            $msg_error = "danger";
            $nombres = "";
            $titulo = "";

            $rs_act_hoy = Actividade::
                            select('fecha_desde','fecha_hasta','hora_inicio','hora_final', 'titulo','subtitulo')
                            ->where('eventos_id',session('eventos_id'))
                            ->where(DB::raw("str_to_date(fecha_hasta, '%Y-%m-%d')"),'=', $fecha_hoy)
                            //->where('hora_final','>', $h_act)//DB::raw("CURRENT_TIME()"))//
                            ->orderBy("fecha_desde", 'ASC')
                            ->orderBy("hora_inicio", 'ASC')
                            ->get();

            if(count($rs_act_hoy) > 0){
                $fe = $rs_act_hoy[0]->fecha_desde;
                $h_ini = $rs_act_hoy[0]->hora_inicio;
                $h_fin = $rs_act_hoy[0]->hora_final;

            }else{
            
                $msg = "No existe ningún evento en el sistema con la<br> fecha: $fecha_hoy_vista";
                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta;
            }

            $rs_act_hoy = DB::table('actividades')->join('actividades_estudiantes as de','actividades.id','=','de.actividad_id')
                            ->select('actividades.id','actividades.fecha_desde','actividades.fecha_hasta','actividades.hora_inicio','actividades.hora_final', 'actividades.titulo','actividades.subtitulo')
                            ->where(DB::raw("str_to_date(actividades.fecha_hasta, '%Y-%m-%d')"), $fecha_hoy)
                            ->where('actividades.hora_final','>=', $h_tolerancia)
                            ->where('actividades.hora_inicio','<=', $h_tolerancia)
                            ->where('de.estudiantes_id', $dni)
                            ->orderBy("actividades.fecha_desde", 'ASC')
                            ->orderBy("actividades.hora_inicio", 'ASC')
                            ->get();


            if(count($rs_act_hoy) > 0){
                $fe = $fe = Carbon::parse($rs_act_hoy[0]->fecha_desde)->format('Y-m-d');
                $h_ini = $rs_act_hoy[0]->hora_inicio;
                $h_fin = $rs_act_hoy[0]->hora_final;
                $actividad_id = $rs_act_hoy[0]->id;
                session(['actividad_id'=> $actividad_id]);
                
                $titulo = $rs_act_hoy[0]->titulo ." ".$rs_act_hoy[0]->subtitulo;

            }else{
            
                $msg = "La fecha: $fecha_hoy_vista y la hora: $h_act no existe en el sistema.";

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta; 
            }

            // VALIDAR CON EL DNI
            $fe_mod = $date->format('d/m/Y');;

            //$fe_mod = Carbon::parse($fe)->format('d/m/Y');
            $rs_validar_dni_asistencia = DB::table('asistencia_eventos')
                                    ->where('estudiantes_id', $dni)
                                    ->where('fecha',$fe_mod)
                                    ->where('actividad_id',$actividad_id)
                                    ->orderBy('id','desc')
                                    ->limit(1)
                                    ->get();

            if(count($rs_validar_dni_asistencia) > 0){

                $h_tb = Carbon::parse($rs_validar_dni_asistencia[0]->created_at);
                $hh_act = Carbon::now();
                $dif_minutos = $h_tb->diffInMinutes($hh_act);
                if($dif_minutos <= 25){

                    $msg = "PARTICIPANTE REGISTRADO<br><br> ACTIVIDAD: $titulo<br><br> HORA:".$rs_validar_dni_asistencia[0]->hora."<br>REGISTRADO HACE: ".$dif_minutos. " min";
                    $msg_error = "warning";

                    $respuesta = array(
                        'msg'   => $msg,
                        'tipo'  => $msg_tipo,
                        'error' => $msg_error,
                        'nombres'=>$nombres,
                        'titulo' => $titulo
                    );

                    return $respuesta; 

                }

            }

            $data = Estudiante::join('actividades_estudiantes as de','estudiantes.dni_doc','=','de.estudiantes_id')
                            ->join('actividades as a', 'a.id','=','de.actividad_id')
                            ->join('estudiantes_act_detalle as dact', 'estudiantes.dni_doc','=','dact.estudiantes_id')
                            ->select('a.id','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','a.titulo as nombre_evento','a.subtitulo','a.hora_inicio', 'a.hora_final')
                            ->where('de.estudiantes_id',$dni)
                            ->where('de.eventos_id',session('eventos_id'))
                            ->where('dact.daccedio','SI')
                            ->where(DB::raw("str_to_date(fecha_desde,'%Y-%m-%d')"),'=',$fe)
                            ->where('a.hora_inicio','=',$h_ini)
                            ->where('a.hora_final','=',$h_fin)
                            ->orderBy("a.fecha_desde","ASC")
                            ->orderBy("a.hora_inicio",'ASC')
                            ->get();
            

            if(count($data) > 0){
                $msg_error = "succes";
                $msg = "";
                $msg_tipo = 3;
                $nombres = $data[0]->nombres. " ".$data[0]->ap_paterno. " ".$data[0]->ap_materno;
                $titulo = $data[0]->nombre_evento. " ".$data[0]->subtitulo."<br><br>HORA: ".$data[0]->hora_inicio . " - ".$data[0]->hora_final;

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta; 

            }else{
                $msg_error = "danger";
                $msg_tipo = 1;
                $msg = "PARTICIPANTE NO REGISTRADO";
                $nombres = "";
                $titulo = "";

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );

                return $respuesta; 
            }
        }

    }

    public function asistencia_act(Request $request){
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["asistencia"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        if($request->eventos_id != ""){
            session(['eventos_id'=> $request->eventos_id]);
        }

        return view('asistencia.asistencia_act');
    }

    public function store_act( Request $request){
        if($request->ajax()){

            $this->validate($request,[
                'asistencia_dni'=>'required',
                //'asistencia_dni'=>'required|unique:estudiantes,dni_doc',
            ]);

            $dni = trim($request->input('asistencia_dni'));
            #dd($dni,$request->session()->get('actividad_id'));
                DB::table('asistencia_eventos')->insert([
                        'estudiantes_id'    =>  $dni,
                        'fecha'         =>  date('d/m/Y'),
                        'hora'          =>  date('H:i'),
                        'usuario_id'    =>  Auth::user()->id,
                        'actividad_id'  =>  session('actividad_id'),
                        //'actividad_id'  =>  $request->session()->get('actividad_id'),
                        'evento_id'     =>  session('eventos_id'),
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now()
                ]);

                $cant = Asistencia_evento::
                                        where('actividad_id',$request->session()->get('actividad_id'))
                                        ->where('evento_id',$request->session()->get('eventos_id'))
                                        ->where('fecha',date('d/m/Y'))
                                        ->select('id')
                                        ->count();

                #return back()->with([ 'info' => 'Asistencia Registrada','cant'=>$cant]); 
                $msg_error = "succes";
                $msg_tipo = 1;
                $msg = "ASISTENCIA REGISTRADA - INSCRITOS: $cant";
                $nombres = "";
                $titulo = "";

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'error' => $msg_error,
                    'nombres'=>$nombres,
                    'titulo' => $titulo
                );
                            
                return $respuesta; 
            
        }
        
    }

    public function store(Request $request)
    {
    
        if($request->ajax()){

            $this->validate($request,[
                'asistencia_dni'=>'required',
                //'asistencia_dni'=>'required|unique:estudiantes,dni_doc',
            ]);

            $dni = trim($request->input('asistencia_dni'));

            DB::table('asistencia_eventos')->insert([
                    'estudiantes_id'    =>  $dni,
                    'fecha'         =>  date('d/m/Y'),
                    'hora'          =>  date('H:i'),
                    'usuario_id'    =>  Auth::user()->id,
                    'evento_id'     =>  session('eventos_id'),
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
            ]);

            $cant = Asistencia_evento::
                                    where('evento_id',$request->session()->get('eventos_id'))
                                    ->whereNull('actividad_id')
                                    ->where('fecha',date('d/m/Y'))
                                    ->select('id')
                                    ->count();

            #return back()->with([ 'info' => 'Asistencia Registrada','cant'=>$cant]);

            $msg_error = "succes";
            $msg_tipo = 1;
            $msg = "ASISTENCIA REGISTRADA - INSCRITOS: $cant";
            $nombres = "";
            $titulo = "";

            $respuesta = array(
                'msg'   => $msg,
                'tipo'  => $msg_tipo,
                'error' => $msg_error,
                'nombres'=>$nombres,
                'titulo' => $titulo
            );
                        
            return $respuesta; 
        
        }

    }

    public function store_conDias(Request $request)
    {
        $this->validate($request,[
            'asistencia'=>'required',
        ]);

        /* 
        v2.
        select e.nombres,e.ap_paterno,p.nombre_curso,p.docente,p.aula,p.piso,p.frecuencia,p.nsesiones,p.fecha_desde,p.fecha_hasta,p.nombre from (estudiantes as e inner join estudiantes_prog_det as ed 
        on e.dni_doc = ed.estudiantes_id) inner join programaciones as p on ed.programacion_id=p.codigo
        where dni_doc = '09908628' and p.frecuencia like '%Ma%'
        and '10/07/2018' between p.fecha_desde and p.fecha_hasta
        order by 1 asc
        */

        // ESTRUCTURA PARA ASISTENCIA CON FECHA
        $dni = $request->input('asistencia');
        $date = Carbon::now();
        $hoy = $date->format('Y-m-d h:m:s');
        //$hoy = $date;
        $hoy = "2019-12-02 11:10:32";
        //dd($hoy);

        $dia = $date->format('N');//obtiene el día en Numero


        switch($dia){
            case(1):
                $dia = 'Lu';
                break;
            case(2):
                $dia = 'Ma';
                break;
            case(3):
                $dia = 'Mi';
                break;
            case(4):
                $dia = 'Ju';
                break;
            case(5):
                $dia = 'Vi';
                break;
            case(6):
                $dia = 'Sa';
                break;
            default:
                $dia = 'Do';
                break;
        }
        

        //$a_datos = DB::select('select * from estudiantes where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis.'%','id2' => $dis]);

        //$verAsistencia = DB::select("select e.nombres,e.ap_paterno,p.nombre_curso,p.docente,p.aula,p.piso,p.frecuencia,p.nsesiones,p.fecha_desde,p.fecha_hasta,p.nombre from (estudiantes as e inner join estudiantes_prog_det as ed on e.dni_doc = ed.estudiantes_id) inner join programaciones as p on ed.programacion_id=p.codigo where dni_doc = :dni and :hoy between p.fecha_desde and p.fecha_hasta and p.frecuencia like :dia order by 1 asc", ['dni' => $dni,'hoy' => $hoy, 'dia' => '%'.$dia.'%']);

        //SQL ASISTENCIA CON FECHA DE PROG DESDE HASTA
        $verAsistencia = DB::select("select e.nombres,e.ap_paterno,e.ap_materno, e.dni_doc, ev.nombre_evento, e.email, e.celular from (estudiantes as e inner join estudiantes_act_detalle as de on e.dni_doc = de.estudiantes_id) inner join eventos as ev on de.eventos_id=ev.id where de.daccedio='SI' and de.confirmado=1 and e.dni_doc = :dni and (:hoy between ev.fechai_evento and ev.fechaf_evento) order by 1 asc", ['dni' => $dni, 'hoy' => $hoy]);

        /*select e.nombres,e.ap_paterno, e.dni_doc, ev.nombre_evento, e.email_labor, e.celular from (estudiantes as e inner join estudiantes_act_detalle as ed on e.dni_doc = ed.estudiantes_id) inner join eventos as ev on ed.eventos_id=ev.id where e.dni_doc = '72431426' AND  ('2019-12-02 11:10:32' between  ev.fechai_evento AND ev.fechaf_evento  )*/

        //SQL ASISTENCIA SOLO VALIDA SI DNI ESTA EN PROG 2
        //$verAsistencia = DB::select("select e.nombres,e.ap_paterno, e.dni_doc, ev.nombre_evento, e.email_labor, e.celular from (estudiantes as e inner join estudiantes_act_detalle as ed on e.dni_doc = ed.estudiantes_id) inner join eventos as ev on ed.eventos_id=ev.id where e.dni_doc = :dni order by 1 asc limit 1", ['dni' => $dni]);

        //return $verAsistencia;
    
        if(!($verAsistencia)){
            alert()->success('El PARTICIPANTE NO ESTA REGISTRADO','Mensaje de Advertencia');

            return redirect()->back();

        }else{
            $asistencia = new Asistencia_evento();
            $asistencia->estudiantes_id = $request->input('asistencia');
            $asistencia->fecha = date('d/m/Y');
            $asistencia->hora = date('H:i:s');
            $asistencia->usuario_id = Auth::user()->id; // usuario que registra ////$user = auth()->user();
            $asistencia->created_at = Carbon::now();
            $asistencia->updated_at = Carbon::now();
            $asistencia->save();

            Cache::flush();
            return back()->with([ 'info' => 'ASISTENCIA REGISTRADA', 'verAsistencia' => $verAsistencia]);
        }

    }


    public function edit($id)
    {

    }

    


    public function eliminarVarios(Request $request)
    {   

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["asistencia"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "asistencia";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if(!is_null($request->tipo)){

            $tipo = $request->tipo;
            if($tipo == 0){

                $eventos_datos = DB::select("select a.id,a.estudiantes_id,e.ap_paterno,e.ap_materno,e.nombres, a.fecha,a.hora,u.name,a.foro as foro from ((asistencia_eventos as a inner join estudiantes as e on e.dni_doc = a.estudiantes_id) inner join users as u on a.usuario_id = u.id) order by a.id desc");
            }else{

                $eventos_datos = DB::select("select a.id,a.estudiantes_id,e.ap_paterno,e.ap_materno,e.nombres, a.fecha,a.hora,u.name,a.foro as foro from (asistencia_eventos as a inner join estudiantes as e on e.dni_doc = a.estudiantes_id) inner join users as u on a.usuario_id = u.id where a.foro=:tipo", ['tipo' => $tipo]);
            }

            return view('asistencia', compact('eventos_datos', 'permisos', 'tipo'));

        }else{

            $tipo_doc = $request->tipo_doc;
            foreach ($tipo_doc as $value) {
                Asistencia_evento::where('id',$value)->delete();
                //DB::table('tipo_documento')->where('id',$value)->delete();
            }

            Cache::flush();
            alert()->error('Eliminado','Registros borrados.');
            return redirect()->route('asistencia.index');
        }

    }



    public function inscritos_foros(){

        $total = DB::table('asistencia_eventos')->where('evento_id', session('eventos_id'))->count();

        $inscritosf_datos = DB::select("select f.id,f.titulo, concat(e.nombres, ' ', e.ap_paterno,' ',e.ap_materno) as nombres, e.dni_doc, e.telefono, e.celular, e.email, e.cargo, e.organizacion, (select count(*) from actividades_estudiantes where actividad_id=f.id) as cantidad, e.grupo,e.pais,e.region from estudiantes as e, actividades_estudiantes as pf, actividades as f where (f.id=pf.actividad_id) and pf.estudiantes_id=e.dni_doc order by f.id asc");
    
        return view('reporte.inscritos_foros', compact('inscritosf_datos', 'total'));    
    }

    // Nueva forma de exportar
    // DESCARGAR REPORTE GEN PREINSCRITOS
    public function exp_asistencia(EstudianteRepository $export, Request $req)
    {
        ## SUPUESTAMENTE ESTE GRAFICO TIENE 12 PARTICIPANTES, PERO COMO TE MOSTRE, HAY DOS PARTICIPANTES QUE TIENEN DOBLE REGISTRO EN DOS HORAS DISTINTAS
        $id = ($req->id>0)?$req->id : 0;
        if($id=="0"){$nom_file = "ASISTENCIA-general-".Carbon::now()->format('d-m-Y');$st=1;}
        elseif($id=="2"){$nom_file = "ASISTENCIA-".Carbon::now()->format('d-m-Y');$st=1;}
        else{$nom_file="Part-Actividades";$st=$id;}
        
        if($id==2)$id="0.2";
        if($id==1)$id="2";
        #dd($id);#st=1;id=1;
        
        $data = array(
            "sorted"      => request('sorted', 'DESC'),
            "eventos_id"  => session('eventos_id'),
            "tipo"        => "$id",
            "all"         => "1",
            "st"          => "$st",
            //"pag"       => 3000
        );

        return Excel::download(new EstudianteExport($data, $export), "$nom_file.xlsx");
    }

    #public function exp_asistencia_old(Request $request){

    // mediante popup se anulo
    public function AsistenciaExport(Request $request){

        $a_sql = "";
        
        $tipo = $request->tipo;

        switch ($tipo) {
            case '0':
                
                $a_sql = Asistencia_evento::join('estudiantes','estudiantes.dni_doc','=','asistencia_eventos.estudiantes_id')
                        ->select('estudiantes.dni_doc','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.nombres', 'asistencia_eventos.fecha','asistencia_eventos.hora', 'estudiantes.email', 'estudiantes.organizacion','asistencia_eventos.actividad_id','estudiantes.pais','estudiantes.region','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','estudiantes.grupo')
                        ->where('evento_id',session('eventos_id'))
                        ->whereNull('actividad_id')
                        ->orderBy('estudiantes.ap_paterno', 'estudiantes.ap_materno','asc')->get();
                
                break;

            case '1':
                $a_sql = Asistencia_evento::join('estudiantes','estudiantes.dni_doc','=','asistencia_eventos.estudiantes_id')
                        ->select('estudiantes.dni_doc','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.nombres', 'asistencia_eventos.fecha','asistencia_eventos.hora', 'estudiantes.email', 'estudiantes.organizacion','asistencia_eventos.actividad_id','estudiantes.pais','estudiantes.region','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','estudiantes.grupo')
                        ->where('evento_id',session('eventos_id'))
                        ->whereNotNull('actividad_id')
                        ->orderBy('estudiantes.ap_paterno', 'estudiantes.ap_materno','asc')->get();

                break;

            default:

                /* select a.estudiantes_id, e.ap_paterno, e.ap_materno , e.nombres, e.email, act.titulo, e.organizacion from asistencia_eventos as a inner join estudiantes AS e on a.estudiantes_id=e.dni_doc inner join actividades AS act ON act.id=a.actividad_id group by a.estudiantes_id, e.ap_paterno,act.titulo having COUNT(*) >= 4 order by a.estudiantes_id*/

                $a_sql = Asistencia_evento::join('estudiantes','estudiantes.dni_doc','=','asistencia_eventos.estudiantes_id')
                        ->join('actividades as act', 'asistencia_eventos.actividad_id','=','act.id')
                        ->select('estudiantes.dni_doc','estudiantes.ap_paterno',DB::raw('count(*) as cant'),'estudiantes.ap_materno','estudiantes.nombres', 'asistencia_eventos.fecha','asistencia_eventos.hora', 'estudiantes.email', 'estudiantes.organizacion','asistencia_eventos.actividad_id','estudiantes.pais','estudiantes.region','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','estudiantes.grupo','act.titulo','act.subtitulo')
                        ->where('evento_id',session('eventos_id'))
                        ->groupBy('estudiantes.dni_doc', 'estudiantes.ap_paterno', 'act.titulo')
                        ->havingRaw("COUNT(*) >= 4")
                        ->orderBy('estudiantes.ap_paterno', 'estudiantes.ap_materno','asc')
                        ->get();//->toArray()
                        //dd($a_sql);
                break;
        }

        if(is_null($a_sql)){
            dd("Ocurrio un Error");
        }

        Excel::create('ASISTENCIA-'.Carbon::now()->format('d-m-Y'), function($excel) use ($a_sql) {
 
            $asistencia = $a_sql;
            
            //sheet -> nomb de hoja
            $excel->sheet('ASISTENCIA', function($sheet) use($asistencia) {
                //$sheet->fromArray($asistencia); // muestra todos los campos

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  12
                        //'bold'      =>  true
                    )
                ));
                //$sheet->setAllBorders('thin');
                //$sheet->setBorder('A1:F10', 'thin'); //solo border de A1 A F10
                //$sheet->setFontBold(true);

                $sheet->row(1, [
                    'DNI','AP. PATERNO', 'AP. MATERNO', 'NOMBRES',  'FECHA', 'HORA', 'EMAIL', 'ORGANIZACION', 'ACTIVIDAD', 'PAÍS', 'DEPARTAMENTO', 'CARGO', 'PROFESIÓN', 'CELULAR', 'GRUPO'
                ]);

                foreach($asistencia as $index => $data) {

                    $sheet->row($index+2, [
                        $data->dni_doc,$data->ap_paterno, $data->ap_materno, $data->nombres, $data->fecha, $data->hora, $data->email, $data->organizacion, $data->actividad_id, $data->pais, $data->region, $data->cargo, $data->profesion, $data->celular, $data->grupo, $data->region
                    ]); 
                }

                //$sheet->loadView('reportes.gafete');

            });
        })->export('xlsx');
    }

    

}
