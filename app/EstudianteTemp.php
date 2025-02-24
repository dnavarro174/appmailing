<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstudianteTemp extends Model
{
	protected $table = 'estudiantes_temp';
    
    protected $fillable = ['id,dni_doc,nombres,ap_paterno,ap_materno,fecha_nac,organizacion,cargo,profesion,direccion,telefono,telefono_labor,celular,email,sexo,idEntidad,repetido,mensaje,idAlumno,codigo_prog,modalidad'];

    public $timestamps = false;

}
