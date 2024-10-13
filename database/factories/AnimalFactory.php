<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'species' => fake()->randomElement(['cat', 'dog', 'bird', 'fish']),
            'birthdate' => fake()->dateTimeBetween('-20 years'),
        ];
    }
}
