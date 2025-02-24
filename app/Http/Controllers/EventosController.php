<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Evento;
use App\Departamento;

use App\Tipo_evento;
//use App\estudiantes_prog_det;
use App\AccionesRolesPermisos;
use Excel;
use Alert;
use Auth;

class EventosController extends Controller
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

    public function index()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "eventos";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $eventos_datos = Evento::all();
        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
         return view('eventos', compact('eventos_datos','departamentos_datos','permisos')); 
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
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }  
        //primera forma
        //$entidades_datos = DB::table('entidades')->get();
        
        $tipo_evento = Tipo_evento::all();
        dd($tipo_evento);
        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
        
        return view('eventos.create', compact('tipo_evento','departamentos_datos'));
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
            'fecha_inicio'=>'required',
            'fecha_fin'=>'required',
        ]);
        //return view "Paso";
        //agregar contralador db:  use DB; // para poder have insert
        DB::table('eventos')->insert([
             'tipo_evento_id'=>$request->input('tipo_evento_id'),
             'fecha_inicio'=>$request->input('fecha_inicio'),
             'fecha_fin'=>$request->input('fecha_fin'),
             'descripcion'=>mb_strtoupper($request->input('descripcion')),
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now(),
             'estado'=>$request->input('cboEstado'),
         ]);

        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        return redirect()->route('eventos.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo_evento = Tipo_evento::all();
        //$eventos_datos = DB::table('eventos')->where('id', $id)->first();
        $eventos_datos = Evento::findOrFail($id);

        return view('eventos.show',compact('eventos_datos','tipo_evento'));
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
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_evento = Tipo_evento::all();
        $eventos_datos = DB::table('eventos')->where('id', $id)->first();
       

        return view('eventos.edit',compact('eventos_datos','tipo_evento'));
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
        //Actualizamos 
        DB::table('eventos')->where('id',$id)->update([
            
             'tipo_evento_id'=>$request->input('tipo_evento_id'),
             'fecha_inicio'=>$request->input('fecha_inicio'),
             'fecha_fin'=>$request->input('fecha_fin'),
             'descripcion'=>mb_strtoupper($request->input('descripcion')),
             
             'updated_at'=>Carbon::now(),
             'estado'=>$request->input('cboEstado'),
        ]);

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
        //Redireccionamos
        //return redirect()->route('eventos.index');
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
        Evento::where('id',$id)->delete();
        
        return redirect()->route('eventos.index');
    }

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["eventos"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Evento::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('eventos.index');
    }
}
