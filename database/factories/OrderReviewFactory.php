<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderReview>
 */
class OrderReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'food_id' => rand(1, 6),
            'user_id' => rand(13, 18),
            'rating' => rand(1, 5),
            'comment' => $this->faker->sentence(),
        ];
    }
}
