<?php

namespace App\Http\Controllers;
//namespace App\Exports;

use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Estudiante, App\Evento;

use App\TipoDoc;
use App\Departamento;
use App\Provincia;
use App\Distrito;
use App\ConsultaDNI;
use App\EstudianteTemp;
use App\estudiantes_act_detalle;

use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;

use Mail;
use Excel;
use Alert;
use Auth;

class Est_AcademicoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $id_aca = $request->get('academico_id');
        
        if($id_aca){
            $rs_datos = DB::table('eventos')->where('academico_id', $id_aca)->count();

            if($rs_datos == 0){
                alert()->warning('El Módulo Académico no existe.','Error')->persistent('Close');
                dd('eee '.session('academico_id'));
                return redirect()->route('academico.index');
            }

            $rs_datos = Evento::where('academico_id', $id_aca)->select('id')->first();

            session(['academico_id'=> $rs_datos->id, 'academico'=> $id_aca, ]);

        }else{
            if(session('academico_id') == false){
                return redirect()->route('academico.index');
            }
        }


        //dd(session('academico_id') ."--". session('academico'));
        // BLOQUEO DE IMPORT 
        $rs_datos = DB::table('eventos')->where('id',session('academico_id'))->count();

        if($rs_datos == 0){
            alert()->warning('El Módulo no existe.','Error')->persistent('Close');
            dd('eee '.session('academico_id'));
            return redirect()->route('academico.index');
        }

        $rs_datos = Evento::where('id', session('academico_id'))->select('id','vacantes')->first();
        $n_e = estudiantes_act_detalle::where('eventos_id', session('eventos_id'))->count();

        $evento_vencido = "";

        $id_even = $rs_datos->id;

        if($n_e >= $rs_datos->vacantes){
            $evento_vencido = 1;
        }else{
            $evento_vencido = 0;
        }

        /*if($request->get('eventos_id')){
            Cache::flush();
            session(['eventos_id'=> $request->get('eventos_id') ]);
        }else{
            if(session('eventos_id') == false){
                return redirect()->route('eventos.index');
            }
        }*/
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
            $permParam["modulo_alias"] = "academico";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
            Cache::put('permisos.all', $permisos, 1);
        }
        ////FIN DE PERMISOS

        $departamentos_datos = Cache::rememberForever('depa', function() {
            return Departamento::select('ubigeo_id','nombre')
            ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
            ->get();
        });

        $tipos = DB::table('estudiantes_tipo')->get();
        
            if($request->get('s')){

                Cache::flush();
                $search = $request->get('s');

                $estudiantes_datos = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','estudiantes.created_at','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo')
                ->where('de.eventos_id',session('academico_id'))
                ->where(function ($query) use ($search) {
                    $query->where("estudiantes.dni_doc", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.cargo", "LIKE", '%'.$search.'%')
                    ->orWhere("de.dgrupo", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.organizacion", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.accedio", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.email", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.email_labor", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.profesion", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.direccion", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.pais", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.region", "LIKE", '%'.$search.'%')
                    ->orWhere("estudiantes.celular", "LIKE", '%'.$search.'%')
                    ->orWhere(DB::raw('CONCAT(nombres," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$search.'%')
                    ->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$search.'%');
                })
                ->orderBy('estudiantes.id', request('sorted', 'DESC'))
                ->paginate($pag);

            }elseif($request->get('st')){
                
                $search = $request->get('st');

                $estudiantes_datos = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                    ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','de.daccedio','estudiantes.created_at','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo')
                    ->where('de.eventos_id',session('academico_id'))
                    ->where('de.estudiantes_tipo_id',$search)
                    ->orderBy('id', request('sorted', 'DESC'))
                    ->paginate($pag);

            }elseif($request->get('reg')){
                
                $search = $request->get('reg');

                $estudiantes_datos = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                    ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','de.daccedio','estudiantes.created_at','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo')
                    ->where('de.eventos_id',session('academico_id'))
                    ->where('de.daccedio',$search)
                    ->orderBy('id', request('sorted', 'DESC'))
                    ->paginate($pag);

            }else{
                
                Cache::flush();
                $key = 'est.page.'.request('page', 1);
                $estudiantes_datos = Cache::rememberForever($key, function() use ($pag){

                return Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                        ->where('de.eventos_id',session('academico_id'))
                        ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','de.daccedio','estudiantes.created_at','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo')
                        ->orderBy('estudiantes.id', request('sorted', 'DESC'))
                        ->paginate($pag);
                });
            }

        return view('est.index', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos')); 
        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if($request->academico_id != ""){
            session(['academico_id'=> $request->academico_id]);
        }
        if(session('academico_id') == false){
            return redirect()->route('academico.index');
        }

        $tipos = DB::table('estudiantes_tipo')->get();
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $tipo_doc = TipoDoc::all();
        $grupos = DB::table('est_grupos')->get();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
        
        return view('est.create', compact('departamentos_datos','tipo_doc','tipos','countrys','grupos'));

    }

    public function getDepartamentos(Request $request,$id){
        if($request->ajax()){
            $provincias = Departamento::departamentos($id);
            return response()->json($provincias);
        }
    }

    public function getProvincias(Request $request,$id){
        if($request->ajax()){
            $provincias = Provincia::provincias($id);
            return response()->json($provincias);
        }
    }
    public function getProvinciasEdit(Request $request,$aa,$id){
        if($request->ajax()){
            $provincias = Provincia::provincias($id);
            return response()->json($provincias);
        }
    }

    public function getDistritos(Request $request,$id){
        if($request->ajax()){
            $distritos = Distrito::distritos($id);
            return response()->json($distritos);
        }
    }
    public function getDistritosEdit(Request $request,$aa,$id){
        if($request->ajax()){
            $distritos = Distrito::distritos($id);
            return response()->json($distritos);
        }
    }
    public function getDNI(Request $request,$id,$evento=0){
        if($request->ajax()){
            $selectDNI = ConsultaDNI::selectDNI($id,$evento);
            return response()->json($selectDNI);
        }
    }

    public function EstudianteExport(){
        Excel::create('Participantes', function($excel) {
 
            //$estudiantes = Estudiante::all();
            $estudiantes = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
            ->orderBy('estudiantes.id','asc')
            ->get();

            //sheet -> nomb de hoja
            $excel->sheet('Estudiante', function($sheet) use($estudiantes) {
                //$sheet->fromArray($estudiantes); // muestra todos los campos
                $sheet->row(1, [
                    'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Email', 'Registrado', 'Fecha de Actualización','Tipo'
                ]);
                foreach($estudiantes as $index => $estud) {
                    $sheet->row($index+2, [
                        $estud->dni_doc, $estud->nombres, $estud->ap_paterno, $estud->ap_materno, $estud->email,$estud->accedio, $estud->updated_at,$estud->estudiantes_tipo_id
                    ]); 
                }
            });
        })->export('xlsx');
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'inputdni'=>'required',
            'cboTipDoc' => 'required'
        ]);

        $error = "";
        $dni_doc = $request->input('inputdni');
        $existe = $request->input('existe');

        $rs_datos = DB::table('eventos')->where('id',session('academico_id'))
                            ->orderBy('id', 'desc')
                            ->first();

        if($rs_datos){
            $evento_id = $rs_datos->id;
            $fechai_evento = $rs_datos->fechai_evento;
            $fechaf_evento = $rs_datos->fechaf_evento;
        }else{
            alert()->success('Ingrese a un Curso','Alerta');
            return redirect()->route('academico.index');
        }


        $tipo_estudiante = 4;//= ESTUDIANTE

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

        // si existe DNI
        if($existe == 2){return "El participante ya esta registrado.";}
        if($existe == 0){

            DB::table('estudiantes')->insert([
                 'dni_doc'=>mb_strtoupper($request->input('inputdni')),
                 'ap_paterno'=>mb_strtoupper($request->input('inputApe_pat')),
                 'ap_materno'=>mb_strtoupper($request->input('inputApe_mat')),
                 'nombres'=>mb_strtoupper($request->input('inputNombres')),
                 'fecha_nac'=>mb_strtoupper($request->input('inputFechaNac')),
                 'grupo'=>mb_strtoupper($request->input('grupo')),
                 'cargo'=>mb_strtoupper($request->input('inputCargo')),
                 'organizacion'=>mb_strtoupper($request->input('inputOrganizacion')),
                 'profesion'=>mb_strtoupper($request->input('inputProfesion')),
                 'direccion'=>mb_strtoupper($request->input('inputDireccion')),
                 'telefono'=>mb_strtoupper($request->input('telefono')),
                 'telefono_labor'=>mb_strtoupper($request->input('inputTelefono_2')),
                 'codigo_cel'=>$request->input('codigo_cel'),
                 'celular'=>mb_strtoupper($request->input('inputCelular')),
                 'email'=>$request->input('inputEmail'),
                 'email_labor'=>$request->input('inputEmail_2'),
                 'sexo'=>$request->input('cboSexo'),
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now(),
                 'estado'=>1,
                 //'accedio'=>$request->input('accedio'),
                 'accedio'=>'SI',
                 'track'=>$request->input('track'),

                 'pais'=>$request->input('pais'),
                 'region'=>$request->input('region'),
                 'tipo_documento_documento_id'=>$request->input('cboTipDoc'),
                 'news'=>$request->input('check_newsletter'),
                 'tipo_id'=>$tipo_estudiante,
                 'ip'=>request()->ip(),
                 'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                 'entidad'=>$request->input('entidad'),
                 'ubigeo_ubigeo_id'=>$request->input('cboDistrito')
            ]);

            $id_dni = DB::getPdo()->lastInsertId();

            DB::table('audi_estudiantes')->insert([
                 'id_estudiante'=>$id_dni,
                 'dni_doc'=>mb_strtoupper($request->input('inputdni')),
                 'ap_paterno'=>mb_strtoupper($request->input('inputApe_pat')),
                 'ap_materno'=>mb_strtoupper($request->input('inputApe_mat')),
                 'nombres'=>mb_strtoupper($request->input('inputNombres')),
                 'fecha_nac'=>mb_strtoupper($request->input('inputFechaNac')),
                 'grupo'=>mb_strtoupper($request->input('grupo')),
                 'cargo'=>mb_strtoupper($request->input('inputCargo')),
                 'organizacion'=>mb_strtoupper($request->input('inputOrganizacion')),
                 'profesion'=>mb_strtoupper($request->input('inputProfesion')),
                 'direccion'=>mb_strtoupper($request->input('inputDireccion')),
                 'telefono'=>mb_strtoupper($request->input('telefono')),
                 'telefono_labor'=>mb_strtoupper($request->input('inputTelefono_2')),
                 'celular'=>mb_strtoupper($request->input('inputCelular')),
                 'email'=>$request->input('inputEmail'),
                 'email_labor'=>$request->input('inputEmail_2'),
                 'sexo'=>$request->input('cboSexo'),
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now(),
                 'estado'=>1,
                 'accedio'=>$request->input('accedio'),
                 'track'=>$request->input('track'),
                 'tipo_documento_documento_id'=>$request->input('cboTipDoc'),
                 'ip'=>request()->ip(),
                 'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                 'entidad' => $request->input('entidad'),
                 'ubigeo_ubigeo_id'     => $request->input('cboDistrito'),
                 'accion'  => 'INSERT',
                 'usuario' => \Auth::User()->email
            ]);
        }else{
            //Modificar estudiante
            DB::table('estudiantes')->where('dni_doc',$dni_doc)->update([
                 'dni_doc'=>mb_strtoupper($request->input('inputdni')),
                 'ap_paterno'=>mb_strtoupper($request->input('inputApe_pat')),
                 'ap_materno'=>mb_strtoupper($request->input('inputApe_mat')),
                 'nombres'=>mb_strtoupper($request->input('inputNombres')),
                 'fecha_nac'=>mb_strtoupper($request->input('inputFechaNac')),
                 //'grupo'=>mb_strtoupper($request->input('grupo')),
                 'cargo'=>mb_strtoupper($request->input('inputCargo')),
                 'organizacion'=>mb_strtoupper($request->input('inputOrganizacion')),
                 'profesion'=>mb_strtoupper($request->input('inputProfesion')),
                 'direccion'=>mb_strtoupper($request->input('inputDireccion')),
                 'telefono'=>mb_strtoupper($request->input('telefono')),
                 'telefono_labor'=>mb_strtoupper($request->input('inputTelefono_2')),
                 'codigo_cel'=>$request->input('codigo_cel'),
                 'celular'=>mb_strtoupper($request->input('inputCelular')),
                 'email'=>$request->input('inputEmail'),
                 'email_labor'=>$request->input('inputEmail_2'),
                 'sexo'=>$request->input('cboSexo'),
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now(),
                 'estado'=>1,
                 'track'=>$request->input('track'),

                 'pais'=>$request->input('pais'),
                 'region'=>$request->input('region'),
                 'tipo_documento_documento_id'=>$request->input('cboTipDoc'),
                 'news'=>$request->input('check_newsletter'),
                 'tipo_id'=>$request->input('tipo_id'),
                 'ip'=>request()->ip(),
                 'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),

            ]);
        }

        if(!is_null($request->input('check_newsletter'))){
            DB::table('newsletters')->insert([
                'estado' => 1,
                'estudiante_id' => $request->input('inputdni'),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
        }

            /* ADD TIPO */
            DB::table('estudiantes_act_detalle')->where('estudiantes_id',$dni_doc)
                                        ->where('eventos_id', session('academico_id'))
                                        ->where('estudiantes_tipo_id', $tipo_estudiante)
                                        ->delete();

            DB::table('estudiantes_act_detalle')->insert([
                'eventos_id'      => session('academico_id'),
                'estudiantes_id'  => mb_strtoupper($request->input('inputdni')),
                'actividades_id'  => 0,
                'estudiantes_tipo_id'=> $tipo_estudiante,
                'confirmado'       => 0,
                'estado'           => 1,
                //'fecha_conf'       => Carbon::now(),
                'dgrupo'           => mb_strtoupper($request->input('grupo')),
                'created_at'       => Carbon::now(),
                'daccedio'         => 'SI',
                'dtrack'           => mb_strtoupper($request->input('track'))
            ]);


        // FLAG TIPO I O P
        $estudiante = Estudiante::where('dni_doc', $dni_doc)->first();

                $dni = $estudiante->dni_doc;
                $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
                $email = $estudiante->email;

        if($rs_datos->auto_conf == 1){

            $flujo_ejecucion = 'CONFIRMACION';
            $asunto = '[CONFIRMACIÓN] '.$rs_datos->nombre_evento;
            $id_plantilla = session('academico_id'); //ID EVENTO
            $plant_confirmacion = $rs_datos->p_conf_registro;
            $plant_confirmacion_2 = $rs_datos->p_conf_registro_2;

            $celular = $estudiante->codigo_cel.$estudiante->celular;
            $dni = $estudiante->dni_doc;
            $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
            $email = $estudiante->email;
           
            $msg_text = $rs_datos->p_conf_registro;// plantila emailp_preregistro_2
            $msg_cel  = $rs_datos->p_conf_registro_2;// plantila whats
            
            if($rs_datos->confirm_email == 1){

                if($email != ""){
                    $email = trim($email);

                    DB::table('historia_email')->insert([
                        'tipo'              =>  'EMAIL',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => $email,
                        'celular'           => "",//$celular,
                        'msg_text'          => $msg_text,
                        'msg_cel'           => "",//$msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);

                }

            
            }else{
                // no inserta en la tb historia_email
                $error .= "No se envío el <strong>email</strong> porque no esta habilitado<br>";

            }

            // MSG WHATS 

            if($rs_datos->confirm_msg == 1){

                if($celular != "" && strlen($estudiante->celular)>= 9){ 

                    $celular = trim($celular);
            
                    DB::table('historia_email')->insert([
                        'tipo'              =>  'WHATS',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => "",//$email,
                        'celular'           => $celular,
                        'msg_text'          => "",//$msg_text,
                        'msg_cel'           => $msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);
                }
                

            }else{
                $error .= "No se envio el <strong>whatsapp</strong> porque no esta habilitado";

            }


        }
                

        Cache::flush();
        
        if($error){
            return redirect()->back()->with('alert', $error);
        }

        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        return redirect()->route('est.index');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        $eventos_id = session('eventos_id');

        $tipos = DB::table('estudiantes_tipo')->get();
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $tipo_doc = TipoDoc::all();
        $grupos = DB::table('est_grupos')->get();

        //$estudiantes_datos = DB::table('estudiantes')->where('id', $id)->first();
        //$order = Order::findOrFail($orderId);
        $estudiantes_datos = Estudiante::findOrFail($id);

        $distrito = $estudiantes_datos->ubigeo_ubigeo_id;

        $dis = substr($distrito,0,4);

        $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis.'%','id2' => $dis]);

        $prov = substr($distrito,0,2);
        $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov.'%','id2' => $prov,'id3' => 4]);

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        return view('est.show',compact('estudiantes_datos','tipo_doc', 'countrys', 'tipos','departamentos_datos','grupos','eventos_id'));
        //return view('leads.show',compact('estudiantes_datos','tipo_doc', 'countrys', 'tipos','departamentos_datos','provincias_datos','distritos_datos','prov','dis'));
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
        if(!isset( session("permisosTotales")["academico"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('academico_id') == false){
            return redirect()->route('academico.index');
        }

        $eventos_id = session('academico_id');

        $tipos = DB::table('estudiantes_tipo')->get();
        $tipo_doc = TipoDoc::all();
        Cache::flush();

        $estudiantes_datos = DB::table('estudiantes as e')
                                ->join('estudiantes_act_detalle as de','de.estudiantes_id','=','e.dni_doc')
                                ->select('e.id','e.tipo_documento_documento_id','e.dni_doc','e.ap_paterno', 'e.ap_materno', 'e.nombres','e.pais','e.region','e.ubigeo_ubigeo_id', 'de.dgrupo as grupo', 'de.estudiantes_tipo_id as tipo_id','e.profesion','e.organizacion', 'e.cargo', 'e.email', 'e.celular', 'e.codigo_cel', 'e.telefono','de.daccedio','de.dgrupo','de.dtrack', 'de.estado')
                                ->where('e.id',$id)
                                ->where('de.eventos_id',session('academico_id'))
                                ->first();

        
        $distrito = $estudiantes_datos->ubigeo_ubigeo_id;
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $grupos = DB::table('est_grupos')->get();


        $dis = substr($distrito,0,4);

        $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis.'%','id2' => $dis]);

        $prov = substr($distrito,0,2);
        $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov.'%','id2' => $prov,'id3' => 4]);

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();


        // BLOQUEO DE IMPORT / DE BAJA/ REENVIAR INVITACIÓN
            $rs_datos = DB::table('eventos as e')
                            ->where('e.id',session('academico_id'))
                            ->orderBy('e.id', 'desc')
                            ->count();

            if($rs_datos == 0){
                alert()->warning('Elimine el registro.','Error')->persistent('Close');
                return redirect()->route('eventos.index');
            }

            $rs_datos = DB::table('eventos as e')
                            ->where('e.id',session('academico_id'))
                            ->orderBy('e.id', 'desc')
                            ->first();

            $n_e = estudiantes_act_detalle::where('eventos_id', session('academico_id'))->count();

            $evento_vencido = "";

            if($n_e >= $rs_datos->vacantes){
                $evento_vencido = 1;
            }else{
                $evento_vencido = 0;
            }

            $datos_h = Estudiante::
                                join('estudiantes_act_detalle as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
                                ->join('eventos as e','de.eventos_id','=','e.id')
                                ->where('estudiantes.dni_doc', '=', $estudiantes_datos->dni_doc)
                                ->select('e.id','e.nombre_evento','e.fechai_evento','e.fechaf_evento','e.gafete')
                                ->orderBy('e.id','desc')
                                ->get();

            $datos_act = Estudiante::join('actividades_estudiantes as de', 'de.estudiantes_id','=','estudiantes.dni_doc')   
                        ->join('actividades as a','a.id','=','de.actividad_id')
                        ->where('estudiantes.dni_doc', '=', $estudiantes_datos->dni_doc)
                        ->select('a.eventos_id','a.titulo','a.subtitulo','a.vacantes','a.inscritos','a.hora_inicio','a.hora_final')
                        ->orderBy('a.fecha_desde','a.hora_inicio','asc')
                        ->get();

        return view('est.edit',compact('estudiantes_datos','tipo_doc', 'tipos','countrys','departamentos_datos','provincias_datos','distritos_datos','prov','dis','grupos','eventos_id','evento_vencido','datos_h','datos_act'));
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

            'inputdni'=>'required|unique:estudiantes,dni_doc,'.$id,
            'cboTipDoc' => 'required'
        ]);

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

        $xdni = mb_strtoupper($request->input('inputdni'));
        $rs_estudiantes = DB::table('estudiantes')->select('tipo_id','dni_doc')
                            ->where('id',$id)->first();

        $dni_server = $rs_estudiantes->dni_doc;

        $rs_datos = DB::table('eventos')
                            ->where('id',session('academico_id'))
                            ->first();

        if($rs_datos){
            $evento_id = $rs_datos->id;
            $fechai_evento = $rs_datos->fechai_evento;
            $fechaf_evento = $rs_datos->fechaf_evento;
        }else{
            alert()->success('Ingrese a un evento','Alerta');
            return redirect()->route('academico.index');
        }
       
        //Actualizamos 
        DB::table('estudiantes')->where('id',$id)->update([
            'dni_doc'=>mb_strtoupper($request->input('inputdni')),
             'ap_paterno'=>mb_strtoupper($request->input('inputApe_pat')),
             'ap_materno'=>mb_strtoupper($request->input('inputApe_mat')),
             'nombres'=>mb_strtoupper($request->input('inputNombres')),
             'fecha_nac'=>mb_strtoupper($request->input('inputFechaNac')),
             //'grupo'=>mb_strtoupper($request->input('grupo')),
             'cargo'=>mb_strtoupper($request->input('inputCargo')),
             'organizacion'=>mb_strtoupper($request->input('inputOrganizacion')),
             'profesion'=>mb_strtoupper($request->input('inputProfesion')),
             'direccion'=>mb_strtoupper($request->input('inputDireccion')),
             'telefono'=>mb_strtoupper($request->input('telefono')),
             'telefono_labor'=>mb_strtoupper($request->input('inputTelefono_2')),
             'codigo_cel'=>$request->input('codigo_cel'),
             'celular'=>mb_strtoupper($request->input('inputCelular')),
             'email'=>$request->input('inputEmail'),
             'email_labor'=>$request->input('inputEmail_2'),
             'sexo'=>$request->input('cboSexo'),
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now(),
             'estado'=>$request->input('cboEstado'),
             'accedio'=>$request->input('accedio'),
             'track'=>$request->input('track'),
             'pais'=>$request->input('pais'),
             'region'=>$request->input('region'),
             
             'tipo_documento_documento_id'=>$request->input('cboTipDoc'),
             'tipo_id'=>5,
             'news'=>$request->input('check_newsletter'),
             'ip'=>request()->ip(),
             'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
             'entidad'=>$request->input('entidad'),
             'ubigeo_ubigeo_id'=>$request->input('cboDistrito')
        ]);

        DB::table('audi_estudiantes')->insert([
             'id_estudiante'=>$id,//DB::getPdo()->lastInsertId()
             'dni_doc'=>mb_strtoupper($request->input('inputdni')),
             'ap_paterno'=>mb_strtoupper($request->input('inputApe_pat')),
             'ap_materno'=>mb_strtoupper($request->input('inputApe_mat')),
             'nombres'=>mb_strtoupper($request->input('inputNombres')),
             'fecha_nac'=>mb_strtoupper($request->input('inputFechaNac')),
             'grupo'=>mb_strtoupper($request->input('grupo')),
             'cargo'=>mb_strtoupper($request->input('inputCargo')),
             'organizacion'=>mb_strtoupper($request->input('inputOrganizacion')),
             'profesion'=>mb_strtoupper($request->input('inputProfesion')),
             'direccion'=>mb_strtoupper($request->input('inputDireccion')),
             'telefono'=>mb_strtoupper($request->input('telefono')),
             'telefono_labor'=>mb_strtoupper($request->input('inputTelefono_2')),
             'celular'=>mb_strtoupper($request->input('inputCelular')),
             'email'=>$request->input('inputEmail'),
             'email_labor'=>$request->input('inputEmail_2'),
             'sexo'=>$request->input('cboSexo'),
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now(),
             'estado'=>$request->input('cboEstado'),
             'accedio'=>$request->input('accedio'),
             'track'=>$request->input('track'),
             'tipo_documento_documento_id'=>$request->input('cboTipDoc'),
             'ip'=>request()->ip(),
             'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
             'entidad' => $request->input('entidad'),
             'ubigeo_ubigeo_id'     => $request->input('cboDistrito'),
             'accion'  => 'UPDATE',
             'usuario' => Auth::user()->email
        ]);

        /* FALTA CAMBIAR SI ACTUALIZAN EL TIPO DEL PARTICIPANTE*/

        $existe_det = DB::table('estudiantes_act_detalle')
                        ->where('estudiantes_id',$dni_server)
                        ->where('eventos_id',session('academico_id'))
                        ->count();

        if($existe_det > 0){

            $rs_update = DB::table('estudiantes_act_detalle')
                        ->where('estudiantes_id',$dni_server)
                        ->where('eventos_id',session('academico_id'))
                        ->update([
                            'estudiantes_id'     => $xdni,
                            'estudiantes_tipo_id'=> 4,
                            'estado'             => $request->input('cboEstado'),
                            'dgrupo'             => mb_strtoupper($request->input('grupo')),
                            'daccedio'           => mb_strtoupper($request->input('accedio')),
                            'dtrack'             => mb_strtoupper($request->input('track')),
                            'created_at'         => Carbon::now()
                        ]);


        }else{
            DB::table('estudiantes_act_detalle')->insert([
                'eventos_id'      => session('academico_id'),
                'estudiantes_id'  => mb_strtoupper($xdni),
                'actividades_id'  => 0,
                'estudiantes_tipo_id'=> 4,
                'confirmado'       => 0,
                'estado'           => 1,
                //'fecha_conf'       => Carbon::now(),
                'dgrupo'           => mb_strtoupper($request->input('grupo')),
                'daccedio'         => mb_strtoupper($request->input('accedio')),
                'dtrack'           => mb_strtoupper($request->input('track')),
                'created_at'       => Carbon::now()
            ]);
        }

        $e_user = DB::table('users')->where('name',$xdni)->first();

        if(!$e_user){
            DB::table('users')
            ->where('name', $dni_server)
            ->update([
                'name'     => $xdni,
                //'password' => 'A'.$xdni.'Z'
            ]);
        }

        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
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

        Estudiante::where('id',$id)->delete();
        //DB::table('estudiantes')->where('id',$id)->delete();
        return redirect()->route('leads.index');
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["eliminar"]   ) ){  
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
            $est = Estudiante::where('id', $value)->first();
            
            DB::table('audi_estudiantes')->insert([
                 'id_estudiante'=> $est->id,
                 'dni_doc'=>$est->dni_doc,
                 'ap_paterno'=>$est->ap_paterno,
                 'ap_materno'=>$est->ap_materno,
                 'nombres'=>$est->nombres,
                 'fecha_nac'=>$est->fecha_nac,
                 'grupo'=>$est->grupo,
                 'cargo'=>$est->cargo,
                 'organizacion'=>$est->organizacion,
                 'profesion'=>$est->profesion,
                 'direccion'=>$est->direccion,
                 'telefono'=>$est->telefono,
                 'telefono_labor'=>$est->telefono_labor,
                 'celular'=>$est->celular,
                 'email'=>$est->email,
                 'email_labor'=>$est->email_labor,
                 'sexo'=>$est->sexo,
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now(),
                 'estado'=>$est->estado,
                 'accedio'=>$est->accedio,
                 'track'=>$est->track,
                 'tipo_documento_documento_id'=>$est->tipo_documento_documento_id,
                 'ip'=>request()->ip(),
                 'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                 'entidad' => $est->entidad,
                 'ubigeo_ubigeo_id'     => $est->ubigeo_ubigeo_id,
                 'accion'  => 'DELETE',
                 'usuario' => \Auth::user()->email
            ]);

            $xreg = estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                        //->where('eventos_id', session('eventos_id'))
                        ->count();

            if($xreg == 1){
                Estudiante::where('id',$value)->delete();
                estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                    ->where('eventos_id',session('eventos_id'))
                    ->delete();
                DB::table('actividades_estudiantes')->where('estudiantes_id',$est->dni_doc)->delete();
                DB::table('users')->where('name',$est->dni_doc)->delete();
                DB::table('actividades_estudiantes')->where('estudiantes_id',$est->dni_doc)
                    ->where('eventos_id',session('academico_id'))
                    ->delete();
            }else{
                
                estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                    ->where('eventos_id',session('academico_id'))
                    ->delete();
            }

            Cache::flush();
            
        }
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('est.index');
    }

    public function EstudianteImport(Request $request){
        $msg = "Solo se aceptan archivos XLS, XLSX y CSV. ";
        $results = [];
        if($request->hasFile('file')){

            $filesd = glob(base_path('storage\excel\*')); //get all file names
            //dd($filesd);
            foreach($filesd as $filed){
                if(is_file($filed))
                unlink($filed); //delete file
            }

            //$file = $request->file('file')->getClientOriginalName();
            $file     = $request->file('file');
        
            $fileog   = $file->getClientOriginalName();

            $filename = pathinfo($fileog, PATHINFO_FILENAME);
            $extension = pathinfo($fileog, PATHINFO_EXTENSION);
            $extension = trim($extension);
            //if(! $extension!="xls" || $extension!="xlsx" || $extension!="csv") ; 
            if( $extension!="xlsx" && $extension!="csv" && $extension!="xls" )  
            {
                return \Response::json(['titulo' => "Solo se aceptan archivos XLS, XLSX y CSV.", 'error' => $msg], 404);
                exit;    
            }

            \Config::set('excel.import.encoding.input', 'iso-8859-1'); 
            \Config::set('excel.import.encoding.output', 'iso-8859-1');

            $reader = \Excel::selectSheetsByIndex(0)->load($request->file('file')->getRealPath())->formatDates( true, 'd/m/Y' );
    
            $results = $reader->noHeading()->get()->toArray();   //this will convert file to array
            //$file->move( base_path('storage\excel'),"estudiantes.".$extension );
            $file->move( base_path('storage\excel'),"estudiantes.xlsx");
           
        } 

        return $results;

    }

    public function EstudianteImportSave(Request $request){
        //$arch_excel = base_path('\storage\excel')."\estudiantes"
        $file_path = base_path('storage\excel');
        $directory = $file_path; 
        $file_exc = scandir ($directory)[2];
        
        \Config::set('excel.import.encoding.input', 'iso-8859-1'); 
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        $reader = \Excel::selectSheetsByIndex(0)->load($file_path ."/". $file_exc)->formatDates( true, 'd/m/Y' );
        $data_exc = $reader->noHeading()->get()->toArray();

        $flagC = $request["chkPrimeraFila"];
        $chkE_invitacion= $request["chkE_invitacion"];
        if($flagC!=""){
            $contF = 0;
        }else{
            $contF = 1;
        }

        DB::table('estudiantes_temp')->delete();
        DB::table('estudiantes_caii')->delete();

        /*$xevento = 0;
        foreach ($data_exc as $lst) {
            if($contF>0){
                $xevento = 0;
                for($x = 1; $x <= $request["totCol"] ; $x++){
                }
            }
        }
        if($xevento > 0){
            // creo bloque
            DB::table('bloques')->insert([
                'fecha'        => Carbon::now(),
                'eventos_id'   => $xevento
            ]);
            $bloques_id = DB::getPdo()->lastInsertId();
        }*/

        //recorre el archivo excel abierto
        foreach ($data_exc as $lst) {
            $est_delete = 0;
            
            if($contF>0){
                // recorre los combos seleccionados
                $estTemp = new EstudianteTemp();
                $tipo_xid = 4;//tipo estudiante
                $estTemp->tipo_id = $tipo_xid;
                $dniT = "";
                $nomT = "";
                $appT = "";
                $apmT = "";
                $grupoT = "";
                $fecnT = "";
                $cargT = "";
                $profT = "";
                $dirT = "";
                $telT = "";
                $celT = "";
                $mailT = "";
                $mailT_2 = "";
                $sexT = "";
                $orgT = "";
                $entT = 0;
                $eventos_idT = session('academico_id');//"";
                $paisT = "";
                $regionT = "";
                $orgT = "";

                $estudiantes_det = new estudiantes_act_detalle();
                $cod_programacionD = 0;
                $cod_estudiantesD = 0;
                
                for($x = 1; $x <= $request["totCol"] ; $x++){
                    
                    if($request["cmbOrganizar".$x]==1){
                        $estTemp->dni_doc = $lst[$x - 1];
                        $dniT = $lst[$x - 1];

                        $estTemp->dni_doc = trim($estTemp->dni_doc);
                        $dniT = trim($dniT);
                    }

                    if($request["cmbOrganizar".$x]==2){
                        $estTemp->nombres = mb_strtoupper($lst[$x - 1]);
                        $nomT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==3){
                        $estTemp->ap_paterno = mb_strtoupper($lst[$x - 1]);
                        $appT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==4){
                        $estTemp->ap_materno = mb_strtoupper($lst[$x - 1]);
                        $apmT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==5){
                        //$estTemp->fecha_nac = $lst[$x - 1]; $fecnT = $lst[$x - 1];
                        $estTemp->grupo = mb_strtoupper($lst[$x - 1]);
                        $grupoT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==6){
                        $estTemp->cargo = mb_strtoupper($lst[$x - 1]);
                        $cargT = $lst[$x - 1];
                    }
                    
                    if($request["cmbOrganizar".$x]==7){
                        $estTemp->profesion = mb_strtoupper($lst[$x - 1]);
                        $profT = $lst[$x - 1];
                    }
                    
                    if($request["cmbOrganizar".$x]==8){
                        $estTemp->direccion = $lst[$x - 1];
                        $dirT = $lst[$x - 1]; 
                    }
                    
                    if($request["cmbOrganizar".$x]==9){
                        $estTemp->telefono = $lst[$x - 1];
                        $telT = $lst[$x - 1];
                    }
                    
                    if($request["cmbOrganizar".$x]==10){
                        
                        if($lst[$x - 1] == ""){
                            $estTemp->codigo_cel = '';
                            $estTemp->celular = $lst[$x - 1];
                            $celT = $lst[$x - 1];
                        }else{
                            $estTemp->codigo_cel = '51';
                            $estTemp->celular = trim($lst[$x - 1]);
                            $celT = trim($lst[$x - 1]);
                        }
                    }
                    
                    if($request["cmbOrganizar".$x]==11){
                        
                        if($lst[$x - 1] == ""){
                            $estTemp->email = $lst[$x - 1];
                            $mailT = $lst[$x - 1];
                        }else{
                            $estTemp->email = trim($lst[$x - 1]);
                            $mailT = trim($lst[$x - 1]);

                            // SE QUITA SI TIENE DOS O MAS EMAILS CON ESPACIO
                            $d_email = $estTemp->email;
                            $email_partes = explode(" ", $d_email);
                            $estTemp->email = $email_partes[0];
                            $mailT = $email_partes[0];
                        }
                    }

                    
                    if($request["cmbOrganizar".$x]==12){
                        $estTemp->sexo = $lst[$x - 1];
                        $sexT = $lst[$x - 1];
                    }

                    /*if($request["cmbOrganizar".$x]==13){
                        $entTv = $lst[$x - 1]; 
                        $entTv = trim($entTv);
                        $entidadTemp = DB::table('entidades')->where("entidad",$entTv)->first();
                        if($entidadTemp){
                            $entT = $entidadTemp->id;
                            $estTemp->idEntidad = $entT;
                        }                        
                    }

                    if($request["cmbOrganizar".$x]==14){
                        $estudiantes_det->academico_id = $lst[$x - 1];
                        $estTemp->codigo_prog = $lst[$x - 1];
                        $eventos_idT = $lst[$x - 1];
                    }*/
                    if($request["cmbOrganizar".$x]==15){
                        $estTemp->email_labor = $lst[$x - 1];
                        $mailT_2 = $lst[$x - 1];
                    }
                    if($request["cmbOrganizar".$x]==16){
                        $estTemp->organizacion = mb_strtoupper($lst[$x - 1]);
                        $orgT = $lst[$x - 1];
                    }
                    if($request["cmbOrganizar".$x]==17){
                        $estTemp->pais = mb_strtoupper($lst[$x - 1]);
                        $paisT = $lst[$x - 1];
                    }
                    if($request["cmbOrganizar".$x]==18){
                        $estTemp->region = mb_strtoupper($lst[$x - 1]);
                        $regionT = $lst[$x - 1];
                    }

                }

                $si_evento = DB::table('eventos')->where('id',$eventos_idT)->count();

                if($si_evento == 0){
                    return "error_no_evento";
                }         

                $flagPASA = 0;
                $flagPASAdni = 1;
                $flagPASAcel = 1;
                //VALIDA FORMATO DE DNI
                /*if(preg_match('#[^0-9]#',$dniT)){
                    $flagPASAdni = 0;
                }*/

                if(strlen($dniT)<4){
                    $flagPASAdni = 0;   
                }

                if(preg_match('#[^0-9]#',$celT)){
                    $flagPASAcel = 0;
                }
                /*
                //validacion celular 
                if(strlen($celT)!=9){
                    $flagPASAcel = 0;   
                }
                
                if($flagPASAcel == 0){
                    echo $dniT . ' = ' .$celT;
                }
                exit;*/
                //echo $dniT." - ".$flagPASAdni;exit;
                /*if (!ctype_digit($dniT)) {
                    echo "Contains non-numbers."; 
                }else{
                    echo "not Contains non-numbers.";
                }
                exit;*/
                //if( is_numeric((int)$dniT) && strlen($dniT)==8){


                if($flagPASAdni == 1){
                    $verEst = Estudiante::where("dni_doc",$dniT)->first();

                    if(!($verEst)){
                        if($mailT!=""){
                            $verMail = Estudiante::where("email",$mailT)->first(); // validar
                            //if(!($verMail)){
                                $estTemp->repetido=0;
                                $estTemp->mensaje="<span style='color:#18e237'>Lead importado</span>";
                                //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                                if($fecnT!=""){
                                    if($this->validar_fecha_espanol($fecnT)){ 
                                        $flagPASA = 1;
                                    }else{
                                        $estTemp->repetido=1;
                                        $estTemp->mensaje="<span style='color:red'>Formato de Fecha Incorrecto, debe ser dd/mm/yyyy</span>";
                                    }
                                    
                                }else{
                                    $flagPASA = 1;
                                }
                                
                            /*}else{
                                $estTemp->repetido=1;
                                $estTemp->mensaje="<span style='color:red'>EMAIL ya se encuentra registrado</span>";
                            }*/                        
                        }else{
                            $estTemp->repetido=0;
                            $estTemp->mensaje="<span style='color:#18e237'>Registro importado</span>";
                            //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                            if($fecnT!=""){
                                if($this->validar_fecha_espanol($fecnT)){
                                    $flagPASA = 1;
                                }else{
                                    $estTemp->repetido=1;
                                    $estTemp->mensaje="<span style='color:red'>Formato de Fecha Incorrecto, debe ser dd/mm/yyyy</span>";
                                }
                                
                            }else{
                                $flagPASA = 1;
                            }
                        }// end $verEst
                    }else{
                        $estTemp->repetido=1;
                        //$estTemp->mensaje="<span style='color:red'>DNI ya se encuentra registrado</span>";
                        $estTemp->mensaje="<span style='color:red'>Registro grabado, DNI existente</span>";
                        // CONDICIONAL DE ACTUALIZACION
                        $colEst1 = 0;
                        if(trim($verEst->nombres)!="" ){$colEst1++;}
                        if(trim($verEst->ap_paterno)!="" ){$colEst1++;}
                        if(trim($verEst->ap_materno)!="" ){$colEst1++;}
                        if(trim($verEst->fecha_nac)!="" ){$colEst1++;}
                        if(trim($verEst->cargo)!="" ){$colEst1++;}
                        if(trim($verEst->profesion)!="" ){$colEst1++;}
                        if(trim($verEst->direccion)!="" ){$colEst1++;}
                        if(trim($verEst->telefono)!="" ){$colEst1++;}
                        if(trim($verEst->celular)!="" ){$colEst1++;}
                        if(trim($verEst->email)!="" ){$colEst1++;}
                        if(trim($verEst->email_labor)!="" ){$colEst1++;}
                        if(trim($verEst->sexo)!="" ){$colEst1++;}
                        // borrar entidades
                        //if((int)$verEst->entidades_entidad_id!=0){$colEst1++;}
                        if((int)$verEst->codigo_prog!=""){$colEst1++;}
                        if((int)$verEst->pais!=""){$colEst1++;}
                        if((int)$verEst->region!=""){$colEst1++;}
                        if((int)$verEst->organizacion!=""){$colEst1++;}
                        //if(trim($verEst->ubigeo_ubigeo_id)!=""){$colEst1++;}

                        $colEst2 = 0;
                        //if($dniT != ""){$colEst2++;}
                        if($nomT != ""){$colEst2++;}
                        if($appT != ""){$colEst2++;} 
                        if($apmT != ""){$colEst2++;}
                        if($fecnT != ""){$colEst2++;}
                        if($grupoT != ""){$colEst2++;}
                        if($cargT != ""){$colEst2++;}
                        if($profT != ""){$colEst2++;}
                        if($dirT != ""){$colEst2++;}
                        if($telT != ""){$colEst2++;}
                        if($celT != ""){$colEst2++;}
                        if($mailT != ""){$colEst2++;}
                        if($sexT != ""){$colEst2++;}
                        if($eventos_idT != ""){$colEst2++;}
                        if($mailT_2 != ""){$colEst2++;}
                        if($orgT != ""){$colEst2++;}
                        if($paisT != ""){$colEst2++;}
                        if($regionT != ""){$colEst2++;}
                        //$eventos_idT
                        //if((int)$entT != 0){$colEst2++;}
                        
                        //echo $dniT."<br>";
                        //echo $colEst1."<br>";
                        //echo $colEst2."<br>";exit; 
                        //si las columnas del excel es igual o mayor a las columas de la BD actualizar la fila
                        if($colEst2 >= $colEst1){
                            $verEst->nombres = mb_strtoupper($nomT);
                            $verEst->dni_doc = $dniT;
                            $verEst->ap_paterno = mb_strtoupper($appT);
                            $verEst->ap_materno  = mb_strtoupper($apmT);
                            //$verEst->grupo  = mb_strtoupper($grupoT);
                            $verEst->fecha_nac = $fecnT;
                            $verEst->cargo = mb_strtoupper($cargT);
                            $verEst->profesion = mb_strtoupper($profT);
                            $verEst->direccion = mb_strtoupper($dirT);
                            $verEst->telefono = $telT;
                            if($celT !== ""){$verEst->codigo_cel = '51';}
                            
                            $verEst->celular = $celT;
                            $verEst->email = $mailT;
                            $verEst->email_labor = $mailT_2;
                            $verEst->sexo = $sexT;
                            $verEst->accedio = 'SI';
                            $verEst->organizacion = mb_strtoupper($orgT);
                            $verEst->pais = mb_strtoupper($paisT);
                            $verEst->region = mb_strtoupper($regionT);
                            //$verEst->codigo_prog = $eventos_idT;
                            //$verEst->tipo_documento_documento_id = 1;
                            $verEst->tipo_id = $tipo_xid; // TIPO invitados

                            // BORRAR ENTIDAD
                            //if((int)$entT != 0){$verEst->entidades_entidad_id = $entT;}                            
                            $verEst->save();

                            $estTemp->repetido=0;
                            $estTemp->mensaje="<span style='color:#18e237'>Lead ACTUALIZADO.</span>";

                            // Registro de listas
                            /*if($xevento > 0){
                                DB::table('bloques_detalle')->insert([
                                    'bloques_id'        => $bloques_id,
                                    'estudiantes_id'    => $dniT
                                ]);
                            }*/

                        } //END colEst2

                        // SAVE INVITACION SI = chkE_invitacion == 1
                        // grabar detalle estudiantes //considerar borrar la prog subida por error
                        $error = '';

                        // VERIFICAR SI EL PARTICIPANTE YA ESTA REGISTRADO.
                            $est_det_cant = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                                            ->where('eventos_id',$eventos_idT)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->count();

                            // estado: accedio = SI -> Ya no envia ni guarda

                            //$est_delete = estudiantes_act_detalle::where('estudiantes_id',$dniT)->where('eventos_id',$eventos_idT)->delete();

                            $check_stado = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                                            ->where('eventos_id',$eventos_idT)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->where('daccedio', 'SI')
                                            ->count(); // FALTA VALIDAR 

                            if($est_det_cant >= 1){

                                $check_stado = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                                            ->where('eventos_id',$eventos_idT)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->where('daccedio', 'SI')
                                            ->update([
                                                'dgrupo'    => mb_strtoupper($grupoT),
                                                'estado'    => 1,
                                                'daccedio'  => 'SI'
                                            ]); 

                                DB::table('estudiantes_caii')->insert([
                                     'dni_doc'=> $dniT,
                                     'ap_paterno'=> $appT,
                                     'ap_materno'=> $apmT,
                                     'nombres'=> $nomT,
                                     'profesion'=> $profT,
                                     'cargo'=> $cargT,
                                     'organizacion'=> $orgT,
                                     'celular'=> $celT,
                                     'email'=> $mailT,
                                     'mensaje' => "<span style='color:#18e237'>Ya se ha importado</span>"
                                ]);

                            }else{

                                // SI EL ESTADO ES "NO", SE ENVIA INVITACION
                                if($check_stado == 1){
                                    
                                }else{
                                    // agregue
                                    $detalle_estud = new estudiantes_act_detalle();
                                    $detalle_estud->estudiantes_id = $dniT;
                                    $detalle_estud->eventos_id = $eventos_idT;
                                    $detalle_estud->actividades_id = 0 ;//$idAct;
                                    $detalle_estud->estudiantes_tipo_id = $tipo_xid;
                                    $detalle_estud->estado = 1;
                                    $detalle_estud->confirmado = 0;
                                    //$detalle_estud->fecha_conf = Carbon::now();
                                    $detalle_estud->daccedio = 'SI';
                                    $detalle_estud->dgrupo = mb_strtoupper($grupoT);
                                    $detalle_estud->dtrack = '';
                                    $detalle_estud->cambio_tipo = $est_delete;
                                    $detalle_estud->created_at = Carbon::now();
                                    $detalle_estud->save();
                                }

                            }


                            if($chkE_invitacion == 1 AND $check_stado == 0){

                                $rs_datos = DB::table('eventos as e')
                                    ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                                    ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                                    ->where('e.id',$eventos_idT)//session('eventos_id')
                                    ->orderBy('e.id', 'desc')
                                    ->first();

                                if(!$rs_datos){
                                    return "error_no_plantilla";
                                }

                                $flujo_ejecucion = 'INVITACION';
                                $asunto = '[INVITACIÓN] '.$rs_datos->nombre_evento;
                                $id_plantilla = $eventos_idT; //session('eventos_id'); //ID EVENTO
                                $plant_confirmacion = $rs_datos->p_conf_inscripcion;
                                $plant_confirmacion_2 = $rs_datos->p_conf_inscripcion_2;

                                $celular = $celT;
                                $dni = $dniT;
                                $nom = mb_strtoupper($nomT) .' '.mb_strtoupper($appT).' '.mb_strtoupper($apmT);
                                $email = $mailT;
                               
                                $msg_text = $rs_datos->p_conf_inscripcion;// html email
                                $msg_cel  = $rs_datos->p_conf_inscripcion_2;// html whats

                                if($rs_datos->confirm_email == 1){

                                    if($email != ""){
                                        $email = trim($email);

                                        DB::table('historia_email')->insert([
                                            'tipo'              =>  'EMAIL',
                                            'fecha'             => Carbon::now(),
                                            'estudiante_id'     => $dni,
                                            'plantillaemail_id' => $id_plantilla,
                                            'flujo_ejecucion'   => $flujo_ejecucion,
                                            'eventos_id'        => $id_plantilla,
                                            'fecha_envio'       => '2000-01-01',
                                            'asunto'            => $asunto,
                                            'nombres'           => $nom,
                                            'email'             => $email,
                                            'celular'           => '',//$celular,
                                            'msg_text'          => $msg_text,
                                            'msg_cel'           => '',//$msg_cel,
                                            'created_at'        => Carbon::now(),
                                            'updated_at'        => Carbon::now()
                                        ]);

                                    }
                                
                                }else{
                                    // no inserta en la tb historia_email
                                    $error .= 'No se envio el email porque no esta habilitado';

                                }

                                // MSG WHATS 

                                if($rs_datos->confirm_msg == 1){

                                    if($celular != "" && strlen($celular)>= 5){ 
                                        $celular = trim($celT);
                                        $celular = "51".$celular;
                                
                                        DB::table('historia_email')->insert([
                                            'tipo'              =>  'WHATS',
                                            'fecha'             => Carbon::now(),
                                            'estudiante_id'     => $dni,
                                            'plantillaemail_id' => $id_plantilla,
                                            'flujo_ejecucion'   => $flujo_ejecucion,
                                            'eventos_id'        => $id_plantilla,
                                            'fecha_envio'       => '2000-01-01',
                                            'asunto'            => $asunto,
                                            'nombres'           => $nom,
                                            'email'             => '',//$email,
                                            'celular'           => $celular,
                                            'msg_text'          => '',//$msg_text,
                                            'msg_cel'           => $msg_cel,
                                            'created_at'        => Carbon::now(),
                                            'updated_at'        => Carbon::now()
                                        ]);
                                    }
                                    
                                }else{
                                    $error .= 'No se envio el whatsapp porque no esta habilitado';
                                }
                                
                            } //chkE_invitacion

                    }
                }else{
                    $estTemp->repetido=1;//y tener 8 dígitos
                    $estTemp->mensaje="<span style='color:red'>DNI debe ser numérico </span>";
                }
                if($flagPASA==1){
                    // CREA EL NUEVO ESTUDIANTE
                    $estudiante = new Estudiante();            
                    $estudiante->nombres = mb_strtoupper($nomT);
                    $estudiante->dni_doc = $dniT;
                    $estudiante->ap_paterno = mb_strtoupper($appT);
                    $estudiante->ap_materno  = mb_strtoupper($apmT);
                    //despues quitar
                    //$estudiante->grupo = mb_strtoupper($grupoT);
                    $estudiante->fecha_nac = $fecnT;
                    $estudiante->cargo = mb_strtoupper($cargT);
                    $estudiante->profesion = mb_strtoupper($profT);
                    $estudiante->direccion = mb_strtoupper($dirT);
                    $estudiante->telefono = $telT;
                    if($celT !== ""){$estudiante->codigo_cel = '51';}
                    
                    $estudiante->celular = $celT;
                    $estudiante->email = $mailT;
                    $estudiante->email_labor = $mailT_2;
                    $estudiante->sexo = $sexT;
                    $estudiante->organizacion = mb_strtoupper($orgT);
                    $estudiante->pais = mb_strtoupper($paisT);
                    $estudiante->region = mb_strtoupper($regionT);
                    $estudiante->tipo_documento_documento_id = 1;
                    $estudiante->estado  = 1;
                    $estudiante->accedio = 'SI';
                    $estudiante->tipo_id = $tipo_xid;
                    // BORRAR ENTIDADES
                    //if((int)$entT != 0){$estudiante->entidades_entidad_id = $entT;}
                    $estudiante->save();

                    // Registro de listas
                    /*if($xevento > 0){
                        DB::table('bloques_detalle')->insert([
                            'bloques_id'        => $bloques_id,
                            'estudiantes_id'    => $dniT
                        ]);
                    }*/

                    // GUARDAMOS EN audi_estudiantes
                    DB::table('audi_estudiantes')->insert([
                         'id_estudiante'=> $estudiante->id,
                         'dni_doc'=> $dniT,
                         'ap_paterno'=> $appT,
                         'ap_materno'=> $apmT,
                         'nombres'=> $nomT,
                         'fecha_nac'=> $fecnT,
                         'grupo'=> $grupoT,
                         'cargo'=> $cargT,
                         'organizacion'=> $orgT,
                         'profesion'=> $profT,
                         'direccion'=> $dirT,
                         'telefono'=> $telT,
                         //'telefono_labor'=> $,
                         'celular'=> $celT,
                         'email'=> $mailT,
                         'email_labor'=> $mailT_2,
                         'sexo'=> $sexT,
                         'created_at'=> Carbon::now(),
                         'updated_at'=> Carbon::now(),
                         'estado'=> 1,
                         'tipo_documento_documento_id'=> 1,
                         'ip'=>request()->ip(),
                         //'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                         //'entidad' =>  '',
                         'ubigeo_ubigeo_id'     =>  '',
                         'accion'  => 'INSERT',
                         'usuario' => Auth::user()->email
                    ]);

                    // end audi_estudiantes

                    $estTemp->idAlumno = $estudiante->id;

                    // grabar detalle estudiantes
                    $detalle = new estudiantes_act_detalle();
                    $detalle->estudiantes_id = $dniT;
                    $detalle->eventos_id = $eventos_idT;
                    $detalle->actividades_id = 0;//$idAct;
                    $detalle->estudiantes_tipo_id = $tipo_xid;
                    $detalle->estado = 1;
                    $detalle->confirmado = 0;
                    //$detalle->fecha_conf = Carbon::now();
                    $detalle->daccedio = 'SI';
                    $detalle->dgrupo = mb_strtoupper($grupoT);
                    $detalle->dtrack = '';
                    $detalle->cambio_tipo = $est_delete;
                    $detalle->created_at = Carbon::now();
                    $detalle->save();

                    $error = '';

                    // SAVE INVITACION SI = chkE_invitacion == 1
                    if($chkE_invitacion == 1){

                        $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',$eventos_idT)//session('eventos_id')
                            ->orderBy('e.id', 'desc')
                            ->first();

                        
                        if(!$rs_datos){
                            return "error_no_plantilla";
                        }

                        $flujo_ejecucion = 'INVITACION';
                        $asunto = '[INVITACIÓN] '.$rs_datos->nombre_evento;
                        $id_plantilla = $eventos_idT;//session('eventos_id'); //ID EVENTO
                        $plant_confirmacion = $rs_datos->p_conf_inscripcion;
                        $plant_confirmacion_2 = $rs_datos->p_conf_inscripcion_2;

                        $celular = $celT;
                        $dni = $dniT;
                        $nom = mb_strtoupper($nomT) .' '.mb_strtoupper($appT).' '.mb_strtoupper($apmT);
                        $email = $mailT;
                       
                        $msg_text = $rs_datos->p_conf_inscripcion;// html email
                        $msg_cel  = $rs_datos->p_conf_inscripcion_2;// html whats

                        if($rs_datos->confirm_email == 1){

                                    if($email != ""){
                                        $email = trim($email);

                                        DB::table('historia_email')->insert([
                                            'tipo'              =>  'EMAIL',
                                            'fecha'             => Carbon::now(),
                                            'estudiante_id'     => $dni,
                                            'plantillaemail_id' => $id_plantilla,
                                            'flujo_ejecucion'   => $flujo_ejecucion,
                                            'eventos_id'        => $id_plantilla,
                                            'fecha_envio'       => '2000-01-01',
                                            'asunto'            => $asunto,
                                            'nombres'           => $nom,
                                            'email'             => $email,
                                            'celular'           => '',//$celular,
                                            'msg_text'          => $msg_text,
                                            'msg_cel'           => '',//$msg_cel,
                                            'created_at'        => Carbon::now(),
                                            'updated_at'        => Carbon::now()
                                        ]);

                                    }

                                
                                }else{
                                    // no inserta en la tb historia_email
                                    $error .= 'No se envio el email porque no esta habilitado';

                                }

                                // MSG WHATS 

                                if($rs_datos->confirm_msg == 1){

                                    if($celular != "" && strlen($estudiante->celular)>= 5){
                                        $celular = trim($celT);
                                        $celular = "51".$celular;
                                
                                        DB::table('historia_email')->insert([
                                            'tipo'              =>  'WHATS',
                                            'fecha'             => Carbon::now(),
                                            'estudiante_id'     => $dni,
                                            'plantillaemail_id' => $id_plantilla,
                                            'flujo_ejecucion'   => $flujo_ejecucion,
                                            'eventos_id'        => $id_plantilla,
                                            'fecha_envio'       => '2000-01-01',
                                            'asunto'            => $asunto,
                                            'nombres'           => $nom,
                                            'email'             => '',//$email,
                                            'celular'           => $celular,
                                            'msg_text'          => '',//$msg_text,
                                            'msg_cel'           => $msg_cel,
                                            'created_at'        => Carbon::now(),
                                            'updated_at'        => Carbon::now()
                                        ]);
                                    }
                                    

                                }else{
                                    $error .= 'No se envio el whatsapp porque no esta habilitado';

                                }

                    }   
                }
                // fin NEW ESTUD. AAAAAAAA

                $estTemp->save();
            }
            $contF++;
            
            Cache::flush();
        }

    }
    
    public function EstudianteImportResults() {
        $nlista = EstudianteTemp::count();
        $lista = EstudianteTemp::select("id",
                               "dni_doc",
                               "nombres",
                               "ap_paterno",
                               "ap_materno",
                               "grupo",
                               "fecha_nac",
                               "organizacion",
                               "cargo",
                               "profesion",
                               "direccion",
                               "telefono",
                               "telefono_labor",
                               "celular",
                               "email",
                               "email_labor",
                               "sexo",
                               "idEntidad",
                               "mensaje",
                               "idAlumno",
                               "codigo_prog",
                               "pais", "region"
                            )
                    ->orderBy("id","ASC")
                    ->get();

        if(count($lista)==0){
            die("No hay registros");
        }
        $vEnt = 0;
        foreach ($lista as $lstT) {
            if($lstT->idEntidad!=0){$vEnt=1;}
        }
        
        return view("leads.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'nlista'=>$nlista]);
    }

    public function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }
    
    public function search(Request $request){
        if($request->ajax()){
            $dato='in here';
            return Response::json($dato);
        }
    }

    // Enviar Invitación email y msg
    public function solicitud($id, $dni, $evento, $tipo){
       
        //return "dni: $dni evento: $evento tipo: $tipo";
        $msg = "";
        $msg_tipo = "warning";
        $msg_color = "#d2910d";

        $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id', $evento)
                            ->orderBy('e.id', 'desc')
                            ->count();

        if($rs_datos==0){
            $msg = "Ingrese a la sección EVENTOS e ingrese a un evento. ";
            $msg_tipo = "error";
            $msg_color = "#c12222";

            $respuesta = array(
                'msg'   => $msg,
                'tipo'  => $msg_tipo,
                'color'  => $msg_color
            );

            return $respuesta;
        }

        $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id', $evento)
                            ->orderBy('e.id', 'desc')
                            ->first();

        // validar por fecha de evento
        
        $f_limite = \Carbon\Carbon::parse($rs_datos->fechaf_evento);
        $hoy = Carbon::now();

        //return "fecha_limite: $f_limite - hoy: $hoy";
        
        // CIERRE DE FORM 
        if($hoy->greaterThan($f_limite)){

            $msg = "EVENTO FINALIZADO";
            $msg_tipo = "error";
            $msg_color = "#c12222";

            $respuesta = array(
                'msg'   => $msg,
                'tipo'  => $msg_tipo,
                'color'  => $msg_color
            );

            return $respuesta;

        }

        
            // DATOS USER
        
            $rs_user = Estudiante::join('users as u','estudiantes.dni_doc','=','u.name')
                        ->select('estudiantes.email','estudiantes.codigo_cel', 'estudiantes.celular','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno')
                        ->where('estudiantes.dni_doc',$dni)
                        ->get();

            $n = count($rs_user);

            if($n == 0){
                $msg = 'El DNI no esta registrado. ';
            }

            if($rs_user[0]->email == ""){
                $msg = "El campo email esta vacio. ";
            }
            if($rs_user[0]->celular =="" || strlen($rs_user[0]->celular) <= 5){
                $msg = "El campo celular esta vacio o no cumple con la cantidad minima de digitos. Cant. min: 9. ";
            }

            if($msg != ""){

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'color'  => $msg_color
                );

                return $respuesta;
            }

            // VALIDAR CHECK DE EMAIL Y WHATSAPP
            

            if($rs_datos->confirm_email != 1){

                $msg .= "El EVENTO no tiene habilitado la opción envio de EMAIL <br>";
                

            }

            if($rs_datos->confirm_msg != 1){

                $msg .= "El EVENTO no tiene habilitado la opción envio de WHATSAPP <br>";
                $msg_val = 1;
                
            }
            /*if($msg_val == 1){
                $msg_tipo = "error";
                $msg_color = "#c12222";

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'color'  => $msg_color
                );

                return $respuesta;
            }*/
        if($tipo == 'preregistro'){

            $msg_val = 0;
            if($rs_datos->p_preregistro == ""){
                $msg = "No existe plantilla para la invitación por email. ";
                $msg_val = 1;
            }
            if($rs_datos->p_preregistro_2 == ""){
                $msg = "No existe plantilla para el mensaje por whatsapp. ";
                $msg_val = 1;
            }

            //if($msg == ""){
            if($msg_val == 0){

                $flujo_ejecucion = 'PREREGISTRO';
                $asunto = '[CONFIRMACIÓN PG]  '.$rs_datos->nombre_evento;
                $id_plantilla = $evento; //ID EVENTO

                $celular = $rs_user[0]->codigo_cel.$rs_user[0]->celular;
                $nom = $rs_user[0]->nombres .' '.$rs_user[0]->ap_paterno.' '.$rs_user[0]->ap_materno;
                $email = $rs_user[0]->email;
               
                $msg_text = $rs_datos->p_preregistro;// html email
                $msg_cel  = $rs_datos->p_preregistro_2;// html whats

                if($rs_datos->confirm_msg == 1){

                    DB::table('historia_email')->insert([
                        'tipo'              =>  'WHATS',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => '',//$email,
                        'celular'           => $celular,
                        'msg_text'          => '',//$msg_text,
                        'msg_cel'           => $msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);

                    
                    $msg .= "PREREGISTRO WHATSAPP: $celular. Se envío correctamente<br>";
                    $msg_tipo = "success";
                    $msg_color = "#058a49";
                }
                
            }
        // end preregistro
        }elseif($tipo == 'invitacion'){

            $msg_val = 0;
            if($rs_datos->p_conf_inscripcion == ""){
                $msg = "No existe plantilla para la invitación por email";
                $msg_val = 1;
            }
            if($rs_datos->p_conf_inscripcion_2 == ""){
                $msg = "No existe plantilla para el mensaje por whatsapp";
                $msg_val = 1;
            }

            //if($msg == ""){
            if($msg_val == 0){

                $flujo_ejecucion = 'INVITACION';
                $asunto = '[INVITACIÓN] '.$rs_datos->nombre_evento;
                $id_plantilla = $evento; //ID EVENTO

                $celular = $rs_user[0]->codigo_cel.$rs_user[0]->celular;
                $nom = $rs_user[0]->nombres .' '.$rs_user[0]->ap_paterno.' '.$rs_user[0]->ap_materno;
                $email = $rs_user[0]->email;
               
                $msg_text = $rs_datos->p_conf_inscripcion;// html email
                $msg_cel  = $rs_datos->p_conf_inscripcion_2;// html whats

                if($rs_datos->confirm_email == 1){

                    DB::table('historia_email')->insert([
                        'tipo'              =>  'EMAIL',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => $email,
                        'celular'           => '',//$celular,
                        'msg_text'          => $msg_text,
                        'msg_cel'           => '',//$msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);

                    //$msg = "Participante: $nom con email: $email y celular: $celular. Se envio correctamente la INVITACIÓN";
                    //$msg .= "Email: $email. Se envío correctamente la INVITACIÓN <br>";
                    $msg .= "INVITACIÓN EMAIL: $email - Se envío correctamente<br>";
                    $msg_tipo = "success";
                    $msg_color = "#058a49";

                }

                if($rs_datos->confirm_msg == 1){

                    DB::table('historia_email')->insert([
                        'tipo'              =>  'WHATS',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => '',//$email,
                        'celular'           => $celular,
                        'msg_text'          => '',//$msg_text,
                        'msg_cel'           => $msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);

                    
                    $msg .= "INVITACIÓN WHATSAPP: $celular. Se envío correctamente<br>";
                    $msg_tipo = "success";
                    $msg_color = "#058a49";

                }

                
            }



        }elseif($tipo == 'confirmacion' or $tipo == 'recordatorio'){

            /*$rs_act = Estudiante::join('actividades_estudiantes as de','estudiantes.dni_doc','=','de.estudiantes_id')
                        ->select('estudiantes.email', 'estudiantes.celular','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno')
                        ->where('estudiantes.dni_doc',$dni)
                        ->where('de.estudiantes_id',$dni)
                        ->where('de.eventos_id', $evento)
                        ->count();

            if($rs_act == 0){
                $msg .= "El participante no ha elegido sus actividades";

                $respuesta = array(
                    'msg'   => $msg,
                    'tipo'  => $msg_tipo,
                    'color'  => $msg_color
                );

                return $respuesta;
            }*/

            $msg_val = 0;
            if($rs_datos->p_conf_registro == ""){
                $msg_val = 1;
                $msg .= "No existe plantilla para la confirmación por email. ";
            }
            if($rs_datos->p_conf_registro_2 == ""){
                $msg_val = 1;
                $msg .= "No existe plantilla para el mensaje por whatsapp. ";
            }

            if($msg_val == 0){

                if($tipo == 'confirmacion'){
                    $flujo_ejecucion = 'CONFIRMACION';
                    $asunto = '[CONFIRMACIÓN] '.$rs_datos->nombre_evento;

                    $msg_text = $rs_datos->p_conf_registro;// html email
                    $msg_cel  = $rs_datos->p_conf_registro_2;// html whats

                }else{
                    $flujo_ejecucion = 'RECORDATORIO';
                    $asunto = '[RECORDATORIO] '.$rs_datos->nombre_evento;

                    $msg_text = $rs_datos->p_recordatorio;// plantila emailp_preregistro_2
                    $msg_cel  = $rs_datos->p_recordatorio_2;// plantila whats
                }
                
                $id_plantilla = $evento; //ID EVENTO

                $celular = $rs_user[0]->codigo_cel.$rs_user[0]->celular;
                $nom = $rs_user[0]->nombres .' '.$rs_user[0]->ap_paterno.' '.$rs_user[0]->ap_materno;
                $email = $rs_user[0]->email;


                if($rs_datos->confirm_email == 1){

                    DB::table('historia_email')->insert([
                        'tipo'              =>  'EMAIL',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => $email,
                        'celular'           => '',//$celular,
                        'msg_text'          => $msg_text,
                        'msg_cel'           => '',//$msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);

                    //$msg = "Participante: $nom con email: $email y celular: $celular. Se envio correctamente la INVITACIÓN";
                    ;
                    if($tipo == 'confirmacion'){
                        $msg .= "CONFIRMACIÓN EMAIL: $email - Se envío correctamente<br>";
                    }else{
                        $msg .= "RECORDATORIO EMAIL: $email - Se envío correctamente<br>";                   
                    }
                    $msg_tipo = "success";
                    $msg_color = "#058a49";

                }

                if($rs_datos->confirm_msg == 1){

                    DB::table('historia_email')->insert([
                        'tipo'              =>  'WHATS',
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     => $dni,
                        'plantillaemail_id' => $id_plantilla,
                        'flujo_ejecucion'   => $flujo_ejecucion,
                        'eventos_id'        => $id_plantilla,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            => $asunto,
                        'nombres'           => $nom,
                        'email'             => '',//$email,
                        'celular'           => $celular,
                        'msg_text'          => '',//$msg_text,
                        'msg_cel'           => $msg_cel,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now()
                    ]);

                    if($tipo == 'confirmacion'){
                        $msg .= "CONFIRMACIÓN WHATSAPP: $celular. Se envío correctamente<br>";

                    }else{
                        $msg .= "RECORDATORIO WHATSAPP: $celular. Se envío correctamente<br>";   
                    }
                    $msg_tipo = "success";
                    $msg_color = "#058a49";

                }

            }


        }else{
            //confirmacion
            $msg = 'Error';
            $msg_tipo = "error";
            $msg_color = "#c12222";

        }

        $respuesta = array(
            'msg'   => $msg,
            'tipo'  => $msg_tipo,
            'color'  => $msg_color
        );

        return $respuesta;
        
    }

    // Enviar Invitación email y msg
    public function enviarInvitacionE(Request $request)
    {
        return 'Datosss';
    }

}
