<?php

namespace Database\Seeders;

use App\Models\Tablelayout;
use Illuminate\Database\Seeder;

class TableLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1001; $i <= 1007; $i++) {
            $capacity = rand(1, 16);
            Tablelayout::updateOrCreate([
                'number' => $i,
                'capacity' => $capacity,
                'available' => $capacity,
                'name' => "Table no $i",
            ]);
        }
    }
}
