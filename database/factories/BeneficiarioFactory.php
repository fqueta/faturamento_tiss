<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BeneficiarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->getGender();
        return [
            'tipo' => $this->tipo(),
            'nome' => $this->faker->name($gender),
        ];
    }
    private function getGender() : string {
        $genders = ['male','female'];
        shuffle($genders);
        return $genders[0];
    }
    private function tipo() : string {
        $tipos = ['1','2'];
        shuffle($tipos);
        return $tipos[0];
    }

}
