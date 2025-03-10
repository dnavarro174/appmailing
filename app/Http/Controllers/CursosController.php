<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;
use DB;

use App\Imports\CursosImport;
use App\Imports\EstudianteImport;
use App\AccionesRolesPermisos;
use App\Models\Curso;
use App\CursoTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Validation\Rule;
//use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }
  
    public function destroy($id)
    {
        //
    }

    // Funcionalidad para agregar exepciones desde un formulario
    public function agregar_cursos(Request $request)
    {   #dd('search');
        $ok = true;

        $rules = [
        //'cod_curso' => 'required|min:1|unique:m4_cursos,cod_curso,evento_id',
        'nom_curso' => 'required|min:2',
        'modalidad' => 'required|min:2',
        'cod_curso' => 
            'required|min:2',
            Rule::unique('m4_cursos')->where(function ($query) use ($request) {
                return $query->where('cod_curso',$request->cod_curso)
                             ->where('evento_id',$request->evento_id);
            })
        ];
            $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $page = 8;

        //save or edit
        $id        = $request->get('id');
        $status    = $request->get('status');
        $nom_curso = mb_strtoupper($request->get('nom_curso'));
        $cod_curso = mb_strtoupper($request->get('cod_curso'));
        $modalidad = mb_strtoupper($request->get('modalidad'));
        $ini       = $request->get('fech_ini');
        $fin       = $request->get('fech_fin');
        $tpo       = (session('tipo')==8)?1:2;
        
        $save = ($request->get('save'))?$request->get('save'):0;
        // FALTA VALIDAR SI SE REPITE EL EVENTO Y EL CODCURSO

        $delete = $request->get('delete');
        $errors=new Collection;
        if($delete==1){
            #No eliminar cursos si existe DJ creada con ese curso.
            $registradosCursos = DB::table('m4_ddjj')->where('curso_id',$id)->count();
            if($registradosCursos>0) {
                //$errors = $ide;
                //alert()->warning('Advertencia',"El curso esta registrado en $id Declaraciones Juradas.");
                //$msg = "El curso esta registrado en $registradosCursos Declaraciones Juradas.";
                $msg=0;$cant=$registradosCursos;
            }else{
                $msg=1;$cant=0;
                DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->where('id',$id)->delete();
            }
            
            #$save=2;

        }

        if($save==1){
            $data = ['nom_curso' => $nom_curso,'status'=>$status>0?1:0, 'cod_curso'=>$cod_curso, 'modalidad'=>$modalidad, 'fech_ini'=>$ini,'fech_fin'=>$fin, 'evento_id'=>session('eventos_id'), 'tpo'=>$tpo];
            if($id>0)$rules['cod_curso'].=",{$id}";
            $validator = Validator::make($data, $rules);
            
            $errors = $validator->errors();
            if (!$validator->fails()) {
                if($id>0) DB::table('m4_cursos')->where('id',$id)->update($data);
                else DB::table('m4_cursos')->insert($data);
            }
        }
        
        $cursos = DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->orderBy('id','DESC')->paginate($page);
        $estados = [1=>"SI",0=>"NO"];
        $campos = ["id"=>"","email"=>"","status"=>1,'nom_curso'=>'', 'cod_curso'=>'','modalidad'=>'', 'fech_ini'=>'','fech_fin'=>''];
        $curso = (object)$campos;

        // Buscar
        if($request->get('s')){
            $s      = $request->get('s');
            $cursos = DB::table('m4_cursos')
                ->where('evento_id',session('eventos_id'))
                ->where(function ($query) use ($s) {
                    $query->orWhere("nom_curso", "LIKE", '%'.$s.'%')
                    ->orWhere('cod_curso','like', '%'.$s.'%')
                    ->orWhere('modalidad','like', '%'.$s.'%');
                })
                ->orderBy('id','DESC')->paginate($page);
        }
        
        if($delete == 1){
            $html = view("cursos.cursos_list", compact('cursos','estados','curso','errors','save'))->render();
            return ["ok"=>$ok, "html"=>$html, "msg"=>$msg, "cant"=>$cant];
        }
        
        return view("cursos.cursos_list", compact('cursos','estados','curso','errors','save'));
        
    }

    public function importar_cursos(){
        return view('import');
    }

    public function importar_cursos_store(Request $request) 
    {
        
        //$file = request()->file('file');
        //$resultado = Excel::import(new CursosImport,request()->file('file'));

        try {
            //$import->import('import-users.xlsx');
            Excel::import(new CursosImport,request()->file('file'));

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             
             foreach ($failures as $failure) {
                 $failure->row(); // row that went wrong
                 $failure->attribute(); // either heading key (if using heading row concern) or column index
                 $failure->errors(); // Actual error messages from Laravel validator
                 $failure->values(); // The values of the row that has failed.
             }
        }
        
        
        //return back()->with('success','Registro guardado');

        /* Excel::import(new UsersImport, 'users.xlsx');
        
        return redirect('/')->with('success', 'All good!'); */
        $errors=new Collection;
        $save = 0;

        $page = 6;
        $cursos = DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->orderBy('id','DESC')->paginate($page);
        $estados = [1=>"SI",0=>"NO"];
        $campos = ["id"=>"","email"=>"","status"=>1,'nom_curso'=>'', 'cod_curso'=>'','modalidad'=>'', 'fech_ini'=>'','fech_fin'=>''];
        $curso = (object)$campos;
        
        //dd($cursos,$estados,$curso,$errors);
        return view("cursos.cursos_list", compact('cursos','estados','curso','errors','save'))->with('success', 'File imported successfully!');
        
    }

    public function CursoImport(Request $request)
    {
        $msg = "Solo se aceptan archivos XLS, XLSX y CSV. ";
        $results = [];
        if ($request->hasFile('file')) {

            $filesd = glob(base_path('storage\excel\*')); //get all file names

            foreach ($filesd as $filed) {
                if (is_file($filed))
                unlink($filed); //delete file
            }

            //$file = $request->file('file')->getClientOriginalName();
            $file      = $request->file('file');

            $file_     = $file->getClientOriginalName();
            $fileog    = pathinfo($file_, PATHINFO_FILENAME);
            $extension = pathinfo($file_, PATHINFO_EXTENSION);
            
            $extension = trim($extension);
            //if(! $extension!="xls" || $extension!="xlsx" || $extension!="csv") ;
            if ($extension != "xlsx" && $extension != "csv" && $extension != "xls") {
                return \Response::json(['titulo' => "Solo se aceptan archivos XLS, XLSX y CSV.", 'error' => $msg], 404);
                exit;
            }

            \Config::set('excel.import.encoding.input', 'iso-8859-1');
            \Config::set('excel.import.encoding.output', 'iso-8859-1');
            #-- TESTEANDO
            //$reader = \Excel::selectSheetsByIndex(0)->load($request->file('file')->getRealPath())->formatDates(true, 'd/m/Y');
            //$results = $reader->noHeading()->get()->toArray();   //this will convert file to array
            $results = Excel::toArray(new CursosImport, $request->file('file'));#$request->file->getRealPath()
            //dd('ab',$request->file->getRealPath(), $results);
            $results = $results[0];
            //$file->move( base_path('storage/excel'),"estudiantes.".$extension );
            $file->move(base_path('storage/excel'), "cursos.xlsx");
            //print_r($results);
        }
        
        
        return $results;
    }

    public function CursoImportSave(Request $request)
    {
        
        $file_path = base_path('storage/excel');
        $directory = $file_path;
        $file_exc = scandir($directory)[2];
        //dd($directory,$file_exc);
        \Config::set('excel.import.encoding.input', 'iso-8859-1');
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        $results = \Excel::toArray(new CursosImport, $file_path . "/" . $file_exc);
        
        $data_exc = $results[0];

        $flagC = $request["chkPrimeraFila"];
        $chkE_invitacion = $request["chkE_invitacion"];
        $tpo   = $request->tpo;//1: oci - 2: cgr

        if ($flagC != "") {
        $contF = 0;
        } else {
        $contF = 1;
        }

        DB::table('m4_cursos_temp')->truncate();
        // begin
        //recorre el archivo excel abierto
        foreach ($data_exc as $lst) {

            if ($contF > 0) {
            // recorre los combos seleccionados
            $curTemp = new CursoTemp();
            $codT = "";
            $nomT = "";
            $modT = "";
            $feciniT = "";
            $fecfinT = "";
            $entT = 0;
            $eventos_idT = session('eventos_id');
            $tpo_capaT = "";
            $provee_capaT = "";
            $horasT = "";
            $cto_directoT = "";
            $cto_indirectoT = "";
            $valor_capaT = "";
            $materia_capaT = "";

                for ($x = 1; $x <= $request["totCol"]; $x++) {

                    if ($request["cmbOrganizar" . $x] == 1) {
                    $curTemp->cod_curso = $lst[$x - 1];
                    $codT = $lst[$x - 1];

                    $curTemp->cod_curso = trim($curTemp->cod_curso);
                    $codT = trim($codT);
                    }

                    if ($request["cmbOrganizar" . $x] == 2) {
                    $curTemp->nom_curso = mb_strtoupper($lst[$x - 1]);
                    $nomT = $lst[$x - 1];
                    }

                    if ($request["cmbOrganizar" . $x] == 3) {
                    $curTemp->modalidad = mb_strtoupper($lst[$x - 1]);
                    $modT = $lst[$x - 1];
                    }

                    if ($request["cmbOrganizar" . $x] == 4) {
                    $curTemp->fech_ini = mb_strtoupper($lst[$x - 1]);
                    $feciniT = $lst[$x - 1];                    
                    }

                    if ($request["cmbOrganizar" . $x] == 5) {
                    $curTemp->fech_fin = mb_strtoupper($lst[$x - 1]);
                    $fecfinT = $lst[$x - 1];
                    }
                    // tpo:2 - CGR
                    if ($request["cmbOrganizar" . $x] == 6) {
                        $curTemp->tpo_capa = mb_strtoupper($lst[$x - 1]);
                        $tpo_capaT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 7) {
                        $curTemp->provee_capa = mb_strtoupper($lst[$x - 1]);
                        $provee_capaT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 8) {
                        $curTemp->horas = mb_strtoupper($lst[$x - 1]);
                        $horasT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 9) {
                        $curTemp->cto_directo = mb_strtoupper($lst[$x - 1]);
                        $cto_directoT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 10) {
                        $curTemp->cto_indirecto = mb_strtoupper($lst[$x - 1]);
                        $cto_indirectoT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 11) {
                        $curTemp->valor_capa = mb_strtoupper($lst[$x - 1]);
                        $valor_capaT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 12) {
                        $curTemp->materia_capa = mb_strtoupper($lst[$x - 1]);
                        $materia_capaT = $lst[$x - 1];
                    }
                    
                }

                /* valida si existe evento*/
                $si_evento = DB::table('eventos')->where('id', $eventos_idT)->count();

                if($si_evento == 0){
                    return "error_no_evento";
                }

                $flagPASA = 0;
                $flagPASAcodcurso = 1;
                $flagPASAcel = 1;


                if(strlen($codT)<2){
                    $flagPASAcodcurso = 0;
                }

                if($flagPASAcodcurso == 1){
                    $verCurso = Curso::where("cod_curso",$codT)
                        ->where('evento_id',$eventos_idT)
                        ->first();

                    if($verCurso){
                        //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                        if($fecfinT!=""){
                            if($this->validar_fecha_espanol($fecfinT)){
                                $flagPASA = 1;
                            }else{
                                $curTemp->mensaje="<span style='color:red'>Formato Incorrecto, debe ser dd/mm/yyyy</span>";
                            }
                        } else {
                            $flagPASA = 1;
                        }

                        if($flagPASA==1){

                            // CONDICIONAL DE ACTUALIZACION
                            $colEst1 = 0;
                            if(trim($verCurso->cod_curso)!="" ){$colEst1++;}
                            if(trim($verCurso->nom_curso)!="" ){$colEst1++;}
                            if(trim($verCurso->modalidad)!="" ){$colEst1++;}
                            if(trim($verCurso->fech_ini)!="" ){$colEst1++;}
                            if(trim($verCurso->fech_fin)!="" ){$colEst1++;}
                            
                            // borrar entidades
                            //if((int)$verCurso->entidades_entidad_id!=0){$colEst1++;}
        
                            $colEst2 = 0;
                            //if($codT != ""){$colEst2++;}
                            if($nomT != ""){$colEst2++;}
                            if($modT != ""){$colEst2++;}
                            if($feciniT != ""){$colEst2++;}
                            if($fecfinT != ""){$colEst2++;}
        
                            //si columnas del excel existe => update
                            
                            if($codT)$verCurso->cod_curso = $codT;
                            if($nomT)$verCurso->nom_curso = trim(mb_strtoupper($nomT));
                            if($modT)$verCurso->modalidad = trim(mb_strtoupper($modT));
                            if($feciniT)$verCurso->fech_ini  = trim(mb_strtoupper($feciniT));
                            if($fecfinT)$verCurso->fech_fin = $fecfinT;
                            
                            $verCurso->tpo=$tpo;

                            if($tpo_capaT)$verCurso->tpo_capa = trim(mb_strtoupper($tpo_capaT));
                            if($provee_capaT)$verCurso->provee_capa = trim(mb_strtoupper($provee_capaT));
                            if($horasT)$verCurso->horas = trim(mb_strtoupper($horasT));
                            if($cto_directoT)$verCurso->cto_directo = trim(mb_strtoupper($cto_directoT));
                            if($cto_indirectoT)$verCurso->cto_indirecto = trim(mb_strtoupper($cto_indirectoT));
                            if($valor_capaT)$verCurso->valor_capa = trim(mb_strtoupper($valor_capaT));
                            if($materia_capaT)$verCurso->materia_capa = trim(mb_strtoupper($materia_capaT));

                            $verCurso->save(); //end save
                            
                            $curTemp->mensaje="<span style='color:#18e237'>Curso UPDATE</span>";
        
                            $error = '';
                        }
                    
                    }else{

                        //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                        if($fecfinT!=""){
                            if($this->validar_fecha_espanol($fecfinT)){
                                $flagPASA = 1;
                            }else{
                                $curTemp->mensaje="<span style='color:red'>Formato Incorrecto, debe ser dd/mm/yyyy</span>";
                            }
                        } else {
                            $flagPASA = 1;
                        }
                        $horasT = intval($horasT);
                        $time_perma = ($horasT / 8) * 2 + 30;
                        //mb_strtoupper($valor_capaT);

                        // CREA EL NUEVO CURSO
                        $curso = new Curso();
                        $curso->cod_curso = $codT;
                        $curso->evento_id = $eventos_idT;
                        $curso->tpo = $tpo;//tipo: oci
                        $curso->nom_curso = trim(mb_strtoupper($nomT));
                        $curso->modalidad = trim(mb_strtoupper($modT));
                        $curso->fech_ini  = trim(mb_strtoupper($feciniT));
                        $curso->fech_fin = $fecfinT;

                        $curso->tpo_capa = trim(mb_strtoupper($tpo_capaT));
                        $curso->provee_capa = trim(mb_strtoupper($provee_capaT));
                        $curso->horas = trim(mb_strtoupper($horasT));
                        $curso->cto_directo   = trim(mb_strtoupper($cto_directoT));
                        $curso->cto_indirecto = trim(mb_strtoupper($cto_indirectoT));
                        $curso->valor_capa    = trim(mb_strtoupper($valor_capaT));
                        $curso->time_perma    = $time_perma;
                        $curso->materia_capa  = trim(mb_strtoupper($materia_capaT));

                        $curso->save();

                        $curTemp->mensaje="<span style='color:#18e237'>Curso SAVE</span>";
                        $error = '';

                    }
                }else{
                    echo "El código del curso debe ser mayor a 2 digitos";
                }

                $curTemp->save();
                
            }
            $contF++;

            Cache::flush();
        }
        // end

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

    public function CursoImportResults()
    {
        $nlista = CursoTemp::count();
        $lista  = CursoTemp::orderBy("id", "ASC")->get();

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
        ##dd('importado-..');
        return view("cursos.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'nlista' => $nlista]);
    }

  
}
