<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SettingSeeder::class,
            IngredientCategorySeeder::class,
            IngredientUnitSeeder::class,
            // IngredientSeeder::class,
            ExpenseCategorySeeder::class,
            IncomeCategorySeeder::class,
            // AttendanceSeeder::class,
            // MealPeriodSeeder::class,
            // CategorySeeder::class,
            // AllergySeeder::class,
            // AddonSeeder::class,
            // TableLayoutSeeder::class,
            // PurchaseSeeder::class,
            // WasteSeeder::class,
            // AskedQuestionSeeder::class,
            UserSeeder::class,
            // PageSeeder::class,
            // FoodSeeder::class,
        ]);
        \App\Models\Supplier::factory(10)->create();
        // \App\Models\Expense::factory(10)->create();
        // \App\Models\Coupon::factory(10)->create();
        // \App\Models\Subscriber::factory(10)->create();
    }
}
