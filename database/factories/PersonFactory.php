<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'type_address' => 'CA',
            'address' => 'Nombre de la calle',
            'number_address' => $this->faker->randomNumber(2),
            'dni' => $this->faker->unique()->randomNumber(9),
            'phone' => $this->faker->phoneNumber,
            'iban' => 'ES25'.$this->faker->numerify('############'),
            'city' => $this->faker->city,
            'zip' => $this->faker->randomNumber(5),
            'region' => $this->faker->country,
            'province' => $this->faker->country
        ];
    }
}
