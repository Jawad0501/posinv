<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $count = 10;
        for ($i = 1; $i <= $count; $i++) {
            $total_amount = rand(100, 1000);
            Purchase::create([
                'supplier_id' => rand(1, 10),
                'reference_no' => sprintf('%s%05s', '', rand(1, $count)),
                'total_amount' => $total_amount,
                'discount_amount' => 0,
                'paid_amount' => $total_amount,
                'status' => $faker->boolean(),
                'date' => $faker->date,
                'payment_type' => ['cash payment', 'bank payment', 'due payment'][rand(0, 2)],
            ])
                ->items()
                ->create([
                    'ingredient_id' => rand(1, 10),
                    'unit_price' => rand(50, 500),
                    'quantity_amount' => rand(1, 100),
                    'total' => rand(200, 2000),
                    'expire_date' => $faker->date,
                ]);
        }
    }
}
