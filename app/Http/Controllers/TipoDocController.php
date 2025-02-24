<?php

namespace App\Http\Controllers;

use App;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\TipoDoc;
use DB;

class TipoDocController extends Controller
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

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $tipo_doc_datos = TipoDoc::where("tipo_doc", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'tipo.page.'.request('page', 1);
            $tipo_doc_datos = Cache::rememberForever($key, function(){
                return TipoDoc::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }
        /*$tipo_doc_datos = DB::table('tipo_documento')->get();*/
        return view('tipo_doc.tipo_doc', compact('tipo_doc_datos'));
    }


    public function eliminarVarios(Request $request)
    {
        $tipo_doc = $request->tipo_doc;
        //var_dump($tipo_doc);
        foreach ($tipo_doc as $value) {
            TipoDoc::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }
        Cache::flush();
        return redirect()->route('tipo_doc.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipo_doc.create');
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
            //'inputNombres'=>'required',
            'inputTipoDoc'=>'required'
        ]);
        //agregar contralador db:  use DB; // para poder have insert
         DB::table('tipo_documento')->insert([
             'tipo_doc'=>mb_strtoupper($request->input('inputTipoDoc')),
         ]);
         Cache::flush();

        return redirect()->route('tipo_doc.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo_doc_datos = DB::table('tipo_documento')->where('id', $id)->first();
        return view('tipo_doc.show',compact('tipo_doc_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo_doc_datos = DB::table('tipo_documento')->where('id', $id)->first();
        return view('tipo_doc.edit',compact('tipo_doc_datos'));
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
        DB::table('tipo_documento')->where('id',$id)->update([
            'tipo_doc'=>mb_strtoupper($request->input('inputTipoDoc')),
        ]);

        Cache::flush();
        //Redireccionamos
        return redirect()->route('tipo_doc.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('tipo_documento')->where('id',$id)->delete();
        return redirect()->route('tipo_doc.index');
    }

    
}
