<?php
namespace App\Http\Controllers;

use App\Exports\GeneralExport;
use App\Imports\GeneralImport;
use App\Models\User;
use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Estudiante;
use App\TipoDoc;
use App\Departamento;
use App\ConsultaDNI;
use App\EstudianteTemp;
use App\estudiantes_act_detalle;
use App\AccionesRolesPermisos;
use Mail;
use Excel;
use Alert;
use Auth;
use App\Traits\ManageExcel;

class bdController extends Controller
{
    use ManageExcel;
    public $time1;
    public $date1;

    public function __construct()
    {
        $this->middleware('auth');
        $this->time1 = microtime(true);
        $this->date1 = date("d-m-Y H:i:s");
    }

    public function index(Request $request)
    {
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["bd"]["permisos"]["inicio"]   ) ){
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
            $permParam["modulo_alias"] = "estudiantes";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

            Cache::put('permisos.all', $permisos, 1);

        }
        ////FIN DE PERMISOS
        $search = $request->get('s');
        $status = $request->get('status');
        $status = (isset($status))?$status:"1";
        $export = $request->get('export');

        // Estados: 0=>inactivos 1=>activos 2=>eventos temporal Mooc
        //$q =  Estudiante::where('estado',0)->orderBy('id', request('sorted', 'DESC'));
        $q =  Estudiante::orderBy('id', request('sorted', 'DESC'));
        
        if(isset($status))$q->where('estado',$status);

        if($search){
            $q->where(function ($query) use ($search) {
                $query->where("dni_doc", "LIKE", '%'.$search.'%')
                    ->orWhere("cargo", "LIKE", '%'.$search.'%')
                    ->orWhere("grupo", "LIKE", '%'.$search.'%')
                    ->orWhere("organizacion", "LIKE", '%'.$search.'%')
                    ->orWhere("accedio", "LIKE", '%'.$search.'%')
                    ->orWhere("email", "LIKE", '%'.$search.'%')
                    ->orWhere("email_labor", "LIKE", '%'.$search.'%')
                    ->orWhere("profesion", "LIKE", '%'.$search.'%')
                    ->orWhere("direccion", "LIKE", '%'.$search.'%')
                    ->orWhere("pais", "LIKE", '%'.$search.'%')
                    ->orWhere("region", "LIKE", '%'.$search.'%')
                    ->orWhere("celular", "LIKE", '%'.$search.'%')
                    ->orWhere(DB::raw('CONCAT(nombres," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$search.'%')
                    ->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$search.'%');
            });
        }
        $tipos = DB::table('estudiantes_tipo')->get();
        if($export==1){
            $xtipos = [];
            if($tipos->count()>0)
                foreach($tipos as $v)$xtipos[$v->id]=$v->nombre;
            return $this->exportaXLS($q, $xtipos);
        }

        $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
        $query = vsprintf($query, $q->getBindings());

        //dd($query);
        $estudiantes_datos =  $q->paginate($pag);
        $departamentos_datos = Cache::rememberForever('depa', function() {
            return Departamento::select('ubigeo_id','nombre')
                ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
                ->get();
        });
        return view('bd.bd', compact('estudiantes_datos','departamentos_datos','tipos', 'permisos'));
    }



    public function show($id)
    {
//        $this->actualizarSesion();
//        //VERIFICA SI TIENE EL PERMISO
//        if(!isset( session("permisosTotales")["bd"]["permisos"]["mostrar"]   ) ){
//            Auth::logout();
//            return redirect('/login');
//        }
        $eventos_id = session('eventos_id');

        $tipos = DB::table('estudiantes_tipo')->get();
        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $tipo_doc = TipoDoc::all();
        $grupos = DB::table('est_grupos')->get();

        $estudiantes_datos = Estudiante::findOrFail($id);

        $distrito = $estudiantes_datos->ubigeo_ubigeo_id;

        $dis = substr($distrito,0,4);

        $distritos_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2', ['id' => $dis.'%','id2' => $dis]);

        $prov = substr($distrito,0,2);
        $provincias_datos = DB::select('select * from ubigeos where ubigeo_id like :id and ubigeo_id <> :id2 and CHARACTER_LENGTH(ubigeo_id)= :id3', ['id' => $prov.'%','id2' => $prov,'id3' => 4]);

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        return view('estudiantes.show',compact('estudiantes_datos','tipo_doc', 'countrys', 'tipos','departamentos_datos','grupos','eventos_id'));

    }


    public function edit($id)
    {
//        $this->actualizarSesion();
//        //VERIFICA SI TIENE EL PERMISO
//        if(!isset( session("permisosTotales")["bd"]["permisos"]["editar"]   ) ){
//            Auth::logout();
//            return redirect('/login');
//        }

        $tipos = DB::table('estudiantes_tipo')->get();
        $tipo_doc = TipoDoc::all();
        $estudiantes_datos = DB::table('estudiantes')->where('id', $id)->first();

        $countrys = DB::table('country')->select('name','phonecode','nicename')->get();
        $grupos = DB::table('est_grupos')->get();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        //$estudiantes_datos = Estudiante::find($id);
        return view('bd.edit',compact('estudiantes_datos','tipo_doc', 'tipos','departamentos_datos','grupos','countrys'));
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

        $tipo_estudiante = 5;



        // si existe DNI
        if($existe == 2){return "El participante ya esta registrado.";}
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


        Cache::flush();

        if($error){
            return redirect()->back()->with('alert', $error);
        }

        alert()->success('Registro grabado.','Mensaje Satisfactorio');

        return redirect()->route('bd.index');
    }


    public function update(Request $request, $id)
    {
        $this->validate($request,[

            'inputdni'=>'required',
            //'inputdni'=>'required|unique:estudiantes,dni_doc,'.$id,
            'cboTipDoc' => 'required'
        ]);




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
             'tipo_id'=>$request->input('tipo_id'),
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

        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
        return redirect()->back();
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["bd"]["permisos"]["eliminar"]   ) ){
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

        // Borrado
                Estudiante::where('id',$value)->delete();
                estudiantes_act_detalle::where('estudiantes_id', $est->dni_doc)
                    ->delete();
                DB::table('actividades_estudiantes')->where('estudiantes_id',$est->dni_doc)->delete();
                DB::table('users')->where('name',$est->dni_doc)->delete();
                DB::table('historia_email')->where('estudiante_id',$est->dni_doc)->delete();
                DB::table('asistencia_eventos')->where('estudiantes_id',$est->dni_doc)->delete();
                DB::table('newsletters')->where('estudiante_id',$est->dni_doc)->delete();


            Cache::flush();

        }
        alert()->error('Eliminado','Registros borrados de todo el sistema.');
        return redirect()->route('bd.index');
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
            $results = Excel::toArray(new GeneralImport, $file);
            \Config::set('excel.import.encoding.input', 'iso-8859-1');
            \Config::set('excel.import.encoding.output', 'iso-8859-1');

            $filePath = $file->storeAs('storage\excel', "estudiantes.".$extension, 'real_public');

        }
        return count($results)>0?$results[0]:[];
        //return $results;

    }

    public function EstudianteImportSave(Request $request){

        $file_path = "storage/excel";
        $file_exc  = "estudiantes.xlsx";

        \Config::set('excel.import.encoding.input', 'iso-8859-1');
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        $reader = Excel::toArray(new GeneralImport, public_path($file_path ."/". $file_exc));//ADDED
        $data_exc = $reader[0];

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
                $estTemp->tipo_id = 2;
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
                $eventos_idT = session('eventos_id');//"";
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

                $si_evento = 1;

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
                        if((int)$verEst->codigo_prog!=""){$colEst1++;}
                        if((int)$verEst->pais!=""){$colEst1++;}
                        if((int)$verEst->region!=""){$colEst1++;}
                        if((int)$verEst->organizacion!=""){$colEst1++;}

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
                    $estudiante->grupo = mb_strtoupper($grupoT);
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
                    $estudiante->accedio = 'NO';
                    $estudiante->tipo_id = 2;
                    // BORRAR ENTIDADES
                    //if((int)$entT != 0){$estudiante->entidades_entidad_id = $entT;}
                    $estudiante->save();

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
                         'celular'=> $celT,
                         'email'=> $mailT,
                         'email_labor'=> $mailT_2,
                         'sexo'=> $sexT,
                         'created_at'=> Carbon::now(),
                         'updated_at'=> Carbon::now(),
                         'estado'=> 1,
                         'tipo_documento_documento_id'=> 1,
                         'ip'=>request()->ip(),
                         'ubigeo_ubigeo_id'     =>  '',
                         'accion'  => 'INSERT',
                         'usuario' => Auth::user()->email
                    ]);

                    // end audi_estudiantes

                    $estTemp->idAlumno = $estudiante->id;

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

        return view("bd.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'dat'=>$dat]);
    }

    // popup Historial
    public function Historial(Request $request, $id)
    {
        //if($request->ajax()){

            $estudiantes_datos = DB::table('estudiantes as e')
                                ->join('estudiantes_act_detalle as de', 'e.dni_doc', '=', 'de.estudiantes_id')
                                ->join('eventos as ev','de.eventos_id','=','ev.id')
                                ->where('e.dni_doc', '=', $id)
                                ->select('e.dni_doc','e.nombres','e.ap_paterno','e.ap_materno','ev.*')
                                ->orderBy('ev.id','desc')
                                ->get();

            $data = array();
            //$i=-1;

            if(count($estudiantes_datos)>0){
                foreach ($estudiantes_datos as $j => $d) {
                    $dni = $d->dni_doc;
                    $nombres = $d->nombres . " ".$d->ap_paterno." ".$d->ap_materno;
                    $evento  = $d->nombre_evento;
                    $tipo    = $d->tipo;
                    $inicio  = \Carbon\Carbon::parse($d->fechai_evento)->format('d/m/Y');
                    $fin     = \Carbon\Carbon::parse($d->fechaf_evento)->format('d/m/Y');

                    $fe_fin = \Carbon\Carbon::parse($d->fechaf_evento);
                    $hoy = Carbon::now();
                    $vencido = "<span class='badge badge-success'>Activo</span>";

                    if($hoy->greaterThanOrEqualTo($fe_fin)){
                        $vencido="<span class='badge badge-secondary'>Vencido</span>";
                    }

                    if($tipo == 1)
                        $tipo = "PRESENCIAL";
                    else
                        $tipo = "VIRTUAL";

                    //$data[$j]
                    $data[$j] = array('id'=>$j+1, 'dni' => $dni, 'nombres'=>$nombres, 'evento'=>$evento, 'tipo'=>$tipo, 'inicio'=>$inicio, 'fin'=>$fin,'estado'=>$vencido );

                }
            }
            /*dd($data);
            exit();

            if($estudiantes_datos){
                //return $estudiantes_datos;*/
                return response()->json($data);

            //}

        //}

    }

    // exportar toda la BD.

    function exportaXLS($rs,$tipos){
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        $path ='reports/';
        $type='xlsx';
        $nom_file="Lista";
        $file = "{$nom_file}.{$type}";
        $filename = "{$path}{$file}";

        $rs->select('dni_doc','ap_paterno','ap_materno','nombres','cargo','organizacion','profesion','grupo','pais','region',
            'tipo_id','codigo_cel','celular','email','created_at','estado');

        $headers = [
            [
                'DNI',
                'Ap.Paterno',
                'Ap.Materno',
                'Nombres',
                'Cargo',
                'Entidad',
                'Profesión',
                'Grupo',
                'País',
                'Departamento',
                //'Tipo',
                'Celular',
                'Email',
                'FechaReg',
                'Estado'
            ]
        ];
        $colWidths = [
            'A'     =>  12,
            'B'     =>  20,
            'C'     =>  20,
            'D'     =>  20,
            'E'     =>  25,
            'F'     =>  25,
            'G'     =>  15,
            'H'     =>  20,
            'I'     =>  15,
            'J'     =>  15,
            'K'     =>  30,
            'L'     =>  20,
            'M'     =>  15,
            'N'     =>  15,
            'O'     =>  15,
        ];
        $colFormats = [
            'A'     =>  "@"
        ];
        $styles = [
            1 =>  [
                'font' => ['bold' => true, 'color' => ['argb' => 'ffffff']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '00458B',
                    ]
                ],
            ]
        ];
        $data = [];
        $rows = $rs->get();
        if($rows->count()>0){
            foreach($rows as $datos){
                $data[] = array(
                    $datos->dni_doc,
                    $datos->ap_paterno,
                    $datos->ap_materno,
                    $datos->nombres,
                    $datos->cargo,
                    $datos->organizacion,
                    $datos->profesion,
                    $datos->grupo,
                    $datos->pais,
                    $datos->region,
                    //array_key_exists($datos->tipo_id, $tipos) ? $tipos[$datos->tipo_id] : '-',
                    $datos->codigo_cel . " " . $datos->celular,
                    $datos->email,
                    $datos->created_at->format('d/m/Y H:m:s'),
                    $datos->estado == 0 ? 'Inactivo' : 'Activo'
                );
            }
        }
        
        Excel::store( new GeneralExport(compact("rows", "headers", "styles", "data", "colWidths", "colFormats")), $filename, 'real_public');
        $success = file_exists(public_path($filename))?true:false;
        sleep(0.1);
        return ["url"=>$filename,"count"=>$rs->count(),"name"=>$file,"success"=>$success];
    }

    function importaCorregidos_m4_cursos(){
        //dd('cursos');
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        $time1 = microtime(true);
        $directory = base_path('storage/excel');
        //dd($directory);
        $name = "cursos_dj.xls";
        $fileName = $directory ."/". $name;

        \Config::set('excel.import.encoding.input', 'iso-8859-1');
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        //$reader = \Excel::selectSheetsByIndex(0)->load($fileName)->formatDates( true, 'd/m/Y' );
        //$data = $reader->noHeading()->get()->toArray();
        $reader = Excel::toArray(new GeneralImport, public_path($fileName));//ADDED
        $data = $reader[0];

        if(count($data)>0){
            foreach($data as $i=>$v){
                $f = $i+1;
                if($f==1)continue;
                $index = $v[0]??"";
                $cod_curso = $v[1]??"";
                $nom_curso = $v[2]??"";
                //$mod_curso = $v[3]??"";
                $fech_ini  = $v[3]??"";
                $fech_fin  = $v[4]??"";


                $nom_curso = trim ($nom_curso);
                $cod_curso = trim ($cod_curso);

                DB::table('m4_cursos')->insert([
                    'evento_id' =>179,
                    'nom_curso' =>$nom_curso,
                    'cod_curso' =>$cod_curso,
                    'mod_curso' =>'',
                    'grupo_id'  =>2,
                    'fech_ini'  =>$fech_ini,
                    'fech_fin'  =>$fech_fin
                ]);
            }
        }
        $time2 = microtime(true);
        $tiempo_transcurrido = round($time2 - $time1,3);
        return compact("i","tiempo_transcurrido");
    }

    function importaCorregidos_si(){

        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        $time1 = microtime(true);
        $directory = base_path('storage\excel');
        
        $name = "estudiantes-email-corregidos.xlsx";
        $fileName = $directory ."/". $name;

        \Config::set('excel.import.encoding.input', 'iso-8859-1');
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        //$reader = \Excel::selectSheetsByIndex(0)->load($fileName)->formatDates( true, 'd/m/Y' );
        //$data = $reader->noHeading()->get()->toArray();
        $reader = Excel::toArray(new GeneralImport, public_path($fileName));//ADDED
        $data = $reader[0];

        $emails = [];
        if(count($data)>0){
            foreach($data as $i=>$v){
                $f = $i+1;
                if($f==1)continue;
                $index = $v[0]??"";
                $dni = $v[1]??"";
                $email = $v[2]??"";
                $corregido = $v[3]??"";
                $dni = trim ($dni);
                $email = trim ($email);
                if($corregido=='ok')$emails["{$dni}"]=$email;
            }
        }
        $count = count($emails);
        $modificados = [];
        $nomodificados = [];
        if($count>0){
            foreach ($emails as $dni=>$email){
                $n = DB::table('estudiantes')->where('dni_doc',$dni)->update(['email'=>$email]);
                if($n)
                    $modificados[]=compact("dni","email");
                else
                    $nomodificados[]=compact("dni","email");
            }
        }
        $time2 = microtime(true);
        $tiempo_transcurrido = round($time2 - $time1,3);
        return compact("count","tiempo_transcurrido","modificados","nomodificados");
    }

}
