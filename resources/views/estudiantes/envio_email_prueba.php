<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Datos_email;
use App\Historia_email;
use App\Plantillaemail;
use App\Usuario;
use Mail;
use Illuminate\Http\Request;


    	//http://localhost:8080/tkt_v12.1/public/envio_email

    	//$users = DB::table('datos_email')->count();
    	$datos_email = Datos_email::count();

    	$prin = "";$xid = "";
    	if($datos_email > 0){
    		$datos_email = Datos_email::all();

	    	foreach ($datos_email as $key => $value) {
	    		$id = $value->id;
	    		$id_participante = $value->participante_id;
	    		$id_plantilla = $value->plantillaemail_id;
	    		$laplantilla = $value->plantillahtml;
	    		$id_lista = $value->lista;
	    		$dni = $value->dni;
	    		$asunto = $value->asunto;
	    		$email = $value->email;
	    		$nombres = $value->nombres .' '.$value->apellido_paterno;

	    		//----------Se crea el archivo con la plantilla que viene de la bd
			    //$file=fopen('../storage/templates/'.$id_plantilla.'.html','w') or die ("error creando fichero!");
			    //$file=fopen(resource_path().'/views/email/'.$id_plantilla.'.html','w') or die ("error creando fichero!");

			    $file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");

				fwrite($file,$laplantilla);
				fclose($file);
			    //-------------- D:\serv_local\laragon\www\tkt_v12.1\resources\views\email

			    //echo $file;
			    $rs_plantilla = Plantillaemail::where('id',$id_plantilla)->get();

			    //$ss = $rs_plantilla->all();
			    //dd($ss);

			    $flujo_ejecucion = ($rs_plantilla[0]->flujo_ejecucion);
			    //echo $prin .= "flujo: ".$flujo_ejecucion;

			    //$flujo_ejecucion = $rs_plantilla->flujo_ejecucion;
			    //dd($flujo_ejecucion);	

			    $datos_email = array(
	                    'estudiante_id' => $dni,
	                    'email' => $email,
	                    'name'  => $nombres,
	                    'flujo_ejecucion' => $flujo_ejecucion,
	                    'asunto'    => $asunto,
	                    'html_id'    => $id_plantilla,
	                    'lista'      => $id_lista
	                );
	                
	                // CONFIGURAR DIFERENTES VISTAS CON UN SOLO CLICK
	                if($flujo_ejecucion == "NEWSLETTER"){

	                    $data = array(
	                        'detail'    => "Mensaje enviado",
	                        'html'      => $laplantilla,
	                        'email'     => $email,
	                        'id'        => $dni, //$id_estu,
	                        'nombre'    => $nombres

	                    );

	                    // pasamos $data: pasamos el array a la vista

	                    /*Mail::send('email.envio_newsletter', $data, function ($mensaje) use ($datos_email){
		                    //$mensaje->from('admin@enc.pe','Admin');
		                    $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
	                    });*/

	                }

	                // INVITACION
	                if($flujo_ejecucion == "INVITACION"){

	                	// EXTRAER USUARIO Y PASS
	                	//SELECT * FROM users where name='10000001' limit 1
	                	//$usuarios_datos = Usuario::orderBy('id','desc')->get();
	                	$usuario = Usuario::where('name',$dni)->select('name','password')->limit(1)->get();
	                	//dd($usuario);
	                	$usu = $usuario[0]->name;
	                	$pass = $usuario[0]->password;

	                    $data = array(
	                        'detail'    => "Mensaje enviado",
	                        'html'      => $laplantilla,
	                        'email'     => $email,
	                        'id'        => $dni, //$id_estu,
	                        'nombre'    => $nombres,
	                        'usuario'	=> $usu,
		                    'pass'		=> $pass

	                    );

	                    // pasamos $data: pasamos el array a la vista
	                    //$id_plantilla.'.blade.php

	                    //Mail::send('email.envio_newsletter', $data, function ($mensaje) use ($datos_email){
	                    Mail::send('email.'.$id_plantilla, $data, function ($mensaje) use ($datos_email){
		                    //$mensaje->from('admin@enc.pe','Admin');
		                    $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
	                    });

	                    DB::table('historia_email')->where('id',$id)->update([
		                	'fecha_envio'	=>	Carbon::now()
		                ]);

	                }
	                //CONFIRMACION
	                // FALTA CONFIG QUE SE ASOCIE POR LISTA: unset --> elimina un array

	                if($flujo_ejecucion == "CONFIRMACION" OR $flujo_ejecucion == "RECORDATORIO"){

	                    $data = array(
	                        'detail'    => "Mensaje enviado",
	                        'html'      => $laplantilla
	                    );

	                    // Falta adjuntar PDF CONFIRMACION

	                    /*Mail::send('email.envio_plantilla', $data, function ($mensaje) use ($datos_email){
		                    //$mensaje->from('admin@enc.pe','Admin');
		                    $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
	                    });*/
	                }

	                // update historia_email

	                //echo "<br>".$xid .= $id."<br>";
	                /*DB::table('historia_email')->where('id',$id)->update([
	                	'fecha_envio'	=>	Carbon::now()

	                ]);*/

	          
	    	}
			//echo 'envio_email '.$laplantilla;
			echo "<h1>Proceso de Envio de Correos</h1>";
		}else{
			echo "<h1>0 Correos Enviados</h1>";
			
		}
