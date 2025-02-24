<?php

namespace App\Http\Controllers;

use DB;use File;
use Auth;
use Mail;
use Alert;
use Cache;
//use Excel;
use Maatwebsite\Excel\Facades\Excel;
use App\TipoDoc;
use App\Distrito,App\Evento;
use App\Provincia;
use Carbon\Carbon;
use App\ConsultaDNI;
use App\Departamento;
use App\EstudianteTemp;
use Jenssegers\Date\Date;

use Illuminate\Http\Request;
use App\AccionesRolesPermisos;
use App\Actividade;
use App\Estudiante, App\Emails;
use App\estudiantes_act_detalle;
use App\Exports\EstudianteExport;
use App\Imports\EstudianteImport;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\EstudianteRepository;

class EstudianteController extends Controller
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

  public function index(Request $request, EstudianteRepository $repository)
  {
    $this->actualizarSesion();
    
    //VERIFICA SI TIENE EL PERMISO
    if (!isset(session("permisosTotales")["estudiantes"]["permisos"]["inicio"])) {
      Auth::logout();
      return redirect('/login');
    }

    if ($request->eventos_id != "") {
      session(['eventos_id' => $request->eventos_id]);
      $evento = Evento::findOrFail($request->eventos_id);
            $evento_nom = array('nombre'=>$evento->nombre_evento, 'id'=>$evento->id, 'maestria'=>$evento->lugar);
      session([
        'eventos_id' => $request->get('eventos_id'),
        'evento'     => $evento_nom,
        'evento_tipo'=> $evento->tipo
         ]);
    }
    #dd(session('eventos_id'));
    if (session('eventos_id') == false) return redirect()->route('caii.index');

    if ($request->get('pag')) {
      Cache::flush();
      session(['pag' => $request->get('pag')]);
      $pag = session('pag');
    } else {
      $pag = 15;
    }
    ////PERMISOS
    if (Cache::has('permisos.all')) {
      $permisos = Cache::get('permisos.all');
    } else {

      $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
      $permParam["modulo_alias"] = "estudiantes"; //caii
      $permParam["roles"] = $roles;
      $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

      //Cache::put('permisos.all', $permisos, 5);
      Cache::put('permisos.all', $permisos, 1);
    }
    ////FIN DE PERMISOS

    $departamentos_datos = Cache::rememberForever('depa', function () {
      return Departamento::select('ubigeo_id', 'nombre')
        ->whereIn('ubigeo_id', ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25'])
        ->get();
    });

    $tipos = DB::table('estudiantes_tipo')->get();
    $modalidades = DB::table('tc_modalidades')->get();

    /*$productCategory = Product:where('id', $productId)
            ->leftJoin('category', 'product.category', '=', 'category.id')
            ->select('product.id','category.name')->first();*/
    $data = array(
      "s" => $request->get('s'),
      "st" => $request->get('st'),
      "reg" => $request->get('reg'),
      "mod" => $request->get('mod'),
      "g" => $request->get('g'),
      "pag" => $pag,
      "page" => request('page', 1),
      "sorted" => request('sorted', 'DESC'),
      "eventos_id" => session('eventos_id'),
      "tipo" => "E"
    );
    $estudiantes_datos = $repository->search($data);
    //dump($estudiantes_datos);

    // BLOQUEO DE IMPORT / DE BAJA/ REENVIAR INVITACIÓN
    $rs_datos = DB::table('eventos as e')
      ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
      ->join('e_formularios as f', 'e.id', '=', 'f.eventos_id')
      ->where('e.id', session('eventos_id'))
      ->orderBy('e.id', 'desc')
      ->count();

    if ($rs_datos == 0) {
      alert()->warning('Elimine el evento y vuelva a crear el evento, plantillas y formulario.', 'Error')->persistent('Close');
      return redirect()->route('caii.index');
    }

    $rs_datos = DB::table('eventos as e')
      ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
      ->join('e_formularios as f', 'e.id', '=', 'f.eventos_id')
      ->where('e.id', session('eventos_id'))
      ->orderBy('e.id', 'desc')
      ->first();

    $f_limite = \Carbon\Carbon::parse($rs_datos->fechaf_evento);

    $hoy = Carbon::now();
    $evento_vencido = "";

    //if($hoy >= $f_limite)$evento_vencido = 1;
    if ($hoy->greaterThanOrEqualTo($f_limite)) {
      $evento_vencido = 1;
    } else {
      $evento_vencido = 0;
    }
    #dd($data,$estudiantes_datos);
    return view('estudiantes.estudiantes', compact('estudiantes_datos', 'departamentos_datos', 'tipos','modalidades', 'evento_vencido', 'permisos'));
  }

  
  public function create()
  {
    $this->actualizarSesion();
    //VERIFICA SI TIENE EL PERMISO
    if (!isset(session("permisosTotales")["estudiantes"]["permisos"]["nuevo"])) {
      Auth::logout();
      return redirect('/login');
    }

    if (session('eventos_id') == false) {
      return redirect()->route('caii.index');
    }
    //primera forma
    //$entidades_datos = DB::table('entidades')->get();
    $tipos = DB::table('estudiantes_tipo')->get();
    $countrys = DB::table('country')->select('name', 'phonecode', 'nicename')->get();
    $tipo_doc = TipoDoc::all();
    $grupos = DB::table('est_grupos')->whereNotNull('eventos_id')->get();
    $modalidades = DB::table('tc_modalidades')->get();
    //---------------
    /*$departamentos_datos = DB::table('ubigeos')
        ->select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();*/
    //------------------

    //$departamentos_datos = Departamento::pluck('ubigeo_id','nombre');
    //$departamentos_datos = Departamento::all();
    $departamentos_datos = Departamento::select('ubigeo_id', 'nombre')
      ->whereIn('ubigeo_id', ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25'])
      ->get();

    return view('estudiantes.create', compact('departamentos_datos', 'tipo_doc', 'tipos', 'countrys', 'grupos', 'modalidades'));
  }

  public function getDepartamentos(Request $request, $id)
  {
    if ($request->ajax()) {
      $provincias = Departamento::departamentos($id);
      return response()->json($provincias);
    }
  }

  public function getProvincias(Request $request, $id)
  {
    if ($request->ajax()) {
      $provincias = Provincia::provincias($id);
      return response()->json($provincias);
    }
  }
  public function getProvinciasEdit(Request $request, $aa, $id)
  {
    if ($request->ajax()) {
      $provincias = Provincia::provincias($id);
      return response()->json($provincias);
    }
  }

  public function getDistritos(Request $request, $id)
  {
    if ($request->ajax()) {
      $distritos = Distrito::distritos($id);
      return response()->json($distritos);
    }
  }
  public function getDistritosEdit(Request $request, $aa, $id)
  {
    if ($request->ajax()) {
      $distritos = Distrito::distritos($id);
      return response()->json($distritos);
    }
  }
  public function getDNI(Request $request, $id, $evento = 0)
  {
    if ($request->ajax()) {
      $selectDNI = ConsultaDNI::selectDNI($id, $evento);
      return response()->json($selectDNI);
    }
  }

  public function EstudianteExport2(EstudianteRepository $export)
  {
    $data = array(
      //"eventos_id" => session('eventos_id'),
      "sorted"      => request('sorted', 'DESC'),
      "eventos_id"  => 1,
      "tipo"        => "1",//2
      "all"         => "1",
      "st"          => "1",
      //"pag"       => 3000
    );
    
    return Excel::download(new EstudianteExport($data, $export), 'Estudiantes.xlsx');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request)
  {
    //return $request->all();

    $this->validate($request, [
      'inputdni' => 'required',
      //'inputdni'=>'required|unique:estudiantes,dni_doc',
      'cboTipDoc' => 'required'
      //'inputEmail'=>'required',
    ]);

    $error = "";
    $dni_doc = $request->input('inputdni');
    $existe = $request->input('existe');

    $rs_datos = DB::table('eventos as e')
      ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
      ->join('e_formularios as f', 'e.id', '=', 'f.eventos_id')
      ->where('e.id', session('eventos_id'))
      ->orderBy('e.id', 'desc')
      ->first();

    if ($rs_datos) {
      $evento_id = $rs_datos->id;
      $fechai_evento = $rs_datos->fechai_evento;
      $fechaf_evento = $rs_datos->fechaf_evento;
    } else {
      alert()->success('Ingrese a un evento', 'Alerta');
      return redirect()->route('caii.index');
    }

    if ($request->input('tipo_id') == 1) {
      $flag = 'P';
    } else {
      $flag = 'I';
    }

    // si existe DNI
    if ($existe == 2) {
      alert()->warning('Alerta', 'El participante ya esta registrado.');
      return redirect()->back();
    }

    if ($existe == 0) {

      // NO EXISTE DNI
      //agregar contralador db:  use DB; // para poder have insert
      DB::table('estudiantes')->insert([
        'dni_doc' => mb_strtoupper($request->input('inputdni')),
        'ap_paterno' => mb_strtoupper($request->input('inputApe_pat')),
        'ap_materno' => mb_strtoupper($request->input('inputApe_mat')),
        'nombres' => mb_strtoupper($request->input('inputNombres')),
        'fecha_nac' => mb_strtoupper($request->input('inputFechaNac')),
        'grupo' => mb_strtoupper($request->input('grupo')),
        'cargo' => mb_strtoupper($request->input('inputCargo')),
        'organizacion' => mb_strtoupper($request->input('inputOrganizacion')),
        'profesion' => mb_strtoupper($request->input('inputProfesion')),
        'direccion' => mb_strtoupper($request->input('inputDireccion')),
        'telefono' => mb_strtoupper($request->input('inputTelefono')),
        'telefono_labor' => mb_strtoupper($request->input('inputTelefono_2')),
        'codigo_cel' => $request->input('codigo_cel'),
        'celular' => mb_strtoupper($request->input('inputCelular')),
        'email' => $request->input('inputEmail'),
        'email_labor' => $request->input('inputEmail_2'),
        'sexo' => $request->input('cboSexo'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        'estado' => 1,
        //'accedio'=>$request->input('accedio'),
        'accedio' => 'NO',
        'track' => $request->input('track'),

        'pais' => $request->input('pais'),
        'region' => $request->input('region'),
        'tipo_documento_documento_id' => $request->input('cboTipDoc'),
        'news' => $request->input('check_newsletter'),
        'tipo_id' => $request->input('tipo_id'),
        'ip' => request()->ip(),
        'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
        'entidad' => $request->input('entidad'),
        'ubigeo_ubigeo_id' => $request->input('cboDistrito')
      ]);

      $id_dni = DB::getPdo()->lastInsertId();

      DB::table('audi_estudiantes')->insert([
        'id_estudiante' => $id_dni,
        'dni_doc' => mb_strtoupper($request->input('inputdni')),
        'ap_paterno' => mb_strtoupper($request->input('inputApe_pat')),
        'ap_materno' => mb_strtoupper($request->input('inputApe_mat')),
        'nombres' => mb_strtoupper($request->input('inputNombres')),
        'fecha_nac' => mb_strtoupper($request->input('inputFechaNac')),
        'grupo' => mb_strtoupper($request->input('grupo')),
        'cargo' => mb_strtoupper($request->input('inputCargo')),
        'organizacion' => mb_strtoupper($request->input('inputOrganizacion')),
        'profesion' => mb_strtoupper($request->input('inputProfesion')),
        'direccion' => mb_strtoupper($request->input('inputDireccion')),
        'telefono' => mb_strtoupper($request->input('inputTelefono')),
        'telefono_labor' => mb_strtoupper($request->input('inputTelefono_2')),
        'celular' => mb_strtoupper($request->input('inputCelular')),
        'email' => $request->input('inputEmail'),
        'email_labor' => $request->input('inputEmail_2'),
        'sexo' => $request->input('cboSexo'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        'estado' => 1,
        'accedio' => $request->input('accedio'),
        'track' => $request->input('track'),
        'tipo_documento_documento_id' => $request->input('cboTipDoc'),
        'ip' => request()->ip(),
        'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
        'entidad' => $request->input('entidad'),
        'ubigeo_ubigeo_id'     => $request->input('cboDistrito'),
        'accion'  => 'INSERT',
        'usuario' => \Auth::User()->email
      ]);
    } else {
      //Modificar estudiante
      DB::table('estudiantes')->where('dni_doc', $dni_doc)->update([
        'dni_doc' => mb_strtoupper($request->input('inputdni')),
        'ap_paterno' => mb_strtoupper($request->input('inputApe_pat')),
        'ap_materno' => mb_strtoupper($request->input('inputApe_mat')),
        'nombres' => mb_strtoupper($request->input('inputNombres')),
        'fecha_nac' => mb_strtoupper($request->input('inputFechaNac')),
        'grupo' => mb_strtoupper($request->input('grupo')),
        'cargo' => mb_strtoupper($request->input('inputCargo')),
        'organizacion' => mb_strtoupper($request->input('inputOrganizacion')),
        'profesion' => mb_strtoupper($request->input('inputProfesion')),
        'direccion' => mb_strtoupper($request->input('inputDireccion')),
        'telefono' => mb_strtoupper($request->input('inputTelefono')),
        'telefono_labor' => mb_strtoupper($request->input('inputTelefono_2')),
        'codigo_cel' => $request->input('codigo_cel'),
        'celular' => mb_strtoupper($request->input('inputCelular')),
        'email' => $request->input('inputEmail'),
        'email_labor' => $request->input('inputEmail_2'),
        'sexo' => $request->input('cboSexo'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        'estado' => 1,
        'track' => $request->input('track'),

        'pais' => $request->input('pais'),
        'region' => $request->input('region'),
        'tipo_documento_documento_id' => $request->input('cboTipDoc'),
        'news' => $request->input('check_newsletter'),
        'tipo_id' => $request->input('tipo_id'),
        'ip' => request()->ip(),
        'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),

      ]);
    }

    // EXISTE DNI - solo guarda detalle


    if (!is_null($request->input('check_newsletter'))) {
      DB::table('newsletters')->insert([
        'estado' => 1,
        'estudiante_id' => $request->input('inputdni'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
      ]);
    }

    /* ADD TIPO */

    DB::table('estudiantes_act_detalle')->insert([
        'eventos_id'       => session('eventos_id'),
        'estudiantes_id'   => mb_strtoupper($dni_doc),
        'actividades_id'   => 0,
        'estudiantes_tipo_id'=> $request->input('tipo_id'),
        'modalidad_id'       => $request->input('mod'),
        'confirmado'       => 0,
        'estado'           => 1,
        //'fecha_conf'     => Carbon::now(),
        'dgrupo'           => mb_strtoupper($request->input('grupo')),
        'created_at'       => Carbon::now(),
        'daccedio'         => 'NO',
        'dtrack'           => ''

    ]);

    // AUMENTAR inscritos_pre inscritos_invi
    $xtipo = $request->input('tipo_id')?$request->input('tipo_id'):0;
    if($xtipo==1){
      $columna = 'inscritos_pre';
    }else{
      $columna = 'inscritos_invi';
    }
    if($xtipo!=0){
      DB::table('eventos')->where('id', session('eventos_id'))
                        ->increment("$columna", 1);
    }
    

    // FLAG TIPO I O P
    $estudiante = Estudiante::where('dni_doc', $dni_doc)->first();

    $dni = $estudiante->dni_doc;
    $nom = $estudiante->nombres . ' ' . $estudiante->ap_paterno;
    $email = $estudiante->email;

    if (!empty($rs_datos->email_asunto)) {
      $from = Emails::findOrFail($rs_datos->email_id);
      $from_email = $from->email;
      $from_name  = $from->nombre;
    } else {
      $from_email = config('mail.from.address');
      $from_name  = config('mail.from.name');
    }

    if ($flag == "I") {

      $flujo_ejecucion = 'INVITACION';

      /* if (!empty($rs_datos->email_asunto)) {
        $asunto = '[INVITACIÓN] ' . $rs_datos->email_asunto;
      } else {
        $asunto = '[INVITACIÓN] ' . $rs_datos->nombre_evento;
      } */
      //$asunto = '[INVITACIÓN] '.$rs_datos->nombre_evento;

      $id_plantilla = session('eventos_id'); //ID EVENTO
      $plant_confirmacion = $rs_datos->p_conf_inscripcion;
      $plant_confirmacion_2 = $rs_datos->p_conf_inscripcion_2;

      $celular = $estudiante->codigo_cel . $estudiante->celular;
      $dni = $estudiante->dni_doc;
      $nom = $estudiante->nombres . ' ' . $estudiante->ap_paterno;
      $email = $estudiante->email;

      $msg_text = $rs_datos->p_conf_inscripcion; // plantila email
      $msg_cel  = $rs_datos->p_conf_inscripcion_2; // plantila whats

      // falta probar x msg y whats

      if ($rs_datos->p_conf_inscripcion_email == 1) {

        if ($email != "") {
          #Envio de mensaje Email
          $asunto   = $rs_datos->p_conf_inscripcion_asunto? '[INVITACIÓN] ' . $rs_datos->p_conf_inscripcion_asunto : '';

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
            'celular'           => "", //$celular,
            'msg_text'          => $msg_text,
            'msg_cel'           => "", //$msg_cel,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
            'from_nombre'       => $from_name,
            'from_email'        => $from_email,
          ]);
        }
      } else {
        // no inserta en la tb historia_email
        $error .= "No se envío el <strong>email</strong> porque no esta habilitado<br>";
      }

      // MSG WHATS

      if ($rs_datos->p_conf_inscripcion_msg == 1) {

        if ($estudiante->celular != "" && strlen($estudiante->celular) >= 9) {

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
            'email'             => "", //$email,
            'celular'           => $celular,
            'msg_text'          => "", //$msg_text,
            'msg_cel'           => $msg_cel,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
          ]);
        }
      } else {
        $error .= "No se envio el <strong>whatsapp</strong> porque no esta habilitado";
      }
    } else {

      // SI ES PRE REGISTRO

      $flujo_ejecucion = 'PREREGISTRO';
      $asunto = '[CONFIRMACIÓN PG] ';
      $id_plantilla = session('eventos_id'); //ID EVENTO
      $plant_confirmacion = $rs_datos->p_conf_inscripcion;
      $plant_confirmacion_2 = $rs_datos->p_conf_inscripcion_2;

      $celular = $estudiante->codigo_cel . $estudiante->celular;
      $dni = $estudiante->dni_doc;
      $nom = $estudiante->nombres . ' ' . $estudiante->ap_paterno;
      $email = $estudiante->email;

      $msg_text = ""; //$rs_datos->p_preregistro;// plantila emailp_preregistro_2
      $msg_cel  = $rs_datos->p_preregistro_2; // plantila whats

      if (!empty($rs_datos->p_preregistro_asunto)) {
        $asunto = $rs_datos->p_preregistro_asunto;
      }
      #dd($rs_datos->p_preregistro_email,$rs_datos->p_preregistro_msg);
      // MSG EMAIL
      if ($rs_datos->p_preregistro_email == 1) {

        if ($email != "") {
           #Envio de mensaje Email
           $asunto   = $rs_datos->p_preregistro_asunto;
           $celular  = "";
           $msg_text = $rs_datos->p_preregistro;// plantilLa email
           $msg_cel  = "";//$datos->p_preregistro_2;// plantila whats

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
            'celular'           => "", //$celular,
            'msg_text'          => $msg_text,
            'msg_cel'           => "", //$msg_cel,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
            'from_nombre'       => $from_name,
            'from_email'        => $from_email,
          ]);
        }
      } else {
        // no inserta en la tb historia_email
        $error .= "No se envío el <strong>email</strong> porque no esta habilitado<br>";
      }
      
      // MSG WHATS
      
      if ($rs_datos->p_preregistro_msg == 1) {
        
        if ($estudiante->celular != "" && strlen($estudiante->celular) >= 9) {
          $asunto   = "";
          $celular  = $estudiante->codigo_cel . $estudiante->celular;
          $msg_text = ""; //$rs_datos->p_preregistro;// plantila email
          $msg_cel  = $rs_datos->p_preregistro_2; // plantila whats

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
            'email'             => $email,
            'celular'           => $celular,
            'msg_text'          => $msg_text,
            'msg_cel'           => $msg_cel,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
          ]);
        }
      } else {
        $error .= 'No se envio el whatsapp porque no esta habilitado';
      }
      
    }

    $auth = 1;
    Cache::flush();

    //return redirect()->back()->with('alert', 'Registro guardado exitosamente.');
    //return redirect()->route('estudiantes.create')->with('error','Success message');
    if ($error) {
      if ($auth == 1) {
        return redirect()->back()->with('alert', $error)
          ->with('info', 'Registro Grabado');
      }
      return redirect()->back()->with('alert', $error);
    }

    alert()->success('Mensaje Satisfactorio', 'Registro grabado exitosamente.');

    return redirect()->route('estudiantes.index');
    //return redirect()->back();
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
    if (!isset(session("permisosTotales")["estudiantes"]["permisos"]["mostrar"])) {
      Auth::logout();
      return redirect('/login');
    }
    $eventos_id = session('eventos_id');

    $tipos = DB::table('estudiantes_tipo')->get();
    $countrys = DB::table('country')->select('name', 'phonecode', 'nicename')->get();
    $tipo_doc = TipoDoc::all();
    $grupos = DB::table('est_grupos')->get();

    //$estudiantes_datos = DB::table('estudiantes')->where('id', $id)->first();
    //$order = Order::findOrFail($orderId);
    $estudiantes_datos = Estudiante::findOrFail($id);

    $distrito = $estudiantes_datos->ubigeo_ubigeo_id;

    $dis = substr($distrito, 0, 4);

    $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis . '%', 'id2' => $dis]);

    $prov = substr($distrito, 0, 2);
    $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov . '%', 'id2' => $prov, 'id3' => 4]);

    $departamentos_datos = Departamento::select('ubigeo_id', 'nombre')
      ->whereIn('ubigeo_id', ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25'])
      ->get();

    return view('estudiantes.show', compact('estudiantes_datos', 'tipo_doc', 'countrys', 'tipos', 'departamentos_datos', 'grupos', 'eventos_id'));
    //return view('estudiantes.show',compact('estudiantes_datos','tipo_doc', 'countrys', 'tipos','departamentos_datos','provincias_datos','distritos_datos','prov','dis'));
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
    if (!isset(session("permisosTotales")["estudiantes"]["permisos"]["editar"])) {
      Auth::logout();
      return redirect('/login');
    }

    if (session('eventos_id') == false) {
      return redirect()->route('caii.index');
    }

    $eventos_id = session('eventos_id');

    $tipos = DB::table('estudiantes_tipo')->get();
    $tipo_doc = TipoDoc::all();
    //$estudiantes_datos = DB::table('estudiantes')->where('id', $id)->first();
    $estudiantes_datos = \App\Estudiante::
      join('estudiantes_act_detalle as de', 'de.estudiantes_id', '=', 'estudiantes.dni_doc')
      ->select('estudiantes.id', 'estudiantes.tipo_documento_documento_id', 'estudiantes.dni_doc', 'estudiantes.ap_paterno', 'estudiantes.ap_materno', 'estudiantes.nombres', 'estudiantes.pais', 'estudiantes.region', 'estudiantes.ubigeo_ubigeo_id', 'de.dgrupo as grupo', 'de.estudiantes_tipo_id as tipo_id', 'estudiantes.profesion', 'estudiantes.organizacion', 'estudiantes.cargo', 'estudiantes.email', 'estudiantes.celular', 'estudiantes.codigo_cel', 'estudiantes.telefono', 'de.daccedio as accedio', 'de.dtrack as track', 'de.estado','de.modalidad_id')
      ->where('estudiantes.id', $id)
      ->where('de.eventos_id', session('eventos_id'))
      ->firstOrFail();
    
    $distrito = $estudiantes_datos->ubigeo_ubigeo_id;

    $countrys = DB::table('country')->select('name', 'phonecode', 'nicename')->get();
    #$grupos = DB::table('est_grupos')->get();
    $grupos = DB::table('est_grupos')->whereNotNull('eventos_id')->get();
    $dis = substr($distrito, 0, 4);
    $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis . '%', 'id2' => $dis]);

    $prov = substr($distrito, 0, 2);
    $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov . '%', 'id2' => $prov, 'id3' => 4]);

    $departamentos_datos = Departamento::select('ubigeo_id', 'nombre')
      ->whereIn('ubigeo_id', ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25'])
      ->get();
    $modalidades = DB::table('tc_modalidades')->get();

    // BLOQUEO DE IMPORT / DE BAJA/ REENVIAR INVITACIÓN
    $rs_datos = DB::table('eventos as e')
      ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
      ->join('e_formularios as f', 'e.id', '=', 'f.eventos_id')
      ->where('e.id', session('eventos_id'))
      ->orderBy('e.id', 'desc')
      ->count();

    if ($rs_datos == 0) {
      alert()->success('Elimine el evento y vuelva a crear el evento, plantillas y formulario.', 'Error')->persistent('Close');
      return redirect()->route('caii.index');
    }

    $rs_datos = DB::table('eventos as e')
      ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
      ->join('e_formularios as f', 'e.id', '=', 'f.eventos_id')
      ->where('e.id', session('eventos_id'))
      ->orderBy('e.id', 'desc')
      ->first();

    /*$f_limite = \Carbon\Carbon::parse($rs_datos->fechaf_evento);
            $hoy = Carbon::now();
            if($hoy >= $f_limite)$evento_vencido = 1;*/

    $evento_vencido = 0;
    $f_limite = \Carbon\Carbon::parse($rs_datos->fechaf_evento);
    $hoy = Carbon::now();
    //return "fecha_limite: $f_limite - hoy: $hoy";

    // CIERRE DE FORM
    if ($hoy->greaterThan($f_limite)) {
      $evento_vencido = 1;
    } else {
      $evento_vencido = 0;
    }

    $datos_h = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
      ->join('eventos as e', 'de.eventos_id', '=', 'e.id')
      ->where('estudiantes.dni_doc', '=', $estudiantes_datos->dni_doc)
      ->select('e.id', 'e.nombre_evento', 'e.fechai_evento', 'e.fechaf_evento', 'e.gafete')
      ->orderBy('e.id', 'desc')
      ->get();
    
    $datos_act = Estudiante::join('actividades_estudiantes as de', 'de.estudiantes_id', '=', 'estudiantes.dni_doc')
      ->join('actividades as a', 'a.id', '=', 'de.actividad_id')
      ->where('estudiantes.dni_doc', '=', $estudiantes_datos->dni_doc)
      ->select('a.eventos_id', 'a.titulo', 'a.subtitulo', 'a.vacantes', 'a.inscritos', 'a.hora_inicio', 'a.hora_final')
      ->orderBy('a.fecha_desde', 'asc')
      ->orderBy('a.hora_inicio', 'asc')
      ->get();

    //ACTIVAR OPC SELECCIONAR ACTIVIDADES:
    session(['user' => $estudiantes_datos->dni_doc]);
    
    return view('estudiantes.edit', compact('estudiantes_datos', 'tipo_doc', 'tipos', 'countrys', 'departamentos_datos', 'provincias_datos', 'distritos_datos', 'prov', 'dis', 'grupos', 'eventos_id', 'evento_vencido', 'datos_h', 'datos_act','modalidades'));
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
    $this->validate($request, [

      'inputdni' => 'required|unique:estudiantes,dni_doc,' . $id,
      'cboTipDoc' => 'required'
    ]);



    $xdni = $request->input('inputdni');
    $rs_estudiantes = DB::table('estudiantes')->select('tipo_id', 'dni_doc')
      ->where('id', $id)->first();

    $dni_server = $rs_estudiantes->dni_doc;

    $rs_datos = DB::table('eventos')
      ->where('id', session('eventos_id'))
      ->first();

    if ($rs_datos) {
      $evento_id = $rs_datos->id;
      $fechai_evento = $rs_datos->fechai_evento;
      $fechaf_evento = $rs_datos->fechaf_evento;
    } else {
      alert()->success('Ingrese a un evento', 'Alerta');
      return redirect()->route('caii.index');
    }

    //Actualizamos
    DB::table('estudiantes')->where('id', $id)->update([
      'dni_doc' => mb_strtoupper($request->input('inputdni')),
      'ap_paterno' => mb_strtoupper($request->input('inputApe_pat')),
      'ap_materno' => mb_strtoupper($request->input('inputApe_mat')),
      'nombres' => mb_strtoupper($request->input('inputNombres')),
      'fecha_nac' => mb_strtoupper($request->input('inputFechaNac')),
      'grupo' => mb_strtoupper($request->input('grupo')),
      'cargo' => mb_strtoupper($request->input('inputCargo')),
      'organizacion' => mb_strtoupper($request->input('inputOrganizacion')),
      'profesion' => mb_strtoupper($request->input('inputProfesion')),
      'direccion' => mb_strtoupper($request->input('inputDireccion')),
      'telefono' => mb_strtoupper($request->input('telefono')),
      'telefono_labor' => mb_strtoupper($request->input('inputTelefono_2')),
      'codigo_cel' => $request->input('codigo_cel'),
      'celular' => mb_strtoupper($request->input('inputCelular')),
      'email' => $request->input('inputEmail'),
      'email_labor' => $request->input('inputEmail_2'),
      'sexo' => $request->input('cboSexo'),
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
      'estado'  => $request->input('cboEstado'),
      'accedio' => mb_strtoupper($request->input('accedio')),
      'track'   => mb_strtoupper($request->input('track')),
      'pais'    => $request->input('pais'),
      'region'  => $request->input('region'),

      'tipo_documento_documento_id' => $request->input('cboTipDoc'),
      'tipo_id' => $request->input('tipo_id'),
      'news' => $request->input('check_newsletter'),
      'ip' => request()->ip(),
      'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
      'entidad' => $request->input('entidad'),
      'ubigeo_ubigeo_id' => $request->input('cboDistrito')
    ]);

    DB::table('audi_estudiantes')->insert([
      'id_estudiante' => $id, //DB::getPdo()->lastInsertId()
      'dni_doc' => mb_strtoupper($request->input('inputdni')),
      'ap_paterno' => mb_strtoupper($request->input('inputApe_pat')),
      'ap_materno' => mb_strtoupper($request->input('inputApe_mat')),
      'nombres' => mb_strtoupper($request->input('inputNombres')),
      'fecha_nac' => mb_strtoupper($request->input('inputFechaNac')),
      'grupo' => mb_strtoupper($request->input('grupo')),
      'cargo' => mb_strtoupper($request->input('inputCargo')),
      'organizacion' => mb_strtoupper($request->input('inputOrganizacion')),
      'profesion' => mb_strtoupper($request->input('inputProfesion')),
      'direccion' => mb_strtoupper($request->input('inputDireccion')),
      'telefono' => mb_strtoupper($request->input('telefono')),
      'telefono_labor' => mb_strtoupper($request->input('inputTelefono_2')),
      'celular' => mb_strtoupper($request->input('inputCelular')),
      'email' => $request->input('inputEmail'),
      'email_labor' => $request->input('inputEmail_2'),
      'sexo' => $request->input('cboSexo'),
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
      'estado' => $request->input('cboEstado'),
      'accedio' => $request->input('accedio'),
      'track' => $request->input('track'),
      'tipo_documento_documento_id' => $request->input('cboTipDoc'),
      'ip' => request()->ip(),
      'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
      'entidad' => $request->input('entidad'),
      'ubigeo_ubigeo_id'     => $request->input('cboDistrito'),
      'accion'  => 'UPDATE',
      'usuario' => Auth::user()->email
    ]);

    /* FALTA CAMBIAR SI ACTUALIZAN EL TIPO DEL PARTICIPANTE*/

    $existe_det = DB::table('estudiantes_act_detalle')
      ->where('estudiantes_id', $dni_server)
      ->where('eventos_id', session('eventos_id'))
      ->count();

    if ($existe_det > 0) {

      $rs_update = DB::table('estudiantes_act_detalle')
        ->where('estudiantes_id', $dni_server)
        ->where('eventos_id', session('eventos_id'))
        ->update([
          'estudiantes_id'     => $xdni,
          'estudiantes_tipo_id'=> $request->input('tipo_id'),
          'modalidad_id'       => $request->input('mod'),
          'estado'             => $request->input('cboEstado'),
          'dgrupo'             => mb_strtoupper($request->input('grupo')),
          'daccedio'           => mb_strtoupper($request->input('accedio')),
          'dtrack'             => mb_strtoupper($request->input('track')),
          'created_at'         => Carbon::now()
        ]);
    } else {
      DB::table('estudiantes_act_detalle')->insert([
          'eventos_id'       => session('eventos_id'),
          'estudiantes_id'   => mb_strtoupper($xdni),
          'actividades_id'   => 0,
          'estudiantes_tipo_id'=> $request->input('tipo_id'),
          'modalidad_id'       => $request->input('mod'),
          'confirmado'       => 0,
          'estado'           => 1,
          //'fecha_conf'       => Carbon::now(),
          'dgrupo'           => mb_strtoupper($request->input('grupo')),
          'daccedio'         => mb_strtoupper($request->input('accedio')),
          'dtrack'           => mb_strtoupper($request->input('track')),
          'created_at'       => Carbon::now()

      ]);
    }

    $e_user = DB::table('users')->where('name', $xdni)->first();

    if (!$e_user) {
      DB::table('users')
        ->where('name', $dni_server)
        ->update([
          'name'     => $xdni,
          //'password' => 'A'.$xdni.'Z'
        ]);
    }

    Cache::flush();

    alert()->success('Registro actualizado correctamente.', 'Mensaje Satisfactorio');
    //Redireccionamos
    //return redirect()->route('estudiantes.index');
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

    Estudiante::where('id', $id)->delete();
    //DB::table('estudiantes')->where('id',$id)->delete();
    return redirect()->route('estudiantes.index');
  }

  public function eliminarVarios(Request $request)
  {

    $this->actualizarSesion();
    //VERIFICA SI TIENE EL PERMISO
    if (!isset(session("permisosTotales")["estudiantes"]["permisos"]["eliminar"])) {
      Auth::logout();
      return redirect('/login');
    }

    $tipo_doc = $request->tipo_doc;

    foreach ($tipo_doc as $value) {
      $est = Estudiante::where('id', $value)->first();
      $dni = $est->dni_doc;
      $tpoEst = estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                            ->where('eventos_id', session('eventos_id'))
                            #->where('estudiantes_tipo_id',1)
                            ->select('modalidad_id')
                            ->first();
      $modalidad = $tpoEst->modalidad_id;

      DB::table('audi_estudiantes')->insert([
        'id_estudiante' => $est->id,
        'dni_doc' => $est->dni_doc,
        'ap_paterno' => $est->ap_paterno,
        'ap_materno' => $est->ap_materno,
        'nombres' => $est->nombres,
        'fecha_nac' => $est->fecha_nac,
        'grupo' => $est->grupo,
        'cargo' => $est->cargo,
        'organizacion' => $est->organizacion,
        'profesion' => $est->profesion,
        'direccion' => $est->direccion,
        'telefono' => $est->telefono,
        'telefono_labor' => $est->telefono_labor,
        'celular' => $est->celular,
        'email' => $est->email,
        'email_labor' => $est->email_labor,
        'sexo' => $est->sexo,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        'estado' => $est->estado,
        'accedio' => $est->accedio,
        'track' => $est->track,
        'tipo_documento_documento_id' => $est->tipo_documento_documento_id,
        'ip' => request()->ip(),
        'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
        'entidad' => $est->entidad,
        'ubigeo_ubigeo_id'     => $est->ubigeo_ubigeo_id,
        'accion'  => 'DELETE',
        'usuario' => \Auth::user()->email
      ]);

      ## descontar los que se eliminan
      $nPart = DB::table('actividades_estudiantes')
                      ->where('estudiantes_id',$dni)
                      ->where('eventos_id',session('eventos_id'))
                      ->select('actividad_id as id')->get();
      $a = '';
      foreach($nPart as $n){
        if($modalidad==1)
          DB::table('actividades')->where('id', $n->id)->decrement('inscritos', 1);
        else
          DB::table('actividades')->where('id', $n->id)->decrement('inscritos_v', 1);
        
      }
      
      $cant_inscritos_pre = estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                            ->where('eventos_id', session('eventos_id'))
                            ->where('estudiantes_tipo_id',1)
                            ->count();

      if($cant_inscritos_pre>0){
          DB::table('eventos')->where('id', session('eventos_id'))
                      ->decrement('inscritos_pre', 1);
      }

      $cant_inscritos_invi = estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                            ->where('eventos_id', session('eventos_id'))
                            ->where('estudiantes_tipo_id',2)
                            ->count();

      if($cant_inscritos_invi>0){
          DB::table('eventos')->where('id', session('eventos_id'))
                      ->decrement('inscritos_invi', 1);
      }

        #Estudiante::where('id', $value)->delete();
        estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
          ->where('eventos_id', session('eventos_id'))
          ->delete();
        \App\Historiaemail::where('estudiante_id', $est->dni_doc)
            ->where('eventos_id',session('eventos_id'))->delete();
        DB::table('users')->where('name', $est->dni_doc)->delete();
        DB::table('actividades_estudiantes')->where('estudiantes_id', $est->dni_doc)
          ->where('eventos_id', session('eventos_id'))
          ->delete();

        $firma = public_path('storage/confirmacion/').session('eventos_id').'-'.$est->dni_doc.".pdf";
        if(File::exists($firma)) File::delete($firma);
      
      Cache::flush();
    }
    alert()->error('Eliminado', 'Registros borrados.');
    return redirect()->route('estudiantes.index');
  }

  public function EstudianteImport(Request $request)
  {
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
    $file_path = "storage/excel";
    $file_exc  = "estudiantes.xlsx";

    \Config::set('excel.import.encoding.input', 'iso-8859-1');
    \Config::set('excel.import.encoding.output', 'iso-8859-1');

    $reader = Excel::toArray(new EstudianteImport, public_path($file_path ."/". $file_exc));//ADDED
    $data_exc = $reader[0];

    $flagC = $request["chkPrimeraFila"];
    $chkE_invitacion = $request["chkE_invitacion"];
    
    if ($flagC != "") {
      $contF = 0;
    } else {
      $contF = 1;
    }

    DB::table('estudiantes_temp')->truncate();
    $respta = $repository->estudiante_import($data_exc,$contF,$chkE_invitacion,$request);
  
    return "ok";
  }

  public function validar_fecha_espanol($fecha)
  {
    $valores = explode('/', $fecha);
    if (count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])) {
      return true;
    }
    return false;
  }

  public function EstudianteImportResults()
  {
    $nlista = EstudianteTemp::count();
    $lista  = EstudianteTemp::orderBy("id", "ASC")->get();

    if (count($lista) == 0) {
      die("No hay registros");
    }
    $vEnt = 0;
    /* 
    foreach ($lista as $lstT) {
      if ($lstT->idEntidad != 0) {
        $vEnt = 1;
      }
    } */

    return view("estudiantes.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'nlista' => $nlista]);
  }



  public function search(Request $request)
  {
    if ($request->ajax()) {
      $dato = 'in here';
      return Response::json($dato);
    }
  }


  // Enviar Invitación email y msg
  public function solicitud($id, $dni, $evento, $tipo)
  {
    $msg = "";
    $msg_tipo = "warning";
    $msg_color = "#d2910d";

    $rs_datos = DB::table('eventos as e')
      ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
      ->join('e_formularios as f', 'e.id', '=', 'f.eventos_id')
      ->where('e.id', $evento)
      ->orderBy('e.id', 'desc')
      ->count();

    if ($rs_datos == 0) {
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

    // eventos y plantilla:
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
    // validar por fecha de evento

    $f_limite = \Carbon\Carbon::parse($rs_datos->fechaf_evento);
    $hoy = Carbon::now();
    //return "fecha_limite: $f_limite - hoy: $hoy";

    // CIERRE DE FORM
    if ($hoy->greaterThan($f_limite)) {

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
    /* $rs_user = Estudiante::select('estudiantes.email', 'estudiantes.codigo_cel', 'estudiantes.celular', 'estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno')
      ->where('estudiantes.dni_doc', $dni)
      ->get(); */
    $rs_user = Estudiante::join('estudiantes_act_detalle as de','de.estudiantes_id','=','estudiantes.dni_doc')
      ->where('de.estudiantes_id',$dni)
      ->where('estudiantes.dni_doc',$dni)
      ->where('de.eventos_id',$evento)
      ->select('estudiantes.email', 'estudiantes.codigo_cel', 'estudiantes.celular', 'estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno','de.modalidad_id','estudiantes.dni_doc',)
      ->first();

    if (!$rs_user) {
      $msg = 'El DNI no esta registrado. ';
    }

    if ($rs_user->email == "") {
      $msg = "El campo email esta vacio. ";
    }
    if ($rs_user->celular == "" || strlen($rs_user->celular) <= 5) {
      $msg = "El campo celular esta vacio o no cumple con la cantidad minima de digitos. Cant. min: 9. ";
    }

    if ($msg != "") {

      $respuesta = array(
        'msg'   => $msg,
        'tipo'  => $msg_tipo,
        'color'  => $msg_color
      );

      return $respuesta;
    }

    // VALIDAR CHECK DE EMAIL Y WHATSAPP
    if ($rs_datos->p_preregistro_email == 0) {
      $msg .= "El EVENTO no tiene habilitado la opción envio de EMAIL <br>";
    }

    if ($rs_datos->p_preregistro_msg == 0) {
      $msg .= "El EVENTO no tiene habilitado la opción envio de WHATSAPP <br>";
      $msg_val = 1;
    }

    $modalidad = $rs_user->modalidad_id;
    $from_name  = $rs_datos->Email->nombre;
    $from_email = $rs_datos->Email->email;
    
    # DATOS PARTICIPANTE
    $celular = $rs_user->codigo_cel . $rs_user->celular;
    $nom = $rs_user->nombres . ' ' . $rs_user->ap_paterno . ' ' . $rs_user->ap_materno;
    $email = $rs_user->email;

    if ($tipo == 'preregistro') {

      $msg_val = 0;
      if ($rs_datos->p_preregistro_email == 0) {
        $msg = "EMAIL no esta habilitado";
        $msg_val = 1;
      }
      if ($rs_datos->p_preregistro_msg == 0) {
        $msg = "WHATSAPP no esta habilitado";
        $msg_val = 1;
      }
      
      if ($rs_datos->p_preregistro_email == 1 or $rs_datos->p_preregistro_msg == 1) {
        
        $asunto = '[CONFIRMACIÓN PG]  ' . $rs_datos->nombre_evento;
        $id_plantilla = $evento; //ID EVENTO

        $tipo = 'p_preregistro';
        $flujo_ejecucion = 'PREREGISTRO';

        $rs_estudiante = $rs_user;
        $mod_desde = "E_EDIT";
        #bajaEvento($modalidad, $rs_estudiante, $rs_datos,$tipo,session('eventos_id'));
        creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,$evento,$flujo_ejecucion,$mod_desde);
        
        $confirm_email = $rs_datos->p_preregistro_email;// SI UTILIZARA email
        $confirm_msg   = $rs_datos->p_preregistro_msg; // SI UTILIZARA whas 
        
        if($confirm_email==1){
          $msg .= "PREREGISTRO EMAIL: $email.<br> Se envío correctamente<br>";
          $msg_tipo = "success";
          $msg_color = "#058a49";
        }

        if ($confirm_msg == 1) {
          $msg .= "PREREGISTRO WHATSAPP: $celular.<br> Se envío correctamente<br>";
          $msg_tipo = "success";
          $msg_color = "#058a49";
        }
      }
      // end preregistro
    } elseif ($tipo == 'invitacion') {

      $msg_val = 0;
      if ($rs_datos->p_conf_inscripcion_email == 0) {
        $msg = "EMAIL no esta habilitado.<br>";
        $msg_val = 1;
      }
      if ($rs_datos->p_conf_inscripcion_msg == 0) {
        $msg = "WHATSAPP no esta habilitado.<br>";
        $msg_val = 1;
      }

      if ($rs_datos->p_conf_inscripcion_email == 1 or $rs_datos->p_conf_inscripcion_msg == 1) {

        $id_plantilla = $evento; //ID EVENTO

        $rs_estudiante = [
            'email'     => $email,
            'dni_doc'   => $dni,
            'nombres'   => $nom,
            'ap_paterno'=> '',
            'ap_materno'=> '',
            'celular'   => $rs_user->celular,
            'codigo_cel'=> $rs_user->codigo_cel,
        ];
        $rs_estudiante = (object) $rs_estudiante;
        $tipo = 'p_conf_inscripcion';
        $flujo_ejecucion = 'INVITACION';
        $mod_desde = "F_INVITACION";
        
        creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,$evento,$flujo_ejecucion,$mod_desde);

        $confirm_email = $rs_datos->p_conf_inscripcion_email;// SI UTILIZARA email
        $confirm_msg   = $rs_datos->p_conf_inscripcion_msg; // SI UTILIZARA whas 
      
        if ($confirm_email == 1) {

          $msg .= "INVITACIÓN EMAIL: $email - Se envío correctamente<br>";
          $msg_tipo = "success";
          $msg_color = "#058a49";
        }

          if ($confirm_msg == 1) {

            $msg .= "INVITACIÓN WHATSAPP: $celular. Se envío correctamente<br>";
            $msg_tipo = "success";
            $msg_color = "#058a49";
          }
        
      }
    } elseif ($tipo == 'confirmacion' or $tipo == 'recordatorio') {

      $rs_act = Estudiante::join('actividades_estudiantes as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
        ->select('estudiantes.email', 'estudiantes.celular', 'estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno')
        ->where('estudiantes.dni_doc', $dni)
        ->where('de.estudiantes_id', $dni)
        ->where('de.eventos_id', $evento)
        ->count();

      if ($rs_act == 0) {

        # NO TERMINARON PROCESO
        $restudiantes = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
          ->where('de.eventos_id', $evento)
          ->where('estudiantes.dni_doc', $dni)
          ->where('de.estudiantes_id', $dni)
          ->where('de.daccedio','NO')#aun no registra sus actividades
          ->where('de.dtrack','SI')#aceptaron su inscripcion
          ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','de.daccedio','estudiantes.created_at','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.modalidad_id')->get();

        $n = count($restudiantes);
        if($n>0){

            foreach ($restudiantes as $rs_estudiante) {
    
                $modalidad = $rs_estudiante->modalidad_id;
                
                $rs_estudiante = [
                    'email'     => $rs_estudiante->email,
                    'dni_doc'   => $rs_estudiante->dni_doc,
                    'nombres'   => $rs_estudiante->nombres ." ".$rs_estudiante->ap_paterno,
                    'ap_paterno'=> '',
                    'ap_materno'=> '',
                    'celular'   => $rs_estudiante->celular,
                    'codigo_cel'=> $rs_estudiante->codigo_cel,
                ];
                $rs_estudiante = (object) $rs_estudiante;
    
                $xtipo = 'p_recordatorio';
                $flujo_ejecucion = 'RECORDATORIO';
                $mod_desde = "RECORD_NOTERMINARON";
                #$evento = $id;//ID EVENTO
                
                creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$xtipo,$evento,$flujo_ejecucion,$mod_desde);
                
            }

            $msg .= "SE ENVIO AVISO SMS PARA TERMINAR DE REGISTRAR SUS ACTIVIDADES.<br> ";
            $msg_tipo  = "success";
            $msg_color = "#058a49";
            
        # NO TERMINARON PROCESO

        }else{
          $msg .= "El participante no ha elegido sus actividades.<br> ";

        }

        $respuesta = array(
          'msg'   => $msg,
          'tipo'  => $msg_tipo,
          'color'  => $msg_color
        );

        return $respuesta;
      }

      $msg_val = 0;
      if ($rs_datos->p_conf_registro_email == 0) {
        $msg_val = 1;
        $msg .= "EMAIL no esta habilitado.<br>";
      }


      if ($rs_datos->p_conf_registro_email == 1 or $rs_datos->p_conf_registro_msg == 1) {
        $rs_estudiante = [
          'email'     => $email,
          'dni_doc'   => $dni,
          'nombres'   => $nom,
          'ap_paterno'=> '',
          'ap_materno'=> '',
          'celular'   => $rs_user->celular,
          'codigo_cel'=> $rs_user->codigo_cel,
        ];
        $rs_estudiante = (object) $rs_estudiante;

        if ($tipo == 'confirmacion') {

          $id_plantilla = $evento; //ID EVENTO
          $xtipo = 'p_conf_registro';
          $flujo_ejecucion = 'CONFIRMACION';
          $mod_desde = "F_CONFIRM";
          
          creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$xtipo,$evento,$flujo_ejecucion,$mod_desde);

          $confirm_email = $rs_datos->p_conf_registro_email;// email
          $confirm_msg   = $rs_datos->p_conf_registro_msg; // whas 
           
        } else {

          $xtipo = 'p_recordatorio';
          $flujo_ejecucion = 'RECORDATORIO';
          $mod_desde = "F_RECOR";
          
          creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$xtipo,$evento,$flujo_ejecucion,$mod_desde);

          $confirm_email = $rs_datos->p_recordatorio_email;// email
          $confirm_msg   = $rs_datos->p_recordatorio_msg; // whas 

        }

        if ($confirm_email == 1) {

          if ($tipo == 'confirmacion') {
            $msg .= "<br>CONFIRMACIÓN EMAIL: $email - Se envío correctamente<br>";

            $msg_val = 0;
            if ($confirm_msg==0) {
              $msg_val = 1;
              $msg .= "<br>WHATSAPP no esta habilitado. ";
            }

          } else {
            $msg .= "<br>RECORDATORIO EMAIL: $email - Se envío correctamente<br>";
            $msg_val = 0;
            if ($confirm_msg==0) {
              $msg_val = 1;
              $msg .= "<br>WHATSAPP no esta habilitado. ";
            }

          }
          $msg_tipo = "success";
          $msg_color = "#058a49";

        }

        ## WHATSAPP
        if ($msg_val == 0) {
          if ($confirm_msg == 1) {

            if ($tipo == 'confirmacion') {
              $msg .= "<br>CONFIRMACIÓN WHATSAPP: $celular. Se envío correctamente<br>";
            } else {
              $msg .= "<br>RECORDATORIO WHATSAPP: $celular. Se envío correctamente<br>";
            }
            $msg_tipo = "success";
            $msg_color = "#058a49";
          }
        }
      }
    } else {
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