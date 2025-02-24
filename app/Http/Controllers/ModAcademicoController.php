<?php

namespace App\Http\Controllers;

use DB;
use Cache;
//use File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Academico, App\Departamento, App\AcademicoTemp;
use App\AccionesRolesPermisos;
use App\estudiantes_act_detalle;
use App\Evento;
use Maatwebsite\Excel\Facades\Excel;

use Alert;
use Auth;

class ModAcademicoController extends Controller
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
            $permParam["modulo_alias"] = "academico";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

            Cache::put('permisos.all', $permisos, 5);

        }

        Cache::flush();
        if($request->get('s')){

            $search = $request->get('s');

            $aca_datos = Academico::where("nombre", "LIKE", '%'.$search.'%')
            ->orWhere("codigo", "LIKE", '%'.$search.'%')
            ->orWhere("descripcion", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }elseif($request->get('st')){
            
            $search = $request->get('st');

            $aca_datos = Academico::where("unidad_area", $search)
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }elseif($request->get('t')){
            
            $search = $request->get('t');

            $aca_datos = Academico::where("tipo_control", $search)
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }else{

            $key = 'mod_eventos.page.'.request('page', 1);
            $aca_datos = Cache::rememberForever($key, function() use ($pag){
                return Academico::orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            });
        }

        $evento_vencido = "";
        

        return view('academico.index', compact('aca_datos', 'permisos', 'evento_vencido')); 

    }


    public function opciones()
    {
        
    }

    public function create()
    {
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        $aulas = DB::table('da_aulas')->orderBy('id','desc')->get();
        $docentes = DB::table('da_docentes')
                    ->orderBy('ap_paterno','asc')
                    ->orderBy('ap_materno','asc')
                    ->orderBy('nombre_doc','asc')
                    ->get();
        $datos=(object)array("aulas_id"=>"");

        return view('academico.create', compact('departamentos_datos', 'aulas', 'docentes','datos'));
    }

    public function store(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $flag_error = 0;
        $f_inicio = $request->input('f_inicio');
        $f_final = $request->input('f_final');

        if($this->validar_fecha_espanol($f_inicio)){ 
            $valores = explode('/', $f_inicio);
            $f_inicio = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 1;
        }

        if($this->validar_fecha_espanol($f_final)){ 
            $valores = explode('/', $f_final);
            $f_final = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 1;
        }

        // error fechas
        if($flag_error == 0) {
            alert()->warning('Error en los campos de las fechas','Error');
            return redirect()->back();
        }

        $reg = DB::table('da_control')->insert([
            'codigo'    =>mb_strtoupper($request->input('codigo')),
            'nombre'    =>mb_strtoupper($request->input('nombre')),
            'descripcion'=>mb_strtoupper($request->input('descripcion')),
            'linea'     =>($request->input('linea')),
            'f_inicio'  =>$f_inicio,
            'f_final'   =>$f_final,
            'h_inicio'  =>$request->input('h_inicio'),
            'h_final'   =>$request->input('h_final'),
            'unidad_area'=>mb_strtoupper($request->input('unidad_area')),
            'modalidad' =>mb_strtoupper($request->input('modalidad')),
            'sesiones'  =>$request->input('sesiones'),
            'vacantes'  =>$request->input('vacantes'),
            'h_cronologicas'=>$request->input('h_cronologicas'),
            'tipo_control'=>$request->input('tipo_control'),
            'L'     =>($request->input('L')) ? 1 : 0,
            'M'     =>($request->input('M')) ? 1 : 0,
            'MI'    =>($request->input('MI')) ? 1 : 0,
            'J'     =>($request->input('J')) ? 1 : 0,
            'V'     =>($request->input('V')) ? 1 : 0,
            'S'     =>($request->input('S')) ? 1 : 0,
            'D'     =>($request->input('D')) ? 1 : 0,
            'lugar_id'  =>$request->input('lugar_id'),
            'lugar'     =>$request->input('lugar'),
            'aulas_id'  =>$request->input('aulas_id'),
            'docentes_id'  =>$request->input('dni_doc'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        $id_academico = DB::getPdo()->lastInsertId();

        $evento = new Evento();
            $evento->nombre_evento = mb_strtoupper($request->input('nombre'));
            $evento->fechai_evento = $f_inicio;
            $evento->fechaf_evento = $f_final;
            $evento->hora          = $request->input('h_inicio');
            $evento->hora_fin      = $request->input('h_final');
            $evento->departamento    = $request->input('lugar');
            $evento->vacantes        = $request->input('vacantes');
            $evento->eventos_tipo_id = 3;
            $evento->academico_id    = $id_academico;
            $evento->created_at      = Carbon::now();
            $evento->updated_at      = Carbon::now();
            $evento->save();

        Cache::flush();
        alert()->success('Registro guardado con éxito', 'Mensaje');

        return redirect()->route('academico.index');

    }

    public function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }

    public function format_fech($fecha){
        $valores = explode('/', $fecha);
        $v_fecha = $valores[2].'-'.$valores[1].'-'.$valores[0];
        return $v_fecha;
    }

    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $datos = Academico::findOrFail($id);
        //$datos = Academico::where('id',$id)->first();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        $aulas = DB::table('da_aulas')->orderBy('id','desc')->get();
        foreach($aulas as $i => $aula){
            $aulas[$i]->ocupado = $aula->id==$datos->aulas_id;
        }
        $docentes = DB::table('da_docentes')
                    ->orderBy('ap_paterno','asc')
                    ->orderBy('ap_materno','asc')
                    ->orderBy('nombre_doc','asc')
                    ->get();

        return view('academico.show', compact('datos', 'departamentos_datos', 'aulas', 'docentes'));

    }

    public function edit($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $datos = Academico::where('id',$id)->first();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        $aulas = DB::table('da_aulas')->orderBy('id','desc')->get();
        foreach($aulas as $i => $aula){
            $aulas[$i]->ocupado = $aula->id==$datos->aulas_id;
        }
        $docentes = DB::table('da_docentes')
                    ->orderBy('ap_paterno','asc')
                    ->orderBy('ap_materno','asc')
                    ->orderBy('nombre_doc','asc')
                    ->get();

        return view('academico.edit', compact('datos', 'departamentos_datos', 'aulas', 'docentes'));
    }

    public function update(Request $request, $id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        $flag_error = 0;
        $f_inicio = $request->input('f_inicio');
        $f_final = $request->input('f_final');

        if($this->validar_fecha_espanol($f_inicio)){ 
            $valores = explode('/', $f_inicio);
            $f_inicio = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 1;
        }

        if($this->validar_fecha_espanol($f_final)){ 
            $valores = explode('/', $f_final);
            $f_final = $valores[2].'-'.$valores[1].'-'.$valores[0];
            $flag_error = 1;
        }

        // error fechas
        if($flag_error == 0) {
            alert()->warning('Error en los campos de las fechas','Error');
            return redirect()->back();
        }

        $reg = DB::table('da_control')->where('id', $id)->update([
            'codigo'    =>mb_strtoupper($request->input('codigo')),
            'nombre'    =>mb_strtoupper($request->input('nombre')),
            'descripcion'=>mb_strtoupper($request->input('descripcion')),
            'linea'     =>($request->input('linea')),
            'f_inicio'  =>$f_inicio,
            'f_final'   =>$f_final,
            'h_inicio'  =>$request->input('h_inicio'),
            'h_final'   =>$request->input('h_final'),
            'unidad_area'=>mb_strtoupper($request->input('unidad_area')),
            'modalidad' =>mb_strtoupper($request->input('modalidad')),
            'sesiones'  =>$request->input('sesiones'),
            'vacantes'  =>$request->input('vacantes'),
            'h_cronologicas'=>$request->input('h_cronologicas'),
            'tipo_control'=>$request->input('tipo_control'),
            'L'     =>($request->input('L')) ? 1 : 0,
            'M'     =>($request->input('M')) ? 1 : 0,
            'MI'    =>($request->input('MI')) ? 1 : 0,
            'J'     =>($request->input('J')) ? 1 : 0,
            'V'     =>($request->input('V')) ? 1 : 0,
            'S'     =>($request->input('S')) ? 1 : 0,
            'D'     =>($request->input('D')) ? 1 : 0,
            'lugar_id'  =>$request->input('lugar_id'),
            'lugar'     =>$request->input('lugar'),
            'aulas_id'  =>$request->input('aulas_id'),
            'docentes_id'  =>$request->input('docentes'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

            $evento = Evento::where('academico_id', $id)->first() ;
            $evento->nombre_evento = mb_strtoupper($request->input('nombre'));
            $evento->fechai_evento = $f_inicio;
            $evento->fechaf_evento = $f_final;
            $evento->hora          = $request->input('h_inicio');
            $evento->hora_fin      = $request->input('h_final');
            $evento->departamento    = $request->input('lugar');
            $evento->vacantes        = $request->input('vacantes');
            $evento->eventos_tipo_id = 3;
            $evento->updated_at      = Carbon::now();
            $evento->save();

        Cache::flush();
        alert()->success('Registro actualizado con éxito', 'Mensaje');

        return back();
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["academico"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }


        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            $dat = Evento::where('academico_id', $value)->first();
            if($dat){
                $evento = estudiantes_act_detalle::where('eventos_id', $dat->id)->count();
                if($evento>=1){
                    alert()->error('La actividad académica tiene registros.','Error');
                    return back();
                }

            }

            Academico::where('id', $value)->delete();
            Evento::where('academico_id', $value)->delete();

        }
        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return back();
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

    public function loadAulas(Request $request)
    {
        //$aulas = $request->input('aulas');
        $f_inicio = $request->input('f_inicio');
        $f_final = $request->input('f_final');
        $idcontrol = $request->input('id');

        $hinicio = $request->input('hinicio');
        $hfin = $request->input('hfin');

        $L = $request->input('L')??0;
        $M = $request->input('M')??0;
        $MI = $request->input('MI')??0;
        $J = $request->input('J')??0;
        $V = $request->input('V')??0;
        $S = $request->input('S')??0;
        $D = $request->input('D')??0;

        $dias = array(
            $L, $M, $MI, $J, $V, $S, $D
        );

        $f_inicio = Carbon::createFromFormat('d/m/Y', $f_inicio);
        $f_final = Carbon::createFromFormat('d/m/Y', $f_final);
      


        $inicio = Carbon::parse($f_inicio)->format('Y-m-d');
        $fin = Carbon::parse($f_final)->format('Y-m-d');
        $ocupados=$this->aulasOcupadas($inicio,$fin,$dias,$hinicio,$hfin,$idcontrol);

        $aulas = DB::table('da_aulas')->orderBy('id','desc')->get();

        //$this->printr($ocupados);exit;

        foreach($aulas as $i => $aula){
            $aulas[$i]->ocupado = $this->buscaAula($aula, $ocupados);
        }
        $datos=(object)array("aulas_id"=>"");

        return view('academico.aulas', compact('ocupados', 'aulas','datos'));
    }

    function buscaAula($aula, $aulas){
        $id = $aula->id;
        
        if(count($aulas)>0){
            foreach ($aulas as $val) {
                if(isset($val->id))if($id == $val->aulas_id){//
                    return $val;
                }
            }
        }
        return false;
    }


    function aulasOcupadas($inicio,$fin,$dias,$hinicio,$hfin,$idcontrol=0){
        
        $fechas=$this->generaFechas($inicio,$fin,$dias);
        /*$sql="SELECT aulas_id,id,f_inicio,f_final,h_inicio,h_final,lunes,martes,miercoles,jueves,viernes,sabado,domingo FROM da_control WHERE NOT(f_final<'$inicio' OR f_inicio > '$fin') AND NOT(h_final<'$hinicio' OR h_inicio > '$hfin')";*/

        //$rows=getRows($sql);
        $rows= DB::select("SELECT aulas_id,id,f_inicio,f_final,h_inicio,h_final,L,M,MI,J,V,S,D FROM da_control WHERE NOT(f_final< :inicio OR f_inicio > :fin) AND NOT(h_final< :hinicio OR h_inicio > :hfin)", ['inicio' => $inicio, 'fin' => $fin, 'hinicio' => $hinicio, 'hfin' => $hfin]);

        /*
        $verAsistencia = DB::select("select * from estudiantes e inner join participante_foro f on e.dni_doc=f.participante_id where e.dni_doc = :dni and f.foro_1='1'", ['dni' => $dni]);
        */
        //$rows = json_decode(json_encode($rows), false);
   
        $data=array();
        if(count($rows)>0){
            foreach($rows as $row){
                $f_inicio=$row->f_inicio;
                $f_final=$row->f_final;
                $h_inicio=$row->h_inicio;
                $h_final=$row->h_final;
                $lunes=$row->L;
                $martes=$row->M;
                $miercoles=$row->MI;
                $jueves=$row->J;
                $viernes=$row->V;
                $sabado=$row->S;
                $domingo=$row->D;
                $aula_id=$row->aulas_id;
                $id=$row->id;
                if($idcontrol>0&&$id==$idcontrol)continue;
                $xdias=array($lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo);
                //genera fechas segun la fecha inicio, fin y dias indicados
                $xfechas=$this->generaFechas($f_inicio,$f_final,$xdias);
                //$this->printr($xfechas);exit;
                //verificar si las fechas generadas estan en la fecha generada del registro 
                $cruces=$this->arrayValueElement($fechas,$xfechas);
                $row->cruces=$cruces;
                if($cruces)//si tiene cruces, esta ocupado
                    array_push($data,$row);
            }
        }
        //dd($data);
        return $data;
    }

    function arrayValueElement($array,$array2){
        $fechas=array();
        if(count($array)>0&&count($array2)>0){
            foreach($array as $v)
                if (in_array($v, $array2))array_push($fechas,$v);
        }
        return $fechas;
    }

    function printr($v){
        echo "<pre>";
        print_r($v);
        echo "</pre>";
    }

    function generaFechas($inicio,$fin,$dias){
        $fecha = new Carbon($inicio);       
        $diff =  $fecha->diffInDays($fin);
        $fechas=array();
        for($i=0; $i <= $diff; $i++){        
            $w = $fecha->dayOfWeek;
            //cambiar formato fecha domingo 0 a sabado 6 - lunes 1  domingo 7
            $w=$w==0?7:$w;        
            $w2=$dias[$w-1];
            //dia esta activo y dia semana es dia j
            if($w2==1)
                array_push($fechas,$fecha->format('Y-m-d'));
            $fecha->addDay();
        }
        return $fechas;
    }

    // IMPORT
    public function CursoImport(Request $request){
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

    public function CursoImportSave(Request $request){
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
        $txtFormatoF = $request->get('txtFormatoF');
        if($txtFormatoF == "dd/mm/yyyy"){
            $fmtFecha = 1;
        }elseif($txtFormatoF == "mm/dd/yyyy"){
            $fmtFecha = 2;
        }else{
            $fmtFecha = 3;
        }

        if($flagC!=""){
            $contF = 0;
        }else{
            $contF = 1;
        }

        /*------------------------------
        05-ago => pasar a: 05/08/2019
        ------------------------------*/

        //AcademicoTemp::all()->delete();
        AcademicoTemp::truncate();

        //recorre el archivo excel abierto
        foreach ($data_exc as $lst) {
            $est_delete = 0;
            
            if($contF>0){
                // recorre los combos seleccionados
                $estTemp = new AcademicoTemp();
                //$tipo_xid = 4;//tipo estudiante
                //$estTemp->tipo_id = $tipo_xid;

                $codigoT = "";
                $nombreT = "";
                $lineaT  = "";
                $f_iniT = "";
                $f_finT = "";
                $h_iniT = "";
                $h_finT = "";
                $uaT = "";
                $modT = "";
                $sesiT = "";
                $vacanT = "";
                $hcT = "";
                $tcT = "";
                $lugarT = "";
                $sede = "";$f_inicio = "";$f_final = "";

                $entT = 0;
                $eventos_idT = session('academico_id');

                $estudiantes_det = new estudiantes_act_detalle();
                $cod_programacionD = 0;
                $cod_estudiantesD = 0;
                
                for($x = 1; $x <= $request["totCol"] ; $x++){
                    
                    if($request["cmbOrganizar".$x]==1){
                        $estTemp->codigo = $lst[$x - 1];
                        $codigoT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==2){
                        $estTemp->nombre = mb_strtoupper($lst[$x - 1]);
                        $nombreT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==3){
                        $estTemp->linea = mb_strtoupper($lst[$x - 1]);
                        $lineaT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==4){
                        $estTemp->tipo_control = mb_strtoupper($lst[$x - 1]);
                        $tcT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==5){
                        $estTemp->f_inicio = mb_strtoupper($lst[$x - 1]);
                        $f_iniT = $lst[$x - 1];

                        if($fmtFecha != 1){
                            return "error_fecha";
                        }
                    }

                    if($request["cmbOrganizar".$x]==6){
                        $estTemp->f_final = mb_strtoupper($lst[$x - 1]);
                        $f_finT = $lst[$x - 1];

                        if($fmtFecha != 1){
                            return "error_fecha";
                        }
                        /*$valores = explode('/', $f_finT);
                        $f_final = $valores[2].'-'.$valores[1].'-'.$valores[0];*/
                    }

                    if($request["cmbOrganizar".$x]==7){
                        $estTemp->h_inicio = mb_strtoupper($lst[$x - 1]);
                        $h_iniT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==8){
                        $estTemp->h_final = mb_strtoupper($lst[$x - 1]);
                        $h_finT = $lst[$x - 1];
                    }
                    
                    if($request["cmbOrganizar".$x]==9){
                        $estTemp->unidad_area = mb_strtoupper($lst[$x - 1]);
                        $uaT = $lst[$x - 1];
                    }
                    
                    if($request["cmbOrganizar".$x]==10){
                        $estTemp->modalidad = $lst[$x - 1];
                        $modT = $lst[$x - 1]; 
                    }
                    
                    if($request["cmbOrganizar".$x]==11){
                        $estTemp->sesiones = $lst[$x - 1];
                        $sesiT = $lst[$x - 1];
                    }
                    
                    if($request["cmbOrganizar".$x]==12){
                            $estTemp->vacantes = trim($lst[$x - 1]);
                            $vacanT = trim($lst[$x - 1]);
                    }
                    
                    if($request["cmbOrganizar".$x]==13){
                            $estTemp->h_cronologicas = $lst[$x - 1];
                            $hcT = $lst[$x - 1];
                    }

                    if($request["cmbOrganizar".$x]==14){
                        $estTemp->lugar = $lst[$x - 1];
                        $lugarT = $lst[$x - 1];

                        if($lugarT == "ENC"){
                            $sede = "LIMA";
                        }else{
                            $sede = ($lugarT!="")?$lugarT:"";
                        }
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

                }


                $flagPASA = 0;
                $flagPASA_1 = 1;

                if($flagPASA_1 == 1){
                    $verEst = Academico::where("codigo", $codigoT)->first();

                    if(!($verEst)){

                        //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                        if($f_iniT!=""){

                            if($this->validar_fecha_espanol($f_iniT)){

                                $f_inicio = $this->format_fech($f_iniT);
                                $estTemp->f_inicio = $f_inicio;
                                $f_iniT = $f_inicio;

                                $flagPASA = 1;
                                $estTemp->repetido=0;
                                $estTemp->mensaje="<span style='color:#18e237'>Curso grabado</span>";
                            }else{
                                $estTemp->f_inicio = "ERROR: ".$f_iniT;
                                $estTemp->repetido=1;
                                $estTemp->mensaje="<span style='color:red'>Fecha Inicio Formato Incorrecto - dd/mm/yyyy</span>";
                            }
                            
                        }else{
                            $flagPASA = 1;
                        }
                        if($f_finT!=""){

                            if($this->validar_fecha_espanol($f_finT)){

                                $f_final = $this->format_fech($f_finT);
                                $estTemp->f_final = $f_final;
                                $f_finT = $f_final;

                                $flagPASA = 1;
                                $estTemp->repetido=0;
                                $estTemp->mensaje="<span style='color:#18e237'>Curso grabado</span>";
                            }else{
                                $estTemp->f_final = "ERROR: ".$f_finT;
                                $estTemp->repetido=1;
                                $estTemp->mensaje="<span style='color:red'>Fecha Fin Formato Incorrecto - dd/mm/yyyy</span>";
                            }
                            
                        }else{
                            $flagPASA = 1;
                        }

                    }else{

                        $estTemp->repetido=1;
                        //$estTemp->mensaje="<span style='color:red'>DNI ya se encuentra registrado</span>";
                        $estTemp->mensaje="<span style='color:red'>Registro grabado, DNI existente</span>";

                        //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                        if($f_iniT!=""){

                            if($this->validar_fecha_espanol($f_iniT)){

                                $f_inicio = $this->format_fech($f_iniT);
                                $estTemp->f_inicio = $f_inicio;
                                $f_iniT = $f_inicio;

                                $estTemp->repetido=0;
                                $estTemp->mensaje="";
                            }else{
                                $estTemp->f_inicio = "ERROR: ".$f_iniT;
                                $estTemp->repetido=1;
                                $estTemp->mensaje="<span style='color:red'>Fecha Inicio Formato Incorrecto - dd/mm/yyyy</span>";
                            }
                            
                        }

                        if($f_finT!=""){

                            if($this->validar_fecha_espanol($f_finT)){

                                $f_final = $this->format_fech($f_finT);
                                $estTemp->f_final = $f_final;
                                $f_finT = $f_final;

                                $estTemp->repetido=0;
                                $estTemp->mensaje="";
                            }else{
                                $estTemp->f_final = "ERROR: ".$f_finT;
                                $estTemp->repetido=1;
                                $estTemp->mensaje="<span style='color:red'>Fecha Fin Formato Incorrecto - dd/mm/yyyy</span>";
                            }
                            
                        }

                        // CONDICIONAL DE ACTUALIZACION
                        $colEst1 = 0;
                        if(trim($verEst->codigo)!="" ){$colEst1++;}
                        if(trim($verEst->nombre)!="" ){$colEst1++;}
                        if(trim($verEst->linea)!="" ){$colEst1++;}
                        if(trim($verEst->tipo_control)!="" ){$colEst1++;}
                        if(trim($verEst->f_inicio)!="" ){$colEst1++;}
                        if(trim($verEst->f_final)!="" ){$colEst1++;}
                        if(trim($verEst->h_inicio)!="" ){$colEst1++;}
                        if(trim($verEst->h_final)!="" ){$colEst1++;}
                        if(trim($verEst->unidad_area)!="" ){$colEst1++;}
                        if(trim($verEst->modalidad)!="" ){$colEst1++;}
                        if(trim($verEst->sesiones)!="" ){$colEst1++;}
                        if(trim($verEst->vacantes)!="" ){$colEst1++;}
                        if(trim($verEst->h_cronologicas)!="" ){$colEst1++;}
                        if(trim($verEst->lugar)!="" ){$colEst1++;}
                        // borrar entidades
                        $colEst2 = 0;
                        if($codigoT  != ""){$colEst2++;}
                        if($nombreT  != ""){$colEst2++;}
                        if($lineaT   != ""){$colEst2++;}
                        if($tcT      != ""){$colEst2++;}
                        if($f_iniT   != ""){$colEst2++;}
                        if($f_finT   != ""){$colEst2++;}
                        if($h_iniT   != ""){$colEst2++;}
                        if($h_finT   != ""){$colEst2++;}
                        if($uaT      != ""){$colEst2++;}
                        if($modT     != ""){$colEst2++;}
                        if($sesiT != ""){$colEst2++;}
                        if($vacanT != ""){$colEst2++;}
                        if($hcT != ""){$colEst2++;}
                        if($lugarT != ""){$colEst2++;}
                        //$eventos_idT 
                        //if((int)$entT != 0){$colEst2++;}
                        
                        /*echo $codigoT."<br>";
                        echo $colEst1."<br>";
                        echo $colEst2."<br>";exit; */

                        //si las columnas del excel es igual o mayor a las columas de la BD actualizar la fila
                        if($colEst2 >= $colEst1){
                            $id_academico_ev = $verEst->id;
                            $verEst->codigo = mb_strtoupper($codigoT);
                            $verEst->nombre = mb_strtoupper($nombreT);
                            $verEst->linea = mb_strtoupper($lineaT);
                            $verEst->f_inicio  = $f_inicio;
                            $verEst->f_final   = $f_final;
                            $verEst->h_inicio  = mb_strtoupper($h_iniT);
                            $verEst->h_final   = mb_strtoupper($h_finT);
                            $verEst->unidad_area = mb_strtoupper($uaT);
                            $verEst->modalidad = mb_strtoupper($modT);
                            $verEst->sesiones = mb_strtoupper($sesiT);
                            $verEst->vacantes = mb_strtoupper($vacanT);
                            $verEst->h_cronologicas = mb_strtoupper($hcT);
                            $verEst->tipo_control = $tcT;
                            $verEst->lugar = mb_strtoupper($sede);          
                            $verEst->save();

                            $evento = Evento::where('academico_id', $id_academico_ev)->first() ;
                            $evento->nombre_evento = mb_strtoupper($nombreT);
                            $evento->fechai_evento = $f_inicio;
                            $evento->fechaf_evento = $f_final;
                            $evento->hora          = $h_iniT;
                            $evento->hora_fin      = $h_finT;
                            $evento->departamento    = $sede;
                            $evento->vacantes        = $vacanT;
                            $evento->created_at      = Carbon::now();
                            $evento->updated_at      = Carbon::now();
                            $evento->save();

                            $estTemp->repetido=0;
                            $estTemp->mensaje="<span style='color:#18e237'>Curso ACTUALIZADO.</span>";

                         //END colEst2
                        }else{
                            $estTemp->repetido=0;
                            $estTemp->mensaje="<span style='color:red'>Campos seleccionados son<br> menores a los del sistema</span>";
                        }


                        $error = '';

                    }
                }

                if($flagPASA==1){

                    // CREA NUEVO CURSO
                    $acad = new Academico();
                    $acad->codigo = mb_strtoupper($codigoT);
                    $acad->nombre = mb_strtoupper($nombreT);
                    $acad->linea = mb_strtoupper($lineaT);
                    $acad->f_inicio  = $f_inicio;
                    $acad->f_final   = $f_final;
                    $acad->h_inicio  = mb_strtoupper($h_iniT);
                    $acad->h_final   = mb_strtoupper($h_finT);
                    $acad->unidad_area = mb_strtoupper($uaT);
                    $acad->modalidad = mb_strtoupper($modT);
                    $acad->sesiones = mb_strtoupper($sesiT);
                    $acad->vacantes = mb_strtoupper($vacanT);
                    $acad->h_cronologicas = mb_strtoupper($hcT);
                    $acad->tipo_control = $tcT;
                    $acad->lugar = mb_strtoupper($sede);
                    $acad->created_at = Carbon::now();     
                    $acad->updated_at = Carbon::now();     
                    $acad->save();

                    $estTemp->idCurso = $acad->id;
                    $id_academico = $acad->id;

                    $evento = new Evento();
                    $evento->nombre_evento = mb_strtoupper($nombreT);
                    $evento->fechai_evento = $f_inicio;
                    $evento->fechaf_evento = $f_final;
                    $evento->hora          = $h_iniT;
                    $evento->hora_fin      = $h_finT;
                    $evento->departamento    = $sede;
                    $evento->vacantes        = $vacanT;
                    $evento->eventos_tipo_id = 3;
                    $evento->academico_id    = $id_academico;
                    $evento->created_at      = Carbon::now();
                    $evento->updated_at      = Carbon::now();
                    $evento->save();

                    $error = '';

                }
                // fin

                $estTemp->save();
            }
            $contF++;
            
            Cache::flush();
        }

    }
    
    public function CursoImportResults() {
        $nlista = AcademicoTemp::count();
        $lista  = AcademicoTemp::select(
                            'id',  'codigo',  'nombre',  'linea',  'f_inicio',  'f_final',  'h_inicio',  'h_final',  'unidad_area',  'modalidad',  'sesiones',  'vacantes',  'h_cronologicas',  'tipo_control',  'lugar', 'suspendido',  'repetido', "mensaje", "idCurso"
                            )
                    ->orderBy("id","ASC")
                    ->get();

        if(count($lista)==0){
            die("No hay registros");
        }
        
        
        return view("academico.importresults", ['lista' => $lista, 'nlista'=>$nlista]);
    }



}