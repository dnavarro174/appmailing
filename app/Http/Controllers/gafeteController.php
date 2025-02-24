<?php

namespace App\Http\Controllers;
use DB;
use Jenssegers\Date\Date;
use Carbon\Carbon;

use App\Estudiante;
use PDF;

use Illuminate\Http\Request;

class gafeteController extends Controller
{
    public function generaGafete($eventos_id,$dni){
        $eventos_n = DB::table('e_plantillas_virtual')->where('eventos_id',$eventos_id)->count();
        if($eventos_n==1){
            $eventos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                            ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                            ->where('eventos.id',$eventos_id)
                            ->orderBy('eventos.id', 'desc')
                            ->first();
        }else{
            $eventos = \App\Evento::join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                            ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                            ->where('eventos.id',$eventos_id)
                            ->orderBy('eventos.id', 'desc')
                            ->first();
        }
        
        if($eventos->gafete==1) $gafete_html = $eventos->gafete_html;

        // GAFETE 
        $gafete=fopen(resource_path().'/views/email/gafetes/gafete_'.$eventos_id.'.blade.php','w') or die ("error creando fichero!");

        $leido = fwrite($gafete,$gafete_html);
        fclose($gafete);

        //$pdf = PDF::loadView('evento.gafete', $data );
        //return PDF::loadView('evento.gafete', $data )->save('storage/gafete_caii/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');

        $file = 'storage/confirmacion/'.$eventos_id.'-'.$dni.'.pdf';

        $fechai_evento = Carbon::parse($eventos->fechai_evento);
        $fechaf_evento = Carbon::parse($eventos->fechaf_evento);
        $evento = $eventos->nombre_evento;
        // Buscar el evento con las actividades elegidas correspondientes
			                		
        $actividades = DB::table('actividades as a')
            //->select('a.id','a.hora_inicio')
            ->join('actividades_estudiantes as de', 'a.id','=','de.actividad_id')
            ->where('a.eventos_id',$eventos_id)
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
            "hora_inicio"   =>$actividad->hora_inicio,
            'enlace'        => $actividad->enlace
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
            ->where('id',$eventos_id)//1
            ->first();

        
        $est = Estudiante::where('dni_doc',$dni)->first();
        
        $codigoG = $est->dni_doc;
        $nombresG  = explode(' ',$est->nombres);
        $nombresG  = $nombresG[0];
        $apellidosG = $est->ap_paterno;
        $apellidosG_2 = $est->ap_materno;

        //arrar para generar PDF
        $data = array(
        'codigoG'      => $codigoG,
        'nombresG'     => $nombresG,
        'apellidosG'   => $apellidosG,
        'apellidosG_2' => $apellidosG_2,
        'foros'		   => $rs_data,
        'fecha'		   => $rs_fecha,
        'cant_dias'	   => $cant_dias
        );
        
        $path = public_path()."/storage/confirmacion";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = "storage/confirmacion/".$eventos_id."-".$dni.".pdf";

        $pdf = PDF::loadView('email.gafetes.gafete_'.$eventos_id, $data);
        $pdf->setPaper('A4','portrait');
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $pdf->save($file);
        return $pdf->stream();
        #return $pdf->download();
        
        // PDF tipo A4
        ##return PDF::loadView('email.gafetes.gafete_'.$eventos_id.'', $data )->save('storage/confirmacion/'.$eventos_id.'-'.$dni.'.pdf');
        
    }

}
