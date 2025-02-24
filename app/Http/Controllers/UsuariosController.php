<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Usuario;
use App\Roles;
use App\UsuarioRol, App\Ajuste;
use App\AccionesRolesPermisos;

use Alert;
use Auth;
use Mail;

class UsuariosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "usuarios";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $usuarios_datos = Usuario::where("name", "LIKE", '%'.$search.'%')
            ->orWhere("email", "LIKE", '%'.$search.'%')
            ->orWhere("created_at", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

        }else{

            $key = 'usuarios.page.'.request('page', 1);
            $usuarios_datos = Cache::rememberForever($key, function() use ($pag){
                return Usuario::where('id','<>',2)->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);

            });
        }

        //$usuarios_datos = Usuario::orderBy('id','desc')->get();
        return view('usuarios.usuarios',compact('usuarios_datos','permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'    =>'required',
            'email'   =>'required|email|unique:users',
            'user_password'=>'required|min:6',
        ]);

            $password = $request->input('user_password');

            $usuario = new Usuario();
            $usuario->name = $request->input('name');
            $usuario->email = $request->input('email');
            $usuario->password = bcrypt($password);//encrypt
            $usuario->estado = 1;
            $usuario->created_at = Carbon::now();
            $usuario->updated_at = Carbon::now();
            $usuario->save();

            if($usuario->id>0) {
                $ajuste = Ajuste::findOrFail(1);

                $datos_email = array(
                        'from'     => $ajuste->email,
                        'from_name'=> $ajuste->email_nom,
                        'name'     => $usuario->name,
                        'email'    => $usuario->email,
                        'asunto'   => "Registro de Usuario - Sistema Ticketing",
                    );

                $data = array(
                    'user'      => $usuario->email,
                    'password'  => $password,
                );
                
                Mail::send('email.new_usuario', $data, function ($mensaje) use ($datos_email){
                    $mensaje->from($datos_email['from'], $datos_email['from_name'])
                    ->to($datos_email['email'], $datos_email['name'])
                    ->subject($datos_email["asunto"]);
                });

                DB::table('historia_email')->insert([
                    'tipo'              =>  'EMAIL',
                    'fecha'             => Carbon::now(),
                    'plantillaemail_id' => 0,
                    'flujo_ejecucion'   => '',
                    'fecha_envio'       => Carbon::now(),
                    'asunto'            => $datos_email["asunto"],
                    'nombres'           => $datos_email["name"],
                    'email'             => $datos_email["email"],
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now()
                ]);
            }
            Cache::flush();

            alert()->success('Mensaje Satisfactorio','Registro grabado.');
            return redirect()->route('usuarios.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$usuarios_datos = Usuario::where('id',$id)->first();
        $usuarios_datos = Usuario::findOrFail($id);

        return view('usuarios.show',compact('usuarios_datos'));
    }

 
    public function edit($id)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $usuarios_datos = Usuario::where('id',$id)->first();
        return view('usuarios.edit',compact('usuarios_datos'));
    }

    public function update(Request $request, $id)
    {
        
        if(!is_null($request->input('password'))){

            DB::table('users')->where('id',$id)->update([
                 'name'=>$request->input('name'),
                 //'email'=>$request->input('email'),
                 'estado'=>$request->input('cboEstado'),
                 'password'=>bcrypt($request->input('password')),
                 'updated_at'=>Carbon::now()
            ]);

        }else{

            DB::table('users')->where('id',$id)->update([
                 'name'=>$request->input('name'),
                 //'email'=>$request->input('email'),
                 'estado'=>$request->input('cboEstado'),
                 'updated_at'=>Carbon::now()
            ]);

        }
            Cache::flush();

            alert()->success('Registro Actualizado.','Mensaje Satisfactorio');

            return back();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function eliminarVarios(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            UsuarioRol::where('user_id',$value)->delete();            
            Usuario::where('id',$value)->delete();
        }

        Cache::flush();
        alert()->error('Eliminado','Registros borrados.');
        return redirect()->route('usuarios.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roles($id)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["roles"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $usuarios_datos = Usuario::where('id',$id)->first(); 
        $roles = Roles::orderBy("rol","ASC")->get();

        $rolesUs = DB::table('user_role')
                    ->where('user_id', $id)
                    ->get();       

        return view("usuarios.roles", 
            [   'usuarios_datos' => $usuarios_datos, 
                'roles'          => $roles,
                'rolesUs'          => $rolesUs
            ]
        );        
    }

    //public function eliminarVarios(Request $request)
    public function storeRoles(Request $request)
    {   
        try {
            if( !($request["cboRol"])  ){
                return \Response::json(['error' => "Elegir al menos un rol."], 404); 
                exit;                    
            }
            $id = $request['id'];

            DB::table('user_role')->where('user_id', $id)->delete();

            for($i=0; $i< count($request["cboRol"]) ; $i++){
                $rol = new UsuarioRol();
                $rol->user_id = $id;
                $rol->role_id = $request["cboRol"][$i];
                $rol->save(); 
            }
            Cache::flush();
                
        }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }        
    }    
}
