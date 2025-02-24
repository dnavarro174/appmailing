<?php

namespace Database\Factories;

use App\Models\MAttr;
use App\Models\MCategory;
use App\Models\MField;
use Illuminate\Database\Eloquent\Factories\Factory;

class MCategoryFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        $attr = MAttr::all()->random();
        return [
            "m_category_id" => 1,
            "m_field_id" => $attr->m_field_id,
            "m_attr_id" => $attr->id,
            "name" => $attr->name,
            "title" => $attr->title,
            "subtitle" => $attr->subtitle,
            "style" =>  $attr->style,
            "note" => $attr->note,
            //"note_style" =>  $attr->note_style,
            "required" =>  $attr->required,
            "value" =>  $attr->required,
            "styles" =>  $attr->styles,
            "visible" =>  $attr->visible,
            "is_detail" =>  $attr->is_detail,
            "is_fullsize" => false,
            "is_title_hidden" => false,
            "opt" =>  $attr->opt,
        ];
    }
}
