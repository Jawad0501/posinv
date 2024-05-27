<?php

namespace Database\Seeders;

use App\Models\Allergy;
use Illuminate\Database\Seeder;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allergies = ['Crustaceans', 'Corn', 'Calery', 'Mustard', 'Eggs', 'Fish', 'Gluten', 'Lupin', 'Milk', 'Molluscs', 'Nuts', 'Peanuts', 'Propolis', 'Sesame', 'Soybeans', 'Sulphites'];
        foreach ($allergies as $key => $allergy) {
            Allergy::updateOrCreate([
                'name' => $allergy,
                'image' => 'allergy/'.$key + 1 .'.png',
            ]);
        }
        \Illuminate\Support\Facades\File::copyDirectory(database_path('fakes/allergy'), storage_path('app/public/allergy'));
    }
}
