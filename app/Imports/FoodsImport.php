<?php

namespace App\Imports;

use App\Models\Food;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FoodsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Food([
            'name' => $row['name'],
            'slug' => generate_slug($row['name']),
            'price' => $row['price'],
            'image' => $row['image'],
            'description' => $row['description'],
            'calorie' => $row['calorie'],
            'processing_time' => $row['processing_time'],
            'tax_vat' => $row['vat'],
        ]);

    }
}
