<?php

namespace Database\Seeders;

use App\Models\IngredientCategory;
use Illuminate\Database\Seeder;

class IngredientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Laptop', 'SSD', 'HDD', 'PC', 'Charger', 'Motherboard', 'RAM'];
        foreach ($categories as $category) {
            IngredientCategory::updateOrCreate([
                'name' => $category,
                'slug' => generate_slug($category),
            ]);
        }
    }
}
