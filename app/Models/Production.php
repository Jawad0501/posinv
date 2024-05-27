<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = ['production_unit_id', 'production_date', 'expire_date', 'serving_unit'];

    /**
     * Get the production unit for the production.
     */
    public function unit()
    {
        return $this->belongsTo(ProductionUnit::class, 'production_unit_id');
    }
}
