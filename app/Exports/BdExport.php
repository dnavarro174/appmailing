<?php

namespace App\Exports;
use App\Estudiante;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Repositories\EstudianteRepository;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class BdExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithColumnFormatting
{//WithTitle
    protected $data;
    protected $export;

    public function __construct($data, $export)
    {
        if (empty($data)) {
            $data = array(
                "sorted"     => 'DESC',
                "eventos_id" => session('eventos_id'),
                "tipo"       => "1"
            );
        }
        $this->export = $export;
        $this->data   = $data;
    }

    public function collection()
    {
        //return $this->repository->search($this->data);
        return $this->export;
    }

    public function headings(): array
    {
        $sheet = ['DNI', 'Ap. Paterno', 'Ap. Materno', 'Nombres', 'Cargo', 'Entidad', 'Profesión', 'Grupo', 'País', 'Departamento', 'Celular', 'Email', 'Estado', 'FechRegistro'];

        return $sheet;
    }

    public function map($e): array
    {
  
      $sheet_det = [
        $e->dni_doc,
        $e->ap_paterno,
        $e->ap_materno,
        $e->nombres,
        $e->cargo,
        $e->organizacion,
        $e->profesion,
        $e->dgrupo,
        $e->pais,
        $e->region,
        $e->celular,
        $e->email,
        ($e->estado==1)?"ACTIVO":"INACTIVO",
        Date::dateTimeToExcel($e->created_at)
      ];

      return $sheet_det;

    }

    public function columnFormats(): array
    {
        
        $position = "N" ;
        return [
        "$position" => "dd-mm-yyyy HH:mm:ss",
        ];
    }
}
