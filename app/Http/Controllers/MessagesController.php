<?php

namespace App\Http\Controllers;
use DB;
use App\Entidade;
use Illuminate\Http\Request;
use Carbon\Carbon;


// crear controlador con CRUD
// php artisan make:controller MessagesController --resource
// us DB;
class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$entidades_datos = DB::table('entidades')->get();

        $entidades_datos = Entidade::all();
        return view('entidades', compact('entidades_datos'));
        //return view('entidades');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return "Mensaje - Mostrar el form creado";
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
        //return "Guardar y redireccionar 2";
        //return $request->all();
        //return $request->input('inputEntidad');
        $this->validate($request,[
            //'inputNombres'=>'required',
            'inputEntidad'=>'required'
        ]);
        //agregar contralador db:  use DB; // para poder have insert

        /*DB::table('entidades')->insert([
             'entidad'=>mb_strtoupper($request->input('inputEntidad')),
             'created_at'=>Carbon::now(),
             'updated_at'=>Carbon::now(),
         ]);*/

         //modelo 2
        /*$entidade = new Entidade;
        $entidade->entidad = $request->input('inputEntidad');
        $entidade->created_at = Carbon::now();
        $entidade->updated_at = Carbon::now();
        $entidade->save();*/

        //modelo 3
        //dd($request->all());
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
        return "Mostrar id = ".$id;
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
}
