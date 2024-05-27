<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Employee fee', 'Product Return', 'Due Payment'];
        foreach ($categories as $category) {
            ExpenseCategory::updateOrCreate(['name' => $category]);
        }
    }
}
