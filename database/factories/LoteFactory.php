<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome'=>rand(1,200),
            'quadra'=>rand(1,4),
            'bairro'=>'1',
            'endereco' => $this->faker->address(),
        ];
    }
}
