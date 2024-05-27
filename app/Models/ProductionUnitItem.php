<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionUnitItem extends Model
{
    use HasFactory;

    protected $fillable = ['production_unit_id', 'ingredient_id', 'quantity', 'unit', 'price'];

    /**
     * Get the production unit for the production unit item.
     */
    public function productionUnit()
    {
        return $this->belongsTo(ProductionUnit::class);
    }

    /**
     * Get the ingredient for the production unit item.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
