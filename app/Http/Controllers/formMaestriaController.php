<?php

namespace App\Http\Controllers;
use Cache;
use App\Maestria;
use App\Maestriadetalle;
use DB;
use Carbon\Carbon;
use App\AccionesRolesPermisos;
//use Mail;
use Excel;
use Alert;
use Auth;
use App\Evento;
use Illuminate\Http\Request;

class formMaestriaController extends Controller
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
        if(!isset( session("permisosTotales")["maestria"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "maestria";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $maestria_datos = Maestria::where("numeror", "LIKE", '%'.$search.'%')
            ->orWhere("descripcion", "LIKE", '%'.$search.'%')
            ->orWhere("uo_solicitante", "LIKE", '%'.$search.'%')
            ->orWhere("uo_beneficiada", "LIKE", '%'.$search.'%')
            ->orWhere("tipo", "LIKE", '%'.$search.'%')
            ->orWhere("fecha_req", "LIKE", '%'.$search.'%')
            ->orWhere("n_os", "LIKE", '%'.$search.'%')
            ->orWhere("solicitante", "LIKE", '%'.$search.'%')
            ->orWhere("gerente", "LIKE", '%'.$search.'%')
            ->orWhere("desc_actividad", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'maestria.page.'.request('page', 1);
            $maestria_datos = Cache::rememberForever($key, function(){
                return Maestria::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }
        
        //$maestria_datos = Maestria::all();

        return view("maestria.index", compact('maestria_datos', 'permisos'));

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
        if(!isset( session("permisosTotales")["maestria"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }  

        return view('maestria.create');
    }

    public function ejm(){

        return view('maestria.ejm');
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
            'numeror'=>'required',
            //'inputEmail'=>'required',
        ]);

        //try {
            $check = Maestria::where('numeror', $request->input('numeror'))->count();
            //dd($check);

            if($check){
                alert()->success('N de requeriemiento ya existe.','Mensaje Satisfactorio');
                return back();
                //return redirect()->back();
            }
            
            //agregar contralador db:  use DB; // para poder have insert
            DB::table('ma_formato')->insert([
                 'numeror'=>$request->input('numeror'),
                 'descripcion'=>$request->input('descripcion'),
                 'uo_solicitante'=>$request->input('uo_solicitante'),
                 'uo_beneficiada'=>$request->input('uo_beneficiada'),
                 'tipo'=>$request->input('tipo'),
                 'nactividad'=>$request->input('nactividad'),
                 'n_os'=>$request->input('n_os'),
                 'tipo_bs'=>$request->input('tipo_bs'),
                 'solicitante'=>$request->input('solicitante'),
                 'desc_actividad'=>$request->input('desc_actividad'),
                 'gerente'=>$request->input('gerente'),
                 'fecha_req'=>$request->input('fecha_req'),
                 'ip'=>$_SERVER["REMOTE_ADDR"],
                 'created_at'=>Carbon::now(),
                 'updated_at'=>Carbon::now()
            ]);

            $id_detalle = DB::getPdo()->lastInsertId();

            $item = $request->input('item');
            $descripcion_de = $request->input('descripcion_de');
            $um = $request->input('um');
            $cantidad = $request->input('cantidad');
            $especificaciones = $request->input('especificaciones');

            //$a = count($item);
            //$item = $item[1];
            //dd($item);

            foreach ($item as $key => $value) {

                DB::table('ma_detalle')->insert([
                 'item'=>$item[$key],
                 'descripcion_de'=>$descripcion_de[$key],
                 'um'=>$um[$key],
                 'cantidad'=>$cantidad[$key],
                 'especificaciones'=>$especificaciones[$key],
                 'id_formato'=>$id_detalle
                ]);

            }
            
            Cache::flush();

            alert()->success('Requerimiento Grabado.','Mensaje Satisfactorio');

            //return redirect()->route('maestria.create')->with('error','Success message');
            return redirect()->route('maestria.index');
          
            
        /*} catch (\Exception $e) {

            return \Response::json(['error' => $e->getMessage() ], 404); 
        }*/
        



        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Maestria  $maestria
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return view('maestria.prueba');
        $maestria_datos = Maestria::findOrFail($id);
        //$detalle_datos = Maestriadetalle::where('id_formato',$id);
        $detalle_datos = DB::table('ma_detalle')->where('id_formato', $id)->first();
        //dd($detalle_datos);

        return view('maestria.show',compact('maestria_datos','detalle_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Maestria  $maestria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["maestria"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        //$maestria_datos = Maestria::findOrFail($id);
        $maestria_datos = Maestria::Join('ma_detalle','ma_detalle.id_formato','=','ma_formato.id')
                        ->where('ma_formato.id','=',$id)
                            ->get();
        //dd($maestria_datos);

        //$maestria_detalle = DB::table('ma_detalle')->where('id_formato',$id)->get();

        //dd($maestria_detalle);

        return view('maestria.edit', compact('maestria_datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Maestria  $maestria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Actualizamos 
        DB::table('ma_formato')->where('id',$id)->update([
            'numeror'=>$request->input('numeror'),
             'descripcion'=>$request->input('descripcion'),
             'uo_solicitante'=>$request->input('uo_solicitante'),
             'uo_beneficiada'=>$request->input('uo_beneficiada'),
             'tipo'=>$request->input('tipo'),
             'nactividad'=>$request->input('nactividad'),
             'n_os'=>$request->input('n_os'),
             'tipo_bs'=>$request->input('tipo_bs'),
             'solicitante'=>$request->input('solicitante'),
             'desc_actividad'=>$request->input('desc_actividad'),
             'gerente'=>$request->input('gerente'),
             'fecha_req'=>$request->input('fecha_req'),
             'ip'=>$_SERVER["REMOTE_ADDR"],
             'updated_at'=>Carbon::now()
        ]);

        /*DB::table('ma_detalle')->where('id_formato',$id)->update([
            'item'=>$request->input('item'),
             'descripcion_de'=>$request->input('descripcion_de'),
             'um'=>$request->input('um'),
             'cantidad'=>$request->input('cantidad'),
             'especificaciones'=>$request->input('especificaciones')
        ]);*/

        DB::table('ma_detalle')->where('id_formato', $id)->delete();
        //return "Se borro ". $id;
        //dd($request->all());

        if(!is_null($request->input('item'))){

            $item = $request->input('item');
            $descripcion_de = $request->input('descripcion_de');
            $um = $request->input('um');
            $cantidad = $request->input('cantidad');
            $especificaciones = $request->input('especificaciones');

            foreach ($item as $key => $value) {

                DB::table('ma_detalle')->insert([
                 'item'=>$item[$key],
                 'descripcion_de'=>$descripcion_de[$key],
                 'um'=>$um[$key],
                 'cantidad'=>$cantidad[$key],
                 'especificaciones'=>$especificaciones[$key],
                 'id_formato'=>$id
                ]);
            }
        }

        Cache::flush();
        
        alert()->success('Registro actualizado.','Mensaje Satisfactorio');
        return redirect()->back();
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Maestria  $maestria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maestria $maestria)
    {
        //
    }

    public function eliminarVarios(Request $request){

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["maestria"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Maestria::where('id',$value)->delete();
            Maestriadetalle::where('id_formato',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('maestria.index');
    }
}
