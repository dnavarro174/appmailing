<?php

namespace App\Http\Controllers;
use DB;
use Cache;
use File;
use Carbon\Carbon;
use App\Evento, App\Evento_form;
use App\AccionesRolesPermisos;
use App\estudiantes_act_detalle;
use App\Emails;

use Alert;
use Auth;
use Illuminate\Http\Request;

class grupoEspecialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["inicio"]   ) ){  
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
            $permParam["modulo_alias"] = "maestria";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

            Cache::put('permisos.all', $permisos, 5);

        }

        $tipo_evento = 7; // tb_eventos_tipo: Tipo: 4 - Maestria

        if($request->get('s')){
            Cache::flush();

            $search = $request->get('s');

            $eventos_datos = Evento::where("nombre_evento", "LIKE", '%'.$search.'%')
            ->where('eventos_tipo_id',$tipo_evento)
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }else{

            $key = 'especiales.page.'.request('page', 1);
            $eventos_datos = Cache::rememberForever($key, function() use ($pag,$tipo_evento){
                return Evento::where('eventos_tipo_id',$tipo_evento)->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            });
        }

        return view('eventos.especiales.index', compact('eventos_datos','permisos')); 

    
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
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $emails = Emails::orderBy("nombre",'asc')->get();
        
        return view('eventos.especiales.create', compact('emails'));
    }

    public function store(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $flag_error = 0;
        $fechai_evento = $request->input('fechai_periodo');
        $fechaf_evento = $request->input('fechaf_periodo');
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
            alert()->warning('Error en los campos de las fechas','Error');
            return redirect()->back();
        }

        $f_fin_evento = $fechaf_evento ." ".$h_fin;


        $ev = DB::table('eventos')->insert([
             'nombre_evento'=>$request->input('nombre_periodo'),
             'descripcion'  =>$request->input('descripcion'),
             'fecha_texto'  =>($request->input('fecha_texto')),
             'hora'         =>$request->input('hora'),
             'hora_fin'     =>$request->input('hora_fin'),
             'hora_cerrar'  =>$request->input('hora_cerrar'),
             'vacantes'     =>$request->input('vacantes'),
             'lugar'        =>mb_strtoupper($request->input('lugar')),
             'activo'        => 1,
             'inscritos_pre' =>0,
             'inscritos_invi'=>0,
             'plantilla'     =>mb_strtoupper($request->input('plantilla')),
             'auto_conf'     =>$request->input('auto_conf'),
             'email_id'      =>$request->input('email_id'),
             'email_asunto'  =>$request->input('email_asunto'),
             'fechai_evento'=>$fechai_evento,
             'fechaf_evento'=>$f_fin_evento,
             'fechaf_pre_evento'=>$fechaf_pre_evento,
             'gafete'      =>$request->input('gafete'),
             'gafete_html' =>$request->input('gafete_html'),
             'auto_conf'        => $request->input('auto_conf'),
             'confirm_email'    => $confirm_email,
             'confirm_msg'      => $confirm_msg,
             'eventos_tipo_id'  => 7,// tb_eventos_tipo: Tipo: 7 - FORM ESPECIAL
             'created_at'       => Carbon::now(),
             'updated_at'       => Carbon::now()
        ]);

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
                    'p_conf_registro'=>$p_conf_registro,
                    'p_conf_registro_2'=>$request->input('p_conf_registro_2'),
                    'p_conf_registro_gracias'=>$p_conf_registro_gracias,
                    'p_recordatorio'=>$p_recordatorio,
                    'p_recordatorio_2'=>$request->input('p_recordatorio_2'),
                    'p_inscripcion_cerrado'=>$p_inscripcion_cerrado,
                    'p_inscripcion_cerrado_2'=>$request->input('p_inscripcion_cerrado_2'),
                ]);


        /*--plantillas */

        Cache::flush();
        alert()->success('Registro guardado con éxito', 'Mensaje');

        return redirect()->route('eventos-es_form.create',compact('eventos_id'));
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
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(isset($request->eventos_id)){
            $eventos_id = $request->eventos_id;
        }else{
            alert()->success('El código del evento no existe', 'Advertencia');
            return redirect()->route('eventos-es.index');
        }
       
        return view('eventos.especiales.formularios',compact('eventos_id'));
    }

    public function storeForm(Request $request){
        $this->actualizarSesion();
        $this->validate($request, [
            'img_cabecera' => 'image|mimes:jpeg,png,jpg|max:270',
            'img_footer' => 'image|mimes:jpeg,png,jpg|max:270'
        ]);
        //required|

        try {
            if($request->input('eventos_id') == ""){
                alert()->success('El código del evento no existe', 'Advertencia');
                return redirect()->route('eventos-es.index');
            }
            $eventos_id = $request->input('eventos_id');

            $cant = DB::table('e_formularios')->where('eventos_id',$eventos_id)->count();

            if($cant >= 1){
                DB::table('e_formularios')->where('eventos_id', $eventos_id)->delete();
            }
            $img_cabecera = "";
            $img_footer = "";
            $new_img_cabecera = "";
            $new_img_footer = "";

            if($request->file('img_cabecera') && $request->file('img_footer')){
                
                $img_cabecera = $request->file('img_cabecera');

                $new_img_cabecera = 'form_head_'.strtotime('now').'.'.$img_cabecera->getClientOriginalExtension();
                $img_cabecera->move('images/form', $new_img_cabecera);

                $img_footer = $request->file('img_footer');
                $new_img_footer = 'form_footer_'.strtotime('now').'.'.$img_footer->getClientOriginalExtension();
                $img_footer->move('images/form', $new_img_footer);
            }

                DB::table('e_formularios')->insert([
                    'eventos_id'      =>$request->input('eventos_id'),
                    'descripcion_form'=>$request->input('descripcion'),
                    'img_cabecera'    =>$new_img_cabecera,
                    'img_footer'      =>$new_img_footer,
                    'tipo_doc'      =>$request->input('tipo_doc'),
                    'dni'           =>$request->input('dni'),
                    'grupo'         =>$request->input('grupo'),
                    'gradoprof'     =>$request->input('gradoprof'),
                    'nombres'       =>$request->input('nombres'),
                    'ap_paterno'    =>$request->input('ap_paterno'),
                    'ap_materno'    =>$request->input('ap_materno'),
                    'pais'          =>$request->input('pais'),
                    'departamentos' =>$request->input('departamentos'),
                    'provincia'     =>$request->input('provincia'),
                    'distrito'      =>$request->input('distrito'),
                    'profesion'     =>$request->input('profesion'),
                    'entidad'       =>$request->input('entidad'),
                    'cargo'         =>$request->input('cargo'),
                    'direccion'     =>$request->input('direccion'),
                    'email'         =>$request->input('email'),
                    'email_labor'   =>$request->input('email_labor'),
                    'celular'       =>$request->input('celular'),
                    'imagen'        =>$request->input('imagen'),
                    'terminos'      =>$request->input('terminos'),
                    'discapacidad'  =>$request->input('discapacidad'),
                    'compago'       =>$request->input('compago'),
                    'decjur'        =>$request->input('decjur'),
                    'ficins'        =>$request->input('ficins'),
                    'nvoucher'      =>$request->input('nvoucher'),
                    'fechadepo'     =>$request->input('fechadepo'),
                    'cv'            =>$request->input('cv')
                ]);

                alert()->success('Registro guardado con éxito', 'Mensaje');
                return redirect()->route('eventos-es.index');

        } catch (Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
            
        }

        
    }

    public function editForm($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $datos = Evento_form::where('eventos_id',$id)->firstOrFail();

        return view('eventos.especiales.formularios_edit', compact('datos'));
    }

    public function updateForm(Request $request, $id)
    {
        
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        try {
            
            #$public = public_path()."/images/form/";
            $public = "images/form/";
            
            #$url_img = public_path('images/form/');
            $url_img = 'images/form';

            if($request->img_cabecera){

                $img = DB::table('e_formularios')->select('img_cabecera')->where('eventos_id',$id)->first();
                $file = $public.$img->img_cabecera;
                File::delete($file);

                $img_cabecera = $request->file('img_cabecera');
                $new_img_cabecera = 'form_head_'.strtotime('now').'.'.$img_cabecera->getClientOriginalExtension();
                $img_cabecera->move($url_img, $new_img_cabecera);

                DB::table('e_formularios')->where('eventos_id',$id)->update([
                    'img_cabecera'=>$new_img_cabecera
                ]);
              
            }

            if($request->img_footer){

                $img = DB::table('e_formularios')->select('img_footer')->where('eventos_id',$id)->first();
                $file = $public.$img->img_footer;
                File::delete($file);

                $img_footer = $request->file('img_footer');
                $new_img_footer = 'form_footer_'.strtotime('now').'.'.$img_footer->getClientOriginalExtension();
                $img_footer->move($url_img, $new_img_footer);

                DB::table('e_formularios')->where('eventos_id',$id)->update([
                    'img_footer'=>$new_img_footer
                ]);
            }

                DB::table('e_formularios')->where('eventos_id',$id)->update([
                    'descripcion_form'=>$request->input('descripcion'),
                    'tipo_doc'      =>$request->input('tipo_doc'),
                    'dni'           =>$request->input('dni'),
                    'grupo'         =>$request->input('grupo'),
                    'gradoprof'     =>$request->input('gradoprof'),
                    'nombres'       =>$request->input('nombres'),
                    'ap_paterno'    =>$request->input('ap_paterno'),
                    'ap_materno'    =>$request->input('ap_materno'),
                    'pais'          =>$request->input('pais'),
                    'departamentos' =>$request->input('departamentos'),
                    'provincia'     =>$request->input('provincia'),
                    'distrito'      =>$request->input('distrito'),
                    'profesion'     =>$request->input('profesion'),
                    'entidad'       =>$request->input('entidad'),
                    'cargo'         =>$request->input('cargo'),
                    'direccion'     =>$request->input('direccion'),
                    'email'         =>$request->input('email'),
                    'email_labor'   =>$request->input('email_labor'),
                    'celular'       =>$request->input('celular'),
                    'imagen'        =>$request->input('imagen'),
                    'terminos'      =>$request->input('terminos'),
                    'discapacidad'  =>$request->input('discapacidad'),
                    'compago'       =>$request->input('compago'),
                    'decjur'        =>$request->input('decjur'),
                    'ficins'        =>$request->input('ficins'),
                    'nvoucher'      =>$request->input('nvoucher'),
                    'fechadepo'     =>$request->input('fechadepo'),
                    'cv'            =>$request->input('cv')
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


    public function edit($id)
    {

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $datos = DB::table('eventos')->join('e_plantillas as p', 'p.eventos_id','eventos.id')
                ->where('eventos.id', $id)
                ->select('eventos.id','p.id as cod_plantilla','eventos.nombre_evento','eventos.descripcion','eventos.fecha_texto','eventos.hora','eventos.hora_fin','eventos.hora_cerrar','eventos.lugar','eventos.vacantes','eventos.color','eventos.fechai_evento','eventos.fechaf_evento','eventos.gafete','eventos.gafete_html','eventos.confirm_email','eventos.confirm_msg','eventos.auto_conf','p.p_conf_registro','p.p_conf_registro_2','p.p_conf_registro_gracias','p.p_recordatorio','p.p_recordatorio_2','p.p_inscripcion_cerrado','eventos.email_id','eventos.email_asunto')->first();

        $emails = Emails::orderBy("nombre",'asc')->get();

        return view('eventos.especiales.edit', compact('datos', 'emails'));
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
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $id_lista  = $id;
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
            alert()->warning('Error en los campos de las fechas','Error');
            return redirect()->back();
        }

        $f_fin_evento = $fechaf_evento ." ".$h_fin;

        DB::table('eventos')->where('id', $id)->update([
             'nombre_evento'=>$request->input('nombre_evento'),
             'descripcion'  =>$request->input('descripcion'),
             'fecha_texto'  =>$request->input('fecha_texto'),
             'hora'         =>$request->input('hora'),
             'hora_fin'     =>$request->input('hora_fin'),
             'hora_cerrar'  =>$request->input('hora_cerrar'),
             'lugar'        =>mb_strtoupper($request->input('lugar')),
             'vacantes'     =>$request->input('vacantes'),
             'color'        =>mb_strtoupper($request->input('color')),
             'activo'       =>1,
             'fechai_evento'=>$fechai_evento,
             'fechaf_evento'=>$f_fin_evento,
             'auto_conf'    =>$request->input('auto_conf'),
             'email_id'     =>$request->input('email_id'),
             'email_asunto' =>$request->input('email_asunto'),
             'gafete'       => $request->input('gafete'),
             'gafete_html'  => $request->input('gafete_html'),
             'auto_conf'    => $request->input('auto_conf'),
             'confirm_email'=> $confirm_email,
             'confirm_msg'  => $confirm_msg,
             'eventos_tipo_id'=> 7,// tb_eventos_tipo: Tipo: 7 - FORM ESPECIAL
             'updated_at'   => Carbon::now()
        ]);

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

        DB::table('e_plantillas')->where('eventos_id',$id)->update([
            'p_conf_registro_2'=>$request->input('p_conf_registro_2'),
            'p_recordatorio_2'=>$request->input('p_recordatorio_2')
        ]);

        Cache::flush();
        alert()->success('Registro actualizado con éxito.', 'Mensaje');

        return redirect()->back();
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            $evento = estudiantes_act_detalle::where('eventos_id', $value)->count();

            if($evento >=1 ){
                alert()->error('El evento esta siendo utilizado en el sistema.','Alerta');
                return redirect()->route('eventos-es.index');
            }

            Evento::where('id',$value)->where('eventos_tipo_id',7)->delete();
            Cache::flush();
            
        }
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('eventos-es.index');
    }

    public function destroy($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos-es"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $evento = estudiantes_act_detalle::where('eventos_id', $id)->count();

            if($evento >=1 ){
                alert()->warning('El evento esta siendo utilizado en el sistema.','Alerta')->persistent('Close');
                return redirect()->route('eventos-es.index');
            }

        // borrar img
        $form = DB::table('e_formularios')->where('eventos_id',$id)->count();
        if($form > 0){

            $img_form = DB::table('e_formularios')->select('img_cabecera','img_footer')->where('eventos_id', $id)->first();
            #$img_1 = public_path()."/images/form/".$img_form->img_cabecera;
            $img_1 = "images/form/".$img_form->img_cabecera;
            $img_2 = "images/form/".$img_form->img_footer;

            if(is_file($img_1))
                unlink($img_1);

            if(is_file($img_1))
                unlink($img_2);
        }

        Evento::where('id', $id)->where('eventos_tipo_id',7)->delete();
        DB::table('e_formularios')->where('eventos_id', $id)->delete();
        DB::table('e_plantillas')->where('eventos_id', $id)->delete();

        Cache::flush();
        alert()->error('Registro borrado.','Eliminado');
        return redirect()->back();
    }
}
