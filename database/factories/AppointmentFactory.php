<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'symptoms' => fake()->text(100),
            'appointment_date' => $dt = fake()->dateTimeBetween('now', '+2 month'),
            'appointment_time' => $dt,
        ];
    }
}
