<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\formMaestria, App\formEstudiosInvest;
use DB;
use Carbon\Carbon;
use App\Estudiante, App\Emails;
use App\Newsletter;
use App\Departamento;
use App\AccionesRolesPermisos;
use Mail;
use Alert;
use Auth;

class PreviewController extends Controller
{
    public function create(Request $request)
    {   
        

    }

    public function store(Request $request)
    {
        dd($request->all());
    }
    public function preview($doc,$file,$tipo=0)
    {
    	//tipo: 4=maestria 5=est.investigacion
    	if($tipo==4)
        	$fila = formMaestria::where('detalle_id',$file)->first();
        else
        	$fila =formEstudiosInvest::where('id_datos',$file)->first();
        $path = storage_path('app/') . $fila->$doc;

        //return $path;
        //return Response::make(file_get_contents($path), 200);
        return response()->download($path);
    }

}
