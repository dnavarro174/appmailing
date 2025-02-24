<?php

namespace Database\Seeders;

use App\Models\MAttr;
use App\Models\MCategoryField;
use Illuminate\Database\Seeder;

class MCategoryFieldSeeder extends Seeder
{
    public function copiaAttr($category_id)
    {
        $attrs = MAttr::get();
        foreach($attrs as $attr){
            MCategoryField::factory(1)->create([
                "m_category_id" => $category_id,
                "m_field_id" => $attr->m_field_id,
                "m_attr_id" => $attr->id,
                "name" => $attr->name,
                "title" => $attr->title,
                "subtitle" => $attr->subtitle,
                "style" =>  $attr->style,
                "note" => $attr->note,
                //"note_style" =>  $attr->note_style,
                "required" =>  $attr->required,
                "value" =>  $attr->value,
                "styles" =>  $attr->styles,
                "visible" =>  $attr->visible,
                "is_detail" =>  $attr->is_detail,
                "position" =>  $attr->position,
                "opt" =>  $attr->opt,
                "is_fullsize" =>  $attr->is_fullsize,
                "is_title_hidden" =>  $attr->is_title_hidden,
            ]);
        }
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->copiaAttr(1);
    }
}
