<?php

namespace App\Http\Controllers;

use App\Evento;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Ajuste;
use App\AccionesRolesPermisos;
use App\Campanias, App\Models\Mod_ddjj;

use Alert;
use Auth, Cache, File;
use Illuminate\Support\Facades\DB;
//use  Excel;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\EstudianteRepository;
use App\Exports\EstudianteReporteExport;

class AjustesController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }

    public function index (Request $request){
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["reportes"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $campanias_data = Campanias::all();

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "participantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $tpo = $request->get('tpo','')??''; //APROBADAS
        $tpo_ins = $request->get('tpo_ins','')??'';//FORMATO+MOOC
        $y = $request->get('y','')??'';//2022
        /* if($tpo<>"")
            dd($tpo, $tpo_ins, $y); */

        $tipo   = $request->get('tipo','')??'';
        $fecha1 = $request->get('fecha1','')??'';
        $fecha2 = $request->get('fecha2','')??'';
        $y      = $request->get('y','')??'';
        
        $data = $this->getDataRep(compact("tipo","fecha1","fecha2","y","tpo","tpo_ins"));
        $tipos = ["","EVENTOS","CAII","MAESTRÍA","MAILING",'','','',"DDJJ OCI","DDJJ Cartas de Compromiso"];
        $titulo = $tipos[$tipo]??"";

        //Get año y tipo de inscripcion
                
        $year = Mod_ddjj::select(DB::raw('YEAR(fecha_inicio) year'))
            ->whereYear('fecha_inicio','>',2020)
            ->groupby('year')
            ->orderBy('year','asc')
            ->get();
            
        return view('reportes.reportes_modulos', compact('data','permisos','fecha1','fecha2','tipo','titulo','year','y'));
        
    

      return view('reportes.reportes_modulos', compact('camps','permisos'));
    }

    public function excel (Request $request){
        $fileName = "registrados";
        $tipo = $request->get('tipo','')??'';
        $fecha1 = $request->get('fecha1','')??'';
        $fecha2 = $request->get('fecha2','')??'';
        $data = $this->getDataRep(compact("tipo","fecha1","fecha2"));
        dd($data);

        Excel::create($fileName, function($excel) use ($request,$data, $tipo) {
            $sheetName = "";
            if($tipo==1)$sheetName = "EVENTOS";
            if($tipo==2)$sheetName = "CAII";
            if($tipo==3)$sheetName = "MAESTRÍA";
            if($tipo==4)$sheetName = "MAILING";

            $excel->sheet($sheetName, function($sheet) use($data, $tipo) {
                $cols= ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
                //if($tipo==2)$cols= ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
                if($tipo==3)$cols= ["#","Nombre","Registrados","Aptos Examen","Aprobaron Examen","Fecha Inicio","Fecha Fin"];
                if($tipo==4)$cols= ["#","Nombre","Total Participantes","Total Enviados","Total Rebotados","Fecha"];
                $fila = 1;
                $sheet->row($fila, $cols);
                $rows= $data["data"]??array();
                //$fila++;
                if(count($rows)>0)
                    foreach ($rows as $v)
                        if($tipo==1||$tipo==2)$sheet->row(++$fila, [$v["id"],$v["nombre"],$v["registrados"],$v["asistieron"],$v["fecha"],$v["fecha2"],$v["gafete"]] );
                        elseif($tipo==3)$sheet->row(++$fila, [$v["id"],$v["nombre"],$v["registrados"],$v["aptos"],$v["aprobados"],$v["fecha"],$v["fecha2"]] );
                        elseif($tipo==4)$sheet->row(++$fila, [$v["id"],$v["nombre"],$v["participantes"],$v["entregados"],$v["rebotados"],$v["fecha"]] );
            });
        })->export('xlsx');
    }

    // exportar excel
    public function exportar_excel(EstudianteRepository $export, Request $request)
    {
        $tipo   = isset($request->tipo)?$request->tipo:"";
        $fecha1 = isset($request->fecha1)?$request->fecha1:"";
        $fecha2 = isset($request->fecha2)?$request->fecha2:"";
        $y      = isset($request->y)?$request->y:"";
        $datos = $this->getDataRep(compact("tipo","fecha1","fecha2","y"));
        
        $sheetName = "REPORTE-";
        if($tipo==1)$sheetName .= "EVENTOS";
        if($tipo==2)$sheetName .= "CAII";
        if($tipo==3)$sheetName .= "MAESTRÍA";
        if($tipo==4)$sheetName .= "MAILING";
        if($tipo==8||$tipo==10)$sheetName .= "DJ";
        $sheetName .= "-".Carbon::now().".xlsx";
        
        $data = array(
            "sorted"     => request('sorted', 'DESC'),
            "eventos_id" => 1,
            "tipo"       => $tipo,//"E"
            "pag"        => 1,//100000,
            "data"       => $datos
        );
        
        return Excel::download(new EstudianteReporteExport($data, $export), $sheetName);
    }
    
    function getDataRep($data){

        $tpo = isset($data["tpo"])?$data["tpo"]:"";
        $tpo_ins = isset($data["tpo_ins"])?$data["tpo_ins"]:"";
        $y = isset($data["y"])?$data["y"]:"";
        
        $tipo = $data["tipo"];
        $fecha1 = $data["fecha1"];
        $fecha2 = $data["fecha2"];
        $f1 = $f2 = "";
        $colIni = "fechai_evento";
        $colFin = "fechaf_evento";

        

        if($tipo==4){
            $colIni = "created_at";
            $colFin = "created_at";
            $q = Campanias::select("id","nombre","total","enviados","errores","created_at");
        }else{
            $q = Evento::select("id","nombre_evento","fechai_evento","fechaf_evento","gafete","gafete_html");
        }

        if($tipo==8){

            if($y!=""){
                /*$q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
                ->where('de.eventos_id',$eventos_id);
                $q->join('m4_ddjj as dd','de.id','=','dd.detalle_id')
                ->join('m4_cursos as cur','cur.id','=','dd.curso_id')
                ->select('dd.id as id_dj','de.id as det_id','estudiantes.id','estudiantes.dni_doc','estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno','estudiantes.cargo','estudiantes.organizacion','estudiantes.profesion','de.dgrupo','estudiantes.pais','estudiantes.region','estudiantes.codigo_cel','estudiantes.celular','estudiantes.accedio','de.daccedio','de.dtrack','de.estudiantes_tipo_id','de.estado','estudiantes.email','de.eventos_id','de.cambio_tipo','estudiantes.email_labor','estudiantes.telefono','estudiantes.tipo_documento_documento_id', 'dd.detalle_id','dd.cod_curso','dd.nom_curso','cur.*','dd.preg_1','dd.preg_2','dd.preg_3','dd.preg_4','dd.preg_5','dd.preg_6','estudiantes.distrito','de.created_at','dd.f_ini_curso','dd.f_fin_curso','dd.nota','dd.obs','de.confirmado','de.actividades_id','estudiantes.track');
                */
                $q->whereYear('created_at',$y);
            }
            
        }

        if($fecha1!="")
            $f1 = Carbon::createFromFormat('d/m/Y', $fecha1)->format('Y-m-d 00:00:00');
        if($fecha2!="")
            $f2 = Carbon::createFromFormat('d/m/Y', $fecha2)->format('Y-m-d 00:00:00');
        if($f1!=''||$f2!=''){
            if($f1!=''&&$f2!='')$q->whereDate($colIni, '>=', $f1)->whereDate($colFin, '<=', $f2);
            elseif($f1!='')$q->whereDate($colIni,  $f1);
            else $q->whereDate($colFin, '<=', $f2);
        }

        /* $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
        $query = vsprintf($query, $q->getBindings());
        dd($query); */

        $ids = [];
        $data = false;
        $details = [];
        if($tipo>0&&$tipo!=4){
            if($tipo==2)$q->where('eventos_tipo_id',1);
            if($tipo==1)$q->where('eventos_tipo_id',2);
            if($tipo==4)$q->where('eventos_tipo_id',4);
            if($tipo==3)$q->where('eventos_tipo_id',4);
            if($tipo==8)$q->where('eventos_tipo_id',8)->orWhere('eventos_tipo_id',10);
            if($tipo==10)$q->where('eventos_tipo_id',10);
            $eventos = $q->get();
            $evento_count = count($eventos);
            $total_gafete = 0;
            $total_sin_gafete = 0;
            if($evento_count>0){
                foreach ($eventos as $evento){
                    $id = $evento->id;
                    $fecha1 = $evento->fechai_evento?$evento->fechai_evento->format("d-m-Y"):'';
                    $fecha2 = $evento->fechaf_evento?$evento->fechaf_evento->format("d-m-Y"):'';
                    $gafete = isset($evento->gafete)&&$evento->gafete_html!=''?1:0;
                    if($gafete==1)$total_gafete++;$total_sin_gafete++;
                    $d = ["id"=>$id,"nombre"=>$evento->nombre_evento,"gafete"=>$gafete,
                        "fecha"=>$fecha1,"fecha2"=>$fecha2,"gafete"=>$gafete==1?'SI':'NO',
                        "registrados"=>'0',"asistieron"=>'0',"aptos"=>'0',"aprobados"=>'0',"rechazados"=>'0'];
                    $details[$id] = $d;
                    array_push($ids,intval($id));
                }
            }
            
            $colsMaestria = '';
            $year = '';
            if($tipo==3)
                $colsMaestria = ", sum(if(confirmado=1,1,0)) AS aptos, sum(if(actividades_id=0,0,1)) AS aprobados";
            if($tipo==8||$tipo==10)
                $colsMaestria = ", sum(if(confirmado=1,1,0)) AS aptos, sum(if(confirmado=2,1,0)) AS rechazados, sum(if(actividades_id=0,0,1)) AS aprobados";

            $q = DB::table('estudiantes_act_detalle')->
            selectRaw("eventos_id as id, count(estudiantes_id) AS can{$colsMaestria}")->whereIn('eventos_id', $ids)->groupBy('eventos_id');
            //->get();

            if($tipo==8||$tipo==10){
                $q = DB::table('estudiantes_act_detalle as de')
                    ->join('m4_ddjj as dj','de.id','=','dj.detalle_id')
                    ->join('m4_cursos as cu','dj.curso_id','=','cu.id')
                    ->selectRaw("de.eventos_id as id, count(de.estudiantes_id) AS can{$colsMaestria}")
                    //->where('de.eventos_id',186)
                    ->whereIn('eventos_id', $ids)
                    ->groupBy('de.eventos_id');

                    if($tpo!=""){
                        //tpo=APROBADAS tb: estudiantes detalle
                        $x = ($tpo=="APROBADAS") ? 1 : ($tpo=="RECHAZADAS" ? 2 : '');
                        $q->where('de.confirmado',$x);
                    }
                    if($tpo_ins!=""){
                        //tpo_ins=HIBRIDO tb: m4_cursos
                        $q->where('cu.modalidad',$tpo_ins);
                    }
                    
                    //->toSql();
                    $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
                    $query = vsprintf($query, $q->getBindings());
            }
            $registrados = $q->get();
            //dd($query);
            $registrado_count = count($registrados);
            //dd($registrados);
            if($registrado_count>0)
                foreach ($registrados as $d)
                    if(isset($details[$d->id])){
                        $details[$d->id]["registrados"] = $d->can;
                        if($tipo==3||$tipo==8||$tipo==10){
                            $details[$d->id]["aptos"] = $d->aptos;
                            $details[$d->id]["aprobados"] = $d->aprobados;
                        }
                        if($tipo==8||$tipo==10)$details[$d->id]["rechazados"] = $d->rechazados;
                    }
            $asistieron = DB::table('asistencia_eventos')->
            selectRaw('evento_id as id, count(estudiantes_id) AS can')->whereIn('evento_id', $ids)->groupBy('evento_id')->get();
            $asistieron_count = count($asistieron);
            if($asistieron_count>0)
                foreach ($asistieron as $d)
                    if(isset($details[$d->id]))$details[$d->id]["asistieron"] = $d->can;
            $data = ["total_gafete"=>$total_gafete,"total_sin_gafete"=>$total_sin_gafete,"data"=>$details,"count"=>$evento_count];
            
        }
        if($tipo==4){
            $campanias = $q->get();
            $campania_count = count($campanias);
            $total = $rebotados = $entregados = 0;
            if($campania_count>0){
                foreach ($campanias as $campania){
                    $id = $campania->id;
                    $total += $campania->total;
                    $entregados += $campania->enviados;
                    $rebotados += $campania->errores;
                    $fecha = $campania->created_at?$campania->created_at->format("d-m-Y"):'';
                    $d = ["id"=>$id,"nombre"=>$campania->nombre,"fecha"=>$fecha,
                        "participantes"=>$campania->total,"entregados"=>$campania->enviados,"rebotados"=>$campania->errores
                    ];
                    $details[$id] = $d;
                    array_push($ids,intval($id));
                }
            }
            $data = array("data"=>$details,"total"=>$total,"entregados"=>$entregados,"rebotados"=>$rebotados,"count"=>$campania_count);
        }
        //dd($details);
        //dd($data);
        return $data;
    }

    public function edit($id)
    {

        $datos = Ajuste::findOrFail($id);
        return view('ajustes.edit',compact('datos'));
    }


    public function update(Request $request, $id)
    {

        try {
              $url_img = public_path('images/form/a/');

              $ajustes = Ajuste::find($id);

              if($file = $request->file('logo')){

                $img = Ajuste::findOrFail($id);
                $img_borrar = $url_img.$img->logo;
                File::delete($img_borrar);

                //$nombre = $file->getClientOriginalName();
                $nombre = 'logo_'.strtotime('now').'.'.$file->getClientOriginalExtension();
                $file->move('images/form/a',$nombre);

                $ajustes->logo = $nombre;
              }

               $ajustes->email = $request->input('email');
               $ajustes->email_nom = $request->input('email_nom');
               $ajustes->save();

            Cache::flush();

            alert()->success('Mensaje Satisfactorio','Registro actualizado.');

            return redirect()->back();

        } catch (Exception $e) {
          return "Error: ".$e;

        }
    }



}
