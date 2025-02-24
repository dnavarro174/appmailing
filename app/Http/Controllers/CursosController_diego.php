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
    {
        $rules = [
        'cod_curso' => 'required|min:1|unique:m4_cursos,cod_curso',
        'nom_curso' => 'required|min:2',
        'modalidad' => 'required|min:2',
        ];
            $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $page = 6;

        //save or edit
        $id = $request->get('id');
        $status = $request->get('status');
        $nom_curso = mb_strtoupper($request->get('nom_curso'));
        $cod_curso = mb_strtoupper($request->get('cod_curso'));
        $modalidad = mb_strtoupper($request->get('modalidad'));
        $ini       = $request->get('fech_ini');
        $fin       = $request->get('fech_fin');
        
        
        /* if($request->get('save')){
            $save = $request->get('save');
        }else{
            $save=0;
        } */

        $save = ($request->get('save'))?$request->get('save'):0;

        $delete = $request->get('delete');
        $errors=new Collection;
        if($delete==1)DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->where('id',$id)->delete();

        if($save==1){
            $data = ['nom_curso' => $nom_curso,'status'=>$status>0?1:0, 'cod_curso'=>$cod_curso, 'modalidad'=>$modalidad, 'fech_ini'=>$ini,'fech_fin'=>$fin, 'evento_id'=>session('eventos_id'), 'tpo'=>1];
            if($id>0)$rules['cod_curso'].=",{$id}";
            $validator = Validator::make($data, $rules);
            
            $errors = $validator->errors();
            if (!$validator->fails()) {
                if($id>0)DB::table('m4_cursos')->where('id',$id)->update($data);
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
        //return redirect('/')->with('success', 'File imported successfully!');
    }

    public function CursoImport(Request $request)
    {
    $msg = "Solo se aceptan archivos XLS, XLSX y CSV. ";
    $results = [];
    if ($request->hasFile('file')) {

      $filesd = glob(base_path('storage\excel\*')); //get all file names
      //dd($filesd);
      foreach ($filesd as $filed) {
        if (is_file($filed))
          unlink($filed); //delete file
      }

      //$file = $request->file('file')->getClientOriginalName();
      $file     = $request->file('file');

      $fileog   = $file->getClientOriginalName();

      $filename = pathinfo($fileog, PATHINFO_FILENAME);
      $extension = pathinfo($fileog, PATHINFO_EXTENSION);
      $extension = trim($extension);
      //if(! $extension!="xls" || $extension!="xlsx" || $extension!="csv") ;
      if ($extension != "xlsx" && $extension != "csv" && $extension != "xls") {
        return \Response::json(['titulo' => "Solo se aceptan archivos XLS, XLSX y CSV.", 'error' => $msg], 404);
        exit;
      }

      \Config::set('excel.import.encoding.input', 'iso-8859-1');
      \Config::set('excel.import.encoding.output', 'iso-8859-1');

      //$reader = \Excel::selectSheetsByIndex(0)->load($request->file('file')->getRealPath())->formatDates(true, 'd/m/Y');
      //$results = $reader->noHeading()->get()->toArray();   //this will convert file to array
      $results = \Excel::toArray(new CursosImport, $request->file('file')->getRealPath());
      $results = $results[0];
      //$file->move( base_path('storage\excel'),"estudiantes.".$extension );
      $file->move(base_path('storage\excel'), "cursos.xlsx");
      //print_r($results);
    }

    return $results;
  }

  public function CursoImportSave(Request $request)
  {
    $file_path = base_path('storage\excel');
    $directory = $file_path;
    $file_exc = scandir($directory)[2];

    \Config::set('excel.import.encoding.input', 'iso-8859-1');
    \Config::set('excel.import.encoding.output', 'iso-8859-1');

    $results = \Excel::toArray(new CursosImport, $file_path . "/" . $file_exc);// de esta forma se lee el archivo excel
    // amigo, este codigo es el que me permite leer el excel, y no tengo idea como personalizar la fecha ahi
    $data_exc = $results[0];
    // solo deseo que me des la mano para que el excel que deseo lo pueda visualizar de este formato: dd/mm/yyyy
    // la parte de abajo es validacion 
    //return $data_exc;

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
                //$feciniT = $feciniT !="" ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($feciniT)->format("d/m/Y"):"";
                
                }

                if ($request["cmbOrganizar" . $x] == 5) {
                $curTemp->fech_fin = mb_strtoupper($lst[$x - 1]);
                $fecfinT = $lst[$x - 1];
                }

                /*if($request["cmbOrganizar".$x]==13){
                            $entTv = $lst[$x - 1];
                            $entTv = trim($entTv);
                            $entidadTemp = DB::table('entidades')->where("entidad",$entTv)->first();
                            if($entidadTemp){
                                $entT = $entidadTemp->id;
                                $curTemp->idEntidad = $entT;
                            }
                        }
                */
            }

            /* valida si existe evento*/
            $si_evento = DB::table('eventos')->where('id', $eventos_idT)->count();

            if($si_evento == 0){
                return "error_no_evento";
            }

            $flagPASA = 0;
            $flagPASAdni = 1;
            $flagPASAcel = 1;


            if(strlen($codT)<4){
                $flagPASAdni = 0;
            }

            if($flagPASAdni == 1){
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
                        if($nomT)$verCurso->nom_curso = mb_strtoupper($nomT);
                        if($modT)$verCurso->modalidad = mb_strtoupper($modT);
                        if($feciniT)$verCurso->fech_ini  = mb_strtoupper($feciniT);
                        if($fecfinT)$verCurso->fech_fin = $fecfinT;
    
                        $verCurso->tpo=$tpo;
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

                    // CREA EL NUEVO CURSO
                    $curso = new Curso();
                    $curso->cod_curso = $codT;
                    $curso->evento_id = $eventos_idT;
                    $curso->tpo = $tpo;//tipo: oci
                    $curso->nom_curso = mb_strtoupper($nomT);
                    $curso->modalidad = mb_strtoupper($modT);
                    $curso->fech_ini  = mb_strtoupper($feciniT);
                    $curso->fech_fin = $fecfinT;
                    $curso->save();

                    $curTemp->mensaje="<span style='color:#18e237'>Curso SAVE</span>";
                    $error = '';

                }
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

    return view("cursos.importresults", ['lista' => $lista, 'vEnt' => $vEnt, 'nlista' => $nlista]);
  }

  
}
