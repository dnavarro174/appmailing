<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvImportacion extends Model
{
    use HasFactory;
    protected $table = 'inv_importaciones';
    protected $fillable = ['nombre', 'tipo', 'procesado', 'total', 'oks', 'formato', 'fields', 'is_first', 'first_row', 'excluir_dni' ,
        'error', 'filesize', 'file', 'estado', 'fields', 'time1', 'time2'];
}
