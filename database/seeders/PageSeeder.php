<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = ['About Us', 'Privacy Policy', 'Return Policy'];
        $faker = \Faker\Factory::create();
        foreach ($pages as $page) {
            Page::create([
                'name' => $page,
                'slug' => generate_slug($page),
                'title' => fake()->sentence(),
                'description' => fake()->text(1000),
                'meta_title' => fake()->sentence(),
                'meta_description' => fake()->text(),
            ]);
        }
    }
}
