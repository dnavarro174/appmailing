<?php

namespace App\Http\Controllers;

use DB;
use App\Tc_tipo;
use Illuminate\Http\Request;
use Carbon\Carbon;

//class CatCursosController extends Controller
class Tc_tipoController extends Controller
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
        $tc_tipos_datos = Tc_tipo::all();
        return view('tc_tipos', compact('tc_tipos_datos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tc_tipos.create');
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
            'tipo'=>'required'
        ]);

        Tc_tipo::create([
            'tipo'=>mb_strtoupper($request->input('tipo')),
            'descripcion'=>mb_strtoupper($request->input('descripcion')),
        ]);

        return redirect()->route('tc_tipos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$tc_tipos_datos = DB::table('tc_tipos')->where('tipo_id', $id)->first();
        $tc_tipos_datos = Tc_tipo::findOrFail($id);
        return view('tc_tipos.show',compact('tc_tipos_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $tc_tipos_datos = DB::table('tc_tipos')->where('tipo_id', $id)->first();
        return view('tc_tipos.edit',compact('tc_tipos_datos'));
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
        DB::table('tc_tipos')->where('tipo_id',$id)->update([
            'tipo'=>mb_strtoupper($request->input('tipo')),
            'descripcion'=>mb_strtoupper($request->input('descripcion')),
            'updated_at'=>Carbon::now(),
        ]);
        //Redireccionamos
        return redirect()->route('tc_tipos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('tc_tipos')->where('tipo_id',$id)->delete();
        return redirect()->route('tc_tipos.index');
    }
    

    public function eliminarVarios(Request $request)
    {
        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            Tc_tipo::where('tipo_id',$value)->delete();
            //DB::table('tipo_documento')->where('id',$value)->delete();
        }
        return redirect()->route('tc_tipos.index');
    }

    
}
 