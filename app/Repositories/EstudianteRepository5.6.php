<?php
namespace App\Repositories;
use App\Estudiante;
use App\Repositories\Interfaces\IEstudianteRepository;
use Auth;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class EstudianteRepository implements IEstudianteRepository
{
    function search($data){
        
        $s = $data["s"]??'';
        $st = $data["st"]??'';
        $reg = $data["reg"]??'';
        $all = $data["all"]??'';
        $pag = $data["pag"]??1;
        $tipo = $data["tipo"]??1;
        $maestria = $data["maestria"]??'';
        
        //Maestria - DDJJ
        $g = $data["g"]??"";
        $cod_curso = $data["cod"]??'';
        $nom_curso = $data["cur"]??'';

        $eventos_id = $data["eventos_id"];
        $page = $data["page"]??"";
        $sorted = $data["sorted"]??"";
        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('de.eventos_id',$eventos_id);
        if($tipo==4){//maestria
            $q->join('mae_maestria as mm','de.id','=','mm.detalle_id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.created_at','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id'
            ,'mm.detalle_id','mm.compago','mm.decjur','mm.ficins','mm.cv','mm.nvoucher','mm.provincia','mm.distrito','mm.link_detalle','de.fecha_conf','estudiantes.gradoprof');
        }elseif($tipo==5){//est.investigacion
            $q->join('inv_personal_details as per','de.estudiantes_id','=','per.p_passport_number')
            ->select('estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','de.created_at','de.estado','estudiantes.email','estudiantes.email_labor','de.eventos_id','estudiantes.telefono',
                'per.id_datos as detalle_id','per.p_passport_photo','per.investigation','per.p_pronom','per.id_datos');
        }elseif($tipo==6){//correos
            $q->leftJoin('tb_correosenc as c','estudiantes.dni_doc','=','c.estudiantes_id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','de.daccedio','de.created_at','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','estudiantes.email_labor','de.eventos_id','de.cambio_tipo'
            ,'c.emailenc','c.area_id','c.id as idcorreo');
        }elseif($tipo==7){//form especiales
            $q->join('e_preguntas as mm','de.id','=','mm.detalle_id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.created_at','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id'
            , 'mm.detalle_id','mm.pregunta','mm.provincia','mm.distrito','mm.correlativo as pregunta_id','de.fecha_conf','de.confirmado');
        }elseif($tipo==8 or $tipo==10){//form DJ
            $q->join('m4_ddjj as dd','de.id','=','dd.detalle_id')
            ->join('m4_cursos as cur','cur.id','=','dd.curso_id')
            ->select('dd.id as id_dj','de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id', 'dd.detalle_id','dd.cod_curso','dd.nom_curso','cur.*','dd.preg_1','dd.preg_2','dd.preg_3','dd.preg_4','dd.preg_5','dd.preg_6','estudiantes.distrito','de.created_at','dd.f_ini_curso','dd.f_fin_curso','dd.nota','dd.obs','de.confirmado','de.actividades_id','estudiantes.track');
        }elseif($tipo==9){//form Doc
            $q->join('m5_datos_personales as m5','de.id','=','m5.detalle_id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id'
            , 'm5.detalle_id','m5.preg_1','m5.preg_2','m5.preg_3','m5.preg_4','m5.preg_5','m5.preg_6','estudiantes.distrito','de.created_at');
        
        }else{//cualquiera
            $q->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','estudiantes.grupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel',
            'estudiantes.celular',
            'estudiantes.c1_tpo_landing',
            'estudiantes.c2_social',
            'de.daccedio','de.created_at','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','estudiantes.email_labor','de.eventos_id','de.cambio_tipo');
        }
        if($tipo==4 or $tipo==8){//maestria - DDJJ
            
            if($g=="2" or $g=="1")
                $q->where('de.confirmado',$g);//apto
            
            if($reg=="2" or $reg=="1")
                $q->where('de.actividades_id',$reg);//aprobo
            
        }elseif($tipo==5 && $tipo==7){

        }else{
            if($reg)
                $q->where('de.daccedio',$reg);
            if($g)
                $q->where('de.dgrupo',$g);
        }
        
        if($st)
            $q->where('de.estudiantes_tipo_id',$st);
        if($cod_curso)
            $q->where("dd.cod_curso", $cod_curso);
        if($nom_curso)
            $q->where("dd.nom_curso", $nom_curso);
        if($s){
            $q->where(function ($query) use ($s, $tipo) {
                $query->where("estudiantes.dni_doc", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.cargo", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.organizacion", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.accedio", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.email", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.email_labor", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.profesion", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.direccion", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.pais", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.region", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.celular", "LIKE", '%'.$s.'%')
                    ->orWhere(DB::raw('CONCAT(nombres," ", ap_paterno," ", ap_materno)'), 'LIKE' , '%'.$s.'%')
                    ->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$s.'%');
                if($tipo!=4){
                    $query->orWhere("estudiantes.grupo", "LIKE", '%'.$s.'%');
                }else{
                    $query->orWhere("de.dgrupo", "LIKE", '%'.$s.'%')
                    ->orWhere("estudiantes.grupo", "LIKE", '%'.$s.'%');
                }
            });
        }
        $vacio = false;
        if($tipo!=4&&!$s&&!$st&&!$reg)$vacio = true;
        if($tipo==4&&!$s&&!$g)$vacio = true;
        if($tipo==4&&($vacio||$s))
            $q->orderBy('de.created_at', 'ASC');//$sorted
        else
            $q->orderBy('de.created_at', $sorted);
        
            //$q->orderBy('id', $sorted);

        //if($tipo==7)$q->orderBy('estudiantes.id', $sorted);

        $key = 'estudiantes';
        if($tipo==4)$key = 'leads-mae';
        if($tipo==6)$key = 'correos';
        if($tipo==7)$key = 'form_especiales';
        if($tipo==8)$key = 'form_ddjj';
        if($tipo==9)$key = 'form_docentes';
        //$key = $tipo!=4 ?'estudiantes': 'leads';
        $datos = null;
        if($vacio){
            $key = "{$key}.page.{$page}";
            Cache::flush();
            $datos = Cache::rememberForever($key, function() use ($pag, $q,$all){
                return $all!=1?$q->paginate($pag):$q->get();
            });
        }else
            $datos = $all!=1?$q->paginate($pag):$q->get();
            $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
            $query = vsprintf($query, $q->getBindings());
        return $datos;
    }

    function getEvento($evento_id,$index = 0){
        if($index == 0)
            return DB::table('eventos as e')
                ->join('e_plantillas as p', 'e.id', '=', 'p.eventos_id')
                ->join('e_formularios as f', 'e.id','=','f.eventos_id')
                ->where('e.id',$evento_id)
                ->orderBy('e.id', 'desc')
                ->first();
        return DB::table('eventos')
            ->where('id',$evento_id)
            ->first();
    }



    function process($data){
        extract($data, EXTR_PREFIX_SAME,"__");
        $error = '';

        $evento = $this->getEvento($evento_id, $accion=='UPDATE'?1:0);
        if(!$evento)return ['success'=>true, "msg"=>"Ingrese a un evento","vista"=>"eventos.index","back"=>0];
        $fechai_evento = $evento->fechai_evento;
        $fechaf_evento = $evento->fechaf_evento;

        $estudiante_column = [
            'dni_doc','ap_paterno','ap_materno','nombres','fecha_nac',/*
            'grupo',*/'cargo','organizacion','profesion','direccion',
            'telefono','telefono_labor','codigo_cel','celular','email',
            'email_labor', 'sexo','created_at','updated_at','estado',
            'accedio','track', 'pais','region','tipo_documento_documento_id',
            'news','tipo_id','ip','navegador','entidad',
            'ubigeo_ubigeo_id'
        ];//grupo -->
        $audit_columns = [
            'id_estudiante', 'dni_doc', 'ap_paterno', 'ap_materno', 'nombres',
            'fecha_nac', 'grupo', 'cargo', 'organizacion', 'profesion',
            'direccion', 'telefono', 'telefono_labor', 'celular', 'email',
            'email_labor', 'sexo' , 'created_at' , 'updated_at' , 'estado',
            'accedio', 'track', 'tipo_documento_documento_id', 'ip', 'navegador',
            'entidad', 'ubigeo_ubigeo_id', 'accion','usuario'
        ];

        if($accion = 'INSERT'){
            if($existe==2)return["success"=>false,"msg"=>"El participante ya esta registrado.","vista"=>""];
            if($existe==0){
                $accedio2 = $accedio;
                $accedio = "SI";
                DB::table('estudiantes')->insert(compact('grupo',$estudiante_column));
                $id_estudiante = DB::getPdo()->lastInsertId();
                $accedio = $accedio2;
                DB::table('audi_estudiantes')->insert(compact($audit_columns));
            }else{
                DB::table('estudiantes')->where('dni_doc',$dni_doc)->update([
                    'dni_doc','ap_paterno', 'ap_materno', 'nombres', 'fecha_nac',
                    //'grupo',
                    'cargo', 'organizacion', 'profesion', 'direccion', 'telefono' ,
                    'telefono_labor', 'codigo_cel', 'celular', 'email', 'email_labor',
                    'sexo', 'created_at', 'updated_at', 'estado', 'track',
                    'pais', 'region', 'tipo_documento_documento_id', 'news', 'tipo_id',
                    //'tipo_id'=>$request->input('tipo_id'),
                    'ip', 'navegador',
                ]);
            }
            $NOW = Carbon::now();
            if(!is_null($news)){
                DB::table('newsletters')->insert([
                    'estado' => 1,
                    'estudiante_id' => $dni_doc,
                    'created_at'=>$NOW,
                    'updated_at'=>$NOW
                ]);
            }
            /* ADD TIPO */
            DB::table('estudiantes_act_detalle')->where('estudiantes_id',$dni_doc)
                ->where('eventos_id', $evento_id)
                ->where('estudiantes_tipo_id', $tipo_id)
                ->delete();

            //VALIDACION EXISTE DETALLE cuando se actualiza linea 754

            DB::table('estudiantes_act_detalle')->insert([
                'eventos_id'      => $evento_id,
                'estudiantes_id'  => $dni_doc,
                'actividades_id'  => 0,
                'estudiantes_tipo_id'=> $tipo_id,
                'confirmado'       => 0,
                'estado'           => 1,
                //'fecha_conf'       => Carbon::now(),
                'dgrupo'           => $grupo,
                'created_at'       => $NOW,
                'daccedio'         => 'SI',
                'dtrack'           => $track
            ]);

            $estudiante = Estudiante::where('dni_doc', $dni_doc)->first();
            $dni = $estudiante->dni_doc;
            $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
            $email = $estudiante->email;

            if($evento->auto_conf == 1){
                $flujo_ejecucion = 'CONFIRMACION';
                $asunto = '[CONFIRMACI脫N] '.$evento->nombre_evento;
                $id_plantilla = $evento_id; //ID EVENTO
                $plant_confirmacion = $evento->p_conf_registro;
                $plant_confirmacion_2 = $evento->p_conf_registro_2;

                $celular = $estudiante->codigo_cel.$estudiante->celular;
                $dni = $estudiante->dni_doc;
                $nom = $estudiante->nombres .' '.$estudiante->ap_paterno;
                $email = $estudiante->email;

                $msg_text = $evento->p_conf_registro;// plantila emailp_preregistro_2
                $msg_cel  = $evento->p_conf_registro_2;// plantila whats

                if($evento->confirm_email == 1){

                    if($email != ""){
                        $email = trim($email);

                        DB::table('historia_email')->insert([
                            'tipo'              =>  'EMAIL',
                            'fecha'             => $created,
                            'estudiante_id'     => $dni_doc,
                            'plantillaemail_id' => $id_plantilla,
                            'flujo_ejecucion'   => $flujo_ejecucion,
                            'eventos_id'        => $id_plantilla,
                            'fecha_envio'       => '2000-01-01',
                            'asunto'            => $asunto,
                            'nombres'           => $nom,
                            'email'             => $email,
                            'celular'           => "",//$celular,
                            'msg_text'          => $msg_text,
                            'msg_cel'           => "",//$msg_cel,
                            'created_at'        => $created,
                            'updated_at'        => $created
                        ]);

                    }

                }else{
                    // no inserta en la tb historia_email
                    $error .= "No se env铆o el <strong>email</strong> porque no esta habilitado<br>";
                }
                // MSG WHATS
                if($evento->confirm_msg == 1){
                    if($celular != "" && strlen($estudiante->celular)>= 9){
                        $celular = trim($celular);
                        DB::table('historia_email')->insert([
                            'tipo'              =>  'WHATS',
                            'fecha'             => $created,
                            'estudiante_id'     => $dni,
                            'plantillaemail_id' => $id_plantilla,
                            'flujo_ejecucion'   => $flujo_ejecucion,
                            'eventos_id'        => $id_plantilla,
                            'fecha_envio'       => '2000-01-01',
                            'asunto'            => $asunto,
                            'nombres'           => $nom,
                            'email'             => "",//$email,
                            'celular'           => $celular,
                            'msg_text'          => "",//$msg_text,
                            'msg_cel'           => $msg_cel,
                            'created_at'        => $created,
                            'updated_at'        => $created
                        ]);
                    }
                }else{
                    $error .= "No se envio el <strong>whatsapp</strong> porque no esta habilitado";
                }
            }
            Cache::flush();
            if($error){
                return ['success'=>false, "msg"=>$error,"vista"=>"","back"=>0];
            }
            return ['success'=>true, "msg"=>"Registro grabado.","vista"=>"'leads.index'","back"=>0];
        }
        if($accion=='UPDATE'){
            $estudiante = DB::table('estudiantes')->select('tipo_id','dni_doc')
                ->where('id',$id)->first();
            $dni_server = $estudiante->dni_doc;

            DB::table('estudiantes')->where('id',$id)->update(compact($estudiante_column));
            DB::table('audi_estudiantes')->insert(compact($audit_columns));

            $existe_det = DB::table('estudiantes_act_detalle')
                ->where('estudiantes_id',$dni_server)
                ->where('eventos_id', $evento_id)
                ->count();
            if($existe_det)
                $rs_update = DB::table('estudiantes_act_detalle')
                    ->where('estudiantes_id',$dni_server)
                    ->where('eventos_id', $evento_id)
                    ->update([
                        'estudiantes_id'     => $dni_doc,
                        'estudiantes_tipo_id'=> $tipo_id,
                        'estado'             => $estado,
                        'dgrupo'             => $grupo,
                        'daccedio'        => $accedio,
                        'dtrack'             => $track,
                        'created_at'         => $NOW
                    ]);
            else
                DB::table('estudiantes_act_detalle')->insert([
                    'eventos_id'      => session('eventos_id'),
                    'estudiantes_id'  => $dni_doc,
                    'actividades_id'  => 0,
                    'estudiantes_tipo_id'=> 5,
                    'confirmado'       => 0,
                    'estado'           => 1,
                    //'fecha_conf'       => Carbon::now(),
                    'dgrupo'           => $grupo,
                    'daccedio'         => $accedio,
                    'dtrack'           => $track,
                    'created_at'       => $NOW
                ]);
            $e_user = DB::table('users')->where('name',$dni_doc)->first();
            if(!$e_user){
                DB::table('users')
                    ->where('name', $dni_server)
                    ->update([
                        'name'     => $dni_doc,
                        //'password' => 'A'.$xdni.'Z'
                    ]);
            }
            Cache::flush();
            return ['success'=>true, "msg"=>"Registro actualizado.","vista"=>"","back"=>1];
        }
        return ['success'=>false, "msg"=>"Operaci贸n no permitida","vista"=>"","back"=>1];
    }
}
