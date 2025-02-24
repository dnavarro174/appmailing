<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "m_category_id" => 1,
            "data" => "{}",
            "visible" => "[]",
        ];
    }
}
