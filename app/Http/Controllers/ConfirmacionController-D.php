<?php

namespace App\Http\Controllers;
use App\HistoryEmails;
use App\Jobs\SendHistoryEmails;
use App\Repositories\CampaniaRepository;
use Cache;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Estudiante;
use App\TipoDoc;
use App\Departamento;

use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;
use App\Repositories\EstudianteRepository;
use Alert;
use Auth;

class ConfirmacionController extends Controller
{
    //cola se ejecutara despues de 5 segundos del tiempo que se comienza a procesar la campaña
    private $seconds_start = 5;
    //Tiempo de espera de cada cola
    private $seconds_for = 3;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, EstudianteRepository $repository, CampaniaRepository $campaniaRepository)
    {


        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "estudiantes";
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

        if(session('eventos_id') == false) return redirect()->route('caii.index');


        $tipos = DB::table('estudiantes_tipo')->get();
        $modalidades = DB::table('tc_modalidades')->get();
        $grupos = DB::table('est_grupos')->whereNotNull('eventos_id')->get();

        Cache::flush();

        $random_number = $request->get('rdn');
        $search = $request->get('s', '');
        $modalidad = $request->get('mod', 0);
        $tipo = $request->get('st', 0);
        $grupo = $request->get('gr', '');
        $apro = $request->get('apro', '');
        $region = $request->get('region', '');

        $data = compact('modalidad', 'tipo', 'grupo','apro', 'region');
        $data['q'] = $search;

        if($random_number > 0){//CHOCOLATEAR
            $random_type = $request->get('type');
            $cn = $request->get('cn',2);
            $url = request()->fullUrlWithQuery(["rdn"=>null,"type"=>null,'cn'=>null]);
            $data['pag'] = 3;
            $data['pag'] = $random_number;
            if($random_type==1)$data['random'] = true;
            $data['choco'] = true;
            $datos = $this->filtros($data);
            $x = $this->chocolatear($datos, session('eventos_id'), $cn, $campaniaRepository);
            return redirect($url);
        }
        $data['pag'] = $pag;
        if($search != ''||$tipo>0||$modalidad>0||$grupo!=''||$apro!=''){
            $f_datos = $this->filtros($data);
        }else{
            $f_datos = Cache::rememberForever('estudiantes_conf.page.'.request('page', 1), fn () => $this->filtros(['pag'=>$pag])
        );
        }
        return view('form_confirmacion.form_confirmacion', compact('f_datos', 'permisos','modalidades','tipos','grupos'));
    }

    public function chocolatear($datos, $evento_id, $cn=0, CampaniaRepository $campaniaRepository)
    {
        $SINO = ['SI', 'NO'];
        $existePlantVirtual1 = 0;
        $existePlantVirtual2 = 0;
        $existePlantVirtual2 = DB::table('e_plantillas_virtual')->where('eventos_id',$evento_id)->count();
        $rs_datos1 = $this->getPlantillaXEvento($evento_id, 0);
        $rs_datos2 = ($existePlantVirtual2 == 0) ? $rs_datos1: $this->getPlantillaXEvento($evento_id, 1);
        $rs_email1 =  $rs_datos1->Email;
        $rs_email2 = ($existePlantVirtual2 == 0) ? $rs_email1:  $rs_datos2->Email;

        $count_email = 0;
        $random = 0;
        foreach($datos as $estudiante){
            $count_email++;
            if($cn==1)$random=2;
            elseif($cn>1)$random=1;
            else $random = random_int(1, 2);
            //$random = 1;
            $SI = ($random == 1);
            $rs_datos = $random ==1 ? $rs_datos1: $rs_datos2;
            $rs_email = $random ==1 ? $rs_email1: $rs_email2;

            $estudiante_id = $estudiante->id;
            $dni = $estudiante->dni_doc;
            $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
            $nombre = $estudiante->nombres;
            $ape_pat = $estudiante->ap_paterno;
            $ape_mat = $estudiante->ap_materno;
            $email = $estudiante->email;

            if(!$SI){
                DB::table('users')->where('name',$dni)->update([
                    'estado'=>0
                    //'tipo_id' => 1  NO-APROBADO
                ]);
            }

            $existe_det = $this->actualizaTrackXEstDet($dni, $evento_id, ($SI)?'SI':'NO' );
            $this->actualizaTrackXEstudiante($estudiante_id, ($SI)?'SI':'NO');

            $flujo_ejecucion = ($SI) ? 'INVITACION': 'NOINVITADO';
            #$asunto = '[INVITACIÓN] '.$rs_datos->nombre_evento;
            $id_plantilla = $evento_id; //ID EVENTO
            $plant_confirmacion = ($SI) ? $rs_datos->p_conf_inscripcion : $rs_datos->p_negacion;
            $plant_confirmacion_2 = ($SI) ? $rs_datos->p_conf_inscripcion_2 : $rs_datos->p_negacion_2;

            $celular = $estudiante->codigo_cel.$estudiante->celular;

            $confirm_email = ($SI) ?$rs_datos->p_conf_inscripcion_email: $rs_datos->p_negacion_email;
            $confirm_msg   = ($SI) ?$rs_datos->p_conf_inscripcion_msg: $rs_datos->p_negacion_msg;

            $asunto='';
            $modalidad = $estudiante->modalidad_id;
            $msg_text = '';
            $msg_cel  = '';
            $from_name  = $rs_email->nombre;
            $from_email = $rs_email->email;
            $accedio = 'NO';
            $confirm_email = 1;
            //dd($SI);
            if($confirm_email == 1){
                // PRESENCIAL #$estudiante->Modalidad->modalidad=='PRESENCIAL'
                if($modalidad==1){
                    $asunto =  ($SI) ? $rs_datos->p_conf_inscripcion_asunto: $rs_datos->p_negacion_asunto;
                    $msg_text = ($SI) ?$rs_datos->p_conf_inscripcion: $rs_datos->p_negacion;// plantila email
                }else{
                    // VIRTUAL
                    $asunto = ($SI) ? $rs_datos->p_conf_inscripcion_asunto_v : $rs_datos->p_negacion_asunto_v;
                    $msg_text = ($SI) ? $rs_datos->p_conf_inscripcion_v : $rs_datos->p_negacion_v;// plantila email
                }
                $xcelular = $celular;
                $xmsg_cel = $msg_cel;
                if(!$SI){
                    $asunto = '[LO SENTIMOS] '.$rs_datos->nombre_evento;
                    $xcelular = '';
                    $xmsg_cel = '';
                }
                if($email != ""){
                    $this->insertaHistoriaEmail('EMAIL',$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $xcelular, $msg_text, $xmsg_cel, $from_name, $from_email);
                    $dd = $this->insertHistory(
                        'EMAIL', $flujo_ejecucion, $id_plantilla, $evento_id, $email, $asunto, $estudiante_id, $nombre, $ape_pat, $ape_mat, $dni,
                        '', '', $msg_text, '', $from_name, $from_email, $accedio
                    );
                    $dt = $dd;//->toArray();
                    $dt['flujo'] = $flujo_ejecucion;//'MAILING';//
                    $dt['tipo'] = "EMAIL";
                    $dt['tipo'] = "EMAIL";
                    $dt['id'] = 0;
                    $dt['plantilla_id'] = $evento_id;;
                    $dt['asunto'] = $asunto ?? '(SIN ASUNTO)';
                    $dt['from_nombre'] = $from_name;
                    $dt['from_email'] = $from_email;
                    $time = ($count_email-1)*$this->seconds_for+$this->seconds_start;
                    //SIN COLA
                    $campaniaRepository->send($dt);
                    // CON COLA
                    //SendHistoryEmails::dispatch($xx)->onConnection('database')->onQueue("emails")->delay(Carbon::now()->addSecond($time));
                }
            }
            // MSG WHATS
            if($confirm_msg == 1&&0){
                $msg_text = '';
                // PRESENCIAL #$estudiante->Modalidad->modalidad=='PRESENCIAL'
                if($SI){// plantila msg
                    $msg_cel = ($modalidad == 1) ? $rs_datos->p_conf_inscripcion_2: $rs_datos->p_conf_inscripcion_2_v;
                }
                if($celular != "" && strlen($estudiante->celular)>= 9){
                    if(!$SI)$msg_text = '';
                    $this->insertaHistoriaEmail('WHATS',$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $celular, $msg_text, $msg_cel, $from_name, $from_email);
                }
            }

        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function edit($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $eventos_id = session('eventos_id');

        $tipos = DB::table('estudiantes_tipo')->get();
        $tipo_doc = TipoDoc::all();
        $estudiantes_datos = DB::table('estudiantes')->where('id', $id)->first();
        //$distrito = $estudiantes_datos->ubigeo_ubigeo_id;
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $grupos = DB::table('est_grupos')->get();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        //$estudiantes_datos = Estudiante::find($id);
        return view('form_confirmacion.edit',compact('estudiantes_datos','tipo_doc','departamentos_datos','grupos','tipos','countrys'));
    }


    public function update(Request $request, $id)
    {

        //Actualizamos
        DB::table('estudiantes')->where('id',$id)->update([
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
             'telefono'=>mb_strtoupper($request->input('inputTelefono')),
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
             'tipo_id'=>$request->input('tipo_id'),
        ]);

        $existe_det = DB::table('estudiantes_act_detalle')
                        ->where('eventos_id', session('eventos_id'))
                        ->where('estudiantes_id', $request->input('inputdni'))
                        ->update([
                            'dtrack'=> mb_strtoupper($request->input('track')),
                        ]);



        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
        //Redireccionamos
        //return redirect()->route('form_confirmacion.index');
        return redirect()->back();
    }


    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["mostrar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = TipoDoc::all();
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

        return view('form_confirmacion.show',compact('estudiantes_datos','tipo_doc','departamentos_datos','provincias_datos','distritos_datos','prov','dis'));
    }

    public function eliminarVarios(Request $request){

        /*$this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["eliminar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }*/
        // Enviar Confirmación - Pre-Inscritos
        $seleccion_si = $request->selection_si;
        $seleccion_no = $request->selection_no;
        $error = "";
        DD('CUANDO ELIJO.... SOLO A LOS SI');
        $i = 0;
        $j = 0;

        if(!is_null($request->selection_si)){

            foreach ($seleccion_si as $value) {
                $i++;

                $estudiante = $this->getEstudiante($value, session('eventos_id'));
                $dni = $estudiante->dni_doc;
                $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
                $email = $estudiante->email;
                $rs_datos = $this->getPlantillaXEvento(session('eventos_id'));
                if($rs_datos){
                    $evento_id = $rs_datos->id;
                    $fechai_evento = $rs_datos->fechai_evento;
                    $fechaf_evento = $rs_datos->fechaf_evento;
                }else{
                    alert()->success('Ingrese a un evento','Alerta');
                    return redirect()->route('caii.index');
                }

                $existe_det = $this->actualizaTrackXEstDet($dni, session('eventos_id') );
                $this->actualizaTrackXEstudiante($value);

                    $flujo_ejecucion = 'INVITACION';
                    #$asunto = '[INVITACIÓN] '.$rs_datos->nombre_evento;
                    $id_plantilla = session('eventos_id'); //ID EVENTO
                    $plant_confirmacion = $rs_datos->p_conf_inscripcion;
                    $plant_confirmacion_2 = $rs_datos->p_conf_inscripcion_2;

                    $celular = $estudiante->codigo_cel.$estudiante->celular;
                    $dni = $estudiante->dni_doc;
                    $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
                    $email = $estudiante->email;

                    $asunto='';
                    // falta probar x msg y whats
                    if($rs_datos->p_conf_inscripcion_email == 1){
                        $msg_cel  = "";
                        $from_name  = $rs_datos->Email->nombre;
                        $from_email = $rs_datos->Email->email;
                        // PRESENCIAL #$estudiante->Modalidad->modalidad=='PRESENCIAL'
                        if($estudiante->modalidad_id==1){
                            $asunto = $rs_datos->p_conf_inscripcion_asunto;
                            $msg_text = $rs_datos->p_conf_inscripcion;// plantila email
                        }else{
                            // VIRTUAL
                            $asunto = $rs_datos->p_conf_inscripcion_asunto_v;
                            $msg_text = $rs_datos->p_conf_inscripcion_v;// plantila email
                        }
                        if($email != ""){
                            $celular = '';
                            $this->insertaHistoriaEmail('EMAIL',$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $celular, $msg_text, $msg_cel, $from_name, $from_email);
                        }
                    }else{
                        // no inserta en la tb historia_email
                        $error .= 'No se envio el EMAIL porque no esta habilitado en la configuración del EVENTO.<br>';
                    }

                    // MSG WHATS
                    if($rs_datos->p_conf_inscripcion_msg == 1){
                        $msg_text = '';
                        // PRESENCIAL #$estudiante->Modalidad->modalidad=='PRESENCIAL'
                        if($estudiante->modalidad_id==1){
                            $asunto = $rs_datos->p_conf_inscripcion_asunto;
                            $msg_cel = $rs_datos->p_conf_inscripcion_2;// plantila msg
                        }else{
                            // VIRTUAL
                            $asunto = $rs_datos->p_conf_inscripcion_asunto_v;
                            $msg_cel = $rs_datos->p_conf_inscripcion_2_v;// plantila msg
                        }
                        if($celular != "" && strlen($estudiante->celular)>= 9){
                            $this->insertaHistoriaEmail('WHATS',$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $celular, $msg_text, $msg_cel, $from_name, $from_email);
                        }
                    }else{
                        $error .= 'No se envio el whatsapp porque no esta habilitado en la configuración del EVENTO.<br>';
                    }
                    $rs_datos   = "";
                    $estudiante = "";
            }
            //end seleccion_SI

        }
        if(!is_null($request->selection_no)){
            foreach ($seleccion_no as $value) {
                $j++;
                #$estudiante = Estudiante::where('id', $value)->first();
                $estudiante = $this->getEstudiante($value);

                $dni = $estudiante->dni_doc;
                $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
                $email = $estudiante->email;

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

                if($rs_datos){
                    $evento_id = $rs_datos->id;
                    $fechai_evento = $rs_datos->fechai_evento;
                    $fechaf_evento = $rs_datos->fechaf_evento;
                }else{
                    alert()->success('Ingrese a un evento','Alerta');
                    return redirect()->route('caii.index');
                }


                    DB::table('estudiantes')->where('id',$value)->update([
                        'track'=>'NO'
                        //'tipo_id' => 1  NO-APROBADO
                    ]);
                    DB::table('users')->where('name',$dni)->update([
                        'estado'=>0
                        //'tipo_id' => 1  NO-APROBADO
                    ]);

                    $existe_det =$this->actualizaTrackXEstDet($dni, session('eventos_id'), 'NO' );

                    DB::table('historial_e')->insert([
                     'est_id'=>$dni,
                     'est_tipo_id'=> 1,
                     'a_id'=> 0,
                     'eve_id'=> session('eventos_id'),
                     'eve_tipo_id'=> 1,
                     'aceptados_pre'=> 0,
                     'flag'=> 'D', //D: DENAGADO
                     //'aceptado_foro'=> 'SI',
                     'fecha_reg'=> Carbon::now(),
                     'fecha_inicial'=> $fechai_evento, // fecha ini d evento y foro
                     'fecha_final'=> $fechaf_evento // fecha fin d evento y foro

                    ]);

                    // envio email
                    $modalidad = $estudiante->modalidad_id;
                    $from_name  = $rs_datos->Email->nombre;
                    $from_email = $rs_datos->Email->email;
                    #dd($dni,$nom,$estudiante->modalidad_id,$from_name,$from_email);

                    $celular = $estudiante->celular;
                    $codigo_celular = $estudiante->codigo_cel;

                    $email = $estudiante->email;
                    $dni = $estudiante->dni_doc;
                    $nombres_ape = $estudiante->nombres ." ".$estudiante->ap_paterno;
                    $nombres_apat = $estudiante->ap_paterno;
                    $nombres_amat = $estudiante->ap_materno;

                    // PRESENCIAL
                    if($modalidad==1){
                        $asunto = $rs_datos->p_negacion_asunto;
                        $msg_text = $rs_datos->p_negacion;// plantila email
                        $msg_cel = $rs_datos->p_negacion_2;//msg what
                    }else{
                        // VIRTUAL
                        $asunto = $rs_datos->p_negacion_asunto_v;
                        $msg_text = $rs_datos->p_negacion_v;// plantila email
                        $msg_cel = $rs_datos->p_negacion_2_v;// msg what
                    }
                    $confirm_email = $rs_datos->p_negacion_email;
                    $confirm_msg   = $rs_datos->p_negacion_msg;

                    $flujo_ejecucion = 'NOINVITADO';
                    $asunto = '[LO SENTIMOS] '.$rs_datos->nombre_evento;
                    $id_plantilla = session('eventos_id'); //ID EVENTO
                    $plant_confirmacion = $rs_datos->p_negacion;
                    $plant_confirmacion_2 = $rs_datos->p_negacion_2;

                    $celular = $estudiante->codigo_cel.$estudiante->celular;
                    $dni = $estudiante->dni_doc;
                    $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
                    $email = $estudiante->email;

                    $msg_text = $rs_datos->p_negacion;// plantila emailp_preregistro_2
                    $msg_cel  = $rs_datos->p_negacion_2;// plantila whats

                    // falta probar x msg y whats

                    #if($rs_datos->confirm_email == 1){
                    if($confirm_email == 1){
                        if($email != ""){$celular = '';$msg_cel = '';
                            $this->insertaHistoriaEmail('EMAIL',$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $celular, $msg_text, $msg_cel, $from_name, $from_email);
                        }
                    }else{
                        // no inserta en la tb historia_email
                        $error .= 'No se envio el email porque no esta habilitado en la configuración del EVENTO.<br>';
                    }
                    // MSG WHATS
                    if($confirm_msg == 1){
                        if($celular != "" && strlen($estudiante->celular)>= 9){$msg_text = '';
                            $this->insertaHistoriaEmail('WHATS',$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $celular, $msg_text, $msg_cel, $from_name, $from_email);
                        }
                    }else{
                        $error .= 'No se envio el whatsapp porque no esta habilitado en la configuración del EVENTO.<br>';
                    }
                    $rs_datos = "";
                    $estudiante = "";

            }

        }

        Cache::flush();


        return redirect()->back()->with('si', 'Se enviarón exitosamente '.$i. ' correos.')->with('no', 'Se enviarón exitosamente '.$j. ' correos.')->with('error', $error);

    }
    //METODOS

    private function filtros($data=[])
    {
        $pag = $data['pag']??0;
        $tipo = $data['tipo']??'';
        $modalidad = $data['modalidad']??'';
        $grupo = $data['grupo']??'';
        $apro = $data['apro']??'';
        $region = $data['region']??'';
        $q = $data['q']??'';
        $random = $data['random']??false;
        $chocolated = $data['choco']??false;
        $st = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->when($q!='', function($query) use($q){
                $query->where(function($query2) use($q){
                    $query2->where("estudiantes.dni_doc", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.nombres", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.ap_paterno", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.ap_materno", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.cargo", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.grupo", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.organizacion", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.accedio", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.email", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.email_labor", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.profesion", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.direccion", "LIKE", '%'.$q.'%')
                        ->orWhere("estudiantes.celular", "LIKE", '%'.$q.'%');
                });
            })
            ->when($tipo > 0, function($query) use($tipo){
                $query->where('de.estudiantes_tipo_id',$tipo);
            })
            ->when($modalidad > 0, function($query) use($modalidad){
                $query->where('de.modalidad_id',$modalidad);
            })
            ->when($apro != '', function($query) use($apro){
                $val = ($apro == 'SI' || $apro == 'NO') ? $apro:'';
                $query->where('estudiantes.track',$val);
            })
            ->when($grupo != '', function($query) use($grupo){
                $query->where('de.dgrupo',$grupo);
            })
            ->when($region > 0, function($query) use($region){
                $cond = [];
                if($region > 0){
                    $query->where('estudiantes.pais','!=','');
                    if($region == 1 || $region == 2){//el pais es PERu si se selecciona LIMA o regiones
                        $query->where('estudiantes.pais','PERU');
                        $operator = $region == 1 ? '=' : '!=';//1 es LIMA, sino no es LIMA
                        $query->where(function($query2) use ($operator){
                            $query2->where("estudiantes.region", $operator, 'LIMA')
                                ->orWhere("estudiantes.provincia", $operator, 'LIMA');
                        });
                    }
                    if($region == 3){
                        $query->where('estudiantes.pais', '!=','PERU');
                    }
                }
            })
            ->where('de.estudiantes_tipo_id',1)
            ->where('de.eventos_id',session('eventos_id'))
            ->when($chocolated, function($query) {
                $query->whereNotIn('de.dtrack',['SI', 'NO']);
            })
            ->when($random, function($query) {
                $query->inRandomOrder();
            }, function($query) {
                $query->orderBy('estudiantes.id', request('sorted', 'DESC'));
            })
            ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno',
                'estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo',
                'estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.email',
                'estudiantes.accedio','estudiantes.created_at','estudiantes.track','de.modalidad_id','de.estudiantes_tipo_id')
        ;
        return $pag<=0 ? $st->get() : $st->paginate($pag);
    }


    private function getEstudiante($id, $evento_id=0)
    {
        return Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('estudiantes.id', $id)
            ->when($evento_id > 0, function($query) use($evento_id){
                $query->where('de.eventos_id',$evento_id);
            })
            ->first();
    }
    private function getPlantillaXEvento($evento_id, $existe=0)
    {
        return \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
            ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
            ->when($existe != 0, function($query){
                $query->join('e_plantillas_virtual as pv', 'eventos.id','=','pv.eventos_id');
            })
            ->where('eventos.id',$evento_id)
            ->orderBy('eventos.id', 'desc')
            ->first();
    }
    private function actualizaTrackXEstDet($dni, $evento_id, $dtrack='SI'){
        return DB::table('estudiantes_act_detalle')
            ->where('eventos_id', $evento_id)
            ->where('estudiantes_id', $dni)
            ->update([
                'dtrack'=> $dtrack,
            ]);

    }
    private function actualizaTrackXEstudiante($id, $track = 'SI')
    {
        return DB::table('estudiantes')->where('id',$id)->update([
            //'tipo_id' => 4,  APROBADO
            'track'=>$track
        ]);
    }

    private function insertaHistoriaEmail($tipo,$dni, $id_plantilla, $flujo_ejecucion, $asunto, $nom, $email, $celular, $msg_text, $msg_cel, $from_name, $from_email)
    {
        if(!$tipo)$tipo = 'EMAIL';
        $data = [
            'tipo'              => $tipo,
            'fecha'             => Carbon::now(),
            'estudiante_id'     => $dni,
            'plantillaemail_id' => $id_plantilla,
            'flujo_ejecucion'   => $flujo_ejecucion,
            'eventos_id'        => $id_plantilla,
            'fecha_envio'       => '2000-01-01',
            'asunto'            => $asunto,
            'nombres'           => $nom,
            'email'             => $email,
            'celular'           => $celular,
            'msg_text'          => $msg_text,
            'msg_cel'           => $msg_cel,
            'from_nombre'       => $from_name,
            'from_email'        => $from_email,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
        if($tipo!='EMAIL')unset($data['from_nombre'], $data['from_email']);
        return DB::table('historia_email')->insert($data);
    }
    private function insertHistory(
        $tipo, $flujo_ejecucion, $plantilla_id, $evento_id, $email, $asunto, $estudiante_id, $nombre, $ape_pat, $ape_mat, $dni,
        $cel_cod, $cel_nro, $msg_text, $msg_cel, $from_nombre, $from_email, $accedio
    ){
        $campania_id = 0;
        $actividad_id =0;
        $fecha_envio = '2000-01-01';
        $status = -1;
        $data = [
            "tipo"         => $tipo,
            'flujo'        => $flujo_ejecucion,
            'plantilla_id' => $plantilla_id,
            'campania_id'  => $campania_id,
            'evento_id'    => ($evento_id) ? $evento_id : 0,
            'actividad_id' => $actividad_id,
            'fecha_envio'  => $fecha_envio,
            'email'        => $email??'',
            'asunto'       => $asunto,
            'estudiante_id'=> $estudiante_id,
            'nombre'       => $nombre??'',
            'ape_pat'      => $ape_pat??'',
            'ape_mat'      => $ape_mat??'',
            'dni'          => $dni,
            'cel_cod'      => $cel_cod??'',
            'cel_nro'      => $cel_nro??'',
            'accedio'      => $accedio??'',
            'msg_text'     => $msg_text,
            'msg_cel'      => $msg_cel,
            'from_nombre'  => $from_nombre,
            'from_email'   => $from_email,
            'status'       => $status
        ];
        return $data;
        //return HistoryEmails::create($data);
    }

    }
