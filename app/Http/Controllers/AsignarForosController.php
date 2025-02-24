<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
//use Carbon\Carbon;
use Jenssegers\Date\Date;
use App\Asistencia_evento;
use App\Estudiante;
use App\Estudiantes_caii;
use App\Departamento;

use App\Tipo_evento;
use App\Foros;
use App\Foros_participante;
use App\Plantillaemail;
//use App\estudiantes_prog_det;
use App\AccionesRolesPermisos;
use Mail;
use Excel;
use Alert;
use PDF;
use Auth;

class AsignarForosController extends Controller
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

    public function index()
    {
        //
    }

    public function EstudianteCaiiExport(){
        Excel::create('Estudiante', function($excel) {
 
            //$estudiantes = Estudiantes_caii::where('accedio','=','SI');
            $estudiantes = Estudiantes_caii::all();
            //sheet -> nomb de hoja
            $excel->sheet('Estudiante', function($sheet) use($estudiantes) {
                $sheet->fromArray($estudiantes); // muestra todos los campos
                /*$sheet->row(1, [
                    'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Email', 'Fecha de Actualización'
                ]);
                foreach($estudiantes as $index => $estud) {
                    $sheet->row($index+2, [
                        $estud->dni_doc, $estud->nombres, $estud->ap_paterno, $estud->ap_materno, $estud->email, $estud->updated_at
                    ]); 
                }*/
            });
        })->export('xlsx');
    }

    public function ParticipantesxConfirmar(){
        Excel::create('Estudiante', function($excel) {
 
            $estudiantes = Estudiante::where('accedio','=','')->get();

            //$estudiantes = DB::table('select * from estudiantes as e inner join estudiantes_prog_det as de on e.dni_doc = de.estudiantes_id where de.programacion_id=3 and e.accedio is null');
            //sheet -> nomb de hoja
            $excel->sheet('Estudiante', function($sheet) use($estudiantes) {
                $sheet->fromArray($estudiantes); // muestra todos los campos
                /*$sheet->row(1, [
                    'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Email', 'Fecha de Actualización'
                ]);
                foreach($estudiantes as $index => $estud) {
                    $sheet->row($index+2, [
                        $estud->dni_doc, $estud->nombres, $estud->ap_paterno, $estud->ap_materno, $estud->email, $estud->updated_at
                    ]); 
                }*/
            });
        })->export('xlsx');
    }



    public function asignar_foros (){

        if(!isset( session("permisosTotales")["asistencia"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }


        $foros_1 = Foros_participante::where('foro_1','1')->count();
        $foros_2 = Foros_participante::where('foro_1','2')->count();
        $foros_3 = Foros_participante::where('foro_1','3')->count();
        $foros_4 = Foros_participante::where('foro_2','4')->count();
        $foros_5 = Foros_participante::where('foro_2','5')->count();
        $foros_6 = Foros_participante::where('foro_2','6')->count();


        $foros_datos = Foros::all();

        return view('asistencia.asignar_foros', compact('foros_datos', 'foros_1', 'foros_2','foros_3', 'foros_4','foros_5','foros_6'));

    }

    public function asignar_foros_copy (){

        if(!isset( session("permisosTotales")["asistencia"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }


        $foros_1 = Foros_participante::where('foro_1','1')->count();
        $foros_2 = Foros_participante::where('foro_1','2')->count();
        $foros_3 = Foros_participante::where('foro_1','3')->count();
        $foros_4 = Foros_participante::where('foro_2','4')->count();
        $foros_5 = Foros_participante::where('foro_2','5')->count();
        $foros_6 = Foros_participante::where('foro_2','6')->count();


        $foros_datos = Foros::all();

        return view('asistencia.asignar_foros_copy', compact('foros_datos', 'foros_1', 'foros_2','foros_3', 'foros_4','foros_5','foros_6'));

    }

    public function store_asignar_foros(Request $request)
    {
        $this->validate($request,[
            'dni'=>'required',
        ]);


    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){

            $dni = $request->input('dni');
            $foro_1 = $request->input('foro_1');
            $foro_2 = $request->input('foro_2');

            // crear bandera
            $xestudiante = Estudiante::where('dni_doc', $dni)->count();
            $xestudiante_caii = Estudiantes_caii::where('dni_doc', $dni)->count();

            if(($xestudiante == 0 and $xestudiante_caii == 0)  ){
                return redirect('/asignar_foros')->with('reg_no', 'NO')->with('dni', $dni);
            }

                $xestudiante_foro = Foros_participante::where('participante_id', $dni)->count();

                $xban = 0;
                if($xestudiante_foro > 0){

                    // corregir: poner el foro que estan.

                    //$xestudiante_foro_datos = Foros::whereIn('id', [$foro_1, $foro_2])->get();
                    $xestudiante_foro_datos = Foros_participante::where('participante_id', $dni)->get();
                    //dd($xestudiante_foro_datos[0]->foro_1 .' - '. $xestudiante_foro_datos[0]->foro_2 );

                    //
                    $file = 'storage/confirmacion/2-'.$dni.'.pdf';

                    //Devuelve true
                    $exists = file_exists( $file );

                    if($exists){
                        //$xban = 1;
                        return redirect('/asignar_foros')->with('reg_ya', 'SI')->with('dni', $dni)->with('foro_1', 'Foro_1: '.$xestudiante_foro_datos[0]->foro_1)->with('foro_2', 'Foro_2: '.$xestudiante_foro_datos[0]->foro_2);

                    }else{
                        $xban = 1;
                        
                    }
                    

                }


            $rs_plantilla = Plantillaemail::where('id','2')->get();
            $rs_estudiante = Estudiante::where('dni_doc',$dni)->get();
            

            if($rs_estudiante){

            }else{

                $rs_estudiante = Estudiantes_caii::where('dni_doc',$dni)->get();

            }


            $email = $rs_estudiante[0]->email;
            $nombre = $rs_estudiante[0]->nombres;
            $nombres_ape = $rs_estudiante[0]->nombres ." ".$rs_estudiante[0]->ap_paterno;
            $nombres_apat = $rs_estudiante[0]->ap_paterno;
            $nombres_amat = $rs_estudiante[0]->ap_materno;
            $flujo_ejecucion = $rs_estudiante[0]->flujo_ejecucion;

            $asunto = $rs_plantilla[0]->asunto;
            $id_plantilla = $rs_plantilla[0]->id;
            $laplantilla = $rs_plantilla[0]->plantillahtml;
            $id_lista = $rs_plantilla[0]->lista;


                    // FOROS ENVIO A PLANTILLA HTML
                    if($xban == 1){

                        $xforo = $xestudiante_foro_datos[0]->foro_1;
                        $xforo_2 = $xestudiante_foro_datos[0]->foro_2;                      

                    }else{

                        $xforo = '7';
                        $xforo_2 = '8';

                    }
                    

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

                    /*$data = array(
                            'codigoG' => $codigoG,
                            'nombresG' => $nombre,
                            'apellidosG' => $apellidosG,
                            'apellidosG_2' => $apellidosG_2,
                            'foro_1_tit' => $foro_1_tit,
                            'foro_1' => $foro_1,
                            'foro_2_tit' => $foro_2_tit,
                            'foro_2' => $foro_2
                        );*/

                    
                    //return PDF::loadView('evento.gafete', $data )->save('storage/gafete_caii/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');
                    $pdf = PDF::loadView('evento.gafete', $data )->save('storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf');

                    
                    $file = 'storage/confirmacion/'.$id_lista.'-'.$dni.'.pdf';
                    //$file = "storage/gafete_caii/".$dni.'.pdf'; // met antiguo
                    //$file = "storage/confirmacion/12345678.pdf";
                    $directory = "storage/confirmacion/";

                    //Devuelve true
                    //$exists = is_file( $file );
                    //Devuelve false
                    /*$exists = is_file( $directory );
                    //Devuelve true
                    $exists = file_exists( $file );
                    //Devuelve TRUE
                    $exists = file_exists( $directory );*/

                    $datos_email = array(
                        'estudiante_id' => $dni,
                        'email' => $email,
                        'name'  => $nombre,
                        'flujo_ejecucion' => $flujo_ejecucion,
                        'asunto'    => $asunto,
                        'html_id'   => $id_plantilla,
                        'lista'     => $id_lista,
                        'file'      => $file
                    );

                    // envio array a plantilla confirmacion
                    $data = array(
                        //'detail'    => "Mensaje enviado",
                        'foro'      =>  $xforo,
                        'foro_2'      =>  $xforo_2,
                        'nombre'    => $nombres_ape
                    );


                    // tiene que agregar su programacion de confirmacion
                    $check_prog = DB::table('estudiantes_prog_det')->where('estudiantes_id','=',$dni)->where('programacion_id','=',2)->count();
                    if($check_prog  == 0){
                        DB::table('estudiantes_prog_det')->insert([
                            'estudiantes_id' => $dni,
                            'programacion_id' => '2' // GRUPO_CAII / PÚBLICO GENERAL
                        ]);
                    }

                    if ($xban == 0) {
                        DB::table('participante_foro')->insert([
                             'confirmado'=>'SI',//dni_doc
                             'participante_id'=>$dni,//$id,
                             'foro_1'=>$request->input('foro_1'),
                             'foro_2'=>$request->input('foro_2'),
                         ]);
                        
                    }


            DB::table('estudiantes')->where('dni_doc',$dni)->update([
                 'accedio'=>'SI'
                 //'track'=>$request->input('track'),
            ]);
    
                
            return redirect('/asignar_foros')->with(['reg_si' => 'SI', 'dni'=> $dni, 'foro_1' => $xforo, 'foro_2' => $xforo_2]);

    }

}
