<?php

namespace App\Imports;

use App\Models\Colaboradores_Temp;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use App\Traits\ManageImport;

class ColaboradoresImport extends DefaultValueBinder implements WithCustomValueBinder, ToModel
{
    use ManageImport;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Colaboradores_Temp([
            //
        ]);
    }

}
