<?php
namespace App\Repositories;

use App\Campanias;
use App\HistoryEmails;
use App\Plantillaemail;
use App\Repositories\Interfaces\ICampaniaRepository;
use App\Usuario;
use App\Whatsapp;
use Illuminate\Support\Facades\Crypt;
use Mail;
use PDF;
use Carbon\Carbon;
use DB;
define("RESULT_MENSAJE","M");
define("RESULT_HOY","H");
define("RESULT_OLD","O");

class CampaniaRepository implements ICampaniaRepository
{
    function hola($mensaje){
        return "Su mensaje es {$mensaje}";
    }

    function test($historyEmail){        
        $result      = "";
        $status = 4;
        $error = '';
        echo "Test de gestion email: \n";
        $this->actualizaB($historyEmail, $result,$status, $error);

    }

    function send($historyEmail=[]){
        
    //function send($id, $default=[])

        $is_test = isset($historyEmail["id"])&&$historyEmail["id"]>0?false:true;
        $id = !$is_test?$historyEmail["id"]:0;
        $historyEmailData = false;
        if($id>0){
            $historyEmailData = HistoryEmails::find($id);
            if(!$historyEmailData)return false;
        }
        #para test gestion de cola sin envio de email
        ###return $this->test($historyEmailData);
        /*
        if(!$is_test){
            $id = $historyEmail->id;
            $historyEmail = HistoryEmails::find($id);
        }
        */

        $nombre  = $historyEmail["nombre"];
        $ape_pat = $historyEmail["ape_pat"];
        $ape_mat = $historyEmail["ape_mat"];

        $nombres  = "{$nombre} {$ape_pat} {$ape_mat}";
        $email    = trim($historyEmail["email"]);
        $flujo    = $historyEmail["flujo"];
        $msg_text = $historyEmail["msg_text"];
        $dni      = $historyEmail["dni"];
        $asunto      = $historyEmail["asunto"];
        $evento_id   = $historyEmail["evento_id"];
        $id          = $historyEmail["id"];
        $from_nombre = $historyEmail["from_nombre"];
        $from_email  = $historyEmail["from_email"];
        $tipo        = $historyEmail["tipo"];
        $mensaje     = "";
        $paq_msn     = false;
        $result      = "";

        //$xurl = "https://www.enc-ticketing.org/au/enc/";//url('').
        $xurl = "http://tkt_junto.test/au/enc/";

        //$dni_crypt = Crypt::encryptString($dni);

        $code = "-{$dni}";
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
        $length = 74;
        $code = substr(str_shuffle(str_repeat($pool, 5)), 0, $length-strlen($code))."{$code}-".substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
        $code = base64_encode($code);
        $dni_crypt = urlencode($code);

        $dni_url = $xurl.$dni_crypt."/2/1";

        $error = 0;
        try {
            if($tipo == "EMAIL"){
                
                $data = array(
                    'detail'    => "Mensaje enviado",
                    'html'      => $msg_text,
                    'email'     => $email,
                    'id'        => $dni,
                    'nombres'   => $nombres,
                    'dni'       => $dni,
                    'dni_url'   => $dni_url,
                );

                if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
                    sleep(1); //use usleep(500000) for half a second or less
                }
                if($historyEmail["plantilla_id"]==15)$flujo="LEY-27419";

                if($flujo=="MAILING"){
                    
                    $plantilla_id = $historyEmail["plantilla_id"];
                    $plantilla = Plantillaemail::find($plantilla_id);
                    $html = $plantilla->plantillahtml;
                    $file=fopen(resource_path().'/views/email/'.$plantilla_id.'.blade.php','w') or die ("error creando fichero!");
                    fwrite($file,$html);
                    fclose($file);
                    
                    Mail::send("email.{$plantilla_id}", $data, function ($mensaje) use ($email, $nombres, $asunto, $from_email, $from_nombre ){
                        $mensaje->from($from_email, $from_nombre);
                        $mensaje->to($email, $nombres)->subject($asunto);
                    });

                    echo "Enviado a: {$email} \r\n";
                    $result=RESULT_HOY.RESULT_MENSAJE;
                }
                // ENVIO PARA AUTORIZACION
                if($flujo == "LEY-27419"){
                    $plantilla_id = $historyEmail["plantilla_id"];
                    // RECUPERO LA PLANTILLA
                    $plantilla = Plantillaemail::find($plantilla_id);
                    $html = $plantilla->plantillahtml;
                    $file=fopen(resource_path().'/views/email/'.$plantilla_id.'.blade.php','w') or die ("error creando fichero!");
                    fwrite($file,$html);
                    fclose($file);

                    $xurl = url('')."/au/enc/";
                    $id_estudiantes = Crypt::encryptString($dni);
                    $si = $xurl.$id_estudiantes."/1/".$plantilla_id;
                    $no = $xurl.$id_estudiantes."/0/".$plantilla_id;

                    //$encrypted = Crypt::encryptString('Hello world.');
                    //$decrypted = Crypt::decryptString($encrypted);

                    $data = array(
                        'si'      => $si,
                        'no'      => $no,
                        'nombre'  => $nombres,
                        'email'   => $email,
                        //'detail'    => "Mensaje enviado",
                        //'html'      => $msg_text,
                        //'id'        => $dni,
                        'id'        => $dni,
                        'nombres'   => $nombres,
                        'dni'       => $dni,
                        'dni_url'   => $dni_url,
                    );

                    // pasamos $data: pasamos el array a la vista
                    Mail::send("email.{$plantilla_id}", $data, function ($mensaje) use ($email, $nombres, $asunto, $from_email, $from_nombre ){
                        $mensaje->from($from_email, $from_nombre);
                        $mensaje->to($email, $nombres)->subject($asunto);
                    });
                    $result=RESULT_HOY.RESULT_MENSAJE;

                }
                if($flujo=="NEWSLETTER"){
                    Mail::send("email.envio_newsletter", $data, function ($mensaje) use ($email, $nombres, $asunto, $from_email, $from_nombre ){
                        $mensaje->from($from_email, $from_nombre);
                        $mensaje->to($email, $nombres)->subject($asunto);
                    });
                    $result=RESULT_MENSAJE;
                }
                if($flujo=="INVITACION"){
                    $usuarios_count = Usuario::where('name',$dni)->where('estado',1)->select('name','password')->count();
                    if($usuarios_count>0){
                        $usuario = Usuario::where('name',$dni)->where('estado',1)->select('name','password')->orderBy('id','DESC')->first();
                        $data2 = $data;
                        $data2['usuario']=$usuario->name;;
                        $data2['pass']=$usuario->password;
                        Mail::send("email.{$msg_text}", $data, function ($mensaje) use ($email, $nombres, $asunto, $from_email, $from_nombre ){
                            $mensaje->from($from_email, $from_nombre);
                            $mensaje->to($email, $nombres)->subject($asunto);
                        });
                        $result=RESULT_HOY.RESULT_MENSAJE;
                    }else{
                        $result=RESULT_OLD;
                    }
                }
                if($flujo == "CONFIRMACION" OR $flujo == "RECORDATORIO"){
                    $actividades = DB::table('actividades as a')
                        //->select('a.id','a.hora_inicio')
                        ->join('actividades_estudiantes as de', 'a.id','=','de.actividad_id')
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
                                "titulo"    =>$actividad->titulo,
                                "subtitulo" =>$actividad->subtitulo,
                                "hora_inicio"    =>  $actividad->hora_inicio
                                /*otras columnas*/
                            );
                            //$rs_data[$i]["horas"][$i2]["actividades"][]=$fila;
                            $rs_data[$i]["horas"][]=$fila;
                            //$hora_inicio2=$hora_inicio;
                            $fecha_desde2=$fecha_desde;
                        }
                    }
                    $eventos_count = DB::table('eventos')->select('id')->where('id',$evento_id)->count();
                    if($eventos_count==0){
                        $result=RESULT_OLD;
                        echo "\r\nNo existe evento para el envío del mailing. ID historia_email: $id - EVENTO: $evento_id - DNI: $dni";
                        return;
                    }
                    $eventos = DB::table('eventos')
                        ->select('id','fechai_evento','fechaf_evento')
                        ->where('id',$evento_id)
                        ->first();

