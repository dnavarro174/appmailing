<?php

namespace App\Imports;

use App\EstudianteTemp;
use Maatwebsite\Excel\Concerns\ToModel;

class EstudianteImport implements ToModel
{
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    return new EstudianteTemp([
      //
    ]);
  }
}