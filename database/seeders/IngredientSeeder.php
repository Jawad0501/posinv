<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientUnit;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = ['Potato', 'Goa wine', 'Beck ice', 'Salt', 'Oregano', 'Spanich', 'Daal', 'Piaz', 'Soft drink', 'Basal'];
        $categoeirs = IngredientCategory::count();
        $units = IngredientUnit::count();

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate([
                'category_id' => rand(1, $categoeirs),
                'unit_id' => rand(1, $units),
                'name' => $ingredient,
                'purchase_price' => rand(10, 500),
                'alert_qty' => rand(1, 20),
                'code' => rand(1000, 99999),
            ])
                ->stock()
                ->create(['qty_amount' => rand(1, 100)]);
        }
    }
}
