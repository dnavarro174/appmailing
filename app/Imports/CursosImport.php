<?php

namespace App\Imports;

use App\CursoTemp;

//use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

//use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use App\Traits\ManageImport;

class CursosImport extends DefaultValueBinder implements WithCustomValueBinder, ToModel
{
    use ManageImport;

    /* public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    } */

    public function model(array $row)
    {
        return new CursoTemp([
            
        ]);
    }
 
}
