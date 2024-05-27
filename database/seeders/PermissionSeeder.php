<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            'Dashboard', 'POS', 'Setting',

            // Orders Menu Items
            'Order',

            // Finance Menu Items
            'Purchase', 'Expense Category', 'Expense', 'Income', 'Bank', 'Bank Transaction',

            // Inventory Menu Items
            'Stock', 'Stock Adjustment',

            // Return
            'Return',

            // Client Menu Items
            'Customer',

            // HR Menu Items
            'Role', 'Staff',

            // Master Menu Items
            'Ingredient Category', 'Ingredient Unit', 'Ingredient', 'Supplier',

            'Report',

            'Ledger',

            'Logs',
        ];

        $permissions = ['Create', 'Show', 'Edit', 'Delete'];

        foreach ($modules as $module) {
            $mod = Module::updateOrCreate(['name' => $module]);

            foreach ($permissions as $permission) {
                $mod->permissions()->updateOrCreate([
                    'name' => "$permission $module",
                    'slug' => strtolower($permission).'_'.strtolower(str_replace(' ', '_', $module)),
                ]);
            }
        }
    }
}
