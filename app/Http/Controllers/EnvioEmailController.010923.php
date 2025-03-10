<?php
namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Datos_email;
use App\Historia_email;
use App\Plantillaemail;
use App\Usuario;
use App\Whatsapp;
use Mail;
use PDF;
//use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EnvioEmailController extends Controller
{

    public function envio_email(){

    	//https://www.enc-ticketing.org/envio_email

    	$datos_email = Datos_email::count();
		
    	$mensaje = "";
    	$send = "";

    	$prin = "";$xid = ""; $error = "";
	    	if($datos_email > 0){
	    		$datos_email = Datos_email::all();

		    	foreach ($datos_email as $key => $value) {

		    		$id = $value->id;
		    		$id_participante = $value->participante_id;
		    		//$id_plantilla = $value->plantillaemail_id;
		    		//$laplantilla = $value->plantillahtml;

		    		$id_lista 	 = $value->lista;
		    		$dni 		 = $value->dni;
		    		
		    		$asunto 	 = $value->asunto;
		    		$nombres 	 = $value->nombres.' '.$value->apellido_paterno;
		    		$from_nombre = $value->from_nombre;
		    		$from_email  = $value->from_email;
					
		    		if(is_null($from_nombre)){
		    			$from_email = "enc@enc-ticketing.org";
		    			$from_nombre = "Escuela Nacional de Control";
		    		}

		    		$nombre 	 = $value->nombres;
		            $nombres_ape = $value->nombres ." ".$value->apellido_paterno;
		            $nombres_apat = $value->apellido_paterno;
		            $nombres_amat = $value->apellido_materno;

		            $flujo_ejecucion = $value->flujo_ejecucion;
		            $xtipo = $value->tipo;
		            
		    		if($value->tipo == "EMAIL"){

		    			$msg_text 	= $value->msg_text;//plantila email p_preregistro_2
		    			$email 		= $value->email;
		    			$email 		= trim($email);

		    			/*$file=fopen(resource_path().'/views/email/'.$id_lista.'.blade.php','w') or die ("error creando fichero!");

						$leido = fwrite($file,$msg_text);
						fclose($file);*/

							/*$file_2=fopen(resource_path().'/views/email/'.$msg_text,'w') or die ("error creando fichero!");

							//$leido = fwrite($file_2,$msg_text);
							fclose($file_2);*/

							//$fp = fopen(url('') . '/files/html/'.$rs_datos->p_conf_inscripcion,'w');
		                    //$linea = fgets($fp);
		                    //fclose($fp);

						    //echo $file;
						    //$rs_plantilla = Plantillaemail::where('id',$id_plantilla)->get();
						    //$flujo_ejecucion = ($rs_plantilla[0]->flujo_ejecucion);

						    $datos_email = array(
				                    'estudiante_id'   => $dni,
				                    'email'           => $email,
				                    'name'            => $nombres,
				                    'from_nombre'     => $from_nombre,
				                    'from_email'      => $from_email,
				                    'flujo_ejecucion' => $flujo_ejecucion,
				                    'asunto'          => $asunto,
				                    'html_id'         => $id_lista,
				                    'lista'           => $id_lista
				                );

						    	// MAILING
						    	if($flujo_ejecucion == "MAILING"){

						    		$id_plantilla = $value->plantillaemail_id;
						    		// RECUPERO LA PLANTILLA
						    		$rs_plantilla = Plantillaemail::where('id',$id_plantilla)->first();

								    $flujo_ejecucion = $rs_plantilla->flujo_ejecucion;
								    $laplantilla = $rs_plantilla->plantillahtml;

						    		$file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");

									fwrite($file,$laplantilla);
									fclose($file);

								    $datos_email = array(
						                    'estudiante_id'   => $dni,
						                    'email'           => $email,
						                    'name'            => $nombres,
						                    'from_nombre'     => $from_nombre,
				                    		'from_email'      => $from_email,
						                    'flujo_ejecucion' => $flujo_ejecucion,
						                    'asunto'          => $asunto,
						                    'html_id'         => $id_plantilla,
						                    'lista'           => $id_lista
						                );

				                    $data = array(
				                        'detail'    => "Mensaje enviado",
				                        'html'      => $msg_text,
				                        'email'     => $email,
				                        'id'        => $dni,
				                        'nombre'    => $nombres
				                    );

				                    // pasamos $data: pasamos el array a la vista
				                    Mail::send('email.'.$id_plantilla, $data, function ($mensaje) use ($datos_email){
					                    $mensaje->from($datos_email['from_email'],$datos_email['from_nombre'])
					                    	->to($datos_email['email'], $datos_email['name'])
					                    	->subject($datos_email["asunto"]);
				                    });

				                    DB::table('historia_email')->where('id',$id)->update([
						                	'fecha_envio'	=>	Carbon::now()
						                ]);

				                }
				                
				                // ENVIO PARA AUTORIZACION

				                if($flujo_ejecucion == "LEY-27419"){

				                    $id_plantilla = $value->plantillaemail_id;
						    		// RECUPERO LA PLANTILLA
						    		$rs_plantilla = Plantillaemail::where('id',$id_plantilla)->first();

								    $flujo_ejecucion = $rs_plantilla->flujo_ejecucion;
								    $laplantilla = $rs_plantilla->plantillahtml;

						    		$file=fopen(resource_path().'/views/email/'.$id_plantilla.'.blade.php','w') or die ("error creando fichero!");

									fwrite($file,$laplantilla);
									fclose($file);

								    $datos_email = array(
						                    'email' 		=> $email,
						                    'name'  		=> $nombres,
						                    'from_nombre'   => $from_nombre,
				                    		'from_email'    => $from_email,
						                    'asunto'    	=> $asunto
						            );

								    $xurl = url('')."/au/enc/";
								    
								    //$id_estudiantes = Crypt::encryptString($dni);

								    $code = "-{$dni}";
							        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
							        $length = 74;
							        $code = substr(str_shuffle(str_repeat($pool, 5)), 0, $length-strlen($code))."{$code}-".substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
							        $code = base64_encode($code);
							        $dni_crypt = urlencode($code);

							        $id_estudiantes = $dni_crypt;

								    $si = $xurl.$id_estudiantes."/1/".$id_plantilla;
								    $no = $xurl.$id_estudiantes."/0/".$id_plantilla;
								    

				                    $data = array(
				                    	'si'      => $si,
				                    	'no'      => $no,
				                        'nombre'  => $nombres,
				                        'email'   => $email,

				                        //'detail'    => "Mensaje enviado",
				                        //'html'      => $msg_text,
				                        //'id'        => $dni,
				                    );

				                    // pasamos $data: pasamos el array a la vista
				                    Mail::send('email.'.$id_plantilla, $data, function ($mensaje) use ($datos_email){
				                    	//'from' => ['address' => 'example@example.com', 'name' => 'App Name'],
					                    $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
					                    		->to($datos_email['email'], $datos_email['name'])
					                    		//->from('inscrip@enc.edu.pe', 'CGR')
					                    		->subject($datos_email["asunto"]);
				                    });

				                    DB::table('historia_email')->where('id',$id)->update([
						                	'fecha_envio'	=>	Carbon::now()
						                ]);

				                }

				                if($flujo_ejecucion == "NEWSLETTER"){

				                    $data = array(
				                        'detail'    => "Mensaje enviado",
				                        'html'      => $msg_text,
				                        'email'     => $email,
				                        'id'        => $dni, 
				                        'nombre'    => $nombres
				                    );

				                    // pasamos $data: pasamos el array a la vista
				                    Mail::send('email.envio_newsletter', $data, function ($mensaje) use ($datos_email){
					                    $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
					                    ->to($datos_email['email'], $datos_email['name'])
					                    ->subject($datos_email["asunto"]);
				                    });

				                }

				                // INVITACION
				                if($flujo_ejecucion == "INVITACION"){

				                	// EXTRAER USUARIO Y PASS
				                	$cant_usuario = Usuario::where('name',$dni)
				                		->where('estado',1)
				                		->select('name','password')->count();
									
									if($cant_usuario == 0){
										// si no tiene usuario y contraseña se le crea:
										$u = new Usuario();
										$u->name = $dni;
										$u->password = $this->generatePassword(8);
										$u->save();
									}

				                	if($cant_usuario > 0){

				                		$usuario = Usuario::where('name',$dni)
				                		->where('estado',1)
				                		->select('name','password')
				                		->orderBy('id','DESC')
				                		->first();
					                	
					                	$usu = $usuario->name;
					                	$pass = $usuario->password;

					                    $data = array(
					                        'detail'    => "Mensaje enviado",
					                        'html'      => $msg_text,
					                        'email'     => $email,
					                        'id'        => $dni, 
					                        'nombres'   => $nombres,
					                        'usuario'	=> $usu,
						                    'pass'		=> $pass
					                    );

					                    Mail::send("email.".$msg_text, $data, function ($mensaje) use ($datos_email){
						                    $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
						                    ->to($datos_email['email'], $datos_email['name'])
						                    ->subject($datos_email["asunto"]);
					                    });

					                    DB::table('historia_email')->where('id',$id)->update([
						                	'fecha_envio'	=>	Carbon::now()
						                ]);

				                	}
									else{

										// si no tiene usuario y contraseña se le crea:
										$u = new Usuario();
										$u->name = $dni;
										$u->password = $this->generatePassword(8);
										$u->save();

										$usuario = Usuario::where('name',$dni)
				                		->where('estado',1)
				                		->select('name','password')
				                		->orderBy('id','DESC')
				                		->first();
					                	
					                	$usu = $usuario->name;
					                	$pass = $usuario->password;

					                    $data = array(
					                        'detail'    => "Mensaje enviado",
					                        'html'      => $msg_text,
					                        'email'     => $email,
					                        'id'        => $dni, 
					                        'nombres'   => $nombres,
					                        'usuario'	=> $usu,
						                    'pass'		=> $pass
					                    );

					                    Mail::send("email.".$msg_text, $data, function ($mensaje) use ($datos_email){
						                    $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
						                    ->to($datos_email['email'], $datos_email['name'])
						                    ->subject($datos_email["asunto"]);
					                    });


				                		DB::table('historia_email')->where('id',$id)->update([
						                	'fecha_envio'	=>	Carbon::now()
						                ]);


				                	}
				                }

				                //CONFIRMACION
				                if($flujo_ejecucion == "CONFIRMACION" OR $flujo_ejecucion == "RECORDATORIO"){

									// Extrae información del evento
									$eventos = DB::table('eventos')
							                    ->select('id','fechai_evento','fechaf_evento')
							                    ->where('id',$id_lista)
							                    ->count();
							        
							        if($eventos==0){
							        	DB::table('historia_email')->where('id',$id)->update([
										                	'fecha_envio'	=>	'2010-01-01'
										                ]);

							        	return "No existe evento para el envío del mailing. ID historia_email: $id - EVENTO: $id_lista - DNI: $dni";
							        }

			                		$eventos = DB::table('eventos')
							                    ->select('id','fechai_evento','fechaf_evento','nombre_evento')
							                    ->where('id',$id_lista)
							                    ->first();

						            $fechai_evento = Carbon::parse($eventos->fechai_evento);
						            $fechaf_evento = Carbon::parse($eventos->fechaf_evento);
									$evento = $eventos->nombre_evento;

									// Buscar el evento con las actividades elegidas correspondientes

			                		//select * FROM actividades as f, actividades_estudiantes as p where (f.id = p.actividad_id) and p.estudiantes_id= '01000001'
			                		
			                		$actividades = DB::table('actividades as a')
			                						//->select('a.id','a.hora_inicio')
			                						->join('actividades_estudiantes as de', 'a.id','=','de.actividad_id')
													->where('a.eventos_id',$id_lista)
			                						->where('estudiantes_id', $dni)
			                						->orderBy('fecha_desde')
							                        ->orderBy('hora_inicio')
							                        ->orderBy('titulo')
			                						->get();

			                		$rs_data=array();
			                		$fecha_desde2='';
    								$i=-1;


							        if(count($actividades)>0){
							            foreach($actividades as $j=>$actividad){
							                $hora_inicio=$actividad->hora_inicio;
							                $fecha_desde=$actividad->fecha_desde;
							                if($fecha_desde!==$fecha_desde2){$rs_data[++$i]=array("fecha_desde"=>$fecha_desde,"horas"=>array());$hora_inicio2='';$i2=-1;}
							                //if($hora_inicio!==$hora_inicio2)$rs_data[$i]["horas"][++$i2]=array("hora_inicio"=>$hora_inicio,"actividades"=>array());
							                $fila=array(
							                    "titulo"    	=>$actividad->titulo,
							                    "subtitulo" 	=>$actividad->subtitulo,
							                    "hora_inicio"   =>$actividad->hora_inicio
							                    /*otras columnas*/
							                );
							                //$rs_data[$i]["horas"][$i2]["actividades"][]=$fila;
							                $rs_data[$i]["horas"][]=$fila;
							                //$hora_inicio2=$hora_inicio;
							                $fecha_desde2=$fecha_desde;
							            }
							        }

			                		

									// Cantidad de días de las Actividades

							        $cant_dias = ($fechaf_evento->diffInDays($fechai_evento))+1;

									$rs_fecha = DB::table('eventos')
												->select('id','nombre_evento','hora','descripcion',DB::raw('DATE_FORMAT(fechai_evento, "%d de %M de %Y") as fecha_inicio' ), DB::raw('DATE_FORMAT(fechaf_evento, "%d de %M de %Y") as fecha_fin'),'fechai_evento')
												->where('id',$id_lista)//1
												->first();
									
				                    // PDF
				                    $codigoG = $dni;
				                    $nombresG  = $nombre;
				                    $apellidosG = $nombres_apat;
				                    $apellidosG_2 = $nombres_amat;

				                    //arrar para generar PDF
				                    $data = array(
			                            'codigoG'      => $codigoG,
			                            'nombresG'     => $nombre,
			                            'apellidosG'   => $apellidosG,
			                            'apellidosG_2' => $apellidosG_2,
			                            'foros'		   => $rs_data,
			                            'fecha'		   => $rs_fecha,
			                            'cant_dias'	   => $cant_dias
			                        );

			                        //obtener gafete
						            $nrs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$id_lista)
						            		->where('gafete',1)->count();


						            $gafete_html = "";

						            if($nrs_gafete > 0){

						            	$rs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$id_lista)
						            		->where('gafete',1)->first();
						            	$gafete_html = $rs_gafete->gafete_html;

						            }

			                        // GAFETE 
			                        $gafete=fopen(resource_path().'/views/email/gafetes/gafete_'.$id_lista.'.blade.php','w') or die ("error creando fichero!");

									$leido = fwrite($gafete,$gafete_html);
									fclose($gafete);

				                    //$pdf = PDF::loadView('evento.gafete', $data );
				                    //return PDF::loadView('evento.gafete', $data )->save('storage/gafete_caii/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');

				                    $file = 'storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf';
				                    //$file = 'storage/confirmacion/12345678.pdf';
				                    $directory = 'storage/confirmacion/'; 

				                    //Devuelve true
				                    //$exists = is_file( $file );

				                    // SOLO PARA CREAR NUEVAMENTE LOS GAFETES
				                    //if(!is_file($file)){

									if($id_lista == 269){
                                        // PDF tipo personalizado
                                        $pdf = PDF::loadView('email.gafetes.gafete_'.$id_lista.'', $data )
                                                ->setPaper([0, 0, 420, 235], 'landscape')
                                                ->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
                                    }else{
                                        // PDF tipo A4
                                        $pdf = PDF::loadView('email.gafetes.gafete_'.$id_lista.'', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
                                    }


				                    //Devuelve false
				                    /*$exists = is_file( $directory );
				                    //Devuelve true
				                    $exists = file_exists( $file );
				                    //Devuelve TRUE
				                    $exists = file_exists( $directory );*/

				                    $datos_email = array(
				                        'estudiante_id'   => $dni,
				                        'email' 	      => $email,
				                        'name'  	      => $nombre,
				                        'flujo_ejecucion' => $flujo_ejecucion,
				                        'from_nombre'     => $from_nombre,
				                    	'from_email'      => $from_email,
				                        'asunto'          => $asunto,
				                        //'html_id'       => $id_plantilla,
				                        'lista'           => $id_lista,
				                        'file'            => $file
				                    );

				                    // envio array a plantilla confirmacion
				                    $data = array(
				                        //'detail'  => "Mensaje enviado",
				                        'evento'    =>  $evento,
				                        'foro_2'    =>  '',
				                        'nombres'   => $nombres_ape,
				                        'foros'		=> $rs_data,
			                            'fecha'		=> $rs_fecha,
			                            'cant_dias'	=> $cant_dias
				                    );	                    

				                    if($nrs_gafete > 0){
				                    	// si tiene gafete
					                    Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
					                        //$mensaje->from('admin@enc.pe','Admin');
					                        $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
					                        		->to($datos_email['email'], $datos_email['name'])
					                        		->subject($datos_email["asunto"]);
					                        $mensaje->attach($datos_email['file']);
				                    	});

				                    }else{
				                    	// si no tiene gafete
				                    	Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
					                        //$mensaje->from('admin@enc.pe','Admin');
					                        $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
					                        ->to($datos_email['email'], $datos_email['name'])
					                        ->subject($datos_email["asunto"]);
				                    	});
				                    }

				                    DB::table('historia_email')->where('id',$id)->update([
					                	'fecha_envio'	=>	Carbon::now()
					                ]);
					                
					                
				                }

				                // NOINVITADO
				                if($flujo_ejecucion == "NOINVITADO"){

				                    $data = array(
					                        'detail'    => "Mensaje enviado",
					                        'html'      => $msg_text,
					                        'email'     => $email
					                    );

				                    Mail::send('email.'.$msg_text, $data, function ($mensaje) use ($datos_email){
					                    $mensaje->from($datos_email['from_email'], $datos_email['from_nombre'])
					                    		->to($datos_email['email'], $datos_email['name'])
					                    		->subject($datos_email["asunto"]);
				                    });

				                    DB::table('historia_email')->where('id',$id)->update([
					                	'fecha_envio'	=>	Carbon::now()
					                ]);

				                }

		    		} // end email

		    		if($value->tipo == "WHATS"){

		    			$paq_msn = DB::table('tb_msn')->where('id',1)->first();

		    			if($paq_msn->mensajes >= $paq_msn->cant){

		    				$data = array('detail'=>"Mensaje enviado",'email'=>'encomunicacion@enc.edu.pe','nombre'=>'Ticketing','asunto'=>'PAQUETE AGOTADO','cant'=>$paq_msn->cant);

		    				Mail::send('email.notificacion', $data, function ($mensaje) use ($data){
					                    $mensaje->to($data['email'], $data['nombre'])->subject($data["asunto"]);
				                    });

		    			}else{
							
							
			    			if($value->celular != "" && strlen($value->celular)>= 5){

				    			$celular 	= $value->celular;
					            $msg_cel  	= $value->msg_cel;// plantila whats
					            $msg_cel 	= trim($msg_cel);

					            /////////////
					            $mensaje .= "Mensajes whatsapp:<br>";

										// PREREGISTRO
			            				if($flujo_ejecucion == "PREREGISTRO" ){
											$texto_test =  $msg_cel;
											$data = array(
													'body'      => $texto_test,//msg_cel
													'celular'   => $celular,
													'pdf_url'   => ''
											);

						                	$cant_usuario = Usuario::where('name',$dni)
						                		->where('estado',1)
						                		->select('name','password')->count();

						                	if($cant_usuario > 0){
							                	
							                  $data = array(
							                        'body'      => $msg_cel,//msg_cel
							                        'celular'   => $celular,
							                        'pdf_url'   => ''

							                  );

							                  $send = $this->sendTo($data);
											  //ya no funciona el envio de whatsapp

							                  	////////////////////////

								                if($send){
								                    DB::table('historia_email')->where('id',$id)->update([
										                	'fecha_envio'	=>	Carbon::now()
										            ]);

										            DB::table('tb_msn')->where('id', $paq_msn->id)
	                                          		->increment('mensajes', 1);
										        }else{
										        	DB::table('historia_email')->where('id',$id)->update([
										                	'fecha_envio'	=>	'2010-01-01'
										                ]);
										        }

									            /*return response()->json([
													//"status" => $usuarios
												    "status" => $send
												]);*/

						                	}

						                }

			            				if($flujo_ejecucion == "INVITACION" ){

						                	// EXTRAER USUARIO Y PASS
						                	//SELECT * FROM users where name='10000001' limit 1
						                	$cant_usuario = Usuario::where('name',$dni)
						                		->where('estado',1)
						                		->select('name','password')->count();

						                	if($cant_usuario > 0){

						                		$usuario = Usuario::where('name',$dni)
						                		->where('estado',1)
						                		->select('name','password')->first();

							                	$dni      = $usuario->name;
							                	$password = $usuario->password;

										        /*$texto_test = $msg_cel."\n\nUsuario: *$dni*\n"
										                			."Contraseña: *$password*\n";*/
										        $texto_test =  "Usuario: *$dni*\n"
										                	  ."Contraseña: *$password*\n\n".$msg_cel;

								                $data = array(
								                        'body'      => $texto_test,//msg_cel
								                        'celular'   => $celular,
								                        'pdf_url'   => ''

								                );

								                $send = $this->sendTo($data);

								                ////////////////////////

								                if($send){
								                    DB::table('historia_email')->where('id',$id)->update([
										                	'fecha_envio'	=>	Carbon::now()
										                ]);

								                    DB::table('tb_msn')->where('id', $paq_msn->id)
	                                          		->increment('mensajes', 1);
										        }else{
										        	DB::table('historia_email')->where('id',$id)->update([
										                	'fecha_envio'	=>	'2010-01-01'
										                ]);
										        }

						                	}

						                }

						                //CONFIRMACION
						                if($flujo_ejecucion == "CONFIRMACION" OR $flujo_ejecucion == "RECORDATORIO"){

						                	// si dni existe en tb actividades_estudiantes -
							                // PDF
							                    $codigoG = $dni;
							                    $nombresG  = $nombre;
							                    $apellidosG = $nombres_apat;
							                    $apellidosG_2 = $nombres_amat;

							                    //arrar para generar PDF
							                    $data = array(
						                            'codigoG' => $codigoG,
						                            'nombresG' => $nombre,
						                            'apellidosG' => $apellidosG,
						                            'apellidosG_2' => $apellidosG_2
						                        );
							                    

							                    $file = 'storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf';
							                    //$file = 'storage/confirmacion/12345678.pdf';
							                    $directory = 'storage/confirmacion/'; 

							                    //Devuelve true
							                    //$exists = is_file( $file );

							                    // SOLO PARA CREAR NUEVAMENTE LOS GAFETES
							                    if(is_file($file)){
							                    	
							                    	//$pdf = PDF::loadView('evento.gafete', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');
							                    	//$pdf_url = "http://enc-ticketing.org/tktv2/public/storage/confirmacion/2-10000096.pdf";

							                    	//obtener gafete
										            $nrs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$id_lista)
										            		->where('gafete',1)->count();

										            $pdf_url = "";

							                    	// ENVIA MSG CON GAFETE
							                    	if($nrs_gafete > 0){

									                    //$pdf_url = "http://localhost/tkt_des/public/".$file;
									                    $pdf_url = "https://enc-ticketing.org/".$file;
									                    //$pdf_url = public_path()."/".$file;
									                }

								                    $data = array(
								                        'body'      => $msg_cel,
								                        'celular'   => $celular,
								                        'pdf_url'   => $pdf_url
									                  );

									                $send = $this->sendTo($data);

								                    if($send){
									                    DB::table('historia_email')->where('id',$id)->update([
											                	'fecha_envio'	=>	Carbon::now()
											                ]);

									                    DB::table('tb_msn')->where('id', $paq_msn->id)
		                                          		->increment('mensajes', 1);
											        }else{
											        	DB::table('historia_email')->where('id',$id)->update([
											                	'fecha_envio'	=>	'2010-01-01'
											                ]);
											        }

							                    }else{
							                    	return 'La URL del PDF no existe';
							                    }

							                
						                }

						                // NOINVITADO
						                if($flujo_ejecucion == "NOINVITADO"){

						                    $data = array(
							                        'body'      => $msg_cel,
							                        'celular'   => $celular,
							                        'pdf_url'   => ''
							                );

							                $send = $this->sendTo($data);

							                if($send){
							                    DB::table('historia_email')->where('id',$id)->update([
									                	'fecha_envio'	=>	Carbon::now()
									                ]);

							                    DB::table('tb_msn')->where('id', $paq_msn->id)
                                          		->increment('mensajes', 1);
									        }else{
										        DB::table('historia_email')->where('id',$id)->update([
										                	'fecha_envio'	=>	'2010-01-01'
										        ]);
										    }   
						                }

						                if($flujo_ejecucion == "BAJA_EVENTO"){

						                    $data = array(
							                        'body'      => $msg_cel,
							                        'celular'   => $celular,
							                        'pdf_url'   => ''
							                );

							                $send = $this->sendTo($data);
							                  

							                if($send){
							                    DB::table('historia_email')->where('id',$id)->update([
									                	'fecha_envio'	=>	Carbon::now()
									                ]);

							                    DB::table('tb_msn')->where('id', $paq_msn->id)
                                          		->increment('mensajes', 1);
									        }else{
									        	DB::table('historia_email')->where('id',$id)->update([
									                	'fecha_envio'	=>	'2010-01-01'
									                ]);
									        } 
						                }

			        
					        }
		    			}

		    		} // end whats

		    	}
				echo "<h1>Proceso de Envío de Correos</h1>";
				echo $error;
				echo $mensaje;
				var_dump($send);
			}else{
				echo "<h1>0 Correos Enviados</h1>";
			}

  } // function

    private function sendTo(...$usuarios) {
        
        /* $result = [];
        
        foreach ( $usuarios as $usuario ) {
            
            $telefono = $usuario['celular'];
            $body = $usuario['body'];

            if($usuario['pdf_url']!=""){

            	$pdf_url = $usuario['pdf_url'];
            	$ano = date('Y');
            	$file = 'GAFETE_CAII'.$ano.'.pdf';

	            $result[] = Whatsapp::send($telefono, $body);
            	$result[] = Whatsapp::send($telefono, $pdf_url, $file);

            }else{

	            $result[] = Whatsapp::send($telefono, $body);

            }
            
        } 
        
        return $result; */
		return false;
    }

	function generatePassword($length)
	{
		$key = "";
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		$max = strlen($pattern)-1;
		for($i = 0; $i < $length; $i++){
			$key .= substr($pattern, mt_rand(0,$max), 1);
		}
		return $key;
	}

}
