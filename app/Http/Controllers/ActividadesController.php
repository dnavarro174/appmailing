<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use App\Actividade;
use App\Ponente;
use App\Departamento;

use App\Programacione;
//use App\estudiantes_prog_det;
use App\AccionesRolesPermisos;
use Excel;
use Alert;
use Auth;

class ActividadesController extends Controller
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
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "estudiantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if(session('eventos_id') == false){
            alert()->warning('Advertencia','Primero ingrese al evento de su interés');
            return redirect()->back();
        }

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
        
        Cache::flush();
        if($request->get('s')){
            $search = $request->get('s');

            $actividades_datos = Actividade::where('eventos_id',session('eventos_id'))
            ->where(function ($query) use ($search) {
                $query->orWhere("titulo", "LIKE", '%'.$search.'%')
                ->orWhere("subtitulo", "LIKE", '%'.$search.'%')
                ->orWhere("desc_actividad", "LIKE", '%'.$search.'%')
                ->orWhere("desc_ponentes", "LIKE", '%'.$search.'%')
                ->orWhere("fecha_desde", "LIKE", '%'.$search.'%')
                ->orWhere("fecha_hasta", "LIKE", '%'.$search.'%')
                ->orWhere("vacantes", "LIKE", '%'.$search.'%')
                ->orWhere("enlace", "LIKE", '%'.$search.'%')
                ->orWhere("hora_inicio", "LIKE", '%'.$search.'%')
                ->orWhere("hora_final", "LIKE", '%'.$search.'%')
                ->orWhere("ubicacion", "LIKE", '%'.$search.'%');
            })
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate(15);

        }else{
            
            $key = 'actividades.page.'.request('page', 3);
            $actividades_datos = Cache::rememberForever($key, function(){
                return Actividade::where('eventos_id',session('eventos_id'))
                    ->orderBy('id', request('sorted', 'DESC'))
                    ->paginate(15);
            });
        }

        return view('actividades.actividades', compact('actividades_datos','departamentos_datos','permisos')); 
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
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }  
        //primera forma
        //$entidades_datos = DB::table('entidades')->get();
        
        $cod_prog = Programacione::all();
        $cod_pon = Ponente::all();

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
        
        return view('actividades.create', compact('cod_prog', 'cod_pon', 'departamentos_datos'));
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
            'fecha_desde'=>'required',
            'fecha_hasta'=>'required',
        ]);
        
        DB::table('actividades')->insert([
             'programaciones_id'=>$request->input('programaciones_id'),
             'nomactividad'=>mb_strtoupper($request->input('nomactividad')),
             'descripcion'=>mb_strtoupper($request->input('descripcion')),
             'fecha_desde'=>$request->input('fecha_desde'),
             'fecha_hasta'=>$request->input('fecha_hasta'),
             'vacantes'=>mb_strtoupper($request->input('aforo')),
             'hora_inicio'=>$request->input('hora_inicio'),
             'hora_final'=>$request->input('hora_final'),
             'ubicacion'=>mb_strtoupper($request->input('ubicacion')),
             'inscritos'=>mb_strtoupper($request->input('inscritos')),
             'ponente_id'=>$request->input('ponente_id'),
             'estado'=>$request->input('cboEstado'),
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now(),
         ]);
        //programaciones_id','nomactividad','descripcion', 'fecha_desde','fecha_hasta','aforo','hora_inicio','hora_final','ubicacion','inscritos','estado','ponente_id'
        Cache::flush();
        alert()->success('Mensaje Satisfactorio','Registro grabado.');

        return redirect()->route('actividades.index');
        //return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cod_prog = Programacione::select('id','codigo','nombre')->get();
        
        $actividades_datos = Actividade::findOrFail($id);
        
        return view('actividades.show', compact('actividades_datos','cod_prog'));

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
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $cod_prog = Programacione::select('id','codigo','nombre')->get();
        $cod_pon = Ponente::all();
        
        $actividades_datos = DB::table('actividades')->where('id', $id)->first();
       

        return view('actividades.edit',compact('actividades_datos','cod_prog', 'cod_pon'));
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
        DB::table('actividades')->where('id',$id)->update([
            
            'programaciones_id'=>$request->input('programaciones_id'),
             'nomactividad'=>mb_strtoupper($request->input('nomactividad')),
             'descripcion'=>mb_strtoupper($request->input('descripcion')),
             'fecha_desde'=>$request->input('fecha_desde'),
             'fecha_hasta'=>$request->input('fecha_hasta'),
             'aforo'=>mb_strtoupper($request->input('aforo')),
             'hora_inicio'=>$request->input('hora_inicio'),
             'hora_final'=>$request->input('hora_final'),
             'ubicacion'=>mb_strtoupper($request->input('ubicacion')),
             'inscritos'=>mb_strtoupper($request->input('inscritos')),
             'ponente_id'=>$request->input('ponente_id'),
             'estado'=>$request->input('cboEstado'),
             'updated_at'=>Carbon::now(),
        ]);

        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
        //Redireccionamos
        //return redirect()->route('actividades.index');
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
        Actividade::where('id',$id)->delete();
        
        return redirect()->route('actividades.index');
    }

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            // borrar img
            $img_form = Actividade::where('id',$value)->first();
            $img_1 = "images/act/".$img_form->imagen;
            
            if(is_file($img_1)) unlink($img_1);

            Actividade::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        //return redirect()->route('actividades.index');
        return redirect()->back();
    }
}
