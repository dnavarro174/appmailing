<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\AccionesRolesPermisos, App\Ajuste;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        /*$this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["roles"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }*/
        $this->actualizarSesion();

        $datos = Ajuste::findOrFail(1);
        session(['logo' => $datos->logo]);
        $ajustes = [
            'email' => $datos->email,
            'email_nom' => $datos->email_nom
        ];

        session(['ajustes' => $ajustes]);
        
        return view('web.index');
        //return DB::connection()->getDatabaseName();
    }

}
