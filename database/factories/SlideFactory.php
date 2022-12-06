<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'url'=>'',
            'img'=>'http://placeimg.com/1920/300/any',
            'status' => 1,
            'seq' => $this->faker->numberBetween(1,999)
        ];
    }
}
