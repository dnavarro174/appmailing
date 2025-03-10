<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use DB;
use Cache;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use App\Estudiante, App\Evento, App\formMaestria, App\Emails;
use App\TipoDoc;
use App\Departamento;
use App\Provincia;
use App\Distrito;
use App\ConsultaDNI;
use App\Models\Mod_ddjj;
use App\EstudianteTemp;
use App\Ajuste;
use App\AccionesRolesPermisos;
use App\estudiantes_act_detalle;
use App\Exports\EstudianteExport;
use App\Imports\EstudianteImport;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Alert;
use Auth;
use File, PDF;
use App\Repositories\EstudianteRepository;

class EEstudianteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, EstudianteRepository $repository)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        
        if($request->get('eventos_id')){
            Cache::flush();
            $id = $request->get('eventos_id');
            $evento = Evento::findOrFail($id);
            $evento_nom = array('nombre'=>$evento->nombre_evento, 'id'=>$evento->id, 'maestria'=>$evento->lugar);
            session([
                'eventos_id' => $request->get('eventos_id'),
                'evento'     => $evento_nom,
                'evento_tipo'=> $evento->tipo
                 ]);

        }else{
            if(session('eventos_id') == false){
                return redirect()->route('eventos.index');
            }
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
            $permParam["modulo_alias"] = "participantes";//eventos
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

        $tipos  = DB::table('estudiantes_tipo')->get();
        $grupos = DB::table('est_grupos')->get();

        $s = $request->get('s');
        $tipo = ($request->get('tipo'))?$request->get('tipo'):0;
        $tipo = intval($tipo);
        
        if($tipo==8 or $tipo==10){
            $modalidades = DB::table('m4_cursos')
                ->where('evento_id',session('eventos_id'))
                ->where('status',1)
                ->select('modalidad')->groupBy('modalidad')->orderBy('modalidad','ASC')->get();

            $cod_cursos = DB::table('m4_cursos')
                ->where('evento_id',session('eventos_id'))
                ->where('status',1)
                ->select('cod_curso')->groupBy('cod_curso')->orderBy('cod_curso','ASC')->get();
            
            $nom_cursos = DB::table('m4_cursos')
                ->select('nom_curso','id')
                ->where('evento_id',session('eventos_id'))
                ->where('status',1)
                ->groupBy('nom_curso')
                ->orderBy('nom_curso','ASC')
                ->get();

            session(['tipo_dj'=>$tipo]);
        }
        
        $data = array(
            "mod"    => $request->get('mod'),
            "cod"    => $request->get('cod'),
            "cur"    => $request->get('cur'),
            "s"      => $request->get('s'),
            "st"     => $request->get('st'),
            "g"      => $request->get('g'),//accedio
            "reg"    => $request->get('reg'),//aprobado
            "pag"    => $pag,
            "page"   => request('page', 1),
            "sorted" => request('sorted', 'DESC'),
            "eventos_id" => session('eventos_id'),
            "tipo"   => $tipo
        );

        $estudiantes_datos = $repository->search($data);
        
        //ALERTAR al inscriptor
        $evento_vencido = "";
        ;
        if(session('evento_tipo') == 1){

            // BLOQUEO DE IMPORT / DE BAJA/ REENVIAR INVITACIÓN
            $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',session('eventos_id'))
                            ->orderBy('e.id', 'desc')
                            ->count();

            if($rs_datos == 0){
                alert()->warning('Elimine el evento y vuelva a crear el evento, plantillas y formulario.','Error')->persistent('Cerrar');
                // tipo==4 tipo==5 - redireccionar
                $t = request()->get('tipo')??"";
                if($t==4)
                    return redirect()->route('grupo-maestria.index');
                elseif($t==5)
                    return redirect()->route('grupo-estudio-investigacion.index');
                elseif($t==7)
                    return redirect()->route('grupo-maestria.index');
                elseif($t==8)
                    return redirect()->route('grupo-dj.index');
                elseif($t==9)
                    return redirect()->route('grupo-form-doc.index');
                else
                    return redirect()->route('eventos.index');
                
            }

            $rs_datos = Evento::where('id', session('eventos_id'))->select('vacantes','lugar')->first();
            $n_e = estudiantes_act_detalle::where('eventos_id', session('eventos_id'))->count();

            $folder = "";
            $folder = $rs_datos->lugar;

            if($n_e >= $rs_datos->vacantes){
                $evento_vencido = 1;
            }else{
                $evento_vencido = 0;
            }
            
            // PARA SABER DE LOS TIPOS: VER LA LINEA: 125
            if($tipo==4)
                return view('leads.leads_maestria', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos'));
            elseif($tipo==5)
                return view('leads.leads_einvestigacion', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos'));
            elseif($tipo==7)
                return view('leads.leads_eventos-es', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos'));
            elseif($tipo==8 or $tipo==10){
                $array = compact('modalidades','cod_cursos','nom_cursos','folder');
                return view('leads.leads_dj', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos', 'array'));
            }
            elseif($tipo==9)
                return view('leads.leads_doc', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos'));
            else
                return view('leads.leads', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos'));

        }

        return view('leads.leads_virtual', compact('estudiantes_datos','departamentos_datos','tipos','evento_vencido', 'permisos', 'grupos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   $this->actualizarSesion();
       
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["nuevo"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        if($request->eventos_id != ""){
            session(['eventos_id'=> $request->eventos_id]);
        }
        if(session('eventos_id') == false){
            return redirect()->route('eventos.index');
        }

        //primera forma
        //$entidades_datos = DB::table('entidades')->get();
        $tipos = DB::table('estudiantes_tipo')->get();
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $tipo_doc = TipoDoc::all();
        $grupos = DB::table('est_grupos')->whereNotNull('eventos_id')->get();
        //---------------
        /*$departamentos_datos = DB::table('ubigeos')
        ->select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();*/
        //------------------

        //$departamentos_datos = Departamento::pluck('ubigeo_id','nombre');
        //$departamentos_datos = Departamento::all();
        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        return view('leads.create', compact('departamentos_datos','tipo_doc','tipos','countrys','grupos'));

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
            //'inputdni'=>'required|unique:estudiantes,dni_doc',
            'cboTipDoc' => 'required'
            //'inputEmail'=>'required',
        ]);

        $error = "";
        $dni_doc = $request->input('inputdni');
        $existe = $request->input('existe');

        $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',session('eventos_id'))
                            ->orderBy('e.id', 'desc')
                            ->first();

        $tipo = ($request->get('tipo'))?$request->input('tipo'):0;
        $tipo = intval($tipo);

        if($rs_datos){
            $evento_id = $rs_datos->id;
            $fechai_evento = $rs_datos->fechai_evento;
            $fechaf_evento = $rs_datos->fechaf_evento;
        }else{
            alert()->success('Ingrese a un evento','Alerta');
            return redirect()->route('eventos.index');
        }

        $tipo_estudiante = 5;//= ESTUDIANTE $request->input('tipo_id')

        // si existe DNI
        if($existe == 2){
            alert()->warning('Alerta','El participante ya esta registrado.');
            return redirect()->back();
        }
        
        if($existe == 0){

            //agregar contralador db:  use DB; // para poder have insert
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
                                        ->where('eventos_id', session('eventos_id'))
                                        ->where('estudiantes_tipo_id', $tipo_estudiante)
                                        ->delete();

            DB::table('estudiantes_act_detalle')->insert([
                'eventos_id'      => session('eventos_id'),
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

        if($tipo == 2){

            if($rs_datos->auto_conf == 1){

                $flujo_ejecucion = 'CONFIRMACION';
                if(!empty($rs_datos->email_asunto)){
                    $asunto = '[CONFIRMACIÓN] '.$rs_datos->email_asunto;
                    $from = Emails::findOrFail($rs_datos->email_id);
                    $from_email = $from->email;
                    $from_name  = $from->nombre;
                }else{
                    $asunto = '[CONFIRMACIÓN] '.$rs_datos->nombre_evento;
                    $from_email = config('mail.from.address');
                    $from_name  = config('mail.from.name');
                }

                //$asunto = '[CONFIRMACIÓN] '.$rs_datos->nombre_evento;
                $id_plantilla = session('eventos_id'); //ID EVENTO
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
                            'updated_at'        => Carbon::now(),
                            'from_nombre'       => $from_name,
                            'from_email'        => $from_email,
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

                }
                /*else{
                    $error .= "No se envío el <strong>whatsapp</strong> porque no esta habilitado";
                }*/


            }
        }


        Cache::flush();

        if($error){
            return redirect()->back()->with('alert', $error);
        }

        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        return redirect()->route('leads.index',['tipo'=>$tipo]);
    }


    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["mostrar"]   ) ){
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

        return view('leads.show',compact('estudiantes_datos','tipo_doc', 'countrys', 'tipos','departamentos_datos','grupos','eventos_id'));
        //return view('leads.show',compact('estudiantes_datos','tipo_doc', 'countrys', 'tipos','departamentos_datos','provincias_datos','distritos_datos','prov','dis'));
    }


    public function edit($id, Request $request)
    {
        
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        if(session('eventos_id') == false){
            return redirect()->route('eventos.index');
        }
        $eventos_id = session('eventos_id');

        $tipo = ($request->get('tipo'))?$request->input('tipo'):0;
        $tipo = intval($tipo);

        // Get ID tb:estudiantes_act_detalle cpo: confirmado para cambiar "APROBO SI O NO"
        $id_detalle = ($request->det)?$request->det:0;
        
        if($tipo == 8 or $tipo==10){
        
            $nrs = DB::table('m4_ddjj')
                        ->where('id',$id_detalle)
                        ->count();

            if($nrs>0){
                $rs = DB::table('m4_ddjj')
                                ->where('id',$id_detalle)
                                ->first();

                $id_detalle = $rs->detalle_id;

            }
        }

        // Datos Cursos DDJJ tipo=8
        $cursos    = [];
        $cursos_m4 = [];
        if($tipo==8 or $tipo==10){
            $cursos = DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->get();
            $cursos_m4 = DB::table('m4_ddjj')->where('detalle_id',$id_detalle)->where('evento_id',session('eventos_id'))->first();
        }
        
        $tipos = DB::table('estudiantes_tipo')->get();
        $tipo_doc = TipoDoc::all();
        Cache::flush();

        if(($tipo==8 or $tipo==10) and $id_detalle>0)
            $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')->where('de.id',$id_detalle);
        else
            $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')->where('estudiantes.id',$id);

        if($tipo==4)
            $q->join('mae_maestria as m','de.id','=','m.detalle_id')
              ->select('estudiantes.id','estudiantes.tipo_documento_documento_id','estudiantes.dni_doc','estudiantes.ap_paterno', 'estudiantes.ap_materno', 'estudiantes.nombres','estudiantes.pais','estudiantes.region','estudiantes.ubigeo_ubigeo_id', 'de.dgrupo as grupo', 'de.estudiantes_tipo_id as tipo_id','estudiantes.profesion','estudiantes.organizacion', 'estudiantes.cargo', 'estudiantes.email', 'estudiantes.celular', 'estudiantes.codigo_cel', 'estudiantes.telefono','de.daccedio','de.dgrupo','de.dtrack', 'de.estado','de.actividades_id','de.confirmado','m.provincia','m.distrito');
        elseif($tipo==7)
            $q->join('e_preguntas as mm','de.id','=','mm.detalle_id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','estudiantes.created_at','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id'
            , 'mm.detalle_id','mm.pregunta','mm.provincia','mm.distrito','mm.id as pregunta_id','de.fecha_conf','de.confirmado','estudiantes.provincia','estudiantes.distrito');
        else
            $q->select('estudiantes.id','estudiantes.tipo_documento_documento_id','estudiantes.dni_doc','estudiantes.ap_paterno', 'estudiantes.ap_materno', 'estudiantes.nombres','estudiantes.pais','estudiantes.region','estudiantes.ubigeo_ubigeo_id', 'de.dgrupo as grupo', 'de.estudiantes_tipo_id as tipo_id','estudiantes.profesion','estudiantes.organizacion', 'estudiantes.cargo', 'estudiantes.email', 'estudiantes.celular', 'estudiantes.codigo_cel', 'estudiantes.telefono','de.daccedio','de.dgrupo','de.dtrack', 'de.estado','de.actividades_id','de.confirmado','estudiantes.provincia','estudiantes.distrito');
        
        $q->where('de.eventos_id',session('eventos_id'));
        $estudiantes_datos = $q->first();
        
        $distrito = isset($estudiantes_datos->ubigeo_ubigeo_id)?$estudiantes_datos->ubigeo_ubigeo_id:0;
        
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $grupos = DB::table('est_grupos')->whereNotNull('eventos_id')->get();


        $dis = substr($distrito,0,4);

        $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis.'%','id2' => $dis]);

        $prov = substr($distrito,0,2);
        $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov.'%','id2' => $prov,'id3' => 4]);

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        $evento_vencido = 1;
        
        if(session('evento_tipo') == 1){
            // BLOQUEO DE IMPORT / DE BAJA/ REENVIAR INVITACIÓN
            $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',session('eventos_id'))
                            ->orderBy('e.id', 'desc')
                            ->count();

            if($rs_datos == 0){
                alert()->warning('Elimine el evento y vuelva a crear el evento, plantillas y formulario.','Error')->persistent('Cerrar');
                return redirect()->route('eventos.index');
            }

            $rs_datos = DB::table('eventos as e')
                            ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                            ->where('e.id',session('eventos_id'))
                            ->orderBy('e.id', 'desc')
                            ->first();

            $n_e = estudiantes_act_detalle::where('eventos_id', session('eventos_id'))->count();


            if($n_e >= $rs_datos->vacantes){
                $evento_vencido = 1;
            }else{
                $evento_vencido = 0;
            }

        } // end tipo_evento
        
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
                        ->orderBy('a.fecha_desde','asc')
                        ->orderBy('a.hora_inicio','asc')
                        ->get();
        
        if($tipo==7)
            return view('leads.edit_eventos-es',compact('estudiantes_datos','tipo_doc', 'tipos','countrys','departamentos_datos','provincias_datos','distritos_datos','prov','dis','grupos','eventos_id','evento_vencido','datos_h','datos_act'));
        else
            return view('leads.edit',compact('estudiantes_datos','cursos_m4','cursos','tipo_doc', 'tipos','countrys','departamentos_datos','provincias_datos','distritos_datos','prov','dis','grupos','eventos_id','evento_vencido','datos_h','datos_act','id_detalle'));
    }

    public function update(Request $request, $id)
    {
        #dd("edit SAVE form: $id eeee" );  
        $this->validate($request,[

            'inputdni'=>'required|unique:estudiantes,dni_doc,'.$id,
            'cboTipDoc' => 'required'
        ]);
        
        // Campos Form Maestria
        $apto = 0;$aprobo=0;
        if($request->get('tipo')==4){
            $apto   = $request->input('confirmado');
            $aprobo = $request->input('actividades_id');
        }
        
        $id_evento = ($request->get('eventos_id'))?$request->input('eventos_id'):0;
        $tipo = ($request->get('tipo'))?$request->input('tipo'):0;
        $tipo = intval($tipo);
        if($tipo == 2) 
            $estudiantes_tipo_id = 5;
        else
            $estudiantes_tipo_id = 4;

        // Get ID tb:estudiantes_act_detalle cpo: confirmado para cambiar "APROBO SI O NO"
        $id_detalle = ($request->id_detalle)?$request->id_detalle:0;

        // ESTUDIANTES: 4 - EVENTO: 5

        $xdni     = mb_strtoupper($request->input('inputdni'));
        $grupo    = mb_strtoupper($request->input('grupo'));
        $daccedio = mb_strtoupper($request->input('accedio'));
        $dtrack   = mb_strtoupper($request->input('track'));
        $estado   = $request->input('cboEstado');
        $upd      = Carbon::now();

        $apat = mb_strtoupper($request->input('inputApe_pat'));
        $amat = mb_strtoupper($request->input('inputApe_mat'));
        $nom  = mb_strtoupper($request->input('inputNombres'));

        
        $rs_estudiantes = DB::table('estudiantes')->select('tipo_id','dni_doc')
                            ->where('id',$id)->first();

        $dni_server = $rs_estudiantes->dni_doc;

        $rs_datos = DB::table('eventos')
                            ->where('id',session('eventos_id'))
                            ->first();

        if($rs_datos){
            $evento_id = $rs_datos->id;
            $fechai_evento = $rs_datos->fechai_evento;
            $fechaf_evento = $rs_datos->fechaf_evento;
            
        }else{
            alert()->success('Ingrese a un evento','Alerta');
            return redirect()->route('eventos.index');
        }

        // Campos Form DDJJ
        if($tipo==8 or $tipo==10){
            $apto   = $request->input('confirmado');
            $aprobo = $request->input('actividades_id');

            $nota = $request->nota;
            $obs = $request->obs;

            $datos_m4 = [
                'nota'        => $nota,
                'obs'         => $obs
            ];

            if($apto=="0")$dtrack = '';
            
            //Participante rechazado, enviar email Asunto: Subsanar  #==2 or $apto==3
            if($apto){

                $dia = date('d/m/Y');
                $hora = date('h:i');

                $from_email = session('ajustes')['email']??'informes@enc.edu.pe';
                $from_name  = session('ajustes')['email_nom']??'Escuela Nacional de Control';
                # Validar email
                // SE QUITA SI TIENE DOS O MAS EMAILS CON ESPACIO
                $d_email = $request->inputEmail;
                $email_partes = explode(" ", $d_email);
                $to_email = $email_partes[0];
                // VERIFICO SI ES VALIDO
                $sanitized_email = filter_var($to_email, FILTER_SANITIZE_EMAIL);
                if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                $to_email = $sanitized_email;
                } else {
                $to_email = "";
                }
                
                #$to_email = $request->inputEmail;
                $to_name  = $request->inputNombres ." ".$request->inputApe_pat;
                $plantillaemail = "";
                $dtrack = '';
                if($apto==1){ #&& $tipo==10

                    $datos = DB::table('eventos as e')
                            ->select('e.lugar')
                            ->where('e.id',$id_evento)
                            ->first();
        
                    // periodo: codigo
                    $pe = $datos->lugar;

                    $path = "storage/ddjj/$pe";
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }

                    if($tipo==10){
                        // Pintar: Carta de compromiso generada
                        $dtrack = 'SI';

                        $file = "storage/ddjj/$pe/".$id_evento."-".$xdni."-".$id_detalle.".CC.pdf";
                        $file_2 = "storage/ddjj/$pe/".$id_evento."-".$xdni."-".$id_detalle.".pdf";
    
                        $adjuntos = [
                            $file,
                            $file_2
                        ];
    
                        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    
                        $pdf = PDF::loadView('ddjjcartas.ficha_carta_compromiso', ["datos"=>$this->getDataPDFDDJJ($xdni,$id_evento,$id_detalle),'meses'=>$meses]);
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
                    }

                    
                    /* $updateListado =estudiantes_act_detalle::where('estudiantes_id',$xdni)
                    ->where('eventos_id',session('eventos_id'))
                    ->where('id',$id_detalle)
                    ->firstOrFail();
                    $updateListado->dtrack = 'SI';
                    $updateListado->save(); */

                    #enviar correo adjuntando la dj y carta
                    $est = Estudiante::where('dni_doc',$xdni)->first();
                    $ajuste = Ajuste::findOrFail(1);
                    
                    $msg_text = '';
                    $datos_email = array(
                        'estudiante_id' => $xdni,
                        'email'    => $est->email,
                        'from'     => $ajuste->email,
                        'from_name'=> $ajuste->email_nom,
                        'name'     => $est->nombres,
                        'asunto'   => 'Solicitud de beca a curso o programa aceptada'
                    );

                    if($tipo==10)
                        $datos_email['adjuntos'] = $adjuntos;

                    $data = array(
                        'detail'    => "Mensaje enviado",
                        'mensaje'   => $msg_text,
                        'nombre'    => $est->nombres.' '.$est->ap_pat,
                        'titulo'    => 'Accedió a una beca de la ENC'
                    );

                    if($tipo==10){
                        Mail::send('email.dj_carta_compromiso', $data, function ($mensaje) use ($datos_email,$pdf,$file){
                            $mensaje->from($datos_email['from'], $datos_email['from_name'])
                            ->to($datos_email['email'], $datos_email['name'])
                            ->subject($datos_email["asunto"]);
                            foreach ($datos_email['adjuntos'] as $f) {
                                $mensaje->attach($f);
                            }
                        });
                    }else{
                        Mail::send('email.dj_carta_compromiso', $data, function ($mensaje) use ($datos_email){
                            $mensaje->from($datos_email['from'], $datos_email['from_name'])
                            ->to($datos_email['email'], $datos_email['name'])
                            ->subject($datos_email["asunto"]);
                        });
                    }

                    /* DB::table('historia_email')->insert([
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
                    ]); */
                    
                #djcgr-inscripcion
                }elseif($apto==2 and $tipo==8){ #DJ OCI
                    $asunto = "Su DJ a la programación de la ENC ha sido observada";
                    $link   = url('')."/ddjj-inscripcion?id=$evento_id&t=obs&d=$xdni&de=$id_detalle";
                    $plantillaemail = "email.dj_observacion";
                    
                }elseif($apto==2 and $tipo==10){ #DJ CGR
                    $asunto = "Su DJ a la programación de la ENC ha sido observada";
                    $link   = url('')."/djcgr-inscripcion/$evento_id/?t=obs&d=$xdni&de=$id_detalle";
                    $plantillaemail = "email.dj_observacion";
                }else{
                    $asunto = "Su solicitud de beca a la programación de la ENC ha sido rechazada";
                    $link   = "";
                    $plantillaemail = "email.dj_rechazado";
                }
                
                if($apto==2 or $apto==3){
                    $datos_email = array(
                        'to_email'   => $to_email,
                        'to_name'    => $to_name,
                        'asunto'     => $asunto,
                        'from_email' => $from_email,
                        'from_name'  => $from_name
                    );
    
                    $data = array(
                        'dni'        => $dni_server,
                        'nombre'     => $nom." ".$apat,
                        'mensaje'    => $obs,
                        'evento_id'  => $evento_id,
                        'tpo'        => 'obs',
                        'titulo'     => $asunto,
                        'link'       => $link
                    );

                    if($to_email){
                        $email_success = Mail::send($plantillaemail, $data, function ($mensaje) use ($datos_email){
                            //$mensaje->from('admin@enc.pe','Admin');
                            $mensaje->from($datos_email['from_email'], $datos_email['from_name']);
                            $mensaje->to($datos_email['to_email'], $datos_email['to_name'])
                                    ->subject($datos_email["asunto"]);
                            
                            //$mensaje->attach($datos_email['file']);
                
                        });
                        #validar si el email se envia - error: 503
                        #if(!$email_success)dd("El servidor de correo no pudo entregar el email.");
                    }

                }
            }
            
            if($request->cod_curso == 1){ //sig que no fue modificado
            
            }else{
                $cod_curso = $request->cod_curso;
                $nom_curso2 = $request->nom_curso2;
                $mod_curso = $request->mod_curso;
                
                $fe_ini = $request->fech_ini;
                $fe_fin = $request->fech_fin;
                
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
                    alert()->warning('Error en los campos de las fechas','Error');
                    return redirect()->back();
                }

                $datos_m4['nom_curso'] = $nom_curso2;
                $datos_m4['cod_curso'] = $cod_curso;
                //$datos_m4['mod_curso'] = $mod_curso;
                $datos_m4['f_ini_curso'] = $fe_ini;
                $datos_m4['f_fin_curso'] = $fe_fin;

            }
            
            
            // firma digital
            if($request->foto){

                $img_firma = $request->file('foto');
                $id_evento = session('eventos_id');
                $firma = "firma-{$xdni}-{$id_evento}.".$img_firma->getClientOriginalExtension();
                $img_firma->move(public_path('storage/ddjj-firmas/'), $firma);
                // /public_html/storage/ddjj-firmas
                
                $datos_m4['firma'] = $firma;
            }

            
            if ($id_detalle>0) {
                
                DB::table('m4_ddjj')->where('detalle_id',$id_detalle)
                ->where('evento_id',session('eventos_id'))
                ->update($datos_m4);
            }
            ##dd('update',$id_detalle, $datos_m4);

        
        }
        
        // pendiente optimizar
        // $data_est = [];

        //Actualizamos
        DB::table('estudiantes')->where('id',$id)->update([
            
            'dni_doc'     => $xdni,
             'ap_paterno' => $apat,
             'ap_materno' => $amat,
             'nombres'    => $nom,
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
             'email_labor'=>$request->input('email_labor'),
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

        $existe_det = estudiantes_act_detalle::where('estudiantes_id',$dni_server)
                        ->where('eventos_id',session('eventos_id'))
                        ->count();

        if($existe_det > 0){

            $data_det = [
                'estudiantes_id'     => $xdni,
                'estudiantes_tipo_id'=> $estudiantes_tipo_id,
                'estado'             => $estado,
                'dgrupo'             => $grupo,
                'daccedio'           => $daccedio,
                'dtrack'             => $dtrack,
                'actividades_id'     => $aprobo,
                'confirmado'         => $apto,
                //'created_at'         => $upd
            ];

            if ($id_detalle>0) {
                $rs_update = estudiantes_act_detalle::where('id',$id_detalle)
                        ->where('eventos_id',session('eventos_id'))
                        ->update($data_det);
            }else{
                $rs_update = estudiantes_act_detalle::where('estudiantes_id',$dni_server)
                        ->where('eventos_id',session('eventos_id'))
                        ->update($data_det);
            }

            

        }else{
            $det = new estudiantes_act_detalle();
            $det->eventos_id = session('eventos_id');
            $det->estudiantes_id = $xdni;
            $det->estudiantes_tipo_id = $estudiantes_tipo_id;
            $det->actividades_id = $aprobo;
            $det->confirmado     = $apto;
            $det->estado         = $estado;
            $det->dgrupo         = $grupo;
            $det->daccedio       = $daccedio;
            $det->dtrack         = $dtrack;
            $det->created_at     = $upd;
            $det->save();

        }

        Cache::flush();
        if($tipo==8 or $tipo==10) 
            $request->session()->flash('status-dj', 'Successful!');

        alert()->success('Mensaje Satisfactorio','Registro actualizado.');
        return redirect()->back();
        dd('edit SAVE form');
    }

    public function getDataPDFDDJJ($dni_doc, $evento_id, $detalle_id = null){
        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('de.eventos_id',$evento_id)
            ->where('estudiantes.dni_doc',$dni_doc)        ;
        $q->join('m4_ddjj as dd','de.id','=','dd.detalle_id')
            ->join('m4_cursos as cu','cu.id','=','dd.curso_id')
            ->where('dd.detalle_id',$detalle_id)
            ->select('*','cu.*')
            ->orderBy('dd.id', 'DESC');
        $n = $q->get()->count();
        
        if($n==0)abort(404);

        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('de.eventos_id',$evento_id)
            ->where('estudiantes.dni_doc',$dni_doc)        ;
        $q->join('m4_ddjj as dd','de.id','=','dd.detalle_id')
            ->join('m4_cursos as cu','cu.id','=','dd.curso_id')
            ->where('dd.detalle_id',$detalle_id)
            ->select('*','cu.*')
            ->orderBy('dd.id', 'DESC');
            return $q->get()->first();
    }

    public function destroy($id)
    {

        Estudiante::where('id',$id)->delete();
        //DB::table('estudiantes')->where('id',$id)->delete();
        return redirect()->route('leads.index');
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["eliminar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        
        $tipo = ($request->get('xtipo'))?$request->get('xtipo'):0;
        $tipo = intval($tipo);
        
        $tipo_doc = $request->tipo_doc;

        if($tipo == 8 or $tipo==10){

            // Borrar file adjunto:
            // tipo: 8 - DDJJ
            foreach ($tipo_doc as $value) {
        
                $ide = DB::table('m4_ddjj')->where('id',$value)->first();
                
                if(is_null($ide)) alert()->warning('Advertencia','No existe registros.');
                
                $id_detalle = $ide->detalle_id;
                $id_estudiante = $ide->estudiante_id;

                $rs_evento = Evento::where('id', session('eventos_id'))->select('lugar')->first();
                $codigo = $rs_evento->lugar;

                $rs_est = estudiantes_act_detalle::where('id', $id_detalle)->select('estudiantes_id')->first();
                #echo "id_detalle: $id_detalle" . " --  value: $value" . " -- id_estudiante: $id_estudiante"."<br>";
                $dni = $rs_est->estudiantes_id;

                $firma = public_path('storage/ddjj-firmas/').$ide->firma;
                $firma2 = "storage/ddjj-firmas/".$ide->firma;

                if(File::exists($firma)) File::delete($firma);
                if(File::exists($firma2)) File::delete($firma2);

                $nom_pdf = session('eventos_id')."-".$dni."-".$id_detalle.".pdf";
                $nom_pdf2 = session('eventos_id')."-".$dni."-".$id_detalle.".CC.pdf";

                $pdf_file = public_path('storage/ddjj/'.$codigo."/").$nom_pdf;
                if(File::exists($pdf_file))File::delete($pdf_file);
                //If file is /
                $pdf_file = "storage/ddjj/".$codigo."/".$nom_pdf;
                if(File::exists($pdf_file))File::delete($pdf_file);

                $pdf_file = public_path('storage/ddjj/'.$codigo."/").$nom_pdf2;
                if(File::exists($pdf_file))File::delete($pdf_file);
                //If file is /
                $pdf_file = "storage/ddjj/".$codigo."/".$nom_pdf2;
                if(File::exists($pdf_file))File::delete($pdf_file);

                // Eliminar de tb: m4_ddjj
                DB::table('m4_ddjj')->where('id',$value)->delete();

                
                $est = Estudiante::where('id', $id_estudiante)->first();
                $id_estudiante = $est->id;

                $this->eliminarDatosEstudiante($id_estudiante, $tipo, $id_detalle);
            }

           
        }else{

            foreach ($tipo_doc as $value) {

                $this->eliminarDatosEstudiante($value, $tipo);
            
                Cache::flush();
            }
        }


        alert()->error('Eliminado','Registros borrados.');
        //return redirect()->route('leads.index');
        return redirect()->back();
    }
    

    public function eliminarDatosEstudiante($value,$tipo,$id_detalle='')
    {
        
        $est = Estudiante::where('id', $value)->first();

            DB::table('audi_estudiantes')->insert([
                 'id_estudiante'=> $est->id,
                 'dni_doc'  =>$est->dni_doc,
                 'ap_paterno'=>$est->ap_paterno,
                 'ap_materno'=>$est->ap_materno,
                 'nombres'  =>$est->nombres,
                 'fecha_nac'=>$est->fecha_nac,
                 'grupo'    =>$est->grupo,
                 'cargo'    =>$est->cargo,
                 'organizacion'=>$est->organizacion,
                 'profesion'=>$est->profesion,
                 'direccion'=>$est->direccion,
                 'telefono' =>$est->telefono,
                 'telefono_labor'=>$est->telefono_labor,
                 'celular'  =>$est->celular,
                 'email'    =>$est->email,
                 'email_labor'=>$est->email_labor,
                 'sexo'     =>$est->sexo,
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now(),
                 'estado'    =>$est->estado,
                 'accedio'   =>$est->accedio,
                 'track'     =>$est->track,
                 'tipo_documento_documento_id'=>$est->tipo_documento_documento_id,
                 'ip'        =>request()->ip(),
                 'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                 'entidad'   => $est->entidad,
                 'ubigeo_ubigeo_id'     => $est->ubigeo_ubigeo_id,
                 'accion'    => 'DELETE',
                 'usuario'   => \Auth::user()->email,
                 'id_evento' => session('eventos_id')
            ]);

            // Borrar file adjunto:
            // tipo: 4 - Maestria
            if($tipo == 4){
                $ide = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                        ->join('mae_maestria as mm','de.id','=','mm.detalle_id')
                        ->where('estudiantes.dni_doc', $est->dni_doc)
                        ->select('de.id','de.dgrupo','mm.compago','mm.decjur','mm.ficins','mm.cv','mm.detalle_id')
                        ->first();
                $id_mae = $ide->detalle_id;

                $file_cp = (storage_path('app/'.$ide->compago));
                $file_dj = (storage_path('app/'.$ide->decjur));
                $file_fi = (storage_path('app/'.$ide->ficins));
                $file_cv = (storage_path('app/'.$ide->cv));

                if(File::exists($file_cp))
                    File::delete($file_cp);

                if(File::exists($file_dj))
                    File::delete($file_dj);

                if(File::exists($file_fi))
                    File::delete($file_fi);

                if(File::exists($file_cv))
                    File::delete($file_cv);

                formMaestria::where('detalle_id',$id_mae)->delete();                
                
            }

            
            Evento::where('id', session('eventos_id'))
                    ->decrement('inscritos_invi', 1);

            $xreg = estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                        //->where('eventos_id', session('eventos_id'))
                        ->count();
            
            if($xreg == 1){
                
                Estudiante::where('id',$value)->delete();
                estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                    ->where('eventos_id',session('eventos_id'))
                    ->delete();
                //DB::table('actividades_estudiantes')->where('estudiantes_id',$est->dni_doc)->delete();
                DB::table('users')->where('name',$est->dni_doc)->delete();
                DB::table('actividades_estudiantes')->where('estudiantes_id',$est->dni_doc)
                    ->where('eventos_id',session('eventos_id'))
                    ->delete();
            }else{

                if($tipo==8 or $tipo==10){
                    estudiantes_act_detalle::where('id', $id_detalle)->delete();
                }else{
                    
                    estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                        ->where('eventos_id',session('eventos_id'))
                        ->delete();

                }

            }

            return "ok";

    }

    public function EstudianteImport(Request $request){
        $msg = "Solo se aceptan archivos XLS, XLSX y CSV. ";
        $results = [];
        if($request->hasFile('file')){

            $filesd = glob(base_path('storage\excel\*')); //get all file names
            
            foreach($filesd as $filed){
                if(is_file($filed))
                unlink($filed); //delete file
            }

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
            $results = Excel::toArray(new EstudianteImport, $file);
            \Config::set('excel.import.encoding.input', 'iso-8859-1');
            \Config::set('excel.import.encoding.output', 'iso-8859-1');

            $filePath = $file->storeAs('storage\excel', "estudiantes.".$extension, 'real_public');

        }
        return count($results)>0?$results[0]:[];
        //return $results;

    }

    public function EstudianteImportSave(Request $request, EstudianteRepository $repository)
    {
        //$arch_excel = base_path('\storage\excel')."\estudiantes"
        /*$file_path = base_path('storage\excel');
        $directory = $file_path;
        $file_exc = scandir ($directory)[2];*/

        $file_path = "storage/excel";
        $file_exc  = "estudiantes.xlsx";

        \Config::set('excel.import.encoding.input', 'iso-8859-1');
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        /*$results = \Excel::toArray(new EstudianteImport, $file_path . "/" . $file_exc);
        $data_exc = $results[0];*/

        $reader = Excel::toArray(new EstudianteImport, public_path($file_path ."/". $file_exc));//ADDED
        $data_exc = $reader[0];

        $flagC = $request["chkPrimeraFila"];
        $chkE_invitacion= $request["chkE_invitacion"];
        if($flagC!=""){
            $contF = 0;
        }else{
            $contF = 1;
        }

        DB::table('estudiantes_temp')->truncate();
        $respta = $repository->estudiante_import($data_exc,$contF,$chkE_invitacion,$request);

        return "ok";
    }

    public function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }

    public function EstudianteImportResults() {
        $nlista = EstudianteTemp::count();
        $lista = EstudianteTemp::orderBy("id","ASC")->get();
        
        if(count($lista)==0){
            die("No hay registros");
        }
        $vEnt = 0;
        /*
        foreach ($lista as $lstT) {
            if($lstT->idEntidad!=0){$vEnt=1;}
        } */

        return view("leads.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'nlista'=>$nlista]);
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

        $eevento = $rs_datos->nombre_evento;

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
                $msg = "El campo celular esta vacio o no cumple con la cantidad mínima de digitos. Cant. min: 9 dígitos";
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

        if($tipo == 'confirmacion' or $tipo == 'recordatorio'){

            $msg_val   = 0;
            $msg_val_2 = 0;
            if($rs_datos->p_conf_registro == ""){
                $msg_val = 1;
                $msg .= "No existe plantilla para la confirmación por email. ";
            }

            if($msg_val == 0 or $msg_val_2 == 0){

                if($tipo == 'confirmacion'){
                    $flujo_ejecucion = 'CONFIRMACION';
                    if(!empty($rs_datos->email_asunto)){
                        $asunto = '[CONFIRMACIÓN] '.$rs_datos->email_asunto;
                    }else{
                        $asunto = '[CONFIRMACIÓN] '.$rs_datos->nombre_evento;
                    }
                    //$asunto          = '[CONFIRMACIÓN] '.$eevento;

                    $msg_text = $rs_datos->p_conf_registro;
                    $msg_cel  = $rs_datos->p_conf_registro_2;

                }else{
                    $flujo_ejecucion = 'RECORDATORIO';
                    if(!empty($rs_datos->email_asunto)){
                        $asunto = '[RECORDATORIO] '.$rs_datos->email_asunto;
                    }else{
                        $asunto = '[RECORDATORIO] '.$rs_datos->nombre_evento;
                    }
                    //$asunto          = '[RECORDATORIO] '.$eevento;

                    $msg_text = $rs_datos->p_recordatorio;
                    $msg_cel  = $rs_datos->p_recordatorio_2;
                }

                if(!empty($rs_datos->email_asunto)){
                    $from = Emails::findOrFail($rs_datos->email_id);
                    $from_email = $from->email;
                    $from_name  = $from->nombre;
                }else{
                    $from_email = config('mail.from.address');
                    $from_name  = config('mail.from.name');
                }

                $id_plantilla = $evento; //ID EVENTO

                $celular = $rs_user[0]->codigo_cel.$rs_user[0]->celular;
                $nom     = $rs_user[0]->nombres .' '.$rs_user[0]->ap_paterno.' '.$rs_user[0]->ap_materno;
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
                        'updated_at'        => Carbon::now(),
                        'from_nombre'       => $from_name,
                        'from_email'        => $from_email,
                    ]);

                    //$msg = "Participante: $nom con email: $email y celular: $celular. Se envio correctamente la INVITACIÓN";
                    ;
                    if($tipo == 'confirmacion'){
                        $msg .= "<br>CONFIRMACIÓN EMAIL: $email - Se envío correctamente<br>";
                    }else{
                        $msg .= "<br>RECORDATORIO EMAIL: $email - Se envío correctamente<br>";
                    }
                    $msg_tipo = "success";
                    $msg_color = "#058a49";

                }

                $msg_val_2 = 0;

                if($rs_datos->p_conf_registro_2 == ""){
                    $msg_val_2 = 1;
                    $msg .= "No existe plantilla para el mensaje por whatsapp. ";
                }

                if($msg_val_2 == 0){
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
                            $msg .= "<br>CONFIRMACIÓN WHATSAPP: $celular. Se envío correctamente<br>";

                        }else{
                            $msg .= "<br>RECORDATORIO WHATSAPP: $celular. Se envío correctamente<br>";
                        }
                        $msg_tipo = "success";
                        $msg_color = "#058a49";

                    }
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
