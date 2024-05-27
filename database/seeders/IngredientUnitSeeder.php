<?php

namespace Database\Seeders;

use App\Models\IngredientUnit;
use Illuminate\Database\Seeder;

class IngredientUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = ['Pcs'];

        foreach ($units as $unit) {
            IngredientUnit::updateOrCreate([
                'name' => $unit,
                'slug' => generate_slug($unit),
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            ]);
        }
    }
}
