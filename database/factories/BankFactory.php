<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bank>
 */
class BankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'account_name' => $this->faker->name,
            'account_number' => rand(78974144, 789741447878),
            'branch_name' => $this->faker->name,
            'balance' => rand(1000, 100000),
        ];
    }
}
