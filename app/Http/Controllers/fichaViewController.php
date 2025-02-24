<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Estudiante;
use DB;
use App\Area;
use App\Area2;
use App\Area3;
use App\Datospersonales, App\Form_academica, App\Capacitaciones, App\Exp_laboral, App\Experiencia_doc, App\Cursos_doc;

use App\AccionesRolesPermisos;
use Auth;

class fichaViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    

    public function ficha($id, Request $request)
    {
        
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        if(session('eventos_id') == false){
            return redirect()->route('eventos.index');
        }

        $eventos_id = session('eventos_id');

            $datos = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
                    ->join('m5_datos_personales as per', 'per.detalle_id','=','de.id')
                    ->join('tipo_documento as tpo','tpo.id','=','estudiantes.tipo_documento_documento_id')
                    ->where('estudiantes.id',$id)
                    ->select('estudiantes.*','tpo.tipo_doc','per.*','de.id','estudiantes.id as id_estudiante')
                    ->count();
            
            if($datos == 0){
                    alert()->warning('No existe ficha en la Base de datos','Error')->persistent('Cerrar');
                    return redirect()->back();
                                
            }else{

                $datos = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
                    ->join('m5_datos_personales as per', 'per.detalle_id','=','de.id')
                    ->join('tipo_documento as tpo','tpo.id','=','estudiantes.tipo_documento_documento_id')
                    ->where('estudiantes.id',$id)
                    ->select('estudiantes.*','tpo.tipo_doc','per.*','de.id','estudiantes.id as id_estudiante')
                    ->first();
            
                $idd = $datos->id;
                $id_datos = $datos->id_datos;
            }
            //$datos = DB::table("m5_datos_personales")->where("id_datos",'=',$id)->first();
                    
            $formaciones = DB::table("m5_form_academica as form")
            ->where("form.id_datos",'=',$id_datos)
            ->select('form.*', 'form.centro_estudio_form as institucion')
            ->orderBy('form.id_form','ASC')
            ->get();

            $capacitaciones = DB::table("m5_capacitaciones as capa")
            ->where("capa.id_datos",'=',$id_datos)
            ->select('capa.*', 'capa.centro_estudio_cap as institucion')->orderBy('capa.id_capa','ASC')
            ->get();
            //dd("ID:$id", $idd, $id_datos, $capacitaciones);

            $experiencias = DB::table("m5_experiencia_laboral")->where("id_datos",'=',$id_datos)
            ->orderBy('id_experiencia','ASC')->get();
            $experiencias2 = DB::table("m5_experiencia_doc")->where("id_datos",'=',$id_datos)
            ->orderBy('id_experiencia_doc','ASC')->get();
            $cursos = DB::table("m5_cursos_doc")
            //->join("m5_datos_personales","m5_datos_personales.id_datos","=","m5_cursos_doc.id_datos_personales")
            ->join("estudiantes as e","e.id","=","m5_cursos_doc.estudiante_id")
            ->join("m5_cursos","m5_cursos_doc.id_doc_cursos","=","m5_cursos.id_doc_cursos")
            ->where("m5_cursos_doc.id_datos",'=',$id_datos)->orderBy('id_doc_cursos_doc','ASC')->get();

            return view("leads.ficha_formdoc",compact("datos","formaciones","capacitaciones","experiencias","experiencias2","cursos"));
    }

    
    function exportaXLS(Request $request,$id){
        $file="ficha-$id.xls";

        header("Content-Disposition: attachment; filename=$file");
        header('Content-Type: text/html; charset=utf-8');
        header("Content-type: application/vnd.ms-excel");

        $datos = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
                    ->join('m5_datos_personales as per', 'per.detalle_id','=','de.id')
                    ->join('tipo_documento as tpo','tpo.id','=','estudiantes.tipo_documento_documento_id')
                    ->where('estudiantes.id',$id)
                    ->select('estudiantes.*','tpo.tipo_doc','per.*','de.id','estudiantes.id as id_estudiante')
                    ->count();
            
            if($datos == 0){
                alert()->warning('No existe ficha en la Base de datos','Error')->persistent('Cerrar');
                return redirect()->back();
                        
            }else{

                $datos = Estudiante::join('estudiantes_act_detalle as de', 'estudiantes.dni_doc', '=', 'de.estudiantes_id')
                    ->join('m5_datos_personales as per', 'per.detalle_id','=','de.id')
                    ->join('tipo_documento as tpo','tpo.id','=','estudiantes.tipo_documento_documento_id')
                    ->where('estudiantes.id',$id)
                    ->select('estudiantes.*','tpo.tipo_doc','per.*','de.id','estudiantes.id as id_estudiante')
                    ->first();
            
                $idd = $datos->id;
                $id_datos = $datos->id_datos;
            }

        $formaciones = DB::table("m5_form_academica as form")
            ->where("form.id_datos",'=',$id_datos)
            ->select('form.*', 'form.centro_estudio_form as institucion')
            ->orderBy('form.id_form','ASC')
            ->get();

            $capacitaciones = DB::table("m5_capacitaciones as capa")
            ->where("capa.id_datos",'=',$id_datos)
            ->select('capa.*', 'capa.centro_estudio_cap as institucion')->orderBy('capa.id_capa','ASC')
            ->get();
            //dd("ID:$id", $idd, $id_datos, $capacitaciones);

            $experiencias = DB::table("m5_experiencia_laboral")->where("id_datos",'=',$id_datos)
            ->orderBy('id_experiencia','ASC')->get();
            $experiencias2 = DB::table("m5_experiencia_doc")->where("id_datos",'=',$id_datos)
            ->orderBy('id_experiencia_doc','ASC')->get();
            $cursos = DB::table("m5_cursos_doc")
            //->join("m5_datos_personales","m5_datos_personales.id_datos","=","m5_cursos_doc.id_datos_personales")
            ->join("estudiantes as e","e.id","=","m5_cursos_doc.estudiante_id")
            ->join("m5_cursos","m5_cursos_doc.id_doc_cursos","=","m5_cursos.id_doc_cursos")
            ->where("m5_cursos_doc.id_datos",'=',$id_datos)->orderBy('id_doc_cursos_doc','ASC')->get();

        return view("docentes.excel.ficha-formdoc-xls",compact("datos","formaciones","capacitaciones","experiencias","experiencias2","cursos"));
    }

    public function migracion(){
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        
        //$dat = DB::table('adoc_datos_personales')
        //$dat = Datospersonales::orderBy('id_datos','ASC')->get();

        //$dat = Datospersonales::orderBy('id_datos','ASC')->skip(0)->take(400)->get();//400
        //$dat = Datospersonales::orderBy('id_datos','ASC')->skip(400)->take(400)->get();//800
        //$dat = Datospersonales::orderBy('id_datos','ASC')->skip(800)->take(300)->get();//1200
        //$dat = Datospersonales::orderBy('id_datos','ASC')->skip(1100)->take(300)->get();//1200
        //$dat = Datospersonales::orderBy('id_datos','ASC')->skip(1400)->take(300)->get();//1600 

        // total 1204 inscritos

        echo "Iniciando... <br>";
        $tipo_xid = 8; //FORM doc TB:estudiantes_tipo
        $id_evento = 164; //152 local ID evento de prueba
        
        /* DB::table('m5_datos_personales')->truncate();
        DB::table('m5_form_academica')->truncate();
        DB::table('m5_capacitaciones')->truncate();
        DB::table('m5_experiencia_laboral')->truncate();
        DB::table('m5_experiencia_doc')->truncate();
        DB::table('m5_cursos_doc')->truncate();*/ 
        dd('Limpio'); 
        DB::table('eventos')->where('id', $id_evento)->update([
            'inscritos_invi'=>0
        ]);

        echo now()."<br>";
        $key = 0;
        foreach($dat as $key => $d ){
            //if($key <= 20){
                $id_datos = $d->id_datos;
                $nombres = $d->nombre;
                
                $ap_paterno = $d->ape_paterno;
                $ap_materno = $d->ape_materno;
                $tipo_doc = $d->tipo_doc;
                $doc_iden = $d->documento_iden;
                $dni_doc = $d->documento_iden;
                $departamento = $d->id_departamento;
                $provincia = $d->id_provincia;
                $distrito = $d->id_distrito;
                
                $dep = 0;
                $prov = 0;
                $dis = 0;

                if($tipo_doc == "DNI")
                    $ttipo_doc = 1;
                else
                    $ttipo_doc = 2;

                if($departamento>0){
                    $depa = Area::where('id',$departamento)->select('nombre')->first();
                    $dep = mb_strtoupper($depa->nombre);
                }

                $pais = "";
                if($dep == 'LIMA'){
                    $pais = "PERU";
                }
                // Jalando de 2 BDatos

                /* $a = Area::first();
                $b = Estudiante::first();
                $d = Area2::first();
                $c = compact('a','b','d');
                dd($c);  */
                
                if($provincia>0){
                    $depa = Area2::where('id',$provincia)->select('nombre')->first();
                    $prov = mb_strtoupper($depa->nombre);
                }
                
                if($distrito>0){
                    $depa = Area3::where('id',$distrito)->select('nombre')->first();
                    $dis = mb_strtoupper($depa->nombre);
                    //dd($dep,$prov,$dis);
                }
                
                $fecha_nac = $d->fecha_nac;
                
                $email = $d->email;
                $email_2 = $d->email_inst;
                $direccion = $d->direccion;
                $celular = $d->celular;
                $grupo = mb_strtoupper($d->tipo_participante);//grupo
                //$org = $d->condicion_colab_2;//organizacion
                //$cargo = $d->cargo;
                //$nivel_estudio = mb_strtoupper($d->nivel_estudio);//gradoprof
                
                $acepto_terminos = '';
                $fecha_reg = $d->fecha_reg;
                $ip = $d->ip;
                
                //tb: m5_datos_personales

                $dep_nac = $d->id_departamento_nac;
                $pro_nac = $d->id_provincia_nac;
                $dis_nac = $d->id_distrito_nac;

                $edad = $d->edad;
                
                $depa = Area::where('id',$dep_nac)->select('nombre')->first();
                $dep_nac = mb_strtoupper($depa->nombre);

                $depa = Area2::where('id',$pro_nac)->select('nombre')->first();
                $pro_nac = mb_strtoupper($depa->nombre);
                
                $depa = Area3::where('id',$dis_nac)->select('nombre')->first();
                $dis_nac = mb_strtoupper($depa->nombre);
                
                $preg_1 = $d->conside_legal_1;
                $preg_2 = $d->conside_legal_2;
                $preg_3 = $d->conside_legal_3;
                $preg_4 = $d->conside_legal_4;
                $preg_5 = $d->conside_legal_5;
                $preg_6 = $d->conside_legal_6;
    
                $data_es = [
                    
                        'tipo_documento_documento_id'=> $ttipo_doc,
                        
                        'nombres'     => $nombres,
                        'ap_paterno'  => $ap_paterno,
                        'ap_materno'  => $ap_materno,
                        'direccion'   => $direccion,
                        'fecha_nac'   => $fecha_nac,
                        'pais'        => $pais,
                        'region'      => $dep,
                        'provincia'   => $prov,
                        'distrito'    => $dis,
                        'email'       => $email,
                        'email_labor' => $email_2,
                        'codigo_cel'  => '51',
                        'celular'     => $celular,
                        //'telefono'    => $tel,
                        //'discapacitado'=> $disca,
                        //'cargo'       => $cargo,
                        'grupo'         => '',//$grupo,//condicion_colab
                        //'organizacion'=> $org,
                        //'profesion'   => $prof,
                        //'entidad'     => $ent,
                        //'entidad'   => $nivel_estudio,
                        'ip'          => $ip,
                        //'navegador'   => $nav,
                        'estado'      => 1,
                        'tipo_id'     => $tipo_xid,//tb_estudiantes_tipo
                        'created_at' => $fecha_reg,
                        //'updated_at'  => Carbon::now(),
                    
                ];

                // si existe en tb: estudiantes
    
                $check_est = Estudiante::where('dni_doc', $dni_doc)->count();
            
                if($check_est == 0){
                    // guardar

                    $data_es['dni_doc'] = $dni_doc;
                    echo "Nuevo: $dni_doc<br>";
                  
                        DB::table('estudiantes')->insert($data_es);
    
                        $id_estudiante = DB::getPdo()->lastInsertId();
                        $id_estudiante = isset($id_estudiante) ? $id_estudiante : 0 ;
    
                        DB::table('eventos')->where('id', $id_evento)
                                            ->increment('inscritos_invi', 1);
                        
    
                }else{
                    // actualizar
                    echo "Actualizado: $dni_doc<br>";
    
                    $check_est = Estudiante::where('dni_doc', $dni_doc)
                                ->join('estudiantes_act_detalle','estudiantes_act_detalle.estudiantes_id','=','estudiantes.dni_doc')
                                ->where('estudiantes_act_detalle.eventos_id',$id_evento)
                                ->count();
                    
                    if($check_est >= 1){
                        //return redirect('ficha-inscripcion?id='.$id_evento)->with('dni', 'Sus datos ya se encuentran registrados.');
                        //break;

                    }

    
    
                        $estt = Estudiante::where('dni_doc', $dni_doc)->select('id')->first();
                        //dd($estt->id);
                        $id_estudiante = isset($estt->id) ? $estt->id : 0 ;
    
                        DB::table('estudiantes')->where('dni_doc',$dni_doc)->update($data_es);
    
                        $autoincrem = DB::table('eventos')->where('id', $id_evento)
                                            ->increment('inscritos_invi', 1);
                        
                        
                } // fin actualizar

                echo "Codigo:  $key - $d->documento_iden - <br>";

                $ide = DB::table('estudiantes_act_detalle')
                                            ->where('estudiantes_id',$dni_doc)
                                            ->where('eventos_id',$id_evento)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->select('id')->count();
                                            
                        // si existe estudiantes_act_detalle
                        if($ide == 0){
                            // si es nuevo
                            DB::table('estudiantes_act_detalle')->insert([
                                'estudiantes_id'     => $dni_doc,
                                'eventos_id'         => $id_evento,
                                'dgrupo'             => $grupo,
                                //'actividades_id'     => $apto_a,
                                //'confirmado'         => $apto_b,
                                //'fecha_conf'         => $fechadepo,//fecha pago
                                //'dato_extra'         => $ffecha,
                                'estudiantes_tipo_id'=> $tipo_xid,
                                'daccedio'           => $acepto_terminos,
                                'estado'             => 1,
                                'created_at'         => $fecha_reg
                            ]);

                            $id_detalle = DB::getPdo()->lastInsertId();
                            $id_detalle = isset($id_detalle) ? $id_detalle : 0 ;


                            
                        }else{
                            // PROBAR IGNORANDO:

                            // actualiza estudiantes_act_detalle
                            DB::table('estudiantes_act_detalle')
                                    ->where('estudiantes_id',$dni_doc)
                                    ->where('eventos_id',$id_evento)
                                    ->where('estudiantes_tipo_id', $tipo_xid)
                                    //->delete();
                                    ->update([
                                        'dgrupo'             => $grupo,
                                        'daccedio'           => $acepto_terminos,
                                        'estado'             => 1,
                                        'created_at'         => $fecha_reg
                                    ]);
        
                            $ide = DB::table('estudiantes_act_detalle')
                                            ->where('estudiantes_id',$dni_doc)
                                            ->where('eventos_id',$id_evento)
                                            ->where('estudiantes_tipo_id', $tipo_xid)
                                            ->select('id')->first();
                            
                            // Obtenemos el id del detalle que es unico por evento
                            $id_detalle = $ide->id;
                        }
                        
                        // INSERT TB: m5_datos_personales
                        $datos_m5 = [
                            'id_datos'        => $id_datos,
                            'estudiante_id'   => $id_estudiante,
                            'detalle_id'      => $id_detalle,
                            'depar_nac'       => $dep_nac,
                            'prov_nac'        => $pro_nac,
                            'dis_nac'         => $dis_nac,
                            'edad'            => $edad,
                            'preg_1'          => $preg_1,
                            'preg_2'          => $preg_2,
                            'preg_3'          => $preg_3,
                            'preg_4'          => $preg_4,
                            'preg_5'          => $preg_5,
                            'preg_6'          => $preg_6
    
                        ];
                        
                        $id_ = DB::table('m5_datos_personales')
                                ->where('estudiante_id',$id_estudiante)
                                ->where('detalle_id',$id_detalle)
                                ->count();

                        // si existe registros
                        if($id_==0) {
                            DB::table('m5_datos_personales')->insert($datos_m5);
                            $id_datos = DB::getPdo()->lastInsertId();
                            $id_datos = isset($id_datos) ? $id_datos : 0 ;

                        }else{
                            // update
                            /* DB::table('m5_datos_personales')
                            ->where('estudiante_id',$id_estudiante)
                            ->where('detalle_id',$id_detalle)
                            // falta id_personal para que sea unico
                            ->update($datos_m5);

                             $id_datos = DB::table('m5_datos_personales')
                                ->where('estudiante_id',$id_estudiante)
                                ->where('detalle_id',$id_detalle)->select('id_datos')
                                ->first();
                            $id_datos=$id_datos->id_datos;
                            */
                        }

                        //GUARDANDO EN Tablas DEPENDIENTES DE DATOS_PERSONALES
                        // #2

                        //DB::table('adoc_form_academica')
                        $dat_form = Form_academica::where('id_datos_personales',$id_datos)->count();
                        $dat_exp_2 = DB::table('m5_form_academica')->where('id_datos',$id_datos)->count();

                        if($dat_form == $dat_exp_2)
                        {

                        }else{
                            if($dat_form > 0){
                                //dd("Si tiene registros", $dat_form);

                                $dat_form = Form_academica::where('id_datos_personales',$id_datos)
                                            ->orderBy('id_formacion','ASC')
                                            //->limit(11)
                                            ->get();

                                foreach($dat_form as $j => $f ){
                                    //if($j <= 10){
                                        $id_formacion        = $f->id_formacion;
                                        $nivel_academico     = $f->nivel_academico;
                                        $carr_profesional    = $f->carr_profesional;
                                        $especialidad        = $f->especialidad;
                                        $id_centro = $f->centro_estudio_form;
                                        $fecha_tit           = $f->fecha_tit;
                                        $napostillado        = $f->napostillado;

                                        if($id_centro>0){
                                            $xcentro = DB::table('m4_institucion')->where('id_institucion',$id_centro)
                                            ->select('institucion')->first();
                                            
                                            $centro_estudio_form = mb_strtoupper($xcentro->institucion);
                                        }

                                        // INSERT TB: m5_form_academica
                                        $form_m5 = [
                                            'id_datos'              => $id_datos,
                                            'estudiante_id'         => $id_estudiante,
                                            'detalle_id'            => $id_detalle,
                                            'nivel_academico'       => $nivel_academico,
                                            'carr_profesional'      => $carr_profesional,
                                            'especialidad'          => $especialidad,
                                            'centro_estudio_form'   => $centro_estudio_form,
                                            'fecha_tit'             => $fecha_tit,
                                            'napostillado'          => $napostillado
                                        ];
                                        
                                        $id_ = DB::table('m5_form_academica')
                                                ->where('estudiante_id',$id_estudiante)
                                                ->where('detalle_id',$id_detalle)
                                                ->count();
                                        $id_ = 0;

                                        // si existe registros
                                        if($id_==0) {
                                            DB::table('m5_form_academica')->insert($form_m5);

                                            echo "form_academica Registrado id_formacion: $id_formacion - <br>";

                                        }else{
                                            // update
                                            DB::table('m5_form_academica')
                                            ->where('estudiante_id',$id_estudiante)
                                            ->where('detalle_id',$id_detalle)
                                            // falta id_personal para que sea unico
                                            ->update($form_m5);

                                            echo "form_academica Actualizado id_formacion: $id_formacion - <br>";
                                        }


                                        
                                    //}
                                }

                            } // end dat_form
                        }

                        // #3

                        //DB::table('adoc_capacitaciones')
                        $dat_capa = Capacitaciones::where('id_datos_personales',$id_datos)->count();
                        $dat_exp_2 = DB::table('m5_capacitaciones')->where('id_datos',$id_datos)->count();

                        if($dat_capa == $dat_exp_2)
                        {

                        }else{

                            if($dat_capa > 0){
                                //dd("Si tiene registros", $dat_capa);

                                $dat_capa = Capacitaciones::where('id_datos_personales',$id_datos)
                                            ->orderBy('id_capacitaciones','ASC')
                                            //->limit(11)
                                            ->get();

                                foreach($dat_capa as $j => $c ){
                                    
                                        $id_capa             = $c->id_capacitaciones;
                                        $nombre_cap          = $c->nombre_cap;
                                        $tipo_cap            = $c->tipo_cap;
                                        $id_centro           = $c->centro_estudio_cap;
                                        $fecha_inicio_cap    = $c->fecha_inicio_cap;
                                        $fecha_fin_cap       = $c->fecha_fin_cap;
                                        $horas_cron          = $c->horas_cron;
                                        $condicion_actual    = $c->condicion_actual;

                                        $fecha_inicio_cap = str_replace('/','-',$fecha_inicio_cap);
                                        $fecha_fin_cap    = str_replace('/','-',$fecha_fin_cap);

                                        if($id_centro>0){
                                            $xcentro = DB::table('m4_institucion')->where('id_institucion',$id_centro)
                                            ->select('institucion')->first();
                                            
                                            $centro_estudio_cap = mb_strtoupper($xcentro->institucion);
                                        }else{
                                            $centro_estudio_cap = "";
                                        }

                                        // INSERT TB: ('m5_capacitaciones')
                                        $form_m5 = [
                                            'id_datos'            => $id_datos,
                                            'estudiante_id'       => $id_estudiante,
                                            'detalle_id'          => $id_detalle,
                                            'nombre_cap'          => $nombre_cap,
                                            'tipo_cap'            => $tipo_cap,
                                            'centro_estudio_cap'  => $centro_estudio_cap,
                                            'fecha_inicio_cap'    => $fecha_inicio_cap,
                                            'fecha_fin_cap'       => $fecha_fin_cap,
                                            'horas_cron'          => $horas_cron,
                                            'condicion_actual'    => $condicion_actual
                                        ];
                                        
                                        $id_ = DB::table('m5_capacitaciones')
                                                ->where('estudiante_id',$id_estudiante)
                                                ->where('detalle_id',$id_detalle)
                                                ->count();
                                        $id_ = 0;
                                        // si existe registros
                                        if($id_==0) {
                                            DB::table('m5_capacitaciones')->insert($form_m5);

                                            echo "m5_capacitaciones Registrado id_capa: $id_capa - <br>";

                                        }else{
                                            // update
                                            DB::table('m5_capacitaciones')
                                            ->where('estudiante_id',$id_estudiante)
                                            ->where('detalle_id',$id_detalle)
                                            // falta id_personal para que sea unico
                                            ->update($form_m5);

                                            echo "m5_capacitaciones Actualizado id_capa: $id_capa - <br>";
                                        }

                                }

                            } // end dat_capa
                        }

                        // #4

                        //DB::table('adoc_experiencia_laboral')
                        $dat_exp = Exp_laboral::where('id_datos_personales',$id_datos)->count();
                        $dat_exp_2 = DB::table('m5_experiencia_laboral')->where('id_datos',$id_datos)->count();

                        if($dat_exp == $dat_exp_2)
                        {

                        }else{

                            if($dat_exp > 0){
                                //dd("Si tiene registros", $dat_exp);
    
                                $dat_exp = Exp_laboral::where('id_datos_personales',$id_datos)
                                            ->orderBy('id_experiencia','ASC')
                                            //->limit(11)
                                            ->get();
    
                                foreach($dat_exp as $j => $e ){
                                
                                        $id_expe                = $e->id_experiencia;
                                        $nom_empresa            = $e->nom_empresa;
                                        $tipo_industria         = $e->tipo_industria;
                                        $puesto_cargo           = $e->puesto_cargo;
                                        $modalidad_contrato     = $e->modalidad_contrato;
                                        $actividad_desarrollada = $e->actividad_desarrollada;
                                        $fecha_inicio_lab       = $e->fecha_inicio_lab;
                                        $fecha_fin_lab          = $e->fecha_fin_lab;
    
                                        $fecha_inicio_lab = str_replace('/','-',$fecha_inicio_lab);
                                        $fecha_fin_lab    = str_replace('/','-',$fecha_fin_lab);
                                        
                                        /* if($id_centro>0){
                                            $xcentro = DB::table('m4_institucion')->where('id_institucion',$id_centro)
                                            ->select('institucion')->first();
                                            
                                            $centro_estudio_cap = mb_strtoupper($xcentro->institucion);
                                        } */
    
                                        // INSERT TB: ('m5_experiencia_laboral')
                                        $exp_m5 = [
                                            'id_datos'              => $id_datos,
                                            'estudiante_id'         => $id_estudiante,
                                            'detalle_id'            => $id_detalle,
                                            'nom_empresa'           => $nom_empresa,
                                            'tipo_industria'        => $tipo_industria,
                                            'puesto_cargo'          => $puesto_cargo,
                                            'modalidad_contrato'    => $modalidad_contrato,
                                            'actividad_desarrollada'=> $actividad_desarrollada,
                                            'fecha_inicio_lab'      => $fecha_inicio_lab,
                                            'fecha_fin_lab'         => $fecha_fin_lab
                                        ];
                                        
                                        $id_ = DB::table('m5_experiencia_laboral')
                                                ->where('estudiante_id',$id_estudiante)
                                                ->where('detalle_id',$id_detalle)
                                                ->count();
                                        $id_ = 0;
                                        // si existe registros
                                        if($id_==0) {
                                            DB::table('m5_experiencia_laboral')->insert($exp_m5);
    
                                            echo "m5_experiencia_laboral Registrado id_expe: $id_expe - <br>";
    
                                        }else{
                                            // update
                                            DB::table('m5_experiencia_laboral')
                                            ->where('estudiante_id',$id_estudiante)
                                            ->where('detalle_id',$id_detalle)
                                            // falta id_personal para que sea unico
                                            ->update($exp_m5);
    
                                            echo "m5_experiencia_laboral Actualizado id_expe: $id_expe - <br>";
                                        }
    
    
                                        
                                    
                                }
    
                            } // end dat_exp_doc
                        }


                        // aaaaaaaa

                        // #5

                        //DB::table('adoc_experiencia_doc')
                        $dat_exp2 = Experiencia_doc::where('id_datos_personales',$id_datos)->count();
                        $dat_exp_2 = DB::table('m5_experiencia_doc')->where('id_datos',$id_datos)->count();

                        if($dat_exp2 == $dat_exp_2)
                        {

                        }else{
                            if($dat_exp2 > 0){
                                //dd("Si tiene registros", $dat_exp2);

                                $dat_exp2 = Experiencia_doc::where('id_datos_personales',$id_datos)
                                            ->orderBy('id_experiencia_doc','ASC')
                                            //->limit(11)
                                            ->get();

                                foreach($dat_exp2 as $k => $ee ){
                                    
                                        $id_expe                = $ee->id_experiencia_doc;
                                        $institucion_exp        = $ee->institucion_exp;
                                        $nombre_institucion     = $ee->nombre_institucion;
                                        $nivel                  = $ee->nivel;
                                        $curso_a_cargo          = $ee->curso_a_cargo;
                                        $fecha_inicio_exp       = $ee->fecha_inicio_exp;
                                        $fecha_fin_exp          = $ee->fecha_fin_exp;

                                        $fecha_inicio_exp = str_replace('/','-',$fecha_inicio_exp);
                                        $fecha_fin_exp    = str_replace('/','-',$fecha_fin_exp);
                                        
                                        /* if($id_centro>0){
                                            $xcentro = DB::table('m4_institucion')->where('id_institucion',$id_centro)
                                            ->select('institucion')->first();
                                            
                                            $centro_estudio_cap = mb_strtoupper($xcentro->institucion);
                                        } */

                                        // INSERT TB: ('m5_experiencia_doc')
                                        $exp_doc_m5 = [
                                            'id_datos'              => $id_datos,
                                            'estudiante_id'         => $id_estudiante,
                                            'detalle_id'            => $id_detalle,
                                            'institucion_exp'       => $institucion_exp,
                                            'nombre_institucion'    => $nombre_institucion,
                                            'nivel'                 => $nivel,
                                            'curso_a_cargo'         => $curso_a_cargo,
                                            'fecha_inicio_exp'      => $fecha_inicio_exp,
                                            'fecha_fin_exp'         => $fecha_fin_exp
                                        ];
                                        
                                        $id_ = DB::table('m5_experiencia_doc')
                                                ->where('estudiante_id',$id_estudiante)
                                                ->where('detalle_id',$id_detalle)
                                                ->count();
                                        $id_ = 0;

                                        // si existe registros
                                        if($id_==0) {
                                            DB::table('m5_experiencia_doc')->insert($exp_doc_m5);

                                            echo "m5_experiencia_doc Registrado id_expe: $id_expe - <br>";

                                        }else{
                                            // update
                                            DB::table('m5_experiencia_doc')
                                            ->where('estudiante_id',$id_estudiante)
                                            ->where('detalle_id',$id_detalle)
                                            // falta id_personal para que sea unico
                                            ->update($exp_doc_m5);

                                            echo "m5_experiencia_doc Actualizado id_expe: $id_expe - <br>";
                                        }

                                }

                            } // end dat_exp2
                        }
                        // #6

                        //DB::table('adoc_cursos_doc')
                        $dat_cursos = Cursos_doc::where('id_datos_personales',$id_datos)->count();
                        $dat_exp_2 = DB::table('m5_cursos_doc')->where('id_datos',$id_datos)->count();

                        if($dat_cursos == $dat_exp_2)
                        {

                        }else{
                            if($dat_cursos > 0){
                                //dd("Si tiene registros", $dat_cursos);

                                $dat_cursos = Cursos_doc::where('id_datos_personales',$id_datos)
                                            ->orderBy('id_doc_cursos_doc','ASC')
                                            //->limit(11)
                                            ->get();

                                foreach($dat_cursos as $l => $cu ){
                                    
                                        $id_curso               = $cu->id_doc_cursos_doc;
                                        $id_doc_cursos_doc      = $cu->id_doc_cursos_doc;
                                        $id_datos_personales    = $cu->id_datos_personales;
                                        $id_doc_cursos          = $cu->id_doc_cursos;

                                    if($id_doc_cursos>0){
                                        // guardamos el id en la tabla: id_doc_cursos
                                        // guardamos la variable estudiantes_id y detalle_id
                                    }

                                        // INSERT TB: ('m5_cursos_doc')
                                        $cursos_m5 = [
                                            'id_datos'              => $id_datos,
                                            'estudiante_id'         => $id_estudiante,
                                            'detalle_id'            => $id_detalle,
                                            //'id_doc_cursos_doc'     => $id_doc_cursos_doc,
                                            //'id_datos_personales'   => $id_datos_personales,
                                            'id_doc_cursos'         => $id_doc_cursos
                                        ];
                                        
                                        $id_ = DB::table('m5_cursos_doc')
                                                ->where('estudiante_id',$id_estudiante)
                                                ->where('detalle_id',$id_detalle)
                                                ->count();

                                        // si existe registros
                                        //if($id_==0) {
                                            DB::table('m5_cursos_doc')->insert($cursos_m5);

                                            echo "m5_cursos_doc Registrado : id_curso: $id_curso - <br>";

                                        /* }else{
                                            // update
                                            DB::table('m5_cursos_doc')
                                            ->where('estudiante_id',$id_estudiante)
                                            ->where('detalle_id',$id_detalle)
                                            // falta id_personal para que sea unico
                                            ->update($cursos_m5);

                                            echo "m5_cursos_doc Actualizado : $id_curso - <br>";
                                        } */

                                }

                            } // end dat_cursos 
                        }

                    
            //} // fin endif


            // si existe en Tb: estudiantes_act_detalle
        }
        echo "Terminado.<br>";
        echo now();

    }

}
