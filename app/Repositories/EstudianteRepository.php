<?php
namespace App\Repositories;
use App\Estudiante,App\Emails;
use App\Repositories\Interfaces\IEstudianteRepository;
use Auth;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\Mod_ddjj, App\EstudianteTemp, App\estudiantes_act_detalle, App\Asistencia_evento;
use Illuminate\Support\Collection;

class EstudianteRepository implements IEstudianteRepository
{
    function search($data){
        
        $s = $data["s"]??'';
        $st = $data["st"]??'';
        $reg = $data["reg"]??'';
        //$mod = $data["mod"]??'';
        $all = $data["all"]??'';
        $pag = $data["pag"]??1;
        $tipo = $data["tipo"]??1;
        $maestria = $data["maestria"]??'';
        
        //Maestria - DDJJ
        $g = $data["g"]??"";
        $cod_curso = $data["cod"]??'';
        $nom_curso = $data["cur"]??'';
        $modalidad = $data["mod"]??'';
        #dd($tipo,$st,$reg,$g, $modalidad);

        $eventos_id = $data["eventos_id"];
        //$eventos_id = $data["eventos_id"]??session('eventos_id');
        $page = $data["page"]??"";
        $sorted = $data["sorted"];
        //$sorted = $data["sorted"]??"DESC";
        
        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('de.eventos_id',$eventos_id);

        if($tipo==2){//caii-Actividades
            #dd("c",$tipo);
            $q->join('actividades_estudiantes as dee','estudiantes.dni_doc','=','dee.estudiantes_id')
            ->join('actividades as act','act.id','=','dee.actividad_id')
            ->where('dee.eventos_id',$eventos_id)
            ->where('act.eventos_id',$eventos_id)
            ->select('estudiantes.id','estudiantes.dni_doc', 'estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno', 
            'estudiantes.cargo',
            'estudiantes.organizacion',
            'estudiantes.profesion',
            'estudiantes.pais',
            'estudiantes.region',
            'estudiantes.email', 'estudiantes.celular', 'de.dgrupo', 'de.daccedio', 'estudiantes.updated_at', 'de.estudiantes_tipo_id','act.titulo','act.subtitulo', 'de.modalidad_id');
        }elseif($tipo==3||$tipo==1.1||$tipo==1.2||$tipo==1.3||$tipo==1.4){//caii-Preinscritos
        
            $q->select('estudiantes.dni_doc', 'estudiantes.nombres', 'estudiantes.ap_paterno', 'estudiantes.ap_materno',
            'estudiantes.cargo',
            'estudiantes.organizacion',
            'estudiantes.profesion',
            'estudiantes.pais',
            'estudiantes.region',
             'estudiantes.email', 'estudiantes.celular','de.dgrupo', 'de.daccedio', 'estudiantes.updated_at', 'de.estudiantes_tipo_id', 'de.modalidad_id');
        }elseif($tipo==4){//maestria
            $q->join('mae_maestria as mm','de.id','=','mm.detalle_id')
              #->join('e_grado_profesional as g','estudiantes.gradoprof','=','g.id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.created_at','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','estudiantes.direccion','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.gradoprof','estudiantes.tipo_documento_documento_id','estudiantes.discapacitado','estudiantes.fecha_nac','estudiantes.sexo',
            'mm.detalle_id','mm.compago','mm.decjur','mm.ficins','mm.cv','mm.nvoucher','mm.provincia','mm.distrito','mm.link_detalle','de.fecha_conf','mm.ubigeo','mm.si_cgr','mm.codigo_cgr','de.confirmado','de.actividades_id',);
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
            ->select('dd.id as id_dj','de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.grupo','estudiantes.pais','estudiantes.region','estudiantes.provincia','estudiantes.distrito','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','estudiantes.entidad','estudiantes.gradoprof','dd.moda_contractual','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','estudiantes.direccion','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id', 'dd.detalle_id','dd.cod_curso','dd.nom_curso','cur.*','dd.preg_1','dd.preg_2','dd.preg_3','dd.preg_4','dd.preg_5','dd.preg_6','de.created_at','dd.f_ini_curso','dd.f_fin_curso','dd.nota','dd.obs','de.confirmado','de.actividades_id','estudiantes.track');
        }elseif($tipo==9){//form Doc
            $q->join('m5_datos_personales as m5','de.id','=','m5.detalle_id')
            ->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id'
            , 'm5.detalle_id','m5.preg_1','m5.preg_2','m5.preg_3','m5.preg_4','m5.preg_5','m5.preg_6','estudiantes.distrito','de.created_at');
        
        }elseif($tipo==="0"&&$tipo!="E"){//asistencia CAII
                #dd("a",$tipo);
                $q->join('asistencia_eventos as asi','estudiantes.dni_doc','=','asi.estudiantes_id')
                #->join('estudiantes asi e','estudiantes.dni_doc','=','asi.estudiantes_id')
                    ->select('estudiantes.dni_doc','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.nombres', 'asi.fecha','asi.hora', 'estudiantes.email', 'estudiantes.organizacion','asi.actividad_id','estudiantes.pais','estudiantes.region','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','de.dgrupo as grupo')
                    ->where('asi.evento_id',session('eventos_id'))
                    ->where('de.eventos_id',session('eventos_id'))
                    ->whereNull('asi.actividad_id')
                    ->groupBy('estudiantes.dni_doc', 'asi.fecha')
                    ;
                    
        }elseif($tipo=="0.2"){ //asistencia CAII
                $q->join('asistencia_eventos as asi','estudiantes.dni_doc','=','asi.estudiantes_id')
                    ->join('actividades as act', 'asi.actividad_id','=','act.id')
                    ->select('estudiantes.dni_doc','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.nombres', 'asi.fecha','asi.hora', 'estudiantes.email', 'estudiantes.organizacion','asi.actividad_id as titulo','estudiantes.pais','estudiantes.region','estudiantes.cargo','estudiantes.profesion','estudiantes.celular','de.dgrupo as grupo','act.titulo','act.subtitulo'
                    )
                    ->where('asi.evento_id',session('eventos_id'))
                    ->where('de.eventos_id',session('eventos_id'));
        }else{//cualquiera
            #dd("Paso 1",$tipo);
            $q->select('de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo as grupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel',
            'estudiantes.celular',
            'estudiantes.c1_tpo_landing',
            'estudiantes.c2_social',
            'de.daccedio','de.created_at','de.dtrack','de.estudiantes_tipo_id','de.modalidad_id','de.estado','estudiantes.email','estudiantes.email_labor','de.eventos_id','de.cambio_tipo');
        }
        
        if($tipo==4 or $tipo==8 or $tipo==10){//maestria - DDJJ
            
            if($g=="2" or $g=="1")
                $q->where('de.confirmado',$g);//apto - accedio Beca
            
            if($reg=="2" or $reg=="1")
                $q->where('de.actividades_id',$reg);//aprobo
            
        }elseif($tipo==5 && $tipo==7){

        }else{
            if($reg)
                $q->where('de.daccedio',$reg);
            if($g)
                $q->where('de.dgrupo',$g);
        }
        #reporte caii:
        if($tipo=="1.2"&&$st==1)$q->where('de.dtrack','SI');
        if($tipo=="3")$q->where('de.daccedio','SI');
        #if($st)$q->where('de.estudiantes_tipo_id',$st);
        
        if(($st&&$tipo=="0"&&$tipo!="E") or ($st&&$tipo=="0.2"&&$tipo!="E")){}elseif($st){$q->where('de.estudiantes_tipo_id',$st);}else{}
        if($cod_curso)
            $q->where("cur.cod_curso", $cod_curso);
        if($nom_curso)
            $q->where("cur.id", $nom_curso);
        if($modalidad&&$tipo==8)
            $q->where("cur.modalidad", $modalidad);
        if($modalidad&&$tipo=="E"){
            #dd('paso');
            /*$q->join('estudiantes_tipo as ttp','ttp.tipo','=','de.estudiantes_tipo_id')
                    ->where('ttp.tipo',$modalidad);*/
            $q->where('de.modalidad_id',$modalidad);
        }
            
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
        elseif($tipo==="0"||$tipo==="0.2")
            $q->orderBy('estudiantes.dni_doc', 'ASC');#orderBy('estudiantes.dni_doc','asc')-
        else
            $q->orderBy('de.created_at', $sorted);
        #para report
        //if($tipo==4){$q->orderBy('mm.detalle_id','ASC');}
            
        //if($tipo==7)$q->orderBy('estudiantes.id', $sorted);

        $key = 'estudiantes';
        if($tipo==0||$tipo=="0.2")$key = 'asistencia';
        if($tipo==4)$key = 'leads-mae';
        if($tipo==6)$key = 'correos';
        if($tipo==7)$key = 'form_especiales';
        if($tipo==8 or $tipo==10)$key = 'form_ddjj';
        if($tipo==9)$key = 'form_docentes';
        //$key = $tipo!=4 ?'estudiantes': 'leads';
        $datos = null;
        if($vacio){
            $key = "{$key}.page.{$page}";
            Cache::flush();
            $datos = Cache::rememberForever($key, function() use ($pag, $q,$all){
                return $all!=1?$q->paginate($pag):$q->get();
            });
        }else{
            $datos = $all!=1?$q->paginate($pag):$q->get();
        }
        $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
        $query = vsprintf($query, $q->getBindings());
        #dd($query);
        return $datos;
    }

    function estudiante_import($data,$contF,$chkE_invitacion,$request){
        $data_exc = $data??'';
        $daccedio = $chkE_invitacion==1?"NO":"SI";//1:CAII 0:EVENTO (SI/NO)
        $tpo_evento = $chkE_invitacion==1?2:5;//1:CAII 0:EVENTO (SI/NO)
        #dd($data_exc,$daccedio,$chkE_invitacion);
        //recorre el archivo excel abierto
        foreach ($data_exc as $lst) {
            $est_delete = 0;
    
            if ($contF > 0) {
            // recorre los combos seleccionados
            $estTemp = new EstudianteTemp();
            $estTemp->tipo_id = 2;
            $dniT = "";
            $nomT = "";
            $appT = "";
            $apmT = "";
            $grupoT = "";
            $fecnT = "";
            $cargT = "";
            $profT = "";
            $dirT = "";
            $telT = "";
            $celT = "";
            $mailT = "";
            $mailT_2 = "";
            $sexT = "";
            $orgT = "";
            $entT = 0;
            $eventos_idT = session('eventos_id');
            $paisT = "";
            $regionT = "";
            $orgT = "";
            $modT = "";#$modalidad
    
            $estudiantes_det = new estudiantes_act_detalle();
    
            for ($x = 1; $x <= $request["totCol"]; $x++) {
    
                if ($request["cmbOrganizar" . $x] == 1) {
                $estTemp->dni_doc = $lst[$x - 1];
                $dniT = $lst[$x - 1];
    
                $estTemp->dni_doc = trim($estTemp->dni_doc);
                $dniT = trim($dniT);
                }
    
                if ($request["cmbOrganizar" . $x] == 2) {
                $estTemp->nombres = mb_strtoupper($lst[$x - 1]);
                $nomT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 3) {
                $estTemp->ap_paterno = mb_strtoupper($lst[$x - 1]);
                $appT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 4) {
                $estTemp->ap_materno = mb_strtoupper($lst[$x - 1]);
                $apmT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 5) {
                $estTemp->grupo = mb_strtoupper($lst[$x - 1]);
                $grupoT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 6) {
                $estTemp->cargo = mb_strtoupper($lst[$x - 1]);
                $cargT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 7) {
                $estTemp->profesion = mb_strtoupper($lst[$x - 1]);
                $profT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 8) {
                $estTemp->direccion = $lst[$x - 1];
                $dirT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 9) {
                $estTemp->telefono = $lst[$x - 1];
                $telT = $lst[$x - 1];
                }
    
                if ($request["cmbOrganizar" . $x] == 10) {
    
                if ($lst[$x - 1] == "") {
                    $estTemp->codigo_cel = '';
                    $estTemp->celular = $lst[$x - 1];
                    $celT = $lst[$x - 1];
                } else {
                    $estTemp->codigo_cel = '51';
                    $estTemp->celular = trim($lst[$x - 1]);
                    $celT = trim($lst[$x - 1]);
                }
                }
    
                if ($request["cmbOrganizar" . $x] == 11) {
    
                    if ($lst[$x - 1] == "") {
                        $estTemp->email = $lst[$x - 1];
                        $mailT = $lst[$x - 1];
                    } else {
                        $estTemp->email = trim($lst[$x - 1]);
                        $mailT = trim($lst[$x - 1]);
        
                        // SE QUITA SI TIENE DOS O MAS EMAILS CON ESPACIO
                        $d_email = $estTemp->email;
                        $email_partes = explode(" ", $d_email);
                        $estTemp->email = $email_partes[0];
                        $mailT = $email_partes[0];
                        // VERIFICO SI ES VALIDO
                        $sanitized_email = filter_var($mailT, FILTER_SANITIZE_EMAIL);
                        if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                        $estTemp->email = $sanitized_email;
                        $mailT = $sanitized_email;
                        } else {
                        $estTemp->email = "";
                        $mailT = "";
                        }
                    }
                }
    
                if ($request["cmbOrganizar" . $x] == 12) {
                $estTemp->sexo = $lst[$x - 1];
                $sexT = $lst[$x - 1];
                }
    
                /*if($request["cmbOrganizar".$x]==13){
                            $entTv = $lst[$x - 1];
                            $entTv = trim($entTv);
                            $entidadTemp = DB::table('entidades')->where("entidad",$entTv)->first();
                            if($entidadTemp){
                                $entT = $entidadTemp->id;
                                $estTemp->idEntidad = $entT;
                            }
                        }
                */
                
                if ($request["cmbOrganizar" . $x] == 15) {
                $estTemp->email_labor = $lst[$x - 1];
                $mailT_2 = $lst[$x - 1];
                }
                if ($request["cmbOrganizar" . $x] == 16) {
                $estTemp->organizacion = mb_strtoupper($lst[$x - 1]);
                $orgT = $lst[$x - 1];
                }
                if ($request["cmbOrganizar" . $x] == 17) {
                $estTemp->pais = mb_strtoupper($lst[$x - 1]);
                $paisT = $lst[$x - 1];
                }
                if ($request["cmbOrganizar" . $x] == 18) {
                $estTemp->region = mb_strtoupper($lst[$x - 1]);
                $regionT = $lst[$x - 1];
                }
                if ($request["cmbOrganizar" . $x] == 21) {
                    $estTemp->modalidad = mb_strtoupper($lst[$x - 1]);
                    $modT = mb_strtoupper($lst[$x - 1]);
                    }
            }
            $modT = mb_strtoupper(trim($modT));
            $modalidad = $modT=="VIRTUAL"?2:1;
            /* valida si existe evento*/
            $si_evento = DB::table('eventos')->where('id', $eventos_idT)->count();
    
            if($si_evento == 0){
                return "error_no_evento";
            }
    
            $flagPASA = 0;
            $flagPASAdni = 1;
            $flagPASAcel = 1;
            //VALIDA FORMATO DE DNI
            /*if(preg_match('#[^0-9]#',$dniT)){
                        $flagPASAdni = 0;
                    }*/
    
            if(strlen($dniT)<4){
                $flagPASAdni = 0;
            }
    
            if(preg_match('#[^0-9]#',$celT)){
                $flagPASAcel = 0;
            }
    
            if($flagPASAdni == 1){
                $verEst = Estudiante::where("dni_doc",$dniT)->first();
    
                if(!($verEst)){
                    estudiantes_act_detalle::where("estudiantes_id",$dniT)->where('eventos_id',session('eventos_id'))->delete();
                    if($mailT!=""){
                        $verMail = Estudiante::where("email",$mailT)->first(); // validar
                        //if(!($verMail)){
                        $estTemp->repetido=0;
                        $estTemp->mensaje="<span style='color:#18e237'>Lead SAVE</span>";
                        //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                        if ($fecnT != "") {
                        if ($this->validar_fecha_espanol($fecnT)) {
                            $flagPASA = 1;
                        } else {
                            $estTemp->repetido = 1;
                            $estTemp->mensaje = "<span style='color:red'>Formato de Fecha Incorrecto, debe ser dd/mm/yyyy</span>";
                        }
                        } else {
                        $flagPASA = 1;
                        }
        
                        /*}else{
                            $estTemp->repetido=1;
                            $estTemp->mensaje="<span style='color:red'>EMAIL registrado</span>";
                        }*/
                    }else{
                        $estTemp->repetido=0;
                        $estTemp->mensaje="<span style='color:#18e237'>Lead SAVE</span>";
                        //VALIDA FORMATO DE FECHA SI NO ESTA VACIO
                        if($fecnT!=""){
                        if($this->validar_fecha_espanol($fecnT)){
                            $flagPASA = 1;
                        }else{
                            $estTemp->repetido=1;
                            $estTemp->mensaje="<span style='color:red'>Formato de Fecha Incorrecto, debe ser dd/mm/yyyy</span>";
                        }
                        } else {
                        $flagPASA = 1;
                        }
                    } // end $verEst
                } else {
                    $estTemp->repetido = 1;
                    //$estTemp->mensaje="<span style='color:red'>DNI registrado</span>";
                    $estTemp->mensaje = "<span style='color:red'>Lead SAVE, DNI existe</span>";
                    // CONDICIONAL DE ACTUALIZACION
                    $colEst1 = 0;
                    if(trim($verEst->nombres)!="" ){$colEst1++;}
                    if(trim($verEst->ap_paterno)!="" ){$colEst1++;}
                    if(trim($verEst->ap_materno)!="" ){$colEst1++;}
                    if(trim($verEst->fecha_nac)!="" ){$colEst1++;}
                    if(trim($verEst->grupo)!="" ){$colEst1++;}
                    if(trim($verEst->cargo)!="" ){$colEst1++;}
                    if(trim($verEst->profesion)!="" ){$colEst1++;}
                    if(trim($verEst->direccion)!="" ){$colEst1++;}
                    if(trim($verEst->telefono)!="" ){$colEst1++;}
                    if(trim($verEst->celular)!="" ){$colEst1++;}
                    if(trim($verEst->email)!="" ){$colEst1++;}
                    if(trim($verEst->email_labor)!="" ){$colEst1++;}
                    if(trim($verEst->sexo)!="" ){$colEst1++;}
                    if((int)$verEst->codigo_prog!=""){$colEst1++;}
                    if((int)$verEst->pais!=""){$colEst1++;}
                    if((int)$verEst->region!=""){$colEst1++;}
                    if((int)$verEst->organizacion!=""){$colEst1++;}
                    //if(trim($verEst->ubigeo_ubigeo_id)!=""){$colEst1++;}
                    
                    /*accedio y track
                    if((int)$verEst->daccedio!=""){$colEst1++;}
                    if((int)$verEst->dtrack!=""){$colEst1++;}*/
        
                    $colEst2 = 0;
                    //if($dniT != ""){$colEst2++;}
                    /*if($nomT != ""){$colEst2++;}
                    if($appT != ""){$colEst2++;}
                    if($apmT != ""){$colEst2++;}
                    if($fecnT != ""){$colEst2++;}
                    if($grupoT != ""){$colEst2++;}
                    if($cargT != ""){$colEst2++;}
                    if($profT != ""){$colEst2++;}
                    if($dirT != ""){$colEst2++;}
                    if($telT != ""){$colEst2++;}
                    if($celT != ""){$colEst2++;}
                    if($mailT != ""){$colEst2++;}
                    if($sexT != ""){$colEst2++;}
                    if($eventos_idT != ""){$colEst2++;}
                    if($mailT_2 != ""){$colEst2++;}
                    if($orgT != ""){$colEst2++;}
                    if($paisT != ""){$colEst2++;}
                    if($regionT != ""){$colEst2++;}
                    if($modT != ""){$colEst2++;}*/
        
                    //si columnas del excel existe => update
                    
                    if($nomT)$verEst->nombres = mb_strtoupper($nomT);
                    if($dniT)$verEst->dni_doc = $dniT;
                    if($appT)$verEst->ap_paterno = mb_strtoupper($appT);
                    if($apmT)$verEst->ap_materno  = mb_strtoupper($apmT);
                    if($grupoT)$verEst->grupo  = mb_strtoupper($grupoT);
                    if($fecnT)$verEst->fecha_nac = $fecnT;
                    if($cargT)$verEst->cargo = mb_strtoupper($cargT);
                    if($profT)$verEst->profesion = mb_strtoupper($profT);
                    if($dirT)$verEst->direccion = mb_strtoupper($dirT);
                    if($telT)$verEst->telefono = $telT;
                    if($celT)$verEst->celular = $celT;
                    if($mailT)$verEst->email = trim($mailT);
                    if($mailT_2)$verEst->email_labor = trim($mailT_2);
                    if($sexT)$verEst->sexo = $sexT;
                    if(!$dniT)$verEst->accedio = $daccedio;
                    if($orgT)$verEst->organizacion = mb_strtoupper($orgT);
                    if($paisT)$verEst->pais = mb_strtoupper($paisT);
                    if($regionT)$verEst->region = mb_strtoupper($regionT);
                    
                    $verEst->save(); //end save
        
                    $estTemp->repetido=0;
                    $estTemp->mensaje="<span style='color:#18e237'>Lead UPDATE</span>";
        
                    $error = '';
        
                    // VERIFICAR SI EL PARTICIPANTE YA ESTA REGISTRADO.
                    $est_det_cant = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                        ->where('eventos_id',$eventos_idT)
                        ->where('estudiantes_tipo_id', 2)
                        ->count();
        
                    $estudiantes_tipo_1 = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                        ->where('eventos_id',$eventos_idT)
                        ->where('estudiantes_tipo_id', 1)
                        ->count();
        
                    $check_stado = estudiantes_act_detalle::where('estudiantes_id',$dniT)
                        ->where('eventos_id',$eventos_idT)
                        ->where('estudiantes_tipo_id', 2)
                        ->where('daccedio', 'SI')
                        ->count();
        
                    if($est_det_cant >= 1){
                        DB::table('estudiantes_act_detalle')
                            ->where('estudiantes_id',$dniT)
                            ->where('eventos_id',$eventos_idT)
                            ->update([
                                'modalidad_id'          => $modalidad,
                            ]);
                    }else{
        
                        // SI EL ESTADO ES "NO", SE ENVIA INVITACION
                        if($check_stado == 1){
                        }else{
                            if($estudiantes_tipo_1 >= 1){
                                // PASA DE 1 A 2
                                //$est_delete = estudiantes_act_detalle::where('estudiantes_id',$dniT)->where('eventos_id',$eventos_idT)->delete();
            
                                $cambio_tipo = DB::table('estudiantes_act_detalle')
                                ->where('estudiantes_id',$dniT)
                                ->where('eventos_id',$eventos_idT)
                                ->where('estudiantes_tipo_id',1)
                                ->update([
                                    'estudiantes_tipo_id'   => 2,
                                    'modalidad_id'          => $modalidad,
                                    'cambio_tipo'           => 1,
                                    'created_at'            => Carbon::now()
                                ]);
                            }else{
            
                                // agregue
                                $detalle_estud = new estudiantes_act_detalle();
                                $detalle_estud->estudiantes_id = $dniT;
                                $detalle_estud->eventos_id = $eventos_idT;
                                $detalle_estud->actividades_id = 0 ;//$idAct;
                                $detalle_estud->estudiantes_tipo_id = $tpo_evento;
                                $detalle_estud->modalidad_id = $modalidad;
                                $detalle_estud->estado = 1;
                                $detalle_estud->confirmado = 0;
                                $detalle_estud->daccedio = $daccedio;
                                $detalle_estud->dgrupo = $grupoT;
                                $detalle_estud->dtrack = '';
                                $detalle_estud->cambio_tipo = $est_delete;
                                $detalle_estud->created_at = Carbon::now();
                                $detalle_estud->save();
                            }
                        }
                    }

                    if($chkE_invitacion == 1 AND $check_stado == 0){
        
                        $rs_datos = \App\Evento::
                        join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                        ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                        ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                        ->where('eventos.id',$eventos_idT)
                        ->orderBy('eventos.id', 'desc')
                        ->first();
        
                        if(!$rs_datos){
                        return "error_no_plantilla";
                        }

                        #IMPORT VIPS
                        $rs_estudiante = [
                            'email'     => $mailT==''?$verEst->email:$mailT,
                            'dni_doc'   => $dniT,
                            'nombres'   => mb_strtoupper($nomT) .' '.mb_strtoupper($appT).' '.mb_strtoupper($apmT),
                            'ap_paterno'=> '',
                            'ap_materno'=> '',
                            'celular'   => $celT==''?$verEst->celular:$celT,
                            'codigo_cel'=> 51,
                        ];
                        $rs_estudiante = (object) $rs_estudiante;
                        $tipo = 'p_conf_inscripcion';
                        $flujo_ejecucion = 'INVITACION';
                        $mod_desde = "F_VIP_IMPORT_UP";
    
                        $rs = creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,$eventos_idT,$flujo_ejecucion,$mod_desde);

                    } //chkE_invitacion
                
                }
            }else{
                $estTemp->repetido=1;//y tener 8 dígitos
                $estTemp->mensaje="<span style='color:red'>DNI debe ser numérico </span>";
            }
            if($flagPASA==1){
                // CREA EL NUEVO ESTUDIANTE
                $estudiante = new Estudiante();
                $estudiante->nombres = mb_strtoupper($nomT);
                $estudiante->dni_doc = $dniT;
                $estudiante->ap_paterno = mb_strtoupper($appT);
                $estudiante->ap_materno  = mb_strtoupper($apmT);
                $estudiante->grupo = mb_strtoupper($grupoT);
                $estudiante->fecha_nac = $fecnT;
                $estudiante->cargo = mb_strtoupper($cargT);
                $estudiante->profesion = mb_strtoupper($profT);
                $estudiante->direccion = mb_strtoupper($dirT);
                $estudiante->telefono = $telT;
                if($celT !== ""){$estudiante->codigo_cel = '51';}
    
                $estudiante->celular = $celT;
                $estudiante->email = trim($mailT);
                $estudiante->email_labor = trim($mailT_2);
                $estudiante->sexo = $sexT;
                $estudiante->organizacion = mb_strtoupper($orgT);
                $estudiante->pais = mb_strtoupper($paisT);
                $estudiante->region = mb_strtoupper($regionT);
                $estudiante->tipo_documento_documento_id = 1;
                $estudiante->estado  = 1;
                $estudiante->accedio = $daccedio;
                $estudiante->tipo_id = 2;
                $estudiante->save();
    
                // GUARDAMOS EN audi_estudiantes
                DB::table('audi_estudiantes')->insert([
                'id_estudiante' => $estudiante->id,
                'dni_doc' => $dniT,
                'ap_paterno' => $appT,
                'ap_materno' => $apmT,
                'nombres' => $nomT,
                'fecha_nac' => $fecnT,
                'grupo' => $grupoT,
                'cargo' => $cargT,
                'organizacion' => $orgT,
                'profesion' => $profT,
                'direccion' => $dirT,
                'telefono' => $telT,
                'celular' => $celT,
                'email' => $mailT,
                'email_labor' => $mailT_2,
                'sexo' => $sexT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'estado' => 1,
                'tipo_documento_documento_id' => 1,
                'ip' => request()->ip(),
                'navegador' => get_browser_name($_SERVER['HTTP_USER_AGENT']),
                'accion'  => "INSERT",
                'usuario' => Auth::user()->email
                ]);
    
                // end audi_estudiantes
    
                $estTemp->idAlumno = $estudiante->id;
    
                // grabar detalle estudiantes
                $detalle = new estudiantes_act_detalle();
                $detalle->estudiantes_id = $dniT;
                $detalle->eventos_id = $eventos_idT;
                $detalle->actividades_id = 0;//$idAct;
                $detalle->estudiantes_tipo_id = $tpo_evento;
                $detalle->modalidad_id = $modalidad;#DEPENDE DEL USUARIO
                $detalle->estado = 1;
                $detalle->confirmado = 0;
                //$detalle->fecha_conf = Carbon::now();
                $detalle->daccedio = $daccedio;
                $detalle->dgrupo = $grupoT;
                $detalle->dtrack = '';
                $detalle->cambio_tipo = $est_delete;
                $detalle->created_at = Carbon::now();
                $detalle->save();
    
                $error = '';
    
                // SAVE INVITACION SI = chkE_invitacion == 1
                if($chkE_invitacion == 1){
    
                    $rs_datos = \App\Evento::
                        join('e_plantillas as p', 'eventos.id', '=', 'p.eventos_id')
                        ->join('e_formularios as f', 'eventos.id','=','f.eventos_id')
                        ->join('e_plantillas_virtual as vir', 'eventos.id','=','vir.eventos_id')
                        ->where('eventos.id',$eventos_idT)
                        ->orderBy('eventos.id', 'desc')
                        ->first();
        
        
                    if(!$rs_datos){
                        return "error_no_plantilla";
                    }
        
                    #IMPORT VIPS
                    $rs_estudiante = [
                        'email'     => $mailT==''?($verEst->email ?? ''):$mailT,
                        'dni_doc'   => $dniT,
                        'nombres'   => mb_strtoupper($nomT) .' '.mb_strtoupper($appT).' '.mb_strtoupper($apmT),
                        'ap_paterno'=> '',
                        'ap_materno'=> '',
                        'celular'   => $celT==''?($verEst->celular ?? ''):$celT,
                        'codigo_cel'=> 51,
                    ];
                    $rs_estudiante = (object) $rs_estudiante;
                    $tipo = 'p_conf_inscripcion';
                    $flujo_ejecucion = 'INVITACION';
                    $mod_desde = "F_VIP_IMPORT";

                    $rs = creaHitoria_email($modalidad, $rs_estudiante, $rs_datos,$tipo,$eventos_idT,$flujo_ejecucion,$mod_desde);
                    
                }
                
            }
            // fin NEW ESTUD.
    
            $estTemp->save();
            }
            // falta poner el aumento de registros
            /* 
            DB::table('eventos')->where('id', session('eventos_id'))
                        ->increment("$columna", 1);
             */
            $contF++;
    
            Cache::flush();
        }



    }

    public function validar_fecha_espanol($fecha)
    {
        $valores = explode('/', $fecha);
        if (count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])) {
        return true;
        }
        return false;
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
                $asunto = '[CONFIRMACIÓN] '.$evento->nombre_evento;
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
                    $error .= "No se envío el <strong>email</strong> porque no esta habilitado<br>";
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
        return ['success'=>false, "msg"=>"Operación no permitida","vista"=>"","back"=>1];
    }

    function exportar_reportes($data){
        /* $cols= ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
        //if($tipo==2)$cols= ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
        if($data['tipo']==3)$cols= ["#","Nombre","Registrados","Aptos Examen","Aprobaron Examen","Fecha Inicio","Fecha Fin"];
        if($data['tipo']==4)$cols= ["#","Nombre","Total Participantes","Total Enviados","Total Rebotados","Fecha"]; */
        
        $rows= $data["data"]["data"]??array();
        $collection = new Collection();
        if(count($rows)>0)
            foreach ($rows as $k=>$v){
                if($data['tipo']==1||$data['tipo']==2)$collection->push((object) ['id'=>$v["id"], 'nombre'=>$v["nombre"], 'registrados'=>$v["registrados"], 'asistieron'=>$v["asistieron"], 'fecha'=>$v["fecha"], 'fecha2'=>$v["fecha2"], 'gafete'=>$v["gafete"]]);
                elseif($data['tipo']==3)$collection->push((object) ['id'=>$v["id"], 'nombre'=>$v["nombre"], 'registrados'=>$v["registrados"], 'asistieron'=>$v["aptos"], 'aprobados'=>$v["aprobados"], 'fecha'=>$v["fecha"], 'fecha2'=>$v["fecha2"]] );
                elseif($data['tipo']==4)$collection->push((object) ['id'=>$v["id"], 'nombre'=>$v["nombre"], 'registrados'=>$v["participantes"], 'asistieron'=>$v["entregados"], 'rebotados'=>$v["rebotados"], 'fecha2'=>$v["fecha"]] );
                elseif($data['tipo']==8||$data['tipo']==10)$collection->push((object) ['id'=>$v["id"], 'nombre'=>$v["nombre"], 'registrados'=>$v["registrados"], 'asistieron'=>$v["aptos"],'rechazados'=>$v["rechazados"], 'fecha'=>$v["fecha"], 'fecha2'=>$v["fecha2"] ]);
                
            /* foreach($items as $item){
                $collection->push((object)['prod_id' => '99',
                                           'desc'=>'xyz',
                                           'price'=>'99',
                                           'discount'=>'7.35',
                ]);

            } */

            }
        return $collection;
        
    }
}
