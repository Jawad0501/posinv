<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'table_id' => rand(1001, 1006),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'start_date' => $this->faker->date,
            'start_time' => $this->faker->time,
            'end_time' => $this->faker->time,
            'total_person' => rand(1, 8),
        ];
    }
}
