<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'staff_id' => rand(1, 4),
            'category_id' => rand(1, 4),
            'date' => $this->faker->date,
            'amount' => rand(10, 250),
            'note' => $this->faker->sentence(),
        ];
    }
}
