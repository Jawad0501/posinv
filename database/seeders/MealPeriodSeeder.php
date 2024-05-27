<?php

namespace Database\Seeders;

use App\Models\MealPeriod;
use Illuminate\Database\Seeder;

class MealPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['name' => 'Breakfast', 'time_slot' => ['start_time' => '10:00', 'end_time' => '12:00']],
            ['name' => 'Lunch', 'time_slot' => ['start_time' => '12:00', 'end_time' => '03:00']],
            ['name' => 'Dinner', 'time_slot' => ['start_time' => '03:00', 'end_time' => '6:00']],
        ];

        foreach ($menus as $menu) {
            MealPeriod::updateOrCreate($menu);
        }
    }
}
