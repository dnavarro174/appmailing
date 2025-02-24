<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPlantilla extends Model
{
    use HasFactory;
    protected $primaryKey = 'm_category_id';

    protected $fillable = ["m_category_id", "nombre", "asunto", "flujo_ejecucion", "gafete", "lista"];
}
