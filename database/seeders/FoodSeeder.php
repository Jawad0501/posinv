<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variants = ['Normal', 'Big', 'Small', 'Medium', 'Double', 'With Fish', 'With Meat', 'With Salad'];

        $foods = [
            ['name' => 'Yoghurt Granola Pot', 'image' => 'food/1.jpg'],
            ['name' => 'Chicken Tikka Wrap', 'image' => 'food/2.jpg'],
            ['name' => 'Chicken Wings', 'image' => 'food/3.jpg'],
            ['name' => 'The Mexican Wraps', 'image' => 'food/4.jpg'],
            ['name' => 'The Portuguese Burger', 'image' => 'food/5.jpg'],
            ['name' => 'The Mexican Burger', 'image' => 'food/6.jpg'],
            ['name' => 'Fish Curry', 'image' => 'food/7.jpg'],
            ['name' => 'Tropical Smoothie', 'image' => 'food/8.jpg'],
            ['name' => 'Mixed Berries', 'image' => 'food/9.jpg'],
            ['name' => 'San Pellegrino', 'image' => 'food/10.jpeg'],
            ['name' => 'Spicy Hummus', 'image' => 'food/12.jpeg'],
            ['name' => 'Tea | English Breakfast', 'image' => 'food/13.jpeg'],
            ['name' => 'Tea | Jasmine', 'image' => 'food/14.jpg'],
            ['name' => 'Tea | Mint', 'image' => 'food/15.jpg'],
            ['name' => 'Tomato & Basil', 'image' => 'food/16.jpeg'],
            ['name' => 'Vita Coco', 'image' => 'food/17.jpg'],
            ['name' => 'Turkey & Cranberry', 'image' => 'food/18.jpg'],
            ['name' => 'Tuna Mayo', 'image' => 'food/19.jpg'],
        ];

        foreach ($foods as $item) {
            $f = Food::create(array_merge(
                $item,
                [
                    'price' => rand(5, 100),
                    'description' => fake()->text(300),
                    'calorie' => rand(1, 10),
                    'processing_time' => rand(10, 50),
                    'tax_vat' => rand(1, 10),
                ]
            ));

            for ($i = 1; $i < 2; $i++) {
                $f->variants()->updateOrCreate([
                    'name' => $variants[rand(0, 6)],
                    'price' => rand(5, 100),
                ]);
            }

            $f->categories()->sync(DB::table('categories')->inRandomOrder()->limit(3)->pluck('id'));
            $f->mealPeriods()->sync(DB::table('meal_periods')->inRandomOrder()->limit(3)->pluck('id'));
            $f->addons()->sync(DB::table('addons')->inRandomOrder()->limit(3)->pluck('id'));
            $f->allergies()->sync(DB::table('allergies')->inRandomOrder()->limit(3)->pluck('id'));
        }

        \Illuminate\Support\Facades\File::copyDirectory(database_path('fakes/food'), storage_path('app/public/food'));
    }
}
