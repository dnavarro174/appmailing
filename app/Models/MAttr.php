<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAttr extends Model
{
    use HasFactory;
    protected $fillable = ["name", "title", "subtitle", "style","note", "required", "value", "styles", "position", "visible", "is_detail", "m_field_id", "opt", "is_title_hidden", "is_fullsize"];
    protected $attributes = [
        'style' => '{}',
        //'note_style' => '{}'
    ];
}
