<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber(),
            'reference' => $this->faker->phoneNumber(),
            'address' => $this->faker->address,
            'id_card_front' => $this->faker->imageUrl,
            'id_card_back' => $this->faker->imageUrl,
        ];
    }
}
