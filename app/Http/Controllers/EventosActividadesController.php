<?php

namespace App\Http\Controllers;


use DB;
use Cache;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use DatePeriod;

use Jenssegers\Date\Date;
use Carbon\Carbon;
use App\Ponente;
use App\Departamento;

use App\Programacione;

use App\AccionesRolesPermisos;

use App\Actividade;
use App\Evento;


use Excel;
use Alert;
use Auth;

class EventosActividadesController extends Controller
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

    public function index($evento_id)
    { 


    }

    public function actividadesXfecha(Request $request, $evento_id, $fecha)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "estudiantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();

            $arrfec = explode("-", $fecha);
            $fecha = $arrfec[2]."-".$arrfec[1]."-".$arrfec[0];
            $s = $request->get('s');
        
        Cache::flush();
        if($request->get('s')){

            $actividades_datos = Actividade::where(
                function ($query) use ($s) {
                    $query->orWhere("titulo", "LIKE", '%'.$s.'%')
                        ->orWhere("subtitulo", "LIKE", '%'.$s.'%');
                })
            
            ->where("fecha_desde",$fecha)
            ->where("eventos_id",$evento_id)            
            ->orderBy('hora_inicio', 'asc')
            ->orderBy('titulo', 'asc')
            //->orderBy('hora_inicio', 'id', request('sorted', 'desc'))
            ->paginate(15);

        }else{
            $key = 'actividades.page.'.request('page', 2);
            $actividades_datos = Cache::rememberForever($key, function() use ($fecha,$evento_id){
                return Actividade::where("fecha_desde",$fecha)->where("eventos_id",$evento_id)
                            ->orderBy('hora_inicio', 'asc')
                            ->orderBy('titulo', 'asc') 
                            #->orderBy('id', request('sorted', 'DESC'))
                            ->paginate(15);

            });

        }
        //$actividades_datos = Actividade::all();

        return view('actividades.actividades', compact('actividades_datos','departamentos_datos','permisos')); 
    }

    /**
     * Lista dias de un evento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function list_dias($evento_id)
    { 
        
        $evento = Evento::find($evento_id);
        $fini = $evento->fechai_evento;
        $ffin = $evento->fechaf_evento;
        
        $begin = new DateTime($fini);
        $end = new DateTime($ffin);
        //date_add($end, date_interval_create_from_date_string('1 days'));

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $dias = [];
        $cont = 0;
        foreach ($dateRange as $date) {
            $dias[$cont]["fecha"] = $date->format("d/m/Y");

            $actividad = Actividade::where("eventos_id",$evento_id)
                                    ->where("fecha_desde",$date->format("Y-m-d H:i:s"))
                                    ->first();
            if($actividad){
                $dias[$cont]["actividad_id"] = $actividad->id;
            }else{
                $dias[$cont]["actividad_id"] = 0;
            }
            $cont++;
        }
        
        return view('actividades.list_dias', compact('evento_id', 'dias')); 
    }

    /**
     * Formulario para crear actividad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function form_actividad($evento_id, $fecha, $actividad_id, $num)
    { 
        /*$arrF = explode("-", $fecha);
        $existe_act = Actividade::where("eventos_id", $evento_id)
                            ->whereRaw("fecha_desde = '" . $arrF[2]."-".$arrF[1]."-".$arrF[0]."'" )
                            ->first();
        if($existe_act){
            $actividad_id = $existe_act->id;
        }*/
        
        $fecha = strtotime( $fecha);
        $fecha = date('d/m/Y', $fecha); 
        
        $actividad = $actividad_id;
        
        $imagen = "";
        
        $fecha_desde = null;
        if($actividad!=0){
            $actividad = Actividade::findOrFail($actividad_id);

            $imagen = "images/act/".$actividad->imagen;
            if(!is_file($imagen)){
                $imagen="";
            }  
        }
        
        $actividad = $actividad ? $actividad : ['titulo'=>'','subtitulo'=>'','desc_actividad'=>'','desc_ponentes'=>'','hora_inicio'=>'','hora_final'=>'','ubicacion'=>'','vacantes'=>'','vacantes_v'=>'','enlace'=>''] ;
        //dd(compact('evento_id', 'fecha', 'actividad_id', 'num', 'actividad','imagen' ));
        return view('actividades.new_edit', compact('evento_id', 'fecha', 'actividad_id', 'num', 'actividad','imagen' ));

    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try {
                $evento_id = $request->input('evento_id');
                $fecha = $request->input('programaciones_id');
                $actividad_id = $request->input('actividad_id');
                $actividad_id_orig = $request->input('actividad_id');
                
                $nomarch = "";

                /*****************IMAGEN****************/
                //VALIDAR IMAGEN
                $ext = "";
                if (isset($request["file_img"])) {

                    $fileFoto = $request->file('file_img'); 
                    $ext = trim((string)$fileFoto->getClientOriginalExtension());   

                    // Get Image Dimension
                    $fileinfo = @getimagesize($_FILES["file_img"]["tmp_name"]);
                    $width = $fileinfo[0];
                    $height = $fileinfo[1];
                    
                    $allowed_image_extension = array(
                        "png",
                        "jpg",
                        "jpeg"
                    );
                    
                    // Get image file extension
                    $file_extension = pathinfo($_FILES["file_img"]["name"], PATHINFO_EXTENSION);
                    
                    // Validate file input to check if is not empty
                    if (! file_exists($_FILES["file_img"]["tmp_name"])) {
                        return \Response::json(['error' => "Choose image file to upload"], 404); 
                        exit;
                    }    // Validate file input to check if is with valid extension
                    else if (! in_array($file_extension, $allowed_image_extension)) {
                        return \Response::json(['error' => "Solo puede subir archivos con extension PNG y JPEG"], 404); 
                        exit;
                    }    // Validate image file size
                    else if (($_FILES["file_img"]["size"] > 2000000)) {
                        return \Response::json(['error' => "La imagen no debe exceder los 2MB"], 404); 
                        exit;
                    }    // Validate image file dimension
                    /*else if ($width > "500" || $height > "600") {
                        return \Response::json(['error' => "Alto y ancho excedidos"], 404); 
                        exit;
                    } */
                    else {

                        $nomarch = $request->input('evento_id')."_".mt_rand(10000000, 99999999) .".".$ext;
                        
                        
                        if($actividad_id==0){
                            $fileFoto->move("images/act", $nomarch);
                            
                        }else{
                            $tabla1 =  Actividade::find($actividad_id) ;

                            if($tabla1){ 
                                $foto =  "images/act/" . $tabla1["imagen"];
                                
                                if(is_file($foto)){                            
                                    unlink($foto);
                                }                
                                
                            }
                            $tabla = Actividade::where("id",$actividad_id) 
                            ->update(array( 
                                    'imagen' => $nomarch
                                )
                            );
                            $fileFoto->move("images/act", $nomarch);
                        }
                        
                        
                    }
                }

                /*******************FIN IMAGEN************************/
                $vacantes = $request->input('vacantes')?$request->input('vacantes'):0;
                $vacantes_v = $request->input('vacantes_v')?$request->input('vacantes_v'):0;
                

                if($actividad_id==0){//NEW
                    $arrf = explode("/", $request->input('fecha'));
                    $fecha = $arrf[2]."-".$arrf[1]."-".$arrf[0];

                    $actividad = new Actividade() ;
                    $actividad->eventos_id = $request->input('evento_id');
                    $actividad->titulo = $request->input('titulo');
                    $actividad->subtitulo = $request->input('subtitulo');
                    $actividad->desc_actividad = $request->input('desc_actividad');
                    $actividad->desc_ponentes = $request->input('desc_ponentes');
                    //$actividad->dia = $request->input('num');
                    $actividad->vacantes = $vacantes;
                    $actividad->vacantes_v = $request->input('vacantes_v');
                    $actividad->enlace = $request->input('enlace');

                    $actividad->fecha_desde = $fecha;
                    $actividad->fecha_hasta = $fecha;
                    $actividad->hora_inicio = $request->input('hora_inicio');
                    $actividad->hora_final = $request->input('hora_final');
                    $actividad->ubicacion = $request->input('ubicacion');
                    /*$actividad->inscritos = $request->input('inscritos');*/
                    $actividad->imagen = $nomarch;
                    //$actividad->estado = $request->input('xxxxxxxxxx');

                    $actividad->save();


                }else{//UPDATE
                    $actividad = Actividade::find($actividad_id) ;
                    
                    $actividad->titulo = $request->input('titulo');
                    $actividad->subtitulo = $request->input('subtitulo');
                    $actividad->desc_actividad = $request->input('desc_actividad');
                    $actividad->desc_ponentes = $request->input('desc_ponentes');
                    $actividad->vacantes = $vacantes;
                    $actividad->vacantes_v = $request->input('vacantes_v');
                    $actividad->enlace = $request->input('enlace');

                    $actividad->hora_inicio = $request->input('hora_inicio');
                    $actividad->hora_final = $request->input('hora_final');
                    $actividad->ubicacion = $request->input('ubicacion');
                    /*$actividad->inscritos = $request->input('inscritos');*/
                    $actividad->save();
                }


                return $actividad_id_orig;

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 


    
    }

}
