<?php

namespace App\Http\Controllers;

use App\CorreosENC;
use App\Repositories\EstudianteRepository;
use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Estudiante, App\Evento;

use App\TipoDoc;
use App\Tipo_evento;
use App\Departamento;
use App\Provincia;
use App\Distrito;
use App\ConsultaDNI;
use App\EstudianteTemp;
use App\estudiantes_act_detalle;
use App\AccionesRolesPermisos;
use App\Imports\EstudianteImport;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Alert;
use Auth;

class CorreosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function genera(Request $request, EstudianteRepository $repository)
    {
        $data = array(
            //"s" => $request->get('s'),
            "tipo"=>"6",
            "eventos_id" => session('correos_id'),//$request->get('evento_id'),
            "all"=>1,
            "reg"=>'NO',
            'sorted'=>'ASC'
        );
        $estudiantes = $repository->search($data);
        $estudiantes_count = count($estudiantes);
        $count = 0;
        DB::beginTransaction();
        if($estudiantes_count>0){
            foreach ($estudiantes as $estudiante){
                $id = $estudiante->id;
                $det_id = $estudiante->det_id;
                $nombres = $estudiante->nombres;
                $dni_doc = $estudiante->dni_doc;
                $ap_paterno = $estudiante->ap_paterno;
                $ap_materno = $estudiante->ap_materno;
                $daccedio = $estudiante->daccedio;
                $id = $estudiante->id;
                $email = strtolower(trim(substr($nombres,0,1)));
                $email.= strtolower(trim($ap_paterno));
                $email.= strtolower(trim(substr($ap_materno,0,1)));
                $email = str_replace("-","",Str::slug($email));
                $index = $this->getIndexEmails($email);
                if($index>0)$email.=(++$index);
                $email.= "@enc.edu.pe";
                $password = str_random(8);
                try {
                    /*$new = CorreosENC::create([
                        'estudiantes_id'=>$dni_doc,
                        'emailenc'=>$email,
                        'password'=>$password,
                        'area_id'=>$area_id,
                    ]);*/
                    /*if($new->id){
                        $count++;
                        DB::table('estudiantes_act_detalle')->where('id',$det_id)->update(["daccedio"=>"SI"]);
                    }*/
                    $new = CorreosENC::where('estudiantes_id',$dni_doc)->update([
                        'emailenc'=>$email,
                        'password'=>$password,
                    ]);
                    if($new){
                        $count++;
                        DB::table('estudiantes_act_detalle')->where('id',$det_id)->update(["daccedio"=>"SI"]);
                    }

                }catch(\Exception $e){

                }
            }
        }
        DB::commit();
        return array(
            'msg'   => $count>0?"Se generaron {$count} correo(s)":'No se generó ningún correo',
            'title'=> $count>0?'Aviso!':'Aviso!',
            'type'  => $count>0?'success':'warning'
        );
    }

    public function getIndexEmails($name)
    {
        $q = CorreosENC::select('emailenc')->where('emailenc','like',$name.'%');
        $emails = [];
        $data = $q->get();
        $max = 0;
        $n = strlen($name);
        if(count($data)>0){
            $max = 1;
            foreach($data as $d){
                $email = substr($d["emailenc"],$n);
                $xx = strtok($email,'@');
                $index = intval($xx);
                if($index>$max)$max = $index;
            }
        }
        return $max;
    }

    public function index(Request $request, EstudianteRepository $repository)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["correos"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        // TB: eventos_tipo
        // ID: eventos_tipo_id: USUARIOS - ID: 6

        Cache::flush();
        // local
        //$id_evento   = 76;
        $tipo_evento = 6;

        // SERVER
        $id_evento   = 2;

        $evento_id = $id_evento;

        session([
            'eventos_id'=> $id_evento,
            'evento'    => ""//$evento_nom
        ]);

        $ev = Tipo_evento::findOrFail($tipo_evento);

        $ev = array('nombre'=>$ev->nombre, 'id'=>$ev->id, 'tipo_evento'=>$ev->id);
        session([
            'correos_id'=> $id_evento,
            'evento'    => $ev
            ]);

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
            $permParam["modulo_alias"] = "correos";//eventos
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
            Cache::put('permisos.all', $permisos, 1);
        }
        ////FIN DE PERMISOS

        $tipos  = DB::table('estudiantes_tipo')->get();
        $grupos = DB::table('est_grupos')->get();

        $s = $request->get('s')??'';
        $reg = $request->get('reg')??'';

        $data = array(
            "s" => $s,
            //"st" => $request->get('st'),
            "reg" => $reg,
            //"g" => $request->get('g'),
            "pag" => $pag,
            "page" => request('page', 1),
            "sorted" => request('sorted', 'DESC'),
            "eventos_id" => $id_evento,
            "tipo" => $tipo_evento
        );

        $estudiantes_datos = $repository->search($data);

        return view('correos.index', compact('estudiantes_datos','tipos', 'permisos', 'grupos','evento_id','reg'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["correos"]["permisos"]["nuevo"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        if($request->correos_id != ""){
            session(['correos_id'=> $request->correos_id]);
        }
        if(session('correos_id') == false){
            return redirect('/');
        }

        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $tipo_doc = TipoDoc::all();
        $areasenc = DB::table('tb_areas_enc')->get();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        return view('correos.create', compact('departamentos_datos','tipo_doc','countrys','areasenc'));

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
        //return $request->all();

        $this->validate($request,[
            //'inputdni'=>'required',
            'inputdni'=>'required|unique:estudiantes,dni_doc',
            'email_recu'   => 'required',
            'tipo_doc'=>'required',
            'area'    =>'required',
        ]);

        $error = "";
        $dni_doc = mb_strtoupper($request->input('inputdni'));
        $existe  = $request->input('existe');

        $tipo_estudiante = 4; // = ESTUDIANTE

        // si existe DNI
        if($existe == 2){
            alert()->warning('Alerta','El participante ya esta registrado.');
            return redirect()->back();
        }

        // tb: estudiantes
        $tipdoc = $request->input('tipo_doc');
        $nom    = mb_strtoupper($request->input('nombres'));
        $appat  = mb_strtoupper($request->input('ap_paterno'));
        $apmat  = mb_strtoupper($request->input('ap_materno'));
        $pais   = mb_strtoupper($request->input('pais'));
        $dep    = $request->input('region');
        $ema    = $request->input('email_recu');
        $area   = $request->input('area');

        $acc    = "NO";
        $est    = mb_strtoupper($request->input('estado'));
        
        /*$org    = mb_strtoupper($request->input('organizacion'));
        $prof   = mb_strtoupper($request->input('profesion'));
        $ent    = mb_strtoupper($request->input('entidad'));
        $gprof  = $request->input('gradoprof');*/

        $ip     = request()->ip();
        $nav    = get_browser_name($_SERVER['HTTP_USER_AGENT']);
        $created= Carbon::now();

        if($existe == 0){

            $est = new Estudiante;
            $est->tipo_documento_documento_id = $tipdoc;
            $est->dni_doc = $dni_doc;
            $est->nombres = $nom;
            $est->ap_paterno = $appat;
            $est->ap_materno = $apmat;
            $est->pais       = $pais;
            $est->region     = $dep;
            $est->email      = $ema;
            $est->ip         = $ip;
            $est->navegador  = $nav;
            $est->created_at = $created;
            $est->save();

            //$est->accedio = $acc;
            //$est->estado  = $est;
    
        }else{
            //Modificar estudiante

            Estudiante::where('dni_doc',$dni_doc)->update([
                 'dni_doc'      => $dni_doc,
                 'ap_paterno'   => $appat,
                 'ap_materno'   => $appat,
                 'nombres'      => $nom,
                 'email'        => $ema,
                 'estado'       => 1,
                 'pais'         => $pais,
                 'region'       => $dep,
                 'tipo_documento_documento_id'=>$tipdoc,
                 'ip'           => $ip,
                 'navegador'    => $nav
            ]);
        }

            /* ADD TIPO */
            estudiantes_act_detalle::where('estudiantes_id',$dni_doc)
                                        ->where('eventos_id', session('correos_id'))
                                        ->where('estudiantes_tipo_id', $tipo_estudiante)
                                        ->delete();

            $det = new estudiantes_act_detalle;
            $det->eventos_id          = session('correos_id');
            $det->estudiantes_id      = $dni_doc;
            $det->actividades_id      = 0;
            $det->estudiantes_tipo_id = $tipo_estudiante;
            $det->confirmado          = 0;
            $det->daccedio            = $acc;
            $det->estado              = 1;//$est;
            $det->dtrack              = '';
            $det->created_at          = $created;
            $det->save();

            $c = new CorreosENC;
            $c->estudiantes_id = $dni_doc;
            $c->area_id = $area;
            $c->created_at = $created;
            $c->save();

        Cache::flush();

        if($error){
            return redirect()->back()->with('alert', $error);
        }

        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        return redirect()->route('correos.index');
    }


    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["correos"]["permisos"]["mostrar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        $correos_id = session('correos_id');

        $areasenc = DB::table('tb_areas_enc')->get();
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
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

        return view('correos.show',compact('estudiantes_datos','tipo_doc', 'countrys','departamentos_datos','areasenc','correos_id'));
    }


    public function edit($id, Request $request)
    {

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["correos"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        if(session('correos_id') == false){
            return redirect('/');
        }
        Cache::flush();

        $eventos_id = session('correos_id');

        $tipo_doc = TipoDoc::all();
        $areasenc = DB::table('tb_areas_enc')->get();

        $estudiantes_datos = DB::table('estudiantes as e')
                                ->join('estudiantes_act_detalle as de','de.estudiantes_id','=','e.dni_doc')
                                ->join('tb_correosenc as em','em.estudiantes_id','=','e.dni_doc')
                                ->select('e.id','e.tipo_documento_documento_id','e.dni_doc','e.ap_paterno', 'e.ap_materno', 'e.nombres','e.pais','e.region','e.ubigeo_ubigeo_id', 'de.dgrupo as grupo', 'de.estudiantes_tipo_id as tipo_id','e.email', 'e.celular', 'e.codigo_cel','de.daccedio','em.emailenc','em.password','em.area_id','em.id as idcorreo','de.dtrack', 'de.estado')
                                ->where('e.id',$id)
                                ->where('de.eventos_id',session('correos_id'))
                                ->first();
        

        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $grupos = DB::table('est_grupos')->get();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        return view('correos.edit',compact('estudiantes_datos','tipo_doc','countrys','departamentos_datos','areasenc','eventos_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,$idcorreo)
    {

        $this->validate($request,[
            'inputdni'   =>'required|unique:estudiantes,dni_doc,'.$id,
            'emailenc'   =>'required|email|unique:tb_correosenc,emailenc,'.$idcorreo,
            'email_recu' =>'required',
            'tipo_doc'   =>'required',
            //'area'       =>'required',
            'password'   =>'required|min:6'
        ]);
        
        $xdni = $request->input('inputdni');

        // ACTUALIZAR DATOS
        // ACT ESTADO Y ACCEDIO
        // MODIFICAR CORREO Y CAMBIAR CONTRASEÑA

        
        $tipdoc = $request->input('tipo_doc');
        $nom    = mb_strtoupper($request->input('nombres'));
        $appat  = mb_strtoupper($request->input('ap_paterno'));
        $apmat  = mb_strtoupper($request->input('ap_materno'));
        $pais   = mb_strtoupper($request->input('pais'));
        $dep    = $request->input('region');
        $ema    = $request->input('email_recu');
        $emailenc= $request->input('emailenc');
        $password= $request->input('password');
        $area   =  $request->input('area');

        //$acc    = "NO";
        $est    = mb_strtoupper($request->input('estado'));

        $ip     = request()->ip();
        $nav    = get_browser_name($_SERVER['HTTP_USER_AGENT']);
        $created= Carbon::now();
        $eventos_tipo_id = 4;

        //Actualizamos
        Estudiante::where('dni_doc',$xdni)->update([
                 'dni_doc'      => $xdni,
                 'ap_paterno'   => $appat,
                 'ap_materno'   => $appat,
                 'nombres'      => $nom,
                 'email'        => $ema,
                 'estado'       => $est,
                 'pais'         => $pais,
                 'region'       => $dep,
                 'tipo_documento_documento_id'=>$tipdoc,
                 'ip'           => $ip,
                 'navegador'    => $nav,
                 'updated_at'   => $created
            ]);

    

        /* FALTA CAMBIAR SI ACTUALIZAN EL TIPO DEL PARTICIPANTE*/

        /*$existe_det = DB::table('estudiantes_act_detalle')
                        ->where('estudiantes_id',$dni_server)
                        ->where('eventos_id',session('correos_id'))
                        ->count();
        
        if($existe_det > 0){*/

            $rs_update = estudiantes_act_detalle::where('estudiantes_id',$xdni)
                        ->where('eventos_id',session('correos_id'))
                        ->update([
                            'estudiantes_tipo_id'=> $eventos_tipo_id,
                            'estado'             => $est,
                            'created_at'         => $created,
                        ]);

            CorreosENC::where('estudiantes_id',$xdni)->update([
                'emailenc'  => $emailenc,
                'password'  => $password,
                'area_id'   => $area
            ]);


        /*}else{
            
            $det = new estudiantes_act_detalle;
            $det->eventos_id          = session('correos_id');
            $det->estudiantes_id      = $dni_doc;
            $det->actividades_id      = 0;
            $det->estudiantes_tipo_id = $tipo_estudiante;
            $det->confirmado          = 0;
            $det->daccedio            = $acc;
            $det->estado              = 1;//$est;
            $det->dtrack              = '';
            $det->created_at          = $created;
            $det->save();
        }*/



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
        return redirect()->route('correos.index');
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["correos"]["permisos"]["eliminar"]   ) ){
            Auth::logout();
            return redirect('/login');
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
                        //->where('eventos_id', session('correos_id'))
                        ->count();

            Evento::where('id', session('correos_id'))
                    ->decrement('inscritos_invi', 1);

            if($xreg == 1){
                //return 1;
                Estudiante::where('id',$value)->delete();
                estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                    ->where('eventos_id',session('correos_id'))
                    ->delete();
                DB::table('users')->where('name',$est->dni_doc)->delete();
                CorreosENC::where('estudiantes_id',$est->dni_doc)->delete();
                
            }else{

                estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                    ->where('eventos_id',session('correos_id'))
                    ->delete();
                CorreosENC::where('estudiantes_id',$est->dni_doc)->delete();

            }

            Cache::flush();

        }
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('correos.index');
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

            /* $reader = \Excel::selectSheetsByIndex(0)->load($request->file('file')->getRealPath())->formatDates( true, 'd/m/Y' );

            $results = $reader->noHeading()->get()->toArray();   //this will convert file to array
            $file->move( base_path('storage\excel'),"estudiantes.xlsx"); */


            $results = \Excel::toArray(new EstudianteImport, $request->file('file'));

            $results = $results[0];
            $file->move(base_path('storage\excel'), "correosenc.xlsx");

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

        /*$reader = \Excel::selectSheetsByIndex(0)->load($file_path ."/". $file_exc)->formatDates( true, 'd/m/Y' );
        $data_exc = $reader->noHeading()->get()->toArray();*/
        $results = \Excel::toArray(new EstudianteImport, $file_path . "/" . $file_exc);
        $data_exc = $results[0];

        $flagC = $request["chkPrimeraFila"];
        $chkE_invitacion= $request["chkE_invitacion"];
        if($flagC!=""){
            $contF = 0;
        }else{
            $contF = 1;
        }

        //recorre el archivo excel abierto
        DB::table('estudiantes_temp')->truncate();

        foreach ($data_exc as $key => $lst) {
            $est_delete = 0;

            if($contF>0){
                // recorre los combos seleccionados
                $estTemp = new EstudianteTemp();
                $tipo_xid = 6;
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
                $eventos_idT = session('correos_id');
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
                            // VERIFICO SI ES VALIDO
                            $sanitized_email = filter_var($mailT, FILTER_SANITIZE_EMAIL);
                            if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                            $estTemp->email = $sanitized_email;
                            $mailT = $sanitized_email;
                            } else {
                            $estTemp->email = "";
                            $mailT = "";
                            }
                        }
                    }


                    if($request["cmbOrganizar".$x]==12){
                        $estTemp->sexo = $lst[$x - 1];
                        $sexT = $lst[$x - 1];
                    }

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

                #echo "paso 1";
                if($flagPASAdni == 1){
                    $verEst = Estudiante::where("dni_doc",$dniT)->first();
                    #echo "paso 2";
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

                        //si columnas del excel existe => update
                
                        if($nomT)$verEst->nombres = mb_strtoupper($nomT);
                        if($dniT)$verEst->dni_doc = $dniT;
                        if($appT)$verEst->ap_paterno = mb_strtoupper($appT);
                        if($apmT)$verEst->ap_materno  = mb_strtoupper($apmT);
                        if($grupoT)$verEst->grupo  = mb_strtoupper($grupoT);
                        if($fecnT)$verEst->fecha_nac = $fecnT;
                        if($cargT)$verEst->cargo = mb_strtoupper($cargT);
                        if($profT)$verEst->profesion = mb_strtoupper($profT);
                        if($dirT)$verEst->direccion = mb_strtoupper($dirT);
                        if($telT)$verEst->telefono = $telT;
                        if($celT)$verEst->celular = $celT;$verEst->codigo_cel = '51';
                        if($mailT)$verEst->email = trim($mailT);
                        if($mailT_2)$verEst->email_labor = trim($mailT_2);
                        if($sexT)$verEst->sexo = $sexT;
                        if(!$dniT)$verEst->accedio = 'NO';
                        if($orgT)$verEst->organizacion = mb_strtoupper($orgT);
                        if($paisT)$verEst->pais = mb_strtoupper($paisT);
                        if($regionT)$verEst->region = mb_strtoupper($regionT);
                        $verEst->tipo_id = 2; // TIPO invitados

                        $verEst->save(); //end save
    
                        $estTemp->repetido=0;
                        $estTemp->mensaje="<span style='color:#18e237'>Lead UPDATE</span>";

                        $error = '';

                        // SAVE INVITACION SI = chkE_invitacion == 1
                        // grabar detalle estudiantes //considerar borrar la prog subida por error
                        $error = '';

                        // VERIFICAR SI EL PARTICIPANTE YA ESTA REGISTRADO.
                            $est_det_cant = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                                            ->where('eventos_id',$eventos_idT)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->count();

                            // estado: accedio = SI -> Ya no envia ni guarda
                            /*$check_stado = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                                            ->where('eventos_id',$eventos_idT)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->where('daccedio', 'NO')
                                            ->count(); // FALTA VALIDAR*/

                            if($est_det_cant >= 1){

                                $check_stado = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                                            ->where('eventos_id',$eventos_idT)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->where('daccedio', 'SI')
                                            ->update([
                                                'dgrupo'    => mb_strtoupper($grupoT),
                                                'estado'    => 1,
                                                'daccedio'  => 'NO'
                                            ]);

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
                                $detalle_estud->daccedio = 'NO';
                                $detalle_estud->dgrupo = mb_strtoupper($grupoT);
                                $detalle_estud->dtrack = '';
                                $detalle_estud->cambio_tipo = $est_delete;
                                $detalle_estud->created_at = Carbon::now();
                                $detalle_estud->save();

                                $c = new CorreosENC;
                                $c->estudiantes_id = $dniT;
                                $c->area_id = 0;
                                $c->created_at = Carbon::now();
                                $c->save();

                            }

                    }
                }else{
                    
                    $estTemp->repetido=1;//y tener 8 dígitos
                    $estTemp->mensaje="<span style='color:red'>DNI debe ser numérico </span>";
                }
                if($flagPASA==1){
                    echo "paso 4";
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
                    $estudiante->save();

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
                    $detalle->daccedio = 'NO';
                    $detalle->dgrupo = mb_strtoupper($grupoT);
                    $detalle->dtrack = '';
                    $detalle->cambio_tipo = $est_delete;
                    $detalle->created_at = Carbon::now();
                    $detalle->save();

                    $c = new CorreosENC;
                    $c->estudiantes_id = $dniT;
                    $c->area_id = 0;
                    $c->created_at = Carbon::now();
                    $c->save();

                    $error = '';

                }
                // fin NEW ESTUD.

                $estTemp->save();
            }
            $contF++;

            Cache::flush();
        }

        $a = DB::table('estudiantes_temp')
                ->select('mensaje')
                ->where('mensaje',"<span style='color:#18e237'>Lead ACTUALIZADO.</span>")
                ->count();
        $b = DB::table('estudiantes_temp')
                ->select('mensaje')
                ->orWhere('mensaje',"<span style='color:#18e237'>Registro importado</span>")
                ->orWhere('mensaje',"<span style='color:#18e237'>Lead importado</span>")
                ->count();

        $c = DB::table('estudiantes_temp')
                ->select('mensaje')
                ->where('mensaje',"<span style='color:red'>DNI debe ser numérico </span>")
                ->count();

        $d = DB::table('estudiantes_temp')
                ->select('mensaje')
                ->where('mensaje',"<span style='color:red'>Registro grabado, DNI existente</span>")
                ->count();

        $tot_table = $key;

        DB::table('historia_import')->insert([
                        'fecha'       => Carbon::now(),
                        'exitoso'     => $b,
                        'actualizado' => $a,
                        'no_valido'   => $c,
                        'repetidos'   => $d,
                        'total'       => $tot_table,
                        'user_id'     => \Auth::User()->id
                    ]);

    }

    public function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }

    public function EstudianteImportResults() {
        $lista = EstudianteTemp::select("id",
                               "dni_doc","nombres","ap_paterno","ap_materno","grupo","fecha_nac","organizacion","cargo","profesion","direccion","telefono","telefono_labor","celular","email","email_labor","sexo","idEntidad","mensaje","idAlumno","codigo_prog","pais", "region"
                            )
                    ->orderBy("id","ASC")
                    ->get();

        $dat = DB::table('historia_import')->orderBy('id','desc')
                ->limit(1)
                ->first();

        if(count($lista)==0){
            die("No hay registros");
        }
        $vEnt = 0;
        foreach ($lista as $lstT) {
            if($lstT->idEntidad!=0){$vEnt=1;}
        }

        return view("correos.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'dat'=>$dat]);
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
            if($rs_datos->p_conf_registro_2 == ""){
                $msg_val_2 = 1;
                $msg .= "No existe plantilla para el mensaje por whatsapp. ";
            }

            if($msg_val == 0 or $msg_val_2 == 0){

                if($tipo == 'confirmacion'){
                    $flujo_ejecucion = 'CONFIRMACION';
                    $asunto          = '[CONFIRMACIÓN] '.$eevento;

                    $msg_text = $rs_datos->p_conf_registro;
                    $msg_cel  = $rs_datos->p_conf_registro_2;

                }else{
                    $flujo_ejecucion = 'RECORDATORIO';
                    $asunto          = '[RECORDATORIO] '.$eevento;

                    $msg_text = $rs_datos->p_recordatorio;
                    $msg_cel  = $rs_datos->p_recordatorio_2;
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
