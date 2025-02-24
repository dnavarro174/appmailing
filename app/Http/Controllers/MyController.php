<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
//use App\Exports\UsersExportQuery;
use App\Exports\UsersExportView;

use App\Imports\UsersImport;
use App\Imports\CursosImport;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Collection;
use DB;


class MyController extends Controller
{
    public function index(){

    }

    public function importExportView()

    {
       
       return view('import');

    }

     

    /**

    * @return \Illuminate\Support\Collection

    */

    #3 forma public function export() 
    public function export(UsersExport $usersExport) 

    {

        //return Excel::download(new UsersExport, 'usuario.xlsx');
        # 2 FORMA
        /* $usersExport = new UsersExport;
        return $usersExport->download('use.xlsx'); */

        # 3 FORMA
        //return (new UsersExport)->download('prueba.xlsx');

        # 4 forma
        #return $usersExport->download('prueba.xlsx');

        # Guardar en el servidor
        //$usersExport->store('prueba.xlsx','public');
        //$usersExport->store('prueba.csv','public');
        $usersExport->store('prueba.pdf','public');
        return "Archivo guardado correctamente";

    }

    public function exportQuery(){
        #primera forma
        //return (new UsersExportQuery(2021))->download('query.xlsx');
        
        #segunda forma
        return (new UsersExportQuery)->forDate('2021-11-24')->download('query.xlsx');
        //return (new UsersExportQuery)->forDate(request('date'))->download('query.xlsx');
    }

    public function exportView(){
        //return "Hola";
        return (new UsersExportView)->download('user-encabezado.xlsx');
    }

    //public function import(Request $request) 
    public function import() 
    {

        Excel::import(new UsersImport,request()->file('file'));
        return back();

        /*Excel::import(new UsersImport, $request->file);
        return redirect()->route('users.index')->with('success', 'User Imported Successfully');

        /* Excel::import(new UsersImport, 'users.xlsx');
        return redirect('/')->with('success', 'All good!'); */

    }

    public function import_cursos(Request $request) 
    {
        /* $caja = $request->get('caja');
        $file = request()->file('file');
        dd('llego',$file,$caja); */
        Excel::import(new CursosImport,request()->file('file'));
        //return back()->with('success','Registro guardado');

        /* Excel::import(new UsersImport, 'users.xlsx');
        
        return redirect('/')->with('success', 'All good!'); */
        $errors=new Collection;

        $page = 6;
        $cursos = DB::table('m4_cursos')->where('evento_id',session('eventos_id'))->orderBy('id','DESC')->paginate($page);
        $estados = [1=>"SI",0=>"NO"];
        $campos = ["id"=>"","email"=>"","status"=>1,'nom_curso'=>'', 'cod_curso'=>'','modalidad'=>'', 'fech_ini'=>'','fech_fin'=>''];
        $curso = (object)$campos;
        

        //dd($cursos,$estados,$curso,$errors);
        return view("cursos.cursos_list", compact('cursos','estados','curso','errors'));

    }


}
