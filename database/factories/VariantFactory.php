<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'food_id' => rand(1, 10),
            'name' => '1'.':'.rand(1, 5),
            'price' => rand(10, 200),
            'status' => 1,
        ];
    }
}
