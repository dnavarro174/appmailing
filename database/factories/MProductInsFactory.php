<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MProductInsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "m_product_id" => 1,
            "m_category_id" => 1,
            "data" => "{}",
            "m_est_id" => 1,
        ];
    }
}
