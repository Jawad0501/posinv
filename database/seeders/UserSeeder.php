<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Test User',
            'email' => 'user@gmail.com',
            'phone' => fake('en_GB')->phoneNumber(),
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
