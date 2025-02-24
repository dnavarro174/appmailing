<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreaHistoriaEmail extends Model
{
    public function __invoke($modalidad, $rs_estudiante, $rs_datos,$tipo,$evento="",$flujo_ejecucion='',$mod_desde=''){
    
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
                #$nombres_amat = $rs_estudiante->ap_materno;
                
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
                    /* $asunto = $rs_datos->p_baja_evento_asunto_v;
                    $msg_text = $rs_datos->p_baja_evento_v;// plantila email
                    $msg_cel = $rs_datos->p_baja_evento_2_v;// msg what
                    $pantallazo = $rs_datos->p_baja_evento_v; */
                }
                
                $confirm_email = $rs_datos->{$tipo."_email"};
                $confirm_msg   = $rs_datos->{$tipo."_msg"};
                #$tipo = "p_baja_evento";
                
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
}