<?php

namespace App\Http\Controllers;
use DB;
use App\Estudiante;
//use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AutorizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$dni,$id, $idplantilla)
    {
        // $dni : numero de DNI
        // $id  : 1 => Si se da debaja / 0 => no se da de baja
        // $idplantilla : id de la plantilla asociada a la cancelación de la suscripcion

        /*$a = "DANY";
        $b = Crypt::encrypt($a);
        $c = Crypt::decrypt($b);*/

        $s = base64_decode(urldecode($dni));
        $data = explode("-",$s,3);
        if(count($data)!=3)return abort(404);
        $dni = $data[1];
        
        $ip = request()->ip();
        $navegador = get_browser_name($_SERVER['HTTP_USER_AGENT']);

        if($id == 1){


            // SI: Acepta el envío de boletines
            Estudiante::where('dni_doc',$dni)->update([
                'estado'    => 1
            ]);

            /*DB::table('newsletters')->where('estudiante_id',$dni)->update([
                            'estado'   =>  '1'
                        ]);*/

        }else{
            // NO: Acepta el envío de boletines
            Estudiante::where('dni_doc',$dni)->update([
                'estado'    => 0
            ]);

            DB::table('newsletters')->where('estudiante_id',$dni)->update([
                            'estado'   =>  '0'
                        ]);

        }

        // GUARDO HISTORIAL EN TB: newsletter_historia

        $cant = DB::table('newsletter_historia')
                    ->where('estudiante_id', $dni)
                    ->count();
        $estado = "";
        if($id==1)
            $estado = "ACEPTO";
        elseif($id==0)
            $estado = "NO ACEPTO";
        else
            $estado = "DESUSCRITO";

            if($cant > 0){
                DB::table('newsletter_historia')->where('estudiante_id',$dni)->update([
                        'ip'             => $ip,
                        'navegador'      => $navegador,
                        'estado'         => $estado,
                        'updated_at'     => Carbon::now()
                ]);
            }else{
                DB::table('newsletter_historia')->insert([
                    'estudiante_id'  => $dni,
                    'ip'             => $ip,
                    'navegador'      => $navegador,
                    'estado'         => $estado,
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now()
                ]);
            }

        // PANTALLASO
        // 1 = SI: TIENE UNA PLANTILLA
        // 0 = NO: TIENE UNA PLANTILLA - Toma una plantilla base del sistema

        $n = DB::table('plantillaemail')
                    ->where('id', $idplantilla)
                    ->where('gafete',  'SI')
                    ->count();

        if($n == 1){
            $rs_plantilla = DB::table('plantillaemail')
                    ->select('gafete','plantilla_gafete','plantilla_extra')
                    ->where('id', $idplantilla)
                    ->first();

            if($id == 1){

                $laplantilla = $rs_plantilla->plantilla_gafete;

                $file=fopen(resource_path().'/views/email/confirmacion-new-'.$idplantilla.'.blade.php','w') or die ("error creando fichero!");

                fwrite($file,$laplantilla);
                fclose($file);
            }else{
                $laplantilla = $rs_plantilla->plantilla_extra;

                $file=fopen(resource_path().'/views/email/confirmacion-new-'.$idplantilla.'.blade.php','w') or die ("error creando fichero!");

                fwrite($file,$laplantilla);
                fclose($file);

            }

            return view('email.confirmacion-new-'.$idplantilla);

        }else{

            return view('email.suscripcion.index');
        }

        return abort(404);
    }

   
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
