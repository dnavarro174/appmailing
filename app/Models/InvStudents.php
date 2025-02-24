<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvStudents extends Model
{
    use HasFactory;
    protected $table = 'inv_estudiantes';
    protected $guarded = ['id'];
}
