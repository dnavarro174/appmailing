<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id_plantilla = $this->data["id_plantilla"]??"";
        $datos_email = $this->data["datos_email"]??array();
        $data = $this->data["data"]??array();
        $flujo_ejecucion = $data["flujo_ejecucion"]??"";
        $html = $data["html"]??"";
        $nrs_gafete = $data["nrs_gafete"]??0;
        $file="";
        if($nrs_gafete>0&&isset($datos_email['file']))$file = $datos_email['file'];

        $param = $this->data["param"]??"";
        Mail::send($param, $data, function ($mensaje) use ($datos_email, $file){
            $mensaje->to($datos_email['email'], $datos_email['name'])->subject($datos_email["asunto"]);
            if($file!="")$mensaje->attach($file);
        });
        //
    }
}
