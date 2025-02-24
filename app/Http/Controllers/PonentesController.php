<?php

namespace App\Http\Controllers;
use Cache;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Departamento;

use App\Ponente;
//use App\estudiantes_prog_det;
use App\AccionesRolesPermisos;
use Excel;
use Alert;
use Auth;

class PonentesController extends Controller
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

    public function index(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ponentes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "ponentes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $ponentes_datos = Ponente::where("nombre", "LIKE", '%'.$search.'%')
            ->orWhere("ap_paterno", "LIKE", '%'.$search.'%')
            ->orWhere("ap_materno", "LIKE", '%'.$search.'%')
            ->orWhere("email", "LIKE", '%'.$search.'%')
            ->orWhere("email_2", "LIKE", '%'.$search.'%')
            ->orWhere("telefono", "LIKE", '%'.$search.'%')
            ->orWhere("telefono_2", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'ponentes.page.'.request('page', 1);
            $ponentes_datos = Cache::rememberForever($key, function(){
                return Ponente::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }

        //$ponentes_datos = Ponente::orderBy('id','desc')->get();
        return view('ponentes', compact('ponentes_datos','departamentos_datos','permisos')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ponentes"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }  
        
        return view('ponentes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre'=>'required',
        ]);
        
        //agregar contralador db:  use DB; // para poder have insert
        DB::table('ponentes')->insert([
             'nombre'=>mb_strtoupper($request->input('nombre')),
             'ap_paterno'=>mb_strtoupper($request->input('ap_paterno')),
             'ap_materno'=>mb_strtoupper($request->input('ap_materno')),
             'email'=>$request->input('email'),
             'email_2'=>$request->input('email_2'),
             'telefono'=>mb_strtoupper($request->input('telefono')),
             'telefono_2'=>$request->input('telefono_2'),
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now()
         ]);

        Cache::flush();

        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        return redirect()->route('ponentes.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ponentes_datos = Ponente::findOrFail($id);
        
        return view('ponentes.show', compact('ponentes_datos'));
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
        if(!isset( session("permisosTotales")["ponentes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        //$actividades_datos = DB::table('actividades')->where('id', $id)->first();
        $ponentes_datos = Ponente::where('id',$id)->first();
        return view('ponentes.edit', compact('ponentes_datos'));
        
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
        // Actualizamos
        DB::table('ponentes')->where('id',$id)->update([

             'nombre'=>$request->input('nombre'),
             'ap_paterno'=>mb_strtoupper($request->input('ap_paterno')),
             'ap_materno'=>mb_strtoupper($request->input('ap_materno')),
             'email'=>$request->input('email'),
             'email_2'=>$request->input('email_2'),
             'telefono'=>mb_strtoupper($request->input('telefono')),
             'telefono_2'=>$request->input('telefono_2'),
             'updated_at'=>Carbon::now()
         ]);

        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');

        //return redirect()->route('ponentes.index');
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
        //
    }

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ponentes"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Ponente::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('ponentes.index');
    }
}
