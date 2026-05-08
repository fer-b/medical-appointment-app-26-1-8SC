<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'specialty' => $this->faker->randomElement(['Cardiology', 'Neurology', 'Pediatrics', 'Oncology', 'Dermatology', 'Psychiatry', 'General Surgery']),
        ];
    }
}
