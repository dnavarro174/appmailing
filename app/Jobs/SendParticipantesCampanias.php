<?php

namespace App\Jobs;

use App\Emails;
use App\Estudiante;
use App\Historiaemail;
use App\HistoryEmails;
use App\Http\Controllers\CampaniasController;
use App\Plantillaemail;
use App\Repositories\CampaniaRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;


class SendParticipantesCampanias implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $postData;

    /**
     * Create a new job instance.
     *
     * @param $postData
     */
    public function __construct($postData)
    {
        //
        $this->postData = $postData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CampaniaRepository $repository)
    {
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        //grabar con correo de pruebas
        $emails = ["dnavarro1745@yahoo.com","dnavarro@jjdsystem.com","dnmanta174@gmail.com","dnavarro174@outlook.com","dnavarro174@outlook.com"];
        $email_count = count($emails);
        $is_test = false;

        $plantilla_id   = $this->postData['radio'];
        $evento  = $this->postData['evento'];
        $grupo   = $this->postData['grupo'];
        $pais    = $this->postData['pais'];
        $depa    = $this->postData['depa'];
        $from_id = $this->postData['from_id'];
        $organizacion = $this->postData['organizacion'];
        $profesion    = $this->postData['profesion'];
        $participantes  = $this->postData['participantes'];
        $campania_id    = $this->postData['campania_id'];
        $all    = $this->postData['all'];


        $tipo    = $this->postData['tipo'];
        $flujo    = $this->postData['flujo'];
        $plantilla_id    = $this->postData['plantilla_id'];
        $asunto    = $this->postData['asunto'];
        $from_nombre    = $this->postData['from_nombre'];
        $from_email    = $this->postData['from_email'];
        $actividad_id    = $this->postData['actividad_id'];

        $inv = $this->postData['inv']??0;//ADDED
        $inv_id = $this->postData['inv_id']??0;//ADDED
        $table = $inv == 1 ? 'inv_estudiantes': 'estudiantes';

        $enc    = [
            "tipo"=>$this->postData['tipo'],
        ];


        if($plantilla_id) {
            $flujo_ejecucion = $flujo;

           $q = CampaniasController::generateQuery ($this->postData);

            $q->select($table.'.dni_doc as estudiantes_id', $table.'.id',$table.'.email', $table.'.nombres', $table.'.ap_paterno', $table.'.ap_materno',$table.'.codigo_cel',$table.'.celular'/*,$table.'accedio'*/);

            $participantes = $q->get();

            $ccount = $participantes->count();

            $query = str_replace(array('?'), array('\'%s\''), $q->toSql());
            $query = vsprintf($query, $q->getBindings());
            //echo $query;

            DB::beginTransaction();
            $error = false;
            $co = 0;
            $co2 = 0;
            $xemail = 0;

            foreach ($participantes as $key => $part) {
                $k = $key+1;
                $email = $part->email;
                #$email = str_replace(" ", "", $email);
                $id = $part->id;
                $dni = $part->estudiantes_id;
                $nombre = $part->nombres;
                $ape_pat = $part->ap_paterno;
                $ape_mat = $part->ap_materno;
                $cel_cod = $part->codigo_cel;
                $cel_nro = $part->celular;
                $accedio = $part->accedio;
                $nom = "{$nombre} {$ape_pat} {$ape_mat}";
                $is_valid_email = ($email=='' || filter_var($email, FILTER_VALIDATE_EMAIL) === false)?0:1;

                $data = [
                    "tipo"         => $tipo,
                    'flujo'        => $flujo_ejecucion,
                    'plantilla_id' => $plantilla_id,
                    'campania_id'  => $campania_id,
                    'evento_id'    => ($evento) ? $evento : 0,
                    'actividad_id' => 0,
                    'fecha_envio'  => '2000-01-01',
                    'email'        => $email??'',
                    'asunto'       => $asunto,
                    'estudiante_id'=> $id,
                    'nombre'       => $nombre??'',
                    'ape_pat'      => $ape_pat??'',
                    'ape_mat'      => $ape_mat??'',
                    'dni'          => $dni,
                    'cel_cod'      => $cel_cod??'',
                    'cel_nro'      => $cel_nro??'',
                    'accedio'      => $accedio??'',
                    'msg_text'     => '',
                    'msg_cel'      => '',
                    'from_nombre'  => $from_nombre,
                    'from_email'   => $from_email,
                    'status'       => -1
                ];
                if ($is_valid_email) {
                    if($is_test)$data["email"] = $emails[$xemail%$email_count];

                    $xdata = HistoryEmails::create($data);
                    if(!$data){
                        $error = true;
                        echo"\r\n ERROR";
                    }else{
                        $co++;
                        echo"     [C:{$campania_id}] ID#{$k}de{$ccount}: {$dni} {$nombre} {$email}\r\n";
                    }
                }else{
                    $co2++;

                    echo"     [Camp {$campania_id}] Part. sin correo valido #{$dni} {$nombre} {$email}\r\n";
                    $data["status"] = 1;
                    $xdata = HistoryEmails::create($data);
                }
            }
            if(!$error){
                DB::commit();
                $repository->actualizaCampania($campania_id);

                ProgramSendJob::dispatch($campania_id)->onConnection('database')->onQueue("emails")
                    ->delay(Carbon::now()->addSecond(5));

                echo "\r\nCAMPAÑA {$campania_id} PROCESADO OK ";
            }
            else {
                DB::rollBack();
                echo "\r\nCAMPAÑA {$campania_id} NO PROCESADA";
                exit;
            }
            echo "CAMPAÑA OK \r\n";
            return false;
        }
    }
}
