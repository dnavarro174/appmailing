<?php

namespace App\Jobs;

use App\Repositories\CampaniaRepository;
use Mail;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class SendHistoryEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $id)
    {
        $this->id  = $id;
        //
    }

    public function handle(CampaniaRepository $repository)
    {
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        
        $time_start = microtime(true);
        $dd = $this->id;
        echo "\r\n --> {$dd['id']} {$dd['nombre']} {$dd['ape_pat']} {$dd['ape_mat']} ";
        $repository->send($this->id);
        echo "CORREO ENVIADO TEST:  {$dd['id']} {$dd['nombre']} {$dd['ape_pat']} {$dd['ape_mat']} ";
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        if($time<1)sleep(1);
    }
}
