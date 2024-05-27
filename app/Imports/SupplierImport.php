<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Supplier([
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'reference' => $row['reference'],
            'address' => $row['address'],
            'id_card_front' => 'default.png',
            'id_card_back' => 'default.png',
            'status' => $row['status'] == 'Active' ? true : false,
        ]);
    }
}
