<?php
use Jenssegers\Date\Date;
use Carbon\Carbon;

function setActive($routeName)
{
	return request()->routeIs($routeName) ? 'active' : '';
}

function miNombre()
{
	return "dany Navarro";
}

function hideEmail($email){
    $prefix = substr($email, 0, strrpos($email, '@'));
    $suffix = substr($email, strripos($email, '@'));
    $len  = floor(strlen($prefix)/2);

    return substr($prefix, 0, $len) . str_repeat('*', $len) . $suffix;
}

function hideCel($number){
    return substr($number, 0, 3) . '***' . substr($number,  -3);
}

function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Brave')) return 'Brave';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

    return 'Other';
}

function validar_fecha_espanol($fecha){
    $valores = explode('/', $fecha);
    if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
        return true;
    }
    return false;
}

function creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,$evento="",$flujo_ejecucion='',$mod_desde=''){
    
    $from_name  = $rs_datos->Email->nombre;
    $from_email = $rs_datos->Email->email;
    
            $flujo_ejecucion = $flujo_ejecucion?$flujo_ejecucion:'BAJA_EVENTO';
            $id_plantilla = $evento; //ID EVENTO
            $celular = $rs_estudiante->celular;
            $codigo_celular = $rs_estudiante->codigo_cel;
    
            $email = $rs_estudiante->email;
            $dni = $rs_estudiante->dni_doc;
            $nombres_ape = $rs_estudiante->nombres ." ".$rs_estudiante->ap_paterno;
            $nombres_apat = $rs_estudiante->ap_paterno;
            
            // PRESENCIAL 
            if($modalidad==1){
                $asunto = $rs_datos->{$tipo."_asunto"};
                $msg_text = $rs_datos->{$tipo};// plantila email
                $msg_cel = $rs_datos->{$tipo."_2"};//msg what
                $pantallazo = $rs_datos->{$tipo};
                /* $asunto = $rs_datos->p_baja_evento_asunto;
                $msg_text = $rs_datos->p_baja_evento;// plantila email
                $msg_cel = $rs_datos->p_baja_evento_2;//msg what
                $pantallazo = $rs_datos->p_baja_evento; */
            }else{
                // VIRTUAL
                #$tipo = $tipo."_asunto_v";
                $asunto = $rs_datos->{$tipo."_asunto_v"};
                $msg_text = $rs_datos->{$tipo}."_v";// plantila email
                $msg_cel = $rs_datos->{$tipo."_2_v"};// msg what
                $pantallazo = $rs_datos->{$tipo}."_v";
            }

            $confirm_email = $rs_datos->{$tipo."_email"};
            $confirm_msg   = $rs_datos->{$tipo."_msg"};
            
            $confirm_msg = 0;#PARA QUE NO ENVIE 

            if($mod_desde=='RECORD_NOTERMINARON'){
                $tipo="p_recordatorio";
                if($modalidad==1){
                    $asunto = $rs_datos->{$tipo."_asunto"};
                    $msg_text = $rs_datos->{$tipo};
                    $msg_cel = $rs_datos->{$tipo."_fin"};
                    $pantallazo = $rs_datos->{$tipo};
                }else{
                    // VIRTUAL
                    $asunto = $rs_datos->{$tipo."_asunto_v"};
                    $msg_text = $rs_datos->{$tipo}."_v";
                    $msg_cel = $rs_datos->{$tipo."_fin_v"};
                    $pantallazo = $rs_datos->{$tipo}."_v";
                }
                
            }
            
                if($confirm_email == 1){

                    if($email != ""){
                        DB::table('historia_email')->insert([
                            'tipo'              =>  'EMAIL',
                            'fecha'             => Carbon::now(),
                            'estudiante_id'     => $dni,
                            'plantillaemail_id' => $id_plantilla,
                            'flujo_ejecucion'   => $flujo_ejecucion,
                            'eventos_id'        => $id_plantilla,
                            'fecha_envio'       => '2000-01-01',
                            'asunto'            => $asunto,
                            'nombres'           => $nombres_ape,
                            'email'             => $email,
                            'celular'           => '',//$celular,
                            'msg_text'          => $msg_text,
                            'msg_cel'           => '',//$msg_cel,
                            'created_at'        => Carbon::now(),
                            'updated_at'        => Carbon::now(),
                            'from_nombre'       => $from_name,
                            'from_email'        => $from_email,
                            'actividades_id'    => $mod_desde,
                        ]);

                    }

                }
              
                if($confirm_msg == 1){

                    // MSG WHATS 
                    if($celular != "" && strlen($celular)>= 9){
                
                        DB::table('historia_email')->insert([
                            'tipo'              =>  'WHATS',
                            'fecha'             => Carbon::now(),
                            'estudiante_id'     => $dni,
                            'plantillaemail_id' => $id_plantilla,
                            'flujo_ejecucion'   => $flujo_ejecucion,
                            'eventos_id'        => $id_plantilla,
                            'fecha_envio'       => '2000-01-01',
                            'asunto'            => $asunto,
                            'nombres'           => $nombres_ape,
                            'email'             => '',//$email,
                            'celular'           => $codigo_celular.$celular,
                            'msg_text'          => '',//$msg_text
                            'msg_cel'           => $msg_cel,
                            'created_at'        => Carbon::now(),
                            'updated_at'        => Carbon::now(),
                            'actividades_id'    => $mod_desde,
                        ]);

                        
                    }
                }
    $respta = ['ok'=>'ok','pantallazo'=>$pantallazo];
    return $respta;
}
function sanitizeExcelString($str, $char){
    $n = strlen($char);
    if(substr($str, 0, $n)==$char)return ltrim($str, $char);
    return $str;
}
function sanitizeExcelObject(&$object, $char){
    $arr = $object->toArray();
    if(count($arr)>0){
        foreach($arr as $k=>$v)
            if(gettype($v)=='string')$object->{$k} = sanitizeExcelString($v, $char);
    }
}
?>