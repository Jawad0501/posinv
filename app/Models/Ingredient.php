<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'unit_id', 'name', 'purchase_price', 'alert_qty', 'code'];

    /**
     * Get the ingredient category that owns the ingredient.
     */
    public function category()
    {
        return $this->belongsTo(IngredientCategory::class, 'category_id');
    }

    /**
     * Get the ingredient unit that owns the ingredient.
     */
    public function unit()
    {
        return $this->belongsTo(IngredientUnit::class, 'unit_id');
    }

    /**
     * Get the stock for the ingredient.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Get the purchase item for the ingredient.
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Get the stock adjustment for the ingredient.
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Get the production unit items for the ingredient.
     */
    public function productionUnitItems()
    {
        return $this->hasMany(ProductionUnitItem::class);
    }

    /**
     * Get the foods for the ingredient.
     */
    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
