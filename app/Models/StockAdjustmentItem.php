<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    use HasFactory;

    protected $fillable = ['stock_adjustment_id', 'ingredient_id', 'quantity_amount', 'consumption_status'];

    /**
     * Get the stock adjustment that owns the stock adjustment item.
     */
    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    /**
     * Get the ingredient that owns the stock adjustment item.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
