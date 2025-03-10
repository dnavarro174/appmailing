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

class EstudianteExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithColumnFormatting, WithTitle
{
  protected $repository;
  protected $data;

  public function __construct($data, EstudianteRepository $repository)
  {
    if (empty($data)) {
      $data = array(
        //"sorted"   => request('sorted', 'DESC'),
        "sorted"     => 'DESC',
        "eventos_id" => session('eventos_id'),
        "tipo"       => "1"
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
    return $this->repository->search($this->data);
  }
  public function headings(): array
  {
    $tipo = ($this->data['tipo']>0)?$this->data['tipo'] : 0;
    $st = ($this->data['st']>0)?$this->data['st'] : 0;
    //dd($tipo);
    
    if($tipo=="1.1"){$nom_file = "Preinscritos";}
    elseif($tipo=="1.2"){$nom_file = "Preinscritos.Registrados";}
    elseif($tipo=="1.3"){$nom_file = "Invitados";}
    elseif($tipo=="1.4"){$nom_file = "Invitados.Registrados";}
    elseif($tipo=="1.5"){$nom_file = "Registrados.Actividades";}
    elseif($tipo=="3"){$nom_file = "Registrados";}
    elseif($tipo=="0"){$nom_file = "Asistencia";}
    else{$nom_file="Participantes";}

    if($tipo=="1.1"||$tipo=="1.2"||$tipo=="1.3"||$tipo=="1.4"||$tipo==3)
      {$sheet = ['MODALIDAD','DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Cargo', 'Organización', 'Profesión', 'País', 'Departamento', 'Email', 'Email 2', 'Celular', 'Grupo', 'Registrado','Tipo', 'FechRegistro'];}
    elseif($tipo=="2")#4 mmm
      {$sheet = ['MODALIDAD','DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Cargo','Organización','Profesión','País','Departamento','Email', 'Celular', 'Grupo', 'Registrado', 'FechRegistro','Tipo','Actividad'];}
    elseif($tipo=="4")#4
      {$sheet = ['Tipo Documento','DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno','Fecha_Nac','Sexo','Cargo','Organización','Profesión','Trab.CGR','CodigoCGR','Celular', 'Telefono', 'Email','Email2','Direccion','País','Departamento','Provincia','Distrito','Gradoprof','Discapacitado','FechaDeposito','Periodo','AptoProceso', 'Aprobo Examen','Registrado','NVoucher','Ubigeo'];}
    elseif($tipo=="5")
      {$sheet = ['DNI','PRONOM','NOMBRES','AP.PATERNO','AP.MATERNO','CELULAR','TELEFONO','EMAIL','CARGO','ORGANIZACION','PROFESION','FECHA_CONF','GRUPO','FECHA.REG'];}
    elseif($tipo=="6")
      {$sheet = ['DNI','Ap. Paterno', 'Ap. Materno','Nombres','Email','Email Recuperación','Área','País','Departamento','Registrado'];}
    elseif($tipo=="7")
      {$sheet = ['ID', 'Nombres', 'Apellidos', 'DNI',
        'Departamento',
        'Provincia','Distrito','Email',
        'Temática','Pregunta',
        'Registrado'];}
    elseif($tipo=="8")
      {$sheet = ['FECHA','COD_CURSO','NOMBRE_CURSO',
        'NOMBRES', 'AP_PATERNO','AP_MATERNO', 'DNI',
        'DIRECCIÓN',
        'DEPARTAMENTO','PROVINCIA','DISTRITO',
        'EMAIL','EMAIL_2','CELULAR','COND.COLABORADOR','LUGAR.LABORA',
        'CARGO','NIVEL ESTUDIO','MODALIDAD',
        'FECHA_INICIO','FECHA_FIN',
        'COND.1','COND.2','COND.3','COND.4','COND.5','COND.6'];}
    elseif($tipo=="9")
      {$sheet = ['ID',
        'NOMBRES', 'AP_PATERNO','AP_MATERNO', 'DNI',
        'DIRECCIÓN',
        'DEPARTAMENTO','PROVINCIA','DISTRITO',
        'EMAIL','EMAIL_2','CELULAR','COND.COLABORADOR','LUGAR.LABORA',
        'CARGO',
        'COND.1','COND.2','COND.3','COND.4','COND.5','COND.6',
        'REGISTRADO'];}
        //falta NIVEL DE ESTUDIO Y MODALIDAD

    elseif($tipo=="0") ##CAII ASISTENCIA
        {$sheet = ['FECHA', 'HORA','DNI','AP. PATERNO', 'AP. MATERNO', 'NOMBRES','PAÍS', 'DEPARTAMENTO', 'PROFESIÓN','CARGO','ORGANIZACION', 'GRUPO','EMAIL', 'CELULAR','ACTIVIDAD'];}
    elseif($tipo=="0.2") ##CAII ASISTENCIA
        {$sheet = ['FECHA', 'HORA','DNI','AP. PATERNO', 'AP. MATERNO', 'NOMBRES','PAÍS', 'DEPARTAMENTO', 'PROFESIÓN','CARGO','ORGANIZACION', 'GRUPO','EMAIL', 'CELULAR','ACTIVIDAD'];}
    
        else
      {$sheet = ['DNI.', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Cargo', 'Organización', 'Profesión', 'País', 'Departamento', 'Email', 'Email 2', 'Celular', 'Grupo',	'Registrado', 'FechRegistro'];}

    return $sheet;


    
     
  }
  public function map($e): array
  {
    $tipo = ($this->data['tipo']>0)?$this->data['tipo'] : 0;
    #dd('error aa',$tipo);
    $st = ($this->data['st']>0)?$this->data['st'] : 0;
    /* if($st == "1"){$tip = "PREREGISTRO";}
    if($st == "2"){$tip = "INVITADOS";} */

    if($e->estudiantes_tipo_id == 1){$tip = "PREREGISTRO";}
    elseif($e->estudiantes_tipo_id == 2){$tip = "INVITADOS";}else{$tip='';}

   
    if($tipo=="1.1"||$tipo=="1.2"||$tipo=="1.3"||$tipo=="1.4"||$tipo==3){
      
      $sheet_det = [
        $e->modalidad_id==1?'PRESENCIAL':'VIRTUAL',
        $e->dni_doc,
        $e->nombres,
        $e->ap_paterno,
        $e->ap_materno,
        $e->cargo,
        $e->organizacion,
        $e->profesion,
        $e->pais,
        $e->region,
        $e->email,
        $e->email_labor,
        $e->celular,
        $e->dgrupo,
        $e->daccedio,
        $tip,
        Date::dateTimeToExcel($e->created_at),
      ];
    //}elseif($tipo=="4"){ #se paso de 4 a 2
    }elseif($tipo=="2"){
      //$id = 2;
      $sheet_det = [
        $e->modalidad_id==1?'PRESENCIAL':'VIRTUAL',
        $e->dni_doc, $e->nombres, $e->ap_paterno, 
        $e->ap_materno,
        $e->cargo, 
        $e->organizacion, 
        $e->profesion, 
        $e->pais, 
        $e->region,
        $e->email,$e->celular, $e->dgrupo,
        $e->daccedio, $e->updated_at,
        $tip,
        $e->titulo." ".$e->subtitulo
      ];
      //Nuevos
    }elseif($tipo=="4"){
      $xfecha =\Carbon\Carbon::parse($e->fecha_conf)->format('d/m/Y');
      $xreg =\Carbon\Carbon::parse($e->created_at)->format('d/m/Y');
      $xsi_cgr=($e->si_cgr==0)?'NO':'SI';
      $sheet_det = [
          $e->tipodoc?$e->tipodoc->tipo_doc:"",$e->dni_doc, $e->nombres, $e->ap_paterno, $e->ap_materno, $e->fecha_nac, $e->sexo,
          $e->cargo,$e->organizacion,$e->profesion,
          $xsi_cgr,$e->codigo_cgr,
          $e->celular,$e->telefono,$e->email,
          $e->email_labor,
          $e->direccion,$e->pais, $e->region, $e->provincia, $e->distrito,
          $e->gradoprof,$e->discapacitado,
          $xfecha, $e->dgrupo, $e->confirmado, $e->actividades_id,$xreg,
          $e->nvoucher, $e->ubigeo

          //FALTA DESCARGAR LA FECHA EXACTA DE REGISTRO -- FALTA
      ];
    }elseif($tipo=="5"){
      $sheet_det = [
        $e->dni_doc,$e->p_pronom,$e->nombres,$e->ap_paterno,$e->ap_materno,$e->celular,$e->telefono,$e->email,$e->cargo,$e->organizacion,$e->profesion,$e->fecha_conf,$e->dgrupo,$e->created_at];
    }elseif($tipo=="6"){
      $sheet_det = [
        $e->dni_doc,$e->ap_paterno,$e->ap_materno,$e->nombres,$e->emailenc,$e->email,
        $e->areas->nombre,$e->pais,$e->region,$e->created_at
      ];
    }elseif($tipo=="7"){
      $sheet_det = [
        $e->pregunta_id, $e->nombres, $e->ap_paterno, $e->dni_doc,
        $e->region,$e->provincia,$e->distrito,
        $e->email,$e->dgrupo,$e->pregunta,
        $e->created_at
      ];
    }elseif($tipo=="8"){
      
      $f_ini =\Carbon\Carbon::parse($e->fecha_inicio)->format('d/m/Y');
      $f_fin =\Carbon\Carbon::parse($e->fecha_fin)->format('d/m/Y');

      $sheet_det = [
          #$index+1, 
          $e->created_at,
          $e->cod_curso,$e->nom_curso,
          $e->nombres, $e->ap_paterno, $e->ap_materno, $e->dni_doc,
          $e->direccion,
          $e->region,$e->provincia,$e->distrito,
          $e->email,$e->email_labor,$e->celular,$e->grupo,$e->organizacion,
          $e->cargo,$e->gradoprof,$e->moda_contractual,
          $f_ini,$f_fin,
          $e->preg_1,$e->preg_2,$e->preg_3,$e->preg_4,$e->preg_5,$e->preg_6
          // 13 camps
      ];
    }elseif($tipo=="9"){
      $sheet_det = [
        #$index+1, 
        $e->id,
        $e->nombres, $e->ap_paterno, $e->ap_materno, $e->dni_doc,
        $e->direccion,
        $e->region,$e->provincia,$e->distrito,
        $e->email,$e->email_labor,$e->celular,$e->grupo,$e->organizacion,
        $e->cargo,
        $e->preg_1,$e->preg_2,$e->preg_3,$e->preg_4,$e->preg_5,$e->preg_6,
        $e->created_at
      ];
    }elseif($tipo=="0"){#CAII ASISTENCIA
      $sheet_det = [
        $e->fecha,$e->hora,$e->dni_doc,$e->ap_paterno,$e->ap_materno,$e->nombres,$e->pais,$e->region,$e->profesion,$e->cargo,$e->organizacion,$e->grupo,$e->email,$e->celular,$e->titulo." ".$e->subtitulo
      ];
    }elseif($tipo=="0.2"){#CAII ASISTENCIA TODO
      $sheet_det = [
        $e->fecha, $e->hora, $e->dni_doc, $e->ap_paterno, $e->ap_materno, $e->nombres, $e->pais, $e->region, $e->profesion, $e->cargo, $e->organizacion, $e->grupo, $e->email, $e->celular, $e->titulo." ".$e->subtitulo
      ];
    }else{

      //$e->nombres ='="HOLA';
      sanitizeExcelObject($e, '=');
      
      $sheet_det = [
        $e->dni_doc ?? '',
        $e->nombres ?? '',
        $e->ap_paterno ?? '',
        $e->ap_materno ?? '',
        $e->cargo ?? '',
        $e->organizacion ?? '',
        $e->profesion ?? '',
        $e->pais ?? '',
        $e->region ?? '',
        $e->email ?? '',
        $e->email_labor ?? '',
        $e->celular ?? '',
        $e->dgrupo ?? '',
        $e->daccedio ?? '',
        Date::dateTimeToExcel($e->created_at),
      ];
    }
    #dd($sheet_det);
    return $sheet_det;
  }
  
  public function columnFormats(): array
  {
    $tipo = ($this->data['tipo']>0)?$this->data['tipo'] : 0;
    $position = "P" ;
    if($tipo=="1") $position = "O" ;
    if($tipo=="6") $position = "J" ;
    return [
      "$position" => "dd-mm-yyyy HH:mm:ss",
    ];
  }

  public function title(): string
  {
    $tipo = ($this->data['tipo']>0)?$this->data['tipo'] : 0;
    
    if($tipo=="1.1"){$nom_file = "Preinscritos";}
    elseif($tipo=="1.2"){$nom_file = "Preinscritos.Registrados";}
    elseif($tipo=="1.3"){$nom_file = "Invitados";}
    elseif($tipo=="1.4"){$nom_file = "Invitados.Registrados";}
    elseif($tipo=="1.5"){$nom_file = "Registrados.Actividades";}
    elseif($tipo=="3"){$nom_file = "Registrados";}
    elseif($tipo=="6"){$nom_file = "Usuarios";}
    else{$nom_file="Participantes";}

    return $nom_file;
  }

}