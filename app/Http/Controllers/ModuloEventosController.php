<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cache;
use File;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use App\Evento;
use App\AccionesRolesPermisos;
use App\estudiantes_act_detalle;
use App\Emails;
use Alert;
use Auth;

class ModuloEventosController extends Controller
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
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["inicio"]   ) ){  
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

        if(Cache::has('permisos.all')){
            $permisos = Cache::get('permisos.all');

        }else{

            $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
            $permParam["modulo_alias"] = "eventos";
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
                ->where('eventos_tipo_id',2)
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            }else{

                $key = 'mod_eventos.page.'.request('page', 1);
                $eventos_datos = Cache::rememberForever($key, function() use ($pag){
                    return Evento::where('eventos_tipo_id',2)->orderBy('id', request('sorted', 'DESC'))
                    ->paginate($pag);

                });
            }
        
            
        return view('eventos.index', compact('eventos_datos', 'permisos')); 

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function opciones()
    {
        
    }

    public function create(Request $request)
    {

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        if($request->get('tipo')){
            session(['tipo_evento'=> $request->get('tipo') ]);
            $tipo_evento = session('tipo_evento');
        }else{
            return redirect()->route('eventos.index');
        }

        $emails = Emails::orderBy("nombre",'asc')->get();
        $plantilla_datos = DB::table('e_gafete_modelos')->get();

        if($tipo_evento == 1)
            return view('eventos.create', compact('plantilla_datos', 'emails'));
        else
            return view('eventos.create_virtual', compact('plantilla_datos', 'emails'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->actualizarSesion();

        $tipo_evento = intval(session('tipo_evento'));
        $request->session()->forget('tipo_evento');

        $flag_error = 0;
        $fechai_evento = $request->input('fechai_evento');
        $fechaf_evento = $request->input('fechaf_evento');
        $h_fin         = $request->input('hora_fin');
        $fechaf_pre_evento = $request->input('fechaf_pre_evento');
        //$fechaf_insc_evento = $request->input('fechaf_insc_evento');

        $id_lista = $request->input('cod_plantilla');
        $auto_conf = $request->input('auto_conf');

        if($auto_conf == 1){
            $confirm_email = $request->input('confirm_email');
            $confirm_msg = $request->input('confirm_msg');
        }else{
            $confirm_email = 0;
            $confirm_msg = 0;
        }

        if($this->validar_fecha_espanol($fechai_evento)){ 
            $valores = explode('/', $fechai_evento);
            $fechai_evento = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;
        }else{
            $flag_error = 1;
        }

        if($this->validar_fecha_espanol($fechaf_evento)){ 
            $valores = explode('/', $fechaf_evento);
            $fechaf_evento = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;
        }else{
            $flag_error = 1;
        }

        // error fechas
        if($flag_error == 1) {
            alert()->warning('Error','Error en los campos de las fechas.');
            return redirect()->back();
        }

        $f_fin_evento = $fechaf_evento ." ".$h_fin;

        DB::table('eventos')->insert([
             'nombre_evento'=>$request->input('nombre_evento'),
             'tipo'         =>$tipo_evento,
             'descripcion'  =>$request->input('descripcion'),
             'fecha_texto'  =>($request->input('fecha_texto')),
             'hora'         =>$request->input('hora'),
             'hora_fin'     =>$request->input('hora_fin'),
             'hora_cerrar'  =>$request->input('hora_cerrar'),
             'lugar'         =>$request->input('lugar'),
             'vacantes'      =>$request->input('vacantes'),
             'inscritos_pre' =>0,
             'inscritos_invi'=>0,
             'plantilla'     =>mb_strtoupper($request->input('plantilla')),
             'auto_conf'     =>$auto_conf,
             'email_id'      =>$request->input('email_id'),
             'email_asunto'  =>$request->input('email_asunto'),
             'color'         =>mb_strtoupper($request->input('color')),
             'activo'        => 1,
             'fechai_evento' =>$fechai_evento,
             'fechaf_evento' =>$f_fin_evento,
             'fechaf_pre_evento'=>$fechaf_pre_evento,
             'gafete'        =>$request->input('gafete'),
             'gafete_html'   =>$request->input('gafete_html'),
             'confirm_email' => $confirm_email,
             'confirm_msg'   => $confirm_msg,
             'eventos_tipo_id'=> 2,// modulo eventos
             
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now()
        ]);

        if($tipo_evento == 1){

            $eventos_id = DB::getPdo()->lastInsertId();

            /*--plantillas */
            $p_preregistro = "";
            $p_conf_inscripcion = "";
            $p_conf_registro = "";
            $p_conf_registro_gracias = "";
            $p_recordatorio = "";
            $p_negacion = "";
            $p_baja_evento = "";
            $p_preinscripcion_cerrado = "";
            $p_baja_evento = "";
            $p_inscripcion_cerrado = "";

            $cant = DB::table('e_plantillas')->where('eventos_id',$eventos_id)->count();

            if($cant >= 1){
                DB::table('e_plantillas')->where('eventos_id', $eventos_id)->delete();
            }

            $id_lista = $eventos_id;

            if($request->input('p_conf_registro') != ""){
                // campo 3:
                $p_conf_registro = $request->input('p_conf_registro');

                $file=fopen('files/html/'.$id_lista.'p_conf_registro'.'.html','w') or die ("error creando fichero!");
                fwrite($file,$p_conf_registro);
                fclose($file);

                $file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro.blade.php','w') or die ("error creando fichero!");

                //$file_2=fopen(resource_path().'/views/email/'.$id_lista.'p_conf_registro.blade.php','w') or die ("error creando fichero!");
                fwrite($file_2,$p_conf_registro);
                fclose($file_2);
            
                $p_conf_registro = $id_lista.'p_conf_registro';
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
                'eventos_id'=>$eventos_id,
                //'p_preregistro'=>$p_preregistro,
                //'p_preregistro_2'=>$request->input('p_preregistro_2'),
                //'p_conf_inscripcion'=>$p_conf_inscripcion,
                //'p_conf_inscripcion_2'=>$request->input('p_conf_inscripcion_2'),
                'p_conf_registro'=>$p_conf_registro,
                'p_conf_registro_2'=>$request->input('p_conf_registro_2'),
                'p_conf_registro_gracias'=>$p_conf_registro_gracias,
                'p_recordatorio'=>$p_recordatorio,
                'p_recordatorio_2'=>$request->input('p_recordatorio_2'),
                
                //'p_preinscripcion_cerrado'=>$p_preinscripcion_cerrado,
                //'p_preinscripcion_cerrado_2'=>$request->input('p_preinscripcion_cerrado_2'),
                'p_inscripcion_cerrado'=>$p_inscripcion_cerrado,
                'p_inscripcion_cerrado_2'=>$request->input('p_inscripcion_cerrado_2'),
            ]);


            /*--plantillas */

            Cache::flush();
            alert()->success('Registro guardado con éxito', 'Mensaje');
            return redirect()->route('eventos_form.create',compact('eventos_id'));

        }else{

            Cache::flush();
            alert()->success('Registro guardado con éxito', 'Mensaje');
            return redirect()->route('eventos.index');
        }

            

    }

    public function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }


    public function createForm(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(isset($request->eventos_id)){
            $eventos_id = $request->eventos_id;
        }else{
            alert()->success('El código del evento no existe', 'Advertencia');
            return redirect()->route('eventos.index');
        }
       
        //return view('eventos.plantillas',compact('eventos_id'));

        return view('eventos.formularios',compact('eventos_id'));
    }

    public function storeForm(Request $request){
        $this->actualizarSesion();
        $this->validate($request, [
            'img_cabecera' => 'required|image|mimes:jpeg,png,jpg|max:1500',
            'img_footer' => 'required|image|mimes:jpeg,png,jpg|max:1500'
        ]);

        try {
            if($request->input('eventos_id') == ""){
                alert()->success('El código del evento no existe', 'Advertencia');
                return redirect()->route('eventos.index');
            }
            $eventos_id = $request->input('eventos_id');

            $cant = DB::table('e_formularios')->where('eventos_id',$eventos_id)->count();

            if($cant >= 1){
                DB::table('e_formularios')->where('eventos_id', $eventos_id)->delete();
            }
                $img_cabecera = $request->file('img_cabecera');

                $new_img_cabecera = 'evento_head_'.strtotime('now').'.'.$img_cabecera->getClientOriginalExtension();
                $img_cabecera->move('images/form', $new_img_cabecera);
                //$img_cabecera->move(public_path('images/form'), $new_img_cabecera);
                //$img_cabecera->move('D:\laragon\www\tkt_junto\public\images\form', $new_img_cabecera);

                $img_footer = $request->file('img_footer');
                $new_img_footer = 'evento_footer_'.strtotime('now').'.'.$img_footer->getClientOriginalExtension();
                $img_footer->move('images/form', $new_img_footer);
                //$img_footer->move(public_path('images/form/'), $new_img_footer);
                //$img_footer->move('D:\laragon\www\tkt_junto\public\images\form', $new_img_footer);

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
                return redirect()->route('eventos.index');

        } catch (Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
            
        }

        
    }

    public function editForm($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $datos = DB::table('e_formularios')->where('eventos_id', $id)->first();

        return view('eventos.formularios_edit', compact('datos'));
    }

    public function updateForm(Request $request, $id)
    {
        $this->actualizarSesion();
        try {

            if($request->img_cabecera){

                $img = DB::table('e_formularios')->select('img_cabecera')->where('eventos_id',$id)->first();
                $file = "images/form/".$img->img_cabecera;
                File::delete($file);

                $img_cabecera = $request->file('img_cabecera');
                $new_img_cabecera = 'evento_head_'.strtotime('now').'.'.$img_cabecera->getClientOriginalExtension();
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
                $new_img_footer = 'evento_footer_'.strtotime('now').'.'.$img_footer->getClientOriginalExtension();
                $img_footer->move('images/form/', $new_img_footer);

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

                alert()->success('Registro actualizado con éxito', 'Mensaje');

                return redirect()->back();

            
        } catch (Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }
    }


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $emails = Emails::orderBy("nombre",'asc')->get();

        $plantilla_datos = DB::table('e_gafete_modelos')->get();

        $datos = Evento::findOrFail($id);

        if($datos->tipo == 1){

            $datos = DB::table('eventos')->join('e_plantillas as p', 'p.eventos_id','eventos.id')
                    ->where('eventos.id', $id)
                    ->select('eventos.id','p.id as cod_plantilla','eventos.nombre_evento','eventos.descripcion','eventos.fecha_texto','eventos.hora','eventos.hora_fin','eventos.hora_cerrar','eventos.lugar','eventos.vacantes','eventos.color','eventos.fechai_evento','eventos.fechaf_evento','eventos.gafete','eventos.gafete_html','eventos.confirm_email','eventos.confirm_msg','eventos.auto_conf','p.p_conf_registro','p.p_conf_registro_2','p.p_conf_registro_gracias','p.p_recordatorio','p.p_recordatorio_2','p.p_inscripcion_cerrado','eventos.email_id','eventos.email_asunto')->first();

            return view('eventos.edit', compact('datos','plantilla_datos', 'emails'));
        }

        return view('eventos.edit_virtual', compact('datos','plantilla_datos', 'emails'));
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
            'fechai_evento'=>'required',
        ]);
        
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $id_lista = $id;
        $auto_conf = $request->input('auto_conf');

        if($auto_conf == 1){
            $confirm_email = $request->input('confirm_email');
            $confirm_msg = $request->input('confirm_msg');
        }else{
            $confirm_email = 0;
            $confirm_msg = 0;
        }

        $flag_error = 0;
        $fechai_evento = $request->input('fechai_evento');
        $fechaf_evento = $request->input('fechaf_evento');
        $h_fin         = $request->input('hora_fin');
        
        if($this->validar_fecha_espanol($fechai_evento)){ 
            $valores = explode('/', $fechai_evento);
            $fechai_evento = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;

        }else{
            $flag_error = 1;
        }
        if($this->validar_fecha_espanol($fechaf_evento)){ 
            $valores = explode('/', $fechaf_evento);
            $fechaf_evento = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 0;

        }else{
            $flag_error = 1;
        }

        // error fechas
        if($flag_error == 1) {
            alert()->warning('Error','Error en los campos de las fechas');
            return redirect()->back();
        }

        $f_fin_evento = $fechaf_evento ." ".$h_fin;

        $tipo_evento = intval(session('tipo_evento'));
        $request->session()->forget('tipo_evento');

        $evento = Evento::findOrFail($id);
        $tipo_evento = $evento->tipo;

        DB::table('eventos')->where('id', $id)->update([
             'nombre_evento'=>$request->input('nombre_evento'),
             'descripcion'  =>$request->input('descripcion'),
             'fecha_texto'  =>$request->input('fecha_texto'),
             'hora'         =>$request->input('hora'),
             'hora_fin'     =>$request->input('hora_fin'),
             'hora_cerrar'  =>$request->input('hora_cerrar'),
             'lugar'        =>$request->input('lugar'),
             'vacantes'     =>$request->input('vacantes'),
             //'plantilla'=>mb_strtoupper($request->input('plantilla')),
             'color'        =>mb_strtoupper($request->input('color')),
             'activo'        => 1,
             'auto_conf'     =>$auto_conf,
             'email_id'      =>$request->input('email_id'),
             'email_asunto'  =>$request->input('email_asunto'),
             
             'fechai_evento'=>$fechai_evento,
             'fechaf_evento'=>$f_fin_evento,

             'gafete'       => $request->input('gafete'),
             'gafete_html'  => $request->input('gafete_html'),
             'confirm_email'=> $confirm_email,
             'confirm_msg'  => $confirm_msg,
             'eventos_tipo_id'=> 2,// modulo eventos
             'updated_at'   => Carbon::now()
        ]);

        if($tipo_evento == 1){

            // UPDATE e_plantillas
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
                        'p_recordatorio'=>$p_recordatorio
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


            DB::table('e_plantillas')->where('id',$request->input('cod_plantilla'))->update([
                'p_conf_registro_2'=>$request->input('p_conf_registro_2'),
                'p_recordatorio_2'=>$request->input('p_recordatorio_2')
            ]);
        }

        Cache::flush();
        alert()->success('Registro actualizado con éxito.', 'Mensaje');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $evento = estudiantes_act_detalle::where('eventos_id', $id)->count();

            if($evento >=1 ){
                alert()->warning('Alerta','El evento esta siendo utilizado en el sistema.')->persistent('Close');
                return redirect()->route('eventos.index');
            }

        // borrar img
        $form = DB::table('e_formularios')->where('eventos_id',$id)->count();
        if($form > 0){

            $img_form = DB::table('e_formularios')->select('img_cabecera','img_footer')->where('eventos_id', $id)->first();
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
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["eliminar"]   ) ){  
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

            $evento = estudiantes_act_detalle::where('eventos_id', $value)->count();

            if($evento >=1 ){
                alert()->error('El evento esta siendo utilizado en el sistema.','Alerta');
                return redirect()->route('eventos.index');
            }

            Evento::where('id',$value)->delete();
            Cache::flush();
            
        }
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('eventos.index');
    }

    public function verHTML(Request $request,$id){

        if($request->ajax()){
            
            $plantillaHTML = DB::table('e_gafete_modelos')
            ->select('html as plantillahtml')
            ->where('id',$id)->first();
            return response()->json($plantillaHTML);
        }
    }

    public function verHTML_e(Request $request,$id,$id2,$id3){
        
        if($request->ajax()){
            $plantillaHTML = DB::table('eventos')->select("$id2 as plantillahtml")->where('id',$id3)->first();
            return response()->json($plantillaHTML);
        }
    }
}
