<?php

namespace App\Http\Controllers;
use Cache;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Estudiante;
use App\Plantillaemail;//plantilla email
use App\Historiaemail;
use App\Programacione;
use App\Departamento;
use App\Newsletter;//ahora solo es para tb newsletter
use App\Estudiantes_vista;
use Illuminate\Support\Facades\Crypt;
use App\AccionesRolesPermisos;
use Mail;
use Alert;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function NewsletterConfirmacion(Request $request, $id){

        $id_estudiantes = $id;
        //$id_estudiantes = Crypt::decryptString($id);//$id;
        //$encrypted = Crypt::encryptString('Hello world.');
        //$decrypted = Crypt::decryptString($encrypted);

        $estado = Estudiante::where("dni_doc",Crypt::decryptString($id_estudiantes))->first();
        
        if($estado){
        	return view('newsletter.confirmacion', compact('id_estudiantes'));
        }else{
        	return view('newsletter.adios');
        }

    }

    public function NewsletterGracias(Request $request){

        $id_estudiantes = Crypt::decryptString($request->cid);//$id;
        if($request->termino == "SI"){

            DB::table('estudiantes')->where('dni_doc',$id_estudiantes)->update([
                
                'accedio'=>$request->termino

            ]);

            return view('newsletter.gracias');

        }else{

            DB::table('estudiantes')->where('dni_doc',$id_estudiantes)->delete();

            return view('newsletter.adios');

        }

    }


    //ENVIAR EMAIL

    public function enviar_email(Request $request){
    
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/estudiantes');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "estudiantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $proga = "";
        $depas = "";
           
        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $estudiantes_datos = Estudiantes_vista::where("nombres", "LIKE", '%'.$search.'%')
            ->orWhere("codigo", "LIKE", '%'.$search.'%')
            ->orWhere("dni_doc", "LIKE", '%'.$search.'%')
            ->orWhere("ap_paterno", "LIKE", '%'.$search.'%')
            ->orWhere("ap_materno", "LIKE", '%'.$search.'%')
            ->orWhere("cargo", "LIKE", '%'.$search.'%')
            ->orWhere("accedio", "LIKE", '%'.$search.'%')
            ->orWhere("email_labor", "LIKE", '%'.$search.'%')
            ->orWhere("profesion", "LIKE", '%'.$search.'%')
            ->orWhere("celular", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate(15);

        }else{
            Cache::flush();
            $key = 'enviar_email.page.'.request('page', 1);
            $estudiantes_datos = Cache::rememberForever($key, function(){
                return Estudiantes_vista::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }

        /////

        /*if(!is_null($request->prog) && !is_null($request->depa) ){
            $proga = $request->get('prog');
            $depas = $request->get('depa');

            $estudiantes_datos = Estudiantes_vista::where('codigo', '=', $request->get('prog'))
                                        ->where('ubigeo_ubigeo_id','like',$depas.'%')->get();

        }

        if(!is_null($request->prog) && is_null($request->depa) ){
            $proga = $request->get('prog');

            $estudiantes_datos = Estudiantes_vista::where('codigo', '=', $request->get('prog'))->get();

        }

        if(is_null($request->prog) && !is_null($request->depa) ){

            $depas = $request->get('depa');
                $estudiantes_datos = Estudiantes_vista::whereRaw('ubigeo_ubigeo_id like "' .$depas. '%"')->get();
                
        }
        if(is_null($request->prog) && is_null($request->depa) ){

            $estudiantes_datos = Estudiantes_vista::orderBy('id','asc')->get();
        }*/
        ////

        
        $plantilla_datos = Plantillaemail::all();
        //$estudiantes_datos = Estudiante::all();
        $programacion_datos = Programacione::all();
        
        $departamentos_datos = Departamento::select('ubigeo_id','nombre')
        ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
        ->get();
        
        return view('estudiantes.enviar_email', compact('estudiantes_datos', 'plantilla_datos', 'permisos'));

    }

    // method post envio email
    public function EmailEstudiantes(Request $request){

        /*$this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["estudiantes"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        */

        $id_html = $request->checkHTML;

        $plantilla = Plantillaemail::findOrFail($id_html);

        $id_estudiantes = $request->tipo_doc;

        //existe estudiantes - si click listado de estud.
        if($id_estudiantes){

            foreach ($id_estudiantes as $id_estu) {
            
                $estudiantes = Estudiante::select()->where('id',$id_estu)->first();
                $ver_email =$estudiantes->email;

                if($ver_email){

                    $nom = $estudiantes->nombres . ' '.$estudiantes->ap_paterno;
                
                    // CONFIGURAR DIFERENTES VISTAS CON UN SOLO CLICK
                    if($plantilla->flujo_ejecucion == "NEWSLETTER"){

                        $data = array(
                            'detail'    => "Mensaje enviado",
                            'html'      => $plantilla->plantillahtml,
                            'email'     => $estudiantes->email,
                            'id'        => $estudiantes->dni_doc, //$id_estu,
                            'nombre'    => $nom

                        );

                        /*Mail::send('email.envio_newsletter', $data, function ($mensaje) use ($datos_email){
                        //$mensaje->from('admin@enc.pe','Admin');
                        $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);

                        });*/

                    }
                    if($plantilla->flujo_ejecucion == "CONFIRMACION" OR $plantilla->flujo_ejecucion == "RECORDATORIO"){

                        $existe_part = DB::table('participante_foro')->where('participante_id',$estudiantes->dni_doc)->count();

                        if($existe_part == 0){

                            alert()->warning('El participante no esta registrado en ningún foro.','Advertencia');

                            return redirect()->route('estudiantes.enviar_email');

                        }
                        //dd($existe_part);

                        $data = array(
                            'detail'    => "Mensaje enviado",
                            'html'      => $plantilla->plantillahtml
                        );

                        /*Mail::send('email.envio_plantilla', $data, function ($mensaje) use ($datos_email){
                        //$mensaje->from('admin@enc.pe','Admin');
                        $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);

                        });*/
                    }

                    // insert historia_email
                    DB::table('historia_email')->insert([
                        'fecha'             => Carbon::now(),
                        'estudiante_id'     =>$estudiantes->dni_doc,
                        'plantillaemail_id' =>$id_html,
                        'eventos_id'         => 0,
                        'fecha_envio'       => '2000-01-01',
                        'asunto'            =>$plantilla->asunto,
                        'nombres'           =>$nom,
                        'email'             =>$estudiantes->email,
                        //'programaciones_id' =>$plantilla->lista,
                        'created_at'        =>Carbon::now(),
                        'updated_at'        =>Carbon::now()
                    ]);

                }

                //select a.id AS id,a.estudiante_id AS participante_id,c.nombres AS nombres,c.ap_paterno AS apellido_paterno,c.ap_materno AS apellido_materno,c.dni_doc AS dni,a.email AS email,a.asunto AS asunto,a.programaciones_id AS lista,b.plantillahtml AS plantillahtml,a.plantillaemail_id AS plantillaemail_id from ((historia_email a join plantillaemail b on((a.plantillaemail_id = b.id))) join estudiantes c on((a.estudiante_id = c.id))) where (a.fecha_envio = '2000-01-01') order by a.fecha desc limit 200


                
                
            } //end foreach

        }else{

            //dd('No paso');

            //dd($request->chek_enviarTodos); -->  VALIDAR QUE EXISTA EMAIL: dnavarro174@gmail.com
            //https://mailtrap.io/inboxes/450050/messages/934383382

            // falta consulta para enviar a todos
            $i = 0;
            $estudiantes = Estudiante::all();
            //$estudiantes = Estudiante::where('email','<>','dnavarromanta@gmail.com')->get();

            foreach ($estudiantes as $estudiante) {

                $ver_email =$estudiante->email;
                if($ver_email){
                    //$i++;
                    $nom = $estudiante->nombres . ' '.$estudiante->ap_paterno;
                    /*$datos_email = array(
                        'estudiante_id' => $estudiantes->dni_doc,
                        'email' => $estudiantes->email,
                        'name'  => $nom,
                        'flujo_ejecucion' => $plantilla->flujo_ejecucion,
                        'asunto'    => $plantilla->asunto,
                        'html_id'    => $id_html,
                        'lista'      => $plantilla->lista
                    );*/

                    // CONFIGURAR DIFERENTES VISTAS CON UN SOLO CLICK
                    DB::table('historia_email')->insert([
                        'fecha'         => Carbon::now(),
                        'estudiante_id'=>$estudiante->dni_doc,
                        'plantillaemail_id'=>$id_html,
                        'eventos_id'=> 0,
                        'fecha_envio'   => '2000-01-01',
                        'asunto'=>$plantilla->asunto,
                        'nombres'=>$nom,
                        'email'=>$estudiante->email,
                        'actividades_id'=>$plantilla->lista,

                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                }
                           
                
            } //end foreach
            

        } // end if
        
        
        // no vale
        /*Mail::send('email.envio_plantilla', ['msg' => $estudiantes], function($m) use ($estudiantes){
        //Mail::send('email.envio_plantilla', ['msg' => $estudiantes, 'pla' => $plantilla], function($m,$p) use ($estudiantes,$plantilla){
            //$m->to('dnavarromanta@gmail.com','Daniel')->subject('Tu mensaje fue recibido');
            $m->to($estudiantes->email, $estudiantes->nombres)->subject('Tu mensaje fue recibido');//$plantilla->asunto
        });*/

        alert()->success('Envío Exitoso.','Mensaje Enviado');
        return redirect()->route('estudiantes.enviar_email');

    }

    public function verHTML(Request $request,$id){
        if($request->ajax()){
            $plantillaHTML = Plantillaemail::where('id',$id)->first();
            //$plantillaHTML = Plantillaemail::select('plantillahtml')->where('id',$id)->first();
            //$provincias = Provincia::provincias($id);
            return response()->json($plantillaHTML);
        }
    }

    


}
