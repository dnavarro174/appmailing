<?php

namespace App\Http\Controllers;
use App\Estudiante, App\Actividade, App\Evento;
use App\Asistencia_evento;
##
use App\estudiantes_act_detalle;
use App\Repositories\EstudianteRepository;
use App\Exports\EstudianteExport;
use DB;//Excel
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class CaiiReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // REPORTE GENERAL PREINSCRITOS
    public function general()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        /*
        $cant_xgrupo = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.modalidad_id','de.dgrupo as name', DB::RAW('count(1) as y'))
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',1)
            #->where('de.modalidad_id',1)#presencial
            ->groupBy('de.modalidad_id','de.dgrupo')->orderBy('de.modalidad_id')->get()->toArray();
        #dd($cant_xgrupo);

        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',1)
            #->where('de.modalidad_id',1)#presencial
            ->count();
            */
            $data1 = $this->getGrupoTotal(session('eventos_id'));
            #$cant_xgrupo = $data1['cantidad'];
            $total = $data1['total'];
            $data2 = $this->getGrupoTotal(session('eventos_id'), 1);
            $data3 = $this->getGrupoTotal(session('eventos_id'), 2);
            $data1['title'] = 'Preinscritos totales por grupos';
            $data1['name'] = 'container';
            $data2['title'] = 'Preinscritos Presenciales';
            $data2['name'] = 'presencial';
            $data3['title'] = 'Preinscritos Virtuales';
            $data3['name'] = 'virtual';
            $reports = [
                $data1, $data2, $data3
            ]; 


        return view('charts.general', compact('total', 'reports'));
    }
    public function getGrupoTotal($evento_id, $modalidad=0){//0 todos, 1 presencial, 2 virtual
        $cantidad = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->when($modalidad==0, function($q){
                $q->select('de.dgrupo as name', DB::RAW('count(1) as y'));
            })
            ->when($modalidad>0, function($q){
                $q->select('de.modalidad_id','de.dgrupo as name', DB::RAW('count(1) as y'));    
            })
            ->where('de.eventos_id',$evento_id)
            ->where('de.estudiantes_tipo_id',1)
            ->when($modalidad>0, function($q) use($modalidad){
                $q->where('de.modalidad_id',$modalidad)
                ->groupBy('de.modalidad_id','de.dgrupo');
            })
            ->when($modalidad==0, function($q){
                $q->groupBy('de.dgrupo');
            })
            ->orderBy('de.modalidad_id')->get()->toArray();

        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.eventos_id',$evento_id)
            ->where('de.estudiantes_tipo_id',1)
            ->when($modalidad>0, function($q) use($modalidad){
                $q->where('de.modalidad_id',$modalidad);
            })
            ->count();
        return compact('cantidad', 'total');
    }

    // Nueva forma de exportar
    // DESCARGAR REPORTE GEN PREINSCRITOS
    public function g_exp(EstudianteRepository $export, Request $req)
    {
        $id = ($req->input('id')>0)?$req->input('id') : 0;

        if($id=="1.1"){$nom_file = "Preinscritos";$st=1;}
        elseif($id=="1.2"){$nom_file = "Preinscritos.Registrados";$st=1;}
        elseif($id=="1.3"){$nom_file = "Invitados";$st=2;}
        elseif($id=="1.4"){$nom_file = "Invitados.Registrados";$st=2;}
        elseif($id=="1.5"){$nom_file = "Registrados.Actividades";$id=2;$st="";}
        else{$nom_file="Participantes";$st=$id;}
        
        $data = array(
            "sorted"      => request('sorted', 'DESC'),
            "eventos_id"  => session('eventos_id'),
            "tipo"        => "$id",
            "all"         => "1",
            "st"          => "$st",
            //"pag"       => 3000
        );

        return Excel::download(new EstudianteExport($data, $export), "$nom_file.xlsx");
    }

    public function general_inv()
    {
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $cant_xgrupo = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name', DB::RAW('count(1) as y'))
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',2)
            ->groupBy('de.dgrupo')->get()->toArray();

        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',2)
            ->count();

        return view('charts.general_inv', compact('cant_xgrupo', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registrados()
    {
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $count_registrados = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.daccedio as name', DB::RAW('count(1) as y'))
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',2)
            ->groupBy('name')->get()->toArray();
   
        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.estudiantes_tipo_id',2)
            ->where('de.eventos_id',session('eventos_id'))
            ->count();

        return view('charts.registrados', compact('count_registrados', 'total'));
    }

    public function r_actividad()
    {
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        /* $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->where('de.eventos_id',$eventos_id)
            ->join('actividades_estudiantes as dee','estudiantes.dni_doc','=','dee.estudiantes_id')
            ->join('actividades as act','act.id','=','dee.actividad_id')
        */

        $cant_xgrupo = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
        ->where('de.eventos_id',session('eventos_id'))
        ->join('actividades_estudiantes as dee','estudiantes.dni_doc','=','dee.estudiantes_id')
        ->join('actividades as actividades','actividades.id','=','dee.actividad_id')
        ->select('actividades.titulo as name', DB::RAW('count(1) as y'),'actividades.fecha_desde')
            ->where('actividades.eventos_id',session('eventos_id'))
            ->where('dee.eventos_id',session('eventos_id'))
            ->groupBy('actividades.id')
            ->orderBy('actividades.fecha_desde', 'asc')
            ->orderBy('actividades.hora_inicio', 'asc')
            ->orderBy('actividades.titulo', 'asc')
            ->get();

        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
        ->where('de.eventos_id',session('eventos_id'))
        ->join('actividades_estudiantes as dee','estudiantes.dni_doc','=','dee.estudiantes_id')
        ->join('actividades as act','act.id','=','dee.actividad_id')
        ->select('act.titulo as name','act.fecha_desde')
            ->where('act.eventos_id',session('eventos_id'))
            ->where('dee.eventos_id',session('eventos_id'))
            ->count();
        /* 
        $cant_xgrupo = Actividade::join('actividades_estudiantes as de','actividades.id','=','de.actividad_id')
            //->join('actividades as act','de.actividad_id','=','act.id')
            ->select('actividades.titulo as name', DB::RAW('count(1) as y'),'actividades.fecha_desde')
            ->where('actividades.eventos_id',session('eventos_id'))
            ->groupBy('actividades.id')
            ->orderBy('actividades.fecha_desde', 'asc')
            ->orderBy('actividades.hora_inicio', 'asc')
            ->orderBy('actividades.titulo', 'asc')
            ->get();//->toArray()

        $total = Actividade::join('actividades_estudiantes as de','actividades.id','=','de.actividad_id')
            ->select('actividades.titulo as name','actividades.fecha_desde')
            ->where('actividades.eventos_id',session('eventos_id'))
            ->count(); */

        /*no utiliza:
        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.estudiantes_tipo_id',2)
            ->where('de.daccedio','SI')
            ->where('de.eventos_id',session('eventos_id'))
            ->count();*/

        return view('charts.r_actividades', compact('cant_xgrupo', 'total'));
    }

    public function update_caii(){
        dd("Actualizado...");
        $sql = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
        ->join('asistencia_eventos as a','estudiantes.dni_doc','=','a.estudiantes_id')
        ->select('estudiantes.dni_doc','estudiantes.cargo','estudiantes.email','de.dgrupo','a.hora')
        ->where('a.evento_id',session('eventos_id'))
        ->where('de.eventos_id',session('eventos_id'))
        ->whereNull('a.actividad_id')
        ->get();
        #dd($sql);

        foreach($sql as $key => $item){
            echo "$item->dni_doc - $item->dgrupo - $item->cargo - $item->email <br>";

            $foo = $item->email;
            if (strlen(stristr($foo,'@contraloria.gob.pe'))>0 and $item->dgrupo=="") {
                $valor = 'CONTRALORÍA';
                echo "$foo === si coincide === <br>"; #exit();
                $upt = estudiantes_act_detalle::where('estudiantes_id',$item->dni_doc)->where('eventos_id',1)
                        ->update(["dgrupo" => $valor]);
                
            }elseif($item->dgrupo==""){
                $valor = '';
                echo "Se encuentra * $valor * $key <br>";
                
                $foo = $item->cargo;
                if (strlen(stristr($foo,'CONTRALORÍA'))>0) {
                    $valor = 'CONTRALORÍA';
                    echo "Se encuentra $valor - $key <br>";
                    
                    #exit;
                }
                if (strlen(stristr($foo,'PÚBLICA'))>0) {
                    $valor = 'ENTIDAD PÚBLICA';
                    echo "Se encuentra $valor $key <br>";
                }else{
                    $valor = 'OTROS';
                    echo "Se encuentra $valor $key <br>";
                }
    
                $upt = estudiantes_act_detalle::where('estudiantes_id',$item->dni_doc)->where('eventos_id',1)
                        ->update(["dgrupo" => $valor]);
            
            }else{ }
            
        }

        
    }
    
    public function a_general()
    {
        $this->actualizarSesion();
        
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        $cant_xgrupo = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
        ->join('asistencia_eventos as a','estudiantes.dni_doc','=','a.estudiantes_id')
        ->select('de.dgrupo as name', DB::RAW('count(1) as y'))
        ->where('a.evento_id',session('eventos_id'))
        ->where('de.eventos_id',session('eventos_id'))
        ->whereNull('actividad_id')
        ->groupBy('de.dgrupo')
        ->get()
        ->toArray();

        $count_registrados = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->join('asistencia_eventos as a','estudiantes.dni_doc','=','a.estudiantes_id')
            ->select('a.fecha','de.dgrupo as name',DB::RAW("if(a.hora<='13:00:00',1,2) AS horab"), DB::RAW('count(1) as y'))
            ->where('a.evento_id',session('eventos_id'))
            ->where('de.eventos_id',session('eventos_id'))
            ->whereNull('a.actividad_id')
            ->groupBy('a.fecha')
            ->groupBy('de.dgrupo')
            ->groupBy('horab')
            ->get();
        #dd($cant_xgrupo,session('eventos_id'));
        #dd($count_registrados);
        /*$query = str_replace(array('?'), array('\'%s\''), $q->toSql());
        $query = vsprintf($query, $q->getBindings());
        dd($query);*/

        $DATA=array();$n=count($count_registrados);$i=-1;$j=-1;$iu=0;
        $fecha2=null;
        $name2=null;
        if($n >0){            
            $fechau=$count_registrados[$n-1]->fecha;
            foreach($count_registrados as $iv=>$v){
                $fecha=$v->fecha;
                $name=$v->name;
                $tipo=$v->horab;
                $total=$v->y;
                if($fechau!=$fecha){
                    if($fecha!==$fecha2||$iv==0){
                        $i++;$j=-1;
                        $DATA[$i]=(object)array("fecha"=>$fecha,"tipo"=>0,"data"=>array(),"total"=>0);
                    }
                    $DATA[$i]->total+=$total;
                    $DATA[$i]->y=$DATA[$i]->total;
                    if($name!==$name2||$j==-1){$j++;
                        $DATA[$i]->data[$j]=(object)array("name"=>$name,"total"=>0,"y"=>0);
                    }
                    $DATA[$i]->data[$j]->total+=$total;
                    $DATA[$i]->data[$j]->y=$DATA[$i]->data[$j]->total;

                }else{$iu=$i+$tipo;
                    if(!isset($DATA[$iu]))$DATA[$iu]=(object)array("fecha"=>$fecha,"tipo"=>$tipo,"data"=>array(),"total"=>0);
                    $DATA[$iu]->total+=$total;
                    $DATA[$iu]->data[]=(object)array("name"=>$name,"total"=>$total,"y"=>$total);

                }
                $fecha2=$v->fecha;
                $name2=$v->name;

            }
        }
        $count_registrados=$DATA;
        #dd($count_registrados);
        /*
        select e.grupo, a.fecha, count(*) FROM asistencia_eventos as a inner join estudiantes as e on e.dni_doc=a.estudiantes_id where a.evento_id=1 and a.actividad_id IS NULL group by a.fecha, e.grupo
        */

        return view('charts.a_general', compact('cant_xgrupo', 'count_registrados'));
    }

    public function a_actividad()
    {
        $this->actualizarSesion();
        
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        
        $count_registrados = Asistencia_evento::join('actividades as act','act.id','=','asistencia_eventos.actividad_id')
            ->select('act.titulo as name', DB::RAW('count(1) as y'),'act.fecha_desde')
            ->where('asistencia_eventos.evento_id',session('eventos_id'))
            ->groupBy('act.id')
            ->orderBy('act.fecha_desde', 'asc')
            ->orderBy('act.hora_inicio', 'asc')
            ->orderBy('act.titulo', 'asc')
            //->groupBy('name')
            ->get(); //->toArray()
       
        $total = Asistencia_evento::join('actividades as act','act.id','=','asistencia_eventos.actividad_id')
            ->select('de.dgrupo as name', DB::RAW('count(1) as y'))
            ->where('asistencia_eventos.evento_id',session('eventos_id'))
            ->count();

        /*$count_registrados = Estudiante::join('actividades_estudiantes as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->join('actividades as act','de.actividad_id','=','act.id')
            ->select('act.titulo as name', DB::RAW('count(1) as y'))
            ->where('act.eventos_id',session('eventos_id'))
            ->groupBy('name')->get()->toArray();*/

        return view('charts.a_actividad', compact('count_registrados', 'total'));
    }

    // DESCARGAR REPORTE ASISTENCIA

    public function registrados_Mod_eventos()
    {

        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $count_registrados = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.daccedio as name', DB::RAW('count(1) as y'))
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',5)
            ->groupBy('name')->get()->toArray();
   
        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.estudiantes_tipo_id',5)
            ->where('de.eventos_id',session('eventos_id'))
            ->count();

        $evento = Evento::where('id',session('eventos_id'))
                ->select('nombre_evento','fechai_evento','fechaf_evento')
                ->first();

        return view('charts.registrados_eventos', compact('count_registrados', 'evento', 'total'));
    }

    // REPORTE: de los preinscritos cuantos se han registrados - CAMPO: daccedio SI NO

    public function rep_preinscritos_aprobados()
    {
        
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $data1 = $this->getAccedioAprobados(session('eventos_id'));
        $total = $data1['total'];
        $data2 = $this->getAccedioAprobados(session('eventos_id'), 1);
        $data3 = $this->getAccedioAprobados(session('eventos_id'), 2);
        $data1['title'] = 'Preinscritos totales registrados';
        $data1['name'] = 'container';
        $data2['title'] = 'Preinscritos Presenciales';
        $data2['name'] = 'presencial';
        $data3['title'] = 'Preinscritos Virtuales';
        $data3['name'] = 'virtual';
        #dd($data1,$total);

        $reports = [
            $data1,$data2,$data3
        ];

        /*
        $count_registrados = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.daccedio as name', DB::RAW('count(1) as y'))
            ->where('de.eventos_id',session('eventos_id'))
            ->where('de.estudiantes_tipo_id',1)
            ->where('de.dtrack','SI')
            ->groupBy('name')->get()->toArray();
   
        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name')
            ->where('de.estudiantes_tipo_id',1)
            ->where('de.dtrack','SI')
            ->where('de.eventos_id',session('eventos_id'))
            ->count();
            */

        return view('charts.pre_aprobados', compact('total', 'reports'));
    
    }

    public function getAccedioAprobados($evento_id, $modalidad=0){//0 todos, 1 presencial, 2 virtual
        $cantidad = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->when($modalidad==0, function($q){
                $q->select('de.daccedio as name', DB::RAW('count(1) as y'));
            })
            ->when($modalidad>0, function($q){
                $q->select('de.modalidad_id','de.daccedio as name', DB::RAW('count(1) as y'));    
            })
            ->where('de.eventos_id',$evento_id)
            ->where('de.estudiantes_tipo_id',1)
            #->where('de.dtrack','SI')
            ->when($modalidad>0, function($q) use($modalidad){
                $q->where('de.modalidad_id',$modalidad)
                ->groupBy('de.modalidad_id','name');
            })
            ->when($modalidad==0, function($q){
                $q->groupBy('name');
            })
            ->orderBy('de.modalidad_id')->get()->toArray();

        $total = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.daccedio as name')
            ->where('de.eventos_id',$evento_id)
            ->where('de.estudiantes_tipo_id',1)
            #->where('de.dtrack','SI')
            ->when($modalidad>0, function($q) use($modalidad){
                $q->where('de.modalidad_id',$modalidad);
            })
            ->count();
        return compact('cantidad', 'total');

    }
}
