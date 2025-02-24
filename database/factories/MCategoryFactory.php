<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        return [
            "name" => $name,
            "slug" => \Str::slug($name),
            "description" => $this->faker->text(50)
        ];
    }
}
