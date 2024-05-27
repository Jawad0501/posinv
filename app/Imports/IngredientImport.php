<?php

namespace App\Imports;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IngredientImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $category = IngredientCategory::where('name', $row['category'])->first();
        if (! $category) {
            $category = IngredientCategory::create(['name' => $row['category'], 'slug' => $row['category']]);
        }
        $unit = IngredientUnit::where('name', $row['unit'])->first();
        if (! $unit) {
            $unit = IngredientUnit::create(['name' => $row['unit'], 'slug' => $row['unit']]);
        }

        return new Ingredient([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'name' => $row['name'],
            'purchase_price' => $row['purchase_price'],
            'alert_qty' => $row['alert_qty'],
            'code' => $row['code'],
        ]);
    }
}
