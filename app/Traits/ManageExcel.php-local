<?php
namespace App\Traits;
use Excel;

trait ManageExcel {

    // exportar toda la BD.

    function exportaXLS($rs,$tipos){
        //ini_set('memory_limit','60m');
        //ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 300000);
        ini_set('memory_limit','4096M');
        $path ='reports/';
        $type='xlsx';

        $nom_file="Lista";
        $rs->select('dni_doc','ap_paterno','nombres','cargo','organizacion','profesion','grupo','pais','region',
            'tipo_id','codigo_cel','celular','email','created_at','estado');
        Excel::create($nom_file, function($excel) use ($rs, $tipos) {
            $excel->sheet('Participantes', function($sheet) use($rs, $tipos) {
                $sheet->row(1, [
                    'DNI',
                    'Apellidos y Nombres',
                    'Cargo',
                    'Entidad',
                    'Profesión',
                    'Grupo',
                    'País',
                    'Departamento',
                    'Tipo',
                    'Celular',
                    'Email',
                    'FechaReg',
                    'Estado'
                ]);
                $firstRow = 2; $index=0;
                $sheet->row(1, function ($row) {
                    $row->setFontColor('#ffffff')->setBackground('#00458B');
                });
                $sheet->setWidth(array(
                    'A'     =>  12,
                    'B'     =>  35,
                    'C'     =>  25,
                    'D'     =>  25,
                    'E'     =>  25,
                    'F'     =>  25,
                    'G'     =>  15,
                    'H'     =>  20,
                    'I'     =>  15,
                    'J'     =>  15,
                    'K'     =>  30,
                    'L'     =>  20,
                    'M'     =>  15,
                ));
                $sheet->setColumnFormat(array(
                    'A' => '@'
                ));

                $rows = $rs->get();
                if($rows->count()>0){
                    foreach($rows as $datos){
                        $sheet->row($index+$firstRow, [
                            $datos->dni_doc,
                            $datos->ap_paterno .' '. $datos->ap_materno .', '. $datos->nombres,
                            $datos->cargo,
                            $datos->organizacion,
                            $datos->profesion,
                            $datos->grupo,
                            $datos->pais,
                            $datos->region,
                            array_key_exists($datos->tipo_id, $tipos)?$tipos[$datos->tipo_id] :'',
                            $datos->codigo_cel." ".$datos->celular,
                            $datos->email,
                            $datos->created_at->format('d.m.Y H:m:s'),
                            $datos->estado == 0?'Inactivo':'Activo'
                        ]);
                        $index++;
                    }
                }
                /*
                $sheet->row($index+$firstRow+1, [
                    "Tiempo inicial",$this->time1
                ]);
                $time2 = microtime(true);
                $date2 = date("d-m-Y H:i:s");
                $sheet->row($index+$firstRow+2, [
                    "Tiempo Final",$time2
                ]);
                $time3 = ($time2 - $this->time1);
                $sheet->row($index+$firstRow+3, [
                    "Tiempo Transcurrido",$time3
                ]);
                $sheet->row($index+$firstRow+4, [
                    $this->date1
                ]);
                $sheet->row($index+$firstRow+5, [
                    $date2
                ]);
                */
            });

            //})->export('xlsx');;
        })->store($type,$path, true);
        $name = "{$nom_file}.{$type}";

        return ["url"=>"{$path}{$name}","count"=>$rs->count(),"name"=>$name];
    }
}
