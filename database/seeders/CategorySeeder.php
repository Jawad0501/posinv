<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Hot Drink', 'Cake', 'Snack', 'Breakfast Items', 'Sandwich', 'Pastry', 'Coffee', 'Cold Drink', 'Pots', 'Salad', 'Promos', 'Meeting', 'Coffee & Yoghurt Deal', 'Fruit Basket', 'Hot Food', 'Soup'];
        foreach ($categories as $key => $category) {
            Category::updateOrCreate([
                'name' => $category
            ]);
        }

        \Illuminate\Support\Facades\File::copyDirectory(database_path('fakes/category'), storage_path('app/public/category'));
    }
}
