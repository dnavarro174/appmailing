<?php

namespace App\Exports;
use App\Models\User;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExportQuery implements FromQuery
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */

    private $date;

    
    use Exportable;

    /* primera forma

    public function __construct($date){
        $this->date = $date;
    }

    public function query()
    {
        return User::query()->wheredate('created_at', $this->date);
    } */

    // otra forma

    public function forDate($date){
        $this->date = $date;
        return $this;
    }

    public function query()
    {
        //return User::query()->whereYear('created_at', $this->date);
        //return User::query()->whereMonth('created_at', $this->date);
        return User::query()->whereDate('created_at', $this->date);
    }
}
