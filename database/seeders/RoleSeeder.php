<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::pluck('id');

        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Admin', 'deletable' => false],
            ['name' => 'Cashier', 'slug' => 'cashier', 'description' => 'Cashier', 'deletable' => false],
            ['name' => 'Manager', 'slug' => 'manager', 'description' => 'Manager', 'deletable' => false],
        ];

        foreach ($roles as $role) {
            $rl = Role::updateOrCreate($role);

            if ($rl->slug == 'admin') {
                $rl->permissions()->sync($permissions);
            }

            $rl->staff()->updateOrCreate([
                'role_id' => $rl->id,
                'name' => $rl->name,
                'email' => "$rl->slug@gmail.com",
                'address' => 'Hello World',
                'image' => 'Hello World',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
