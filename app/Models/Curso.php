<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $table = 'm4_cursos';
    
    //protected $fillable = ["nom_curso", "cod_curso", "modalidad", "fech_ini","fech_fin","evento_id", "tpo"];
}
