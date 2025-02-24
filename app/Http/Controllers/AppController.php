<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiante, App\User, App\Actividade;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");


class AppController extends Controller
{

    public function login(Request $request, $dni)
    {
    	
        $user = User::where('name',$dni)->get();
        if(count($user) > 0){
            $user = User::join('estudiantes as e','e.dni_doc','=','users.name')
            ->select('e.dni_doc as name','e.nombres','e.ap_paterno as apellidos')
            ->where('name',$dni)->first();

            //$jwt = JwtAuth::generateToken($user);

            $error = false;
            //$data = compact('user', 'jwt');
            $data = compact('user');

            return compact('error', 'data');

        }else{

            $error = true;
            $message = 'Invalid credentials';

            return compact('error', 'message');
        }
    }

    public function show(Request $request, $dni)
    {
    	//$user = Auth::guard('api')->user();
        //$user = auth()->user();
        #$user = User::where('name',$dni)->firstOrFail();

    	$datos = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc','=','de.estudiantes_id')
    		->select('estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.email','de.daccedio','de.modalidad_id')
    		->where('de.estudiantes_id',$dni)
    		->where('de.eventos_id',334)
    		->where('de.daccedio', 'SI')
    		->get();

    	if(count($datos) > 0){
    		/*$datos = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc','=','de.estudiantes_id')
    		->select('estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.email','de.daccedio')
    		->where('de.estudiantes_id',$dni)
    		->where('de.eventos_id',334)
    		//->where('de.daccedio', 'SI')
    		->get();*/

    		return compact('datos');

    	}else{
    		$error = true;
    		$message = 'Invalid credentials';
    		return compact('error', 'message');

    	}
    	
    }

    public function actividad(Request $request, $dni)
    {
        //$user = Auth::guard('api')->user();
    	//$user = Auth()->user();
        $user = User::where('name',$dni)->count();
        if($user==0){
            $error = true;
    		$message = 'Participante no registrado';
    		return compact('error', 'message');
        }

    	$datos = Actividade::join('actividades_estudiantes as de', 'actividades.id','=','de.actividad_id')
    		->select('actividades.titulo','actividades.subtitulo','actividades.ubicacion as lugar','actividades.fecha_desde','actividades.hora_inicio','actividades.hora_final','actividades.id','de.confirmado','actividades.enlace')
    		->where('de.estudiantes_id',$dni)
    		->where('de.eventos_id',334)
    		->get();

    	if(count($datos) > 0){
    		/* $datos = Actividade::join('actividades_estudiantes as de', 'actividades.id','=','de.actividad_id')
    		->select('actividades.titulo','actividades.subtitulo','actividades.fecha_desde','actividades.hora_inicio','actividades.hora_final','actividades.id','de.confirmado','actividades.enlace')
    		->where('de.estudiantes_id',$dni)
    		->where('de.eventos_id',334)
    		->get(); */

    		return compact('datos');

    	}else{
    		$error = true;
    		$message = 'Participante no ha registrado sus actividades';
    		return compact('error', 'message');
    	}

    }

    public function act_all()
    {
        //$user = Auth::guard('api')->user();
        
        $datos = Actividade::select('id','titulo','subtitulo','subtitulo','ubicacion as lugar','fecha_desde','hora_inicio','hora_final')
            ->where('eventos_id',334)
            ->get();

        if(count($datos) > 0){
            /* $datos = Actividade::select('id','titulo','subtitulo','subtitulo','ubicacion as lugar','fecha_desde','hora_inicio','hora_final')
            ->where('eventos_id',334)
            ->get(); */

            return compact('datos');

        }else{
            $error = true;
            $message = 'No hay actividades';
            return compact('error', 'message');
        }

    }

    public function actividades_generales()
    {
        //$user = Auth::guard('api')->user();
        
        $datos = Actividade::select('id','titulo','subtitulo','subtitulo','ubicacion as lugar','fecha_desde','hora_inicio','hora_final','enlace')
            ->where('eventos_id',335)
            ->get();
        
        if(count($datos) > 0){
            /* $datos = Actividade::select('id','titulo','subtitulo','subtitulo','ubicacion as lugar','fecha_desde','hora_inicio','hora_final')
            ->where('eventos_id',334)
            ->get(); */

            return compact('datos');

        }else{
            $error = true;
            $message = 'No hay actividades';
            return compact('error', 'message');
        }

    }



}
