<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cache;
use File;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use App\Evento, App\Emails;
use App\AccionesRolesPermisos;
use App\estudiantes_act_detalle;

use Alert;
use Auth;

class ModuloCaiiController extends Controller
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
        // agregar: permisos caii
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(Cache::has('permisos.all')){
            $permisos = Cache::get('permisos.all');

        }else{

            $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
            $permParam["modulo_alias"] = "estudiantes";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

            Cache::put('permisos.all', $permisos, 5);

        }

        if($request->get('s')){
                Cache::flush();

                $search = $request->get('s');

                $eventos_datos = Evento::where("nombre_evento", "LIKE", '%'.$search.'%')
                ->orWhere("fecha_texto", "LIKE", '%'.$search.'%')
                ->orWhere("hora", "LIKE", '%'.$search.'%')
                ->orWhere("lugar", "LIKE", '%'.$search.'%')
                
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            }else{

                $key = 'eventos.page.'.request('page', 1);
                $eventos_datos = Cache::rememberForever($key, function(){
                    return Evento::where('eventos_tipo_id',1)->orderBy('id', request('sorted', 'DESC'))
                    ->paginate(15);

                });
            }
        

        return view('caii.index', compact('eventos_datos', 'permisos')); 

    }

    public function opciones(){    }

    public function create()
    {
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $plantilla_datos = DB::table('e_gafete_modelos')->get();
        $emails = Emails::orderBy("nombre",'asc')->get();
        
        return view('caii.create', compact('plantilla_datos','emails'));
    }

    public function create_json()
    {
        
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $plantilla_datos = DB::table('e_gafete_modelos')->get();
        $emails = Emails::orderBy("nombre",'asc')->get();
        
        return view('caii.create_json', compact('plantilla_datos','emails'));
    }

    public function store_json(Request $request){
        #return $request->all();

        $recs = $request->get('datar');
        #$recs = json_decode($request->get('datar')??"[]", true);
        //$ins = json_decode($request->get('datai')??"[]", true);
        
        $nombre = $request->get('nombre_evento');

       

        /* DB::table('eventos_msg')->insert(
            ['nombre'=>$nombre,
            'opt'   =>$recs,
            ]
        ); */
       

        dd('store',$nombre, $recs);

        /* DB::table('eventos')->insert([
            'nombre_evento'=>$request->input('nombre_evento'), */

    }


    public function store(Request $request)
    {
        $flag_error = 0;

        $auto_conf_pre = $request->auto_conf_pre;
        $email_asunto_pre = $request->email_asunto_pre;
        $email_id_pre = $request->email_id_pre;
        $confirm_email_pre = $request->confirm_email_pre;
        $confirm_msg_pre = $request->confirm_msg_pre;

        

        #return "auto_conf_pre: $auto_conf_pre, email_asunto_pre: $email_asunto_pre, email_id_pre: $email_id_pre, confirm_email_pre: $confirm_email_pre,confirm_msg_pre: $confirm_msg_pre";

        $fechai_evento = $request->input('fechai_evento');
        $fechaf_evento = $request->input('fechaf_evento');
        $fechaf_pre_evento = $request->input('fechaf_pre_evento');
        //$fechaf_insc_evento = $request->input('fechaf_insc_evento');
        
        if($this->validar_fecha_espanol($fechai_evento)){ 
            $valores = explode('/', $fechai_evento);
            $fechai_evento = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;
        }else{
            $flag_error = 1;
        }

        // error fechas
        if($flag_error == 1) {
            alert()->warning('Error','Error en los campos de las fechas.');
            return redirect()->back();
        }

        DB::table('eventos')->insert([
             'nombre_evento'=>$request->input('nombre_evento'),
             'descripcion'=>$request->input('descripcion'),
             'fecha_texto'=>($request->input('fecha_texto')),
             'hora'=>($request->input('hora')),
             'lugar'=>mb_strtoupper($request->input('lugar')),
             'vacantes'=>($request->input('vacantes')),
             'inscritos_pre'=>0,
             'inscritos_invi'=>0,
             'plantilla'=>mb_strtoupper($request->input('plantilla')),
             'auto_conf'=>($request->input('auto_conf')),
             'email_id'      =>$request->input('email_id'),
             'email_asunto'  =>$request->input('email_asunto'),
             'color'=>mb_strtoupper($request->input('color')),
             'activo'=>($request->input('activo')),
             'grupo'=>mb_strtoupper($request->input('grupo')),
             'departamento'=>mb_strtoupper($request->input('departamento')),
             'fechai_evento'=>$fechai_evento,
             'fechaf_evento'=>$fechaf_evento,
             'fechaf_pre_evento'=>$fechaf_pre_evento,
             //'fechaf_insc_evento'=>$fechaf_insc_evento,
             'gafete'=>$request->input('gafete'),
             'gafete_html'=>$request->input('gafete_html'),
             'confirm_email'=>($request->input('confirm_email')),
             'confirm_msg'=>($request->input('confirm_msg')),
             'eventos_tipo_id'=> 1,
             
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now()
        ]);

        $eventos_id = DB::getPdo()->lastInsertId();
        Cache::flush();
        alert()->success('Registro guardado con éxito', 'Mensaje');
        return redirect()->route('caii_plantilla.create',compact('eventos_id'));

    }

     public function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }

    public function createPlantilla(Request $request){
        if(isset($request->eventos_id)){
            $eventos_id = $request->eventos_id;
        }else{
            alert()->success('El código del evento no existe', 'Advertencia');
            return redirect()->route('caii.index');
        }
       
        return view('caii.plantillas',['eventos_id'=>$eventos_id]);
    }

    public function storePlantilla(Request $request){

        try {
            
            $p_preregistro = "";
            $p_preregistro_v = "";
            $p_conf_inscripcion = "";
            $p_conf_inscripcion_v = "";
            $p_conf_registro = "";
            $p_conf_registro_v = "";
            $p_conf_registro_gracias = "";
            $p_conf_registro_gracias_v = "";
            $p_recordatorio = "";
            $p_recordatorio_v = "";
            $p_negacion = "";
            $p_negacion_v = "";
            $p_baja_evento = "";
            $p_baja_evento_v = "";
            $p_preinscripcion_cerrado = "";
            $p_baja_evento = "";
            $p_baja_evento_v = "";
            $p_inscripcion_cerrado = "";

            $p_preregistro_email  = ($request->p_preregistro_email)?$request->p_preregistro_email:0;
            $p_preregistro_msg  = ($request->p_preregistro_msg)?$request->p_preregistro_msg:0;
            $p_preregistro_asunto  = ($request->p_preregistro_asunto)?$request->p_preregistro_asunto:'';
            $p_preregistro_asunto_v  = ($request->p_preregistro_asunto_v)?$request->p_preregistro_asunto_v:'';
            $p_conf_preregistro  = ($request->p_conf_preregistro)?$request->p_conf_preregistro:'';
            $p_conf_preregistro_v  = ($request->p_conf_preregistro_v)?$request->p_conf_preregistro_v:'';

            $p_conf_inscripcion_email  = ($request->p_conf_inscripcion_email)?$request->p_conf_inscripcion_email:0;
            $p_conf_inscripcion_msg  = ($request->p_conf_inscripcion_msg)?$request->p_conf_inscripcion_msg:0;
            $p_conf_inscripcion_asunto  = ($request->p_conf_inscripcion_asunto)?$request->p_conf_inscripcion_asunto:'';
            $p_conf_inscripcion_asunto_v  = ($request->p_conf_inscripcion_asunto_v)?$request->p_conf_inscripcion_asunto_v:'';
            
            $p_conf_registro_email  = ($request->p_conf_registro_email)?$request->p_conf_registro_email:0;
            $p_conf_registro_msg  = ($request->p_conf_registro_msg)?$request->p_conf_registro_msg:0;
            $p_conf_registro_asunto  = ($request->p_conf_registro_asunto)?$request->p_conf_registro_asunto:'';
            $p_conf_registro_asunto_v  = ($request->p_conf_registro_asunto_v)?$request->p_conf_registro_asunto_v:'';

            $p_recordatorio_email  = ($request->p_recordatorio_email)?$request->p_recordatorio_email:0;
            $p_recordatorio_msg  = ($request->p_recordatorio_msg)?$request->p_recordatorio_msg:0;
            $p_recordatorio_asunto  = ($request->p_recordatorio_asunto)?$request->p_recordatorio_asunto:'';
            $p_recordatorio_asunto_v  = ($request->p_recordatorio_asunto_v)?$request->p_recordatorio_asunto_v:'';

            $p_negacion_email  = ($request->p_negacion_email)?$request->p_negacion_email:0;
            $p_negacion_msg  = ($request->p_negacion_msg)?$request->p_negacion_msg:0;
            $p_negacion_asunto  = ($request->p_negacion_asunto)?$request->p_negacion_asunto:'';
            $p_negacion_asunto_v  = ($request->p_negacion_asunto_v)?$request->p_negacion_asunto_v:'';

            $p_baja_evento_email  = ($request->p_baja_evento_email)?$request->p_baja_evento_email:0;
            $p_baja_evento_msg  = ($request->p_baja_evento_msg)?$request->p_baja_evento_msg:0;
            $p_baja_evento_asunto  = ($request->p_baja_evento_asunto)?$request->p_baja_evento_asunto:'';
            $p_baja_evento_asunto_v  = ($request->p_baja_evento_asunto_v)?$request->p_baja_evento_asunto_v:'';


            if($request->input('eventos_id') == ""){
                alert()->success('El código del evento no existe', 'Advertencia');
                return redirect()->route('caii.index');
            }
            $eventos_id = $request->input('eventos_id');

            $cant = DB::table('e_plantillas')->where('eventos_id',$eventos_id)->count();

            if($cant >= 1){
                DB::table('e_plantillas')->where('eventos_id', $eventos_id)->delete();
            }

            $id_lista = $eventos_id;

            if($request->input('p_preregistro') != ""){
                // campo 1:
                
                $p_preregistro = $request->input('p_preregistro');

                $file=fopen('files/html/'.$id_lista.'p_preregistro'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_preregistro);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_preregistro.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_preregistro);
                fclose($file_2);

                $p_preregistro = $id_lista.'p_preregistro';
                //$p_preregistro = $id_lista.'p_preregistro'.'.html';

            }
            if($request->input('p_preregistro_v') != ""){
                // campo 1:
                
                $p_preregistro_v = $request->input('p_preregistro_v');

                $file=fopen('files/html/'.$id_lista.'p_preregistro_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_preregistro_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_preregistro_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_preregistro_v);
                fclose($file_2);

                $p_preregistro_v = $id_lista.'p_preregistro_v';
                //$p_preregistro_v = $id_lista.'p_preregistro_v'.'.html';

            }

            if($request->input('p_conf_inscripcion') != ""){
                // campo 2:
                $p_conf_inscripcion = $request->input('p_conf_inscripcion');

                $file=fopen('files/html/'.$id_lista.'p_conf_inscripcion'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_inscripcion);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_inscripcion.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_inscripcion);
                fclose($file_2);

                $p_conf_inscripcion = $id_lista.'p_conf_inscripcion';


            }
            if($request->input('p_conf_inscripcion_v') != ""){
                // campo 2:
                $p_conf_inscripcion_v = $request->input('p_conf_inscripcion_v');

                $file=fopen('files/html/'.$id_lista.'p_conf_inscripcion_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_inscripcion_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_inscripcion_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_inscripcion_v);
                fclose($file_2);

                $p_conf_inscripcion_v = $id_lista.'p_conf_inscripcion_v';
            }

            if($request->input('p_conf_preregistro') != ""){
                // campo 1:
                
                $p_conf_preregistro = $request->input('p_conf_preregistro');

                $file=fopen('files/html/'.$id_lista.'p_conf_preregistro'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_preregistro);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_preregistro.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_preregistro);
                fclose($file_2);

                $p_conf_preregistro = $id_lista.'p_conf_preregistro';
                //$p_preregistro = $id_lista.'p_preregistro'.'.html';

            }

            if($request->input('p_conf_preregistro_v') != ""){
                // campo 1:
                
                $p_conf_preregistro_v = $request->input('p_conf_preregistro_v');

                $file=fopen('files/html/'.$id_lista.'p_conf_preregistro_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_preregistro_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_preregistro_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_preregistro_v);
                fclose($file_2);

                $p_conf_preregistro_v = $id_lista.'p_conf_preregistro_v';

            }

            if($request->input('p_conf_registro') != ""){
                // campo 3:
                $p_conf_registro = $request->input('p_conf_registro');

                $file=fopen('files/html/'.$id_lista.'p_conf_registro'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_registro);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_registro);
                fclose($file_2);
            
                $p_conf_registro = $id_lista.'p_conf_registro';
            }
            if($request->input('p_conf_registro_v') != ""){
                // campo 3:
                $p_conf_registro_v = $request->input('p_conf_registro_v');

                $file=fopen('files/html/'.$id_lista.'p_conf_registro_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_registro_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_registro_v);
                fclose($file_2);
            
                $p_conf_registro_v = $id_lista.'p_conf_registro_v';
            }


            if($request->input('p_conf_registro_gracias') != ""){
                // campo 3:
                $p_conf_registro_gracias = $request->input('p_conf_registro_gracias');

                $file=fopen('files/html/'.$id_lista.'p_conf_registro_gracias'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_registro_gracias);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro_gracias.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_registro_gracias);
                fclose($file_2);

                $p_conf_registro_gracias = $id_lista.'p_conf_registro_gracias';

            }
            if($request->input('p_conf_registro_gracias_v') != ""){
                // campo 3:
                $p_conf_registro_gracias_v = $request->input('p_conf_registro_gracias_v');

                $file=fopen('files/html/'.$id_lista.'p_conf_registro_gracias_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_registro_gracias_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro_gracias_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_registro_gracias_v);
                fclose($file_2);

                $p_conf_registro_gracias_v = $id_lista.'p_conf_registro_gracias_v';

            }

            if($request->input('p_recordatorio') != ""){
                // campo 3:
                $p_recordatorio = $request->input('p_recordatorio');
                $file=fopen('files/html/'.$id_lista.'p_recordatorio'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_recordatorio);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_recordatorio.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_recordatorio);
                fclose($file_2);

                $p_recordatorio = $id_lista.'p_recordatorio';

            }
            if($request->input('p_recordatorio_v') != ""){
                // campo 3:
                $p_recordatorio_v = $request->input('p_recordatorio_v');
                $file=fopen('files/html/'.$id_lista.'p_recordatorio_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_recordatorio_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_recordatorio_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_recordatorio_v);
                fclose($file_2);

                $p_recordatorio_v = $id_lista.'p_recordatorio_v';
            }

            if($request->input('p_negacion') != ""){
                // campo 3:
                $p_negacion = $request->input('p_negacion');

                $file=fopen('files/html/'.$id_lista.'p_negacion'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_negacion);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_negacion);
                fclose($file_2);

                $p_negacion = $id_lista.'p_negacion';
            }

            if($request->input('p_negacion_v') != ""){
                // campo 3:
                $p_negacion_v = $request->input('p_negacion_v');

                $file=fopen('files/html/'.$id_lista.'p_negacion_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_negacion_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion_v.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_negacion_v);
                fclose($file_2);

                $p_negacion_v = $id_lista.'p_negacion_v';

            }

            /* if($request->input('p_negacion_v2') != ""){
                // campo 3:
                $p_negacion_v2 = $request->input('p_negacion_v2');

                $file=fopen('files/html/'.$id_lista.'p_negacion_v2'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_negacion_v2);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion_v2.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_negacion_v2);
                fclose($file_2);

                $p_negacion_v2 = $id_lista.'p_negacion_v2';
            } */

            if($request->input('p_baja_evento') != ""){
                // campo 3:
                $p_baja_evento = $request->input('p_baja_evento');

                $file=fopen('files/html/'.$id_lista.'p_baja_evento'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_baja_evento);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_negacion);
                fclose($file_2);

                $p_baja_evento = $id_lista.'p_baja_evento';

            }
            if($request->input('p_baja_evento_v') != ""){
                // campo 3:
                $p_baja_evento_v = $request->input('p_baja_evento_v');

                $file=fopen('files/html/'.$id_lista.'p_baja_evento_v'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_baja_evento_v);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_negacion);
                fclose($file_2);

                $p_baja_evento_v = $id_lista.'p_baja_evento_v';

            }


            if($request->input('p_preinscripcion_cerrado') != ""){
                // campo 3:
                $p_preinscripcion_cerrado = $request->input('p_preinscripcion_cerrado');

                $file=fopen('files/html/'.$id_lista.'p_preinscripcion_cerrado'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_preinscripcion_cerrado);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_preinscripcion_cerrado.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_preinscripcion_cerrado);
                fclose($file_2);

                $p_preinscripcion_cerrado = $id_lista.'p_preinscripcion_cerrado';

            }

            if($request->input('p_inscripcion_cerrado') != ""){
                // campo 3:
                $p_inscripcion_cerrado = $request->input('p_inscripcion_cerrado');

                $file=fopen('files/html/'.$id_lista.'p_inscripcion_cerrado'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_inscripcion_cerrado);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_inscripcion_cerrado.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_inscripcion_cerrado);
                fclose($file_2);

                $p_inscripcion_cerrado = $id_lista.'p_inscripcion_cerrado';

            }

                DB::table('e_plantillas')->insert([
                    'eventos_id'=>$request->input('eventos_id'),
                    'p_preregistro'=>$p_preregistro,
                    'p_preregistro_2'=>$request->input('p_preregistro_2'),
                    'p_preregistro_email' =>$p_preregistro_email,
                    'p_preregistro_msg'   =>$p_preregistro_msg,
                    'p_preregistro_asunto'=>$p_preregistro_asunto,

                    'p_conf_inscripcion'=>$p_conf_inscripcion,
                    'p_conf_inscripcion_2'=>$request->input('p_conf_inscripcion_2'),
                    'p_conf_inscripcion_email'=>$p_conf_inscripcion_email,
                    'p_conf_inscripcion_msg'=>$p_conf_inscripcion_msg,
                    'p_conf_inscripcion_asunto'=>$p_conf_inscripcion_asunto,

                    'p_conf_preregistro'=>$p_conf_preregistro,

                    'p_conf_registro'=>$p_conf_registro,
                    'p_conf_registro_2'=>$request->input('p_conf_registro_2'),
                    'p_conf_registro_email'=>$p_conf_registro_email,
                    'p_conf_registro_msg'=>$p_conf_registro_msg,
                    'p_conf_registro_asunto'=>$p_conf_registro_asunto,

                    'p_conf_registro_gracias'=>$p_conf_registro_gracias,
                    'p_recordatorio'=>$p_recordatorio,
                    'p_recordatorio_2'=>$request->input('p_recordatorio_2'),
                    'p_recordatorio_email'=>$request->input('p_recordatorio_email'),
                    'p_recordatorio_msg'=>$request->input('p_recordatorio_msg'),
                    'p_recordatorio_asunto'=>$p_recordatorio_asunto,
                    
                    'p_negacion'=>$p_negacion,
                    'p_negacion_2'=>$request->input('p_negacion_2'),
                    'p_negacion_email'=>$p_negacion_email,
                    'p_negacion_msg'=>$p_negacion_msg,
                    'p_negacion_asunto'=>$p_negacion_asunto,

                    'p_baja_evento'=>$p_baja_evento,
                    'p_baja_evento_2'=>$request->input('p_baja_evento_2'),
                    'p_baja_evento_email'=>$p_baja_evento_email,
                    'p_baja_evento_msg'=>$p_baja_evento_msg,
                    'p_baja_evento_asunto'=>$p_baja_evento_asunto,

                    'p_preinscripcion_cerrado'=>$p_preinscripcion_cerrado,
                    'p_preinscripcion_cerrado_2'=>$request->input('p_preinscripcion_cerrado_2'),
                    'p_inscripcion_cerrado'=>$p_inscripcion_cerrado,
                    'p_inscripcion_cerrado_2'=>$request->input('p_inscripcion_cerrado_2'),
                ]);

                DB::table('e_plantillas_virtual')->insert([
                    'eventos_id'=>$request->input('eventos_id'),
                    'p_preregistro_asunto_v'=>$p_preregistro_asunto_v,
                    'p_preregistro_v'=>$p_preregistro_v,
                    'p_preregistro_2_v'=>$request->input('p_preregistro_2_v'),
                    'p_conf_preregistro_v'=>$p_conf_preregistro_v,

                    'p_conf_inscripcion_v'=>$p_conf_inscripcion_v,
                    'p_conf_inscripcion_2_v'=>$request->input('p_conf_inscripcion_2_v'),
                    'p_conf_inscripcion_asunto_v'=>$p_conf_inscripcion_asunto_v,

                    'p_conf_registro_v'=>$p_conf_registro_v,
                    'p_conf_registro_2_v'=>$request->input('p_conf_registro_2_v'),
                    'p_conf_registro_asunto_v'=>$p_conf_registro_asunto_v,

                    'p_conf_registro_gracias_v'=>$p_conf_registro_gracias_v,
                    'p_recordatorio_v'=>$p_recordatorio_v,
                    'p_recordatorio_2_v'=>$request->input('p_recordatorio_2_v'),
                    'p_recordatorio_asunto_v'=>$p_recordatorio_asunto_v,
                    
                    'p_negacion_v'=>$p_negacion_v,
                    'p_negacion_2_v'=>$request->input('p_negacion_2_v'),
                    'p_negacion_asunto_v'=>$p_negacion_asunto_v,

                    'p_baja_evento_v'=>$p_baja_evento_v,
                    'p_baja_evento_2_v'=>$request->input('p_baja_evento_2_v'),
                    'p_baja_evento_asunto_v'=>$p_baja_evento_asunto_v,
                ]);

                alert()->success('Registro guardado con éxito', 'Mensaje');

                return redirect()->route('caii_form.create', compact('eventos_id'));

            
            
        } catch (Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
            
        }

    }

    public function editPlantilla($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        $reg = DB::table('e_plantillas_virtual')->where('eventos_id', $id)->count();
        if($reg>=1){
            $datos = DB::table('e_plantillas as p')->join('e_plantillas_virtual as v','v.eventos_id','=','p.eventos_id')->where('p.eventos_id', $id)->first();
        }else{
            $datos = DB::table('e_plantillas')->where('eventos_id', $id)->first();
        }

        if($datos){
            if($id==1) return view('caii.plantillas_edit2', compact('datos'));
            return view('caii.plantillas_edit', compact('datos'));

        }else{
            alert()->success('No tiene plantilla, elimine y vuelva a crear el evento', 'Error')->autoclose(3500);
            return redirect()->back();
            //return redirect()->route('caii.index');
        }

    }

    public function updatePlantilla(Request $request, $id)
    {
        
        /*try {*/

        $p_preregistro_v = '';
        $p_conf_inscripcion_v = '';
        $p_conf_registro_v = '';
        $p_conf_registro_gracias_v = '';
        $p_recordatorio_v = '';
        $p_recordatorio_fin = '';
        $p_negacion_v = '';
        $p_baja_evento_v = '';
        
        $id_lista = $id;
        $p_preregistro_email  = ($request->p_preregistro_email)?$request->p_preregistro_email:0;
        $p_preregistro_msg  = ($request->p_preregistro_msg)?$request->p_preregistro_msg:0;
        $p_preregistro_asunto  = ($request->p_preregistro_asunto)?$request->p_preregistro_asunto:'';
        $p_preregistro_asunto_v  = ($request->p_preregistro_asunto_v)?$request->p_preregistro_asunto_v:'';
        $p_conf_preregistro  = ($request->p_conf_preregistro)?$request->p_conf_preregistro:'';
        $p_conf_preregistro_v  = ($request->p_conf_preregistro_v)?$request->p_conf_preregistro_v:'';

        $p_conf_inscripcion_email  = ($request->p_conf_inscripcion_email)?$request->p_conf_inscripcion_email:0;
        $p_conf_inscripcion_msg  = ($request->p_conf_inscripcion_msg)?$request->p_conf_inscripcion_msg:0;
        $p_conf_inscripcion_asunto  = ($request->p_conf_inscripcion_asunto)?$request->p_conf_inscripcion_asunto:'';
        $p_conf_inscripcion_asunto_v  = ($request->p_conf_inscripcion_asunto_v)?$request->p_conf_inscripcion_asunto_v:'';
        
        $p_conf_registro_email  = ($request->p_conf_registro_email)?$request->p_conf_registro_email:0;
        $p_conf_registro_msg  = ($request->p_conf_registro_msg)?$request->p_conf_registro_msg:0;
        $p_conf_registro_asunto  = ($request->p_conf_registro_asunto)?$request->p_conf_registro_asunto:'';
        $p_conf_registro_asunto_v  = ($request->p_conf_registro_asunto_v)?$request->p_conf_registro_asunto_v:'';

        $p_recordatorio_email  = ($request->p_recordatorio_email)?$request->p_recordatorio_email:0;
        $p_recordatorio_msg  = ($request->p_recordatorio_msg)?$request->p_recordatorio_msg:0;
        $p_recordatorio_msg_f  = ($request->p_recordatorio_msg_f)?$request->p_recordatorio_msg_f:0;
        $p_recordatorio_asunto  = ($request->p_recordatorio_asunto)?$request->p_recordatorio_asunto:'';
        $p_recordatorio_asunto_v  = ($request->p_recordatorio_asunto_v)?$request->p_recordatorio_asunto_v:'';

        $p_negacion_email  = ($request->p_negacion_email)?$request->p_negacion_email:0;
        $p_negacion_msg  = ($request->p_negacion_msg)?$request->p_negacion_msg:0;
        $p_negacion_asunto  = ($request->p_negacion_asunto)?$request->p_negacion_asunto:'';
        $p_negacion_asunto_v  = ($request->p_negacion_asunto_v)?$request->p_negacion_asunto_v:'';

        $p_baja_evento_email  = ($request->p_baja_evento_email)?$request->p_baja_evento_email:0;
        $p_baja_evento_msg  = ($request->p_baja_evento_msg)?$request->p_baja_evento_msg:0;
        $p_baja_evento_asunto  = ($request->p_baja_evento_asunto)?$request->p_baja_evento_asunto:'';
        $p_baja_evento_asunto_v  = ($request->p_baja_evento_asunto_v)?$request->p_baja_evento_asunto_v:'';
        

        if($request->input('p_preregistro') != "" ){
            // campo 1:
            
            $p_preregistro = $request->input('p_preregistro');

            $file=fopen('files/html/'.$id_lista.'p_preregistro'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_preregistro);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_preregistro.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_preregistro);
            fclose($file_2);

            $p_preregistro = $id_lista.'p_preregistro';
            //$p_preregistro = $id_lista.'p_preregistro'.'.html';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_preregistro'       =>$p_preregistro
                ]);
        }
        if($request->input('p_preregistro_v') != "" ){
            // campo 1:
            
            $p_preregistro_v = $request->input('p_preregistro_v');

            $file=fopen('files/html/'.$id_lista.'p_preregistro_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_preregistro_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_preregistro_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_preregistro_v);
            fclose($file_2);

            $p_preregistro_v = $id_lista.'p_preregistro_v';
            //$p_preregistro_v = $id_lista.'p_preregistro_v'.'.html';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_preregistro_v'       =>$p_preregistro_v
                ]);
        }

        if($request->input('p_conf_inscripcion') != ""){
            // campo 2:
            $p_conf_inscripcion = $request->input('p_conf_inscripcion');

            $file=fopen('files/html/'.$id_lista.'p_conf_inscripcion'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_inscripcion);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_inscripcion.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_inscripcion);
            fclose($file_2);
            $p_conf_inscripcion = $id_lista.'p_conf_inscripcion';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_conf_inscripcion'=>$p_conf_inscripcion,
                ]);
        }
        if($request->input('p_conf_inscripcion_v') != ""){
            // campo 2:
            $p_conf_inscripcion_v = $request->input('p_conf_inscripcion_v');

            $file=fopen('files/html/'.$id_lista.'p_conf_inscripcion_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_inscripcion_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_inscripcion_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_inscripcion_v);
            fclose($file_2);
            $p_conf_inscripcion_v = $id_lista.'p_conf_inscripcion_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_conf_inscripcion_v'=>$p_conf_inscripcion_v,
                ]);
        }
        

        if($request->input('p_conf_preregistro') != ""){
            // campo 2.1: Confirmacion email preregistro
            $p_conf_preregistro = $request->input('p_conf_preregistro');

            $file=fopen('files/html/'.$id_lista.'p_conf_preregistro'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_preregistro);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_preregistro.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_preregistro);
            fclose($file_2);
            $p_conf_preregistro = $id_lista.'p_conf_preregistro';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_conf_preregistro'=>$p_conf_preregistro
                ]);
        }
        if($request->input('p_conf_preregistro_v') != ""){
            // campo 2.1: Confirmacion email preregistro
            $p_conf_preregistro_v = $request->input('p_conf_preregistro_v');

            $file=fopen('files/html/'.$id_lista.'p_conf_preregistro_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_preregistro_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_preregistro_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_preregistro_v);
            fclose($file_2);
            $p_conf_preregistro_v = $id_lista.'p_conf_preregistro_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_conf_preregistro_v'=>$p_conf_preregistro_v
                ]);
        }

        if($request->input('p_conf_registro') != ""){
            // campo 3:
            $p_conf_registro = $request->input('p_conf_registro');

            $file=fopen('files/html/'.$id_lista.'p_conf_registro'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_registro);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_registro);
            fclose($file_2);
        
            $p_conf_registro = $id_lista.'p_conf_registro';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_conf_registro'=>$p_conf_registro
                ]);
        }
        if($request->input('p_conf_registro_v') != ""){
            // campo 3:
            $p_conf_registro_v = $request->input('p_conf_registro_v');

            $file=fopen('files/html/'.$id_lista.'p_conf_registro_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_registro_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_registro_v);
            fclose($file_2);
        
            $p_conf_registro_v = $id_lista.'p_conf_registro_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_conf_registro_v'=>$p_conf_registro_v
                ]);
        }
        

        if($request->input('p_conf_registro_gracias') != ""){
            // campo 3:
            $p_conf_registro_gracias = $request->input('p_conf_registro_gracias');

            $file=fopen('files/html/'.$id_lista.'p_conf_registro_gracias'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_registro_gracias);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro_gracias.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_registro_gracias);
            fclose($file_2);

            $p_conf_registro_gracias = $id_lista.'p_conf_registro_gracias';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_conf_registro_gracias'=>$p_conf_registro_gracias
                ]);
        }
        if($request->input('p_conf_registro_gracias_v') != ""){
            // campo 3:
            $p_conf_registro_gracias_v = $request->input('p_conf_registro_gracias_v');

            $file=fopen('files/html/'.$id_lista.'p_conf_registro_gracias_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_conf_registro_gracias_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro_gracias_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_conf_registro_gracias_v);
            fclose($file_2);

            $p_conf_registro_gracias_v = $id_lista.'p_conf_registro_gracias_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_conf_registro_gracias_v'=>$p_conf_registro_gracias_v
                ]);
        }

        if($request->input('p_recordatorio') != ""){
            // campo 3:
            $p_recordatorio = $request->input('p_recordatorio');
            $file=fopen('files/html/'.$id_lista.'p_recordatorio'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_recordatorio);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_recordatorio.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_recordatorio);
            fclose($file_2);

            $p_recordatorio = $id_lista.'p_recordatorio';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_recordatorio'=>$p_recordatorio,
                ]);
        }
        if($request->input('p_recordatorio_v') != ""){
            // campo 3:
            $p_recordatorio_v = $request->input('p_recordatorio_v');
            $file=fopen('files/html/'.$id_lista.'p_recordatorio_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_recordatorio_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_recordatorio_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_recordatorio_v);
            fclose($file_2);

            $p_recordatorio_v = $id_lista.'p_recordatorio_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_recordatorio_v'=>$p_recordatorio_v,
                ]);
        }
        
        if($request->input('p_negacion') != ""){
            // campo 3:
            $p_negacion = $request->input('p_negacion');

            $file=fopen('files/html/'.$id_lista.'p_negacion'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_negacion);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_negacion);
            fclose($file_2);

            $p_negacion = $id_lista.'p_negacion';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_negacion'=>$p_negacion,
                ]);
        }
        if($request->input('p_negacion_v') != ""){
            // campo 3:
            $p_negacion_v = $request->input('p_negacion_v');

            $file=fopen('files/html/'.$id_lista.'p_negacion_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_negacion_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_negacion_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_negacion_v);
            fclose($file_2);

            $p_negacion_v = $id_lista.'p_negacion_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_negacion_v'=>$p_negacion_v,
                ]);
        }

        if($request->input('p_baja_evento') != ""){
            // campo 3:
            $p_baja_evento = $request->input('p_baja_evento');

            $file=fopen('files/html/'.$id_lista.'p_baja_evento'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_baja_evento);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_baja_evento.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_baja_evento);
            fclose($file_2);

            $p_baja_evento = $id_lista.'p_baja_evento';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_baja_evento'=>$p_baja_evento,
                ]);
        }
        if($request->input('p_baja_evento_v') != ""){
            // campo 3:
            $p_baja_evento_v = $request->input('p_baja_evento_v');

            $file=fopen('files/html/'.$id_lista.'p_baja_evento_v'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_baja_evento_v);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_baja_evento_v.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_baja_evento_v);
            fclose($file_2);

            $p_baja_evento_v = $id_lista.'p_baja_evento_v';

            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                    'p_baja_evento_v'=>$p_baja_evento_v,
                ]);
        }
        
        if($request->input('p_preinscripcion_cerrado') != ""){
            // campo 3:
            $p_preinscripcion_cerrado = $request->input('p_preinscripcion_cerrado');

            $file=fopen('files/html/'.$id_lista.'p_preinscripcion_cerrado'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_preinscripcion_cerrado);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_preinscripcion_cerrado.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_preinscripcion_cerrado);
            fclose($file_2);

            $p_preinscripcion_cerrado = $id_lista.'p_preinscripcion_cerrado';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_preinscripcion_cerrado'=>$p_preinscripcion_cerrado
                ]);
        }

        if($request->input('p_inscripcion_cerrado') != ""){
            // campo 3:
            $p_inscripcion_cerrado = $request->input('p_inscripcion_cerrado');

            $file=fopen('files/html/'.$id_lista.'p_inscripcion_cerrado'.'.html','w') or die ("error creando fichero!");
            fwrite($file,$p_inscripcion_cerrado);
            fclose($file);

            $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_inscripcion_cerrado.blade.php','w') or die ("error creando fichero!");
            fwrite($file_2,$p_inscripcion_cerrado);
            fclose($file_2);

            $p_inscripcion_cerrado = $id_lista.'p_inscripcion_cerrado';

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                    'p_inscripcion_cerrado'=>$p_inscripcion_cerrado
                ]);
        }

        $p_recordatorio_f = $request->input('p_recordatorio_f')?$request->input('p_recordatorio_f'):0;

            DB::table('e_plantillas')->where('eventos_id',$id)->update([
                'p_preregistro_2'=>$request->input('p_preregistro_2'),
                'p_preregistro_email' =>$p_preregistro_email,
                'p_preregistro_msg'   =>$p_preregistro_msg,
                'p_preregistro_asunto'=>$p_preregistro_asunto,

                'p_conf_inscripcion_2'=>$request->input('p_conf_inscripcion_2'),
                'p_conf_inscripcion_email'=>$p_conf_inscripcion_email,
                'p_conf_inscripcion_msg'=>$p_conf_inscripcion_msg,
                'p_conf_inscripcion_asunto'=>$p_conf_inscripcion_asunto,

                'p_conf_registro_2'=>$request->input('p_conf_registro_2'),
                'p_conf_registro_email'=>$p_conf_registro_email,
                'p_conf_registro_msg'=>$p_conf_registro_msg,
                'p_conf_registro_asunto'=>$p_conf_registro_asunto,

                'p_recordatorio_2'=>$request->input('p_recordatorio_2'),
                'p_recordatorio_email'=>$p_recordatorio_email,
                'p_recordatorio_msg'=>$p_recordatorio_msg,
                'p_recordatorio_asunto'=>$p_recordatorio_asunto,
                'p_recordatorio_f'=>$p_recordatorio_f,

                'p_negacion_2'=>$request->input('p_negacion_2'),
                'p_negacion_email'=>$p_negacion_email,
                'p_negacion_msg'=>$p_negacion_msg,
                'p_negacion_asunto'=>$p_negacion_asunto,

                'p_baja_evento_2'=>$request->input('p_baja_evento_2'),
                'p_baja_evento_email'=>$p_baja_evento_email,
                'p_baja_evento_msg'=>$p_baja_evento_msg,
                'p_baja_evento_asunto'=>$p_baja_evento_asunto,

                'p_preinscripcion_cerrado_2'=>$request->input('p_preinscripcion_cerrado_2'),
                'p_inscripcion_cerrado_2'=>$request->input('p_inscripcion_cerrado_2'),
            ]);
            
            DB::table('e_plantillas_virtual')->where('eventos_id',$id)->update([
                'p_preregistro_asunto_v'=>$p_preregistro_asunto_v,
                #'p_preregistro_v'=>$p_preregistro_v,
                'p_preregistro_2_v'=>$request->input('p_preregistro_2_v'),
                #'p_conf_preregistro_v'=>$p_conf_preregistro_v, #tiene su if

                #'p_conf_inscripcion_v'=>$p_conf_inscripcion_v,
                'p_conf_inscripcion_2_v'=>$request->input('p_conf_inscripcion_2_v'),
                'p_conf_inscripcion_asunto_v'=>$p_conf_inscripcion_asunto_v,

                #'p_conf_registro_v'=>$p_conf_registro_v,
                'p_conf_registro_2_v'=>$request->input('p_conf_registro_2_v'),
                'p_conf_registro_asunto_v'=>$p_conf_registro_asunto_v,

                #'p_conf_registro_gracias_v'=>$p_conf_registro_gracias_v,
                #'p_recordatorio_v'=>$p_recordatorio_v,
                'p_recordatorio_2_v'=>$request->input('p_recordatorio_2_v'),
                'p_recordatorio_asunto_v'=>$p_recordatorio_asunto_v,

                #RECORDATORIO _FINALIZAR INSCRIPCION
                'p_recordatorio_fin'=>$request->input('p_recordatorio_fin'),
                'p_recordatorio_fin_v'=>$request->input('p_recordatorio_fin_v'),
                
                #'p_negacion_v'=>$p_negacion_v,
                'p_negacion_2_v'=>$request->input('p_negacion_2_v'),
                'p_negacion_asunto_v'=>$p_negacion_asunto_v,

                #'p_baja_evento_v'=>$p_baja_evento_v,
                'p_baja_evento_2_v'=>$request->input('p_baja_evento_2_v'),
                'p_baja_evento_asunto_v'=>$p_baja_evento_asunto_v,

                
            ]);

            alert()->success('Registro actualizado', 'Registro actualizado con éxito');
            return redirect()->back();
        
            #dd($request->all());
            //return redirect()->route('caii_plantilla.edit',['id'=>$id]);


        /*} catch (Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
            
        }*/
    }

    public function createForm(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(isset($request->eventos_id)){
            $eventos_id = $request->eventos_id;
        }else{

            alert()->success('El código del evento no existe', 'Advertencia');
            return redirect()->route('caii.index');
        }
       
        //return view('caii.plantillas',compact('eventos_id'));

        return view('caii.formularios',['eventos_id'=>$eventos_id]);
    }

    public function storeForm(Request $request){

        $this->validate($request, [
            'img_cabecera' => 'required|image|mimes:jpeg,png,jpg|max:1500',
            'img_footer' => 'required|image|mimes:jpeg,png,jpg|max:1500'
        ]);

        try {
            if($request->input('eventos_id') == ""){
                alert()->success('El código del evento no existe', 'Advertencia');
                return redirect()->route('caii.index');
            }
            $eventos_id = $request->input('eventos_id');
            //dd($request->all());
            $cant = DB::table('e_formularios')->where('eventos_id',$eventos_id)->count();

            if($cant >= 1){
                DB::table('e_formularios')->where('eventos_id', $eventos_id)->delete();
            }
                $img_cabecera = $request->file('img_cabecera');
                $new_img_cabecera = 'caii_head_'.strtotime('now').'.'.$img_cabecera->getClientOriginalExtension();
                $img_cabecera->move('images/form', $new_img_cabecera);

                $img_footer = $request->file('img_footer');
                $new_img_footer = 'caii_footer_'.strtotime('now').'.'.$img_footer->getClientOriginalExtension();
                $img_footer->move('images/form', $new_img_footer);

                //dd($new_img_cabecera)
                DB::table('e_formularios')->insert([
                    'eventos_id'=>$request->input('eventos_id'),
                    'descripcion_form'=>$request->input('descripcion'),
                    'img_cabecera'=>$new_img_cabecera,
                    'img_footer'=>$new_img_footer,
                    'tipo_doc'=>$request->input('tipo_doc'),
                    'dni'=>$request->input('dni'),
                    'grupo'=>$request->input('grupo'),
                    'nombres'=>$request->input('nombres'),
                    'ap_paterno'=>$request->input('ap_paterno'),
                    'ap_materno'=>$request->input('ap_materno'),
                    'pais'=>$request->input('pais'),
                    'departamentos'=>$request->input('departamentos'),
                    'profesion'=>$request->input('profesion'),
                    'entidad'=>$request->input('entidad'),
                    'cargo'=>$request->input('cargo'),
                    'email'=>$request->input('email'),
                    'celular'=>$request->input('celular')
                ]);

                alert()->success('Registro guardado con éxito', 'Mensaje');

            
                return redirect()->route('caiieventos.index');


        } catch (Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
            
        }

        
    }

    public function editForm($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        $datos = DB::table('e_formularios')->where('eventos_id', $id)->first(); 

        return view('caii.formularios_edit', compact('datos'));
    }

    public function updateForm(Request $request, $id)
    {

        try {

            if($request->img_cabecera){

                $img = DB::table('e_formularios')->select('img_cabecera')->where('eventos_id',$id)->first();
                $file = "images/form/".$img->img_cabecera;
                File::delete($file);

                $img_cabecera = $request->file('img_cabecera');
                $new_img_cabecera = 'caii_head_'.strtotime('now').'.'.$img_cabecera->getClientOriginalExtension();
                $img_cabecera->move('images/form', $new_img_cabecera);

                DB::table('e_formularios')->where('eventos_id',$id)->update([
                    'img_cabecera'=>$new_img_cabecera
                ]);
              
            }

            if($request->img_footer){

                $img = DB::table('e_formularios')->select('img_footer')->where('eventos_id',$id)->first();
                $file = "images/form/".$img->img_footer;
                File::delete($file);

                $img_footer = $request->file('img_footer');
                $new_img_footer = 'caii_footer_'.strtotime('now').'.'.$img_footer->getClientOriginalExtension();
                $img_footer->move('images/form', $new_img_footer);

                DB::table('e_formularios')->where('eventos_id',$id)->update([
                    'img_footer'=>$new_img_footer
                ]);
            }

                DB::table('e_formularios')->where('eventos_id',$id)->update([
                    'descripcion_form'=>$request->input('descripcion'),
                    'tipo_doc'=>$request->input('tipo_doc'),
                    'dni'=>$request->input('dni'),
                    'grupo'=>$request->input('grupo'),
                    'nombres'=>$request->input('nombres'),
                    'ap_paterno'=>$request->input('ap_paterno'),
                    'ap_materno'=>$request->input('ap_materno'),
                    'pais'=>$request->input('pais'),
                    'departamentos'=>$request->input('departamentos'),
                    'profesion'=>$request->input('profesion'),
                    'entidad'=>$request->input('entidad'),
                    'cargo'=>$request->input('cargo'),
                    'email'=>$request->input('email'),
                    'celular'=>$request->input('celular')
                ]);
                
                alert()->success('Registro actualizado', 'Registro actualizado con éxito');

                return redirect()->back();
            
            
        } catch (Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
            
        }
    }

    public function edit($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        //$datos = Evento::where('id', $id)->get();
        $datos = DB::table('eventos')->where('id', $id)->first();
        $plantilla_datos = DB::table('e_gafete_modelos')->get();
        $emails = Emails::orderBy("nombre",'asc')->get();
        
        return view('caii.edit', compact('datos','plantilla_datos','emails'));
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
        $this->validate($request,[
            'fechaf_pre_evento'=>'required',
        ]);

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $flag_error = 0;
        $fechai_evento = $request->input('fechai_evento');
        $fechaf_evento = $request->input('fechaf_evento');
        $fechaf_pre_evento = $request->input('fechaf_pre_evento');

        if($this->validar_fecha_espanol($fechai_evento)){ 
            $valores = explode('/', $fechai_evento);
            $fechai_evento = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;
        }else{
            $flag_error = 1;
        }


        // error fechas
        if($flag_error == 1) {
            alert()->warning('Error','Error en el campo de Fecha Inicio');
            return redirect()->back();
        }

        DB::table('eventos')->where('id', $id)->update([
             'nombre_evento'=>mb_strtoupper($request->input('nombre_evento')),
             'descripcion'=>$request->input('descripcion'),
             'fecha_texto'=>($request->input('fecha_texto')),
             'hora'=>($request->input('hora')),
             'lugar'=>mb_strtoupper($request->input('lugar')),
             'vacantes'=>($request->input('vacantes')),
             'plantilla'=>mb_strtoupper($request->input('plantilla')),
             'auto_conf'=>($request->input('auto_conf')),
             'email_id'      =>$request->input('email_id'),
             'email_asunto'  =>$request->input('email_asunto'),
             'color'=>mb_strtoupper($request->input('color')),
             'activo'=>($request->input('activo')),
             'grupo'=>mb_strtoupper($request->input('grupo')),
             'departamento'=>mb_strtoupper($request->input('departamento')),
             'fechai_evento'=>$fechai_evento,
             'fechaf_evento'=>$fechaf_evento,
             'fechaf_pre_evento'=>$fechaf_pre_evento,
             //'fechaf_insc_evento'=>$fechaf_insc_evento,
             'gafete'=>$request->input('gafete'),
             'gafete_html'=>$request->input('gafete_html'),
             'confirm_email'=>($request->input('confirm_email')),
             'confirm_msg'=>($request->input('confirm_msg')),
             'eventos_tipo_id'=> 1,
             
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now()
        ]);

        Cache::flush();
        
        alert()->success('Registro actualizado', 'Registro actualizado con éxito');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $evento = estudiantes_act_detalle::where('eventos_id', $id)->count();
        if($evento >=1 ){
            alert()->warning('Alerta','El evento esta siendo utilizado en el sistema.')->persistent('Close');
            return redirect()->route('caii.index');
        }

        // borrar img
        $img_form = DB::table('e_formularios')->select('img_cabecera','img_footer')->where('eventos_id', $id)->first();

        if($img_form){
            $img_1 = "images/form/".$img_form->img_cabecera;
            $img_2 = "images/form/".$img_form->img_footer;

            if(is_file($img_1))
                unlink($img_1);

            if(is_file($img_2))
                unlink($img_2);
        }

        Evento::where('id', $id)->delete();
        DB::table('e_formularios')->where('eventos_id', $id)->delete();
        DB::table('e_plantillas')->where('eventos_id', $id)->delete();
        
        Cache::flush();
        alert()->error('Registro borrado.','Eliminado');
        return redirect()->back();

    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }


        function get_browser_name($user_agent)
        {
            if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
            elseif (strpos($user_agent, 'Edge')) return 'Edge';
            elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
            elseif (strpos($user_agent, 'Safari')) return 'Safari';
            elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
            elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

            return 'Other';
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            $evento = estudiantes_act_detalle::where('eventos_id', $value)->get()->count();

            if($evento >=1 ){
                alert()->error('El evento esta siendo utilizado en el sistema.','Alerta');
                return redirect()->route('caii.index');
            }

            Evento::where('id',$value)->delete();

            /*estudiantes_act_detalle::where('estudiantes_id', $est[0]->dni_doc)->delete();
            $id_est_caii = DB::table('estudiantes_caii')->where('dni_doc', $est[0]->dni_doc)->count();
            if($id_est_caii >= 0){
                DB::table('estudiantes_caii')->where('dni_doc', $est[0]->dni_doc)->delete();
            }*/

            Cache::flush();
            
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('caii.index');
    }

    // popup Detalle Programación
    public function idAct(Request $request, $id)
    {
        if($request->ajax()){
            
            try {
                $datos = DB::table('eventos')
                                    ->select(DB::raw("TIMESTAMPDIFF(DAY, fechai_evento, fechaf_evento) AS dias, DATE_FORMAT(fechai_evento,'%m/%Y') AS fechai_evento, fechaf_evento"))
                                    ->where('id',$id)->get();
                if($datos){
                    
                    //return response()->json(['codigos' => $codigos, 'codigo_check' => $codigo_check, 'html' => $html]);
                    return response()->json(['datos' => $datos]);

                }
                
            } catch (\Exception $e) {
                
                return \Response::json(['error' => $e->getMessage() ], 404); 
            }

        } //end: if($request->ajax()){
    }

    
    public function idAct_enviar(Request $request)
    {

        try {
            $id_dni     = $request['id_dni'];
            $total      = $request['totalRows'];

            DB::table('estudiantes_prog_det')
                //->where('idModuloAccion', $idAccion)
                ->where('estudiantes_id', $id_dni)
                ->delete();

                //$a = 0;

            for($x=0; $x<= $total ; $x++){
                
                if($request['detprog_'.$x]){

                    //$a .=$request['detprog_'.$x];
                    
                    $detalle = new estudiantes_prog_det();
                    $detalle->eventos_id    =   $request['detprog_'.$x];  
                    $detalle->estudiantes_id     =   $id_dni;  
                    $detalle->save();
                }
                
            }
            return $x;
            

        }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }  
    }

    public function verHTML(Request $request,$id,$id2,$id3){
        if($request->ajax()){
            //return "$id - $id2- $id3";
            $plantillaHTML = DB::table('e_plantillas')->select("$id2 as plantillahtml")->where('id',$id3)->first();
            return response()->json($plantillaHTML);
        }
    }

    public function verHTML_e(Request $request,$id,$id2,$id3){
        if($request->ajax()){
            //return "$id - $id2- $id3";
            $plantillaHTML = DB::table('eventos')->select("$id2 as plantillahtml")->where('id',$id3)->first();
            return response()->json($plantillaHTML);
        }
    }
}
