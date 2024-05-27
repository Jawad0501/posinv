<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            Ad::query()->create(['image' => "ad/$i.jpg"]);
        }

        \Illuminate\Support\Facades\File::copyDirectory(database_path('fakes/ads'), storage_path('app/public/ad'));
    }
}