                    $fechai_evento = Carbon::parse($eventos->fechai_evento);
                    $fechaf_evento = Carbon::parse($eventos->fechaf_evento);

                    $cant_dias = ($fechaf_evento->diffInDays($fechai_evento))+1;

                    $rs_fecha = DB::table('eventos')
                        ->select('id','nombre_evento',DB::raw('DATE_FORMAT(fechai_evento, "%d de %M de %Y") as fecha_inicio' ), DB::raw('DATE_FORMAT(fechaf_evento, "%d de %M de %Y") as fecha_fin'),'fechai_evento')
                        ->where('id',$evento_id)//1
                        ->first();

                    // PDF
                    $codigoG = $dni;
                    $nombresG  = $nombre;
                    $apellidosG = $ape_pat;
                    $apellidosG_2 = $ape_mat;

                    //arrar para generar PDF
                    $data = array(
                        'codigoG' => $dni,
                        'nombresG' => $nombre,
                        'apellidosG' => $ape_pat,
                        'apellidosG_2' => $ape_mat,
                        'foros'		=> $rs_data,
                        'fecha'		=> $rs_fecha,
                        'cant_dias'		=> $cant_dias
                    );

                    //obtener gafete
                    $nrs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$evento_id)->where('gafete',1)->count();
                    $gafete_html = "";
                    if($nrs_gafete > 0){
                        $rs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$evento_id)->where('gafete',1)->first();
                        $gafete_html = $rs_gafete->gafete_html;
                    }

                    // GAFETE
                    $gafete=fopen(resource_path().'/views/email/gafetes/gafete_'.$evento_id.'.blade.php','w') or die ("error creando fichero!");
                    $leido = fwrite($gafete,$gafete_html);
                    fclose($gafete);

                    //$pdf = PDF::loadView('evento.gafete', $data );
                    //return PDF::loadView('evento.gafete', $data )->save('storage/gafete_caii/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');

                    $file = 'storage/confirmacion/'.$evento_id.'-'.$dni.'.pdf';
                    //$file = 'storage/confirmacion/12345678.pdf';
                    $directory = 'storage/confirmacion/';

                    //Devuelve true
                    //$exists = is_file( $file );

                    // SOLO PARA CREAR NUEVAMENTE LOS GAFETES
                    //if(!is_file($file)){
                    $pdf = PDF::loadView('email.gafetes.gafete_'.$evento_id.'', $data )->save('storage/confirmacion/'.$evento_id.'-'.$dni.'.pdf');
                    /*$pdf = PDF::loadView('evento.gafete', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');*/
                    //}

                    $datos_email = array(
                        'estudiante_id' => $dni,
                        'email' 	=> $email,
                        'name'  	=> $nombre,
                        'flujo_ejecucion' => $flujo,
                        'asunto'    => $asunto,
                        //'html_id'   => $id_plantilla,
                        'lista'     => $evento_id,
                        'file'      => $file
                    );
                    $data = array(
                        //'detail'    => "Mensaje enviado",
                        'foro'      =>  '',
                        'foro_2'    =>  '',
                        'nombres'   => $nombres,
                        'foros'		=> $rs_data,
                        'fecha'		=> $rs_fecha,
                        'cant_dias'	=> $cant_dias
                    );
                    if($nrs_gafete > 0){
                        // si tiene gafete
                        Mail::send("email.{$msg_text}", $data, function ($mensaje) use ($email, $nombres, $asunto, $file, $from_email, $from_nombre ){
                            $mensaje->from($from_email, $from_nombre);
                            $mensaje->to($email, $nombres)->subject($asunto);
                            $mensaje->attach($file);
                        });
                    }else{
                        // si no tiene gafete
                        Mail::send("email.{$msg_text}", $data, function ($mensaje) use ($email, $nombres, $asunto, $from_email, $from_nombre ){
                            $mensaje->from($from_email, $from_nombre);
                            $mensaje->to($email, $nombres)->subject($asunto);
                        });
                    }
                    $result=RESULT_HOY.RESULT_MENSAJE;
                }
                // NOINVITADO
                if($flujo == "NOINVITADO"){
                    $data = array(
                        'detail'    => "Mensaje enviado",
                        'html'      => $msg_text,
                        'email'     => $email
                    );
                    Mail::send("email.{$msg_text}", $data, function ($mensaje) use ($email, $nombres, $asunto, $from_email, $from_nombre ){
                        $mensaje->from($from_email, $from_nombre);
                        $mensaje->to($email, $nombres)->subject($asunto);
                    });
                    $result=RESULT_HOY.RESULT_MENSAJE;
                }
            }
            if($tipo == "WHATS"){
                $paq_msn = DB::table('tb_msn')->where('id',1)->first();
                if($paq_msn->mensajes >= $paq_msn->cant){
                    $data = array('detail'=>"Mensaje enviado",'email'=>'encomunicacion@enc.edu.pe','nombre'=>'Ticketing','asunto'=>'PAQUETE AGOTADO','cant'=>$paq_msn->cant);
                    Mail::send('email.notificacion', $data, function ($mensaje) use ($data, $from_nombre, $from_email){
                        $mensaje->from($from_email, $from_nombre);
                        $mensaje->to($data['email'], $data['nombre'])->subject($data["asunto"]);
                    });
                    $result=RESULT_MENSAJE;
                }else{
                    $celular = $historyEmail["cel_nro"];
                    if($celular != "" && strlen($celular)>= 5){
                        $msg_cel  	= $historyEmail["msg_cel"];// plantila whats
                        $msg_cel 	= trim($msg_cel);
                        /////////////
                        $mensaje .= "Mensajes whatsapp:<br>";
                        if($flujo == "PREREGISTRO" ){
                            $usuarios_count = Usuario::where('name',$dni)->where('estado',1)->select('name','password')->count();
                            if($usuarios_count>0){
                                $data = array(
                                    'body'      => $msg_cel,//msg_cel
                                    'celular'   => $celular,
                                    'pdf_url'   => ''
                                );
                                $send = $this->sendTo($data);
                            }
                            if($send){
                                $result=RESULT_HOY.RESULT_MENSAJE;
                            }else{
                                $result=RESULT_OLD.RESULT_MENSAJE;
                            }
                        }
                        // PREREGISTRO
                        if($flujo == "PREREGISTRO" ){
                            $texto_test =  $msg_cel;
                            $data = array(
                                'body'      => $texto_test,//msg_cel
                                'celular'   => $celular,
                                'pdf_url'   => ''
                            );
                            $send = $this->sendTo($data);
                            if($send){
                                $result=RESULT_HOY.RESULT_MENSAJE;
                            }else{
                                $result=RESULT_OLD.RESULT_MENSAJE;
                            }
                        }
                        if($flujo == "INVITACION" ){
                            $usuarios_count = Usuario::where('name',$dni)->where('estado',1)->select('name','password')->count();
                            if($usuarios_count>0){
                                $usuario = Usuario::where('name',$dni)->where('estado',1)->select('name','password')->first();
                                /*$texto_test = $msg_cel."\n\nUsuario: *$dni*\n"
                                                    ."Contraseña: *$password*\n";*/
                                $texto_test =  "Usuario: *{$usuario->name}*\n"
                                    ."Contraseña: *{$usuario->password}*\n\n".$msg_cel;
                                $data = array(
                                    'body'      => $texto_test,//msg_cel
                                    'celular'   => $celular,
                                    'pdf_url'   => ''
                                );
                                $send = $this->sendTo($data);
                                if($send){
                                    $result=RESULT_HOY.RESULT_MENSAJE;
                                }else{
                                    $result=RESULT_OLD.RESULT_MENSAJE;
                                }
                            }
                        }
                        if($flujo == "CONFIRMACION" OR $flujo == "RECORDATORIO"){
                            $data = array(
                                'codigoG' => $dni,
                                'nombresG' => $nombre,
                                'apellidosG' => $ape_pat,
                                'apellidosG_2' => $ape_mat
                            );
                            $file = 'storage/confirmacion/'.$evento_id.'-'.$dni.'.pdf';
                            //$file = 'storage/confirmacion/12345678.pdf';
                            $directory = 'storage/confirmacion/';
                            if(is_file($file)){
                                //obtener gafete
                                $nrs_gafete = DB::table('eventos')->select('gafete_html')->where('id',$evento_id)->where('gafete',1)->count();
                                $pdf_url = "";
                                // ENVIA MSG CON GAFETE
                                if($nrs_gafete > 0){
                                    //$pdf_url = "http://localhost/tkt_des/public/".$file;
                                    $pdf_url = "https://enc-ticketing.org/demo/public/".$file;
                                    //$pdf_url = public_path()."/".$file;
                                }
                                $data = array(
                                    'body'      => $msg_cel,
                                    'celular'   => $celular,
                                    'pdf_url'   => $pdf_url
                                );
                                $send = $this->sendTo($data);
                                if($send){
                                    $result=RESULT_HOY.RESULT_MENSAJE;
                                }else{
                                    $result=RESULT_OLD.RESULT_MENSAJE;
                                }
                            }else{
                                echo '\r\nLa URL del PDF no existe';
                            }
                        }
                        // NOINVITADO
                        if($flujo == "NOINVITADO"){
                            $data = array(
                                'body'      => $msg_cel,
                                'celular'   => $celular,
                                'pdf_url'   => ''
                            );
                            $send = $this->sendTo($data);
                            if($send){
                                $result=RESULT_HOY.RESULT_MENSAJE;
                            }else{
                                $result=RESULT_OLD.RESULT_MENSAJE;
                            }
                        }
                        if($flujo == "BAJA_EVENTO"){
                            $data = array(
                                'body'      => $msg_cel,
                                'celular'   => $celular,
                                'pdf_url'   => ''
                            );
                            $send = $this->sendTo($data);
                            if($send){
                                $result=RESULT_HOY.RESULT_MENSAJE;
                            }else{
                                $result=RESULT_OLD.RESULT_MENSAJE;
                            }
                        }
                    }
                }
                if(strstr($result,RESULT_HOY))DB::table('tb_msn')->where('id', $paq_msn->id)->increment('mensajes', 1);
            }
            //FINN WHATS
        }catch(\Swift_TransportException $transportExp){
            $error = 2;//envio
        }catch (\Exception $e) {
            $error = 3; //plantilla
        }
        //$status = strstr($result,RESULT_MENSAJE)&&!Mail::failures()?4:2;
        $status = strstr($result,RESULT_MENSAJE)&&!Mail::failures()?4:$error;
        if($is_test)return true;
        return $historyEmailData ? $this->actualizaB($historyEmailData, $result,$status, $error):true;
    }

    function actualiza($hm,$result,$status, $error){
        $id = $hm["id"];
        $historyEmail = HistoryEmails::find($id);
        return $this->actualizaB($historyEmail,$result,$status, $error);

    }
    function actualizaB($historyEmail,$result,$status, $error){
        #DB::beginTransaction();
        if($error>0)$status = $error;

        if(!$error){
            if(strstr($result,RESULT_HOY))$historyEmail-> fecha_envio = Carbon::now();
            if(strstr($result,RESULT_OLD))$historyEmail-> fecha_envio = '2010-01-01';
        }
        $historyEmail->status = $status;
        $historyEmail->save();
        //$this->actualizaCampania($historyEmail->campania_id);
        #DB::commit();
        return $historyEmail;
    }

    private function sendTo(...$usuarios) {
        $result = [];
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
        return $result;
    }

    /* function totalXStatus($campania_id){
        $q = HistoryEmails::select('status',DB::raw('count(*) as total'))->where("campania_id",$campania_id)->groupBy('status');
        $sum = $pendiente  = $error_email = $error_envio = $error_plantilla = $enviado = 0;
        $histories = $q->get();
        if(count($histories)>0) {
            foreach ($histories as $h) {
                if($h->status == 0)$pendiente = $h->total;
                if($h->status == 1)$error_email = $h->total;
                if($h->status == 2)$error_envio = $h->total;
                if($h->status == 32)$error_plantilla = $h->total;
                if($h->status == 4)$enviado = $h->total;
            }
        }
        $count = $pendiente + $error_email + $error_envio + $error_plantilla + $enviado;
        $rebotados = $error_email + $error_envio;
        $errores = $error_email + $error_envio + $error_plantilla;
        return compact("count", "pendiente", "error_email", "error_envio", "error_plantilla","enviado","rebotados","errores");
    } */

    function actualizaCampania($campania_id){
        if(!$campania_id||$campania_id<1)return;
        $res = $this->totalXStatus($campania_id);
        $data = [
            'total'	=>	$res["count"],
            'errores'	=>	$res["errores"],
            'enviados'	=>	$res["enviado"],
        ];
        $ok = Campanias::where('id',$campania_id)->update($data);
        return $ok==1?(object)$data:false;
    }

    function totalXStatus($campania_id){
        $q = HistoryEmails::select('status',DB::raw('count(*) as total'))->where("campania_id",$campania_id)->groupBy('status');
        $sum = $pendiente  = $error_email = $error_envio = $error_plantilla = $enviado = $programado = 0;
        $histories = $q->get();
        if(count($histories)>0) {
            foreach ($histories as $h) {
                if($h->status == -1)$programado = $h->total;
                if($h->status == 0)$pendiente = $h->total;
                if($h->status == 1)$error_email = $h->total;
                if($h->status == 2)$error_envio = $h->total;
                if($h->status == 3)$error_plantilla = $h->total;
                if($h->status == 4)$enviado = $h->total;
            }
        }
        $count = $programado + $pendiente + $error_email + $error_envio + $error_plantilla + $enviado;
        $rebotados = $error_email + $error_envio;
        $errores = $error_email + $error_envio + $error_plantilla;
        $total = $count;
        return compact("count","total", "programado","pendiente", "error_email", "error_envio", "error_plantilla","enviado","rebotados","errores");
    }

    function totalAccedio($campania_id){
        $q = HistoryEmails::select('accedio',DB::raw('count(1) as total'))->where("campania_id",'=',$campania_id)->groupBy('accedio');//
        $accedio =  $noaccedio = 0;
        $histories = $q->get();
        if(count($histories)>0) {
            foreach ($histories as $h) {
                $xtotal = $h->total;
                if($h->accedio == 'SI')$accedio+=$xtotal;else $noaccedio+=$xtotal;
            }
        }
        $total = $accedio + $noaccedio;
        return compact("total","accedio", "noaccedio");
    }

}
