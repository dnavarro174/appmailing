<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Request\CreateMessageRequest; //NO SALE

class PagesController extends Controller
{
	/*protected $request;

	public function __construct(Request $request)
	{
		$this -> request = $request;
	}*/
    public function __construct()
    {
        $this->middleware('VerificarToken',['except'=>['web.index']]);
        //$this->middleware('VerificarToken',['except'=>['web.index']]);//no bloquea el middleware
    }

    public function home(){
    	return view('web.index');
    }

    /*public function login(){
        return view('login.login');
    }*/

    public function form_estudiante(Request $request){
    	//return view('estudiante');
    	//return "Envio datos";
    	//return $this->request->all();
    	//return $request->all();
    	/*if($request->has('inputNombres'))
		{
		return "Si tiene Nombres:". $request->input('inputNombres');
		}
		return "No tiene nombres";*/
		$this->validate($request,[
			//'inputNombres'=>'required',
			'inputEmail'=>'required|email'
		]);
		return $request->all();
        return back()->with('info','Tu mensaje ha sido enviado correctamente');

    }

    public function entidades(){
    	return view('entidades');
    }

    public function entidades_edit(){
        return view('entidades_edit');
    }

    public function form_entidade(Request $request){
    //public function form_entidade(CreateMessageRequest $request){
    //public function form_entidade(CreateMessageRequest $request){
        
        $this->validate($request,[
            'inputEntidad'=>'required'
            //'inputEmail'=>'required|email|min:5'
        ]);

        //return $request->all();
        return back()->with('info','Tu mensaje ha sido enviado correctamente');
    }

}
