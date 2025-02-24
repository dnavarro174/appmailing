<?php

namespace App\Http\Controllers;

use App\AccionesRolesPermisos;
use App\Campanias;
use App\EstudianteTemp;
use App\Historiaemail;
use App\HistoryEmails;
use App\Imports\EstudianteImport;
use App\Imports\TStudentsImport;
use App\Imports\TStudentsImportShort;
use App\Jobs\GenerateImportJob;
use App\Models\InvImportacion;
use App\Models\InvStudents;
use App\Repositories\EstudianteRepository;
use App\Traits\ManageInv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Cache;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class InvImportacionesController extends Controller
{
    use ManageInv;
    
    public function __construct()
    {
        $this->middleware('auth');
    }   

    public function index(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        //phpinfo();
        Session::forget("personalizados");
        $campanias_data = Campanias::all();

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "crm";
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

        Cache::flush();
        if($request->get('s')){

            $search = $request->get('s');

            $campanias_data = InvImportacion::where("nombre", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

        }else{

            $key = 'invimp.page.'.request('page', 1);
            $campanias_data = Cache::rememberForever($key, function() use ($pag){
                return InvImportacion::orderBy('id', request('sorted', 'DESC'))
                    ->paginate($pag);
            });

        }
        //$campanias_data = Campanias::all();
        $camps = $campanias_data;
        $personalizados = Session::has('personalizados')?Session::get('personalizados'):array();

        $q = InvImportacion::select(\DB::raw('max(id) as maxid'))->pluck('maxid')->toArray();
        $max = count($q) ? $q[0] : 0;
        $default_nombre = 'Importación #'.++$max;


        $form = [
            "estados" => ["Todos", "Sin Error", "Error"]
        ];


        return view("invimp.index", compact('camps', 'permisos', 'default_nombre', 'form'));

    }

    public function show($id){}

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["crm"]["permisos"]["eliminar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $ids = $request->tipo_doc;
        if(count($ids)>0){
            foreach($ids as $id){
                InvImportacion::where('id',$id)->delete();
                InvStudents::where('import_id',$id)->delete();
            }
        }
        Cache::flush();
        alert()->error('Registros borrados.','Eliminado');
        return redirect()->back();
    }

    public function import(Request $request)
    {/*
        ini_set('max_execution_time', 300000);
        ini_set('max_input_time',300000);
        ini_set('memory_limit','4096M');
        ini_set('post_max_size','110M');
        ini_set('upload_max_filesize','100M');

        $error_types = array(
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            'The uploaded file was only partially uploaded.',
            'No file was uploaded.',
            6 => 'Missing a temporary folder.',
            'Failed to write file to disk.',
            'A PHP extension stopped the file upload.'
        );

        // Outside a loop...
        if ($_FILES['file']['error'] == 0) {
            // here userfile is the name
            // i.e(<input type="file" name="*userfile*" size="30" id="userfile">
            echo "no error ";
        } else {
            $error_message = $error_types[$_FILES['file']['error']];
            echo $error_message;
        }

        dd($_FILES['file']);*/


        $msg = "Solo se aceptan archivos XLS, XLSX y CSV. ";
        $results = [];

        $file     = $request->file('file');
        \session()->remove('filename_import');

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


            #$collection = (new FastExcel)->import($file);
            $fastexcel = true;
            $current_imports= 0;
            $limit_imports = 10;
            $total_imports = 0;
            if($fastexcel){
                $r = (new FastExcel)->withoutHeaders()->import($file, function ($line)use(&$results, &$current_imports, $limit_imports, &$total_imports){
                    $total_imports++;
                    if ($limit_imports > 0 && $current_imports++ >= $limit_imports) return null;
                    $results[0][] = array_values($line);
                    return ($line);
                });
            }else{
                if($limit_imports > 0)
                    $results = Excel::toArray(new TStudentsImportShort($limit_imports), $file);
                else
                    $results = Excel::toArray(new TStudentsImport(), $file);
            }
            \Config::set('excel.import.encoding.input', 'iso-8859-1');
            \Config::set('excel.import.encoding.output', 'iso-8859-1');

            $filePath = $file->storeAs('storage\excel', "estudiantes.".$extension, 'real_public');
            //\session('filename_import', $filePath);
            \session()->put('filename_import', $filePath);
        }
        return count($results)>0?$results[0]:[];
        //return $results;
    }


    public function EstudianteImportResults() {

        //SAVE


        dd(request());


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

    public function EstudianteImportSave(Request $request, EstudianteRepository $repository)
    {
        $filename = session('filename_import');
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $extension = trim($extension);
        $name = pathinfo($filename, PATHINFO_FILENAME).'.'.$extension;

        $campos = '';
        $nombre = $request->input('nombre') ?? '';
        $totCol = $request->input('totCol')  ?? '';
        $formato = $request->input('txtFormatoF') ?? '';
        $first_row = $request->input('txtFila') ?? '';
        $is_first = $request->input('chkPrimeraFila') ?? '';
        $excluir_dni = $request->input('exclude') ?? '0';
        $fields = [];
        for($i=1; $i<=$totCol;$i++){
            $ii = $request->input('cmbOrganizar'.$i) ?? '0';
            array_push($fields, intval($ii));
        }
        $data = [
            'nombre' => $nombre,
            'fields' => join(',', $fields),
            'formato' => $formato,
            'first_row' => $first_row,
            'is_first' => $is_first,
            'excluir_dni' => $excluir_dni,
        ];

        $invimp = InvImportacion::create($data);
        $folder = 'storage/excel/';
        $folder_import = $folder.'imports/';

        $filename0 = $folder_import. $invimp->id . '.'. $extension;
        //$filename2 = public_path($filename0);
        $filename2 = public_path($filename);

        $file_old = public_path($folder.$name);
        $file_new = public_path($folder_import.$invimp->id . '.'. $extension);

        $invimp->filesize = \File::size($file_old);
        if(\File::exists($file_new))\File::delete($file_new);
        \File::move($file_old, $file_new);
        $invimp->file = $filename0;
        $invimp->save();
        GenerateImportJob::dispatch($invimp->id)->onConnection('database')->onQueue("emails");
            //->delay(Carbon::now()->addMinutes(0.2));
        return "ok";
    }

    public function generate($id)
    {
        $this->generateImport($id);
    }

    // Funcionalidad para agregar exepciones desde un formulario
    public function detail(Request $request, $import_id=0)
    {
        //save or edit
        $save = $request->get('save');
        $delete = $request->get('delete');
        $edit = $request->get('edit')??0;

        $errors=new Collection;
        if($delete==1){
            $id = $request->get('id');
            \DB::table('inv_estudiantes')->where('id',$id)->delete();
        }

        if($save==1){
            $id = $request->get('sid');
            $import_id = $request->get('iid');
            $rules = [
                'email' => 'required|min:1|email|unique:inv_estudiantes,email,'.$id.',id,import_id,'.$import_id,
                'nombres' => 'required|min:3',
            ];
            $data = [
                'email' => $request->get('email'),
                'nombres' => $request->get('nombres'),
                'ap_paterno' => $request->get('ap_paterno'),
                'ap_materno' => $request->get('ap_materno'),
                'error' => 0
                ];
            $validator = Validator::make($data, $rules);
            $errors = $validator->errors();
            if ($validator->fails()) {
                $edit = $id;
            }
            else {
                $invstudent = InvStudents::find($id);
                $error = $invstudent->error;
                $invstudent->update($data);
                /*if($error>0){
                    $imp = InvImportacion::find($import_id);
                    $imp->oks = $imp->oks+1;
                    $imp->error = $imp->error-1;
                    $imp->save();
                }*/
                $d = $this->setTotalsImport($import_id);
                InvImportacion::where('id', $import_id)->update([
                    'error' => $d['error'],
                    'oks' => $d['ok'],
                ]);
            }
        }
        $page = 5;
        $q = $request->get('q');
        $s = $request->get('s');
        $e = $request->get('e');
        $students = InvStudents::where('import_id', $import_id)
            ->when($q, function($students) use($q){
                //$students->where('email','LIKE','%'.$q.'%');
                $students->where(function($query) use($q){
                    $query->where('email','LIKE','%'.$q.'%');
                    $query->orWhere('nombres','LIKE','%'.$q.'%');
                    $query->orWhere('ap_paterno','LIKE','%'.$q.'%');
                    $query->orWhere('ap_materno','LIKE','%'.$q.'%');
                });
//                $students->where('nombres','LIKE','%'.$q.'%');
//                $students->where('ape_mat','LIKE','%'.$q.'%');
            })
            ->when($e > 0, function($students) use($e){
                $signo = $e == 1 ? '=': '>';
                $students->where('error',$signo,0);
            })
            ->paginate($page);
        $fs = self::$fields;
        $campos = ["id"=>"","email"=>"","status"=>1];
        $noemail = (object)$campos;
        $page2 = $request->get('page') ?? 1;
        if(count($students)==1)$page2--;
        if($page2<=1)$page2 = null;

        return view("invimp.detail", compact('students','noemail','errors','edit','page2'));
    }
}
