<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithColumnFormatting
{
    public $data = [];
    public $headers = [];
    public $colWidths = [];
    public $style = [];
    public $colFormats = [];
    public function __construct($v)
    {
        $this->data = array_key_exists("data", $v)?$v["data"]:[];
        $this->headers = array_key_exists("headers", $v)?$v["headers"]:[];
        $this->colWidths = array_key_exists("colWidths", $v)?$v["colWidths"]:[];
        $this->style = array_key_exists("styles", $v)?$v["styles"]:[];
        $this->colFormats = array_key_exists("colFormats", $v)?$v["colFormats"]:[];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function columnWidths(): array
    {
        return $this->colWidths;
    }

    public function styles(Worksheet $sheet)
    {
        return $this->style;
    }

    public function columnFormats(): array
    {
        return $this->colFormats;
    }
}
