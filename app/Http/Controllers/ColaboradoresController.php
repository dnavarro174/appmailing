<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;
use DB;

use App\Imports\CursosImport;
use App\Imports\ColaboradoresImport;
use App\AccionesRolesPermisos;
use App\Models\Colaboradores, App\Models\Colaboradores_temp;
use App\Models\Curso, App\CursoTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Validation\Rule;

class ColaboradoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "index";
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
    public function agregar_colaboradores(Request $request)
    {
        $ok = true;

        $rules = [
        //'cod_curso' => 'required|min:1|unique:m4_cursos,cod_curso,evento_id',
        'nombres' => 'required|min:2',
        'ap_paterno' => 'required|min:2',
        'codigo' => 
            'required|min:2',
            Rule::unique('m4_personalcgr')->where(function ($query) use ($request) {
                return $query->where('codigo',$request->cod_curso);
                             #->where('evento_id',$request->evento_id);
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
        
        $dat = DB::table('m4_personalcgr')
        //->where('evento_id',session('eventos_id'))
        ->orderBy('id','DESC')->paginate($page);
        $estados = [1=>"SI",0=>"NO"];
        $campos = ["id"=>"","email"=>"","status"=>1,'nom_curso'=>'', 'cod_curso'=>'','modalidad'=>'', 'fech_ini'=>'','fech_fin'=>''];
        $curso = (object)$campos;

        // Buscar
        if($request->get('s')){
            $s   = $request->get('s');
            $dat = DB::table('m4_personalcgr')
                #->where('evento_id',session('eventos_id'))
                ->where(function ($query) use ($s) {
                    $query->orWhere("nombres", "LIKE", '%'.$s.'%')
                    ->orWhere('codigo','like', '%'.$s.'%')
                    ->orWhere('ap_paterno','like', '%'.$s.'%')
                    ->orWhere('ap_materno','like', '%'.$s.'%')
                    ->orWhere(DB::raw('CONCAT(nombres," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$s.'%');
                })
                ->orderBy('id','DESC')->paginate($page);
        }
        
        if($delete == 1){
            $html = view("colaboradores.colaboradores_list", compact('dat','estados','curso','errors','save'))->render();
            return ["ok"=>$ok, "html"=>$html, "msg"=>$msg, "cant"=>$cant];
        }
        
        return view("colaboradores.colaboradores_list", compact('dat','estados','curso','errors','save'));
        
    }

    public function importar_cursos(){
        return view('import');
    }

    public function importar_cursos_store(Request $request) 
    {
        
        try {
            Excel::import(new ColaboradoresImport,request()->file('file'));

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             
             foreach ($failures as $failure) {
                 $failure->row(); // row that went wrong
                 $failure->attribute(); // either heading key (if using heading row concern) or column index
                 $failure->errors(); // Actual error messages from Laravel validator
                 $failure->values(); // The values of the row that has failed.
             }
        }
        
        
        $errors=new Collection;
        $save = 0;

        $page = 6;
        $cursos = DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->orderBy('id','DESC')->paginate($page);
        $estados = [1=>"SI",0=>"NO"];
        $campos = ["id"=>"","email"=>"","status"=>1,'nom_curso'=>'', 'cod_curso'=>'','modalidad'=>'', 'fech_ini'=>'','fech_fin'=>''];
        $curso = (object)$campos;
        

        //dd($cursos,$estados,$curso,$errors);
        return view("colaboradores.colaboradores_list", compact('cursos','estados','curso','errors','save'))->with('success', 'File imported successfully!');
        
    }

    public function ColaboradoresImport(Request $request)
    {
        $msg = "Solo se aceptan archivos XLS, XLSX y CSV. ";
        $results = [];
        if ($request->hasFile('file')) {

            $filesd = glob(base_path('storage\excel\*')); //get all file names

            foreach ($filesd as $filed) {
                if (is_file($filed))
                unlink($filed); //delete file
            }

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
            
            $results = Excel::toArray(new ColaboradoresImport, $request->file('file'));#$request->file->getRealPath()
            
            $results = $results[0];
            $file->move(base_path('storage/excel'), "cursos.xlsx");
            //print_r($results);
        }
        
        
        return $results;
    }

    public function ColaboradoresImportSave(Request $request)
    {
        
        $file_path = base_path('storage/excel');
        $directory = $file_path;
        $file_exc = scandir($directory)[2];
        
        \Config::set('excel.import.encoding.input', 'iso-8859-1');
        \Config::set('excel.import.encoding.output', 'iso-8859-1');

        $results = \Excel::toArray(new ColaboradoresImport, $file_path . "/" . $file_exc);
        
        $data_exc = $results[0];

        $flagC = $request["chkPrimeraFila"];
        $chkE_invitacion = $request["chkE_invitacion"];
        $tpo   = $request->tpo;//1: oci - 2: cgr

        if ($flagC != "") {
        $contF = 0;
        } else {
        $contF = 1;
        }

        DB::table('m4_personalcgr')->truncate();
        DB::table('m4_personalcgr_temp')->truncate();
        // begin
        //recorre el archivo excel abierto
        foreach ($data_exc as $lst) {

            if ($contF > 0) {
            // recorre los combos seleccionados
            $curTemp = new Colaboradores_temp();
            $codT = "";
            $nomT = "";
            $apaT = "";
            $amaT = "";
            $dniT = "";
            $catT = "";
            $entT = 0;
            $eventos_idT = session('eventos_id');
            $unidad_oT = "";
            $emailT = "";

                for ($x = 1; $x <= $request["totCol"]; $x++) {

                    if ($request["cmbOrganizar" . $x] == 1) {
                    $curTemp->codigo = $lst[$x - 1];
                    $codT = $lst[$x - 1];

                    $curTemp->codigo = trim($curTemp->codigo);
                    $codT = trim($codT);
                    }

                    if ($request["cmbOrganizar" . $x] == 2) {
                    $curTemp->nombres = mb_strtoupper($lst[$x - 1]);
                    $nomT = $lst[$x - 1];
                    }

                    if ($request["cmbOrganizar" . $x] == 3) {
                    $curTemp->ap_paterno = mb_strtoupper($lst[$x - 1]);
                    $apaT = $lst[$x - 1];
                    }

                    if ($request["cmbOrganizar" . $x] == 4) {
                    $curTemp->ap_materno = mb_strtoupper($lst[$x - 1]);
                    $amaT = $lst[$x - 1];                    
                    }

                    if ($request["cmbOrganizar" . $x] == 5) {
                        $curTemp->dni_doc = mb_strtoupper($lst[$x - 1]);
                        $dniT = $lst[$x - 1];                    
                        }

                    if ($request["cmbOrganizar" . $x] == 6) {
                    $curTemp->categoria = mb_strtoupper($lst[$x - 1]);
                    $catT = $lst[$x - 1];
                    }
                    // tpo:2 - CGR
                    if ($request["cmbOrganizar" . $x] == 7) {
                        $curTemp->unidad_organica = mb_strtoupper($lst[$x - 1]);
                        $unidad_oT = $lst[$x - 1];
                    }
                    if ($request["cmbOrganizar" . $x] == 8) {
                        $curTemp->email = mb_strtoupper($lst[$x - 1]);
                        $emailT = $lst[$x - 1];
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


                /* if(strlen($codT)<2){
                    $flagPASAcodcurso = 0;
                } */

                if($flagPASAcodcurso == 1){
                    #$verCurso = Curso::where("cod_curso",$codT)
                    $verCurso = Colaboradores::where("codigo",$codT)
                        ->where('evento_id',$eventos_idT)
                        ->first();
                    
                    if($verCurso){
                        $flagPASA = 1;

                        if($flagPASA==1){

                            // CONDICIONAL DE ACTUALIZACION
                            $colEst1 = 0;
                            if(trim($verCurso->codigo)!="" ){$colEst1++;}
                            if(trim($verCurso->nombres)!="" ){$colEst1++;}
                            if(trim($verCurso->ap_paterno)!="" ){$colEst1++;}
                            if(trim($verCurso->ap_materno)!="" ){$colEst1++;}
                            if(trim($verCurso->dni_doc)!="" ){$colEst1++;}
                            if(trim($verCurso->categoria)!="" ){$colEst1++;}
                            if(trim($verCurso->unidad_organica)!="" ){$colEst1++;}
                            if(trim($verCurso->email)!="" ){$colEst1++;}
        
                            $colEst2 = 0;
                            if($codT != ""){$colEst2++;}
                            if($nomT != ""){$colEst2++;}
                            if($apaT != ""){$colEst2++;}
                            if($amaT != ""){$colEst2++;}
                            if($dniT != ""){$colEst2++;}
        
                            //si columnas del excel existe => update
                            
                            if($codT)$verCurso->codigo = $codT;
                            if($nomT)$verCurso->nombres = trim(mb_strtoupper($nomT));
                            if($apaT)$verCurso->ap_paterno = trim(mb_strtoupper($apaT));
                            if($amaT)$verCurso->ap_materno  = trim(mb_strtoupper($amaT));
                            if($dniT)$verCurso->dni_doc = $dniT;
                            
                            #$verCurso->tpo=$tpo;

                            if($catT==""){$verCurso->categoria ="CAS";}else{$verCurso->categoria = trim(mb_strtoupper($catT));}
                            if($unidad_oT)$verCurso->unidad_organica = trim(mb_strtoupper($unidad_oT));
                            if($emailT)$verCurso->email = trim($emailT);
                            

                            $verCurso->save(); //end save
                            
                            $curTemp->mensaje="<span style='color:#18e237'>Personal UPDATE</span>";
        
                            $error = '';
                        }
                    
                    }else{

                        $flagPASA = 1;

                        // CREA EL NUEVO CURSO
                        #$curso = new Curso();
                        $curso = new Colaboradores();
                        $curso->codigo = $codT;
                        $curso->evento_id = $eventos_idT;
                        $curso->nombres = trim(mb_strtoupper($nomT));
                        $curso->ap_paterno = trim(mb_strtoupper($apaT));
                        $curso->ap_materno = trim(mb_strtoupper($amaT));
                        $curso->dni_doc = trim(mb_strtoupper($dniT));

                        if($catT==""){$curso->categoria ="CAS";}else{$curso->categoria = trim(mb_strtoupper($catT));}
                        #$curso->categoria  = trim(mb_strtoupper($catT));

                        $curso->unidad_organica = trim(mb_strtoupper($unidad_oT));
                        $curso->email = trim(($emailT));

                        $curso->save();

                        $curTemp->mensaje="<span style='color:#18e237'>Personal SAVE</span>";
                        $error = '';

                    }
                }else{
                    echo "El código del colaborador debe ser mayor a 2 digitos";
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

    public function ColaboradoresImportResults()
    {
        #$nlista = CursoTemp::count();
        #$lista  = CursoTemp::orderBy("id", "ASC")->get();
        $nlista = Colaboradores_temp::count();
        $lista  = Colaboradores_temp::orderBy("id", "ASC")->get();

        if (count($lista) == 0) {
        die("No hay registros");
        }
        $vEnt = 0;
       

        return view("colaboradores.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'nlista' => $nlista]);
    }

  
}
