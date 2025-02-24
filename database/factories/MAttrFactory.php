<?php

namespace Database\Factories;

use App\Models\MField;
use Illuminate\Database\Eloquent\Factories\Factory;

class MAttrFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $name = $this->faker->name();
        $m_field_id = 1;// MField::all()->random()->id
        return [
            "name" => $name,
            "title" => $name,
            "subtitle" => "",//$this->faker->name(),
            "style" => '{"c":"#dc3545","f":"Arial","s":"24"}',
            "note" => "",//$this->faker->text(50),
            //"note_style" => '{"c":"blue","f":"Arial","s":"10px"}',
            "required" => true,
            "value" => "",
            "styles" => "",
            "visible" => false,
            "is_detail" => false,
            "opt" => '{}',
            "is_fullsize" => false,
            "is_title_hidden" => false,
            "m_field_id" => $m_field_id
        ];
    }
}
