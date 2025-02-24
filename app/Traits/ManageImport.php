<?php
namespace App\Traits;


use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;


trait ManageImport {
   
       /**
     * @param Cell $cell
     * @param $value
     * 
     * @return boolean;
     */
    public function bindValue(Cell $cell, $value)
    {
        //$value_num = is_numeric($value)?intval($value):0;
        $value_num = preg_match('/^-?(?:\d+|\d*\.\d+)$/',$value)?floatval($value):0;

        $formatedCellValue = $this->formatDateTimeCell($value, $datetime_output_format = "d-m-Y H:i:s", $date_output_format = "d/m/Y", $time_output_format = "H:i:s" );
        if($value_num>=1900 && $formatedCellValue != false){
            $cell->setValueExplicit($formatedCellValue, DataType::TYPE_STRING);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    /*public function bindValue(Cell $cell, $value)
    {
       if(preg_match('/^E*\d*$/', $cell->getCoordinate())){
                $cell->setValueExplicit(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d'), DataType::TYPE_STRING);
        }
        else{
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
        }

        return true;
    }*/


    /**
     * 
     * Convert excel-timestamp to Php-timestamp and again to excel-timestamp to compare both compare
     * By Leonardo J. Jauregui ( @Nanod10 | siskit dot com )
     * 
     * @param $value (cell value)
     * @param String $datetime_output_format
     * @param String $date_output_format
     * @param String $time_output_format
     * 
     * @return $formatedCellValue
     */
    private function formatDateTimeCell( $value, $datetime_output_format = "Y-m-d H:i:s", $date_output_format = "Y-m-d", $time_output_format = "H:i:s" )
    {

        // is only time flag
        $is_only_time = false;
        
        // Divide Excel-timestamp to know if is Only Date, Only Time or both of them
        $excel_datetime_exploded = explode(".", $value);

        // if has dot, maybe date has time or is only time
        if(strstr($value,".")){
            // Excel-timestamp to Php-DateTimeObject
            $dateTimeObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            // if Excel-timestamp > 0 then has Date and Time 
            if(intval($excel_datetime_exploded[0]) > 0){
                // Date and Time
                $output_format = $datetime_output_format;
                $is_only_time = false;
            }else{
                // Only time
                $output_format = $time_output_format;
                $is_only_time = true;
            }
        }else{
            // Only Date
            // Excel-timestamp to Php-DateTimeObject
            $dateTimeObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            $output_format = $date_output_format;
            $is_only_time = false;
        }
            
        // Php-DateTimeObject to Php-timestamp
        $phpTimestamp = $dateTimeObject->getTimestamp();

        // Php-timestamp to Excel-timestamp
        $excelTimestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel( $phpTimestamp );
            
        // if is only Time
        if($is_only_time){
            // 01-01-1970 = 25569
            // Substract to match PhpToExcel conversion
            $excelTimestamp = $excelTimestamp - 25569;
        }

        /* 
        // uncoment to debug manualy and see if working
        $debug_arr = [
                "value"=>$value,
                "value_float"=>floatval($value),
                "dateTimeObject"=>$dateTimeObject,
                "phpTimestamp"=>$phpTimestamp,
                "excelTimestamp"=>$excelTimestamp,
                "default_date_format"=>$dateTimeObject->format('Y-m-d H:i:s'),
                "custom_date_format"=>$dateTimeObject->format($output_format)
            ];
            
        if($cell->getColumn()=="Q"){
            if($cell->getRow()=="2"){
                if(floatval($value)===$excelTimestamp){
                    dd($debug_arr);
                }
            }
        }

        */
        //dd(floatval($value), $excelTimestamp,$output_format);
        // if the values match
        if( floatval($value) === $excelTimestamp ){
            // is a fucking date! ;)
            $formatedCellValue = $dateTimeObject->format($output_format);
            return $formatedCellValue;
        }else{
            // return normal value
            return false;
        }
        
    }
}