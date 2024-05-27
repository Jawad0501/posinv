<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addons = [
            'Spicy Sriracha Mayo',
            'Waffle Fries',
            'Savoury Rice',
            'Mixed Side Salad',
            'Grilled Halloumi',
            'Sweet potato fries',
            'Garlic Sauce',
            'Fries',
            'Water',
        ];
        foreach ($addons as $addon) {
            Addon::updateOrCreate([
                'name' => $addon,
                'price' => rand(1, 15),
            ]);
        }
    }
}
