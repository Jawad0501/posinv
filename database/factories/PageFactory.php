<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
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
            'slug' => $this->faker->slug,
            'title' => $this->faker->title,
            'description' => $this->faker->sentence(),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
        ];
    }
}
