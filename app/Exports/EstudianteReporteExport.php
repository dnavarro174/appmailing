<?php

namespace App\Exports;

//use App\Models\Estudiante;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Repositories\EstudianteRepository;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class EstudianteReporteExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithColumnFormatting, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /* public function collection()
    {
        dd('aaaaaaaa');
        return Estudiante::all();
    } */

    protected $repository;
    protected $data;

  public function __construct($data, EstudianteRepository $repository)
  {
    if (empty($data)) {
      $data = array(
        "sorted" => request('sorted', 'DESC'),
        "eventos_id" => session('eventos_id'),
        "tipo" => "E"
      );
    }
    $this->repository = $repository;
    $this->data = $data;
  }

  // public function setData($data)
  // {
  //   $this->data = $data;
  //   return $this;
  // }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    
    return $this->repository->exportar_reportes($this->data);
    //dd($this->repository->exportar_reportes($this->data));
  }
  public function headings(): array
  {
    
    if($this->data['tipo']==1||$this->data['tipo']==2)
      return ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
    elseif($this->data['tipo']==3)
      return ["#","Nombre","Registrados","Aptos Examen","Aprobaron Examen","Fecha Inicio","Fecha Fin"];
    elseif($this->data['tipo']==4)
      return ["#","Nombre","Total Participantes","Total Enviados","Total Rebotados","Fecha"];
    elseif($this->data['tipo']==8||$this->data['tipo']==10)
      return ["#","Nombre","Registrados","DJ.Aprobados","DJ.Rechazados","Fecha Inicio","Fecha Fin"];
    else
      return ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
      /*return [
        'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Cargo', 'Organización', 'Profesión', 'País', 'Departamento', 'Email', 'Email 2', 'Celular', 'Grupo	Registrado', 'FechRegistro'
      ];*/
  }
  
  public function map($e): array
  {
    
    if($this->data['tipo']==1||$this->data['tipo']==2)return [ $e->id,$e->nombre,$e->registrados,$e->asistieron,$e->fecha,$e->fecha2,$e->gafete];
    if($this->data['tipo']==3)return [ $e->id,$e->nombre,$e->registrados,$e->asistieron,$e->aprobados,$e->fecha,$e->fecha2];
    if($this->data['tipo']==4)return [ $e->id,$e->nombre,($e->registrados==0)?'0':$e->registrados,($e->asistieron==0)?'0':$e->asistieron,($e->rebotados==0)?'0':$e->rebotados,$e->fecha2];
    elseif($this->data['tipo']==8||$this->data['tipo']==10)return [ $e->id,$e->nombre,$e->registrados,$e->asistieron,$e->rechazados,$e->fecha,$e->fecha2 ];
    else
      return [$e->id,$e->nombre,$e->registrados,$e->asistieron,$e->fecha,$e->fecha2,$e->gafete,];
      //Date::dateTimeToExcel($e->created_at),
    
  }

  public function columnFormats(): array
  {
    return [
      'N' => "dd-mm-yyyy HH:mm:ss",
    ];
  }

  public function title(): string
  {
      return 'Reporte';
  }

  
}
