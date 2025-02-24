<?php

namespace App\Jobs;

use App\Traits\ManageInv;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,ManageInv;

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $this->generateImport($this->id);
    }
}
