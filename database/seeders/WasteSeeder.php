<?php

namespace Database\Seeders;

use App\Models\Waste;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class WasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 10; $i++) {

            $items = [];
            for ($s = 0; $s <= 5; $s++) {
                $price = rand(1, 50);
                $quantity = rand(1, 10);
                $items[$s] = ['food_id' => rand(1, 10), 'food_name' => $faker->name, 'price' => $price, 'quantity' => $quantity, 'total' => $price * $quantity];
            }

            Waste::create([
                'staff_id' => rand(1, 4),
                'reference_no' => sprintf('%s%05s', '', rand(1, 100)),
                'date' => $faker->date(),
                'note' => $faker->sentence(),
                'added_by' => 'Staff',
                'total_loss' => rand(10, 400),
                'items' => json_encode($items),
            ]);
        }

    }
}
