<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => generate_invoice(rand(2010, 2023)),
            'discount_type' => 'fixed',
            'discount' => rand(1, 200),
            'expire_date' => now()->addYears(rand(1, 3))->format('Y-m-d'),
        ];
    }
}
