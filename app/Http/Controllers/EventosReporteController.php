<?php

namespace App\Http\Controllers;
use App\Estudiante, App\Actividade, App\Evento;
use App\Asistencia_evento;
use App\Repositories\EstudianteRepository;
use App\Exports\EstudianteExport;
use DB;//Excel
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class EventosReporteController extends Controller
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

    // Nueva forma de exportar
    public function excel_registrados(EstudianteRepository $export, Request $req)
    {
        $id = ($req->input('id')>0)?$req->input('id') : 0;
        $t  = $req->input('t')??'';
        // ID=1 : Preinscritos
        // ID=2 : Invitados
        // ID=3 : divide las consultas con if
        // T=4  : R.Maestria
        // T=5  : R.Estudios e Investigación
        // T=6  : R.Correos ENC
        // T=7  : R.leads_eventos-especiales
        // T=8  : R.DDJJ
        // T=9  : R.Form.Doc

        $id = ($id>0)?$id : 0;
        if($id == 1){$nom_file = "Preinscritos";}
        elseif($id == 2){$nom_file = "Invitados";}
        elseif($id == 3){$nom_file = "Registrados";}#
        elseif($id == 4){$nom_file = "Actividades";}
        elseif($id == 5){$nom_file = "Registrados";}
        elseif($id == 8){$nom_file = "DDJJ";}
        elseif($id == 9){$nom_file = "Form-docentes";}
        else{$nom_file = "Participantes";}

        if($id==3){
            if($t==4){$id=4;$st="";}
            elseif($t==5){$id=5;$st="";}
            elseif($t==6){$id=6;$st="";$nom_file = "Correos.ENC";}
            elseif($t==7){$id=7;$st="";}
            elseif($t==8){$id=8;$st="";}
            elseif($t==9){$id=9;$st="";}
            else{$id=1;$st="";}
        }else{$id=1;$st="";}
        
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

    // DESCARGAR REPORTE TODOS
    public function all(){

        //$id = $req->input('id');
        //$t = $req->input('t')??'';

        // ID=1 : Preinscritos
        // ID=2 : Invitados
        // ID=3 : divide las consultas con if
        // T=4  : R.Maestria
        // T=5  : R.Estudios e Investigación
        // T=6  : R.Correos ENC

        $id = 1;
        $nom_file = "Registrados";

            //$id = 2;
            Excel::create($nom_file, function($excel) use ($id) {

                //$estudiantes = Estudiante::all();
                $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id');

                    $q->select('estudiantes.dni_doc', 'estudiantes.nombres','estudiantes.ap_paterno','estudiantes.ap_materno',
                        'estudiantes.tipo_documento_documento_id',
                        'estudiantes.cargo',
                        'estudiantes.organizacion',
                        'estudiantes.profesion',
                        'estudiantes.pais',
                        'estudiantes.region',
                        'estudiantes.email', 'estudiantes.email_labor',
                        'estudiantes.celular','estudiantes.grupo', 'de.daccedio', 'estudiantes.updated_at');

                $q->where('de.daccedio','SI');
                //->where('de.eventos_id',session('eventos_id'))

                    //usuario, Nombres, Apellidos, Nombre para mostrar, Puesto, Departamento, Número del Trabajo, Teléfono del trabajo, Teléfono movil, Número de Fax, Dirección, Ciudad, Estado o Provincia, Código Postal, País o Región

                //$q->limit(10000);
                $q->skip(20000)->take(10000);
                $estudiantes = $q->get();



                //sheet -> nomb de hoja
                $excel->sheet('Participantes', function($sheet) use($estudiantes) {
                    //$sheet->fromArray($estudiantes); // muestra todos los campos


                $cols = [
                            'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno',
                            'Cargo',
                            'Organización',
                            'Profesión',
                            'País',
                            'Departamento',
                            'Email','Email 2','Celular','Grupo', 'Registrado', 'FechRegistro'
                        ];


                    $sheet->row(1, $cols);
                    foreach($estudiantes as $index => $estud) {
                        $cols2 = [
                            $estud->dni_doc, $estud->nombres, $estud->ap_paterno, $estud->ap_materno,
                            $estud->cargo,
                            $estud->organizacion,
                            $estud->profesion,
                            $estud->pais,
                            $estud->region,
                            $estud->email, $estud->email_labor,
                            $estud->celular, $estud->grupo, $estud->daccedio, $estud->updated_at
                        ];
                        $sheet->row($index+2, $cols2 );
                    }
                });

            })->export('xlsx');
    }


    public function registrados()
    {
        $t = request()->get('t')??"";
        $eventos_id = ($t==6)?2:session('eventos_id');
        //servidor=2 // local=76 //server 2
        $this->actualizarSesion();

        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["inicio"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        // depende de: session('eventos_id')
        // si por get pasa
        $count_registrados = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id')
            ->select('de.dgrupo as name', DB::RAW('count(1) as y'))
            ->where('de.eventos_id',$eventos_id)
            ->groupBy('name')->get()->toArray();


        $q = Estudiante::join('estudiantes_act_detalle as de','estudiantes.dni_doc','=','de.estudiantes_id');
            if($t==4)$q->join('mae_maestria as mm','de.id','=','mm.detalle_id');
            if($t==5)$q->join('inv_personal_details as per','de.estudiantes_id','=','per.p_passport_number');
            if($t==8)$q->join('m4_ddjj as mm','de.id','=','mm.detalle_id');
            if($t==9)$q->join('m5_datos_personales as mm','de.id','=','mm.detalle_id');

        $q->select('estudiantes.dgrupo as name')
            ->where('de.eventos_id',$eventos_id);
        $total = $q->count();


        if($t!="")$t="&t={$t}";

        return view('charts.e_registrados', compact('count_registrados', 'total','t'));
    }
}
