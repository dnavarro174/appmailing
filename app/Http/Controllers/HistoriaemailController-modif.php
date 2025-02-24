<?php

namespace App\Http\Controllers;
use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Historiaemail;
//use App\Departamento;
//use App\Tipo_evento;

use App\AccionesRolesPermisos;
//use Excel;
use Alert;
use Auth;

class HistoriaemailController extends Controller
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
        if(!isset( session("permisosTotales")["historiaemail"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "historiaemail";
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
        $s = $request->get('s');

        if($request->get('s')){
            Cache::flush();

            $historiaemail_datos = Historiaemail::where('eventos_id',session('eventos_id'))
            ->where(function ($query) use ($s) {
                $query->where("fecha", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiante_id", "LIKE", '%'.$s.'%')
                    ->orWhere("asunto", "LIKE", '%'.$s.'%')
                    ->orWhere("nombres", "LIKE", '%'.$s.'%')
                    ->orWhere("email", "LIKE", '%'.$s.'%')
                    ->orWhere("fecha_envio", "LIKE", '%'.$s.'%');
            });
            $historiaemail_datos->orderBy('id', 'DESC')->paginate($pag);
            
   
        }elseif($request->get('evid')){
            //dd(session('eventos_id'));

            Cache::flush();
            $key = 'historia.page.'.request('page', 1);
            $historiaemail_datos = Cache::rememberForever($key, function() use ($pag){
                return Historiaemail::orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            });

        }else{
            Cache::flush();
            $key = 'historia.page.'.request('page', 1);
            $historiaemail_datos = Cache::rememberForever($key, function() use ($pag){
                return Historiaemail::where('eventos_id',session('eventos_id'))->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            });
        }

        //$historiaemail_datos = Historiaemail::orderBy('id','desc')->get();

        return view('historiaemail.historiaemail', compact('historiaemail_datos','permisos')); 
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        if(session('eventos_id') == false){
            return redirect()->route('caii.index');
        }

        $eventos_id = session('eventos_id');
        $datos = DB::table('historia_email')->where('id', $id)->first();
        //dd($estudiantes_datos);

        return view('historiaemail.edit', compact('datos'));
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
        $sql = DB::table('historia_email')->where('id',$id)->where('fecha_envio',"2002-02-02 00:00:00")->count();
        if($sql==1){
            DB::table('historia_email')->where('id',$id)->update([
                'email'=>$request->input('email'),
                'celular'=>mb_strtoupper($request->input('celular')),
                'fecha_envio'=>"2000-01-01 00:00:00"
            ]);
        }else{
            DB::table('historia_email')->where('id',$id)->update([
                'email'=>$request->input('email'),
                'celular'=>mb_strtoupper($request->input('celular')),
                'fecha_envio'=>mb_strtoupper($request->input('fecha_envio')),
            ]);
        }
        //Actualizamos 
        
        Cache::flush();

        alert()->success('Registro actualizado.','Mensaje Satisfactorio');

        return redirect()->route('historiaemail.index');
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

    public function eliminarVarios(Request $request){
        
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["historiaemail"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Historiaemail::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }
        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('historiaemail.index');
    }

}
