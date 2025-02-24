<?php

namespace App\Http\Controllers;

use DB;
use App\Entidade;
use Illuminate\Http\Request;
use Carbon\Carbon;


// crear controlador con CRUD
// php artisan make:controller MessagesController --resource
// us DB;
class EntidadesController extends Controller
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
        $entidades_datos = Entidade::all();
        return view('entidades', compact('entidades_datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('entidades.create');
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
            'inputEntidad'=>'required'
        ]);

        Entidade::create([
            'entidad'=>mb_strtoupper($request->input('inputEntidad'))
        ]);

        return redirect()->route('entidades.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$entidades_datos = DB::table('entidades')->where('id', $id)->first();
        $entidades_datos = Entidade::findOrFail($id);
        return view('entidades.show',compact('entidades_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $entidades_datos = DB::table('entidades')->where('id', $id)->first();
        return view('entidades.edit',compact('entidades_datos'));
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
        DB::table('entidades')->where('id',$id)->update([
            'entidad'=>mb_strtoupper($request->input('inputEntidad')),
            'updated_at'=>Carbon::now(),
        ]);
        //Redireccionamos
        return redirect()->route('entidades.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('entidades')->where('id',$id)->delete();
        return redirect()->route('entidades.index');
    }

    public function eliminarVarios(Request $request)
    {
        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Entidade::where('id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }
        return redirect()->route('entidades.index');
    }
}
