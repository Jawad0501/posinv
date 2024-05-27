<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionUnit extends Model
{
    use HasFactory;

    protected $fillable = ['food_id', 'variant_id'];

    /**
     * Get the food for the production unit.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Get the variant for the production unit.
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Get the items for the production unit.
     */
    public function items()
    {
        return $this->hasMany(ProductionUnitItem::class);
    }

    /**
     * Get the productions for the production.
     */
    public function productions()
    {
        return $this->hasMany(Production::class, 'production_unit_id');
    }
}
