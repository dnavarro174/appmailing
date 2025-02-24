<?php

namespace App\Jobs;

use App\Campanias;
use App\Historiaemail;
use App\HistoryEmails;
use App\Repositories\CampaniaRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProgramSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campania_id;
    //cola se ejecutara despues de 5 segundos del tiempo que se comienza a procesar la campaña
    private $seconds_start = 5;
    //Tiempo de espera de cada cola
    private $seconds_for = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campania_id)
    {
        $this->campania_id = $campania_id;

        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CampaniaRepository $campaniaRepository)
    {
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        echo "Procesando history email campaña {$this->campania_id} \r\n";

        $emails = ["dnavarro1745@yahoo.com","dnavarro@jjdsystem.com","dnmanta174@gmail.com","dnavarro174@outlook.com","dnavarro174@outlook.com"];
        $email_count = count($emails);
        $is_test = false;
        $max_envios = 20000;

        $xemail = 0;
        $q = HistoryEmails::where('campania_id','=',$this->campania_id)
            ->where('status',-1);
        $rs = $q->get();
        $historyEmail_count = $rs->count();
        $historyEmail_oks = 0;

        $campania = Campanias::find($this->campania_id);
        $tipo = $campania->tipo;
        $flujo = $campania->flujo;
        $plantilla_id = $campania->plantilla_id;
        $asunto = $campania->asunto;
        $from_nombre = $campania->from_nombre;
        $from_email = $campania->from_email;
        $actividad_id = $campania->actividad_id;
        if($historyEmail_count>0){
            foreach ($rs as $key => $part) {
                $data = $part->toArray();
                $data["tipo"]=$tipo;
                $data["flujo"]=$flujo;
                $data["plantilla_id"]=$plantilla_id;
                $data["asunto"]=$asunto;
                $data["from_nombre"]=$from_nombre;
                $data["from_email"]=$from_email;
                $data["actividad_id"]=$actividad_id;
                $part->status = 0;
                if($is_test)
                    $data["email"] = $emails[$xemail%$email_count];#BORRA
                $ok = $part->save();
                $xemail += 1;
                $time = ($xemail-1)*$this->seconds_for+$this->seconds_start;
                if($ok){
                    SendHistoryEmails::dispatch($data)->onConnection('database')->onQueue("emails")
                        ->delay(Carbon::now()->addSecond($time));
                    $historyEmail_oks++;
                }
                #BORRA: PARA TESTING
                if($max_envios >0&&$xemail>$max_envios)break;
            }
        }
        if($historyEmail_oks>0&&$historyEmail_oks!=$historyEmail_count)
            ProgramSendJob::dispatch($this->campania_id)->onConnection('database')->onQueue("emails")
                ->delay(Carbon::now()->addSecond(5));
    }
}
